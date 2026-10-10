<?php
// -*- coding: utf-8 -*-
declare(strict_types=1);

/**
 * MergeRules — 같은 회사가 여러 이름으로 갈라져 있던 것을 합치는 규칙.
 *
 * Port of merge_rules.py.
 *
 * 두 인격은 서로 다른 조사 회차다.
 *   등급 보유 행 = m-BP Grading 대상 조사분 (grade·소재군 확정, site 적음)
 *   무등급 행    = 사업소 전수 조사분        (site 많음, 등급 없음)
 * 합집합을 취해야 커버리지와 등급이 동시에 산다.
 *
 * 실제 병합 규칙(어느 표기가 어느 BP 인가)은 곧 BP 명부라 사내 데이터다.
 * 그래서 코드에 박지 않고 데이터 디렉토리의 baseline/merge_rules.json 에서 읽는다.
 * 모사 프로필에는 빈 규칙이 실린다 — 갈아끼우면 실제 규칙이 따라온다.
 */
final class MergeRules
{
    private const REL = 'baseline/merge_rules.json';

    /** @var array<string, string>|null */
    private static ?array $cache = null;

    // ── 스토어 헬퍼 ───────────────────────────────────────────────

    private static function pathFor(string $rel): string
    {
        return Config::dataDir() . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, $rel);
    }

    // ── 공개 API ──────────────────────────────────────────────────

    /**
     * 병합 규칙을 읽는다.
     * baseline/merge_rules.json 이 없거나 비어 있으면 빈 배열을 반환한다 — 병합 없음.
     *
     * @return array<string, string>  [원본 표기 → 정규 BP 이름]
     */
    public static function loadRules(): array
    {
        if (self::$cache !== null) { return self::$cache; }

        $path = self::pathFor(self::REL);
        if (!file_exists($path)) {
            self::$cache = [];
            return self::$cache;
        }

        $raw = file_get_contents($path);
        if ($raw === false || trim($raw) === '') {
            self::$cache = [];
            return self::$cache;
        }

        $parsed = json_decode($raw, true);
        if (!is_array($parsed)) {
            self::$cache = [];
            return self::$cache;
        }

        // 값이 문자열인 것만 받는다 (규격: {"원본표기": "정규명"})
        $rules = [];
        foreach ($parsed as $k => $v) {
            if (is_string($k) && is_string($v)) {
                $rules[$k] = $v;
            }
        }
        self::$cache = $rules;
        return self::$cache;
    }

    /**
     * 캐시를 비운다. 규칙 파일을 갱신한 뒤 새로 읽어야 할 때 쓴다.
     */
    public static function clearCache(): void
    {
        self::$cache = null;
    }

    /**
     * 표기 하나를 정규 BP 이름으로 바꾼다.
     * 규칙에 없으면 원래 값을 그대로 돌려준다.
     */
    public static function resolve(string $name): string
    {
        $rules = self::loadRules();
        return $rules[$name] ?? $name;
    }

    /**
     * 행 배열의 bpName 을 일괄 정규화한다. 고친 행 수를 반환한다.
     *
     * @param array<array<string, mixed>> $rows  참조로 받아 직접 수정한다
     */
    public static function normalizeRows(array &$rows): int
    {
        $rules = self::loadRules();
        if (empty($rules)) { return 0; }

        $n = 0;
        foreach ($rows as &$r) {
            $orig = (string) ($r['bpName'] ?? '');
            if ($orig !== '' && isset($rules[$orig])) {
                $r['bpName'] = $rules[$orig];
                $n++;
            }
        }
        unset($r);
        return $n;
    }
}
