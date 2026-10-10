<?php
// -*- coding: utf-8 -*-
declare(strict_types=1);

/**
 * Inspection — 점검 요청 (사건 → 메일 초안, 점검 이력).
 *
 * Port of inspection.py.
 *
 * 경계를 분명히 한다.
 *   사외 알림   사건 판정 + 대상 BP 명단           (자동)
 *   사내 화면   hotline 연결 · 메일 양식 생성       (자동)
 *   사람        양식 검토 → 수동 발송 → 회신 취합    (사람)
 *
 * 발송을 자동화하지 않는다. 이 클래스는 메일 초안까지만 만들고 보내지 않는다.
 * 보내는 코드는 없다.
 *
 * 지리 참조 의존 (선택):
 *   GeoRef::resolveAdmin1(country, name)
 *   GeoRef::adminAreaCandidates(country, lat, lon)
 */
final class Inspection
{
    // ── 점검 상태 ────────────────────────────────────────────────
    public const STATUS = [
        'draft'   => '양식 생성',
        'sent'    => '발송함',
        'replied' => '회신 받음',
        'closed'  => '종결',
        'skipped' => '해당 없음',
        'bounced' => '반송',
    ];

    // 점검을 안 한 이유 코드
    public const SKIP_REASONS = [
        'no_contact'    => ['label' => '연락처가 없다',               'next' => '연락처 채우기 목록으로'],
        'no_impact'     => ['label' => '그 사업장은 해당 소재를 안 만든다', 'next' => '명부의 소재군을 고친다'],
        'already_known' => ['label' => '다른 경로로 이미 확인했다',       'next' => '-'],
        'low_severity'  => ['label' => '영향이 경미하다',               'next' => '판정 임계값을 본다'],
        'covered'       => ['label' => '같은 사건에서 이미 다뤘다',       'next' => '중복 대상 묶기'],
        'other'         => ['label' => '그 밖 (내용을 적는다)',          'next' => '쌓이면 코드로 승격'],
    ];

    // 반송 이유 코드
    public const BOUNCE_REASONS = [
        'unknown_address' => ['label' => '없는 주소',           'next' => '연락처를 다시 받는다'],
        'mailbox_full'    => ['label' => '사서함이 찼다',        'next' => '다시 시도하거나 전화'],
        'rejected'        => ['label' => '수신 거부됐다',        'next' => '다른 담당자를 찾는다'],
        'other'           => ['label' => '그 밖 (내용을 적는다)', 'next' => '-'],
    ];

    // 이유를 반드시 받는 결과
    public const REASON_REQUIRED = [
        'skipped' => 'SKIP_REASONS',
        'bounced' => 'BOUNCE_REASONS',
    ];

    // 사건 단위 기록
    private const EVENT_OPS = [
        'note'           => '메모',
        'campaign.close' => '봉인',
    ];

    private const INSPECTION_REL = 'revision/inspection.jsonl';

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

    private static function hotlineOf(array $row): array
    {
        $h = $row['hotline'] ?? [];
        return [
            'contactName' => (string) ($h['contactName'] ?? ''),
            'phone'       => (string) ($h['phone'] ?? ''),
            'email'       => (string) ($h['email'] ?? ''),
            'verifiedAt'  => (string) ($h['verifiedAt'] ?? ''),
        ];
    }

    private static function hasHotline(array $row): bool
    {
        $h = self::hotlineOf($row);
        return $h['contactName'] !== '' || $h['phone'] !== '' || $h['email'] !== '';
    }

    // ── 대상 BP 선별 ──────────────────────────────────────────────

    /**
     * 사건의 도/현 후보군에 걸리는 BP site 를 고른다.
     * 판정 축은 행정구역이다. 반경은 쓰지 않는다.
     *
     * @param array<array<string, mixed>> $rows   bp_resolved 행들
     * @param string[]                   $adminAreas
     * @return array<array<string, mixed>>
     */
    public static function targetsForEvent(array $rows, string $country, array $adminAreas): array
    {
        $wanted = array_flip(array_filter(
            array_map(fn($a) => trim((string) $a), $adminAreas),
            fn($a) => $a !== ''
        ));

        $out = [];
        foreach ($rows as $r) {
            if (($r['active'] ?? true) === false) { continue; }
            if (($r['country'] ?? null) !== $country) { continue; }
            $areas = array_filter(array_map(
                fn($k) => trim((string) ($r[$k] ?? '')),
                ['admin1', 'admin1Ko', 'prefecture', 'prefectureKo']
            ));
            if (!empty(array_intersect($areas, array_keys($wanted)))) {
                $out[] = $r;
            }
        }

        $rank = ['QD' => 0, 'Q' => 1];
        usort($out, static function (array $a, array $b) use ($rank): int {
            $ga = $rank[substr(strtoupper((string) ($a['grade'] ?? '')), 0, 2)] ?? 9;
            $gb = $rank[substr(strtoupper((string) ($b['grade'] ?? '')), 0, 2)] ?? 9;
            if ($ga !== $gb) { return $ga <=> $gb; }
            return strcmp((string) ($a['bpName'] ?? ''), (string) ($b['bpName'] ?? ''));
        });
        return $out;
    }

