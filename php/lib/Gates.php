<?php
// -*- coding: utf-8 -*-
declare(strict_types=1);

/**
 * Gates — 5게이트 판정 (서버 정본).
 *
 * Port of gates.py.
 * 화면 JS 와 글자 단위로 같은 낱말 사전을 써야 한다. 어느 한쪽만 고치면
 * drift_test 가 릴리스를 막는다.
 *
 * 판정 흐름
 *   G1 지리 근접    — 지역 정합 BP 가 실제로 있는가
 *   G2 소재군 노출  — 8축 소재군이 있고 그 지역에 있는가
 *   G3 출처 공식성  — 공식 경보이거나 교차 확인 2건 이상인가
 *   G4 실제 중단    — 근거 문장이 '일어난 일'을 말하는가
 *   G5 사실 확인    — 공식이면 단독 충분, 아니면 출처 2곳 이상인가
 */
final class Gates
{
    // ── 낱말 사전 ─────────────────────────────────────────────────
    // 화면(mgscs.html) 의 /*DR*/ /*SP*/ /*NG*/ 표시 사이와 글자까지 같아야 한다.

    public const MATERIAL_GROUPS = [
        'Photo', 'Chemical', 'Slurry', 'Pad', 'Gas', 'Precursor', 'Wafer', 'Target',
    ];

    // /u — UTF-8 한글 정확히 매칭
    private const DISRUPTION_RE =
        '/가동\s?중단|가동\s?중지|조업\s?중단|생산\s?중단|생산\s?차질|출하\s?중단|' .
        '출하\s?지연|선적\s?중단|통관\s?중단|공장\s?폐쇄|조업\s?정지|정전|단수|' .
        '급수\s?중단|항로\s?폐쇄|항로\s?우회|운항\s?중단|봉쇄\s?조치|셧다운|전면\s?중단|' .
        'shut\s?down|shutdown|halted|suspended|suspension|closure|power\s?outage|' .
        'production\s?stop/u';

    private const SPECULATION_RE =
        '/우려|전망|예상|가능성|검토\s?중|추진\s?중|계획\s?중|할\s?수\s?있|것으로\s?보인|' .
        'may\s|could\s|expected\s+to|plans?\s+to|considering|potential/u';

    private const NEGATION_RE =
        '/없어|없다|없음|없이|없는|미발생|아니다|아닌|않았|않은|않기|차질\s?최소화|' .
        '영향\s?최소화|차질\s?없|경미|미미한|no\s+impact|not\s+affected|unaffected|' .
        'no\s+disruption|without\s+disruption|minimal\s+impact|limited\s+impact/u';

    // ── 낱말 판단 ─────────────────────────────────────────────────

    public static function isDisruption(string $text): bool
    {
        return (bool) preg_match(self::DISRUPTION_RE, $text);
    }

    public static function isSpeculation(string $text): bool
    {
        return (bool) preg_match(self::SPECULATION_RE, $text);
    }

    public static function isNegation(string $text): bool
    {
        return (bool) preg_match(self::NEGATION_RE, $text);
    }

    /**
     * 텍스트에서 8소재군 중 언급된 것들을 반환한다.
     * @return string[]
     */
    public static function materialGroupHits(string $text): array
    {
        $hits = [];
        foreach (self::MATERIAL_GROUPS as $g) {
            if (mb_stripos($text, $g, 0, 'UTF-8') !== false) {
                $hits[] = $g;
            }
        }
        return $hits;
    }

    // ── 내부 헬퍼 ─────────────────────────────────────────────────

    private static function gate(string $status, string $why): array
    {
        return ['status' => $status, 'why' => $why];
    }

    /**
     * affectedMaterials 배열을 8축 LUT 로 정규화한다.
     * @param mixed[] $raw
     * @return string[]
     */
    private static function normaliseMats(array $raw): array
    {
        static $lut = null;
        if ($lut === null) {
            $lut = [];
            foreach (self::MATERIAL_GROUPS as $g) {
                $lut[strtolower($g)] = $g;
            }
        }
        $out = [];
        foreach ($raw as $m) {
            $k = strtolower(trim((string) $m));
            if (isset($lut[$k])) {
                $out[] = $lut[$k];
            }
        }
        return $out;
    }

