<?php
// -*- coding: utf-8 -*-
/**
 * Pages — 화면 처리기 전부. routes.py 의 화면 라우트 이식.
 *
 * 라우팅표는 index.php 에 있다. 여기는 "무엇을 그리는가" 만 든다.
 *
 *   hub        허브(최초 진입) — 화면·API 목록과 서버 상태
 *   home       대문
 *   dashboard  Risk 현황 (비로그인은 연락처를 뺀 명부)
 *   system     판정 기준 문서
 *   inspect    점검 현황
 *   bp         BP 기준 정보 (관리자)
 *   revision   등록·수정 (관리자)
 *   review     감지 검토 (관리자)
 *   settings   시스템 설정 (관리자)
 *   login / logout
 */
declare(strict_types=1);

final class Pages
{
    // 비로그인에게 줄 칸만 적는다. 막을 칸을 세지 않는다. (routes.py PUBLIC_SITE_FIELDS)
    public const PUBLIC_SITE_FIELDS = [
        'bpId', 'bpName', 'bpAliases', 'materialGroups', 'grade', 'tier',
        'country', 'regionCode', 'region', 'regionKo',
        'admin1', 'admin1Ko', 'admin1Romaji', 'prefecture', 'prefectureKo',
        'siteId', 'siteName', 'siteRole', 'address', 'lat', 'lon',
        'precision', 'coordinateStatus', 'distanceEligible', 'mapVisible', 'active',
        'recordId',
    ];
    public const CONTACT_FIELDS  = ['hotline', 'contacts'];
    private const CONTACT_PATTERN = '/(\+?\d[\d\-() ]{7,}\d)|([\w.+-]+@[\w-]+\.[\w.]+)/u';
    private static array $scrubSeen = [];

    // ── 허브 — 최초 진입 ──────────────────────────────────────────

    /**
     * @param array $pages  index.php 의 화면 라우팅표
     * @param array $apis   index.php 의 API 라우팅표
     */
    public static function hub(array $pages, array $apis): void
    {
        $checks = self::healthChecks();
        $stats  = ['sites' => null, 'companies' => null, 'countries' => null];
        try {
            $rows = self::loadResolved();
            $stats['sites']     = count($rows);
            $stats['companies'] = count(array_unique(array_column($rows, 'bpName')));
            $stats['countries'] = count(array_filter(array_unique(array_column($rows, 'country'))));
        } catch (\Throwable $e) {
            $checks[] = ['명부 재생성', false, get_class($e) . ': ' . $e->getMessage()];
        }
        $collect = null;
        try {
            $collect = Collector::lastStatus();
        } catch (\Throwable) {}

        Http::render('hub.php', [
            'pages'   => $pages,
            'apis'    => $apis,
            'checks'  => $checks,
            'stats'   => $stats,
            'collect' => $collect,
            'php'     => PHP_VERSION,
            'data_dir' => Config::dataDir(),
        ]);
    }

    /** 서버가 이 앱을 돌릴 준비가 됐는가. [이름, 통과여부, 설명] */
    public static function healthChecks(): array
    {
        $out = [];
        $out[] = ['PHP 8.1 이상', PHP_VERSION_ID >= 80100, 'PHP ' . PHP_VERSION . (PHP_VERSION_ID >= 80400 ? ' (8.4 배포 기준 충족)' : '')];
        foreach (['json', 'mbstring', 'openssl', 'curl', 'session'] as $ext) {
            $out[] = ["확장 {$ext}", extension_loaded($ext), extension_loaded($ext) ? '켜짐' : '꺼짐 — php.ini 에서 켜야 한다'];
        }
        $dd = Config::dataDir();
        $out[] = ['데이터 폴더 쓰기 가능', is_dir($dd) && is_writable($dd), $dd];
        $out[] = ['기준 명부 baseline/bp_sites.json', is_file($dd . '/baseline/bp_sites.json'), '없으면 화면이 비어 보인다'];
        $out[] = ['재생성 산출물 build/bp_resolved.json', is_file($dd . '/build/bp_resolved.json'), '첫 요청 때 자동으로 만든다'];
        $out[] = ['관리자 비밀번호 설정', Auth::isConfigured(), Auth::isConfigured() ? Config::authFile() : '아직 없다 — 로그인 화면에서 최초 설정'];
        $rewrite = (function_exists('apache_get_modules') ? in_array('mod_rewrite', apache_get_modules(), true) : null);
        $out[] = ['URL 재작성(mod_rewrite)', $rewrite ?? true, $rewrite === null ? '판별 불가(비 Apache) — 아래 API 점검으로 확인' : ($rewrite ? '켜짐' : '꺼짐 — .htaccess 가 동작하지 않는다')];
        $out[] = ['세션 저장 경로 쓰기 가능', is_writable(session_save_path() ?: sys_get_temp_dir()), session_save_path() ?: sys_get_temp_dir()];
        return $out;
    }

