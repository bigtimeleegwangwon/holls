<?php
// -*- coding: utf-8 -*-
declare(strict_types=1);

/**
 * AlertConfig — 경보 설정.
 *
 * Port of alert_config.py.
 *
 * 왜 이 화면이 사내(tailnet)에 있는가.
 *   수신자 주소는 개인정보다. 공개 대시보드에 렌더하면 그대로 노출된다.
 *   그래서 설정 화면은 tailnet 으로만 닿는 이 앱에 둔다.
 *
 * 왜 여기서 바로 발송 설정을 바꾸지 못하는가.
 *   경보는 별도 실행 환경에서 돈다. 이 화면은 값을 저장하고,
 *   사외에 옮겨 심을 형태로 보여준다. 옮기는 행위는 사람이 한다.
 */
final class AlertConfig
{
    private const REL         = 'revision/alert_config.json';
    private const HISTORY_REL = 'revision/alert_config_history.jsonl';

    // ── 항목 정의 ─────────────────────────────────────────────────

    /** @var array<array<string, mixed>> */
    public const FIELDS = [
        ['key' => 'EMAIL_ENABLED',            'label' => '메일 초안 만들기',       'type' => 'bool',
         'help' => '이 시스템은 메일을 보내지 않는다 — .eml 초안을 만들 뿐이고 보내는 것은 사람이다. 끄면 초안 만들기 버튼이 안 보인다.'],
        ['key' => 'EMAIL_TO',                 'label' => '수신자',                 'type' => 'list',
         'help' => '쉼표로 구분. 초안의 받는 사람 칸에 미리 채워진다 — 열린 뒤 사람이 고칠 수 있다.'],
        ['key' => 'EMAIL_CC',                 'label' => '참조',                   'type' => 'list',
         'help' => '비워도 된다.'],
        ['key' => 'EMAIL_FROM',               'label' => '발신자',                 'type' => 'text',
         'help' => '초안의 보낸 사람 칸. 비워 두면 메일 클라이언트가 본인 계정으로 채운다 — 보통 비워 두는 편이 낫다.'],
        ['key' => 'EMAIL_INCLUDE_BP_DETAILS', 'label' => '초안에 BP 상세표',       'type' => 'bool',
         'help' => '켜도 연락처는 본문에 들어가지 않는다. 담당자 정보는 사내에서만 본다.'],
        ['key' => 'TELEGRAM_ENABLED',         'label' => '텔레그램 발송',          'type' => 'bool'],
        ['key' => 'TELEGRAM_INCLUDE_BP_DETAILS', 'label' => '텔레그램에 BP 상세',  'type' => 'bool'],
        ['key' => 'ALERT_ENABLED',            'label' => '경보 전체',              'type' => 'bool',
         'help' => '끄면 판정은 돌지만 아무 채널로도 보내지 않는다.'],
        ['key' => 'ALERT_DRY_RUN',            'label' => '모의 실행',              'type' => 'bool',
         'help' => '켜면 본문만 만들고 실제로 보내지 않는다.'],
        ['key' => 'ALLOWED_RECIPIENT_DOMAINS','label' => '허용 수신 도메인',        'type' => 'list',
         'help' => '여기 없는 도메인으로는 나가지 않는다. 오발송 방지 최후 방어선이다.'],
        ['key' => 'INSPECTION_SLA_HOURS',     'label' => '점검 회신 기한(시간)',   'type' => 'text', 'group' => '점검 기준',
         'help' => '발송 표시 후 이 시간이 지나도록 회신이 없으면 점검 화면에 \'기한 초과\'가 뜬다. 기본 24.'],
        ['key' => 'LLM_ENABLED',             'label' => 'AI 분석',               'type' => 'bool',   'group' => 'AI 분석',
         'help' => '끄면 서버가 LLM 을 부르지 않는다. 수집·판정은 그대로 돈다.'],
        ['key' => 'LLM_FORMAT',              'label' => 'API 형식',              'type' => 'select', 'group' => 'AI 분석',
         'source' => 'adapters',
         'help' => '사내 게이트웨이가 어떤 규격으로 말하는지. 모르면 openai 부터 걸어 시험해 본다.'],
        ['key' => 'LLM_BASE',               'label' => '엔드포인트',             'type' => 'text',   'group' => 'AI 분석',
         'help' => '베이스 URL. 예: http://llmops.사내/api  (끝의 경로는 어댑터가 붙인다)'],
        ['key' => 'LLM_MODEL',              'label' => '모델',                  'type' => 'text',   'group' => 'AI 분석',
         'help' => 'qwen3.5 · glm5.2 · 자체 LLM 등 사내에서 부르는 이름.'],
        ['key' => 'LLM_KEY',               'label' => 'API 키',                'type' => 'secret', 'group' => 'AI 분석',
         'help' => '서버에만 보관된다. 저장한 뒤에는 화면에 다시 나오지 않는다.'],
        ['key' => 'LLM_EXTRA',             'label' => '추가 필드(JSON)',        'type' => 'text',   'group' => 'AI 분석',
         'help' => '사내 규격에 별도 필드가 필요할 때. 예: {"tenant":"materials"}'],
        ['key' => 'LLM_TIMEOUT',           'label' => '응답 대기(초)',          'type' => 'text',   'group' => 'AI 분석',
         'help' => '이 시간을 넘기면 실패로 본다. 기본 60초.'],
        ['key' => 'ADMIN_CANDIDATE_MARGIN_KM', 'label' => '도/현 후보 마진(km)', 'type' => 'text',
         'help' => '진앙에서 이 거리만큼 더 넓게 인접 도/현을 후보에 넣는다. 실제 사건 6건으로 60km 가 맞춰져 있다.'],
    ];

