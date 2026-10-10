<?php
// -*- coding: utf-8 -*-
/**
 * Collector — 수집 사이클 관리.
 * collector.py 이식본 (PHP 환경에 맞게 조정).
 *
 * Python 의 threading.Thread 스케줄러 대신 외부 cron 이 collect.php 를 호출한다.
 * 이 클래스는 상태 관리, 신호 저장, 재난 원장, 연결 시험만 담당한다.
 * 실제 외부 소스 수집(detect_sources.fetch_all)은 cron/collect.php 에서 수행한다.
 */
declare(strict_types=1);

require_once __DIR__ . '/Store.php';
require_once __DIR__ . '/../config.php';

final class Collector
{
    public const STATUS_REL   = 'signals/collector_status.json';
    public const LOCK_REL     = 'signals/collector.lock';
    public const DISASTER_REL = 'disasters.jsonl';

    private const KST          = 'Asia/Seoul';
    private const TRACK_PARAMS = ['utm_source','utm_medium','utm_campaign','utm_term','utm_content',
                                   'fbclid','gclid','igshid','spm','ref','referer','referrer',
                                   'from','cds','sid','input'];

    // ── 환경 플래그 ───────────────────────────────────────────────

    public static function collectEnabled(): bool
    {
        $v = strtolower(trim(getenv('MGSCS_COLLECT') ?: '0'));
        return in_array($v, ['1', 'true', 'on', 'yes'], true);
    }

    public static function reviewEnabled(): bool
    {
        $v = strtolower(trim(getenv('MGSCS_REVIEW') ?: '1'));
        return in_array($v, ['1', 'true', 'on', 'yes'], true);
    }

    public static function schedule(): array
    {
        $intervalMin = max(5, (int)(getenv('MGSCS_COLLECT_MIN') ?: 60));
        $reportHour  = (int)(getenv('MGSCS_REPORT_HOUR') ?: 6);
        return ['intervalMin' => $intervalMin, 'reportHour' => $reportHour];
    }

    // ── URL 정규화 ────────────────────────────────────────────────

    /**
     * 기사 URL 을 한 가지 모양으로 편다. collector.py normalize_signal_id() 이식.
     */
    public static function normalizeSignalId(string $value): string
    {
        $s = trim($value);
        if (!str_contains($s, '://')) {
            return $s;
        }
        $u = parse_url($s);
        if (!$u || !isset($u['scheme']) || !in_array(strtolower($u['scheme']), ['http', 'https'], true)) {
            return $s;
        }
        $host = strtolower($u['host'] ?? '');
        // 접두 제거 (반복)
        $changed = true;
        while ($changed) {
            $changed = false;
            foreach (['www.', 'm.', 'n.', 'mobile.', 'amp.'] as $pre) {
                if (str_starts_with($host, $pre) && substr_count($host, '.') > substr_count($pre, '.')) {
                    $rest = substr($host, strlen($pre));
                    if (str_contains($rest, '.')) {
                        $host    = $rest;
                        $changed = true;
                        break;
                    }
                }
            }
        }
        $port = $u['port'] ?? null;
        if ($port && !in_array($port, [80, 443], true)) {
            $host = "{$host}:{$port}";
        }
        $path = $u['path'] ?? '/';
        if (str_starts_with($path, '/mnews/')) {
            $path = substr($path, strlen('/mnews'));
        }
        if (strlen($path) > 1 && str_ends_with($path, '/')) {
            $path = rtrim($path, '/') ?: '/';
        }
        // 쿼리 파라미터 — 트래킹 제거
        $query = [];
        if (!empty($u['query'])) {
            parse_str($u['query'], $qs);
            foreach ($qs as $k => $v) {
                if (!in_array(strtolower($k), self::TRACK_PARAMS, true)) {
                    $query[$k] = $v;
                }
            }
        }
        $queryStr = http_build_query($query, '', '&', PHP_QUERY_RFC3986);
        return 'https://' . $host . $path . ($queryStr ? '?' . $queryStr : '');
    }

    // ── 신호 수집 ─────────────────────────────────────────────────