    // ── 대문 ──────────────────────────────────────────────────────

    public static function home(): void
    {
        $rows = self::loadResolved();

        $statOpenEvents = null;
        $nowLine = null;
        $nowLink = null;
        try {
            $incs = self::loadIncidents();
            $open = array_values(array_filter($incs, fn($x) => ($x['status'] ?? 'open') !== 'closed'));
            $statOpenEvents = count($open);
            if ($open && Auth::loggedIn()) {
                usort($open, fn($a, $b) => strcmp((string)($a['occurredAt'] ?? ''), (string)($b['occurredAt'] ?? '')));
                $latest = end($open);
                $eid    = trim((string)($latest['id'] ?? ''));
                $state  = [];
                foreach (Inspection::loadRecords() as $r) {
                    if (($r['eventId'] ?? '') === $eid && !empty($r['bpName'])) {
                        $state[$r['bpName']] = $r['op'] ?? '';
                    }
                }
                $done  = count(array_filter($state, fn($v) => in_array($v, ['replied', 'closed', 'skipped'], true)));
                $title = (string)($latest['title'] ?? '');
                if ($state) {
                    $nowLine = "{$title} — 점검 " . count($state) . "곳 중 {$done}곳 회신";
                    $nowLink = ['inspect', '이어서 점검하기'];
                } else {
                    $nowLine = "{$title} — 아직 점검을 시작하지 않았습니다";
                    $nowLink = ['inspect', '점검 시작하기'];
                }
            }
        } catch (\Throwable) {}

        $funnel = null;
        try {
            $funnel = SignalReview::funnel(24);
        } catch (\Throwable) {}

        $summary = null;
        if (Auth::loggedIn()) {
            try {
                $summary = DailyReport::collect();
            } catch (\Throwable) {}
        }

        $verified  = array_filter($rows, fn($r) => ($r['coordinateStatus'] ?? '') === 'verified_site');
        $hot       = array_filter($rows, function ($r) {
            $h = $r['hotline'] ?? [];
            return is_array($h) && array_filter($h);
        });
        $companies = array_unique(array_column($rows, 'bpName'));
        $countries = array_filter(array_unique(array_column($rows, 'country')));

        $snapDir = Config::ROOT . '/static/260611_junghoon/mgscs';
        $snaps   = is_dir($snapDir)
            ? array_filter(scandir($snapDir) ?: [], fn($f) => str_starts_with($f, '20') && str_ends_with($f, '.json'))
            : [];

        Http::render('home.php', [
            'funnel'           => $funnel,
            'summary'          => $summary,
            'alerts_pending'   => Auth::loggedIn() ? Alerts::pending() : [],
            'stat_open_events' => $statOpenEvents,
            'now_line'         => $nowLine,
            'now_link'         => $nowLink,
            'stat_sites'       => count($rows),
            'stat_companies'   => count($companies),
            'stat_countries'   => count($countries),
            'stat_verified'    => count($verified),
            'stat_hotline_pct' => $rows ? sprintf('%d%%', (int)round(count($hot) / count($rows) * 100)) : '0%',
            'stat_snapshots'   => count($snaps) . '일',
            'build_date'       => self::today(),
        ]);
    }

    // ── Risk 현황 ─────────────────────────────────────────────────

    public static function dashboard(): void
    {
        [$rows, $taxonomy] = self::embedPayload();
        if (!Auth::loggedIn()) {
            $rows = self::publicRows($rows);
        }
        Http::render('dashboard.php', [
            'bp_rows_json'     => json_encode($rows, JSON_UNESCAPED_UNICODE),
            'taxonomy_json'    => json_encode($taxonomy, JSON_UNESCAPED_UNICODE),
            'match_vocab_json' => json_encode($rows ? self::matchVocab($rows) : [], JSON_UNESCAPED_UNICODE),
        ]);
    }

    // ── 판정 기준 ─────────────────────────────────────────────────

    public static function system(): void
    {
        Http::render('system.php', ['build_date' => self::today()]);
    }

    // ── 점검 현황 ─────────────────────────────────────────────────

