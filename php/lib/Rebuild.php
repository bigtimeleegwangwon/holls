<?php
// -*- coding: utf-8 -*-
declare(strict_types=1);

/**
 * Rebuild — mGSCS 데이터 리비전 엔진 (baseline + ledger → resolved).
 *
 * Port of rebuild.py.
 *
 * 설계 원칙
 *   baseline/   사외 제공. 사내 편집 금지. 통째 교체만.
 *   revision/   사내 전용. append-only. 삭제 없음.
 *   build/      산출물. 매 실행 재생성. 손편집 금지.
 *
 * 이 클래스는 순수 함수처럼 동작한다.
 * 같은 baseline + 같은 ledger 는 항상 같은 결과를 낸다.
 *
 * 지리 참조 의존 (선택):
 *   GeoRef::resolveAdmin1(country, raw, admin1Ko, address)  → string|null
 *   GeoRef::regionFor(country, admin1)                      → string|null
 *   GeoRef::nameKo()                                        → array<string, array{string, string}>
 */
final class Rebuild
{
    // ── 상수 ──────────────────────────────────────────────────────

    // 유효한 원장 op 집합
    public const OPS = [
        'bp.add', 'bp.update', 'bp.retire',
        'site.add', 'site.update', 'site.retire',
        'coord.verify', 'hotline.set', 'hotline.bulk', 'alias.add', 'note',
        'bp.source',
        'hotline.clear',
    ];

    // 좌표 신뢰등급 — 높을수록 정밀하다. 머지 시 높은 쪽이 이긴다.
    private const PRECISION_RANK = [
        'verified_site'      => 40,
        'address_geocoded'   => 30,
        'city_centroid'      => 25,
        'prefecture_centroid'=> 20,
        'country_approx'     => 10,
        'unknown'            => 0,
    ];

    private const EXTERNAL_PREFIX      = 'X-';
    private const INTERNAL_PREFIX      = 'I-';
    private const HOTLINE_HISTORY_KEEP = 20;

    // ── 스토어 헬퍼 ───────────────────────────────────────────────

    private static function dataDir(): string
    {
        return Config::dataDir();
    }

