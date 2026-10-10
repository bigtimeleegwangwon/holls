<?php
// -*- coding: utf-8 -*-
declare(strict_types=1);

/**
 * SignalFilter — 수집된 것 중 재난·재해로 볼 것만 남긴다.
 *
 * Port of signal_filter.py.
 *
 * 브라우저가 훑어 오는 것은 하루 150~200건인데 대부분은 우리와 무관하다.
 * 전부 쌓으면 공통 이력이 잡동사니가 되고, 사람이 그 안에서 다시 고른다.
 *
 * 그런데 조용히 버리지 않는다. 뺀 것을 왜 뺐는지와 함께 따로 쌓는다.
 * 이유는 하나다 — 지금 우리가 하려는 것은 workflow 지만, 다음은 agent 다.
 * "무엇을 잘못 걸렀나" 를 보고 규칙을 고치려면 그 기록이 있어야 한다.
 *
 * 선별은 두 층이다.
 *   ① 범주   — 감시 20종에 드는가 ('기타' 는 뺀다)
 *   ② 낱말   — 제목·요약에 재난·공급 차질을 가리키는 말이 있는가
 * 둘 중 하나만 통과해도 남긴다 — 여기서 조이면 진짜를 놓친다.
 */
final class SignalFilter
{
    private const DROP_DIR = 'signals/dropped';

    /**
     * 감시하는 20종. 화면(DEFAULT_CATEGORIES)과 같은 이름이어야 한다.
     *
     * @var string[]
     */
    public const WATCHED = [
        '파업', '화재', '홍수', '지진', '쓰나미', '태풍', '가뭄', '폭염', '한파',
        '전쟁', '무역 규제', '해상 봉쇄', '환율', '부도', '화산',
        '우크라이나', '이스라엘', '폭발', '산업 안전', '정전',
    ];

    // 범주가 '기타' 로 와도 이런 말이 있으면 남긴다 — 분류가 늘 맞지는 않는다.
    // 한글 포함이므로 /u 플래그 필수.
    private const KEEP_PATTERN =
        '지진|해일|쓰나미|태풍|호우|폭우|홍수|산사태|화산|분화|한파|폭염|가뭄|산불|' .
        '화재|폭발|정전|단수|누출|유출|붕괴|침수|' .
        '파업|봉쇄|폐쇄|중단|차질|셧다운|리콜|규제|제재|수출\s?통제|관세|' .
        '공급망|반도체|웨이퍼|소재|공장|플랜트|생산라인|' .
        'earthquake|tsunami|typhoon|flood|volcan|wildfire|' .
        'fire|explosion|blackout|outage|leak|collapse|' .
        'strike|blockade|shutdown|halt|disrupt|sanction|export\s?control|tariff|' .
        'supply\s?chain|semiconductor|wafer|fab|plant';

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
        $json = (string) json_encode($data, JSON_UNESCAPED_UNICODE);
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

    private static function now(): string
    {
        return (new \DateTime('now', new \DateTimeZone('Asia/Seoul')))->format(\DATE_ATOM);
    }

    // ── 내부 유틸 ─────────────────────────────────────────────────

    /**
     * 판정에 쓸 통합 텍스트. 제목·요약·위치를 한 줄로.
     */
    private static function text(array $sig): string
    {
        $parts = [];
        foreach (['titleKo', 'title', 'summary', 'headline', 'location'] as $k) {
            $v = (string) ($sig[$k] ?? '');
            if ($v !== '') { $parts[] = $v; }
        }
        return implode(' ', $parts);
    }

    /**
     * 중복 판정용 열쇠. id 가 없으면 내용으로 만든다.
     *
     * 예전에는 id 가 없는 것이 전부 "" 하나로 뭉쳤다. 그래서 id 없는 신호가
     * 하루에 한 건 쌓이고 나면, 그 뒤로 오는 id 없는 신호는 전부 '이미 있음' 이
     * 되어 사라졌다. 버린 것을 남기려고 있는 모듈이 버린 것을 조용히 비우고 있었다.
     */
    private static function dedupKey(array $x): string
    {
        $sid = trim((string) ($x['id'] ?? ''));
        if ($sid !== '') { return $sid; }
        $parts = [];
        foreach (['titleKo', 'title', 'summary', 'url', 'link', 'source'] as $k) {
            $parts[] = (string) ($x[$k] ?? '');
        }
        return 'sha1:' . substr(sha1(implode("\x1f", $parts)), 0, 16);
    }

    // ── 공개 API ──────────────────────────────────────────────────