    public static function inspect(): void
    {
        $sla = 24;
        try {
            $raw = trim((string)(AlertConfig::load()['INSPECTION_SLA_HOURS'] ?? '24'));
            $sla = $raw !== '' ? (int)$raw : 24;
        } catch (\Throwable) {}
        Http::render('inspect.php', ['here_label' => '점검', 'sla_hours' => $sla]);
    }

    // ── 관리자 화면 ───────────────────────────────────────────────

    public static function bp(): void
    {
        [$rows, ] = self::embedPayload();
        Http::render('bp.php', [
            'bp_rows_json'  => json_encode($rows, JSON_UNESCAPED_UNICODE),
            'category_json' => json_encode(MaterialGroups::CATEGORY, JSON_UNESCAPED_UNICODE),
            'actor'         => Auth::actorNow(),
            'build_date'    => self::today(),
        ]);
    }

    /** 옛 주소 /roster → /bp */
    public static function roster(): void
    {
        Http::redirect(Http::url('/bp', $_GET));
    }

    /** 옛 주소 /hotline → /bp?tab=hot */
    public static function hotline(): void
    {
        Http::redirect(Http::url('/bp', array_merge($_GET, ['tab' => 'hot'])));
    }

    public static function revision(): void
    {
        [$rows, $taxonomy] = self::embedPayload();
        $reportPath = Store::pathFor('build/BUILD_REPORT.md');
        Http::render('revision.php', [
            'bp_rows_json'  => json_encode($rows, JSON_UNESCAPED_UNICODE),
            'taxonomy_json' => json_encode($taxonomy, JSON_UNESCAPED_UNICODE),
            'build_report'  => is_file($reportPath) ? (string)file_get_contents($reportPath) : '',
            'actor'         => Auth::actorNow(),
        ]);
    }

    public static function review(): void
    {
        Http::render('review.php');
    }

