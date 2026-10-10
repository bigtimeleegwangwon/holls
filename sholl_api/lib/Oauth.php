<?php
class Oauth {
  private $provider;
  private $config;

  public function __construct($provider) {
    global $OAUTH_PROVIDERS;
    if (!isset($OAUTH_PROVIDERS[$provider])) {
      throw new Exception("Unsupported provider: $provider");
    }
    $this->provider = $provider;
    $this->config = $OAUTH_PROVIDERS[$provider];
  }

  // 1단계: 인증 페이지로 리다이렉트
  public function getAuthorizeUrl() {
    Session::start();

    // state 토큰 (CSRF 방지)
    $state = bin2hex(random_bytes(16));
    Session::set('oauth_state', $state);

    $params = [
      'client_id' => $this->config['client_id'],
      'redirect_uri' => $this->config['redirect_uri'],
      'response_type' => 'code',
      'scope' => $this->config['scopes'],
      'state' => $state
    ];

    // Google 특화 파라미터
    if ($this->provider === 'google') {
      $params['access_type'] = 'online';
      $params['prompt'] = 'consent'; // 사용자 동의 화면 항상 표시
    }

    return $this->config['authorize_url'] . '?' . http_build_query($params);
  }

  // 2단계: 콜백 처리 (code → token → userinfo)
  public function handleCallback($code, $state) {
    Session::start();

    // state 검증
    $sessionState = Session::get('oauth_state');
    if (!$sessionState || $state !== $sessionState) {
      throw new Exception("Invalid state token");
    }
    Session::set('oauth_state', null); // state 소비

    // 코드 → 토큰 교환
    $token = $this->getToken($code);
    if (!$token || !isset($token['access_token'])) {
      throw new Exception("Failed to get access token");
    }

    // 토큰 → 사용자 정보
    $userinfo = $this->getUserInfo($token['access_token']);
    if (!$userinfo) {
      throw new Exception("Failed to get user info");
    }

    return $userinfo;
  }

  // 액세스 토큰 요청
  private function getToken($code) {
    $postData = [
      'code' => $code,
      'client_id' => $this->config['client_id'],
      'client_secret' => $this->config['client_secret'],
      'redirect_uri' => $this->config['redirect_uri'],
      'grant_type' => 'authorization_code'
    ];

    $context = stream_context_create([
      'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/x-www-form-urlencoded',
        'content' => http_build_query($postData),
        'timeout' => 10
      ],
      'ssl' => [
        'verify_peer' => true
      ]
    ]);

    $response = file_get_contents($this->config['token_url'], false, $context);
    if ($response === false) {
      throw new Exception("Failed to contact token endpoint");
    }

    return json_decode($response, true);
  }

  // 사용자 정보 요청
  private function getUserInfo($accessToken) {
    $context = stream_context_create([
      'http' => [
        'method' => 'GET',
        'header' => "Authorization: Bearer $accessToken",
        'timeout' => 10
      ],
      'ssl' => [
        'verify_peer' => true
      ]
    ]);

    $response = file_get_contents($this->config['userinfo_url'], false, $context);
    if ($response === false) {
      throw new Exception("Failed to contact userinfo endpoint");
    }

    $data = json_decode($response, true);

    // Google 응답 매핑
    if ($this->provider === 'google') {
      return [
        'provider' => 'google',
        'providerId' => $data['sub'] ?? null,
        'name' => $data['name'] ?? $data['email'] ?? '',
        'email' => $data['email'] ?? null,
        'avatar' => $data['picture'] ?? null
      ];
    }

    return null;
  }
}
