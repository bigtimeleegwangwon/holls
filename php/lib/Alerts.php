<?php
// -*- coding: utf-8 -*-
declare(strict_types=1);

/**
 * Alerts — 미확인 이상 큐.
 *
 * Port of alerts.py.
 *
 * 서버는 메일을 보내지 않는다. 이 클래스는 "보냈나" 가 아니라 "확인했나" 축이다.
 *   이상이 잡히면 즉시 '미확인'으로 확정되고,
 *   사람이 ack 를 눌러야 빠진다.
 *
 * 저장은 append-only 원장(alerts.jsonl)이고
 * 현재 상태는 재생(replay)으로 만든다.
 */
final class Alerts
{
    // ── 상수 ──────────────────────────────────────────────────────

    public const KIND_INCIDENT = 'incident';
    public const KIND_SYSTEM   = 'system';
    public const KIND_DECISION = 'decision';   // 점검 여부가 안 정해진 채 묵는 것

    private const ALERTS_REL  = 'revision/alerts.jsonl';
    private const SEV_ORDER   = ['high' => 0, 'medium' => 1, 'low' => 2];

    // ── 스토어 헬퍼 ───────────────────────────────────────────────

    private static function pathFor(string $rel): string
    {
        return Config::dataDir() . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, $rel);
    }

    private static function appendLine(string $rel, array $row): void
    {
        $path = self::pathFor($rel);
        $dir  = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $fp = fopen($path, 'a');
        if ($fp === false) {
            throw new \RuntimeException("Cannot append to: {$path}");
        }
        flock($fp, LOCK_EX);
        fwrite($fp, json_encode($row, JSON_UNESCAPED_UNICODE) . "\n");
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    // ── 내부 유틸 ─────────────────────────────────────────────────

    private static function now(): string
    {
        return (new \DateTime('now', new \DateTimeZone('Asia/Seoul')))->format(\DATE_ATOM);
    }

    /**
     * 원장을 그대로 읽는다. 한 줄이 깨져도 나머지는 살린다.
     * @return array<array<string, mixed>>
     */
    private static function readAll(): array
    {
        $path = self::pathFor(self::ALERTS_REL);
        if (!file_exists($path)) {
            return [];
        }
        $out = [];
        $fh  = fopen($path, 'r');
        if ($fh === false) {
            return [];
        }
        while (($line = fgets($fh)) !== false) {
            $line = trim($line);
            if ($line === '') { continue; }
            $v = json_decode($line, true);
            if (is_array($v)) { $out[] = $v; }
        }
        fclose($fh);
        return $out;
    }

    /**
     * 같은 이상은 한 번만 뜬다.
     * 같은 사건이 여러 회차에 걸쳐도 하나다.
     */
    public static function alertId(string $kind, string $ref): string
    {
        return $kind . ':' . substr(sha1($ref), 0, 10);
    }

    // ── 공개 API ──────────────────────────────────────────────────

    /**
     * 이상을 올린다. 이미 미확인으로 떠 있으면 다시 올리지 않는다.
     * 종결된 것이 재발하면 막지 않는다 — 그건 새로 봐야 할 일이다.
     *
     * @return array<string, mixed>|null  저장된 행, 또는 이미 떠 있으면 null
     */
    public static function raiseAlert(
        string $kind,
        string $ref,
        string $title,
        string $severity = 'medium',
        string $detail   = '',
        string $actor    = 'collector'
    ): ?array {
        $aid    = self::alertId($kind, $ref);
        $pended = self::pending();
        foreach ($pended as $a) {
            if ($a['id'] === $aid) {
                return null;  // 이미 미확인으로 떠 있다
            }
        }
        $row = [
            'op'       => 'raise',
            'id'       => $aid,
            'kind'     => $kind,
            'ref'      => $ref,
            'title'    => $title,
            'severity' => $severity,
            'detail'   => $detail,
            'at'       => self::now(),
            'by'       => $actor,
        ];
        self::appendLine(self::ALERTS_REL, $row);
        return $row;
    }

    /**
     * 확인했다고 기록한다. 이 기록이 '봤다' 의 증거다.
     *
     * @return array<string, mixed>|null
     */
    public static function ack(string $aid, string $actor = 'unknown', string $note = ''): ?array
    {
        $aid = trim($aid);
        if ($aid === '') { return null; }
        // 미확인에 있는 것만 ack 한다
        $pended = self::pending();
        $found  = false;
        foreach ($pended as $a) {
            if ($a['id'] === $aid) { $found = true; break; }
        }
        if (!$found) { return null; }

        $row = ['op' => 'ack', 'id' => $aid, 'at' => self::now(), 'by' => $actor, 'note' => $note];
        self::appendLine(self::ALERTS_REL, $row);
        return $row;
    }

    /**
     * 상태가 풀렸으니 내린다. 사람이 확인을 누르지 않아도 된다.
     * 상태형 이상(LLM 닿지 않음 등)에 쓴다.
     *
     * @return array<string, mixed>|null
     */
    public static function resolve(
        string $kind,
        string $ref,
        string $why   = '',
        string $actor = 'system'
    ): ?array {
        $aid    = self::alertId($kind, $ref);
        $pended = self::pending();
        $found  = false;
        foreach ($pended as $a) {
            if ($a['id'] === $aid) { $found = true; break; }
        }
        if (!$found) { return null; }
        $row = ['op' => 'resolve', 'id' => $aid, 'at' => self::now(), 'by' => $actor, 'why' => $why];
        self::appendLine(self::ALERTS_REL, $row);
        return $row;
    }

    /**
     * ref 가 그 말로 시작하는 것을 모두 내린다.
     * ref 에 시각이 박히는 감시 공백 같은 이상에 쓴다.
     */
    public static function resolvePrefix(
        string $kind,
        string $prefix,
        string $why   = '',
        string $actor = 'system'
    ): int {
        $n      = 0;
        $pended = self::pending();
        foreach ($pended as $a) {
            if ($a['kind'] === $kind && str_starts_with((string) ($a['ref'] ?? ''), $prefix)) {
                self::appendLine(self::ALERTS_REL, [
                    'op'  => 'resolve',
                    'id'  => $a['id'],
                    'at'  => self::now(),
                    'by'  => $actor,
                    'why' => $why,
                ]);
                $n++;
            }
        }
        return $n;
    }

    /**
     * 지금 미확인인 것. 원장을 재생해서 만든다 — 별도 상태 파일을 두지 않는다.
     *
     * @return array<array<string, mixed>>
     */
    public static function pending(): array
    {
        $live = [];
        foreach (self::readAll() as $r) {
            $rid = $r['id'] ?? null;
            if (!$rid) { continue; }
            if ($r['op'] === 'raise') {
                $live[$rid] = $r;
            } elseif (in_array($r['op'], ['ack', 'resolve'], true)) {
                unset($live[$rid]);
            }
        }
        $out = array_values($live);
        usort($out, static function (array $a, array $b): int {
            $sa = self::SEV_ORDER[$a['severity'] ?? ''] ?? 9;
            $sb = self::SEV_ORDER[$b['severity'] ?? ''] ?? 9;
            if ($sa !== $sb) { return $sa <=> $sb; }
            return strcmp((string) ($a['at'] ?? ''), (string) ($b['at'] ?? ''));
        });
        return $out;
    }

    /**
     * 화면 상단 배너가 읽는 값. 한 줄로 줄일 수 있게 다 계산해서 준다.
     *
     * @return array<string, mixed>
     */
    public static function summary(?\DateTimeInterface $now = null): array
    {
        $now = $now ?? new \DateTime('now', new \DateTimeZone('Asia/Seoul'));
        $p   = self::pending();

        $oldest  = null;
        $ageH    = null;
        if (!empty($p)) {
            usort($p, fn($a, $b) => strcmp((string) ($a['at'] ?? ''), (string) ($b['at'] ?? '')));
            $oldest = $p[0];
            try {
                $ot   = new \DateTime((string) $oldest['at']);
                $diff = $now->getTimestamp() - $ot->getTimestamp();
                $ageH = $diff / 3600.0;
            } catch (\Exception $e) {
                $ageH = null;
            }
        }

        return [
            'count'          => count($p),
            'high'           => count(array_filter($p, fn($a) => ($a['severity'] ?? '') === 'high')),
            'incidents'      => count(array_filter($p, fn($a) => ($a['kind'] ?? '') === self::KIND_INCIDENT)),
            'system'         => count(array_filter($p, fn($a) => ($a['kind'] ?? '') === self::KIND_SYSTEM)),
            'decisions'      => count(array_filter($p, fn($a) => ($a['kind'] ?? '') === self::KIND_DECISION)),
            'oldestAt'       => $oldest ? $oldest['at'] : null,
            'oldestTitle'    => $oldest ? $oldest['title'] : null,
            'oldestAgeHours' => $ageH !== null ? round($ageH, 1) : null,
            'stale'          => ($ageH !== null && $ageH > 24),
            'checkedAt'      => $now->format(\DATE_ATOM),
        ];
    }

    /**
     * 확인까지 끝난 것 포함 전체 흐름.
     *
     * @return array<array<string, mixed>>
     */
    public static function history(int $limit = 200): array
    {
        $rows  = self::readAll();
        $acked = [];
        foreach ($rows as $r) {
            if ($r['op'] === 'ack' && !empty($r['id'])) {
                $acked[$r['id']] = $r;
            }
        }
        $out = [];
        foreach ($rows as $r) {
            if ($r['op'] !== 'raise') { continue; }
            $a     = $acked[$r['id']] ?? null;
            $out[] = array_merge($r, [
                'acked'   => (bool) $a,
                'ackedAt' => $a ? ($a['at'] ?? null) : null,
                'ackedBy' => $a ? ($a['by'] ?? null) : null,
                'ackNote' => $a ? ($a['note'] ?? '') : '',
            ]);
        }
        usort($out, fn($a, $b) => strcmp((string) ($b['at'] ?? ''), (string) ($a['at'] ?? '')));
        return array_slice($out, 0, $limit);
    }

    /**
     * 한 번이라도 올린 적이 있나.
     * raiseAlert 는 떠 있는 것만 걸러 낸다.
     * everRaised 는 확인 후 재발을 막을 때 쓴다.
     */
    public static function everRaised(string $kind, string $ref): bool
    {
        $aid = self::alertId($kind, $ref);
        foreach (self::readAll() as $r) {
            if ($r['op'] === 'raise' && ($r['id'] ?? '') === $aid) {
                return true;
            }
        }
        return false;
    }
}