    public static function settings(): void
    {
        $history = [];
        $histPath = Store::pathFor('revision/alert_config_history.jsonl');
        if (is_file($histPath)) {
            foreach (file($histPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
                $dec = json_decode($line, true);
                if (is_array($dec)) {
                    $history[] = $dec;
                }
            }
        }
        $values = AlertConfig::load();
        Http::render('settings.php', [
            'fields_json'  => json_encode(AlertConfig::FIELDS, JSON_UNESCAPED_UNICODE),
            'values_json'  => json_encode(AlertConfig::public($values), JSON_UNESCAPED_UNICODE),
            'history_json' => json_encode($history, JSON_UNESCAPED_UNICODE),
            'actor'        => Auth::actorNow(),
        ]);
    }

    // ── 로그인 · 로그아웃 (routes.py login()/logout()) ────────────

    public static function login(): void
    {
        // next 는 우리 앱 안으로만. 열린 리다이렉트 방지.
        $nxt = trim((string)($_REQUEST['next'] ?? ''));
        if (!Http::isLocalPath($nxt)) {
            $nxt = Http::url('/bp');
        }

        // 최초 비밀번호 설정 — 한 번만 열린다.
        if (!Auth::isConfigured()) {
            $error = null;
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $emp = trim((string)($_POST['actor'] ?? ''));
                $pw1 = (string)($_POST['password'] ?? '');
                $pw2 = (string)($_POST['password2'] ?? '');
                if ($emp === '') {
                    $error = '사번을 넣어주세요.';
                } elseif ($pw1 !== $pw2) {
                    $error = '두 입력이 다릅니다.';
                } elseif (mb_strlen($pw1) < 8) {
                    $error = '비밀번호는 8자 이상이어야 합니다.';
                } else {
                    Auth::setup($pw1);
                    Store::appendAudit('auth.setup', '최초 비밀번호 설정', $emp);
                    session_regenerate_id(true);
                    $_SESSION = ['actor' => $emp];
                    Http::redirect($nxt);
                    return;
                }
            }
            Http::render('login.php', ['setup' => true, 'next' => $nxt, 'error' => $error, 'lock' => 0]);
            return;
        }

        $error = null;
        $lock  = 0;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $who  = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $lock = Auth::lockedFor($who);
            if ($lock > 0) {
                $error = "시도가 너무 잦습니다. {$lock}초 뒤에 다시 해주세요.";
            } else {
                $emp = trim((string)($_POST['actor'] ?? ''));
                $pw  = (string)($_POST['password'] ?? '');
                if ($emp === '') {
                    $error = '사번을 넣어주세요.';
                } elseif (Auth::verify($pw)) {
                    Auth::clearFailures($who);
                    session_regenerate_id(true);
                    $_SESSION = ['actor' => $emp];
                    Http::redirect($nxt);
                    return;
                } else {
                    Auth::noteFailure($who);
                    $error = '비밀번호가 맞지 않습니다.';
                }
            }
        }
        Http::render('login.php', ['setup' => false, 'next' => $nxt, 'error' => $error, 'lock' => $lock]);
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        Http::redirect(Http::url('/'));
    }

    // ── 공용 헬퍼 (Api 도 쓴다) ───────────────────────────────────

    public static function today(): string
    {
        return (new \DateTime('now', new \DateTimeZone('Asia/Seoul')))->format('Y-m-d');
    }

    /** 활성 명부. 산출물이 없으면 먼저 재생성한다. */
    public static function loadResolved(): array
    {
        $path = Store::pathFor('build/bp_resolved.json');
        if (!is_file($path)) {
            Rebuild::rebuild();
        }
        $rows = is_file($path) ? (json_decode((string)file_get_contents($path), true) ?: []) : [];
        return array_values(array_filter($rows, fn($r) => ($r['active'] ?? true) !== false));
    }

    public static function loadIncidents(): array
    {
        return Store::readJson('revision/incidents.json', []) ?: [];
    }

    /** @return array{0: array, 1: array}  [명부, taxonomy] */
    public static function embedPayload(): array
    {
        $rows     = self::loadResolved();
        $taxPath  = Store::pathFor('baseline/taxonomy.json');
        $taxonomy = is_file($taxPath) ? (json_decode((string)file_get_contents($taxPath), true) ?: []) : [];
        return [$rows, $taxonomy];
    }

    /** 비로그인에게 줄 형태 — 허용한 칸만 골라 담는다. routes.py _public_rows() */
    public static function publicRows(array $rows): array
    {
        $out = [];
        $scrubbed = [];
        foreach ($rows as $r) {
            $r2 = [];
            foreach (self::PUBLIC_SITE_FIELDS as $k) {
                if (array_key_exists($k, $r)) {
                    $r2[$k] = $r[$k];
                }
            }
            foreach (array_keys($r2) as $k) {
                $v = $r2[$k];
                if (is_string($v) && $v !== '' && preg_match(self::CONTACT_PATTERN, $v)) {
                    unset($r2[$k]);
                    $scrubbed[] = $k;
                }
            }
            $has = false;
            foreach (self::CONTACT_FIELDS as $cf) {
                $c = $r[$cf] ?? null;
                if (!is_array($c)) {
                    continue;
                }
                if (array_is_list($c)) {
                    foreach ($c as $x) {
                        if (is_array($x) && (!empty($x['phone']) || !empty($x['email']))) {
                            $has = true;
                            break;
                        }
                    }
                } elseif (!empty($c['contactName']) || !empty($c['phone']) || !empty($c['email'])) {
                    $has = true;
                }
            }
            $r2['hasHotline'] = $has;
            $out[] = $r2;
        }
        $fresh = array_values(array_diff(array_unique($scrubbed), self::$scrubSeen));
        if ($fresh) {
            self::$scrubSeen = array_merge(self::$scrubSeen, $fresh);
            sort($fresh);
            try {
                Store::appendAudit('public.scrub', '공개 명부에서 연락처처럼 보이는 값을 뺀 칸: ' . implode(', ', $fresh), 'system');
            } catch (\Throwable) {}
        }
        return $out;
    }

    /** LLM 이 고를 수 있는 낱말만 추린다 — 명부에 실제로 있는 것만. */
    public static function matchVocab(array $rows): array
    {
        $areas = [];
        $mats = [];
        $countries = [];
        foreach ($rows as $r) {
            if (($r['active'] ?? true) === false) {
                continue;
            }
            $c = $r['country'] ?? null;
            if (!$c) {
                continue;
            }
            $countries[$c] = true;
            $a = $r['admin1Ko'] ?? $r['admin1'] ?? null;
            if ($a) {
                $areas[$c][$a] = true;
            }
            foreach ($r['materialGroups'] ?? [] as $g) {
                $mats[$g] = true;
            }
        }
        $areasSorted = [];
        foreach ($areas as $cc => $aMap) {
            ksort($aMap);
            $areasSorted[$cc] = array_keys($aMap);
        }
        ksort($areasSorted);
        ksort($countries);
        $matList = array_keys($mats);
        sort($matList);
        return [
            'countries'      => array_keys($countries),
            'areas'          => $areasSorted,
            'materialGroups' => $matList,
            'companies'      => count(array_unique(array_filter(array_column($rows, 'bpName')))),
            'sites'          => count(array_filter($rows, fn($r) => ($r['active'] ?? true) !== false)),
        ];
    }
}
