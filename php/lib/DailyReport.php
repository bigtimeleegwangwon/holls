<?php
// -*- coding: utf-8 -*-
/**
 * DailyReport — 일간 보고 생성·조회.
 * daily_report.py 완전 이식본.
 *
 * 만들기까지만 한다. 서버는 SMTP 를 쓰지 않는다.
 * 아무것도 없는 날에도 보고서를 만든다 — '조용하다'와 '죽었다'를 구별하게.
 */
declare(strict_types=1);

require_once __DIR__ . '/Store.php';
require_once __DIR__ . '/Collector.php';
require_once __DIR__ . '/Alerts.php';
require_once __DIR__ . '/../config.php';

final class DailyReport
{
    private const REPORT_DIR = 'reports';

    // ── KST 유틸 ──────────────────────────────────────────────────

    private static function kst(): DateTimeZone
    {
        return new DateTimeZone('Asia/Seoul');
    }

    private static function nowKst(): DateTime
    {
        return new DateTime('now', self::kst());
    }

    // ── 수집 ──────────────────────────────────────────────────────

    /**
     * 지금 시각 기준으로 보고에 필요한 데이터를 모은다.
     * daily_report.py collect() 이식.
     *
     * @return array<string,mixed>
     */
    public static function collect(?DateTime $now = null): array
    {
        if ($now === null) {
            $now = self::nowKst();
        }
        $data = [
            'generatedAt' => $now->format('Y-m-d\TH:i:sP'),
            'day' => $now->format('Y-m-d'),
        ];

        // 1. 수집 상태
        try {
            $status = Store::readJson('signals/collector_status.json', []) ?? [];
            $data['collector'] = [
                'enabled' => ($status['collectEnabled'] ?? false),
                'lastRun' => $status['lastCycleAt'] ?? null,
                'intervalMin' => (int)($status['intervalMin'] ?? 60),
            ];
        } catch (Throwable) {
            $data['collector'] = ['enabled' => false, 'lastRun' => null, 'intervalMin' => 60];
        }

        // 2. 사건 원장
        try {
            $incs = Store::readJson('revision/incidents.json', []) ?? [];
            $open = array_filter($incs, fn($x) => ($x['status'] ?? 'open') !== 'closed');
            $high = array_filter($open, fn($x) => ($x['severity'] ?? '') === 'high');
            $med  = array_filter($open, fn($x) => ($x['severity'] ?? '') === 'medium');
            $data['incidents'] = [
                'total' => count($incs),
                'open' => count($open),
                'high' => count($high),
                'medium' => count($med),
                'openList' => array_values(array_map(
                    fn($x) => ['id' => $x['id'] ?? '', 'title' => $x['title'] ?? '', 'severity' => $x['severity'] ?? '', 'occurredAt' => $x['occurredAt'] ?? ''],
                    array_slice(array_values($open), -10)
                )),
            ];
        } catch (Throwable) {
            $data['incidents'] = ['total' => 0, 'open' => 0, 'high' => 0, 'medium' => 0, 'openList' => []];
        }

        // 3. 신호 요약 (최근 24시간)
        try {
            $cutKst = clone $now;
            $cutKst->modify('-24 hours');
            $cutStr = $cutKst->format('Y-m-d');
            $signals = [];
            for ($d = 0; $d < 2; $d++) {
                $day = clone $now;
                $day->modify("-{$d} days");
                $fn = 'signals/' . $day->format('Ymd') . '.json';
                $arr = Store::readJson($fn, []) ?? [];
                if (is_array($arr)) {
                    $signals = array_merge($signals, $arr);
                }
            }
            $reviewed = array_filter($signals, fn($s) => !empty($s['grade']));
            $highSig  = array_filter($reviewed, fn($s) => ($s['grade'] ?? '') === 'high');
            $data['signals'] = [
                'total24h' => count($signals),
                'reviewed' => count($reviewed),
                'high' => count($highSig),
            ];
        } catch (Throwable) {
            $data['signals'] = ['total24h' => 0, 'reviewed' => 0, 'high' => 0];
        }

        // 4. 미확인 이상
        try {
            $alertsRel = 'revision/alerts.jsonl';
            $alertRows = Store::readJsonl($alertsRel);
            $pending = array_filter($alertRows, fn($a) => empty($a['ackedAt']));
            $data['alerts'] = [
                'pending' => count($pending),
                'items' => array_values(array_slice(array_values($pending), -5)),
            ];
        } catch (Throwable) {
            $data['alerts'] = ['pending' => 0, 'items' => []];
        }

        // 5. 점검 이력 (최근 7일)
        try {
            $recs = Store::readJsonl('revision/inspection.jsonl');
            $cutTs = (clone $now)->modify('-7 days')->getTimestamp();
            $recent = array_filter($recs, function ($r) use ($cutTs) {
                try {
                    $ts = (new DateTime($r['ts'] ?? $r['at'] ?? '1970-01-01'))->getTimestamp();
                    return $ts >= $cutTs;
                } catch (Throwable) {
                    return false;
                }
            });
            $state = [];
            foreach ($recent as $r) {
                if ($r['bpName'] ?? '') {
                    $state[($r['eventId'] ?? '') . '|' . $r['bpName']] = $r['op'] ?? '';
                }
            }
            $replied = count(array_filter($state, fn($op) => in_array($op, ['replied', 'closed'], true)));
            $data['inspection'] = [
                'activeBps' => count($state),
                'replied' => $replied,
                'replyRate' => count($state) > 0 ? round($replied / count($state) * 100, 1) : 0,
            ];
        } catch (Throwable) {
            $data['inspection'] = ['activeBps' => 0, 'replied' => 0, 'replyRate' => 0];
        }

        // 6. 화면(home.php)이 읽는 모양 — daily_report.py collect() 의 키를 그대로 얹는다.
        //    감시(watch) · 선별(triage) · 영향(impact) · 미결(unclosed). 메일 본문(render)은 위 1~5 를 쓴다.
        try {
            $since = (clone $now)->modify('-24 hours');
            $h     = Collector::health($now);
            $incs  = Store::readJson('revision/incidents.json', []) ?: [];
            $fresh = [];
            $openOld = [];
            foreach ($incs as $x) {
                $imported = self::parseTs($x['importedAt'] ?? null) ?? self::parseTs($x['occurredAt'] ?? null);
                $isOpen   = ($x['status'] ?? 'open') !== 'closed';
                if ($imported && $imported >= $since && $imported <= $now) {
                    $fresh[] = $x;
                } elseif ($isOpen) {
                    $openOld[] = $x;
                }
            }
            [$signals, $files] = Collector::readSignals(2);
            $today    = $now->format('Y-m-d');
            $sigToday = array_values(array_filter($signals, fn($s) => substr((string)($s['ingestedAt'] ?? ''), 0, 10) === $today));
            $bpOf     = fn(array $x) => isset($x['bpCount']) ? (int)$x['bpCount'] : null;
            $ingestAts = array_map(fn($x) => (string)($x['ingestedAt'] ?? ''), $sigToday);
            $sched = $h['schedule'] ?? [];
            $data['window'] = ['since' => $since->format('Y-m-d\TH:i:sP'), 'until' => $now->format('Y-m-d\TH:i:sP')];
            $data['watch'] = [
                'cyclesToday'    => $h['cyclesToday'] ?? 0,
                'lastRunAt'      => $h['lastRunAt'] ?? null,
                'ageMinutes'     => $h['ageMinutes'] ?? null,
                'overdue'        => $h['overdue'] ?? false,
                'failingSources' => $h['failingSources'] ?? [],
                'signalsToday'   => count($sigToday),
                'signalFiles'    => $files,
                'schedule'       => $sched,
                'serverCollect'  => (bool)($sched['collectEnabled'] ?? false),
                'ingestToday'    => count($sigToday),
                'lastIngestAt'   => $ingestAts ? max($ingestAts) : '',
                'ingestRuns'     => count(array_unique(array_map(fn($a) => substr($a, 0, 16), array_filter($ingestAts)))),
            ];
            usort($fresh, fn($a, $b) => [($a['severity'] ?? '') === 'high' ? 0 : 1, (string)($a['occurredAt'] ?? '')]
                                    <=> [($b['severity'] ?? '') === 'high' ? 0 : 1, (string)($b['occurredAt'] ?? '')]);
            $data['triage'] = [
                'new'   => count($fresh),
                'high'  => count(array_filter($fresh, fn($x) => ($x['severity'] ?? '') === 'high')),
                'items' => array_slice($fresh, 0, 20),
            ];
            $data['impact'] = [
                'withBp'   => array_values(array_filter($fresh, fn($x) => ($bpOf($x) ?? 0) > 0)),
                'noImpact' => array_values(array_filter($fresh, fn($x) => $bpOf($x) === 0)),
                'unknown'  => array_values(array_filter($fresh, fn($x) => $bpOf($x) === null)),
                'bpTotal'  => array_sum(array_map(fn($x) => $bpOf($x) ?? 0, $fresh)),
            ];
            $data['pending']      = Alerts::pending();
            $data['alertSummary'] = Alerts::summary($now);
            usort($openOld, fn($a, $b) => strcmp((string)($b['occurredAt'] ?? ''), (string)($a['occurredAt'] ?? '')));
            $data['unclosed'] = array_slice($openOld, 0, 20);
        } catch (Throwable) {
            $data += ['watch' => ['ingestToday' => 0, 'ingestRuns' => 0, 'lastIngestAt' => '', 'serverCollect' => false, 'failingSources' => [], 'signalsToday' => 0],
                      'triage' => ['new' => 0, 'high' => 0, 'items' => []],
                      'impact' => ['withBp' => [], 'noImpact' => [], 'unknown' => [], 'bpTotal' => 0],
                      'pending' => [], 'alertSummary' => [], 'unclosed' => []];
        }

        return $data;
    }

