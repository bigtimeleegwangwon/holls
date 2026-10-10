<?php
require 'config.php';
require 'lib/Session.php';
require 'lib/Store.php';
require 'lib/Oauth.php';

Session::start();

// 라우팅
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/sholl_api', '', $path);
$method = $_SERVER['REQUEST_METHOD'];

// 간단한 라우터
$route = function($pattern, $methods, $handler) use ($path, $method) {
  $regex = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $pattern);
  $regex = '#^' . $regex . '$#';

  if (!in_array($method, (array)$methods)) {
    return false;
  }

  if (preg_match($regex, $path, $matches)) {
    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
    $handler(...array_values($params));
    return true;
  }
  return false;
};

// ========== OAuth 엔드포인트 ==========

// GET /auth/{provider}/start → provider 인증 페이지로 리다이렉트
if ($route('/auth/{provider}/start', 'GET', function($provider) {
  try {
    $oauth = new Oauth($provider);
    $authUrl = $oauth->getAuthorizeUrl();
    header('Location: ' . $authUrl);
    exit;
  } catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
  }
})) { exit; }

// GET /auth/{provider}/callback → 콜백 처리, 세션 발급
if ($route('/auth/{provider}/callback', 'GET', function($provider) {
  try {
    $code = $_GET['code'] ?? null;
    $state = $_GET['state'] ?? null;

    if (!$code || !$state) {
      throw new Exception("Missing code or state");
    }

    $oauth = new Oauth($provider);
    $userinfo = $oauth->handleCallback($code, $state);

    // 사용자 ID: provider:providerId
    $uid = $userinfo['provider'] . ':' . $userinfo['providerId'];

    // 사용자 저장 (첫 로그인 시 생성, 이후 업데이트)
    $store = new Store('users');
    $user = $store->read($uid);

    if (!$user) {
      // 신규 사용자
      $user = [
        'uid' => $uid,
        'provider' => $userinfo['provider'],
        'providerId' => $userinfo['providerId'],
        'name' => $userinfo['name'],
        'email' => $userinfo['email'],
        'avatar' => $userinfo['avatar'],
        'createdAt' => date('c'),
        'updatedAt' => date('c')
      ];
    } else {
      // 기존 사용자 정보 업데이트
      $user['name'] = $userinfo['name'];
      $user['email'] = $userinfo['email'];
      $user['avatar'] = $userinfo['avatar'];
      $user['updatedAt'] = date('c');
    }

    $store->write($uid, $user);

    // 세션 고정 방지: 로그인 후 세션 ID 재생성
    Session::regenerate();
    Session::setUser($user);

    // 프론트엔드로 리다이렉트
    header('Location: ' . FRONTEND_BASE . '/?logged_in=1');
    exit;
  } catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
  }
})) { exit; }

// POST /auth/logout → 로그아웃
if ($route('/auth/logout', 'POST', function() {
  Session::destroy();
  echo json_encode(['success' => true]);
  exit;
})) { exit; }

// ========== 사용자 정보 ==========

// GET /me → 현재 로그인한 사용자 정보
if ($route('/me', 'GET', function() {
  $user = Session::getUser();
  if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
  }
  echo json_encode($user);
  exit;
})) { exit; }

// ========== 분석 저장소 (사용자당 1개) ==========

// GET /analysis → 사용자의 분석 조회
if ($route('/analysis', 'GET', function() {
  $user = Session::getUser();
  if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
  }

  $store = new Store('analyses');
  $analysis = $store->read($user['uid']);

  if (!$analysis) {
    // 분석이 없으면 빈 스키마 반환
    echo json_encode([
      'nodes' => [],
      'edges' => [],
      'title' => '',
      'updatedAt' => null
    ]);
    exit;
  }

  echo json_encode($analysis);
  exit;
})) { exit; }