    /**
     * 같은 회사의 여러 site 를 한 통의 메일로 묶는다.
     *
     * @param array<array<string, mixed>> $rows
     * @return array<string, array<array<string, mixed>>>
     */
    public static function groupByBp(array $rows): array
    {
        $by = [];
        foreach ($rows as $r) {
            $key = (string) ($r['bpName'] ?? $r['bpId'] ?? '');
            $by[$key][] = $r;
        }
        return $by;
    }

    /**
     * 사건 소재군과 겹치는 담당자를 고른다.
     * hotline 대표 → contacts 소재군 매칭 순으로 내려간다.
     *
     * @param array<array<string, mixed>> $sites
     * @param string[]|null              $eventGroups
     * @return array{0: array<array<string, mixed>>, 1: string}  [contacts, routedBy]
     */
    private static function recipientsFor(array $sites, ?array $eventGroups): array
    {
        $contacts = [];
        $seen     = [];
        foreach ($sites as $s) {
            foreach ($s['contacts'] ?? [] as $c) {
                $k = implode('|', [$c['name'] ?? '', $c['email'] ?? '', $c['phone'] ?? '']);
                if (!isset($seen[$k])) {
                    $seen[$k]   = true;
                    $contacts[] = $c;
                }
            }
        }

        $routed = 'none';
        if (!empty($contacts)) {
            $routed = 'all-contacts';
            $eg     = array_flip(array_filter(
                array_map(fn($g) => strtolower(trim((string) $g)), $eventGroups ?? []),
                fn($g) => $g !== ''
            ));
            if (!empty($eg)) {
                $hit = array_filter($contacts, function ($c) use ($eg) {
                    foreach ($c['materialGroups'] ?? [] as $g) {
                        if (isset($eg[strtolower(trim((string) $g))])) { return true; }
                    }
                    return false;
                });
                if (!empty($hit)) {
                    $contacts = array_values($hit);
                    $routed   = 'material-group';
                }
            }
        } else {
            $h = self::hotlineOf($sites[0]);
            if ($h['contactName'] !== '' || $h['email'] !== '' || $h['phone'] !== '') {
                $contacts = [['name' => $h['contactName'], 'email' => $h['email'], 'phone' => $h['phone']]];
                $routed   = 'hotline';
            }
        }
        return [$contacts, $routed];
    }

    // ── 메일 초안 ─────────────────────────────────────────────────

