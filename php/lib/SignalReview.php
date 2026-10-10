<?php
// -*- coding: utf-8 -*-
/**
 * SignalReview — 신호 판정 엔진.
 * signal_review.py 완전 이식본.
 *
 * LLM 추출 → 게이트 → 사건 원장 등록.
 * LLM 이 꺼져 있거나 닿지 않으면 판정을 건너뛴다 — 모르는 것을 안다고 하지 않는다.
 */
declare(strict_types=1);

require_once __DIR__ . '/Store.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/GeoRef.php';

final class SignalReview
{
    private const REVIEW_REL = 'signals/reviewed.json';

    // LLM 프롬프트 — signal_review.py PROMPT 이식
    private const PROMPT = <<<'TMPL'
다음 기사에서 두 가지만 뽑아라. 판단하지 말고 원문에서 찾아라.

1) materials — 아래 8개 중 기사가 직접 말하는 소재군만. 없으면 빈 배열.
   Photo, Chemical, Slurry, Pad, Gas, Precursor, Wafer, Target
2) evidence — 실제로 **일어난** 공급 차질을 말하는 원문 문장 하나.
   "우려·전망·가능성" 같은 추측이면 빈 문자열.
   기사에 그런 문장이 없으면 빈 문자열.
3) country — 사건이 난 나라의 ISO 2글자 코드(KR JP CN TW US 등). 모르면 빈 문자열.
4) areas — 기사에 나오는 행정구역 이름(도/현/시). 원문 표기 그대로. 없으면 빈 배열.

JSON 으로만 답하라: {"materials":[],"evidence":"","country":"","areas":[]}

