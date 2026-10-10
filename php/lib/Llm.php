<?php
// -*- coding: utf-8 -*-
declare(strict_types=1);

/**
 * Llm — LLM 호출 어댑터.
 *
 * Port of llm.py.
 *
 * 왜 서버가 부르는가.
 *   지금까지는 브라우저가 직접 불렀고, API 키가 localStorage 에 평문으로 남았다.
 *   서버가 대신 부르면 키는 서버에만 있고, 누가 언제 분석했는지도 남는다.
 *
 * 붙이는 방법.
 *   사내 LLM Ops 는 자체 형식이다. ADAPTER_INHOUSE 의 build/parse 를 채우면 된다.
 *   프롬프트·타임아웃·재시도·오류 문구·감사 기록은 이미 call() 이 처리한다.
 *
 * HTTP 는 PHP curl 을 쓴다.
 */
final class Llm
{
    private const UA = 'mGSCS/4.1 (SK hynix Materials Strategy)';

    // ── 어댑터 등록부 ─────────────────────────────────────────────
    // 각 항목: ['label' => ..., 'build' => callable, 'parse' => callable]
    // build(cfg, prompt, maxTokens, temperature) → [url, headers, body]
    // parse(cfg, status, payload)                → string (본문)

    private static array $adapters = [];