    /**
     * 점검 요청 메일 초안. 보내지 않는다 — 배열만 돌려준다.
     *
     * @param array<array<string, mixed>> $sites
     * @param array<string, mixed>        $event
     * @return array<string, mixed>
     */
    public static function draftMail(string $bpName, array $sites, array $event, string $senderName = '이정훈'): array
    {
        [$contacts, $routed] = self::recipientsFor($sites, $event['materialGroups'] ?? null);
        $h        = self::hotlineOf($sites[0]);
        $when     = str_replace('T', ' ', substr((string) ($event['occurredAt'] ?? ''), 0, 16));
        $where    = $event['location']
            ?? implode(', ', $event['adminAreas'] ?? []);
        $evType   = $event['type'] ?? '재해';
        $evMag    = (string) ($event['magnitude'] ?? '');
        $what     = $event['headline'] ?? trim("{$evType} {$evMag}");

        $siteLines = [];
        foreach ($sites as $s) {
            $area = implode(' ', array_filter([
                $s['admin1Ko'] ?? $s['admin1'] ?? null,
                $s['address'] ?? null,
            ]));
            $groups    = implode(', ', $s['materialGroups'] ?? []) ?: '-';
            $siteName  = $s['siteName'] ?? '(사업소명 미상)';
            $siteLines[] = "  · {$siteName} — {$area} / {$groups}";
        }

        $ref       = trim((string) ($event['id'] ?? '')) ?: '미지정';
        $toNames   = implode(', ', array_filter(array_map(
            fn($c) => $c['name'] ?? '담당자', $contacts
        ))) ?: ($h['contactName'] ?: '담당자');
        $firstName = $contacts[0]['name'] ?? $h['contactName'] ?: '담당자';

        $subject = "[SK하이닉스 긴급점검] {$bpName} 사업장 확인 요청 · 점검번호 {$ref}";

        $siteLinesText = implode("\n", $siteLines);
        $body = <<<MAIL
{$firstName}님, 안녕하십니까.
SK hynix 소재전략 {$senderName}입니다.

아래 재해 발생과 관련하여 귀사 사업장의 영향 여부를 확인드리고자 연락드립니다.

■ 사건
  발생 : {$when}
  지역 : {$where}
  내용 : {$what}

■ 확인 요청 대상 사업장
{$siteLinesText}

번거로우시더라도 아래 회신 블록만 채워 회신 부탁드립니다.
(표기 — X: 해당 없음 · △: 확인 중 · O: 영향 있음)

===== mGSCS 회신 (이 블록만 수정해 회신) =====
점검번호: {$ref}
협력사: {$bpName}
소재 제조 사업장: X
원재료 제조 사업장: X
Source(3rd BP 이하): X
재고(일):
특이사항: 없음
소통 요청: 불필요
=============================================

긴급 상황인 점 양해 부탁드리며, 확인되는 범위에서 우선 회신 주시면
감사하겠습니다.

SK hynix 소재전략 {$senderName} 드림
MAIL;

        $toEmails   = implode('; ', array_filter(array_map(fn($c) => $c['email'] ?? '', $contacts)));
        $toPhones   = implode('; ', array_filter(array_map(fn($c) => $c['phone'] ?? '', $contacts)));
        $hotlineMiss = empty($contacts) && !self::hasHotline($sites[0]);

        return [
            'bpName'         => $bpName,
            'eventId'        => $ref,
            'to'             => $toEmails ?: $h['email'],
            'contactName'    => $toNames,
            'phone'          => $toPhones ?: $h['phone'],
            'routedBy'       => $routed,
            'recipients'     => count($contacts),
            'subject'        => $subject,
            'body'           => $body,
            'siteCount'      => count($sites),
            'hotlineMissing' => $hotlineMiss,
        ];
    }

    /**
     * 사건의 도/현이 없으면 좌표로 채운다.
     *
     * @return array{0: string[], 1: string}  [areas, source]
     */
    public static function resolveEventAreas(array $event): array
    {
        $areas = array_filter(
            $event['adminAreas'] ?? [],
            fn($a) => trim((string) $a) !== ''
        );
        if (!empty($areas)) {
            $cc   = $event['country'] ?? null;
            $norm = [];
            $seen = [];
            foreach ($areas as $a) {
                $hit = (class_exists('GeoRef', false))
                    ? (GeoRef::resolveAdmin1($cc, (string) $a) ?: trim((string) $a))
                    : trim((string) $a);
                if (!isset($seen[$hit])) {
                    $seen[$hit] = true;
                    $norm[] = $hit;
                }
            }
            return [$norm, 'source'];
        }

        $lat = $event['lat'] ?? null;
        $lon = $event['lon'] ?? null;
        if ($lat === null || $lon === null || !class_exists('GeoRef', false)) {
            return [[], 'none'];
        }
        try {
            $cands = GeoRef::adminAreaCandidates($event['country'] ?? null, (float) $lat, (float) $lon);
            return [array_column($cands, 'admin1'), 'coordinates'];
        } catch (\Exception $e) {
            return [[], 'none'];
        }
    }

    /**
     * 사건 → 대상 BP → 회사별 메일 초안.
     *
     * @param array<array<string, mixed>> $rows
     * @param array<string, mixed>        $event
     * @return array<string, mixed>
     */
    public static function buildDrafts(array $rows, array $event, string $senderName = '이정훈'): array
    {
        [$areas, $areaSource] = self::resolveEventAreas($event);
        $event   = array_merge($event, ['adminAreas' => $areas, 'adminAreaSource' => $areaSource]);
        $targets = self::targetsForEvent($rows, (string) ($event['country'] ?? ''), $areas);
        $grouped = self::groupByBp($targets);

        $drafts = [];
        foreach ($grouped as $bp => $sites) {
            $drafts[] = self::draftMail((string) $bp, $sites, $event, $senderName);
        }
        // 연락처 있는 곳을 위로
        usort($drafts, fn($a, $b) =>
            ($a['hotlineMissing'] <=> $b['hotlineMissing']) ?: strcmp($a['bpName'], $b['bpName'])
        );

        return [
            'event'         => $event,
            'adminAreas'    => $areas,
            'adminAreaSource'=> $areaSource,
            'targetSites'   => count($targets),
            'targetBps'     => count($grouped),
            'hotlineReady'  => count(array_filter($drafts, fn($d) => !$d['hotlineMissing'])),
            'drafts'        => $drafts,
        ];
    }

