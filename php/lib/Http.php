<?php
// -*- coding: utf-8 -*-
/**
 * Http — URL · 템플릿 렌더 · JSON 응답 · 요청 본문.
 *
 * routes.py 의 url_for() · render_template() · jsonify() · body_json() 과
 * @app_context_processor 4개(_auth_state · _rail_counts · _stamp · _profile)를 한 곳에 모았다.
 *
 * URL 접두어를 고정하지 않는다 — index.php 가 놓인 폴더를 그대로 base 로 쓴다.
 * DocumentRoot 바로 아래에 두면 base 는 '' 이고, /mbridgephp/ 아래에 두면 '/mbridgephp' 다.
 */
declare(strict_types=1);

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Auth.php';
require_once __DIR__ . '/Store.php';

final class Http
{
    private static ?string $base = null;

    // ── URL ───────────────────────────────────────────────────────

    /** index.php 가 놓인 URL 경로. 끝 슬래시 없음. 루트면 ''. */
    public static function base(): string
    {
        if (self::$base === null) {
            $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
            if (basename($script) === 'index.php') {
                $dir = dirname($script);
            } else {
                // php -S 의 정적 요청처럼 SCRIPT_NAME 이 index.php 가 아닐 때 —
                // 앱 폴더가 DocumentRoot 아래 어디에 있는지로 계산한다.
                $doc  = realpath((string)($_SERVER['DOCUMENT_ROOT'] ?? '')) ?: '';
                $root = realpath(Config::ROOT) ?: Config::ROOT;
                $dir  = ($doc !== '' && str_starts_with($root, $doc))
                    ? str_replace('\\', '/', substr($root, strlen($doc)))
                    : '/';
            }
            $dir = str_replace('\\', '/', $dir);
            self::$base = ($dir === '/' || $dir === '.' || $dir === '') ? '' : '/' . trim($dir, '/');
        }
        return self::$base;
    }

    /**
     * 앱 안 경로 → 절대 URL 경로.  url('/dashboard') → '/mbridgephp/dashboard'
     * 옛 '/mgscs/...' 표기가 들어오면 접두어를 벗겨 준다.
     */
    public static function url(string $path = '/', array $query = []): string
    {
        if (str_starts_with($path, '/mgscs/') || $path === '/mgscs') {
            $path = substr($path, 6) ?: '/';
        }
        if ($path === '' || $path[0] !== '/') {
            $path = '/' . $path;
        }
        $u = self::base() . $path;
        if ($query) {
            $u .= (str_contains($u, '?') ? '&' : '?') . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
        }
        return $u === '' ? '/' : $u;
    }

    /** 요청 경로에서 base 를 벗긴 라우팅 키. '/dashboard', '/api/resolved', '/' */
    public static function routeKey(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $base = self::base();
        if ($base !== '' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base));
        }
        // php -S 나 /index.php/xxx 형태
        if (str_starts_with($path, '/index.php')) {
            $path = substr($path, strlen('/index.php'));
        }
        $path = '/' . ltrim($path, '/');
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }
        return $path;
    }

    /** 우리 앱 안으로만 가는 경로인가 (열린 리다이렉트 방지). */
    public static function isLocalPath(string $p): bool
    {
        if ($p === '' || $p[0] !== '/' || str_starts_with($p, '//') || str_starts_with($p, '/\\')) {
            return false;
        }
        $base = self::base();
        return $base === '' || $p === $base || str_starts_with($p, $base . '/');
    }

    // ── 템플릿 ────────────────────────────────────────────────────

    /**
     * pages/ 아래 템플릿을 렌더링한다. 전역 컨텍스트를 자동 주입한다.
     * 옛 이름(mgscs_home.html 등)이 들어오면 새 이름(home.php)으로 바꿔 준다.
     */
    public static function render(string $template, array $vars = []): void
    {
        $name = preg_replace('/^mgscs_?/', '', pathinfo($template, PATHINFO_FILENAME)) ?: 'home';
        if ($name === '' || $name === 'mgscs') {
            $name = 'dashboard';
        }
        $file = Config::ROOT . '/pages/' . $name . '.php';
        if (!is_readable($file)) {
            http_response_code(500);
            echo htmlspecialchars('템플릿을 찾을 수 없습니다: ' . $template, ENT_QUOTES, 'UTF-8');
            return;
        }
        $merged = array_merge(self::globalContext(), ['here' => $name], $vars);
        (static function (string $__file, array $__vars): void {
            extract($__vars, EXTR_SKIP);
            require $__file;
        })($file, $merged);
    }

    /** routes.py 의 context processor 4개 합산. */
    public static function globalContext(): array
    {
        $ctx = [
            'logged_in'   => Auth::loggedIn(),
            'is_admin'    => Auth::isAdmin(),
            'actor_now'   => Auth::actorNow(),
            'app_version' => Config::appVersion(),
            'updated_at'  => Config::updatedAt(),
            'app_base'    => self::base(),
        ];
        try {
            $prof = Store::readJson('PROFILE.json', []) ?: [];
        } catch (Throwable) {
            $prof = [];
        }
        $ctx['data_profile']      = $prof['profile'] ?? 'real';
        $ctx['data_profile_meta'] = $prof;

        $rail = ['n_open_events' => null, 'n_hotline_gap' => null];
        if (Auth::loggedIn()) {
            try {
                $incs = Store::readJson('revision/incidents.json', []) ?: [];
                $rail['n_open_events'] = count(array_filter($incs, fn($x) => ($x['status'] ?? 'open') !== 'closed'));
            } catch (Throwable) {}
            try {
                $rows = Store::readJson('build/bp_resolved.json', []) ?: [];
                $rows = array_filter($rows, fn($r) => ($r['active'] ?? true) !== false);
                $all  = array_unique(array_column($rows, 'bpName'));
                $have = array_unique(array_column(array_filter($rows, function ($r) {
                    $h = $r['hotline'] ?? [];
                    return is_array($h) && array_filter($h);
                }), 'bpName'));
                $rail['n_hotline_gap'] = count($all) - count($have);
            } catch (Throwable) {}
        }
        $ctx['rail'] = $rail;
        return $ctx;
    }

    // ── 응답 ──────────────────────────────────────────────────────

    public static function jsonResponse(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public static function redirect(string $url, int $status = 302): void
    {
        http_response_code($status);
        header('Location: ' . $url);
    }

    /**
     * POST 본문을 **배열(dict)로만** 받는다. routes.py body_json() 이식.
     * JSON 이 아니거나 리스트면 [] — 뒤에서 ['key'] 를 읽다 500 이 나지 않게.
     */
    public static function bodyJson(): array
    {
        $raw = file_get_contents('php://input');
        if ($raw === false || $raw === '') {
            return $_POST ?: [];
        }
        if (strlen($raw) > Config::MAX_BODY_BYTES) {
            return [];
        }
        $d = json_decode($raw, true);
        if (is_array($d) && !array_is_list($d)) {
            return $d;
        }
        return is_array($d) && $d === [] ? [] : ($_POST ?: []);
    }
}

// 템플릿용 짧은 헬퍼
if (!function_exists('url')) {
    function url(string $path = '/', array $query = []): string
    {
        return Http::url($path, $query);
    }
}