    /**
     * 어댑터를 등록한다. 이 클래스의 static initialiser 에서 호출된다.
     */
    private static function registerAdapters(): void
    {
        if (!empty(self::$adapters)) { return; }

        // ── OpenAI 호환 (vLLM · Qwen · Ollama · 대부분의 사내 게이트웨이) ──
        self::$adapters['openai'] = [
            'label' => 'OpenAI 호환',
            'build' => static function (array $cfg, string $prompt, int $maxTokens, float $temperature): array {
                $url     = self::chatUrl($cfg['base'] ?? '');
                $headers = ['Content-Type: application/json', 'User-Agent: ' . self::UA];
                if (!empty($cfg['key'])) {
                    $headers[] = 'Authorization: Bearer ' . $cfg['key'];
                }
                $body = [
                    'model'       => $cfg['model'],
                    'max_tokens'  => $maxTokens,
                    'temperature' => $temperature,
                    'messages'    => [
                        ['role' => 'system', 'content' =>
                            'You are a strict JSON generator. Output ONLY a valid JSON array. No prose, no markdown fences.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ];
                return [$url, $headers, $body];
            },
            'parse' => static function (array $cfg, int $status, mixed $payload): string {
                if (!is_array($payload)) {
                    throw new \RuntimeException('[4020] 응답이 JSON 이 아닙니다: ' . substr((string) $payload, 0, 200));
                }
                try {
                    return (string) $payload['choices'][0]['message']['content'];
                } catch (\Throwable $e) {
                    throw new \RuntimeException('[4021] 응답에서 본문을 찾지 못했습니다: '
                        . substr(json_encode($payload, JSON_UNESCAPED_UNICODE), 0, 300));
                }
            },
        ];

        // ── Anthropic 호환 ──
        self::$adapters['anthropic'] = [
            'label' => 'Anthropic 호환',
            'build' => static function (array $cfg, string $prompt, int $maxTokens, float $temperature): array {
                $url     = rtrim($cfg['base'] ?? '', '/') . '/v1/messages';
                $headers = [
                    'Content-Type: application/json',
                    'User-Agent: ' . self::UA,
                    'anthropic-version: 2023-06-01',
                ];
                if (!empty($cfg['key'])) {
                    $headers[] = 'x-api-key: ' . $cfg['key'];
                }
                $body = [
                    'model'       => $cfg['model'],
                    'max_tokens'  => $maxTokens,
                    'temperature' => $temperature,
                    'messages'    => [
                        ['role' => 'user',      'content' => $prompt],
                        ['role' => 'assistant', 'content' => '['],
                    ],
                ];
                return [$url, $headers, $body];
            },
            'parse' => static function (array $cfg, int $status, mixed $payload): string {
                if (!is_array($payload)) {
                    throw new \RuntimeException('[4020] 응답이 JSON 이 아닙니다: ' . substr((string) $payload, 0, 200));
                }
                try {
                    $text = (string) $payload['content'][0]['text'];
                } catch (\Throwable $e) {
                    throw new \RuntimeException('[4021] 응답에서 본문을 찾지 못했습니다: '
                        . substr(json_encode($payload, JSON_UNESCAPED_UNICODE), 0, 300));
                }
                return (!str_starts_with(ltrim($text), '[')) ? '[' . $text : $text;
            },
        ];

        // ── 사내 LLM Ops ─────────────────────────────────────────
        // 담당: 사내 Claude Code / 대상 모델: qwen3.5 · glm5.2 · 자체 LLM
        // cfg 로 들어오는 것: base, model, key, extra
        // 고칠 곳: build 의 url/headers/body 구성, parse 의 응답 경로
        // 확인: [알림 관리] → AI 분석 → 연결 시험
        self::$adapters['inhouse'] = [
            'label' => '사내 LLM Ops',
            'build' => static function (array $cfg, string $prompt, int $maxTokens, float $temperature): array {
                $url     = self::chatUrl($cfg['base'] ?? '');              // ← 사내 경로로
                $headers = ['Content-Type: application/json', 'User-Agent: ' . self::UA];
                if (!empty($cfg['key'])) {
                    $headers[] = 'Authorization: Bearer ' . $cfg['key'];   // ← 사내 인증 방식으로
                }
                $body = [
                    'model'       => $cfg['model'],
                    'max_tokens'  => $maxTokens,
                    'temperature' => $temperature,
                    'messages'    => [
                        ['role' => 'system', 'content' =>
                            'You are a strict JSON generator. Output ONLY a valid JSON array. No prose, no markdown fences.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                ];
                // 사내 규격에 추가 필드가 있으면 설정에서 넣는다
                if (!empty($cfg['extra']) && is_array($cfg['extra'])) {
                    $body = array_merge($body, $cfg['extra']);
                }
                return [$url, $headers, $body];
            },
            'parse' => static function (array $cfg, int $status, mixed $payload): string {
                if (!is_array($payload)) {
                    throw new \RuntimeException('[4020] 응답이 JSON 이 아닙니다: ' . substr((string) $payload, 0, 200));
                }
                // 흔한 자리들을 차례로 본다. 사내 규격이 다르면 이 목록만 바꾼다.
                $getters = [
                    fn($d) => $d['choices'][0]['message']['content'],
                    fn($d) => $d['choices'][0]['text'],
                    fn($d) => $d['content'][0]['text'],
                    fn($d) => $d['output']['text'],
                    fn($d) => $d['result'],
                    fn($d) => $d['data']['content'],
                    fn($d) => $d['text'],
                ];
                foreach ($getters as $get) {
                    try {
                        $v = $get($payload);
                        if (is_string($v) && trim($v) !== '') { return $v; }
                    } catch (\Throwable $e) {
                        continue;
                    }
                }
                throw new \RuntimeException(
                    '[4021] 응답에서 본문을 찾지 못했습니다. Llm.php 의 inhouse parse 에 사내 응답 구조를 넣어 주세요.' . "\n"
                    . '받은 것: ' . substr(json_encode($payload, JSON_UNESCAPED_UNICODE), 0, 400)
                );
            },
        ];
    }

    // ── 내부 유틸 ─────────────────────────────────────────────────

    /**
     * base 에 /v1 까지 적어 오는 일이 잦다. 그대로 붙이면 /v1/v1/... 로 404 가 난다.
     */
    private static function chatUrl(string $base): string
    {
        $b = rtrim($base, '/');
        if (str_ends_with($b, '/v1')) {
            $b = rtrim(substr($b, 0, -3), '/');
        }
        return $b . '/v1/chat/completions';
    }

    private static function httpMessage(int $status, string $raw): string
    {
        $body = substr($raw, 0, 300);
        switch ($status) {
            case 401: return "인증 실패(401) — API 키를 확인하세요. {$body}";
            case 403: return "권한 없음(403) — 이 모델에 대한 권한을 확인하세요. {$body}";
            case 404: return "엔드포인트를 찾을 수 없습니다(404) — 베이스 URL 과 경로를 확인하세요. {$body}";
            case 429: return "요청 한도 초과(429) — 잠시 후 다시 시도하세요. {$body}";
            default:  return "HTTP {$status} — {$body}";
        }
    }

    /**
     * curl 로 POST 한다. [status, rawBody] 를 반환하거나 RuntimeException 을 던진다.
     */
    private static function curlPost(string $url, array $headers, string $jsonBody, int $timeout): array
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $jsonBody,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_CONNECTTIMEOUT => min(10, $timeout),
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 3,
        ]);
        $raw    = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err    = curl_error($ch);
        curl_close($ch);

        if ($raw === false) {
            throw new \RuntimeException("연결 실패 — 사내망에서 이 주소에 닿는지 확인하세요. ({$err})");
        }
        return [$status, (string) $raw];
    }

    // ── 공개 API ──────────────────────────────────────────────────

    /**
     * 등록된 어댑터 목록.
     *
     * @return array<string, string>  [name => label]
     */
    public static function adapters(): array
    {
        self::registerAdapters();
        return array_map(fn($a) => $a['label'], self::$adapters);
    }

    /**
     * 현재 설정 상태.
     *
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public static function status(array $config): array
    {
        self::registerAdapters();
        $fmt = $config['format'] ?? 'openai';
        return [
            'enabled'       => !empty($config['base']) && !empty($config['model']),
            'format'        => $fmt,
            'formatKnown'   => isset(self::$adapters[$fmt]),
            'baseSet'       => !empty($config['base']),
            'modelSet'      => !empty($config['model']),
            'keySet'        => !empty($config['key']),
        ];
    }

    /**
     * 설정대로 한 번 부른다. 실패는 사람이 읽을 문장으로 바꿔 던진다.
     *
     * @param array<string, mixed> $config  AlertConfig::llmConfig() 결과
     * @throws \RuntimeException
     */
    public static function call(
        array  $config,
        string $prompt,
        int    $maxTokens   = 2000,
        float  $temperature = 0.0,
        int    $timeout     = 60,
        int    $retries     = 1
    ): string {
        self::registerAdapters();

        $fmt = $config['format'] ?? 'openai';
        $ad  = self::$adapters[$fmt] ?? null;
        if (!$ad) {
            throw new \RuntimeException("[4004] 모르는 API 형식입니다: {$fmt}");
        }
        if (empty($config['base'])) {
            throw new \RuntimeException('[4002] 엔드포인트가 설정되지 않았습니다 — 시스템 설정에서 넣어 주세요.');
        }
        if (empty($config['model'])) {
            throw new \RuntimeException('[4003] 모델 이름이 설정되지 않았습니다 — 시스템 설정에서 넣어 주세요.');
        }

        [$url, $headers, $body] = ($ad['build'])($config, $prompt, $maxTokens, $temperature);
        $jsonBody = (string) json_encode($body, JSON_UNESCAPED_UNICODE);

        $last = null;
        for ($attempt = 0; $attempt <= $retries; $attempt++) {
            try {
                [$status, $raw] = self::curlPost($url, $headers, $jsonBody, $timeout);
            } catch (\RuntimeException $e) {
                if ($attempt < $retries) {
                    usleep((int) (1_500_000 * ($attempt + 1)));
                    $last = $e->getMessage();
                    continue;
                }
                throw $e;
            }

            if ($status >= 400) {
                if (in_array($status, [429, 502, 503, 504], true) && $attempt < $retries) {
                    usleep((int) (1_500_000 * ($attempt + 1)));
                    $last = self::httpMessage($status, $raw);
                    continue;
                }
                throw new \RuntimeException(self::httpMessage($status, $raw));
            }

            $payload = json_decode($raw, true);
            if ($payload === null) { $payload = $raw; }

            return ($ad['parse'])($config, $status, $payload);
        }
        throw new \RuntimeException("재시도했지만 실패했습니다: {$last}");
    }

    /**
     * 연결 시험. 무엇을 보냈고 무엇을 받았는지 그대로 돌려준다.
     * 사내 규격을 맞춰 갈 때 이게 있어야 한다.
     *
     * @param array<string, mixed> $config
     * @return array<string, mixed>
     */
    public static function probe(array $config, int $timeout = 20): array
    {
        self::registerAdapters();

        $fmt = $config['format'] ?? 'openai';
        $ad  = self::$adapters[$fmt] ?? null;
        if (!$ad) {
            return ['ok' => false, 'error' => "모르는 API 형식입니다: {$fmt}"];
        }

        try {
            [$url, $headers, $body] = ($ad['build'])($config, '다음 JSON 을 그대로 돌려주세요: [1]', 64, 0.0);
        } catch (\Throwable $e) {
            return ['ok' => false, 'error' => "요청을 만들지 못했습니다: {$e->getMessage()}"];
        }

        // 헤더에서 인증 정보를 가린다
        $safeHeaders = array_map(static function (string $h): string {
            if (preg_match('/^(Authorization|x-api-key|api-key):/i', $h)) {
                return preg_replace('/:.+/', ': ***', $h) ?? $h;
            }
            return $h;
        }, $headers);

        $out = ['ok' => false, 'sent' => ['url' => $url, 'headers' => $safeHeaders, 'body' => $body]];

        try {
            $text = self::call($config, '다음 JSON 을 그대로 돌려주세요: [1]',
                64, 0.0, $timeout, 0);
            $out['ok']       = true;
            $out['received'] = substr($text, 0, 600);
        } catch (\RuntimeException $e) {
            $out['error'] = $e->getMessage();
        } catch (\Throwable $e) {
            $out['error'] = '예상 못 한 오류: ' . get_class($e) . ' ' . $e->getMessage();
        }

        return $out;
    }
}
