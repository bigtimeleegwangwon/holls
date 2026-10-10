<?php
// -*- coding: utf-8 -*-
declare(strict_types=1);

/**
 * MaterialGroups — 소재군 정규화와 대분류.
 *
 * Port of material_groups.py.
 *
 * 두 가지를 한다.
 *   1) 별칭을 8축으로 모은다. 'NP Wafer' 는 Wafer 다. 표기가 갈리면 같은 소재군이
 *      화면에서 둘로 보이고, 판정에서도 서로 다른 축으로 취급된다.
 *   2) 8축 밖의 소재를 대분류로 묶는다. 기판(글래스)과 기판(ABF·유기)를 따로 두면
 *      목록만 길어지고 "기판 몇 사인가" 를 한눈에 못 본다.
 *
 * 8축은 판정 축이다. 임의로 늘리지 않는다 — 늘리면 알림 매칭 규칙이 흔들린다.
 */
final class MaterialGroups
{
    /**
     * 별칭 → 8축. bp_master_canonical 의 grpSrc→grp 대응을 그대로 따른다.
     * 키는 소문자로 비교한다.
     *
     * @var array<string, string>
     */
    public const ALIAS = [
        'np wafer'          => 'Wafer',
        'p-gas'             => 'Gas',
        'prime wafer'       => 'Wafer',
        '웨이퍼(분류 확인 필요)'  => 'Wafer',
        'pad'               => 'Pad',
        'photo chemical'    => 'Photo',
    ];

    /**
     * 8판정 축. 임의로 늘리지 않는다.
     *
     * @var string[]
     */
    public const AXIS8 = ['Photo', 'Chemical', 'Slurry', 'Pad', 'Gas', 'Precursor', 'Wafer', 'Target'];

    /**
     * 대분류. 소재전략 파트 구분을 따른다.
     * 화면에서 묶는 단위이고 판정에는 쓰지 않는다.
     * 각 항목: [카테고리명, [속하는 소재군 목록]]
     *
     * @var array<array{0: string, 1: string[]}>
     */
    public const CATEGORY = [
        ['Photo', ['Photo']],
        ['C&C',   ['Chemical', 'Slurry', 'Pad']],
        ['EDTW',  ['Gas', 'Precursor', 'Wafer', 'Target']],
        ['WLP/PKG/기타', [
            'WLP+PKG',
            '패키징소재(EMC·언더필·TIM)',
            '기판(글래스)',
            '기판(ABF·유기)',
            '카본·세라믹 부재',
            'Raw Material-TBD',
            '미지정',
        ]],
    ];

    // ── 내부 캐시 ─────────────────────────────────────────────────

    /** @var array<string, string>|null */
    private static ?array $ofGroup = null;

    private static function ofGroup(): array
    {
        if (self::$ofGroup === null) {
            self::$ofGroup = [];
            foreach (self::CATEGORY as [$cat, $groups]) {
                foreach ($groups as $g) {
                    self::$ofGroup[$g] = $cat;
                }
            }
        }
        return self::$ofGroup;
    }

    // ── 공개 API ──────────────────────────────────────────────────

    /**
     * 별칭이면 8축 이름으로, 아니면 원래 이름으로 돌려준다.
     * 대소문자 무관(소문자로 비교).
     */
    public static function normalize(mixed $name): string
    {
        $s = trim((string) ($name ?? ''));
        if ($s === '') { return ''; }
        return self::ALIAS[mb_strtolower($s, 'UTF-8')] ?? $s;
    }

    /**
     * 모든 행의 materialGroups 를 8축 표기로 통일한다. 고친 행 수를 반환한다.
     *
     * @param array<array<string, mixed>> $rows  참조로 받아 직접 수정한다
     */
    public static function normalizeRows(array &$rows): int
    {
        $n = 0;
        foreach ($rows as &$r) {
            $gs  = is_array($r['materialGroups'] ?? null) ? $r['materialGroups'] : [];
            $new = [];
            foreach ($gs as $g) {
                $v = self::normalize($g);
                if ($v !== '' && !in_array($v, $new, true)) {
                    $new[] = $v;
                }
            }
            if ($new !== $gs) {
                $r['materialGroups'] = $new;
                $n++;
            }
        }
        unset($r);
        return $n;
    }

    /**
     * 소재군 → 대분류. 8축에 없는 것은 '부재·기타'.
     */
    public static function categoryOf(string $group): string
    {
        return self::ofGroup()[$group] ?? '부재·기타';
    }

    /**
     * 8판정 축 전체 목록.
     *
     * @return string[]
     */
    public static function allGroups(): array
    {
        return self::AXIS8;
    }

    /**
     * 대분류 목록. 화면에서 섹션을 그릴 때 쓴다.
     *
     * @return array<string, string[]>  카테고리명 → 소재군 목록
     */
    public static function categories(): array
    {
        $out = [];
        foreach (self::CATEGORY as [$cat, $groups]) {
            $out[$cat] = $groups;
        }
        return $out;
    }

    /**
     * 이름이 8축 중 하나인가.
     */
    public static function isAxis(string $name): bool
    {
        return in_array($name, self::AXIS8, true);
    }
}
