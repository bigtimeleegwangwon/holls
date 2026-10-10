<?php
// -*- coding: utf-8 -*-
/**
 * Auth — 로그인, PBKDF2-SHA256, 연속 실패 잠금.
 *
 * auth.py 의 완전 이식본.
 *
 * 설계 결정은 원본 주석에 그대로 있다. 핵심만 다시 적는다.
 *   - 비밀번호가 없는 것과 파일이 깨진 것을 반드시 구별한다.
 *   - 틀리는 방향 → '설정 안 됨'으로 오판하면 소유권이 넘어간다.
 *     반대(설정 됨으로 오판)는 로그인이 안 될 뿐이다.
 *   - 비밀번호를 바꾸면 서명 키도 함께 새로 뽑는다.
 */
declare(strict_types=1);

require_once __DIR__ . '/../config.php';

class AuthFileBroken extends RuntimeException {}

final class Auth
{
    // auth.py 상수와 동일
    private const ITERATIONS    = 240_000;
    private const MAX_FAILS     = 5;
    private const LOCK_SECONDS  = 60;

    // 연속 실패 카운터 — APCu 가 있으면 프로세스 간 공유, 없으면 파일 기반
    private static string $failFile = '';

    // ── 자격 증명 파일 읽기/쓰기 ─────────────────────────────────

    /**
     * 자격 증명을 읽는다. **없는 것과 깨진 것을 구별한다.**
     * auth.py _load() 이식.
     *
     * @return array<string,mixed>  파일이 없으면 []
     * @throws AuthFileBroken       파일이 있는데 못 읽거나 JSON 이 아닌 경우
     */
    public static function load(): array
    {
        $p = Config::authFile();
        if (!file_exists($p)) {
            return [];
        }
        $raw = @file_get_contents($p);
        if ($raw === false) {
            throw new AuthFileBroken('자격 파일을 읽지 못했습니다: ' . $p);
        }
        $d = json_decode($raw, true);
        if (!is_array($d)) {
            throw new AuthFileBroken('자격 파일 모양이 잘못됐습니다: ' . $p);
        }
        return $d;
    }