    // ── 점검 이력 ─────────────────────────────────────────────────

    /**
     * 화면에서 온 기록을 검증해 원장 레코드로 만든다.
     *
     * @param array<string, mixed> $body
     * @return array{0: array<string, mixed>|null, 1: string|null}  [record, error]
     */
    public static function makeRecord(array $body, string $actor = 'unknown'): array
    {
        $op  = trim((string) ($body['op'] ?? ''));
        $eid = trim((string) ($body['eventId'] ?? ''));

        $allOps = array_merge(array_keys(self::STATUS), array_keys(self::EVENT_OPS));
        if (!in_array($op, $allOps, true)) {
            return [null, "모르는 op 입니다: {$op} (가능: " . implode(' ', $allOps) . ')'];
        }
        if ($eid === '') {
            return [null, 'eventId 가 필요합니다 — 어느 사건의 점검인지 없이는 이력이 되지 않습니다.'];
        }

        $rec = ['op' => $op, 'eventId' => $eid, 'actor' => $actor];

        if (isset(self::STATUS[$op])) {
            $bp = trim((string) ($body['bpName'] ?? ''));
            if ($bp === '') {
                return [null, "{$op} 는 BP 단위 기록입니다 — bpName 이 필요합니다."];
            }
            $rec['bpName'] = $bp;

            $rqKey = self::REASON_REQUIRED[$op] ?? null;
            if ($rqKey !== null) {
                $table = ($rqKey === 'SKIP_REASONS') ? self::SKIP_REASONS : self::BOUNCE_REASONS;
                $code  = trim((string) ($body['reason'] ?? ''));
                if (!isset($table[$code])) {
                    return [null, (self::STATUS[$op] ?? $op) . ' 에는 이유가 필요합니다 — 가능: ' . implode(' ', array_keys($table))];
                }
                if ($code === 'other' && trim((string) ($body['note'] ?? '')) === '') {
                    return [null, "'그 밖' 은 내용(note)을 적어야 합니다."];
                }
                $rec['reason']   = $code;
                $rec['reasonKo'] = $table[$code]['label'];
            }
            foreach (['s1', 's2', 's3', 'stock', 'note', 'commReq', 'to', 'contactName'] as $k) {
                if (isset($body[$k]) && $body[$k] !== null && $body[$k] !== '') {
                    $rec[$k] = $body[$k];
                }
            }
        } else {
            // EVENT_OPS
            if ($op === 'note' && trim((string) ($body['note'] ?? '')) === '') {
                return [null, 'note 에는 내용(note)이 필요합니다.'];
            }
            foreach (['note', 'summary', 'actions'] as $k) {
                if (isset($body[$k]) && $body[$k] !== null && $body[$k] !== '') {
                    $rec[$k] = $body[$k];
                }
            }
        }
        return [$rec, null];
    }

    /**
     * 검증된 기록을 원장에 추가한다. flock 과 fsync 를 걸어 내구성을 보장한다.
     *
     * @param array<string, mixed> $record  makeRecord 가 반환한 레코드
     * @return array<string, mixed>
     */
    public static function appendRecord(array $record): array
    {
        $record['ts'] = self::now();
        self::appendLine(self::INSPECTION_REL, $record);
        return $record;
    }

    /**
     * 점검 이력 전체를 읽는다.
     *
     * @return array<array<string, mixed>>
     */
    public static function loadRecords(): array
    {
        $path = self::pathFor(self::INSPECTION_REL);
        if (!file_exists($path)) { return []; }
        $out = [];
        $fh  = fopen($path, 'r');
        if ($fh === false) { return []; }
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
     * 사건×BP 별 최신 상태. 마지막 기록이 이긴다.
     *
     * @return array<string, array<string, mixed>>  key = "eventId|bpName"
     */
    public static function currentState(): array
    {
        $state = [];
        foreach (self::loadRecords() as $r) {
            $key         = ($r['eventId'] ?? '') . '|' . ($r['bpName'] ?? '');
            $state[$key] = $r;
        }
        return $state;
    }

    /**
     * 이유 코드 사전 전체.
     *
     * @return array<string, array<string, array<string, string>>>
     */
    public static function reasons(): array
    {
        return [
            'skip'   => self::SKIP_REASONS,
            'bounce' => self::BOUNCE_REASONS,
        ];
    }
}