    /**
     * 남길지 뺄지. 뺄 때는 왜 를 함께 돌려준다.
     *
     * @param array<string, mixed> $sig
     * @return array{0: bool, 1: string}  [keep, reason]
     */
    public static function judge(array $sig): array
    {
        $cat = trim((string) ($sig['category'] ?? ''));
        if (in_array($cat, self::WATCHED, true)) {
            return [true, "범주 {$cat}"];
        }
        $txt = self::text($sig);
        if (preg_match('/' . self::KEEP_PATTERN . '/iu', $txt, $m)) {
            return [true, "낱말 '{$m[0]}'"];
        }
        if (trim($txt) === '') {
            return [false, '제목·요약이 비었다'];
        }
        return [false, "범주 '" . ($cat ?: '없음') . "' 이고 재난·공급 차질 낱말도 없다"];
    }

    /**
     * 받은 것을 남길 것과 뺄 것으로 가른다. 뺀 것에는 이유를 붙인다.
     *
     * @param array<array<string, mixed>> $signals
     * @return array{0: array<array<string, mixed>>, 1: array<array<string, mixed>>}  [keep, drop]
     */
    public static function split(array $signals): array
    {
        $keep = [];
        $drop = [];
        $now  = self::now();
        foreach ($signals as $s) {
            [$ok, $why] = self::judge($s);
            if ($ok) {
                $keep[] = array_merge($s, ['keepReason' => $why]);
            } else {
                $drop[] = array_merge($s, ['dropReason' => $why, 'droppedAt' => $now]);
            }
        }
        return [$keep, $drop];
    }

    /**
     * 뺀 것을 날짜별로 쌓는다. 이것이 나중에 규칙을 고치는 재료다.
     *
     * 같은 신호를 두 번 빼도 한 번만 쌓는다 — 30분마다 재수집하면 대부분이 이미 본 것이다.
     * 읽기-고치기-쓰기는 한 락으로 묶는다. ingest 는 여러 요청이 동시에 들어오고,
     * 락이 없으면 먼저 쓴 쪽이 통째로 사라진다.
     *
     * @param array<array<string, mixed>> $drop
     * @return int  새로 쌓인 건수
     */
    public static function recordDropped(array $drop, string $actor = 'unknown'): int
    {
        if (empty($drop)) { return 0; }

        $day  = (new \DateTime('now', new \DateTimeZone('Asia/Seoul')))->format('Ymd');
        $rel  = self::DROP_DIR . '/' . $day . '.json';
        $path = self::pathFor($rel);
        $dir  = dirname($path);
        if (!is_dir($dir)) { mkdir($dir, 0755, true); }

        // 읽기-고치기-쓰기를 파일 락으로 직렬화한다.
        // 쓰기용으로 열어야 락이 다른 프로세스를 막는다 — r+ 모드.
        // 파일이 없으면 먼저 만든다.
        if (!file_exists($path)) {
            file_put_contents($path, '[]');
        }

        $lockFp = fopen($path, 'r+');
        if ($lockFp === false) {
            return 0;
        }
        flock($lockFp, LOCK_EX);

        try {
            $raw  = stream_get_contents($lockFp);
            $have = (is_string($raw) && trim($raw) !== '')
                ? (json_decode($raw, true) ?: [])
                : [];
            if (!is_array($have)) { $have = []; }

            $seen  = [];
            foreach ($have as $x) { $seen[self::dedupKey($x)] = true; }

            $fresh = [];
            foreach ($drop as $x) {
                $k = self::dedupKey($x);
                if (isset($seen[$k])) { continue; }
                $seen[$k] = true;
                $fresh[]  = $x;
            }

            if (!empty($fresh)) {
                $merged = array_merge($have, $fresh);
                $json   = (string) json_encode($merged, JSON_UNESCAPED_UNICODE);
                rewind($lockFp);
                ftruncate($lockFp, 0);
                fwrite($lockFp, $json);
                fflush($lockFp);
            }

            return count($fresh);
        } finally {
            flock($lockFp, LOCK_UN);
            fclose($lockFp);
        }
    }

    /**
     * 뺀 것을 읽는다 — 사람이 "이건 잘못 걸렀다" 를 찾는 자리.
     *
     * @return array{0: array<array<string, mixed>>, 1: string[]}  [items, filenames]
     */
    public static function readDropped(int $days = 3): array
    {
        $base = self::pathFor(self::DROP_DIR);
        if (!is_dir($base)) { return [[], []]; }

        // 날짜 순으로 정렬한 파일 중 최근 $days 개
        $files = [];
        $dh    = opendir($base);
        if ($dh === false) { return [[], []]; }
        while (($f = readdir($dh)) !== false) {
            if (strlen($f) >= 2 && substr($f, 0, 2) === '20' && str_ends_with($f, '.json')) {
                $files[] = $f;
            }
        }
        closedir($dh);
        sort($files);
        $files = array_slice($files, -max(1, $days));

        $out = [];
        foreach ($files as $f) {
            $rel  = self::DROP_DIR . '/' . $f;
            $data = self::readJson($rel, []);
            if (is_array($data)) {
                foreach ($data as $item) {
                    $out[] = $item;
                }
            }
        }
        return [$out, $files];
    }
}
