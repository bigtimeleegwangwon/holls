<?php
// -*- coding: utf-8 -*-
declare(strict_types=1);

/**
 * Api — 모든 /api/* 엔드포인트. 라우팅표는 index.php 에 있다.
 *
 * routes.py 의 api_* 함수 전체 이식.
 *
 * 공개(PUBLIC_ENDPOINTS) 항목과 관리자 전용 항목 모두 여기 있다.
 * 관문(게이트) 판정은 index.php 에서 미리 하고, 이 클래스는
 * 이미 통과한 요청만 받는다고 가정한다.
 *
 * 관리자 전용 엔드포인트는 메서드 첫 줄에서 isAdmin() 을 확인한다.
 */


final class Api
{
    private const INCIDENTS_REL  = 'revision/incidents.json';
    private const LLM_PROBE_REL  = 'llm_probe.json';
    private const INGEST_REVIEW_MAX = 3;

    // 심각도 정본 매핑
    private const SEV_CANON = [
        'critical' => 'high', 'high'   => 'high', '심각' => 'high', '위험' => 'high',
        'warning'  => 'medium', 'medium' => 'medium', '주의' => 'medium',
        'info'     => 'low',  'low'    => 'low',  '관심' => 'low',  '낮음' => 'low',
    ];

    // 내보내기 열 정의
    private const EXPORT_COLUMNS = [
        ['bpName',            'BP'],
        ['parentBp',          '모회사'],
        ['bpId',              'BP ID'],
        ['grade',             '등급'],
        ['tier',              '티어'],
        ['materialGroups',    '소재군'],
        ['siteName',          '사업장'],
        ['siteId',            '사업장 ID'],
        ['siteRole',          '역할'],
        ['country',           '국가'],
        ['admin1Ko',          '행정구역'],
        ['regionKo',          '권역'],
        ['address',           '주소'],
        ['lat',               '위도'],
        ['lon',               '경도'],
        ['coordinateStatus',  '좌표등급'],
        ['distanceEligible',  '거리판정 자격'],
        ['surveyedAt',        '조사일'],
        ['provenance',        '근거'],
        ['sourceUrls',        '출처 주소'],
        ['active',            '활성'],
    ];

    private const HOTLINE_COLUMNS = [
        ['contactName', '담당자'],
        ['phone',       '전화'],
        ['email',       '이메일'],
        ['verifiedAt',  '연락처 확인일'],
    ];

    // ── KST 유틸 ──────────────────────────────────────────────────

    private static function kst(): \DateTimeZone
    {
        return new \DateTimeZone('Asia/Seoul');
    }

    private static function nowKst(): string
    {
        return (new \DateTime('now', self::kst()))->format(\DATE_ATOM);
    }

    // ── 명부 헬퍼 ─────────────────────────────────────────────────

    private static function loadResolved(bool $includeRetired = false): array
    {
        $path = Store::pathFor(implode(DIRECTORY_SEPARATOR, ['build', 'bp_resolved.json']));
        if (!file_exists($path)) {
            Rebuild::rebuild();
        }
        $rows = json_decode((string) file_get_contents($path), true) ?: [];
        return $includeRetired ? $rows
            : array_values(array_filter($rows, fn($r) => ($r['active'] ?? true) !== false));
    }

    private static function loadIncidents(): array
    {
        return Store::readJson(self::INCIDENTS_REL, default: []) ?: [];
    }

    private static function incidentKey(array $x): string
    {
        return trim((string) ($x['id'] ?? ''));
    }

    private static function findIncident(string $iid): ?array
    {
        foreach (self::loadIncidents() as $r) {
            if (self::incidentKey($r) === $iid) {
                return $r;
            }
        }
        return null;
    }

    private static function canonIncident(array $x): array
    {
        $raw   = trim((string) ($x['severity'] ?? ''));
        $canon = self::SEV_CANON[mb_strtolower($raw, 'UTF-8')] ?? null;
        if ($canon && $canon !== $raw) {
            $x['severityRaw'] = $raw;
            $x['severity']    = $canon;
        }
        $x['status'] ??= 'open';
        if (!isset($x['certainty'])) {
            $x['certainty'] = !empty($x['official']) ? 'confirmed' : 'rumor';
        }
        return $x;
    }

    // ── Content-Disposition 헬퍼 ──────────────────────────────────

    private static function attachHeader(string $fname): string
    {
        $ascii = preg_replace('/[^\x20-\x7E]/', '', $fname) ?: 'mgscs_export';
        return sprintf(
            'attachment; filename="%s"; filename*=UTF-8\'\'%s',
            $ascii,
            rawurlencode($fname)
        );
    }

    // ── /api/resolved ─────────────────────────────────────────────

    public static function apiResolved(): void
    {
        Auth::sessionStartSafe();
        $rows = self::loadResolved();
        if (!Auth::loggedIn()) {
            $rows = Pages::publicRows($rows);
        }
        Http::jsonResponse($rows);
    }

    // ── /api/export ───────────────────────────────────────────────

    public static function apiExport(): void
    {
        Auth::sessionStartSafe();

        $fmt     = strtolower($_GET['fmt'] ?? 'csv');
        $withHot = ($_GET['hotline'] ?? '') === '1';
        $rows    = self::loadResolved();
        $stamp   = (new \DateTime('now', self::kst()))->format('Ymd');
        $name    = 'mGSCS_BP명부_' . $stamp . ($withHot ? '_연락처포함' : '');

        if ($fmt === 'json') {
            $out = [];
            foreach ($rows as $r) {
                $d = [];
                foreach (self::EXPORT_COLUMNS as [$key]) {
                    $d[$key] = $r[$key] ?? null;
                }
                $d['sourceUrls'] = array_column($r['sources'] ?? [], 'url');
                if ($withHot) {
                    $d['hotline'] = $r['hotline'] ?? [];
                }
                $out[] = $d;
            }
            $body = json_encode($out, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            header('Content-Type: application/json; charset=utf-8');
            header('Content-Disposition: ' . self::attachHeader($name . '.json'));
            echo $body;
            return;
        }

        // CSV
        $buf = fopen('php://temp', 'r+');
        $headers = array_column(self::EXPORT_COLUMNS, 1);
        if ($withHot) {
            foreach (self::HOTLINE_COLUMNS as [, $label]) {
                $headers[] = $label;
            }
        }
        fputcsv($buf, $headers, escape: '');

        foreach ($rows as $r) {
            $line = [];
            foreach (self::EXPORT_COLUMNS as [$key]) {
                if ($key === 'sourceUrls') {
                    $line[] = implode(' ', array_column($r['sources'] ?? [], 'url'));
                    continue;
                }
                $v = $r[$key] ?? null;
                $line[] = is_array($v) ? implode(', ', $v) : ($v === null ? '' : (string) $v);
            }
            if ($withHot) {
                $h = $r['hotline'] ?? [];
                foreach (self::HOTLINE_COLUMNS as [$hk]) {
                    $line[] = (string) ($h[$hk] ?? '');
                }
            }
            fputcsv($buf, $line, escape: '');
        }
        rewind($buf);
        $csv  = (string) stream_get_contents($buf);
        fclose($buf);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: ' . self::attachHeader($name . '.csv'));
        echo "\xEF\xBB\xBF" . $csv;  // BOM — 엑셀이 한글을 깨뜨리지 않게
    }

    // ── /api/export/hotline.csv ───────────────────────────────────

    public static function apiExportHotline(): void
    {
        Auth::sessionStartSafe();

        [$cols, $csvRows] = HotlineImport::exportRows(self::loadResolved());

        $buf = fopen('php://temp', 'r+');
        fputcsv($buf, $cols, escape: '');
        foreach ($csvRows as $row) {
            fputcsv($buf, $row, escape: '');
        }
        rewind($buf);
        $csv = (string) stream_get_contents($buf);
        fclose($buf);

        $day = (new \DateTime('now', self::kst()))->format('Ymd');
        Store::appendAudit('export.hotline', '연락처 양식 ' . count($csvRows) . '행', actor: Auth::actorNow());

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename*=UTF-8\'\'' . rawurlencode("mgscs_연락처양식_{$day}.csv"));
        header('X-Content-Type-Options: nosniff');
        echo "\xEF\xBB\xBF" . $csv;
    }

