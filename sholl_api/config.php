<?php
// 환경 설정
define('API_BASE', 'https://getupearly.mycafe24.com/sholl_api');
define('FRONTEND_BASE', 'https://getupearly.mycafe24.com/sholl');
define('DATA_DIR', __DIR__ . '/data');
define('MAX_NODES', 300);
define('MAX_EDGES', 600);

// OAuth 클라이언트 ID/Secret은 config.local.php에서 읽음 (git에 커밋하지 않음)
// config.local.php 예시:
// define('GOOGLE_CLIENT_ID', 'xxx.apps.googleusercontent.com');
// define('GOOGLE_CLIENT_SECRET', 'xxx');
// define('KAKAO_CLIENT_ID', 'xxx');
// ... 등

$configLocal = __DIR__ . '/config.local.php';
if (file_exists($configLocal)) {
  require $configLocal;
} else {
  // 로컬 설정 파일이 없으면 에러 (프로덕션에서는 필수)
  if (php_sapi_name() !== 'cli') {
    http_response_code(500);
    die('config.local.php not found');
  }
}

// OAuth 제공자 설정
$OAUTH_PROVIDERS = [
  'google' => [
    'name' => 'Google',
    'authorize_url' => 'https://accounts.google.com/o/oauth2/v2/auth',
    'token_url' => 'https://oauth2.googleapis.com/token',
    'userinfo_url' => 'https://openidconnect.googleapis.com/v1/userinfo',
    'client_id' => GOOGLE_CLIENT_ID ?? '',
    'client_secret' => GOOGLE_CLIENT_SECRET ?? '',
    'scopes' => 'openid profile email',
    'redirect_uri' => API_BASE . '/auth/google/callback'
  ],
  // Stage 3에서 추가: kakao, naver, github
];

// 응답 헤더 설정
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Content-Type: application/json; charset=utf-8');

// CORS: 같은 출처만 허용
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (strpos($origin, 'getupearly.mycafe24.com') !== false) {
  header('Access-Control-Allow-Origin: ' . $origin);
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
  header('Access-Control-Allow-Headers: Content-Type');
}

// preflight 요청 처리
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}