    private static function pathFor(string $rel): string
    {
        return self::dataDir() . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, $rel);
    }

    private static function loadJson(string $path, mixed $default = null): mixed
    {
        if (!file_exists($path)) {
            return $default;
        }
        $raw = file_get_contents($path);
        if ($raw === false) {
            return $default;
        }
        $v = json_decode($raw, true);
        return ($v !== null) ? $v : $default;
    }

    private static function writeJson(string $rel, mixed $data): void
    {
        $path = self::pathFor($rel);
        $dir  = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        $tmp  = $path . '.tmp.' . getmypid();
        $fp   = fopen($tmp, 'w');
        if ($fp === false) {
            throw new \RuntimeException("Cannot write: {$tmp}");
        }
        flock($fp, LOCK_EX);
        fwrite($fp, (string) $json);
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
        rename($tmp, $path);
    }

    // ── 낮은 수준 유틸 ────────────────────────────────────────────

    private static function nowKst(): string
    {
        return (new \DateTime('now', new \DateTimeZone('Asia/Seoul')))->format(\DATE_ATOM);
    }

    private static function siteKey(array $row): string
    {
        return (string) ($row['siteId'] ?? $row['recordId'] ?? '');
    }

    /**
     * 별칭 비교용 키 — 띄어쓰기·기호·대소문자를 없앤다.
     */
    private static function akey(mixed $s): string
    {
        return preg_replace('/[\s·・.,\-_\/&()（）]+/u', '', strtolower((string) $s)) ?? '';
    }

    /**
     * 회사 식별 키. baseline 과 revision 을 잇는 조인 키다.
     */
    private static function bpKeyOf(array $row): string
    {
        return strtolower(trim((string) ($row['bpKey'] ?? $row['bpName'] ?? $row['bpId'] ?? '')));
    }

    /**
     * 원장이 손댄 흔적을 남긴다.
     * 새로 만든 행이면 출처가 곧 원장이다. 이미 있던 행이면 뒤에 덧붙인다.
     */
    private static function stamp(array &$row, string $prov, array $origin): void
    {
        $cur = $row['provenance'] ?? null;
        if (is_string($cur) && trim($cur) !== '' && $cur !== $prov) {
            $row['provenance'] = $cur . ' / ' . $prov;
        } else {
            $row['provenance'] = $prov;
        }
        $row['origin'] = $origin;
    }

    /**
     * 이력에 남길 연락처 한 줄 요약 — 담당자 수와 대표 한 명.
     */
    private static function hotlineSummary(array $row): array
    {
        $cs   = $row['contacts'] ?? [];
        $h    = $row['hotline'] ?? [];
        $lead = $h['contactName'] ?? ($cs[0]['name'] ?? '') ?? '';
        $mail = $h['email'] ?? ($cs[0]['email'] ?? '') ?? '';
        $hHas = array_filter(array_map(
            fn($k) => trim((string) ($h[$k] ?? '')),
            ['contactName', 'phone', 'email']
        ));
        $n = count($cs) ?: (count($hHas) ? 1 : 0);
        return ['n' => $n, 'lead' => (string) $lead, 'email' => (string) $mail];
    }

    /**
     * 누가·언제·무엇을·왜 바꿨나를 그 행에 한 줄 쌓는다.
     */
    private static function hotlineHistory(
        array &$row, string $op, string $ts, string $actor, string $reason, array $before
    ): void {
        $hist   = array_values($row['hotlineHistory'] ?? []);
        $hist[] = [
            'ts'     => $ts,
            'actor'  => $actor,
            'op'     => $op,
            'reason' => $reason,
            'before' => $before,
            'after'  => self::hotlineSummary($row),
        ];
        $row['hotlineHistory'] = array_slice($hist, -self::HOTLINE_HISTORY_KEEP);
    }

    // ── 원장 읽기 ─────────────────────────────────────────────────

    /**
     * append-only 원장 읽기. 깨진 줄은 버리지 않고 오류로 보고한다.
     *
     * @return array{0: array{int, array}[], 1: string[]}  [events, errors]
     */
    public static function loadLedger(string $path): array
    {
        $events = [];
        $errors = [];
        if (!file_exists($path)) {
            return [$events, $errors];
        }

        $opsSet = array_flip(self::OPS);
        $fh     = fopen($path, 'r');
        if ($fh === false) {
            $errors[] = "원장 파일을 열 수 없습니다: {$path}";
            return [$events, $errors];
        }
        $lineno = 0;
        while (($line = fgets($fh)) !== false) {
            $lineno++;
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '//')) {
                continue;
            }
            $ev = json_decode($line, true);
            if (!is_array($ev)) {
                $errors[] = "{$lineno}행 JSON 오류: " . json_last_error_msg();
                continue;
            }
            if (!isset($opsSet[$ev['op'] ?? ''])) {
                $errors[] = "{$lineno}행 알 수 없는 op: " . ($ev['op'] ?? '(없음)');
                continue;
            }
            $events[] = [$lineno, $ev];
        }
        fclose($fh);
        return [$events, $errors];
    }

    // ── 사건 검증 ─────────────────────────────────────────────────

    /**
     * 검증 게이트. 통과 못 하면 그 사건만 건너뛰고 나머지는 살린다.
     *
     * @return string[]  문제 목록. 비면 통과.
     */
    public static function validateEvent(array &$ev): array
    {
        $op       = $ev['op'] ?? '';
        $problems = [];

        // 조인 키를 여기서 한 번만 다듬는다.
        if (isset($ev['bpKey'])) {
            $ev['bpKey'] = strtolower(trim((string) $ev['bpKey']));
        }

        // site.* / coord.verify
        if (str_starts_with($op, 'site.') || $op === 'coord.verify') {
            if (empty($ev['siteId'])) {
                $problems[] = 'siteId 누락';
            } elseif (str_starts_with((string) $ev['siteId'], self::EXTERNAL_PREFIX) && $op === 'site.add') {
                $problems[] = '사내는 ' . self::EXTERNAL_PREFIX . ' 접두어를 발급할 수 없다';
            }
        }

        // alias.add
        if ($op === 'alias.add') {
            $al = ($ev['after'] ?? [])['aliases'] ?? null;
            if (!is_array($al) || empty(array_filter($al, fn($a) => trim((string) $a) !== ''))) {
                $problems[] = 'alias.add — aliases 배열이 필요하다';
            }
        }

        // bp.source
        if ($op === 'bp.source') {
            $srcs = ($ev['after'] ?? [])['sources'] ?? null;
            if (!is_array($srcs) || empty($srcs)) {
                $problems[] = 'bp.source — sources 배열이 필요하다';
            } else {
                foreach ($srcs as $i => $c) {
                    if (!is_array($c) || empty(trim((string) ($c['url'] ?? '')))) {
                        $problems[] = "bp.source — {$i}번째에 url 이 없다";
                        break;
                    }
                    $url = strtolower((string) $c['url']);
                    if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
                        $problems[] = "bp.source — {$i}번째가 주소 형식이 아니다";
                        break;
                    }
                }
            }
        }

        // hotline.bulk
        if ($op === 'hotline.bulk') {
            $cs = ($ev['after'] ?? [])['contacts'] ?? null;
            if (!is_array($cs) || empty($cs)) {
                $problems[] = 'hotline.bulk — contacts 배열이 필요하다 (지우려면 hotline.clear)';
            } else {
                foreach ($cs as $i => $c) {
                    if (!is_array($c)) {
                        $problems[] = "hotline.bulk — {$i}번째가 객체가 아니다";
                        break;
                    }
                    $hasAny = array_filter(
                        array_map(fn($k) => trim((string) ($c[$k] ?? '')), ['name', 'phone', 'email'])
                    );
                    if (empty($hasAny)) {
                        $problems[] = "hotline.bulk — {$i}번째에 이름·전화·메일이 모두 없다";
                        break;
                    }
                }
            }
        }

        // hotline.set
        if ($op === 'hotline.set') {
            $h    = $ev['after'] ?? [];
            $vals = array_filter(array_map(
                fn($k) => trim((string) ($h[$k] ?? '')),
                ['contactName', 'phone', 'email']
            ));
            if (empty($vals)) {
                $problems[] = 'hotline.set — 담당자·전화·이메일 중 최소 하나는 있어야 한다';
            }
        }

        // hotline.clear
        if ($op === 'hotline.clear' && trim((string) ($ev['reason'] ?? '')) === '') {
            $problems[] = 'hotline.clear — 지우는 이유를 반드시 적어야 한다';
        }

        // bpKey / siteId 필요
        $needsBpOrSite = in_array($op, [
            'bp.add', 'bp.update', 'bp.retire',
            'hotline.set', 'hotline.bulk', 'hotline.clear', 'alias.add',
        ], true);
        if ($needsBpOrSite && empty($ev['bpKey']) && empty($ev['siteId'])) {
            $problems[] = 'bpKey 또는 siteId 가 필요하다';
        }

        // 좌표 검증
        $after = $ev['after'] ?? [];
        foreach (['lat', 'lon'] as $k) {
            if (array_key_exists($k, $after) && $after[$k] !== null) {
                if (!is_numeric($after[$k])) {
                    $problems[] = "{$k} 가 숫자가 아니다";
                } else {
                    $v     = (float) $after[$k];
                    $limit = ($k === 'lat') ? 90 : 180;
                    if (abs($v) > $limit) {
                        $problems[] = "{$k} 범위 초과({$v})";
                    }
                }
            }
        }

        if (($after['coordinateStatus'] ?? null) === 'verified_site') {
            if (!isset($after['lat']) || !isset($after['lon'])) {
                $problems[] = 'verified_site 인데 좌표가 없다';
            }
        }

        // bp.add 필수 항목
        if ($op === 'bp.add') {
            if (empty($after['bpName']))        { $problems[] = 'bpName 누락'; }
            if (empty($after['country']))       { $problems[] = 'country 누락'; }
            if (empty($after['materialGroups'])){ $problems[] = 'materialGroups 누락'; }
        }

        // reason 필수
        if (trim((string) ($ev['reason'] ?? '')) === '') {
            $problems[] = 'reason 누락 — 왜 바꿨는지 남겨야 한다';
        }

        return $problems;
    }

    // ── 재생 엔진 ─────────────────────────────────────────────────

    /**
     * baseline 위에 ledger 이벤트를 순서대로 replay 한다.
     *
     * @param array<array<string, mixed>> $baselineRows
     * @param array{int, array}[]         $events        loadLedger 결과
     * @return array{
     *   0: array<array<string, mixed>>,  resolved rows
     *   1: string[],                     applied log
     *   2: string[],                     conflict log
     *   3: string[]                      skipped log
     * }
     */
    public static function applyEvents(array $baselineRows, array $events): array
    {
        $rows      = [];
        $applied   = [];
        $conflicts = [];
        $skipped   = [];

        // Baseline 로드
        foreach ($baselineRows as $r) {
            $r = array_merge($r, ['origin' => $r['origin'] ?? ['layer' => 'baseline']]);
            $k = self::siteKey($r) ?: ('__b' . count($rows));
            if (isset($rows[$k])) {
                $conflicts[] =
                    "baseline siteId 중복 {$k} — 뒤에 온 행이 앞의 행을 덮어 앞의 것이 사라진다"
                    . " (앞 '{$rows[$k]['siteName']}' / 뒤 '{$r['siteName']}')";
            }
            $rows[$k] = $r;
        }

        $retiredBp = [];

        foreach ($events as [$lineno, $ev]) {
            $problems = self::validateEvent($ev);
            if (!empty($problems)) {
                $skipped[] = "{$lineno}행 [{$ev['op']}] " . implode(' · ', $problems);
                continue;
            }

            $op    = $ev['op'];
            $after = $ev['after'] ?? [];
            $actor = $ev['actor'] ?? '';
            $ts    = $ev['ts'] ?? '';
            $sid   = (string) ($ev['siteId'] ?? '');
            $rsn   = (string) ($ev['reason'] ?? '');

            $origin = ['layer' => 'revision', 'actor' => $actor, 'ts' => $ts, 'ledgerLine' => $lineno];
            $datePart = $ts ? substr($ts, 0, 10) : '날짜 미상';
            $prov  = "리비전 원장 · {$datePart} · {$lineno}행" . ($rsn ? " · {$rsn}" : '');

            // ── note ──
            if ($op === 'note') {
                $applied[] = "{$lineno}행 note — {$rsn}";
                continue;
            }

            // ── bp.retire ──
            if ($op === 'bp.retire') {
                $retiredBp[strtolower(trim((string) ($ev['bpKey'] ?? '')))] = true;
                $applied[] = "{$lineno}행 bp.retire " . ($ev['bpKey'] ?? '');
                continue;
            }

            // ── site.retire ──
            if ($op === 'site.retire') {
                if (isset($rows[$sid])) {
                    $rows[$sid]['active'] = false;
                    self::stamp($rows[$sid], $prov, $origin);
                    $applied[] = "{$lineno}행 site.retire {$sid}";
                } else {
                    $skipped[] = "{$lineno}행 site.retire — 존재하지 않는 siteId {$sid}";
                }
                continue;
            }

            // ── site.add ──
            if ($op === 'site.add') {
                if (isset($rows[$sid])) {
                    $conflicts[] = "{$lineno}행 site.add — 이미 있는 siteId {$sid}, update 로 처리";
                    $rows[$sid]  = array_merge($rows[$sid], $after);
                    self::stamp($rows[$sid], $prov, $origin);
                } else {
                    $row               = $after;
                    $row['siteId']     = $sid;
                    $row['recordId']   = $sid;
                    $row['active']     = $row['active'] ?? true;
                    $row['hotline']    = $row['hotline'] ?? ['contactName' => '', 'phone' => '', 'email' => ''];
                    $row['contacts']   = $row['contacts'] ?? [];
                    $row['bpKey']      = $ev['bpKey'] ?? '';
                    $row['provenance'] = $prov;
                    $row['origin']     = $origin;
                    $rows[$sid]        = $row;
                    $applied[] = "{$lineno}행 site.add {$sid} (" . ($after['siteName'] ?? '') . ')';
                }
                continue;
            }

            // ── site.update / coord.verify ──
            if ($op === 'site.update' || $op === 'coord.verify') {
                if (!isset($rows[$sid])) {
                    $skipped[] = "{$lineno}행 {$op} — 존재하지 않는 siteId {$sid}";
                    continue;
                }
                $cur = &$rows[$sid];
                if ($op === 'coord.verify') {
                    $newRank = self::PRECISION_RANK[$after['coordinateStatus'] ?? ''] ?? 0;
                    $curRank = self::PRECISION_RANK[$cur['coordinateStatus'] ?? ''] ?? 0;
                    if ($newRank < $curRank) {
                        $conflicts[] = "{$lineno}행 coord.verify — 현재 {$cur['coordinateStatus']} 가 더 정밀해 무시";
                        continue;
                    }
                    $cur['distanceEligible'] = ($after['coordinateStatus'] ?? '') === 'verified_site';
                }
                $cur = array_merge($cur, $after);
                self::stamp($cur, $prov, $origin);
                $applied[] = "{$lineno}행 {$op} {$sid}";
                unset($cur);
                continue;
            }

            // ── bp.source ──
            if ($op === 'bp.source') {
                $bk      = strtolower(trim((string) ($ev['bpKey'] ?? '')));
                $targets = array_keys(array_filter($rows, fn($r) => self::bpKeyOf($r) === $bk));
                if (empty($targets)) {
                    $skipped[] = "{$lineno}행 bp.source — 대상 BP 없음 " . ($ev['bpKey'] ?? '');
                    continue;
                }
                $srcs = [];
                foreach ($after['sources'] ?? [] as $c) {
                    $u = trim((string) ($c['url'] ?? ''));
                    if ($u) {
                        $srcs[] = [
                            'url'       => $u,
                            'title'     => trim((string) ($c['title'] ?? '')),
                            'tier'      => trim((string) ($c['tier'] ?? '')),
                            'checkedAt' => trim((string) ($c['checkedAt'] ?? '')),
                            'evidence'  => trim((string) ($c['evidence'] ?? 'SEED_ONLY')),
                            'caution'   => trim((string) ($c['caution'] ?? '')),
                        ];
                    }
                }
                foreach ($targets as $t) {
                    $have = array_flip(array_column($rows[$t]['sources'] ?? [], 'url'));
                    $add  = array_filter($srcs, fn($x) => !isset($have[$x['url']]));
                    $rows[$t]['sources'] = array_merge($rows[$t]['sources'] ?? [], array_values($add));
                    self::stamp($rows[$t], $prov, $origin);
                }
                $applied[] = "{$lineno}행 bp.source " . ($ev['bpKey'] ?? '')
                    . ' → ' . count($targets) . '곳 · 출처 ' . count($srcs) . '건';
                continue;
            }

            // ── alias.add ──
            if ($op === 'alias.add') {
                $bk      = strtolower(trim((string) ($ev['bpKey'] ?? '')));
                $targets = array_keys(array_filter($rows, fn($r) => self::bpKeyOf($r) === $bk));
                if (empty($targets)) {
                    $skipped[] = "{$lineno}행 alias.add — 대상 BP 없음 " . ($ev['bpKey'] ?? '');
                    continue;
                }
                // 한 이름이 두 회사를 가리키면 막는다.
                $taken = [];
                foreach ($rows as $r) {
                    foreach (array_merge([$r['bpName'] ?? null], $r['bpAliases'] ?? []) as $nm) {
                        if ($nm) {
                            $taken[self::akey($nm)][] = $r['bpName'] ?? '';
                        }
                    }
                }
                $added = [];
                foreach ($after['aliases'] ?? [] as $a) {
                    $a = trim((string) $a);
                    if (!$a) { continue; }
                    $owners = array_unique($taken[self::akey($a)] ?? []);
                    $myBp   = $rows[$targets[0]]['bpName'] ?? '';
                    if (!empty($owners) && !in_array($myBp, $owners, true)) {
                        $conflicts[] = "{$lineno}행 alias.add — '{$a}' 는 이미 " . $owners[0] . " 의 이름";
                        continue;
                    }
                    $added[] = $a;
                }
                foreach ($targets as $t) {
                    $cur = array_values($rows[$t]['bpAliases'] ?? []);
                    foreach ($added as $a) {
                        $exists = array_filter($cur, fn($x) => self::akey($x) === self::akey($a));
                        if (empty($exists)) { $cur[] = $a; }
                    }
                    $rows[$t]['bpAliases'] = $cur;
                }
                if (!empty($added)) {
                    $applied[] = "{$lineno}행 alias.add " . ($ev['bpKey'] ?? '')
                        . ' ← ' . implode(', ', $added);
                }
                continue;
            }

            // ── hotline.bulk ──
            if ($op === 'hotline.bulk') {
                $bk      = strtolower(trim((string) ($ev['bpKey'] ?? '')));
                $targets = $sid ? (isset($rows[$sid]) ? [$sid] : [])
                    : array_keys(array_filter($rows, fn($r) => self::bpKeyOf($r) === $bk));
                if (empty($targets)) {
                    $skipped[] = "{$lineno}행 hotline.bulk — 대상 없음 " . ($ev['bpKey'] ?? $sid);
                    continue;
                }
                $cs = [];
                foreach ($after['contacts'] ?? [] as $c) {
                    $cs[] = [
                        'name'           => trim((string) ($c['name'] ?? '')),
                        'phone'          => trim((string) ($c['phone'] ?? '')),
                        'email'          => trim((string) ($c['email'] ?? '')),
                        'materialGroups' => $c['materialGroups'] ?? [],
                        'dept'           => trim((string) ($c['dept'] ?? '')),
                        'bpSelfName'     => trim((string) ($c['bpSelfName'] ?? '')),
                        'bpCode'         => trim((string) ($c['bpCode'] ?? '')),
                        'sent'           => trim((string) ($c['sent'] ?? '')),
                    ];
                }
                foreach ($targets as $t) {
                    $before               = self::hotlineSummary($rows[$t]);
                    $rows[$t]['contacts'] = $cs;
                    $first                = $cs[0] ?? [];
                    $rows[$t]['hotline']  = [
                        'contactName' => $first['name'] ?? '',
                        'phone'       => $first['phone'] ?? '',
                        'email'       => $first['email'] ?? '',
                        'verifiedAt'  => $ts ? substr($ts, 0, 10) : '',
                    ];
                    $rows[$t]['origin'] = $origin;
                    self::hotlineHistory($rows[$t], $op, $ts, $actor, $rsn, $before);
                }
                $applied[] = "{$lineno}행 hotline.bulk " . ($ev['bpKey'] ?? '')
                    . ' → ' . count($targets) . '건 · 담당자 ' . count($cs) . '명';
                continue;
            }

            // ── hotline.set ──
            if ($op === 'hotline.set') {
                $bk      = strtolower(trim((string) ($ev['bpKey'] ?? '')));
                $targets = $sid ? (isset($rows[$sid]) ? [$sid] : [])
                    : array_keys(array_filter($rows, fn($r) => self::bpKeyOf($r) === $bk));
                if (empty($targets)) {
                    $skipped[] = "{$lineno}행 hotline.set — 대상 없음 " . ($ev['bpKey'] ?? $sid);
                    continue;
                }
                foreach ($targets as $t) {
                    $before              = self::hotlineSummary($rows[$t]);
                    $rows[$t]['hotline'] = [
                        'contactName' => $after['contactName'] ?? '',
                        'phone'       => $after['phone'] ?? '',
                        'email'       => $after['email'] ?? '',
                        'verifiedAt'  => $after['verifiedAt'] ?? ($ts ? substr($ts, 0, 10) : ''),
                    ];
                    $rows[$t]['origin'] = $origin;
                    self::hotlineHistory($rows[$t], $op, $ts, $actor, $rsn, $before);
                }
                $applied[] = "{$lineno}행 hotline.set → " . count($targets) . '건';
                continue;
            }

            // ── hotline.clear ──
            if ($op === 'hotline.clear') {
                $bk      = strtolower(trim((string) ($ev['bpKey'] ?? '')));
                $targets = $sid ? (isset($rows[$sid]) ? [$sid] : [])
                    : array_keys(array_filter($rows, fn($r) => self::bpKeyOf($r) === $bk));
                if (empty($targets)) {
                    $skipped[] = "{$lineno}행 hotline.clear — 대상 없음 " . ($ev['bpKey'] ?? $sid);
                    continue;
                }
                foreach ($targets as $t) {
                    $before               = self::hotlineSummary($rows[$t]);
                    $rows[$t]['contacts'] = [];
                    $rows[$t]['hotline']  = ['contactName' => '', 'phone' => '', 'email' => '', 'verifiedAt' => ''];
                    $rows[$t]['origin']   = $origin;
                    self::hotlineHistory($rows[$t], $op, $ts, $actor, $rsn, $before);
                }
                $applied[] = "{$lineno}행 hotline.clear " . ($ev['bpKey'] ?? $sid)
                    . ' → ' . count($targets) . '건 비움 · ' . $rsn;
                continue;
            }

            // ── bp.add / bp.update ──
            if ($op === 'bp.add' || $op === 'bp.update') {
                $bk = strtolower(trim((string) ($ev['bpKey'] ?? '')));
                if ($op === 'bp.add') {
                    $sid2 = $sid ?: (self::INTERNAL_PREFIX
                        . strtoupper($after['country'] ?? 'XX') . '-'
                        . strtoupper(substr($bk, 0, 12)) . '-01');
                    if (isset($rows[$sid2])) {
                        $conflicts[] = "{$lineno}행 bp.add — 이미 있는 {$sid2}";
                        continue;
                    }
                    $row = array_merge($after, [
                        'siteId'     => $sid2,
                        'recordId'   => $sid2,
                        'bpKey'      => $bk,
                        'active'     => true,
                        'provenance' => $prov,
                        'origin'     => $origin,
                    ]);
                    $row['hotline']  = $row['hotline'] ?? ['contactName' => '', 'phone' => '', 'email' => ''];
                    $row['contacts'] = $row['contacts'] ?? [];
                    $rows[$sid2]     = $row;
                    $applied[] = "{$lineno}행 bp.add " . ($after['bpName'] ?? '') . " → {$sid2}";
                } else {
                    $hit = array_keys(array_filter($rows, fn($r) => self::bpKeyOf($r) === $bk));
                    if (empty($hit)) {
                        $skipped[] = "{$lineno}행 bp.update — 대상 BP 없음 {$bk}";
                        continue;
                    }
                    $beforeKeys = [];
                    foreach ($hit as $h) { $beforeKeys[$h] = self::bpKeyOf($rows[$h]); }
                    foreach ($hit as $h) {
                        $rows[$h] = array_merge($rows[$h], $after);
                        self::stamp($rows[$h], $prov, $origin);
                    }
                    $applied[] = "{$lineno}행 bp.update {$bk} → " . count($hit) . '건';

                    $moved  = [];
                    $stayed = [];
                    foreach ($hit as $h) {
                        $newKey = self::bpKeyOf($rows[$h]);
                        if ($newKey !== $beforeKeys[$h]) { $moved[]  = $newKey; }
                        else                             { $stayed[] = $beforeKeys[$h]; }
                    }
                    $moved  = array_unique($moved);
                    $stayed = array_unique($stayed);
                    if (!empty($moved)) {
                        $movedStr  = implode(', ', array_map(fn($m) => "'{$m}'", $moved));
                        $msg       = "{$lineno}행 bp.update — 조인 키가 '{$bk}' → {$movedStr} 로 바뀌었다. 이 줄 뒤의 op 는 그 키로 적어야 한다";
                        if (!empty($stayed)) {
                            $stayedStr = implode(', ', array_map(fn($s) => "'{$s}'", $stayed));
                            $msg .= " (다만 {$stayedStr} 는 bpKey 를 들고 있어 그대로다 — 두 키로 나눠 적어야 한다)";
                        }
                        $conflicts[] = $msg;
                    }
                }
                continue;
            }
        }  // foreach events

        // 사내 retire 는 baseline 부활을 이긴다
        foreach ($rows as &$r) {
            if (isset($retiredBp[self::bpKeyOf($r)])) {
                $r['active'] = false;
            }
        }
        unset($r);

        return [array_values($rows), $applied, $conflicts, $skipped];
    }

    // ── 공개 진입점 ───────────────────────────────────────────────

    /**
     * baseline + ledger → resolved. 행정구역 정본화 포함.
     *
     * @return array{rows: array, stats: array, applied: string[], conflicts: string[], skipped: string[], errors: string[], regionFilled: int}
     */
    public static function rebuild(?string $root = null): array
    {
        $baselinePath = self::pathFor('baseline/bp_sites.json');
        $ledgerPath   = self::pathFor('revision/ledger.jsonl');
        $outDir       = self::pathFor('build');

        if (!is_dir($outDir)) {
            mkdir($outDir, 0755, true);
        }

        $baseline = self::loadJson($baselinePath, []);
        if (!is_array($baseline)) {
            throw new \RuntimeException("baseline 을 찾을 수 없습니다: {$baselinePath}");
        }

        [$events, $loadErrors] = self::loadLedger($ledgerPath);
        [$rows, $applied, $conflicts, $skipped] = self::applyEvents($baseline, $events);

        // 행정구역 정본화 + regionCode 보충
        $canonFixed    = 0;
        $regionFilled  = 0;
        $hasGeoRef     = class_exists('GeoRef', false);
        $nameKo        = $hasGeoRef ? GeoRef::nameKo() : [];

        foreach ($rows as &$r) {
            if ($hasGeoRef) {
                $raw   = $r['admin1'] ?? null;
                $canon = GeoRef::resolveAdmin1(
                    (string)($r['country'] ?? ''),
                    ...array_values(array_filter(
                        [(string)($raw ?? ''), (string)($r['admin1Ko'] ?? ''), (string)($r['address'] ?? '')],
                        fn($x) => $x !== ''
                    ))
                );
                if ($canon && $canon !== $raw) {
                    $r['admin1Raw'] = $raw;
                    $r['admin1']    = $canon;
                    $canonFixed++;
                }
                if (empty($r['regionCode'])) {
                    $rc = GeoRef::regionFor(isset($r['country']) ? (string)$r['country'] : null, isset($r['admin1']) ? (string)$r['admin1'] : null);
                    if ($rc) {
                        [$en, $ko]       = $nameKo[$rc] ?? ['', ''];
                        $r['regionCode'] = $rc;
                        $r['region']     = $r['region'] ?? $en;
                        $r['regionKo']   = $r['regionKo'] ?? $ko;
                        $regionFilled++;
                    }
                }
            }
        }
        unset($r);

        self::writeJson('build/bp_resolved.json', $rows);

        $stats = self::summarise($rows);
        $ts    = self::nowKst();

        $report   = ["# BUILD REPORT", "", "생성 {$ts} KST",
            "baseline " . count($baseline) . "행 + ledger " . count($events) . "건 → resolved " . count($rows) . "행",
            "행정구역 정본화 {$canonFixed}행 · regionCode 보충 {$regionFilled}행",
            "", "## 현황"];
        foreach ($stats as $k => $v) { $report[] = "- {$k}: {$v}"; }

        foreach ([['적용', $applied], ['충돌', $conflicts], ['건너뜀', $skipped], ['원장 오류', $loadErrors]] as [$title, $items]) {
            $report[] = '';
            $report[] = "## {$title} (" . count($items) . "건)";
            $report   = array_merge($report, !empty($items)
                ? array_map(fn($x) => "- {$x}", $items)
                : ['- 없음']);
        }
        $report = array_merge($report, ['', '## 주의',
            '- 이 파일과 bp_resolved.json 은 재생성물이다. 손으로 고치지 말 것.',
            '- 데이터를 바꾸려면 revision/ledger.jsonl 에 사건을 추가하고 다시 rebuild 한다.']);

        $rptPath = $outDir . DIRECTORY_SEPARATOR . 'BUILD_REPORT.md';
        file_put_contents($rptPath, implode("\n", $report) . "\n");

        return [
            'rows'         => $rows,
            'stats'        => $stats,
            'applied'      => $applied,
            'conflicts'    => $conflicts,
            'skipped'      => $skipped,
            'errors'       => $loadErrors,
            'regionFilled' => $regionFilled,
        ];
    }

    /**
     * bp_resolved.json 을 읽어 반환한다.
     *
     * @return array<array<string, mixed>>
     */
    public static function loadResolved(): array
    {
        $path = self::pathFor('build/bp_resolved.json');
        $data = self::loadJson($path, []);
        return is_array($data) ? $data : [];
    }

    /**
     * 연락처 필드를 제거한 공개 가능한 행을 반환한다.
     * Python 의 public_rows() / strip_contacts() 에 해당한다.
     *
     * @param array<array<string, mixed>> $rows
     * @return array<array<string, mixed>>
     */
    public static function publicRows(array $rows): array
    {
        static $keep = [
            'siteId', 'recordId', 'bpName', 'bpKey', 'country', 'admin1', 'admin1Ko',
            'prefecture', 'prefectureKo', 'address', 'lat', 'lon', 'coordinateStatus',
            'distanceEligible', 'materialGroups', 'grade', 'active', 'regionCode',
            'region', 'regionKo', 'matchType', 'matchLabel', 'referenceDistanceKm',
            'referenceDistanceText', 'provenance', 'origin', 'bpAliases', 'sources',
        ];
        $keepSet = array_flip($keep);
        $out = [];
        foreach ($rows as $r) {
            $out[] = array_intersect_key($r, $keepSet);
        }
        return $out;
    }

    // ── 통계 ──────────────────────────────────────────────────────

    private static function summarise(array $rows): array
    {
        $active   = array_filter($rows, fn($r) => ($r['active'] ?? true) !== false);
        $verified = array_filter($active, fn($r) => ($r['coordinateStatus'] ?? '') === 'verified_site');
        $hot      = array_filter($active, fn($r) => !empty(array_filter($r['hotline'] ?? [])));
        $internal = array_filter($active, fn($r) => str_starts_with((string) ($r['siteId'] ?? ''), self::INTERNAL_PREFIX));
        $aCount   = count($active);

        return [
            '전체'                 => count($rows),
            '활성'                 => $aCount,
            '회사'                 => count(array_unique(array_column(array_values($active), 'bpName'))),
            '정밀좌표(verified_site)'=> count($verified),
            '거리계산 자격'          => count(array_filter($active, fn($r) => !empty($r['distanceEligible']))),
            '연락처 채움'            => count($hot),
            '연락처 채움률'          => $aCount ? round(count($hot) / $aCount * 100, 1) . '%' : '0%',
            '사내 추가분'            => count($internal),
        ];
    }
}