    /**
     * 브라우저가 모은 신호를 서버에 쌓는다.
     * collector.py ingest_signals() 이식.
     */
    public static function ingestSignals(array $items, string $source = 'browser', string $actor = 'unknown'): array
    {
        $kst = new DateTimeZone(self::KST);
        $day = (new DateTime('now', $kst))->format('Ymd');
        $rel = 'signals/' . $day . '.json';
        $now = (new DateTime('now', $kst))->format('Y-m-d\TH:i:sP');

        // URL 정규화
        $normalized = [];
        foreach ($items as $x) {
            if (!is_array($x)) {
                continue;
            }
            $y = $x;
            foreach (['id', 'link', 'url'] as $k) {
                if (!empty($y[$k]) && is_string($y[$k])) {
                    $n = self::normalizeSignalId($y[$k]);
                    if ($n !== $y[$k]) {
                        if ($k === 'id') {
                            $y['rawId'] = $y[$k];
                        }
                        $y[$k] = $n;
                    }
                }
            }
            if (empty($y['id']) && !empty($y['link'])) {
                $y['id'] = $y['link'];
            }
            $normalized[] = $y;
        }

        // 읽기-쓰기 락
        $lockPath = Store::sandbox(dirname($rel)) . DIRECTORY_SEPARATOR . basename($rel) . '.lock';
        if (!is_dir(dirname($lockPath))) {
            mkdir(dirname($lockPath), 0755, true);
        }
        $lf = fopen($lockPath, 'c+');
        if ($lf) {
            flock($lf, LOCK_EX);
        }
        try {
            $have = Store::readJson($rel, []) ?? [];
            $seen = [];
            foreach ($have as $x) {
                $key = self::normalizeSignalId((string)($x['id'] ?? $x['link'] ?? ''));
                if ($key) {
                    $seen[$key] = true;
                }
            }

            $fresh = [];
            $bad   = 0;
            foreach ($normalized as $x) {
                $key = trim((string)($x['id'] ?? $x['link'] ?? $x['title'] ?? ''));
                if (!$key || isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;
                $y = $x;
                $y['id']           = $y['id'] ?? $key;
                $y['ingestedAt']   = $now;
                $y['ingestedBy']   = $actor;
                $y['ingestSource'] = $source;
                $fresh[]           = $y;
            }
            if ($fresh) {
                Store::writeJson($rel, array_merge($have, $fresh), false);
                self::appendDisasterLedger($fresh);
            }
        } finally {
            if ($lf) {
                flock($lf, LOCK_UN);
                fclose($lf);
            }
        }

        $duplicated = count($normalized) - count($fresh ?? []) - ($bad ?? 0);
        if ($fresh ?? []) {
            Store::appendAudit(
                'signals.ingest',
                "{$source} → 신규 " . count($fresh) . '건',
                $actor
            );
        }

        return [
            'added'      => count($fresh ?? []),
            'duplicated' => max(0, $duplicated),
            'invalid'    => $bad ?? 0,
            'dayTotal'   => count(($have ?? [])) + count($fresh ?? []),
            'day'        => $day,
            'droppedIds' => [],
        ];
    }

    /**
     * 최근 N일치 신호를 읽는다.
     * collector.py read_signals() 이식.
     *
     * @return array{0: array, 1: array}  [신호 배열, 파일명 배열]
     */
    public static function readSignals(int $days = 7): array
    {
        $days = max(1, $days);
        try {
            $dir = Store::sandbox('signals');
        } catch (Throwable) {
            return [[], []];
        }
        if (!is_dir($dir)) {
            return [[], []];
        }
        $files = [];
        foreach (glob($dir . DIRECTORY_SEPARATOR . '2*.json') ?: [] as $f) {
            $base = basename($f);
            if (preg_match('/^\d{8}\.json$/', $base)) {
                $files[] = $base;
            }
        }
        sort($files);
        $files = array_slice($files, -$days);
        $out = [];
        foreach ($files as $fn) {
            $rows = Store::readJson('signals/' . $fn, []) ?? [];
            if (is_array($rows)) {
                $out = array_merge($out, $rows);
            }
        }
        return [$out, $files];
    }

    // ── 상태 ──────────────────────────────────────────────────────

    /**
     * 마지막 사이클 상태를 읽는다.
     * collector.py last_status() 이식.
     */
    public static function lastStatus(): ?array
    {
        try {
            $v = Store::readJson(self::STATUS_REL, null);
            return is_array($v) ? $v : null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * 스케줄러 상태 요약.
     */
    public static function schedulerState(): array
    {
        $st = self::lastStatus() ?? [];
        return [
            'collectEnabled' => self::collectEnabled(),
            'reviewEnabled'  => self::reviewEnabled(),
            'schedule'       => self::schedule(),
            'lastCycleAt'    => $st['ranAt'] ?? null,
            'cyclesToday'    => $st['cyclesToday'] ?? 0,
        ];
    }

    /**
     * 감시가 살아 있나 판정.
     * collector.py health() 이식.
     */
    public static function health(?DateTime $now = null): array
    {
        $kst = new DateTimeZone(self::KST);
        if ($now === null) {
            $now = new DateTime('now', $kst);
        }
        if (!$now->getTimezone() || $now->getTimezone()->getName() === 'UTC') {
            $now->setTimezone($kst);
        }
        $sc  = self::schedule();
        $st  = self::lastStatus() ?? [];
        $ran = $st['ranAt'] ?? null;
        $last = null;
        if ($ran) {
            try {
                $last = new DateTime((string)$ran);
                if (!$last->getTimezone() || $last->getTimezone()->getName() === 'UTC') {
                    $last->setTimezone($kst);
                }
            } catch (Throwable) {
                $last = null;
            }
        }
        $ageMin  = $last ? ($now->getTimestamp() - $last->getTimestamp()) / 60 : null;
        $overdue = ($ageMin === null) || ($ageMin > $sc['intervalMin'] * 2.5);
        $bad = [];
        foreach ($st['sources'] ?? [] as $s) {
            if (empty($s['ok'])) {
                $bad[] = $s['name'] ?? '';
            }
        }
        // 다음 실행 시각 계산 (정시 맞춤)
        $stepH  = max(1, (int)round($sc['intervalMin'] / 60));
        $nextTs = (clone $now)->modify("+{$stepH} hours");
        $nextTs->setTime((int)$nextTs->format('H'), 0, 0);

        return [
            'overdue'        => $overdue,
            'lastRunAt'      => $ran,
            'ageMinutes'     => $ageMin !== null ? round($ageMin, 1) : null,
            'cyclesToday'    => $st['cyclesToday'] ?? 0,
            'failingSources' => $bad,
            'schedule'       => $sc,
            'nextRunAt'      => $nextTs->format('Y-m-d\TH:i:sP'),
            'checkedAt'      => $now->format('Y-m-d\TH:i:sP'),
        ];
    }

    // ── 연결 시험 ─────────────────────────────────────────────────

    /**
     * 서버가 인터넷에 나갈 수 있나 — 어디서 막히는지까지 가른다.
     * collector.py connectivity_test() 이식.
     */
    public static function connectivityTest(int $timeout = 8): array
    {
        $probes = [
            ['name' => 'JMA',    'url' => 'https://www.jma.go.jp/bosai/quake/data/list.json', 'kind' => '재해'],
            ['name' => 'USGS',   'url' => 'https://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/significant_week.geojson', 'kind' => '재해'],
            ['name' => 'GDACS',  'url' => 'https://www.gdacs.org/xml/rss.xml',                'kind' => '재해'],
            ['name' => 'Google News', 'url' => 'https://news.google.com/rss/search?q=%EB%B0%98%EB%8F%84%EC%B2%B4&hl=ko', 'kind' => '뉴스'],
            ['name' => '연합뉴스',    'url' => 'https://www.yna.co.kr/rss/industry.xml',       'kind' => '뉴스'],
        ];

        $results = [];
        foreach ($probes as $p) {
            $results[] = self::probeOne($p['name'], $p['url'], $p['kind'], $timeout);
        }

        $dis    = array_filter($results, fn($r) => $r['kind'] === '재해');
        $news   = array_filter($results, fn($r) => $r['kind'] === '뉴스');
        $disOk  = count(array_filter($dis,  fn($r) => !empty($r['ok'])));
        $newsOk = count(array_filter($news, fn($r) => !empty($r['ok'])));

        $bad       = array_filter($results, fn($r) => empty($r['ok']));
        $atHttp    = array_filter($bad, fn($r) => ($r['stage'] ?? '') === 'HTTP');
        $gateBlocked = !empty($bad) && count($atHttp) === count($bad);
        $codes     = array_unique(array_filter(array_column(array_values($atHttp), 'http')));
        sort($codes);

        if ($disOk && $newsOk) {
            $verdict = '나간다 — 뉴스까지 서버가 직접 수집할 수 있다';
            $state   = 'open';
        } elseif ($gateBlocked && !$disOk && !$newsOk) {
            $codesStr = implode('·', $codes) ?: '403';
            $verdict  = "[9002] 게이트웨이가 막는다(HTTP {$codesStr}) — 이 시스템에서는 정상입니다. "
                      . '서버는 나갈 필요가 없습니다: 수집은 브라우저가 하고 서버는 판정만 합니다.';
            $state    = 'gated';
        } elseif ($disOk && !$newsOk) {
            $verdict = '재해 피드만 나간다 — 뉴스가 막혔다. 수집은 브라우저에 남기고 판정만 서버로 옮기는 편이 낫다';
            $state   = 'partial';
        } elseif ($newsOk && !$disOk) {
            $verdict = '뉴스만 나간다 — 재해 피드가 막혔다. 방화벽 예외를 요청해야 한다';
            $state   = 'partial';
        } else {
            $verdict = '[9003] 아무 데도 못 나간다 — 아래 stage 를 보고 그것부터 푼다.';
            $state   = 'closed';
        }

        $kst = new DateTimeZone(self::KST);
        return [
            'verdict' => $verdict,
            'state'   => $state,
            'summary' => ['재해' => "{$disOk}/" . count(array_values($dis)), '뉴스' => "{$newsOk}/" . count(array_values($news))],
            'sources' => $results,
            'proxy'   => [
                'http_proxy'  => getenv('http_proxy')  ?: '',
                'https_proxy' => getenv('https_proxy') ?: '',
                'HTTP_PROXY'  => getenv('HTTP_PROXY')  ?: '',
                'HTTPS_PROXY' => getenv('HTTPS_PROXY') ?: '',
            ],
            'at' => (new DateTime('now', $kst))->format('Y-m-d\TH:i:sP'),
        ];
    }

    // ── 재난 원장 ─────────────────────────────────────────────────

    /**
     * 재난 원장에 이름·출처만 추가한다.
     * collector.py _append_disaster_ledger() 이식.
     */
    public static function appendDisasterLedger(array $rows): void
    {
        $kst = new DateTimeZone(self::KST);
        $now = (new DateTime('now', $kst))->format('Y-m-d\TH:i:sP');
        foreach ($rows as $x) {
            if (!is_array($x)) {
                continue;
            }
            try {
                Store::appendJsonl(self::DISASTER_REL, [
                    'at'           => $x['ingestedAt'] ?? $now,
                    'category'     => $x['category']   ?? '',
                    'why'          => $x['keepReason']  ?? '',
                    'title'        => mb_substr((string)($x['titleKo'] ?? $x['title'] ?? ''), 0, 200),
                    'source'       => $x['source']      ?? '',
                    'link'         => $x['link'] ?? $x['id'] ?? '',
                    'pubDate'      => $x['pubDate']     ?? '',
                    'official'     => !empty($x['official']),
                    'corroboration' => (int)($x['corroboration'] ?? 0),
                    'by'           => $x['ingestedBy']  ?? '',
                ]);
            } catch (Throwable) {
                // 원장 실패해도 수집은 살린다
            }
        }
    }

    /**
     * 재난 원장을 읽는다. 최신이 뒤로.
     * collector.py read_disasters() 이식.
     */
    public static function readDisasters(int $limit = 0, string $since = ''): array
    {
        try {
            $rows = Store::readJsonl(self::DISASTER_REL);
        } catch (Throwable) {
            return [];
        }
        if ($since) {
            $rows = array_filter($rows, fn($r) => ((string)($r['at'] ?? '')) >= $since);
        }
        $rows = array_values($rows);
        if ($limit > 0 && count($rows) > $limit) {
            $rows = array_slice($rows, -$limit);
        }
        return $rows;
    }

    // ── 한 사이클 (PHP cron 에서 호출) ───────────────────────────

    /**
     * 한 사이클을 PHP 에서 실행한다.
     * Python 의 run_cycle() 역할 — DetectRules, SignalReview 를 조율한다.
     *
     * PHP 는 실제 외부 소스 수집(detect_sources.fetch_all)을 구현하지 않으므로
     * 수집 활성 여부에 따라 분기하고, 판정만 실행한다.
     */
    public static function runCycle(string $actor = 'collector', bool $catchUp = false): array
    {
        require_once __DIR__ . '/SignalReview.php';
        require_once __DIR__ . '/DailyReport.php';
        require_once __DIR__ . '/AlertConfig.php';

        $kst     = new DateTimeZone(self::KST);
        $started = (new DateTime('now', $kst))->format('Y-m-d\TH:i:sP');
        $sc      = self::schedule();

        $reviewed  = 0;
        $candidates = 0;
        $llmFailed  = 0;
        $llmWhy     = '';

        if (self::reviewEnabled()) {
            // 판정 (최대 REVIEW_ROUNDS × REVIEW_BATCH)
            $rounds     = 0;
            $batchSize  = 30;
            $maxRounds  = 10;
            while ($rounds < $maxRounds) {
                $r = SignalReview::review(days: 1, limit: $batchSize, actor: $actor);
                $rounds++;
                $n = (int)($r['reviewed'] ?? 0);
                $reviewed   += $n;
                $candidates += count($r['candidates'] ?? []);
                $llmFailed  += (int)($r['llmFailed'] ?? 0);
                if (!$llmWhy && !empty($r['llmWhy'])) {
                    $llmWhy = $r['llmWhy'];
                }
                if ($n < $batchSize) {
                    break;
                }
            }
        }

        // 일간 보고서
        $now = new DateTime('now', $kst);
        if (self::reportDue($now, $sc)) {
            try {
                DailyReport::publish(actor: $actor, now: $now);
                self::markReport($now);
            } catch (Throwable $ex) {
                Store::appendAudit('report.error', get_class($ex) . ': ' . $ex->getMessage(), $actor);
            }
        }

        // 상태 갱신
        $prev = self::lastStatus() ?? [];
        $prevAt = null;
        if (!empty($prev['ranAt'])) {
            try {
                $prevAt = new DateTime((string)$prev['ranAt']);
            } catch (Throwable) {}
        }
        $gapH = null;
        if ($prevAt) {
            $gapH = ($now->getTimestamp() - $prevAt->getTimestamp()) / 3600;
        }

        $status = [
            'ranAt'               => $started,
            'prevRanAt'           => $prev['ranAt'] ?? null,
            'finishedAt'          => (new DateTime('now', $kst))->format('Y-m-d\TH:i:sP'),
            'catchUp'             => $catchUp,
            'gapHours'            => $gapH !== null ? round($gapH, 2) : null,
            'intervalMin'         => $sc['intervalMin'],
            'collectEnabled'      => self::collectEnabled(),
            'reviewEnabled'       => self::reviewEnabled(),
            'reviewed'            => $reviewed,
            'candidates'          => $candidates,
            'llmFailed'           => $llmFailed,
            'llmWhy'              => $llmWhy,
            'cyclesToday'         => (
                (isset($prev['ranAt']) && substr((string)$prev['ranAt'], 0, 10) === substr($started, 0, 10))
                    ? (int)($prev['cyclesToday'] ?? 0) + 1 : 1
            ),
            'schedule'            => $sc,
        ];
        // lastReportDay 이어받기
        foreach (['lastReportDay', 'lastReportAt'] as $k) {
            if (isset($prev[$k])) {
                $status[$k] = $prev[$k];
            }
        }
        Store::writeJson(self::STATUS_REL, $status, false);
        Store::appendAudit('collect.cycle',
            "판정 {$reviewed}건 · 후보 {$candidates}건" . ($llmFailed ? " · LLM 실패 {$llmFailed}" : ''),
            $actor
        );

        return $status;
    }

    // ── 보고서 트리거 ────────────────────────────────────────────

    public static function reportDue(DateTime $now, array $sc): bool
    {
        if ((int)$now->format('H') < (int)($sc['reportHour'] ?? 6)) {
            return false;
        }
        $st = self::lastStatus() ?? [];
        return (string)($st['lastReportDay'] ?? '') !== $now->format('Y-m-d');
    }

    public static function markReport(DateTime $now): void
    {
        $st = self::lastStatus() ?? [];
        $st['lastReportDay'] = $now->format('Y-m-d');
        $st['lastReportAt']  = $now->format('Y-m-d\TH:i:sP');
        Store::writeJson(self::STATUS_REL, $st, false);
    }

    // ── 연결 탐색 내부 ────────────────────────────────────────────

    private static function probeOne(string $name, string $url, string $kind, int $timeout): array
    {
        $host = parse_url($url, PHP_URL_HOST) ?: '';
        $step = ['name' => $name, 'kind' => $kind, 'host' => $host];

        // ① DNS
        $addrs = @dns_get_record($host, DNS_A | DNS_AAAA);
        if (empty($addrs)) {
            return array_merge($step, [
                'dns' => 'fail', 'ok' => false, 'stage' => 'DNS',
                'why' => "이름을 못 찾는다. 서버가 내부 DNS 만 보고 있거나 이름 해석이 막혀 있다.",
            ]);
        }
        $step['dns'] = 'ok';

        // ② TCP
        $proxy    = getenv('https_proxy') ?: getenv('HTTPS_PROXY') ?: getenv('http_proxy') ?: getenv('HTTP_PROXY') ?: '';
        $tcpHost  = $proxy ? (parse_url($proxy, PHP_URL_HOST) ?: $host) : $host;
        $tcpPort  = $proxy ? (parse_url($proxy, PHP_URL_PORT) ?: 8080)  : 443;
        $sock     = @fsockopen($tcpHost, (int)$tcpPort, $errno, $errstr, $timeout);
        if (!$sock) {
            return array_merge($step, [
                'tcp' => 'fail', 'ok' => false, 'stage' => 'TCP',
                'why' => "{$tcpHost}:{$tcpPort} 에 연결이 안 된다 ({$errstr})."
                       . ($proxy ? ' 프록시 주소·포트를 확인해야 한다.' : ' 방화벽에 막혔거나 프록시를 지정해야 한다.'),
            ]);
        }
        fclose($sock);
        $step['tcp'] = 'ok';

        // ③④ TLS · HTTP (curl)
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 3,
            CURLOPT_USERAGENT      => 'mGSCS-probe/1.0',
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        if ($proxy) {
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
        }
        $body   = curl_exec($ch);
        $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        if ($curlErr) {
            if (str_contains($curlErr, 'SSL') || str_contains($curlErr, 'certificate')) {
                return array_merge($step, [
                    'tls' => 'fail', 'ok' => false, 'stage' => 'TLS',
                    'why' => "TLS 가 깨졌다 ({$curlErr}). 사내 SSL 검사 장비의 인증서를 서버가 못 믿는 것이다.",
                ]);
            }
            return array_merge($step, ['ok' => false, 'stage' => '기타', 'why' => $curlErr]);
        }

        $hints = [
            407 => '[9004] 프록시 인증이 필요하다 — 계정을 넣은 주소로 지정해야 한다.',
            403 => '[9002] 차단됐다 — 카테고리 차단이거나 봇 차단이다. 이 시스템에서는 정상이다.',
            401 => '인증이 필요하다.',
        ];
        if ($httpCode >= 200 && $httpCode < 300) {
            return array_merge($step, ['tls' => 'ok', 'http' => $httpCode, 'ok' => true, 'bytes' => strlen((string)$body), 'why' => '나간다']);
        }
        return array_merge($step, [
            'tls' => 'ok', 'http' => $httpCode, 'ok' => false, 'stage' => 'HTTP',
            'why' => $hints[$httpCode] ?? "서버가 거부했다 (HTTP {$httpCode}).",
        ]);
    }
}
