<?php
// -*- coding: utf-8 -*-
declare(strict_types=1);

/**
 * Eml — .eml 초안 만들기.
 *
 * Port of eml.py.
 *
 * 서버는 SMTP 를 쓰지 않는다. 이 클래스는 .eml 바이트를 만들어 내려보내고,
 * 담당자가 그 파일을 열면 편집 가능한 초안이 뜬다.
 * [보내기]를 누르는 것은 언제나 사람이다.
 *
 * 규격 확인 사항
 *   X-Unsent: 1     이게 없으면 읽기 전용으로 열린다
 *   줄바꿈 CRLF      LF 만 쓰면 클라이언트에 따라 헤더를 못 읽는다
 *   제목·본문 인코딩   날것 한글은 깨진다
 */
final class Eml
{
    private const CRLF = "\r\n";

    // 헤더에 들어가면 안 되는 것 — 줄바꿈은 헤더 주입 벡터다.
    private const BAD_HDR_PATTERN  = '/[\r\n\x00]/';

    // 파일 이름에 쓸 수 없는 글자. 한글은 남긴다.
    private const BAD_FNAME_PATTERN = '/[\\\\\\/:*?"<>|\\r\\n\\t]/u';

    // ── 내부 유틸 ─────────────────────────────────────────────────

    /**
     * 헤더 값에서 줄바꿈을 지운다. 헤더 주입을 막는 자리다.
     */
    private static function clean(mixed $v): string
    {
        return trim((string) preg_replace(self::BAD_HDR_PATTERN, ' ', (string) $v));
    }

    /**
     * RFC 2047 — 아스키면 그대로, 아니면 =?utf-8?B?…?= 로 접어 쓴다.
     * CRLF 로 접는다 (LF 만 쓰면 클라이언트에 따라 그 뒤를 못 읽는다).
     */
    private static function encodeHeader(string $v, string $name = ''): string
    {
        $v = self::clean($v);
        if ($v === '') {
            return '';
        }
        // 순수 ASCII 인지 확인 — strlen(UTF-8) == mb_strlen(UTF-8) 은 ASCII 에서만 같다
        if (strlen($v) === mb_strlen($v, 'UTF-8')) {
            return $v;
        }
        // RFC 2047 base64 — mb_encode_mimeheader 는 내부적으로 CRLF 접기를 한다.
        // maxlinelen 을 낮게 주면 encoded-word 가 76자를 넘지 않는다.
        // PHP 는 linesep 인자를 받지 않으므로 결과에서 LF → CRLF 변환을 한다.
        $encoded = mb_encode_mimeheader($v, 'UTF-8', 'B', "\r\n");
        return $encoded;
    }

    /**
     * 문자열이든 배열이든 쉼표로 이어진 한 줄로. 빈 것은 버린다.
     */
    private static function addrList(mixed $v): string
    {
        if ($v === null) { return ''; }
        $items = is_array($v) ? $v : explode(',', (string) $v);
        $out   = array_filter(array_map([self::class, 'clean'], $items));
        return implode(', ', $out);
    }

    // ── 공개 API ──────────────────────────────────────────────────

    /**
     * .eml 바이트를 만든다. 보내지 않는다 — 만들기만 한다.
     *
     * sender 는 비워도 된다. 비우면 메일 클라이언트가 여는 사람의 계정으로 채운다.
     *
     * @param string|string[] $to
     * @param string|string[]|null $cc
     * @param array<string, string>|null $extraHeaders
     * @return string  UTF-8 인코딩된 .eml 내용
     * @throws \InvalidArgumentException
     */
    public static function build(
        mixed  $to,
        string $subject,
        string $body,
        mixed  $cc           = null,
        string $sender       = '',
        string $replyTo      = '',
        ?array $extraHeaders = null
    ): string {
        if (trim($subject) === '') {
            throw new \InvalidArgumentException('제목이 없다');
        }
        if (trim($body) === '') {
            throw new \InvalidArgumentException('본문이 없다');
        }

        // 본문을 base64 로 접는다 — 76자 한 줄씩
        $b64   = base64_encode($body);
        $lines = str_split($b64, 76) ?: [''];

        // RFC 2822 Date
        $date = date('r');

        // Message-ID
        $msgId = '<mgscs.' . time() . '.' . bin2hex(random_bytes(6)) . '@mgscs.local>';

        $h = [
            ['X-Unsent',                   '1'],           // ★ 초안으로 열리게 한다
            ['MIME-Version',               '1.0'],
            ['Date',                       $date],
            ['Message-ID',                 $msgId],
            ['To',                         self::addrList($to)],
        ];
        if ($cc !== null) {
            $h[] = ['Cc', self::addrList($cc)];
        }
        if ($sender !== '') {
            $h[] = ['From', self::addrList($sender)];
        }
        if ($replyTo !== '') {
            $h[] = ['Reply-To', self::addrList($replyTo)];
        }
        $h[] = ['Subject',                   self::encodeHeader($subject, 'Subject')];
        $h[] = ['Content-Type',              'text/plain; charset="utf-8"'];
        $h[] = ['Content-Transfer-Encoding', 'base64'];

        foreach ($extraHeaders ?? [] as $k => $v) {
            $k   = self::clean($k);
            $h[] = [$k, self::encodeHeader($v, $k)];
        }

        $head = implode(self::CRLF, array_map(fn($pair) => $pair[0] . ': ' . $pair[1], $h));
        $out  = $head . self::CRLF . self::CRLF . implode(self::CRLF, $lines) . self::CRLF;

        // 마지막 안전장치 — 홀로 남은 LF 를 CRLF 로 올린다.
        // 이미 CRLF 인 것을 두 번 바꾸지 않도록 LF 기준으로 정규화한다.
        $out = str_replace(self::CRLF, "\n", $out);
        $out = str_replace("\n", self::CRLF, $out);

        return $out;
    }