    /**
     * bpMatchCount 를 int|null 로 정규화한다.
     * 숫자로 변환 불가 → null ('모름' — 0 으로 단정하지 않는다).
     */
    private static function normaliseBp(mixed $bp): ?int
    {
        if ($bp === null) {
            return null;
        }
        if (is_numeric($bp)) {
            return (int) $bp;
        }
        return null;
    }

    // ── 개별 게이트 ──────────────────────────────────────────────

    /**
     * G1 지리 근접 — 지역 정합 BP 가 실제로 있는가.
     * $signal['bpMatchCount'] 를 참조한다.
     */
    public static function gate1Geography(array $signal, array $bpRows = []): array
    {
        $bp = self::normaliseBp($signal['bpMatchCount'] ?? null);
        if ($bp === null) {
            return self::gate('unknown', '[5002] BP 명부 미설정 — 지리 판정 불가');
        }
        if ($bp > 0) {
            return self::gate('pass', "지역 정합 BP {$bp}사");
        }
        return self::gate('fail', '[5001] 영향권 내 등록 BP 없음');
    }

    /**
     * G2 소재군 직접 노출 — 8축 소재군이 있고 그 지역에 있는가.
     */
    public static function gate2Material(array $signal, array $bpRows = []): array
    {
        $mats = self::normaliseMats($signal['affectedMaterials'] ?? []);
        $bp   = self::normaliseBp($signal['bpMatchCount'] ?? null);

        if (empty($mats)) {
            return self::gate('fail', '[5003] 8소재군에 해당하는 소재군 없음');
        }
        if ($bp === null) {
            return self::gate('unknown', '[5002] BP 명부 미설정 — 소재군 노출 확인 불가');
        }
        if ($bp > 0) {
            return self::gate('pass', implode('·', $mats) . ' 노출');
        }
        return self::gate('fail', '[5004] ' . implode('·', $mats) . ' — 해당 지역 생산·출하 거점 없음');
    }

    /**
     * G3 출처 공식성 — 공식 경보이거나 교차 확인 2건 이상인가.
     */
    public static function gate3Source(array $signal, bool $requireOfficial = false): array
    {
        $official = ($signal['official'] ?? null) === true;
        $corr     = (int) ($signal['corroboration'] ?? 0);

        if ($official) {
            return self::gate('pass', '공식 기관 경보');
        }
        if ($requireOfficial) {
            return self::gate('fail', '공식 발표 아님');
        }
        if ($corr >= 2) {
            return self::gate('pass', "교차 확인 {$corr}개 출처로 대체");
        }
        return self::gate('fail', '[5005] 공식 발표 아니고 교차 확인도 부족');
    }

    /**
     * G4 실제 중단 정황 — 근거 문장이 '일어난 일'을 말하는가.
     * $signal['disruptionEvidence'] 는 LLM 이 뽑은 원문 문장이다.
     */
    public static function gate4Disruption(array $signal): array
    {
        $ev = trim((string) ($signal['disruptionEvidence'] ?? ''));

        if ($ev === '') {
            return self::gate('unknown', '[5007] 중단 정황 근거 문장 없음');
        }
        if (!self::isDisruption($ev)) {
            return self::gate('fail', '[5006] 근거 문장에 실제 중단 표현 없음');
        }
        if (self::isNegation($ev)) {
            return self::gate('fail', '부정 표현 — 오히려 영향 없음을 뜻함');
        }
        if (self::isSpeculation($ev)) {
            return self::gate('fail', '추측·전망 표현 — 실제 발생 아님');
        }
        return self::gate('pass', mb_substr($ev, 0, 80, 'UTF-8'));
    }

    /**
     * G5 사실 확인 — 공식이면 단독 충분, 아니면 출처 2곳 이상인가.
     */
    public static function gate5Specificity(array $signal): array
    {
        $official = ($signal['official'] ?? null) === true;
        $corr     = (int) ($signal['corroboration'] ?? 0);

        if ($official) {
            return self::gate('pass', '공식 발표 — 단독 출처로 충분');
        }
        if ($corr >= 2) {
            return self::gate('pass', "독립 출처 {$corr}곳 확인");
        }
        return self::gate('fail', "교차 확인 부족({$corr})");
    }

    // ── 통합 판정 ─────────────────────────────────────────────────

