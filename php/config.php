<?php
declare(strict_types=1);

final class Config
{
    // 앱 루트 — index.php 가 있는 폴더
    public const ROOT = __DIR__;

    // 환경변수 이름
    public const DATA_DIR_ENV      = 'MGSCS_DATA_DIR';
    public const AUTH_FILE_ENV     = 'MGSCS_AUTH_FILE';
    public const COLLECT_ENV       = 'MGSCS_COLLECT';
    public const COLLECT_MIN_ENV   = 'MGSCS_COLLECT_MIN';
    public const REPORT_HOUR_ENV   = 'MGSCS_REPORT_HOUR';
    public const HTTP_PROXY_ENV    = 'MGSCS_HTTP_PROXY';
    public const DEFAULT_ACTOR_ENV = 'MGSCS_DEFAULT_ACTOR';
    public const SET_PASSWORD_ENV  = 'MGSCS_SET_PASSWORD';

    // 세션 수명 — auth.py SESSION_HOURS=12
    public const SESSION_LIFETIME  = 43200;

    // 본문 크기 상한 — webmain.py MAX_CONTENT_LENGTH
    public const MAX_BODY_BYTES    = 33554432;

    // 앱 이름 (허브·탭 제목)
    public const APP_NAME          = 'mGSCS';

    // URL 접두어는 더 이상 고정하지 않는다 — index.php 위치에서 자동으로 잡는다.
    // (Http::base() 참고.) 옛 코드 호환용으로 빈 문자열을 둔다.
    public const URL_PREFIX        = '';

    private static ?string $dataDir  = null;
    private static ?string $authFile = null;

    /** 데이터 루트. 기본은 앱 폴더 안의 data/ */
    public static function dataDir(): string
    {
        if (self::$dataDir === null) {
            $env = getenv(self::DATA_DIR_ENV);
            self::$dataDir = ($env !== false && $env !== '')
                ? rtrim($env, '/\\')
                : self::ROOT . DIRECTORY_SEPARATOR . 'data';
        }
        return self::$dataDir;
    }

    /** 자격 파일. 기본은 data/auth.json (웹에서 .htaccess 로 막힌다) */
    public static function authFile(): string
    {
        if (self::$authFile === null) {
            $env = getenv(self::AUTH_FILE_ENV);
            self::$authFile = ($env !== false && $env !== '')
                ? $env
                : self::dataDir() . DIRECTORY_SEPARATOR . 'auth.json';
        }
        return self::$authFile;
    }

    /** VERSION 파일 한 곳에서만 읽는다. */
    public static function appVersion(): string
    {
        $f = self::ROOT . DIRECTORY_SEPARATOR . 'VERSION';
        if (is_readable($f)) {
            $v = trim((string)file_get_contents($f));
            if ($v !== '') {
                return 'v' . $v;
            }
        }
        return 'v0';
    }

    /**
     * 갱신일 — "오늘"이 아니다. 실제로 손댄 파일의 수정 시각 중 가장 최근.
     * routes.py updated_at() 이식: 루트·pages·static·data/build 를 훑는다.
     */
    public static function updatedAt(): string
    {
        $latest = 0;
        foreach (['', 'pages', 'static', 'lib', 'routes', 'data' . DIRECTORY_SEPARATOR . 'build'] as $sub) {
            $d = self::ROOT . ($sub === '' ? '' : DIRECTORY_SEPARATOR . $sub);
            if (!is_dir($d)) {
                continue;
            }
            foreach (scandir($d) ?: [] as $f) {
                if ($f[0] === '.') {
                    continue;
                }
                $p = $d . DIRECTORY_SEPARATOR . $f;
                if (is_file($p)) {
                    $latest = max($latest, (int)filemtime($p));
                }
            }
        }
        if ($latest === 0) {
            return '';
        }
        return (new DateTime('@' . $latest))->setTimezone(new DateTimeZone('Asia/Seoul'))->format('Y-m-d');
    }
}