    /**
     * 내용이 드러나는 파일 이름으로 짓는다.
     * kind = 종류 (예: 'inspection'), ref = 점검번호 등.
     */
    public static function filename(string $kind, string $ref = '', ?string $day = null): string
    {
        $day  = $day ?? date('Ymd');
        $kind = (string) preg_replace(self::BAD_FNAME_PATTERN, '_', $kind);
        $ref  = $ref !== '' ? ((string) preg_replace(self::BAD_FNAME_PATTERN, '_', $ref)) : '';

        $parts = array_filter(['mgscs', $kind, $ref, $day]);
        $name  = implode('_', $parts);

        // 최대 120자 + .eml
        if (mb_strlen($name, 'UTF-8') > 120) {
            $name = mb_substr($name, 0, 120, 'UTF-8');
        }
        return $name . '.eml';
    }

    // ── 두 경로를 시그니처로 나눈다 ───────────────────────────────
    // 사내 공지와 BP 점검 메일은 다른 함수로 두고,
    // BP 주소가 사내 공지 쪽으로 흘러들 수 없게 한다.

    /**
     * 사내 공지 초안.
     * 허용 도메인(ALLOWED_RECIPIENT_DOMAINS) 안의 주소만 받는다.
     *
     * @param string|string[] $to
     * @param string|string[]|null $cc
     * @return string
     * @throws \InvalidArgumentException  사외 주소가 섞이면 던진다
     */
    public static function draftInternal(
        mixed  $to,
        string $subject,
        string $body,
        mixed  $cc     = null,
        string $sender = ''
    ): string {
        // AlertConfig 가 로드된 경우에만 도메인 검사를 한다.
        if (class_exists('AlertConfig', false)) {
            $cfg     = AlertConfig::load();
            $allowed = array_filter(array_map(
                fn($d) => strtolower(trim($d)),
                explode(',', (string) ($cfg['ALLOWED_RECIPIENT_DOMAINS'] ?? ''))
            ));
            if (!empty($allowed)) {
                $toList  = is_array($to) ? $to : explode(',', (string) $to);
                $outside = [];
                foreach ($toList as $a) {
                    $a   = trim((string) $a);
                    $dom = str_contains($a, '@') ? strtolower(explode('@', $a)[1] ?? '') : '';
                    if ($dom && !in_array($dom, $allowed, true)) {
                        $outside[] = $a;
                    }
                }
                if (!empty($outside)) {
                    throw new \InvalidArgumentException(
                        '사내 공지에 사외 주소가 섞였다: ' . implode(', ', $outside)
                    );
                }
            }
        }
        return self::build($to, $subject, $body, $cc, $sender);
    }

    /**
     * BP(사외 협력사) 점검 메일 초안.
     * 허용 도메인 검사를 하지 않는다 — 사외 주소가 정상이다.
     *
     * @param string|string[] $to
     * @param string|string[]|null $cc
     * @return string
     */
    public static function draftBp(
        mixed  $to,
        string $subject,
        string $body,
        mixed  $cc      = null,
        string $sender  = '',
        string $replyTo = ''
    ): string {
        return self::build($to, $subject, $body, $cc, $sender, $replyTo);
    }
}