    /** ISO 시각 → DateTime. 실패하면 null. */
    private static function parseTs(mixed $v): ?DateTime
    {
        if ($v === null || $v === '') {
            return null;
        }
        try {
            $d = new DateTime(str_replace('Z', '+00:00', (string)$v));
            return $d;
        } catch (Throwable) {
            return null;
        }
    }

    // ── 렌더링 ────────────────────────────────────────────────────

    /**
     * 수집된 데이터를 텍스트 본문으로 만든다.
     * daily_report.py render() 이식.
     */
    public static function render(array $data): string
    {
        $day = $data['day'] ?? '';
        $inc = $data['incidents'] ?? [];
        $sig = $data['signals'] ?? [];
        $alr = $data['alerts'] ?? [];
        $insp = $data['inspection'] ?? [];

        $lines = [];
        $lines[] = "=== mGSCS 일간 보고 [{$day}] ===";
        $lines[] = '';

        // 한 줄 결론 (3행)
        $lines[] = '【결론】';
        if (($inc['high'] ?? 0) > 0) {
            $lines[] = "  ● 고위험 사건 {$inc['high']}건 진행 중";
        }
        if (($alr['pending'] ?? 0) > 0) {
            $lines[] = "  ● 미확인 이상 {$alr['pending']}건 미처리";
        }
        if (($inc['high'] ?? 0) === 0 && ($alr['pending'] ?? 0) === 0) {
            $lines[] = '  ○ 긴급 항목 없음';
        }
        $lines[] = '';

        // 사건
        $lines[] = '【미완료 사건】';
        $lines[] = "  열린 사건 {$inc['open']}건 (고위험 {$inc['high']} / 중위험 {$inc['medium']})";
        foreach ($inc['openList'] ?? [] as $x) {
            $sev = $x['severity'] ?? '';
            $lines[] = "  - [{$sev}] {$x['title']} ({$x['occurredAt']})";
        }
        $lines[] = '';

        // 신호
        $lines[] = '【신호 감지 (24h)】';
        $lines[] = "  수집 {$sig['total24h']}건 → 판정 {$sig['reviewed']}건 → 고위험 {$sig['high']}건";
        $lines[] = '';

        // 점검
        $lines[] = '【점검 이력 (7일)】';
        $lines[] = "  대상 {$insp['activeBps']}사 · 회신 {$insp['replied']}사 ({$insp['replyRate']}%)";
        $lines[] = '';

        // 미확인 이상
        if (($alr['pending'] ?? 0) > 0) {
            $lines[] = '【미확인 이상】';
            foreach ($alr['items'] ?? [] as $a) {
                $lines[] = '  - ' . ($a['message'] ?? $a['type'] ?? '알 수 없음');
            }
            $lines[] = '';
        }

        $lines[] = '--- end of report ---';
        return implode("\n", $lines);
    }

