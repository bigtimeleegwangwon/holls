<?php
class Session {
  public static function start() {
    if (session_status() === PHP_SESSION_NONE) {
      // 보안 옵션: HttpOnly, Secure, SameSite=Lax
      session_set_cookie_params([
        'lifetime' => 86400 * 7, // 7 days
        'path' => '/sholl_api',
        'domain' => '.mycafe24.com',
        'secure' => true,        // HTTPS only
        'httponly' => true,      // JavaScript 접근 불가
        'samesite' => 'Lax'      // CSRF 방지
      ]);
      session_start();

      // 세션 고정 공격 방지: 로그인 후 세션 재생성
      if (!isset($_SESSION['_session_started'])) {
        $_SESSION['_session_started'] = true;
      }
    }
  }

  public static function set($key, $value) {
    $_SESSION[$key] = $value;
  }

  public static function get($key, $default = null) {
    return $_SESSION[$key] ?? $default;
  }

  public static function has($key) {
    return isset($_SESSION[$key]);
  }

  public static function destroy() {
    session_destroy();
  }

  public static function regenerate() {
    session_regenerate_id(true);
  }

  public static function getUser() {
    return self::get('user');
  }

  public static function setUser($user) {
    self::set('user', $user);
  }
}