    /**
     * 5게이트를 모두 매기고 triage 를 돌려준다.
     *
     * 입력 (연상 배열 $i)
     *   affectedMaterials   string[]  LLM 이 뽑은 소재군
     *   disruptionEvidence  string    LLM 이 뽑은 중단 정황 원문 문장
     *   official            bool      공식 경보 회선인가 (코드가 정한다)
     *   corroboration       int       교차 확인 고유 출처 수 (코드가 센다)
     *   bpMatchCount        int|null  지역 정합 BP 수. null = 명부 미설정(모름)
     *
     * @param array<string, mixed> $i
     * @return array{gates: array, triage: string, blockedBy: string[], gateSummary: string}
     */
    public static function evaluate(array $i = [], bool $g3RequiresOfficial = false): array
    {
        $official = ($i['official'] ?? null) === true;
        $corr     = (int) ($i['corroboration'] ?? 0);
        $mats     = self::normaliseMats($i['affectedMaterials'] ?? []);
        $bp       = self::normaliseBp($i['bpMatchCount'] ?? null);
        $ev       = trim((string) ($i['disruptionEvidence'] ?? ''));

        // G1
        if ($bp === null) {
            $g1 = self::gate('unknown', '[5002] BP 명부 미설정 — 지리 판정 불가');
        } elseif ($bp > 0) {
            $g1 = self::gate('pass', "지역 정합 BP {$bp}사");
        } else {
            $g1 = self::gate('fail', '[5001] 영향권 내 등록 BP 없음');
        }

        // G2
        if (empty($mats)) {
            $g2 = self::gate('fail', '[5003] 8소재군에 해당하는 소재군 없음');
        } elseif ($bp === null) {
            $g2 = self::gate('unknown', '[5002] BP 명부 미설정 — 소재군 노출 확인 불가');
        } elseif ($bp > 0) {
            $g2 = self::gate('pass', implode('·', $mats) . ' 노출');
        } else {
            $g2 = self::gate('fail', '[5004] ' . implode('·', $mats) . ' — 해당 지역 생산·출하 거점 없음');
        }

        // G3
        if ($official) {
            $g3 = self::gate('pass', '공식 기관 경보');
        } elseif ($g3RequiresOfficial) {
            $g3 = self::gate('fail', '공식 발표 아님');
        } elseif ($corr >= 2) {
            $g3 = self::gate('pass', "교차 확인 {$corr}개 출처로 대체");
        } else {
            $g3 = self::gate('fail', '[5005] 공식 발표 아니고 교차 확인도 부족');
        }

        // G4
        if ($ev === '') {
            $g4 = self::gate('unknown', '[5007] 중단 정황 근거 문장 없음');
        } elseif (!self::isDisruption($ev)) {
            $g4 = self::gate('fail', '[5006] 근거 문장에 실제 중단 표현 없음');
        } elseif (self::isNegation($ev)) {
            $g4 = self::gate('fail', '부정 표현 — 오히려 영향 없음을 뜻함');
        } elseif (self::isSpeculation($ev)) {
            $g4 = self::gate('fail', '추측·전망 표현 — 실제 발생 아님');
        } else {
            $g4 = self::gate('pass', mb_substr($ev, 0, 80, 'UTF-8'));
        }

        // G5
        if ($official) {
            $g5 = self::gate('pass', '공식 발표 — 단독 출처로 충분');
        } elseif ($corr >= 2) {
            $g5 = self::gate('pass', "독립 출처 {$corr}곳 확인");
        } else {
            $g5 = self::gate('fail', "교차 확인 부족({$corr})");
        }

        $gates = ['g1' => $g1, 'g2' => $g2, 'g3' => $g3, 'g4' => $g4, 'g5' => $g5];

        $mark    = ['pass' => '○', 'fail' => '×'];
        $blocked = [];
        $summary = [];
        foreach ($gates as $k => $g) {
            if ($g['status'] !== 'pass') {
                $blocked[] = strtoupper($k);
            }
            $summary[] = strtoupper($k) . ':' . ($mark[$g['status']] ?? '?');
        }

        return [
            'gates'       => $gates,
            'triage'      => empty($blocked) ? 'review' : 'monitor',
            'blockedBy'   => $blocked,
            'gateSummary' => implode(' ', $summary),
        ];
    }
}