    // ── 저장/조회 ─────────────────────────────────────────────────

    /**
     * 지금 시각으로 보고서를 만들고 저장한다.
     * daily_report.py publish() 이식.
     *
     * @return array<string,mixed>
     */
    public static function publish(string $actor = 'system', ?DateTime $now = null): array
    {
        if ($now === null) {
            $now = self::nowKst();
        }
        $data = self::collect($now);
        $body = self::render($data);
        $data['body'] = $body;
        $data['publishedBy'] = $actor;

        $rel = self::REPORT_DIR . '/' . $now->format('Ymd') . '.json';
        Store::writeJson($rel, $data);

        return $data;
    }

    /**
     * 특정 날의 보고서를 읽는다. daily_report.py latest() 이식.
     * day 가 null 이면 오늘 것을 읽는다.
     */
    public static function latest(?string $day = null): array
    {
        if ($day === null) {
            $day = self::nowKst()->format('Ymd');
        }
        // 날짜 형식 검증 — /d{8}/ 만
        if (!preg_match('/^\d{8}$/', $day)) {
            return [];
        }
        $rel = self::REPORT_DIR . '/' . $day . '.json';
        $doc = Store::readJson($rel, null);
        if (!is_array($doc)) {
            return [];
        }
        return $doc;
    }

    /**
     * 저장된 보고 날짜 목록. daily_report.py history() 이식.
     *
     * @return string[]  YYYYMMDD 내림차순
     */
    public static function history(int $limit = 30): array
    {
        try {
            $dir = Store::sandbox(self::REPORT_DIR);
        } catch (Throwable) {
            return [];
        }
        if (!is_dir($dir)) {
            return [];
        }
        $files = glob($dir . DIRECTORY_SEPARATOR . '????????.json') ?: [];
        $days = array_map(
            fn($f) => basename($f, '.json'),
            array_filter($files, fn($f) => preg_match('/\d{8}\.json$/', basename($f)))
        );
        rsort($days);
        return array_slice($days, 0, $limit);
    }
}