    /**
     * 자격 증명을 원자적으로 쓴다 (임시 파일 → rename).
     * auth.py _save() 이식.
     */
    private static function save(array $d): void
    {
        $p    = Config::authFile();
        $dir  = dirname($p);
        if (!is_dir($dir)) {
            mkdir($dir, 0700, true);
        }
        $tmp  = $p . '.tmp.' . bin2hex(random_bytes(4));
        // 600 권한으로 생성
        $fd   = fopen($tmp, 'w');
        if ($fd === false) {
            throw new RuntimeException('자격 파일 임시본을 만들지 못했습니다: ' . $tmp);
        }
        chmod($tmp, 0600);
        fwrite($fd, json_encode($d, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        fflush($fd);
        fclose($fd);
        if (!rename($tmp, $p)) {
            @unlink($tmp);
            throw new RuntimeException('자격 파일 갈아끼우기가 실패했습니다: ' . $p);
        }
        chmod($p, 0600);
    }

    // ── 해시 ──────────────────────────────────────────────────────

    /**
     * PBKDF2-SHA256. auth.py _hash() 이식.
     * hash_pbkdf2('sha256', $password, $salt, 240000, 0, true) — raw binary, base64 반환.
     */
    private static function hashPassword(string $password, string $salt): string
    {
        $raw = hash_pbkdf2('sha256', $password, $salt, self::ITERATIONS, 0, true);
        return base64_encode($raw);
    }

    // ── 공개 API ──────────────────────────────────────────────────

    /**
     * 비밀번호가 설정돼 있는가.
     * auth.py is_configured() — 모르면 '설정 됨' 으로 답한다.
     */
    public static function isConfigured(): bool
    {
        try {
            $d = self::load();
            return !empty($d['hash']);
        } catch (AuthFileBroken) {
            return true;   // 못 읽으면 '설정 됨' 쪽으로 실패
        }
    }

    /**
     * 비밀번호를 새로 정한다. auth.py set_password() 이식.
     * 서명 키도 함께 새로 뽑는다.
     *
     * @return string 저장된 자격 파일 경로
     * @throws ValueError          8자 미만
     * @throws AuthFileBroken      파일이 깨진 경우 (복구 쓰기는 허용)
     */
    public static function setup(string $password): string
    {
        if (strlen($password) < 8) {
            throw new InvalidArgumentException('비밀번호는 8자 이상이어야 한다');
        }
        try {
            $d = self::load();
        } catch (AuthFileBroken) {
            $d = [];   // 깨진 파일을 새로 쓰는 것은 복구다. 여기만 허용
        }
        $salt = random_bytes(16);
        $d['salt']      = base64_encode($salt);
        $d['hash']      = self::hashPassword($password, $salt);
        $d['iterations'] = self::ITERATIONS;
        $d['updatedAt'] = self::nowKst();
        // 비밀번호를 바꾼다 = 전원 다시 로그인. 키도 새로 뽑는다.
        $d['secret']    = base64_encode(random_bytes(32));
        self::save($d);
        return Config::authFile();
    }

    /**
     * 비밀번호를 바꾼다. auth.py set_password() 의 change 용도 이식.
     * 기존 비밀번호를 먼저 확인한다.
     *
     * @throws RuntimeException    기존 비밀번호가 틀렸을 때
     */
    public static function changePassword(string $old, string $new): string
    {
        if (!self::verify($old)) {
            throw new RuntimeException('기존 비밀번호가 맞지 않습니다.');
        }
        return self::setup($new);
    }

    /**
     * 비밀번호를 검사한다. auth.py check_password() 이식.
     * 상수 시간 비교(hash_equals)로 타이밍 공격을 막는다.
     */
    public static function verify(string $password): bool
    {
        try {
            $d = self::load();
        } catch (AuthFileBroken) {
            return false;   // 못 읽으면 아무도 못 들어온다
        }
        if (empty($d['hash']) || empty($d['salt'])) {
            return false;
        }
        $salt     = base64_decode($d['salt'], true);
        if ($salt === false) {
            return false;
        }
        $expected = self::hashPassword($password, $salt);
        // hash_equals — 상수 시간 비교
        return hash_equals($expected, (string)$d['hash']);
    }

    /**
     * 세션 서명 키. auth.py secret_key() 이식.
     * PHP 세션은 자체 서명을 쓰므로, 여기서는 값을 반환만 한다 (필요 시 사용).
     */
    public static function secretKey(): string
    {
        $d = self::load();
        if (empty($d['secret'])) {
            $d['secret'] = base64_encode(random_bytes(32));
            self::save($d);
        }
        return (string)$d['secret'];
    }

    // ── 연속 실패 제어 ────────────────────────────────────────────
    // APCu 가 있으면 메모리 공유, 없으면 /tmp 파일 기반으로 내린다.
    // auth.py _fails{} — 재시작하면 풀린다는 원본 특성을 파일 기반에서 재현하지 않는다.
    // PHP 는 프로세스가 짧게 살아 메모리 공유가 없으면 파일이 현실적 대안이다.

    private static function failKey(string $who): string
    {
        return 'mgscs_fail_' . md5($who);
    }

    /**
     * 실패를 기록한다. auth.py note_failure() 이식.
     */
    public static function noteFailure(string $who): void
    {
        $key = self::failKey($who);
        if (function_exists('apcu_fetch')) {
            $val = apcu_fetch($key) ?: [0, 0];
            apcu_store($key, [$val[0] + 1, time()], self::LOCK_SECONDS + 10);
            return;
        }
        $f = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $key . '.fail';
        $val = [0, 0];
        if (is_readable($f)) {
            $tmp = json_decode((string)file_get_contents($f), true);
            if (is_array($tmp)) {
                $val = $tmp;
            }
        }
        $val = [$val[0] + 1, time()];
        file_put_contents($f, json_encode($val), LOCK_EX);
    }

    /**
     * 실패 기록을 지운다. auth.py clear_failures() 이식.
     */
    public static function clearFailures(string $who): void
    {
        $key = self::failKey($who);
        if (function_exists('apcu_delete')) {
            apcu_delete($key);
            return;
        }
        $f = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $key . '.fail';
        @unlink($f);
    }

    /**
     * 남은 잠금 시간(초). 0이면 시도 가능. auth.py locked_for() 이식.
     */
    public static function lockedFor(string $who): int
    {
        $key = self::failKey($who);
        $val = [0, 0];
        if (function_exists('apcu_fetch')) {
            $tmp = apcu_fetch($key);
            if (is_array($tmp)) {
                $val = $tmp;
            }
        } else {
            $f = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $key . '.fail';
            if (is_readable($f)) {
                $tmp = json_decode((string)file_get_contents($f), true);
                if (is_array($tmp)) {
                    $val = $tmp;
                }
            }
        }
        [$n, $ts] = $val;
        if ($n < self::MAX_FAILS) {
            return 0;
        }
        $left = self::LOCK_SECONDS - (time() - $ts);
        if ($left <= 0) {
            self::clearFailures($who);
            return 0;
        }
        return (int)$left + 1;
    }

    // ── 세션 헬퍼 ────────────────────────────────────────────────

    /**
     * 세션을 안전하게 시작한다.
     * 쿠키 파라미터를 먼저 설정한 뒤 session_start() 를 부른다.
     */
    public static function sessionStartSafe(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        session_set_cookie_params([
            'lifetime' => Config::SESSION_LIFETIME,
            'path'     => (class_exists('Http') ? Http::base() : '') . '/',
            'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }

    /**
     * 로그인 여부. routes.py logged_in() 이식.
     */
    public static function loggedIn(): bool
    {
        return !empty($_SESSION['actor']);
    }

    /**
     * 관리자 여부. routes.py is_admin() 이식.
     * 현재는 로그인 == 관리자 (역할표 붙이면 여기만 고친다).
     */
    public static function isAdmin(): bool
    {
        return self::loggedIn();
    }

    /**
     * 현재 행위자. routes.py actor() 이식.
     * 세션 → LASTUSER 쿠키 → X-Remote-User 헤더 → 환경변수 → 'unknown'
     */
    public static function actorNow(): string
    {
        if (!empty($_SESSION['actor'])) {
            return (string)$_SESSION['actor'];
        }
        if (!empty($_COOKIE['LASTUSER'])) {
            return (string)$_COOKIE['LASTUSER'];
        }
        $header = $_SERVER['HTTP_X_REMOTE_USER'] ?? '';
        if ($header !== '') {
            return $header;
        }
        $env = getenv(Config::DEFAULT_ACTOR_ENV);
        if ($env !== false && $env !== '') {
            return $env;
        }
        return 'unknown';
    }

    // ── 내부 유틸 ────────────────────────────────────────────────

    private static function nowKst(): string
    {
        $tz  = new DateTimeZone('Asia/Seoul');
        $now = new DateTime('now', $tz);
        return $now->format('Y-m-d\TH:i:sP');
    }
}