    // ── /api/export/sources.json ──────────────────────────────────

    public static function apiExportSources(): void
    {
        Auth::sessionStartSafe();

        $rows  = self::loadResolved();
        $byBp  = [];
        foreach ($rows as $r) {
            $n = $r['bpName'] ?? null;
            if (!$n || ($r['active'] ?? true) === false) {
                continue;
            }
            if (!isset($byBp[$n])) {
                $byBp[$n] = [
                    'bp_name'         => $n,
                    'country'         => $r['country'] ?? null,
                    'material_groups' => [],
                    'site_count'      => 0,
                    'parent_bp'       => '',
                    'sources'         => [],
                ];
            }
            if (!empty($r['parentBp'])) {
                $byBp[$n]['parent_bp'] = $r['parentBp'];
            }
            foreach ($r['materialGroups'] ?? [] as $g) {
                $byBp[$n]['material_groups'][$g] = true;
            }
            $byBp[$n]['site_count']++;
            foreach ($r['sources'] ?? [] as $x) {
                $u = $x['url'] ?? null;
                if ($u && !isset($byBp[$n]['sources'][$u])) {
                    $byBp[$n]['sources'][$u] = $x;
                }
            }
        }

        ksort($byBp);
        $out    = [];
        $filled = 0;
        foreach ($byBp as $n => $d) {
            $mats = array_keys($d['material_groups']);
            sort($mats);
            $sources = array_values($d['sources']);
            if ($sources) {
                $filled++;
            }
            $out[] = [
                'bp_name'           => $n,
                'country'           => $d['country'],
                'material_groups'   => $mats,
                'parent_bp'         => $d['parent_bp'],
                'site_count'        => $d['site_count'],
                'collection_status' => $sources ? 'COLLECTED' : 'NOT_COLLECTED',
                'sources'           => array_map(fn($x) => [
                    'url'              => $x['url'] ?? null,
                    'publisher'        => $x['title'] ?? '',
                    'source_tier'      => $x['tier'] ?? '',
                    'evidence_strength'=> $x['evidence'] ?? 'SEED_ONLY',
                    'fetched_at'       => $x['checkedAt'] ?? '',
                    'caution'          => $x['caution'] ?? '',
                ], $sources),
            ];
        }

        $doc = [
            'schema'        => 'bp_source_ledger',
            'version'       => '1.1',
            'origin_system' => 'mGSCS',
            'as_of'         => (new \DateTime('now', self::kst()))->format('Y-m-d'),
            'join_key'      => 'bp_name',
            'note'          => '사내 BP 코드가 시스템마다 달라 이름으로 잇는다. 이름이 다르게 적히면 mGSCS 의 bpAliases 를 함께 받아 맞춘다.',
            'counts'        => ['bp' => count($out), 'with_sources' => $filled, 'without_sources' => count($out) - $filled],
            'records'       => $out,
        ];

        Store::appendAudit('export.sources', "{$filled}/" . count($out) . '사', actor: Auth::actorNow());

        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="mgscs_bp_source_ledger.json"');
        echo json_encode($doc, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    // ── /api/export/material_survey.csv ───────────────────────────

    public static function apiMaterialSurvey(): void
    {
        Auth::sessionStartSafe();

        $rows   = self::loadResolved();
        $graded = array_filter($rows, fn($r) => !empty($r['grade']));
        usort($graded, fn($a, $b) => strcmp(
            ($a['bpName'] ?? '') . ($a['siteName'] ?? ''),
            ($b['bpName'] ?? '') . ($b['siteName'] ?? '')
        ));

        $buf = fopen('php://temp', 'r+');
        fputcsv($buf, ['BP', '사업장', '국가', '지역', '등급', '소재군', '연관 여부(O/X)', '대상 자재 코드', '대상 자재 명', '비고'], escape: '');
        foreach ($graded as $r) {
            fputcsv($buf, [
                $r['bpName']   ?? '',
                $r['siteName'] ?? '',
                $r['country']  ?? '',
                $r['admin1Ko'] ?? $r['admin1'] ?? '',
                $r['grade']    ?? '',
                implode(' / ', $r['materialGroups'] ?? []),
                '', '', '', '',
            ], escape: '');
        }
        rewind($buf);
        $csv = (string) stream_get_contents($buf);
        fclose($buf);

        $day = (new \DateTime('now', self::kst()))->format('Ymd');
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: ' . self::attachHeader("mGSCS_대상소재_조사양식_{$day}.csv"));
        echo "\xEF\xBB\xBF" . $csv;
    }

    // ── /api/hotline/preview ──────────────────────────────────────

    public static function apiHotlinePreview(): void
    {
        Auth::sessionStartSafe();

        $b    = Http::bodyJson();
        $text = trim((string) ($b['text'] ?? ''));
        if ($text === '') {
            Http::jsonResponse(['ok' => false, 'code' => 7001, 'error' => '붙여넣은 내용이 없습니다.'], 400);
            return;
        }
        $rows = self::loadResolved();
        $out  = HotlineImport::parse($text, $rows);
        Http::jsonResponse(['ok' => true, ...$out]);
    }

    // ── /api/hotline/gaps ─────────────────────────────────────────

    public static function apiHotlineGaps(): void
    {
        Auth::sessionStartSafe();

        $g   = HotlineImport::gaps(self::loadResolved());
        $fmt = strtolower($_GET['fmt'] ?? '');

        if ($fmt === 'csv') {
            $buf = fopen('php://temp', 'r+');
            fputcsv($buf, ['BP', '등급', '티어', '소재군', '국가', '사업장수', '담당자수', '상태'], escape: '');
            $allRows = array_merge($g['empty'] ?? [], ($_GET['all'] ?? '') === '1' ? ($g['filled'] ?? []) : []);
            foreach ($allRows as $row) {
                fputcsv($buf, [
                    $row['bp'], $row['grade'], $row['tier'],
                    implode(' ', $row['groups'] ?? []),
                    implode(' ', $row['countries'] ?? []),
                    $row['sites']    ?? 0,
                    $row['contacts'] ?? 0,
                    ($row['contacts'] ?? 0) ? '채움' : '비어 있음',
                ], escape: '');
            }
            rewind($buf);
            $csv = (string) stream_get_contents($buf);
            fclose($buf);
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="mgscs_hotline_gaps.csv"');
            echo "\xEF\xBB\xBF" . $csv;
            return;
        }

        Http::jsonResponse(['ok' => true, ...$g]);
    }

    // ── /api/hotline/commit ───────────────────────────────────────

    public static function apiHotlineCommit(): void
    {
        Auth::sessionStartSafe();

        $body  = Http::bodyJson();
        $byBp  = $body['byBp'] ?? null;
        if (!is_array($byBp) || empty($byBp)) {
            Http::jsonResponse(['ok' => false, 'code' => 8003, 'error' => '반영할 내용이 없습니다.'], 400);
            return;
        }
        $reason = trim((string) ($body['reason'] ?? '')) ?: 'Hotline 엑셀 일괄 등록';

        $known = array_flip(array_filter(array_column(self::loadResolved(), 'bpName')));

        // 별칭 기록
        $aliases      = is_array($body['aliases'] ?? null) ? $body['aliases'] : [];
        $aliasWritten = 0;
        foreach ($aliases as $bpName => $names) {
            $names = array_filter(array_map('strval', (array) $names));
            if (!isset($known[$bpName]) || !$names) {
                continue;
            }
            $ev = [
                'ts'     => self::nowKst(),
                'actor'  => Auth::actorNow(),
                'op'     => 'alias.add',
                'bpKey'  => $bpName,
                'reason' => '연락처 일괄 등록에서 확인한 이름 — ' . implode(', ', $names),
                'after'  => ['aliases' => array_values($names)],
            ];
            if (!Rebuild::validateEvent($ev)) {
                Store::appendLine(implode(DIRECTORY_SEPARATOR, ['revision', 'ledger.jsonl']), $ev);
                $aliasWritten++;
            }
        }

        $written  = [];
        $skipped  = [];
        foreach ($byBp as $bpName => $contacts) {
            if (!isset($known[$bpName])) {
                $skipped[] = "{$bpName} — 명부에 없음";
                continue;
            }
            if (!is_array($contacts) || !$contacts) {
                $skipped[] = "{$bpName} — 담당자 없음";
                continue;
            }
            $ev = [
                'ts'     => self::nowKst(),
                'actor'  => Auth::actorNow(),
                'op'     => 'hotline.bulk',
                'bpKey'  => $bpName,
                'reason' => $reason,
                'after'  => ['contacts' => $contacts],
            ];
            $problems = Rebuild::validateEvent($ev);
            if ($problems) {
                $skipped[] = "{$bpName} — " . implode(' / ', $problems);
                continue;
            }
            Store::appendLine(implode(DIRECTORY_SEPARATOR, ['revision', 'ledger.jsonl']), $ev);
            $written[] = $bpName;
        }

        if (!$written) {
            Http::jsonResponse(['ok' => false, 'error' => '반영된 곳이 없습니다.', 'skipped' => array_slice($skipped, 0, 40)], 400);
            return;
        }

        Store::appendAudit('hotline.bulk', count($written) . '사 반영 · 건너뜀 ' . count($skipped), actor: Auth::actorNow());
        $res = Rebuild::rebuild();

        // 실제로 붙은 수 재확인
        $rows   = self::loadResolved();
        $landed = array_flip(array_filter(array_column($rows, 'bpName'), function ($n) use ($rows) {
            foreach ($rows as $r) {
                if (($r['bpName'] ?? '') === $n && !empty($r['contacts'])) {
                    return true;
                }
            }
            return false;
        }));
        $missed    = array_filter($written, fn($b) => !isset($landed[$b]));
        $errors    = array_filter($res['errors'] ?? [], fn($e) => str_contains($e, 'hotline.bulk'));
        $errors    = array_merge(array_values($errors), array_values(array_filter($res['skipped'] ?? [], fn($e) => str_contains($e, 'hotline.bulk'))));
        $conflicts = array_filter($res['conflicts'] ?? [], fn($c) => str_contains($c, 'alias.add'));
        $people    = array_sum(array_map('count', array_values($byBp)));

        Http::jsonResponse([
            'ok'      => !$missed,
            'written' => count($written),
            'landed'  => count($written) - count($missed),
            'missed'  => array_values(array_slice($missed, 0, 20)),
            'errors'  => array_slice(array_merge(array_values($errors), array_values($conflicts)), 0, 20),
            'skipped' => array_slice($skipped, 0, 40),
            'bps'     => array_slice($written, 0, 60),
            'aliases' => $aliasWritten,
            'people'  => $people,
        ]);
    }

    // ── /api/incidents ────────────────────────────────────────────

    public static function apiIncidents(): void
    {
        Auth::sessionStartSafe();

        $rows  = self::loadIncidents();
        $since = $_GET['since'] ?? '';
        if ($since) {
            $rows = array_values(array_filter($rows, fn($r) => ((string) ($r['occurredAt'] ?? '')) >= $since));
        }
        Http::jsonResponse(['ok' => true, 'count' => count($rows), 'incidents' => array_slice($rows, -500)]);
    }

    // ── /api/incidents/import ─────────────────────────────────────

    public static function apiIncidentsImport(): void
    {
        Auth::sessionStartSafe();

        $body  = Http::bodyJson();
        $items = $body['incidents'] ?? null;
        if (!is_array($items)) {
            Http::jsonResponse(['ok' => false, 'code' => 8001, 'error' => 'incidents 배열이 필요합니다.'], 400);
            return;
        }

        $lock = Store::LOCK();
        flock($lock, LOCK_EX);
        try {
            $have  = self::loadIncidents();
            $seen  = array_flip(array_filter(array_map([self::class, 'incidentKey'], $have)));
            $added = [];
            $dup   = 0;
            $bad   = 0;
            $noId  = [];
            $now   = self::nowKst();

            foreach ($items as $x) {
                if (!is_array($x) || !trim((string) ($x['title'] ?? ''))) {
                    $bad++;
                    continue;
                }
                $k = self::incidentKey($x);
                if (!$k) {
                    $noId[] = mb_substr((string) ($x['title'] ?? ''), 0, 40);
                    continue;
                }
                if (isset($seen[$k])) {
                    $dup++;
                    continue;
                }
                $seen[$k]        = true;
                $x                = self::canonIncident($x);
                $x['importedAt']  = $now;
                $x['importedBy']  = Auth::actorNow();
                $x['source']    ??= (string) ($body['source'] ?? '사외');
                $added[]          = $x;
            }

            if ($added) {
                Store::writeJson(self::INCIDENTS_REL, array_merge($have, $added));
                Store::appendAudit(
                    'incident.import',
                    '신규 ' . count($added) . ' · 중복 ' . $dup . ' · 형식오류 ' . $bad . ' · id없음 ' . count($noId),
                    actor: Auth::actorNow()
                );
            }
        } finally {
            flock($lock, LOCK_UN);
        }

        $out = [
            'ok'         => true,
            'added'      => count($added),
            'duplicated' => $dup,
            'invalid'    => $bad,
            'total'      => count($have) + count($added),
        ];
        if ($noId) {
            $out['rejectedNoId'] = array_slice($noId, 0, 20);
            $out['hint']         = 'id 가 없는 사건은 받지 않습니다 — 사외 내보내기에서 id 를 실어 다시 보내세요.';
        }
        Http::jsonResponse($out);
    }

    // ── /api/incidents/status ─────────────────────────────────────

    public static function apiIncidentsStatus(): void
    {
        Auth::sessionStartSafe();

        $b   = Http::bodyJson();
        $iid = trim((string) ($b['id'] ?? ''));
        $st  = trim((string) ($b['status'] ?? ''));
        if (!$iid || !in_array($st, ['open', 'closed'], true)) {
            Http::jsonResponse(['ok' => false, 'error' => 'id 와 status(open|closed) 가 필요합니다.'], 400);
            return;
        }

        $lock = Store::LOCK();
        flock($lock, LOCK_EX);
        try {
            $rows = self::loadIncidents();
            $hits = array_filter($rows, fn($r) => self::incidentKey($r) === $iid);
            if (!$hits) {
                flock($lock, LOCK_UN);
                Http::jsonResponse(['ok' => false, 'code' => 6001, 'error' => "사건이 없습니다: {$iid}"], 404);
                return;
            }
            $now = self::nowKst();
            foreach ($rows as &$r) {
                if (self::incidentKey($r) === $iid) {
                    $r['status']          = $st;
                    $r['statusChangedAt'] = $now;
                    $r['statusChangedBy'] = Auth::actorNow();
                }
            }
            unset($r);
            Store::writeJson(self::INCIDENTS_REL, $rows);
        } finally {
            flock($lock, LOCK_UN);
        }

        Store::appendAudit('incident.status', "{$iid} → {$st}", actor: Auth::actorNow());
        Http::jsonResponse(['ok' => true, 'id' => $iid, 'status' => $st]);
    }

    // ── /api/settings ─────────────────────────────────────────────

    public static function apiSettings(): void
    {
        Auth::sessionStartSafe();

        $values  = Http::bodyJson();
        $current = AlertConfig::load();
        $merged  = array_merge($current, array_map('strval', $values));
        [$problems, $warnings] = AlertConfig::validate($merged);

        if ($problems) {
            Http::jsonResponse(['ok' => false, 'problems' => $problems, 'warnings' => $warnings], 400);
            return;
        }
        [$saved, $changed] = AlertConfig::save(Auth::actorNow(), $values);
        Http::jsonResponse(['ok' => true, 'values' => $saved, 'changed' => $changed, 'warnings' => $warnings]);
    }

    // ── /api/collect/test ─────────────────────────────────────────

    public static function apiCollectTest(): void
    {
        Auth::sessionStartSafe();
        Http::jsonResponse(['ok' => true, ...Collector::connectivityTest()]);
    }

    // ── /api/collect/run ──────────────────────────────────────────

    public static function apiCollectRun(): void
    {
        Auth::sessionStartSafe();

        $b  = Http::bodyJson();
        $st = Collector::runCycle(actor: Auth::actorNow(), catchUp: !empty($b['catchUp']));
        Http::jsonResponse(['ok' => true, ...$st]);
    }

    // ── /api/signals/ingest ───────────────────────────────────────

    public static function apiSignalsIngest(): void
    {
        Auth::sessionStartSafe();

        $b     = Http::bodyJson();
        $items = $b['signals'] ?? null;
        if (!is_array($items)) {
            Http::jsonResponse(['ok' => false, 'code' => 2003, 'error' => 'signals 배열이 필요합니다.'], 400);
            return;
        }
        if (count($items) > 5000) {
            Http::jsonResponse(['ok' => false, 'code' => 2004, 'error' => '한 번에 5000건까지 보낼 수 있습니다.'], 413);
            return;
        }

        $out = Collector::ingestSignals($items, source: (string) ($b['source'] ?? 'browser'), actor: Auth::actorNow());

        if (!empty($out['added'])) {
            try {
                $r = SignalReview::review(days: 1, limit: self::INGEST_REVIEW_MAX, actor: Auth::actorNow());
                $out['reviewed']   = $r['reviewed'] ?? 0;
                $out['candidates'] = count($r['candidates'] ?? []);
                foreach (['llmFailed', 'llmDown', 'llmWhy'] as $k) {
                    if (!empty($r[$k])) {
                        $out[$k] = $r[$k];
                    }
                }
            } catch (\Throwable $ex) {
                Store::appendAudit('review.error', get_class($ex) . ': ' . $ex->getMessage(), actor: Auth::actorNow());
            }
        }

        Http::jsonResponse(['ok' => true, ...$out]);
    }

    // ── /api/signals/review ───────────────────────────────────────

    public static function apiSignalsReview(): void
    {
        Auth::sessionStartSafe();

        $b     = Http::bodyJson();
        $limit = max(1, min(200, (int) ($b['limit'] ?? 40)));
        $days  = max(1, min(7,   (int) ($b['days']  ?? 1)));

        $out = SignalReview::review(days: $days, limit: $limit, actor: Auth::actorNow());
        Http::jsonResponse($out);
    }

    // ── /api/signals/decide ───────────────────────────────────────

    public static function apiSignalDecide(): void
    {
        Auth::sessionStartSafe();

        if (!Auth::isAdmin()) {
            Http::jsonResponse(['ok' => false, 'why' => '소재전략만 결정할 수 있다'], 403);
            return;
        }
        $b = Http::bodyJson();
        try {
            $rec = SignalReview::decide(
                $b['id'] ?? null,
                $b['decision'] ?? null,
                reason: (string) ($b['reason'] ?? ''),
                note:   (string) ($b['note']   ?? ''),
                actor:  Auth::actorNow()
            );
        } catch (\ValueError | \InvalidArgumentException $ex) {
            Http::jsonResponse(['ok' => false, 'why' => $ex->getMessage()], 400);
            return;
        }
        Http::jsonResponse(['ok' => true, 'record' => $rec]);
    }

    // ── /api/signals/reasons ──────────────────────────────────────

    public static function apiSignalReasons(): void
    {
        Auth::sessionStartSafe();

        $reasons = [];
        foreach (Feedback::REJECT_REASONS as $k => $v) {
            $reasons[] = ['code' => $k, ...$v];
        }
        Http::jsonResponse(['ok' => true, 'reasons' => $reasons]);
    }

    // ── /api/signals/dropped/sample ───────────────────────────────

    public static function apiSignalsDroppedSample(): void
    {
        Auth::sessionStartSafe();

        if (!Auth::isAdmin()) {
            Http::jsonResponse(['ok' => false, 'why' => '관리자만 가능하다'], 403);
            return;
        }
        $d    = max(1, min(30,  (int) ($_GET['days'] ?? 3)));
        $n    = max(1, min(100, (int) ($_GET['n']    ?? 20)));
        $seed = (int) ($_GET['seed'] ?? 0);
        Http::jsonResponse(['ok' => true, ...SignalReview::sampleDropped(days: $d, n: $n, seed: $seed)]);
    }

    // ── /api/signals/pending ──────────────────────────────────────

    public static function apiSignalsPending(): void
    {
        Auth::sessionStartSafe();

        if (!Auth::isAdmin()) {
            Http::jsonResponse(['ok' => false, 'why' => '관리자만 가능하다'], 403);
            return;
        }
        $limit = max(1, min(200, (int) ($_GET['limit'] ?? 60)));
        Http::jsonResponse(['ok' => true, ...SignalReview::pendingDecisions(limit: $limit)]);
    }

    // ── /api/signals/dropped/misdrop ─────────────────────────────

    public static function apiSignalsMisdrop(): void
    {
        Auth::sessionStartSafe();

        if (!Auth::isAdmin()) {
            Http::jsonResponse(['ok' => false, 'why' => '관리자만 가능하다'], 403);
            return;
        }
        $b = Http::bodyJson();
        try {
            $row = SignalReview::markMisdrop(
                $b['id'] ?? null,
                title: (string) ($b['title'] ?? ''),
                note:  (string) ($b['note']  ?? ''),
                actor: Auth::actorNow()
            );
        } catch (\ValueError $ex) {
            Http::jsonResponse(['ok' => false, 'why' => $ex->getMessage()], 400);
            return;
        }
        Http::jsonResponse(['ok' => true, 'record' => $row]);
    }

    // ── /api/feedback/queue ───────────────────────────────────────

    public static function apiFeedbackQueue(): void
    {
        Auth::sessionStartSafe();

        if (!Auth::isAdmin()) {
            Http::jsonResponse(['ok' => false, 'why' => '관리자만 가능하다'], 403);
            return;
        }
        $d = max(1, min(365, (int) ($_GET['days'] ?? 30)));
        Http::jsonResponse(['ok' => true, ...Feedback::queue(days: $d)]);
    }

    // ── /api/feedback/suggest ─────────────────────────────────────

    public static function apiFeedbackSuggest(): void
    {
        Auth::sessionStartSafe();

        if (!Auth::isAdmin()) {
            Http::jsonResponse(['ok' => false, 'why' => '관리자만 가능하다'], 403);
            return;
        }
        $d = max(1, min(365, (int) ($_GET['days'] ?? 30)));
        $m = max(1, min(50,  (int) ($_GET['min']  ?? 3)));
        Http::jsonResponse(['ok' => true, ...Feedback::suggest(days: $d, minCount: $m)]);
    }

    // ── /api/signals/funnel ───────────────────────────────────────

    public static function apiSignalsFunnel(): void
    {
        Auth::sessionStartSafe();

        $h = max(1, min(168, (int) ($_GET['hours'] ?? 24)));
        Http::jsonResponse(['ok' => true, ...SignalReview::funnel(hours: $h)]);
    }

    // ── /api/signals/judged ───────────────────────────────────────

    public static function apiSignalsJudged(): void
    {
        Auth::sessionStartSafe();

        $d = max(1, min(90, (int) ($_GET['days'] ?? 7)));
        Http::jsonResponse(['ok' => true, ...SignalReview::byCategory(days: $d)]);
    }

    // ── /api/signals ──────────────────────────────────────────────

    public static function apiSignals(): void
    {
        Auth::sessionStartSafe();

        $days = max(1, min(90, (int) ($_GET['days'] ?? 7)));
        [$rows, $files] = Collector::readSignals($days);
        Http::jsonResponse(['ok' => true, 'count' => count($rows), 'days' => $files, 'signals' => $rows]);
    }

    // ── /api/collect/status ───────────────────────────────────────

    public static function apiCollectStatus(): void
    {
        Auth::sessionStartSafe();

        Http::jsonResponse([
            'ok'        => true,
            'scheduler' => Collector::schedulerState(),
            'health'    => Collector::health(),
            'last'      => Collector::lastStatus(),
        ]);
    }

    // ── /api/alerts ───────────────────────────────────────────────

    public static function apiAlerts(): void
    {
        Auth::sessionStartSafe();

        Http::jsonResponse([
            'ok'      => true,
            'summary' => Alerts::summary(),
            'pending' => Alerts::pending(),
        ]);
    }

    // ── /api/alerts/ack ───────────────────────────────────────────

    public static function apiAlertsAck(): void
    {
        Auth::sessionStartSafe();

        $b   = Http::bodyJson();
        $ids = $b['ids'] ?? (isset($b['id']) ? [$b['id']] : []);
        if (is_string($ids) || is_int($ids)) {
            $ids = [$ids];
        }
        if (!is_array($ids)) {
            Http::jsonResponse(['ok' => false, 'error' => 'ids 는 배열이어야 합니다.'], 400);
            return;
        }
        $ids = array_filter(array_map(fn($i) => trim((string) $i), $ids));
        if (!$ids) {
            Http::jsonResponse(['ok' => false, 'error' => 'id 또는 ids 가 필요합니다.'], 400);
            return;
        }

        $who  = Auth::actorNow();
        $note = (string) ($b['note'] ?? '');
        $done = [];
        foreach ($ids as $i) {
            $r = Alerts::ack($i, actor: $who, note: $note);
            if ($r) {
                $done[] = $r['id'];
            }
        }
        if ($done) {
            Store::appendAudit('alert.ack', count($done) . '건 확인 — ' . implode(', ', array_slice($done, 0, 5)), actor: $who);
        }
        Http::jsonResponse([
            'ok'      => true,
            'acked'   => $done,
            'skipped' => count($ids) - count($done),
            'summary' => Alerts::summary(),
        ]);
    }

    // ── /api/alerts/history ───────────────────────────────────────

    public static function apiAlertsHistory(): void
    {
        Auth::sessionStartSafe();

        $limit = max(1, min(500, (int) ($_GET['limit'] ?? 200)));
        Http::jsonResponse(['ok' => true, 'history' => Alerts::history($limit)]);
    }

    // ── /api/report/daily (GET) ───────────────────────────────────

    public static function apiReportDaily(): void
    {
        Auth::sessionStartSafe();

        $day = $_GET['day'] ?? null;
        $doc = DailyReport::latest($day);
        if (!is_array($doc) || !$doc) {
            Http::jsonResponse([
                'ok'      => true,
                'exists'  => false,
                'day'     => $day,
                'history' => DailyReport::history(),
            ]);
            return;
        }
        Http::jsonResponse(['ok' => true, 'exists' => true, ...$doc, 'history' => DailyReport::history()]);
    }

    // ── /api/report/daily (POST) ──────────────────────────────────

    public static function apiReportDailyMake(): void
    {
        Auth::sessionStartSafe();

        $doc = DailyReport::publish(actor: Auth::actorNow());
        Http::jsonResponse(['ok' => true, ...$doc]);
    }

    // ── /api/report/preview ───────────────────────────────────────

    public static function apiReportPreview(): void
    {
        Auth::sessionStartSafe();

        $data = DailyReport::collect();
        Http::jsonResponse(['ok' => true, 'body' => DailyReport::render($data), 'data' => $data]);
    }

    // ── /api/inspection/draft ─────────────────────────────────────

    public static function apiInspectionDraft(): void
    {
        Auth::sessionStartSafe();

        $b   = Http::bodyJson();
        $iid = trim((string) ($b['eventId'] ?? ''));
        if (!$iid) {
            Http::jsonResponse(['ok' => false, 'error' => 'eventId 가 필요합니다.'], 400);
            return;
        }
        $ev = self::findIncident($iid);
        if (!$ev) {
            Http::jsonResponse(['ok' => false, 'code' => 6001, 'error' => "사건이 없습니다: {$iid}"], 404);
            return;
        }
        if (($ev['certainty'] ?? '') === 'rumor' && empty($b['force'])) {
            Http::jsonResponse(['ok' => false, 'code' => 6004, 'error' => '미확인(rumor) 사건입니다 — 공식 확인 뒤 만들거나 force 를 지정하세요.'], 409);
            return;
        }

        $rows  = self::loadResolved();
        $event = [
            'id'             => $iid,
            'country'        => $ev['country'] ?? null,
            'adminAreas'     => $ev['adminAreas'] ?? (isset($ev['admin1']) ? [$ev['admin1']] : []),
            'lat'            => $ev['lat'] ?? null,
            'lon'            => $ev['lon'] ?? null,
            'occurredAt'     => $ev['occurredAt'] ?? null,
            'location'       => $ev['admin1'] ?? '',
            'headline'       => $ev['title'] ?? null,
            'materialGroups' => $ev['materialGroups'] ?? [],
        ];

        $out      = Inspection::buildDrafts($rows, $event);
        $state    = Inspection::currentState();
        $recorded = 0;
        foreach ($out['drafts'] ?? [] as $d) {
            $key = [$iid, $d['bpName']];
            if (!isset($state[serialize($key)])) {
                // PHP equivalent: check (iid, bpName) pair in state
                $found = false;
                foreach ($state as $k => $v) {
                    if ($v['eventId'] === $iid && ($v['bpName'] ?? '') === $d['bpName']) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    Inspection::appendRecord([
                        'op'             => 'draft',
                        'eventId'        => $iid,
                        'bpName'         => $d['bpName'],
                        'actor'          => Auth::actorNow(),
                        'hotlineMissing' => $d['hotlineMissing'] ?? false,
                    ]);
                    $recorded++;
                }
            }
        }
        $out['recorded'] = $recorded;
        Http::jsonResponse(['ok' => true, ...$out]);
    }

    // ── /api/incidents/<iid>/targets ──────────────────────────────

    public static function apiIncidentTargets(string $iid): void
    {
        Auth::sessionStartSafe();

        $iid = trim($iid);
        $ev  = self::findIncident($iid);
        if (!$ev) {
            Http::jsonResponse(['ok' => false, 'code' => 6001, 'error' => "사건이 없습니다: {$iid}"], 404);
            return;
        }

        $event = [
            'id'             => $iid,
            'country'        => $ev['country'] ?? null,
            'adminAreas'     => $ev['adminAreas'] ?? (isset($ev['admin1']) ? [$ev['admin1']] : []),
            'lat'            => $ev['lat'] ?? null,
            'lon'            => $ev['lon'] ?? null,
            'materialGroups' => $ev['materialGroups'] ?? [],
        ];

        [$areas, $how] = Inspection::resolveEventAreas($event);
        $targets       = Inspection::targetsForEvent(
            self::loadResolved(),
            (string) ($event['country'] ?? ''),
            $areas
        );

        $names = array_unique(array_filter(array_column($targets, 'bpName')));
        sort($names);

        // BP 별 진행 상태 — 연락처 없음
        $state = [];
        foreach (Inspection::loadRecords() as $r) {
            if (($r['eventId'] ?? '') === $iid && !empty($r['bpName'])) {
                $state[$r['bpName']] = $r['op'] ?? '';
            }
        }

        // 사업장별 그룹
        $bySite = [];
        foreach ($targets as $t) {
            $bn = $t['bpName'] ?? '';
            $bySite[$bn][] = [
                'siteName'       => $t['siteName'] ?? null,
                'admin1'         => $t['admin1Ko'] ?? $t['admin1'] ?? null,
                'regionKo'       => $t['regionKo'] ?? null,
                'materialGroups' => $t['materialGroups'] ?? [],
            ];
        }

        // hasHotline — 연락처 유무(값 아님)
        $hotMap = [];
        foreach ($targets as $t) {
            $bn = $t['bpName'] ?? '';
            if (!isset($hotMap[$bn])) {
                $hotMap[$bn] = false;
            }
            $h = $t['hotline'] ?? [];
            if (is_array($h) && (!empty($h['contactName']) || !empty($h['phone']) || !empty($h['email']))) {
                $hotMap[$bn] = true;
            }
        }

        $rows = [];
        foreach ($names as $n) {
            $rows[] = [
                'bpName'     => $n,
                'op'         => $state[$n] ?? '',
                'sites'      => $bySite[$n] ?? [],
                'hasHotline' => $hotMap[$n] ?? false,
            ];
        }

        $done = count(array_filter($names, fn($n) => in_array($state[$n] ?? '', ['replied', 'closed', 'skipped'], true)));

        Http::jsonResponse([
            'ok'             => true,
            'areas'          => $areas,
            'areaSource'     => $how,
            'companies'      => $names,
            'bps'            => count($names),
            'sites'          => count($targets),
            'materialGroups' => array_values(array_unique($event['materialGroups'] ?? [])),
            'rows'           => $rows,
            'replied'        => $done,
        ]);
    }

    // ── /api/inspection/draft.eml ─────────────────────────────────

    public static function apiDraftEml(): void
    {
        Auth::sessionStartSafe();

        $b      = Http::bodyJson();
        $iid    = trim((string) ($b['eventId'] ?? ''));
        $bpName = trim((string) ($b['bpName']  ?? ''));
        if (!$iid || !$bpName) {
            Http::jsonResponse(['ok' => false, 'error' => 'eventId 와 bpName 이 필요합니다.'], 400);
            return;
        }
        $ev = self::findIncident($iid);
        if (!$ev) {
            Http::jsonResponse(['ok' => false, 'code' => 6001, 'error' => "사건이 없습니다: {$iid}"], 404);
            return;
        }
        if (($ev['certainty'] ?? '') === 'rumor' && empty($b['force'])) {
            Http::jsonResponse(['ok' => false, 'code' => 6004, 'error' => '미확인(rumor) 사건입니다 — 공식 확인 뒤 만들거나 force 를 지정하세요.'], 409);
            return;
        }

        $event = [
            'id'             => $iid,
            'country'        => $ev['country'] ?? null,
            'adminAreas'     => $ev['adminAreas'] ?? (isset($ev['admin1']) ? [$ev['admin1']] : []),
            'lat'            => $ev['lat'] ?? null,
            'lon'            => $ev['lon'] ?? null,
            'occurredAt'     => $ev['occurredAt'] ?? null,
            'location'       => $ev['admin1'] ?? '',
            'headline'       => $ev['title'] ?? null,
            'materialGroups' => $ev['materialGroups'] ?? [],
        ];

        $out = Inspection::buildDrafts(self::loadResolved(), $event);
        $d   = null;
        foreach ($out['drafts'] ?? [] as $draft) {
            if (($draft['bpName'] ?? '') === $bpName) {
                $d = $draft;
                break;
            }
        }
        if (!$d) {
            Http::jsonResponse(['ok' => false, 'code' => 6002, 'error' => "이 사건의 대상에 없습니다: {$bpName}"], 404);
            return;
        }
        if (!empty($d['hotlineMissing']) || empty($d['to'])) {
            Http::jsonResponse(['ok' => false, 'code' => 6003, 'error' => "{$bpName} 의 연락처가 비어 있습니다 — 명부를 먼저 채우세요."], 409);
            return;
        }

        try {
            $blob = Eml::draftBp($d['to'], $d['subject'], $d['body']);
        } catch (\ValueError $ex) {
            Http::jsonResponse(['ok' => false, 'error' => $ex->getMessage()], 400);
            return;
        }

        Inspection::appendRecord([
            'op'             => 'draft',
            'eventId'        => $iid,
            'bpName'         => $bpName,
            'actor'          => Auth::actorNow(),
            'hotlineMissing' => false,
            'channel'        => 'eml',
        ]);
        Store::appendAudit('inspection.draft.eml', "{$iid} · {$bpName} 초안 내려받음", actor: Auth::actorNow());

        $fname = Eml::filename('점검요청', "{$iid}_{$bpName}");
        header('Content-Type: message/rfc822');
        header('Content-Disposition: attachment; filename*=UTF-8\'\'' . rawurlencode($fname));
        header('X-Content-Type-Options: nosniff');
        echo $blob;
    }

    // ── /api/inspection/record ────────────────────────────────────

    public static function apiInspectionRecord(): void
    {
        Auth::sessionStartSafe();

        $b = Http::bodyJson();

        if (($b['op'] ?? '') === 'campaign.close') {
            $eid  = (string) ($b['eventId'] ?? '');
            $recs = array_filter(Inspection::loadRecords(), fn($r) => ($r['eventId'] ?? '') === $eid);
            $state = [];
            foreach ($recs as $r) {
                if (!empty($r['bpName'])) {
                    $state[$r['bpName']] = $r['op'] ?? '';
                }
            }
            $b['summary'] = [
                '대상' => count($state),
                '회신' => count(array_filter($state, fn($v) => in_array($v, ['replied', 'closed'], true))),
                '종결' => count(array_filter($state, fn($v) => $v === 'closed')),
                '반송' => count(array_filter($state, fn($v) => $v === 'bounced')),
            ];
        }

        [$rec, $err] = Inspection::makeRecord($b, actor: Auth::actorNow());
        if ($err) {
            Http::jsonResponse(['ok' => false, 'error' => $err], 400);
            return;
        }
        Inspection::appendRecord($rec);
        Store::appendAudit(
            'inspection.' . $rec['op'],
            trim(($rec['eventId'] ?? '') . ' ' . ($rec['bpName'] ?? '')),
            actor: Auth::actorNow()
        );
        Http::jsonResponse(['ok' => true, 'record' => $rec]);
    }

    // ── /api/inspection/reasons ───────────────────────────────────

    public static function apiInspectionReasons(): void
    {
        Auth::sessionStartSafe();

        $reasons = [];
        foreach (Inspection::REASON_REQUIRED as $op => $constName) {
            $table = Inspection::$$constName ?? constant("Inspection::{$constName}");
            $list  = [];
            foreach ($table as $k => $v) {
                $list[] = ['code' => $k, ...$v];
            }
            $reasons[$op] = $list;
        }
        Http::jsonResponse(['ok' => true, 'reasons' => $reasons]);
    }

    // ── /api/inspection/history ───────────────────────────────────

    public static function apiInspectionHistory(): void
    {
        Auth::sessionStartSafe();

        $iid  = trim($_GET['eventId'] ?? '');
        $bpq  = trim($_GET['bp'] ?? '');
        $recs = Inspection::loadRecords();

        if ($iid) {
            $recs = array_filter($recs, fn($r) => ($r['eventId'] ?? '') === $iid);
        }
        if ($bpq) {
            $recs = array_filter($recs, fn($r) => str_contains((string) ($r['bpName'] ?? ''), $bpq));
        }
        $recs = array_values($recs);

        // 상태 집계 (최후 상태만)
        $state = [];
        foreach ($recs as $r) {
            if (!empty($r['bpName'])) {
                $state[($r['eventId'] ?? '') . '|' . $r['bpName']] = $r;
            }
        }

        $by      = [];
        $replied = 0;
        $why     = [];
        foreach ($state as $v) {
            $op = $v['op'] ?? '';
            $by[$op] = ($by[$op] ?? 0) + 1;
            if (in_array($op, ['replied', 'closed'], true)) {
                $replied++;
            }
            $code = $v['reason'] ?? null;
            if ($code) {
                $constName = Inspection::REASON_REQUIRED[$op] ?? null;
                $tbl  = $constName ? (constant("Inspection::{$constName}") ?? []) : [];
                $meta = $tbl[$code] ?? ['label' => $code, 'next' => '-'];
                if (!isset($why[$code])) {
                    $why[$code] = ['code' => $code, 'label' => $meta['label'] ?? $code,
                                   'next' => $meta['next'] ?? '-', 'op' => $op, 'n' => 0];
                }
                $why[$code]['n']++;
            }
        }

        usort($why, fn($a, $b) => $b['n'] <=> $a['n']);
        $bpCount = count($state);
        $replyRate = $bpCount > 0 ? "{$replied}/{$bpCount}" : '0/0';

        Http::jsonResponse([
            'ok'      => true,
            'count'   => count($recs),
            'records' => array_slice($recs, -500),
            'summary' => [
                'bp'        => $bpCount,
                'byStatus'  => $by,
                'replied'   => $replied,
                'replyRate' => $replyRate,
                'whyNot'    => array_values($why),
            ],
        ]);
    }

    // ── /api/inspection/migrate ───────────────────────────────────

    public static function apiInspectionMigrate(): void
    {
        Auth::sessionStartSafe();

        $b     = Http::bodyJson();
        $items = $b['records'] ?? null;
        if (!is_array($items)) {
            Http::jsonResponse(['ok' => false, 'code' => 8001, 'error' => 'records 배열이 필요합니다.'], 400);
            return;
        }
        $eid   = trim((string) ($b['eventId'] ?? '')) ?: 'LEGACY-BROWSER';
        $have  = array_flip(array_map(
            fn($r) => ($r['bpName'] ?? '') . '|' . ($r['legacyTs'] ?? ''),
            array_filter(Inspection::loadRecords(), fn($r) => ($r['source'] ?? '') === 'localStorage')
        ));

        $added = 0;
        $dup   = 0;
        $bad   = 0;
        foreach ($items as $x) {
            if (!is_array($x) || !trim((string) ($x['bp'] ?? ''))) {
                $bad++;
                continue;
            }
            $legTs = (string) ($x['ts'] ?? $x['at'] ?? '');
            $key   = ($x['bp']) . '|' . $legTs;
            if (isset($have[$key])) {
                $dup++;
                continue;
            }
            $have[$key] = true;
            $rec = [
                'op'       => 'replied',
                'eventId'  => $eid,
                'bpName'   => $x['bp'],
                'actor'    => Auth::actorNow(),
                'source'   => 'localStorage',
                'legacyTs' => $legTs,
            ];
            foreach (['s1', 's2', 's3', 'stock', 'note', 'commReq'] as $k) {
                if (isset($x[$k]) && $x[$k] !== '') {
                    $rec[$k] = $x[$k];
                }
            }
            Inspection::appendRecord($rec);
            $added++;
        }

        if ($added) {
            Store::appendAudit('inspection.migrate', "브라우저 기록 {$added}건 이관", actor: Auth::actorNow());
        }
        Http::jsonResponse(['ok' => true, 'added' => $added, 'duplicated' => $dup, 'invalid' => $bad]);
    }

    // ── /api/gates ────────────────────────────────────────────────

    public static function apiGates(): void
    {
        Auth::sessionStartSafe();

        $b   = Http::bodyJson();
        $out = Gates::evaluate($b, g3RequiresOfficial: ($b['g3RequiresOfficial'] ?? null) === true);
        Http::jsonResponse(['ok' => true, ...$out]);
    }

    // ── /api/match ────────────────────────────────────────────────

    public static function apiMatch(): void
    {
        Auth::sessionStartSafe();

        $ev      = Http::bodyJson();
        $country = trim(strtoupper((string) ($ev['country'] ?? '')));
        if (!$country) {
            Http::jsonResponse(['ok' => false, 'error' => 'country 가 필요합니다.'], 400);
            return;
        }

        $vals   = AlertConfig::load();
        $margin = (int) ($vals['ADMIN_CANDIDATE_MARGIN_KM'] ?? 60);

        $lat   = $ev['lat'] ?? null;
        $lon   = $ev['lon'] ?? null;
        $areas = [];
        $how   = '';

        if ($lat !== null && $lon !== null) {
            try {
                $areas = GeoRef::adminAreaCandidates($country, (float) $lat, (float) $lon, marginKm: $margin);
                $n     = count($areas);
                $how   = "좌표 → 도/현 후보 {$n}개 (마진 {$margin}km)";
            } catch (\Throwable) {
                $areas = [];
            }
        }

        if (!$areas) {
            $names = $ev['areas'] ?? [];
            if (is_string($names)) {
                $names = [$names];
            } elseif (!is_array($names)) {
                $names = [];
            }
            $names = array_filter(array_map('strval', $names));
            $text  = trim((string) ($ev['regionText'] ?? ''));
            if ($text) {
                $names = array_merge($names, preg_split('/[,\s·\/]+/u', $text, -1, PREG_SPLIT_NO_EMPTY));
            }
            $hit   = $names ? GeoRef::resolveAdmin1($country, ...$names) : null;
            $areas = $hit ? [$hit] : [];
            $how   = "지역 이름 → " . ($hit ?: '해석 실패');
        }

        if (!$areas) {
            Http::jsonResponse([
                'ok'        => true,
                'how'       => $how ?: '판정할 지역을 찾지 못했습니다',
                'areas'     => [],
                'companies' => [],
                'sites'     => [],
            ]);
            return;
        }

        $rows = self::loadResolved();
        $hits = DetectRules::matchBps(
            ['country' => $country, 'lat' => $lat, 'lon' => $lon, 'place' => (string) ($ev['place'] ?? '')],
            $rows,
            $areas
        );

        $mats = array_filter(array_map(fn($m) => mb_strtolower((string) $m, 'UTF-8'), $ev['materialGroups'] ?? []));
        if ($mats) {
            $hits = array_filter($hits, function ($h) use ($mats) {
                $hm = array_map(fn($g) => mb_strtolower((string) $g, 'UTF-8'), $h['materialGroups'] ?? []);
                return (bool) array_intersect($mats, $hm);
            });
        }

        $byBp = [];
        foreach ($hits as $h) {
            $bn = $h['bpName'];
            if (!isset($byBp[$bn])) {
                $byBp[$bn] = ['bpName' => $bn, 'sites' => [], 'grade' => $h['grade'] ?? '-', 'tier' => $h['tier'] ?? '미등록'];
            }
            $byBp[$bn]['sites'][] = [
                'siteName'   => $h['siteName']   ?? null,
                'admin1'     => $h['admin1Ko']    ?? $h['admin1'] ?? null,
                'regionKo'   => $h['regionKo']   ?? null,
                'distanceKm' => $h['distanceKm'] ?? null,
            ];
        }

        $companies = array_values($byBp);
        usort($companies, fn($a, $b) => count($b['sites']) <=> count($a['sites']) ?: strcmp($a['bpName'], $b['bpName']));

        Http::jsonResponse(['ok' => true, 'how' => $how, 'areas' => $areas, 'companies' => $companies, 'sites' => count($hits)]);
    }

    // ── /api/llm ──────────────────────────────────────────────────

    public static function apiLlm(): void
    {
        Auth::sessionStartSafe();

        $vals = AlertConfig::load();
        if (($vals['LLM_ENABLED'] ?? '') !== 'true') {
            Http::jsonResponse(['ok' => false, 'code' => 4001, 'error' => 'AI 분석이 꺼져 있습니다 — 시스템 설정에서 켜세요.'], 503);
            return;
        }

        $b      = Http::bodyJson();
        $prompt = trim((string) ($b['prompt'] ?? ''));
        if (!$prompt) {
            Http::jsonResponse(['ok' => false, 'error' => 'prompt 가 비어 있습니다.'], 400);
            return;
        }
        if (mb_strlen($prompt, 'UTF-8') > 200000) {
            Http::jsonResponse(['ok' => false, 'error' => 'prompt 가 너무 깁니다.'], 413);
            return;
        }

        $cfg = AlertConfig::llmConfig($vals);
        try {
            $maxTokens   = (int) ($b['max_tokens']  ?? 2000);
            $temperature = (float) ($b['temperature'] ?? 0.0);
        } catch (\Throwable) {
            Http::jsonResponse(['ok' => false, 'error' => 'max_tokens 는 정수, temperature 는 숫자여야 합니다.'], 400);
            return;
        }

        try {
            $text = Llm::call($cfg, $prompt, maxTokens: $maxTokens, temperature: $temperature, timeout: (int) $cfg['timeout']);
        } catch (\RuntimeException $ex) {
            Http::jsonResponse(['ok' => false, 'error' => $ex->getMessage()], 502);
            return;
        }

        Store::appendAudit('llm.call', "{$cfg['format']}/{$cfg['model']} · " . mb_strlen($prompt, 'UTF-8') . '자', actor: Auth::actorNow());
        Http::jsonResponse(['ok' => true, 'text' => $text, 'model' => $cfg['model']]);
    }

    // ── /api/llm/test ─────────────────────────────────────────────

    public static function apiLlmTest(): void
    {
        Auth::sessionStartSafe();

        $vals = AlertConfig::load();
        $over = Http::bodyJson();
        foreach (['LLM_FORMAT', 'LLM_BASE', 'LLM_MODEL', 'LLM_EXTRA', 'LLM_TIMEOUT'] as $k) {
            if (!empty($over[$k])) {
                $vals[$k] = (string) $over[$k];
            }
        }
        if (!empty($over['LLM_KEY'])) {
            $vals['LLM_KEY'] = (string) $over['LLM_KEY'];
        }

        $result = Llm::probe(AlertConfig::llmConfig($vals));
        Store::appendAudit('llm.test', $result['ok'] ? '성공' : substr((string) ($result['error'] ?? ''), 0, 120), actor: Auth::actorNow());

        try {
            $cfg = AlertConfig::llmConfig($vals);
            Store::writeJson(self::LLM_PROBE_REL, [
                'ok'     => (bool) ($result['ok'] ?? false),
                'at'     => self::nowKst(),
                'actor'  => Auth::actorNow(),
                'format' => $vals['LLM_FORMAT'] ?? '',
                'base'   => $vals['LLM_BASE']   ?? '',
                'model'  => $vals['LLM_MODEL']  ?? '',
                'error'  => substr((string) ($result['error'] ?? ''), 0, 200),
            ]);
        } catch (\Throwable) {
            // 기록 실패 무시
        }

        Http::jsonResponse($result);
    }

    // ── /api/llm/status ───────────────────────────────────────────

    public static function apiLlmStatus(): void
    {
        Auth::sessionStartSafe();

        $vals = AlertConfig::load();
        $last = Store::readJson(self::LLM_PROBE_REL, default: null);
        $cur  = [
            'format' => $vals['LLM_FORMAT'] ?? '',
            'base'   => $vals['LLM_BASE']   ?? '',
            'model'  => $vals['LLM_MODEL']  ?? '',
        ];
        $stale = is_array($last) && array_filter(array_map(
            fn($k) => ($last[$k] ?? '') !== $cur[$k],
            array_keys($cur)
        ));

        $adaptersList = [];
        foreach (Llm::adapters() as $name => $label) {
            $adaptersList[] = ['name' => $name, 'label' => $label];
        }

        Http::jsonResponse([
            'ok'      => true,
            'enabled' => strtolower((string) ($vals['LLM_ENABLED'] ?? '')) === 'true',
            'keySet'  => !empty($vals['LLM_KEY']),
            'current' => $cur,
            'adapters'=> $adaptersList,
            'last'    => $last,
            'stale'   => (bool) $stale,
        ]);
    }

    // ── /api/llm/adapters ─────────────────────────────────────────

    public static function apiLlmAdapters(): void
    {
        Auth::sessionStartSafe();

        $out = [];
        foreach (Llm::adapters() as $name => $label) {
            $out[] = ['name' => $name, 'label' => $label];
        }
        Http::jsonResponse($out);
    }

    // ── /api/errors ───────────────────────────────────────────────

    public static function apiErrors(): void
    {
        Auth::sessionStartSafe();
        Http::jsonResponse(['ok' => true, 'stages' => ErrorCode::STAGES, 'rows' => ErrorCode::table()]);
    }

    // ── /api/disasters ────────────────────────────────────────────

    public static function apiDisasters(): void
    {
        Auth::sessionStartSafe();

        $limit = max(0, min(20000, (int) ($_GET['limit'] ?? 0)));
        $since = substr(trim($_GET['since'] ?? ''), 0, 32);

        $rows  = Collector::readDisasters(limit: $limit, since: $since);
        $byCat = [];
        foreach ($rows as $r) {
            $c = $r['category'] ?? '미분류';
            $byCat[$c] = ($byCat[$c] ?? 0) + 1;
        }
        arsort($byCat);
        $catList = [];
        foreach ($byCat as $c => $n) {
            $catList[] = [$c, $n];
        }

        Http::jsonResponse(['ok' => true, 'count' => count($rows), 'categories' => $catList, 'rows' => $rows]);
    }

    // ── 원장 · 재생성 · 리포트 (routes.py api_ledger / api_rebuild / api_report) ──
    // append-only 원장 — 기존 줄을 고치거나 지우는 경로는 만들지 않는다.

    public static function apiLedger(): void
    {
        $ev = Http::bodyJson();
        $op = $ev['op'] ?? null;
        if (!in_array($op, Rebuild::OPS, true)) {
            Http::jsonResponse(['ok' => false, 'code' => 8002, 'error' => "알 수 없는 op: {$op}", 'allowed' => Rebuild::OPS], 400);
            return;
        }
        $ev['ts']    = self::nowKst();
        $ev['actor'] = Auth::actorNow();
        $problems = Rebuild::validateEvent($ev);
        if ($problems) {
            Http::jsonResponse(['ok' => false, 'error' => '검증 실패', 'problems' => $problems], 400);
            return;
        }
        Store::appendLine('revision/ledger.jsonl', $ev);
        $bpOrSite = ($ev['bpKey'] ?? '') ?: ($ev['siteId'] ?? '');
        Store::appendAudit((string)($ev['op'] ?? 'ledger'), mb_substr("{$bpOrSite} · " . ($ev['reason'] ?? ''), 0, 180), (string)($ev['actor'] ?? ''));
        $result = Rebuild::rebuild();
        Http::jsonResponse([
            'ok'        => true,
            'appended'  => $ev,
            'stats'     => $result['stats'] ?? [],
            'conflicts' => array_slice($result['conflicts'] ?? [], -5),
            'skipped'   => array_slice($result['skipped'] ?? [], -5),
        ]);
    }

    public static function apiRebuild(): void
    {
        $result = Rebuild::rebuild();
        Http::jsonResponse([
            'ok'        => empty($result['errors']),
            'stats'     => $result['stats'] ?? [],
            'applied'   => count($result['applied'] ?? []),
            'conflicts' => $result['conflicts'] ?? [],
            'skipped'   => $result['skipped'] ?? [],
            'errors'    => $result['errors'] ?? [],
        ]);
    }

    public static function apiReport(): void
    {
        $path = Store::pathFor('build/BUILD_REPORT.md');
        if (!is_file($path)) {
            Http::jsonResponse(['ok' => false, 'error' => 'BUILD_REPORT 가 없습니다. rebuild 를 먼저 실행하세요.'], 404);
            return;
        }
        Http::jsonResponse(['ok' => true, 'report' => (string)file_get_contents($path)]);
    }
}
