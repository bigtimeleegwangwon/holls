<?php
// -*- coding: utf-8 -*-
/**
 * Feedback — 되먹임 원장.
 * feedback.py 완전 이식본.
 *
 * n 의 이유가 필터로 돌아가는 길.
 * 자동으로 규칙을 고치지 않는다 — 제안까지만 만든다.
 */
declare(strict_types=1);

require_once __DIR__ . '/Store.php';

final class Feedback
{
    private const LEDGER_REL = 'revision/feedback.jsonl';
    private const KST        = 'Asia/Seoul';

    // feedback.py REJECT_REASONS
    public const REJECT_REASONS = [
        'not_disaster' => [
            'detected' => false,
            'label'    => '재난·재해가 아니다',
            'layer' => 2, 'layerKo' => '1차 선별',
            'target' => 'signal_filter.KEEP_RE / WATCHED',
            'how'    => '제목에서 공통 낱말을 뽑아 제외 후보로 올린다',
        ],
        'wrong_region' => [
            'detected' => false,
            'label'    => '우리 권역이 아니다',
            'layer' => 3, 'layerKo' => 'G1 지리 근접',
            'target' => 'gates G1 · 행정구역 매칭',
            'how'    => '지역명 오인식인지 BP 명부 누락인지 가른다',
        ],
        'no_material' => [
            'detected' => false,
            'label'    => '우리 소재와 무관하다',
            'layer' => 3, 'layerKo' => 'G2 소재군 노출',
            'target' => 'gates G2 · 소재군 추출',
            'how'    => 'LLM 이 뽑은 소재군이 과했는지 본다',
        ],
        'speculation' => [
            'detected' => false,
            'label'    => '추측이지 실제 중단이 아니다',
            'layer' => 3, 'layerKo' => 'G4 실제 중단 정황',
            'target' => 'gates SPECULATION_SRC 사전',
            'how'    => '본문의 추측성 낱말을 사전에 보탠다',
        ],
        'duplicate' => [
            'detected' => false,
            'label'    => '이미 등록된 사건이다',
            'layer' => 1, 'layerKo' => '수집',
            'target' => '수집 중복 제거',
            'how'    => '같은 사건의 다른 기사 — 묶음 규칙을 본다',
        ],
        'minor' => [
            'detected' => true,
            'label'    => '사실이나 영향이 경미하다',
            'layer' => 4, 'layerKo' => '등급',
            'target' => 'grade() 임계값',
            'how'    => '탐지는 옳았다 — 등급 기준만 조인다',
        ],
        'stale' => [
            'detected' => false,
            'label'    => '너무 오래된 기사다',
            'layer' => 1, 'layerKo' => '수집',
            'target' => '수집 날짜 필터',
            'how'    => '발행일을 보고 거른다 — 수집 창을 좁힌다',
        ],
        'other' => [
            'detected' => false,
            'label'    => '기타',
            'layer' => 0, 'layerKo' => '미분류',
            'target' => '-',
            'how'    => '이유 메모를 보고 수동으로 판단한다',
        ],
    ];

    // ── 조회 ──────────────────────────────────────────────────────

    /**
     * 최근 N일 되먹임 원장을 읽어 이유별로 집계한다.
     * feedback.py queue() 이식.
     */
    public static function queue(int $days = 30): array
    {
        $rows = self::readRecent($days);
        $counts = [];
        $byCode = [];
        foreach ($rows as $r) {
            $code = (string)($r['reason'] ?? 'other');
            $counts[$code] = ($counts[$code] ?? 0) + 1;
            $byCode[$code][] = $r;
        }

        $out = [];
        foreach ($counts as $code => $n) {
            $meta = self::REJECT_REASONS[$code] ?? ['label' => $code, 'layer' => 0, 'layerKo' => '미분류'];
            $out[] = [
                'code'      => $code,
                'label'     => $meta['label'],
                'layer'     => $meta['layer'],
                'layerKo'   => $meta['layerKo'],
                'detected'  => $meta['detected'] ?? false,
                'target'    => $meta['target'] ?? '-',
                'how'       => $meta['how'] ?? '',
                'n'         => $n,
                'examples'  => array_slice(array_column($byCode[$code], 'title'), 0, 5),
            ];
        }
        usort($out, fn($a, $b) => $b['n'] <=> $a['n']);
        return ['total' => count($rows), 'byReason' => $out, 'days' => $days];
    }

    /**
     * 제외 낱말 후보를 만든다. 자동 적용하지 않는다.
     * feedback.py suggest() 이식.
     */
    public static function suggest(int $days = 30, int $minCount = 3): array
    {
        $rows = self::readRecent($days);
        // not_disaster 인 것의 제목에서 자주 나오는 낱말을 뽑는다
        $titleWords = [];
        foreach ($rows as $r) {
            if (($r['reason'] ?? '') !== 'not_disaster') {
                continue;
            }
            $title = (string)($r['title'] ?? '');
            if (!$title) {
                continue;
            }
            // 두 글자 이상 연속 단어 토큰
            preg_match_all('/[\w가-힣]{2,}/u', $title, $matches);
            foreach ($matches[0] as $w) {
                $titleWords[$w] = ($titleWords[$w] ?? 0) + 1;
            }
        }
        $candidates = [];
        foreach ($titleWords as $w => $cnt) {
            if ($cnt >= $minCount) {
                $candidates[] = ['word' => $w, 'count' => $cnt];
            }
        }
        usort($candidates, fn($a, $b) => $b['count'] <=> $a['count']);
        return ['days' => $days, 'minCount' => $minCount, 'candidates' => array_slice($candidates, 0, 50)];
    }

    // ── 내부 ──────────────────────────────────────────────────────

    private static function readRecent(int $days): array
    {
        try {
            $rows = Store::readJsonl(self::LEDGER_REL);
        } catch (Throwable) {
            return [];
        }
        if (!$rows) {
            return [];
        }
        $kst  = new DateTimeZone(self::KST);
        $cutTs = (new DateTime('now', $kst))->modify("-{$days} days")->getTimestamp();
        return array_filter($rows, function (array $r) use ($cutTs): bool {
            try {
                $ts = (new DateTime((string)($r['ts'] ?? $r['at'] ?? '1970-01-01')))->getTimestamp();
                return $ts >= $cutTs;
            } catch (Throwable) {
                return false;
            }
        });
    }
}