    /** @var array<string, string> */
    public const DEFAULTS = [
        'EMAIL_ENABLED'              => 'false',
        'EMAIL_TO'                   => '',
        'EMAIL_CC'                   => '',
        'EMAIL_FROM'                 => '',
        'EMAIL_INCLUDE_BP_DETAILS'   => 'false',
        'TELEGRAM_ENABLED'           => 'false',
        'TELEGRAM_INCLUDE_BP_DETAILS'=> 'false',
        'ALERT_ENABLED'              => 'true',
        'ALERT_DRY_RUN'              => 'false',
        'ALLOWED_RECIPIENT_DOMAINS'  => 'sk.com',
        'ADMIN_CANDIDATE_MARGIN_KM'  => '60',
        'LLM_ENABLED'               => 'false',
        'LLM_FORMAT'                => 'inhouse',
        'LLM_BASE'                  => '',
        'LLM_MODEL'                 => '',
        'LLM_KEY'                   => '',
        'LLM_EXTRA'                 => '',
        'LLM_TIMEOUT'               => '60',
        'INSPECTION_SLA_HOURS'      => '24',
    ];

    /** 화면으로 되돌려 보내지 않는 값. 한 번 넣으면 서버에만 남는다. */
    public const SECRETS = ['LLM_KEY'];

    // ── 스토어 헬퍼 ───────────────────────────────────────────────