// PUT /analysis → 분석 저장
if ($route('/analysis', 'PUT', function() {
  $user = Session::getUser();
  if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
  }

  $body = json_decode(file_get_contents('php://input'), true);
  if (!$body) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
  }

  // 스키마 검증
  if (!isset($body['nodes']) || !is_array($body['nodes'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing or invalid nodes array']);
    exit;
  }
  if (!isset($body['edges']) || !is_array($body['edges'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing or invalid edges array']);
    exit;
  }

  // 크기 검증
  if (count($body['nodes']) > MAX_NODES) {
    http_response_code(400);
    echo json_encode(['error' => 'Too many nodes (max ' . MAX_NODES . ')']);
    exit;
  }
  if (count($body['edges']) > MAX_EDGES) {
    http_response_code(400);
    echo json_encode(['error' => 'Too many edges (max ' . MAX_EDGES . ')']);
    exit;
  }

  // 엣지 검증: source/target 노드 존재 확인
  $nodeIds = [];
  foreach ($body['nodes'] as $node) {
    if (!isset($node['id'])) {
      http_response_code(400);
      echo json_encode(['error' => 'Node missing id']);
      exit;
    }
    $nodeIds[$node['id']] = true;
  }

  foreach ($body['edges'] as $edge) {
    if (!isset($edge['source']) || !isset($edge['target'])) {
      http_response_code(400);
      echo json_encode(['error' => 'Edge missing source or target']);
      exit;
    }
    $sourceId = is_array($edge['source']) ? $edge['source']['id'] : $edge['source'];
    $targetId = is_array($edge['target']) ? $edge['target']['id'] : $edge['target'];

    if (!isset($nodeIds[$sourceId]) || !isset($nodeIds[$targetId])) {
      http_response_code(400);
      echo json_encode(['error' => 'Edge references non-existent node']);
      exit;
    }
  }

  // 분석 저장
  $analysis = [
    'uid' => $user['uid'],
    'title' => $body['title'] ?? '내 분석',
    'nodes' => $body['nodes'],
    'edges' => $body['edges'],
    'updatedAt' => date('c')
  ];

  $store = new Store('analyses');
  $store->write($user['uid'], $analysis);

  echo json_encode(['success' => true, 'updatedAt' => $analysis['updatedAt']]);
  exit;
})) { exit; }

// ========== 공유 토큰 ==========

// POST /analysis/share → 공유 토큰 발급
if ($route('/analysis/share', 'POST', function() {
  $user = Session::getUser();
  if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
  }

  $store = new Store('analyses');
  $analysis = $store->read($user['uid']);
  if (!$analysis) {
    http_response_code(404);
    echo json_encode(['error' => 'No analysis to share']);
    exit;
  }

  // 공유 토큰 생성
  $token = bin2hex(random_bytes(16));
  $shareStore = new Store('shares');
  $shareStore->write($token, [
    'uid' => $user['uid'],
    'analysis' => $analysis,
    'createdAt' => date('c'),
    'expiresAt' => date('c', strtotime('+30 days'))
  ]);

  $shareUrl = FRONTEND_BASE . '/?s=' . $token;
  echo json_encode(['token' => $token, 'url' => $shareUrl]);
  exit;
})) { exit; }

// DELETE /analysis/share/{token} → 공유 해제
if ($route('/analysis/share/{token}', 'DELETE', function($token) {
  $user = Session::getUser();
  if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
  }

  $shareStore = new Store('shares');
  $share = $shareStore->read($token);
  if (!$share || $share['uid'] !== $user['uid']) {
    http_response_code(404);
    echo json_encode(['error' => 'Share not found or not owned by you']);
    exit;
  }

  $shareStore->delete($token);
  echo json_encode(['success' => true]);
  exit;
})) { exit; }

// ========== 공유 읽기 (인증 불필요) ==========

// GET /shared/{token} → 공유 분석 조회 (공개 읽기)
if ($route('/shared/{token}', 'GET', function($token) {
  $shareStore = new Store('shares');
  $share = $shareStore->read($token);
  if (!$share) {
    http_response_code(404);
    echo json_encode(['error' => 'Share not found']);
    exit;
  }

  // 만료 확인
  $expiresAt = strtotime($share['expiresAt']);
  if ($expiresAt < time()) {
    http_response_code(410); // Gone
    echo json_encode(['error' => 'Share expired']);
    exit;
  }

  echo json_encode([
    'analysis' => $share['analysis'],
    'sharedBy' => $share['uid']
  ]);
  exit;
})) { exit; }

// ========== 404 ==========
http_response_code(404);
echo json_encode(['error' => 'Endpoint not found']);