기사 제목: %TITLE%
기사 요약: %SUMMARY%
TMPL;

    // ── KST 유틸 ──────────────────────────────────────────────────

    private static function nowKst(): string
    {
        return (new DateTime('now', new DateTimeZone('Asia/Seoul')))->format('Y-m-d\TH:i:sP');
    }

    // ── 신호 id → 사건 id ─────────────────────────────────────────

    /**
     * 신호 id 전체를 sha1 로 접는다. _incident_id() 이식.
     */
    private static function incidentId(string $sid): string
    {
        return 'SIG-' . substr(sha1($sid), 0, 16);
    }

    /**
     * 옛 방식 id. 이미 원장에 있는 사건을 찾을 때만. _legacy_incident_id() 이식.
     */
    private static function legacyIncidentId(string $sid): string
    {
        $clean = preg_replace('/[^A-Za-z0-9]+/', '', $sid) ?? '';
        return 'SIG-' . substr($clean, -24);
    }

    /**
     * 이 신호의 사건 id. 이미 등록된 사건은 옛 id 를 그대로 쓴다.
     */
    private static function incidentIdFor(string $sid, array $rows): string
    {
        $legacy = self::legacyIncidentId($sid);
        foreach ($rows as $r) {
            if ((string)($r['id'] ?? '') !== $legacy) {
                continue;
            }
            $had = (string)($r['signalId'] ?? $r['sourceUrl'] ?? '');
            if (!$had || $had === $sid) {
                return $legacy;
            }
            break;
        }
        return self::incidentId($sid);
    }

    // ── LLM 추출 ──────────────────────────────────────────────────

    /**
     * 사내 LLM 으로 소재군·증거·국가·지역을 뽑는다.
     * _extract() 이식.
     *
     * @param array      $sig    신호 레코드
     * @param array      $cfg    LLM 설정 (alert_config.llm_config 결과)
     * @param array|null &$stats 실패 카운터 참조
     * @return array|null  {materials, evidence, country, areas} 또는 null
     */
    public static function extract(array $sig, array $cfg, ?array &$stats = null): ?array
    {
        $fail = function (string $why) use (&$stats) {
            if ($stats !== null) {
                $stats['failed'] = ($stats['failed'] ?? 0) + 1;
                if (empty($stats['why'])) {
                    $stats['why'] = $why;
                }
            }
            return null;
        };

        $title = $sig['titleKo'] ?? $sig['title'] ?? '';
        $summary = substr($sig['summary'] ?? '', 0, 1200);
        $prompt = str_replace(['%TITLE%', '%SUMMARY%'], [$title, $summary], self::PROMPT);

        // LLM 호출 — 재시도 없음(retries=0). 타임아웃이 긴 요청이 겹치면 앱이 선다.
        try {
            $raw = self::callLlm($cfg, $prompt, maxTokens: 400, temperature: 0.0);
        } catch (Throwable $ex) {
            return $fail(get_class($ex) . ': ' . substr($ex->getMessage(), 0, 160));
        }

        if ($raw === null) {
            return $fail('LLM 호출 실패');
        }

        // JSON 추출
        if (!preg_match('/\{.*\}/su', $raw, $m)) {
            return $fail('응답에 JSON 이 없다');
        }
        $d = json_decode($m[0], true);
        if (!is_array($d)) {
            return $fail('JSON 을 못 읽었다');
        }

        $materials = array_slice(array_map('strval', (array)($d['materials'] ?? [])), 0, 8);
        $evidence  = substr((string)($d['evidence'] ?? ''), 0, 400);
        $country   = strtoupper(substr(trim((string)($d['country'] ?? '')), 0, 2));
        $areas     = array_slice(array_map('strval', (array)($d['areas'] ?? [])), 0, 6);

        return compact('materials', 'evidence', 'country', 'areas');
    }

    /**
     * 사내 LLM 을 호출한다.
     * llm.call() 의 PHP 대응 — HTTP POST.
     *
     * @return string|null
     */
    private static function callLlm(array $cfg, string $prompt, int $maxTokens = 400, float $temperature = 0.0): ?string
    {
        $format  = $cfg['format'] ?? 'openai';
        $base    = rtrim($cfg['base'] ?? '', '/');
        $model   = $cfg['model'] ?? '';
        $apiKey  = $cfg['key'] ?? '';
        $timeout = (int)($cfg['timeout'] ?? 60);

        if (!$base) {
            return null;
        }

        // OpenAI 호환 형식
        $url  = $base . '/chat/completions';
        $body = json_encode([
            'model' => $model,
            'messages' => [['role' => 'user', 'content' => $prompt]],
            'max_tokens' => $maxTokens,
            'temperature' => $temperature,
        ], JSON_UNESCAPED_UNICODE);

        $ch = curl_init($url);
        if ($ch === false) {
            return null;
        }
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_HTTPHEADER => array_filter([
                'Content-Type: application/json',
                $apiKey ? "Authorization: Bearer {$apiKey}" : null,
            ]),
        ]);
        $resp = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($resp === false || $code < 200 || $code >= 300) {
            return null;
        }
        $data = json_decode((string)$resp, true);
        return $data['choices'][0]['message']['content'] ?? null;
    }

    // ── BP 매칭 ───────────────────────────────────────────────────

    /**
     * 지역 이름으로 걸리는 BP 수. _bp_match() 이식.
     * 못 가리면 null (0이 아님 — 0은 '없음', null은 '모름').
     */
    public static function bpMatch(string $country, array $areas, array $rows): ?int
    {
        if (!$country || !$areas) {
            return null;
        }
        $wanted = GeoRef::resolveAdmin1All($country, ...$areas);
        if (!$wanted) {
            return null;
        }
        $wantedSet = array_flip($wanted);
        $bps = [];
        foreach ($rows as $r) {
            if (strtoupper((string)($r['country'] ?? '')) !== strtoupper($country)) {
                continue;
            }
            $admin = $r['admin1'] ?? $r['admin1Ko'] ?? '';
            if (!$admin) {
                continue;
            }
            // admin1 이 wanted 에 속하면 카운트
            if (isset($wantedSet[$admin])) {
                $bps[$r['bpName'] ?? ''] = true;
            }
        }
        return count($bps);
    }

    // ── 등급 ──────────────────────────────────────────────────────

    /**
     * 재난 등급 — high/medium/low. grade() 이식.
     *
     * @param array    $g   gates.evaluate() 결과
     * @param array    $sig 신호 레코드
     * @param int|null $bp  BP 매칭 수 (null = 모름)
     */
    public static function grade(array $g, array $sig, ?int $bp): string
    {
        $blockedBy = (array)($g['blockedBy'] ?? []);
        $strong = !in_array('G3', $blockedBy, true)
               && !in_array('G4', $blockedBy, true)
               && !in_array('G5', $blockedBy, true);
        $ours = $bp !== null && $bp > 0;

        if (($g['triage'] ?? '') === 'review' && $ours) {
            return 'high';
        }
        if ($bp === null) {
            return 'medium';
        }
        if ($strong || $ours) {
            return 'medium';
        }
        return 'low';
    }

    // ── 게이트 평가 (간이) ────────────────────────────────────────

    /**
     * gates.evaluate() 의 PHP 대응 — 5개 게이트를 순서대로 평가.
     * Python 원본의 gates.py 논리를 이식.
     * 실제 배포에서는 별도 Gates 클래스를 두는 것이 권장됨.
     *
     * @return array  {triage, blockedBy[], gateSummary}
     */
    public static function evaluateGates(array $event): array
    {
        $blockedBy = [];
        $summary   = [];

        $materials = (array)($event['affectedMaterials'] ?? []);
        $evidence  = (string)($event['disruptionEvidence'] ?? '');
        $official  = (bool)($event['official'] ?? false);
        $corrob    = (int)($event['corroboration'] ?? 0);
        $bpCount   = $event['bpMatchCount'] ?? null;

        // G2: 소재군
        if (!$materials) {
            $blockedBy[] = 'G2';
            $summary['G2'] = 'fail:소재군 없음';
        } else {
            $summary['G2'] = 'pass:' . implode(',', $materials);
        }

        // G4: 실제 중단 정황
        if (!$evidence) {
            $blockedBy[] = 'G4';
            $summary['G4'] = 'fail:중단 정황 없음';
        } else {
            $summary['G4'] = 'pass:evidence';
        }

        // G3: 공신력 (official 또는 corroboration >= 1)
        if (!$official && $corrob < 1) {
            $blockedBy[] = 'G3';
            $summary['G3'] = 'fail:미공식·미확인';
        } else {
            $summary['G3'] = 'pass';
        }

        // G1: 지리 근접 (bpMatchCount 기반)
        if ($bpCount === null) {
            $summary['G1'] = 'unknown:BP판정미완';
        } elseif ($bpCount === 0) {
            $blockedBy[] = 'G1';
            $summary['G1'] = 'fail:권역내 BP없음';
        } else {
            $summary['G1'] = "pass:{$bpCount}사";
        }

        // G5: BP 매칭 (G1 과 연동)
        if ($bpCount === null) {
            $summary['G5'] = 'unknown';
        } elseif ($bpCount === 0) {
            $blockedBy[] = 'G5';
            $summary['G5'] = 'fail';
        } else {
            $summary['G5'] = 'pass';
        }

        $triage = $blockedBy ? 'drop' : 'review';

        return [
            'triage'     => $triage,
            'blockedBy'  => array_values(array_unique($blockedBy)),
            'gateSummary' => $summary,
        ];
    }

    // ── 판정 메인 ─────────────────────────────────────────────────

    /**
     * 쌓인 신호를 판정한다. review() 이식.
     *
     * @return array  {ok, seen, pending, reviewed, candidates, llmFailed, llmWhy, llmDown}
     */
    public static function review(int $days = 1, int $limit = 40, string $actor = 'server'): array
    {
        // LLM 설정 확인
        $values = Store::readJson('revision/alert_config.json', []) ?? [];
        if ((string)($values['LLM_ENABLED'] ?? '') !== 'true') {
            return [
                'ok' => false,
                'why' => 'LLM 이 꺼져 있다 — 판정을 건너뛴다',
                'seen' => 0, 'reviewed' => 0, 'candidates' => [],
                'llmFailed' => 0, 'llmWhy' => '',
            ];
        }

        $cfg = self::llmConfig($values);

        // 신호 읽기
        $sigs = self::readSignals($days);
        $done = Store::readJson(self::REVIEW_REL, []) ?? [];
        if (!is_array($done)) {
            $done = [];
        }
        $rows = Store::readJson('build/bp_resolved.json', []) ?? [];
        $rows = array_filter((array)$rows, fn($r) => ($r['active'] ?? true) !== false);
        $rows = array_values($rows);

        $todo = array_slice(
            array_filter($sigs, fn($s) => !isset($done[(string)($s['id'] ?? '')])),
            0, $limit
        );

        $out = [];
        $registered = [];
        $stats = ['tried' => 0, 'failed' => 0, 'why' => ''];

        foreach ($todo as $s) {
            $sid = (string)($s['id'] ?? '');
            $stats['tried']++;

            $ex = self::extract($s, $cfg, $stats);
            if ($ex === null) {
                continue;
            }

            $country = strtoupper((string)($s['country'] ?? $ex['country'] ?? ''));
            $bp = self::bpMatch($country, $ex['areas'], $rows);

            $g = self::evaluateGates([
                'affectedMaterials' => $ex['materials'],
                'disruptionEvidence' => $ex['evidence'],
                'official' => (bool)($s['official'] ?? false),
                'corroboration' => (int)($s['corroboration'] ?? 0),
                'bpMatchCount' => $bp,
            ]);

            $grade = self::grade($g, $s, $bp);
            $now = self::nowKst();

            $rec = [
                'id' => $sid,
                'title' => $s['titleKo'] ?? $s['title'] ?? $sid,
                'triage' => $g['triage'],
                'grade' => $grade,
                'blockedBy' => $g['blockedBy'],
                'gateSummary' => $g['gateSummary'],
                'materials' => $ex['materials'],
                'evidence' => $ex['evidence'],
                'country' => $country,
                'areas' => $ex['areas'],
                'bpMatchCount' => $bp,
                'at' => $now,
            ];

            $done[$sid] = [
                'triage' => $rec['triage'],
                'grade' => $grade,
                'decision' => null,
                'judgedAt' => $now,
                'title' => $rec['title'],
                'url' => $s['url'] ?? $s['link'] ?? '',
                'materials' => $rec['materials'],
                'areas' => $rec['areas'],
                'country' => $country,
                'bpMatchCount' => $bp,
                'gateSummary' => $g['gateSummary'],
                'source' => $s['source'] ?? $s['ingestSource'] ?? '',
            ];

            $out[] = $rec;
            if ($g['triage'] === 'review') {
                $registered[] = $rec;
            }
        }

        // 판정 통과분을 사건 원장에 등록
        if ($registered) {
            self::registerIncidents($registered, $rows, $actor);
        }

        // read-modify-write: 사람이 찍은 y/n 은 건드리지 않는다
        $lockFile = Store::sandbox(self::REVIEW_REL) . '.lock';
        $lf = @fopen($lockFile, 'c');
        if ($lf) {
            flock($lf, LOCK_EX);
        }
        $cur = Store::readJson(self::REVIEW_REL, []) ?? [];
        if (!is_array($cur)) {
            $cur = [];
        }
        foreach ($done as $sid => $v) {
            $have = $cur[$sid] ?? null;
            if (is_array($have) && !empty($have['decision'])) {
                continue; // 사람이 이미 찍었다
            }
            $cur[$sid] = $v;
        }
        try {
            Store::writeJson(self::REVIEW_REL, $cur, false);
        } catch (Throwable) {}
        if ($lf) {
            flock($lf, LOCK_UN);
            fclose($lf);
        }

        $llmDown = $stats['tried'] > 0 && $stats['failed'] === $stats['tried'];

        if ($out) {
            Store::appendAudit('signal.review', count($out) . '건 판정 · 점검 후보 ' . count($registered) . '건', (string)$actor);
        }

        return [
            'ok' => true,
            'seen' => count($sigs),
            'pending' => count($sigs) - count($done),
            'reviewed' => count($out),
            'candidates' => $registered,
            'results' => $out,
            'llmFailed' => $stats['failed'],
            'llmWhy' => $stats['why'],
            'llmDown' => $llmDown,
        ];
    }

    /**
     * 4층 결정. decide() 이식.
     * y/n 과 이유를 reviewed.json 에 기록하고 feedback.jsonl 에 남긴다.
     *
     * @throws ValueError  검증 실패
     */
    public static function decide(
        mixed $sid,
        mixed $decision,
        string $reason = '',
        string $note = '',
        string $actor = 'unknown'
    ): array {
        $sid = (string)($sid ?? '');
        $decision = (string)($decision ?? '');

        if (!$sid) {
            throw new ValueError('신호 id 가 필요합니다');
        }
        if (!in_array($decision, ['y', 'n', 'misdrop'], true)) {
            throw new ValueError('decision 은 y / n / misdrop 이어야 합니다');
        }
        if ($decision === 'n' && !$reason) {
            throw new ValueError('n 결정에는 이유(reason)가 필요합니다');
        }

        $lockFile = Store::sandbox(self::REVIEW_REL) . '.lock';
        $lf = @fopen($lockFile, 'c');
        if ($lf) {
            flock($lf, LOCK_EX);
        }

        $cur = Store::readJson(self::REVIEW_REL, []) ?? [];
        if (!is_array($cur)) {
            $cur = [];
        }
        if (!isset($cur[$sid])) {
            if ($lf) { flock($lf, LOCK_UN); fclose($lf); }
            throw new ValueError("판정된 신호가 아닙니다: {$sid}");
        }

        $now = self::nowKst();
        $cur[$sid]['decision'] = $decision;
        $cur[$sid]['decidedAt'] = $now;
        $cur[$sid]['decidedBy'] = $actor;
        if ($reason) {
            $cur[$sid]['reason'] = $reason;
        }
        if ($note) {
            $cur[$sid]['note'] = $note;
        }

        try {
            Store::writeJson(self::REVIEW_REL, $cur, false);
        } catch (Throwable) {}

        if ($lf) {
            flock($lf, LOCK_UN);
            fclose($lf);
        }

        // feedback.jsonl 기록
        $row = [
            'at' => $now,
            'id' => $sid,
            'decision' => $decision,
            'reason' => $reason,
            'note' => substr($note, 0, 400),
            'title' => substr((string)($cur[$sid]['title'] ?? ''), 0, 200),
            'source' => (string)($cur[$sid]['source'] ?? ''),
            'grade' => (string)($cur[$sid]['grade'] ?? ''),
            'by' => $actor,
        ];
        try {
            Store::appendJsonl('revision/feedback.jsonl', $row);
        } catch (Throwable) {}

        Store::appendAudit('signal.decide', "{$sid} → {$decision}" . ($reason ? " ({$reason})" : ''), (string)$actor);

        return $row;
    }

    /**
     * 4단계 결정 대기 목록. pending_decisions() 이식.
     *
     * @return array  {items, count}
     */
    public static function pendingDecisions(int $limit = 60): array
    {
        $cur = Store::readJson(self::REVIEW_REL, []) ?? [];
        if (!is_array($cur)) {
            return ['items' => [], 'count' => 0];
        }
        $items = [];
        foreach ($cur as $sid => $v) {
            if (!is_array($v)) {
                continue;
            }
            if (($v['grade'] ?? '') !== 'high') {
                continue;
            }
            if (!empty($v['decision'])) {
                continue;
            }
            $v['id'] = $sid;
            $items[] = $v;
        }
        // 가장 오래된 것부터
        usort($items, fn($a, $b) => strcmp($a['judgedAt'] ?? '', $b['judgedAt'] ?? ''));
        return [
            'items' => array_slice($items, 0, $limit),
            'count' => count($items),
        ];
    }

    /**
     * 감지량 피라미드. funnel() 이식.
     *
     * @return array  {collected, dropped, kept, reviewed, grade, decisions}
     */
    public static function funnel(int $hours = 24): array
    {
        $sigs = self::readSignals(2);
        $dropped = self::readDropped(2);

        $tz = new DateTimeZone('Asia/Seoul');
        $cut = new DateTime('now', $tz);
        $cut->modify("-{$hours} hours");
        $cutTs = $cut->getTimestamp();

        $recentSig = array_filter($sigs, fn($r) => self::tsOf($r) >= $cutTs);
        $recentDrop = array_filter($dropped, fn($r) => self::tsOf($r) >= $cutTs);

        $cur = Store::readJson(self::REVIEW_REL, []) ?? [];
        if (!is_array($cur)) {
            $cur = [];
        }
        $ids = array_map(fn($s) => (string)($s['id'] ?? ''), array_values($recentSig));

        $grade = ['high' => 0, 'medium' => 0, 'low' => 0];
        $judged = $yes = $no = $misfire = $benign = 0;
        $oldest = 0.0;
        $nowTs  = time();
        foreach ($ids as $sid) {
            $v = $cur[$sid] ?? null;
            if (!is_array($v) || !isset($grade[$v['grade'] ?? ''])) {
                continue;
            }
            $grade[$v['grade']]++;
            $judged++;
            // y/n 은 3층(우리 것)에 올라온 것만 센다 — 층이 포개져야 피라미드다.
            if (($v['grade'] ?? '') === 'high') {
                if (($v['decision'] ?? '') === 'y') {
                    $yes++;
                } elseif (($v['decision'] ?? '') === 'n') {
                    $no++;
                    $isMis = class_exists('Feedback') && method_exists('Feedback', 'isMisfire')
                        ? (bool)Feedback::isMisfire($v['reason'] ?? null) : false;
                    $isMis ? $misfire++ : $benign++;
                } else {
                    try {
                        $age = ($nowTs - (new DateTime((string)($v['judgedAt'] ?? '')))->getTimestamp()) / 3600;
                        $oldest = max($oldest, $age);
                    } catch (Throwable) {}
                }
            }
        }
        // 적중률 — 결정한 것 중에서만. 대기 중인 것을 맞은 것으로 세지 않는다.
        $decided   = $yes + $no;
        $accuracy  = $decided ? round(($yes + $benign) / $decided, 3) : null;
        $precision = $decided ? round($yes / $decided, 3) : null;
        $latest = static function (array $rows, string ...$keys): string {
            $best = '';
            foreach ($rows as $r) {
                foreach ($keys as $k) {
                    $v = (string)($r[$k] ?? '');
                    if ($v !== '' && strcmp($v, $best) > 0) {
                        $best = $v;
                        break;
                    }
                }
            }
            return $best;
        };

        return [
            // signal_review.py funnel() 과 같은 키 — 화면(home.php)이 이 이름으로 읽는다
            'hours'     => $hours,
            'collected' => count($recentSig) + count($recentDrop),   // 1층 · 훑어 온 것
            'stage1'    => count($recentSig),                        // 2층 · 재난·재해로 볼 것
            'dropped1'  => count($recentDrop),
            'judged'    => $judged,
            'pending'   => count($recentSig) - $judged,              // 아직 판정 전
            'stage2'    => $grade['high'],                           // 3층 · 우리와 관련 있는 것
            'grades'    => $grade,
            'decided'   => $decided, 'yes' => $yes, 'no' => $no,
            'accuracy'  => $accuracy,
            'precision' => $precision,
            'misfire'   => $misfire,
            'benign'    => $benign,
            'waiting'   => $grade['high'] - $decided,                // 4층 · 대기 — 감추면 "아무 일 없음"과 같아 보인다
            'oldestWaitingH' => $oldest ? round($oldest, 1) : 0,
            'lastSignalAt'   => $latest(array_values($recentSig), 'ingestedAt', 'pubDate'),
            'lastReviewAt'   => $latest(array_values(is_array($cur) ? $cur : []), 'judgedAt', 'at'),
            // 이전 PHP 이식판 호환 키
            'dropped'   => count($recentDrop),
            'kept'      => count($recentSig),
            'reviewed'  => $judged,
            'grade'     => $grade,
            'decisions' => ['yes' => $yes, 'no' => $no, 'pending' => $grade['high'] - $decided],
        ];
    }

    /**
     * 범주별 판정 신호. by_category() 이식.
     */
    public static function byCategory(int $days = 7): array
    {
        $sigs = self::readSignals($days);
        $cur  = Store::readJson(self::REVIEW_REL, []) ?? [];
        if (!is_array($cur)) {
            $cur = [];
        }

        $by = [];
        foreach ($sigs as $s) {
            $sid = (string)($s['id'] ?? '');
            $v = $cur[$sid] ?? null;
            if (!is_array($v) || !isset($v['grade'])) {
                continue;
            }
            $cat = (string)($s['category'] ?? $s['type'] ?? '미분류');
            if (!isset($by[$cat])) {
                $by[$cat] = ['category' => $cat, 'high' => 0, 'medium' => 0, 'low' => 0, 'total' => 0];
            }
            $g = $v['grade'];
            if (isset($by[$cat][$g])) {
                $by[$cat][$g]++;
            }
            $by[$cat]['total']++;
        }

        usort($by, fn($a, $b) => $b['total'] - $a['total']);
        return ['days' => $days, 'categories' => array_values($by)];
    }

    /**
     * 버린 것 표본. sample_dropped() 이식.
     *
     * @return array  {days, n, total, samples}
     */
    public static function sampleDropped(int $days = 3, int $n = 20, int $seed = 0): array
    {
        $dropped = self::readDropped($days);
        $total = count($dropped);
        if (!$total) {
            return ['days' => $days, 'n' => $n, 'total' => 0, 'samples' => []];
        }
        // 결정론적 샘플링 (seed 기반)
        mt_srand($seed ?: 42);
        shuffle($dropped);
        mt_srand(); // 리셋
        return [
            'days' => $days,
            'n' => $n,
            'total' => $total,
            'samples' => array_slice($dropped, 0, $n),
        ];
    }

    /**
     * 잘못 버렸다 — 오탐(false negative) 기록. mark_misdrop() 이식.
     */
    public static function markMisdrop(mixed $sid, string $title = '', string $note = '', string $actor = 'unknown'): array
    {
        $sid = (string)($sid ?? '');
        if (!$sid) {
            throw new ValueError('신호 id 가 필요합니다');
        }
        $row = [
            'at' => self::nowKst(),
            'id' => $sid,
            'decision' => 'misdrop',
            'reason' => '',
            'note' => substr($note, 0, 400),
            'title' => substr($title, 0, 200),
            'source' => '',
            'grade' => '',
            'by' => $actor,
        ];
        Store::appendJsonl('revision/feedback.jsonl', $row);
        Store::appendAudit('signal.misdrop', (string)$sid, (string)$actor);
        return $row;
    }

    // ── 사건 원장 등록 ────────────────────────────────────────────

    /**
     * 판정 통과 신호를 사건 원장에 등록. _register() + _build_incidents() 이식.
     */
    private static function registerIncidents(array $recs, array $rows, string $actor): void
    {
        $relPath  = 'revision/incidents.json';
        $lockFile = Store::sandbox($relPath) . '.lock';
        $lf = @fopen($lockFile, 'c');
        if ($lf) {
            flock($lf, LOCK_EX);
        }

        $have = Store::readJson($relPath, []) ?? [];
        if (!is_array($have)) {
            $have = [];
        }
        $seen = array_flip(array_map(fn($x) => (string)($x['id'] ?? ''), $have));
        $now  = self::nowKst();

        $add = [];
        foreach ($recs as $r) {
            $sid = (string)($r['id'] ?? '');
            $iid = self::incidentIdFor($sid, $have);
            if (isset($seen[$iid])) {
                continue;
            }
            $seen[$iid] = true;
            $add[] = [
                'id' => $iid,
                'signalId' => $sid,
                'title' => $r['title'] ?? $iid,
                'country' => $r['country'] ?? '',
                'admin1' => ($r['areas'] ?? [''])[0] ?? '',
                'adminAreas' => $r['areas'] ?? [],
                'occurredAt' => $r['at'] ?? $now,
                'severity' => $r['grade'] ?? 'medium',
                'official' => false,
                'certainty' => 'confirmed',
                'status' => 'open',
                'decision' => null,
                'materialGroups' => $r['materials'] ?? [],
                'bpCount' => $r['bpMatchCount'] ?? null,
                'siteCount' => null,
                'source' => '서버 판정(브라우저 수집 신호)',
                'gateSummary' => $r['gateSummary'] ?? null,
                'evidence' => $r['evidence'] ?? null,
                'sourceUrl' => str_starts_with((string)$sid, 'http') ? $sid : '',
                'importedAt' => $now,
                'importedBy' => $actor,
            ];
        }

        if ($add) {
            try {
                Store::writeJson($relPath, array_merge($have, $add));
            } catch (Throwable) {}
            Store::appendAudit('signal.register', '신호 판정 → 사건 ' . count($add) . '건 등록', (string)$actor);
        }

        if ($lf) {
            flock($lf, LOCK_UN);
            fclose($lf);
        }
    }

    // ── 신호 읽기 헬퍼 ────────────────────────────────────────────

    /**
     * 최근 days 일치의 신호를 읽는다. collector.read_signals() 이식.
     *
     * @return array[]
     */
    public static function readSignals(int $days = 7): array
    {
        $tz  = new DateTimeZone('Asia/Seoul');
        $now = new DateTime('now', $tz);
        $out = [];
        for ($d = 0; $d < $days; $d++) {
            $day = clone $now;
            $day->modify("-{$d} days");
            $rel = 'signals/' . $day->format('Ymd') . '.json';
            try {
                $arr = Store::readJson($rel, []);
                if (is_array($arr)) {
                    $out = array_merge($out, $arr);
                }
            } catch (Throwable) {}
        }
        return $out;
    }

    /**
     * 버린 신호 읽기. signal_filter.read_dropped() 이식.
     *
     * @return array[]
     */
    public static function readDropped(int $days = 2): array
    {
        $tz  = new DateTimeZone('Asia/Seoul');
        $now = new DateTime('now', $tz);
        $out = [];
        for ($d = 0; $d < $days; $d++) {
            $day = clone $now;
            $day->modify("-{$d} days");
            $rel = 'signals/dropped_' . $day->format('Ymd') . '.json';
            try {
                $arr = Store::readJson($rel, []);
                if (is_array($arr)) {
                    $out = array_merge($out, $arr);
                }
            } catch (Throwable) {}
        }
        return $out;
    }

    // ── LLM 설정 ──────────────────────────────────────────────────

    /**
     * alert_config.llm_config() 이식.
     */
    public static function llmConfig(array $values): array
    {
        return [
            'format'  => $values['LLM_FORMAT'] ?? 'openai',
            'base'    => $values['LLM_BASE'] ?? '',
            'model'   => $values['LLM_MODEL'] ?? '',
            'key'     => $values['LLM_KEY'] ?? '',
            'timeout' => (int)($values['LLM_TIMEOUT'] ?? 60),
        ];
    }

    // ── 내부 헬퍼 ─────────────────────────────────────────────────

    private static function tsOf(array $r): int
    {
        $t = $r['ingestedAt'] ?? $r['droppedAt'] ?? $r['at'] ?? '';
        if (!$t) {
            return 0;
        }
        try {
            return (new DateTime((string)$t))->getTimestamp();
        } catch (Throwable) {
            return 0;
        }
    }
}