    private static function pathFor(string $rel): string
    {
        return Config::dataDir() . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, $rel);
    }

    private static function readJson(string $rel, mixed $default = null): mixed
    {
        $path = self::pathFor($rel);
        if (!file_exists($path)) { return $default; }
        $raw = file_get_contents($path);
        if ($raw === false || trim($raw) === '') { return $default; }
        $v = json_decode($raw, true);
        return ($v === null) ? $default : $v;
    }

    private static function writeJson(string $rel, mixed $data): void
    {
        $path = self::pathFor($rel);
        $dir  = dirname($path);
        if (!is_dir($dir)) { mkdir($dir, 0755, true); }
        $json = (string) json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $tmp  = $path . '.tmp.' . getmypid();
        $fp   = fopen($tmp, 'w');
        if ($fp === false) {
            throw new \RuntimeException("Cannot write: {$tmp}");
        }
        flock($fp, LOCK_EX);
        fwrite($fp, $json);
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
        rename($tmp, $path);
    }

    private static function appendLine(string $rel, array $row): void
    {
        $path = self::pathFor($rel);
        $dir  = dirname($path);
        if (!is_dir($dir)) { mkdir($dir, 0755, true); }
        $fp = fopen($path, 'a');
        if ($fp === false) {
            throw new \RuntimeException("Cannot append to: {$path}");
        }
        flock($fp, LOCK_EX);
        fwrite($fp, json_encode($row, JSON_UNESCAPED_UNICODE) . "\n");
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    private static function now(): string
    {
        return (new \DateTime('now', new \DateTimeZone('Asia/Seoul')))->format(\DATE_ATOM);
    }

    // ── 공개 API ──────────────────────────────────────────────────

    /**
     * 저장된 설정을 읽는다. 없는 키는 DEFAULTS 로 채운다.
     *
     * @return array<string, string>
     */
    public static function load(): array
    {
        $doc   = self::readJson(self::REL, []);
        $saved = (is_array($doc) && isset($doc['values']) && is_array($doc['values']))
            ? $doc['values'] : [];
        $result = self::DEFAULTS;
        foreach ($saved as $k => $v) {
            if (array_key_exists($k, self::DEFAULTS)) {
                $result[$k] = (string) $v;
            }
        }
        return $result;
    }

    /**
     * 설정을 저장하고 변경 이력을 남긴다.
     *
     * @param array<string, mixed> $values
     * @return array{0: array<string, string>, 1: array<string, array<int, string>>}
     */
    public static function save(string $actor, array $values): array
    {
        $before = self::load();

        // 알려진 키만. 값은 모두 문자열.
        $incoming = [];
        foreach ($values as $k => $v) {
            if (array_key_exists($k, self::DEFAULTS)) {
                $incoming[$k] = (string) $v;
            }
        }

        // 비밀은 빈 값으로 오면 "안 바꾼다" 는 뜻.
        // 화면이 값을 되받지 못하므로, 그대로 저장하면 다른 항목만 고쳐도 키가 지워진다.
        foreach (self::SECRETS as $k) {
            if (isset($incoming[$k]) && trim($incoming[$k]) === '') {
                unset($incoming[$k]);
            }
        }

        $after   = array_merge($before, $incoming);
        $changed = [];
        foreach ($after as $k => $v) {
            $bv = $before[$k] ?? '';
            if ($bv !== $v) {
                $changed[$k] = [$bv, $v];
            }
        }

        // 이력에 비밀을 평문으로 남기면, 화면을 막아 둔 의미가 사라진다.
        // 바뀐 사실만 남기고 값은 지운다.
        foreach (self::SECRETS as $k) {
            if (isset($changed[$k])) {
                $changed[$k] = [
                    ($before[$k] ?? '') !== '' ? '(설정됨)' : '(없음)',
                    ($after[$k]  ?? '') !== '' ? '(설정됨)' : '(없음)',
                ];
            }
        }

        self::writeJson(self::REL, [
            'values'    => $after,
            'updatedAt' => self::now(),
            'updatedBy' => $actor,
        ]);

        if (!empty($changed)) {
            self::appendLine(self::HISTORY_REL, [
                'ts'      => self::now(),
                'actor'   => $actor,
                'changed' => $changed,
            ]);
        }

        return [$after, $changed];
    }

    /**
     * 저장 전에 막을 것만 막는다. 나머지는 경고로 알린다.
     *
     * @param array<string, mixed> $values
     * @return array{0: string[], 1: string[]}  [problems, warnings]
     */
    public static function validate(array $values): array
    {
        $problems = [];
        $warnings = [];

        $domainStr  = (string) ($values['ALLOWED_RECIPIENT_DOMAINS'] ?? '');
        $domains    = array_filter(array_map(
            fn($d) => strtolower(trim($d)),
            explode(',', $domainStr)
        ));

        $recipStr   = (string) ($values['EMAIL_TO'] ?? '');
        $recipients = array_filter(array_map('trim', explode(',', $recipStr)));

        if (($values['EMAIL_ENABLED'] ?? '') === 'true') {
            if (empty($recipients)) {
                $problems[] = '초안 만들기를 켜려면 기본 수신자가 최소 한 명 있어야 한다';
            }
            foreach ($recipients as $r) {
                $dom = str_contains($r, '@') ? strtolower(explode('@', $r)[1] ?? '') : '';
                if (!$dom) {
                    $problems[] = "수신자 형식이 아니다: {$r}";
                } elseif (!empty($domains) && !in_array($dom, $domains, true)) {
                    $problems[] = "허용 도메인 밖 수신자다: {$r} (허용: " . implode(', ', $domains) . ')';
                }
            }
            $sender = trim((string) ($values['EMAIL_FROM'] ?? ''));
            if ($sender !== '' && !str_contains($sender, '@')) {
                $problems[] = "발신자 형식이 아니다: {$sender}";
            }
            if ($sender === '') {
                $warnings[] = '발신자를 비웠다 — 초안을 여는 사람의 계정으로 채워진다. 보통 이게 낫다.';
            }
        }

        $slaRaw = trim((string) ($values['INSPECTION_SLA_HOURS'] ?? '24'));
        if (!is_numeric($slaRaw)) {
            $problems[] = '점검 회신 기한이 숫자가 아니다';
        } else {
            $sla = (int) $slaRaw;
            if ($sla < 1 || $sla > 168) {
                $problems[] = '점검 회신 기한은 1~168시간 사이여야 한다';
            }
        }

        $marginRaw = trim((string) ($values['ADMIN_CANDIDATE_MARGIN_KM'] ?? '60'));
        if (!is_numeric($marginRaw)) {
            $problems[] = '도/현 후보 마진은 숫자여야 한다';
        } else {
            $margin = (int) $marginRaw;
            if ($margin < 0 || $margin > 300) {
                $problems[] = '도/현 후보 마진은 0~300km 사이여야 한다';
            } elseif ($margin < 30) {
                $warnings[] = '마진이 좁으면 경계 근처 사건에서 인접 도/현을 놓친다(노토반도·경주가 그런 사례다).';
            }
        }

        if (($values['ALERT_ENABLED'] ?? '') === 'true' && ($values['ALERT_DRY_RUN'] ?? '') === 'true') {
            $warnings[] = '모의 실행이 켜져 있어 실제로는 아무것도 나가지 않는다.';
        }

        return [$problems, $warnings];
    }

    /**
     * 설정을 화면에 보낼 때 비밀은 뺀다. 대신 들어 있는지 여부만 알린다.
     *
     * @param array<string, string> $values
     * @return array<string, mixed>
     */
    public static function public(array $values): array
    {
        $out = array_filter($values, fn($k) => !in_array($k, self::SECRETS, true), ARRAY_FILTER_USE_KEY);
        foreach (self::SECRETS as $k) {
            $out[$k . '_SET'] = trim((string) ($values[$k] ?? '')) !== '';
        }
        return $out;
    }

    /**
     * Llm::call() 이 받는 모양으로 바꾼다.
     *
     * @param array<string, string> $values
     * @return array<string, mixed>
     */
    public static function llmConfig(array $values): array
    {
        $extra  = [];
        $rawExtra = trim((string) ($values['LLM_EXTRA'] ?? ''));
        if ($rawExtra !== '') {
            $parsed = json_decode($rawExtra, true);
            if (is_array($parsed)) {
                $extra = $parsed;
            }
        }

        $timeoutRaw = trim((string) ($values['LLM_TIMEOUT'] ?? '60'));
        $timeout    = is_numeric($timeoutRaw) ? (int) $timeoutRaw : 60;
        $timeout    = max(5, min(300, $timeout));

        return [
            'format'  => trim((string) ($values['LLM_FORMAT'] ?? 'inhouse')) ?: 'inhouse',
            'base'    => trim((string) ($values['LLM_BASE']   ?? '')),
            'model'   => trim((string) ($values['LLM_MODEL']  ?? '')),
            'key'     => (string) ($values['LLM_KEY'] ?? ''),
            'extra'   => $extra,
            'timeout' => $timeout,
        ];
    }

    /**
     * 현재 설정으로 LLM 을 부른다. Llm::call() 에 llmConfig() 를 넘기는 편의 함수.
     *
     * @throws \RuntimeException
     */
    public static function callLlm(string $prompt, int $maxTokens = 2000, float $temperature = 0.0): string
    {
        $cfg = self::llmConfig(self::load());
        return Llm::call($cfg, $prompt, $maxTokens, $temperature, $cfg['timeout']);
    }
}
