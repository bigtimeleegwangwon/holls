<?php
// -*- coding: utf-8 -*-
declare(strict_types=1);

/**
 * cron/collect.php — 수집 사이클 크론 진입점.
 *
 * Python 의 threading.Thread 스케줄러를 대체한다.
 * 이 스크립트를 외부 cron 이 MGSCS_COLLECT_MIN 마다 호출한다.
 *
 * 권장 crontab (기본값 60분):
 *   * * * * *  php /var/www/mbridgephp/cron/collect.php >> /var/log/mgscs-cron.log 2>&1
 *
 * 환경변수:
 *   MGSCS_COLLECT       1/true/on/yes → 활성화 (기본 0 = 비활성)
 *   MGSCS_REVIEW        1/true/on/yes → 판정 활성화 (기본 1)
 *   MGSCS_COLLECT_MIN   주기(분, 최소 5, 기본 60)
 *   MGSCS_REPORT_HOUR   일간 보고서 발행 시각(KST 시간, 기본 6)
 *   MGSCS_DATA_DIR      데이터 루트 경로
 *   MGSCS_DEFAULT_ACTOR 감사 로그에 쓸 actor 이름 (기본 'cron')
 *
 * 이중 실행 방지:
 *   signals/collector.lock 에 flock(LOCK_EX|LOCK_NB) 를 건다.
 *   앞 사이클이 아직 돌고 있으면 새 호출은 즉시 종료한다.
 *
 * 주기 준수:
 *   MGSCS_COLLECT_MIN 보다 짧은 시간이 지나지 않았으면
 *   이중 실행 방지 이후에도 종료한다 (cron 간격 < MGSCS_COLLECT_MIN 인 경우 대비).
 */

// ── 부트스트랩 ───────────────────────────────────────────────────────────────

$baseDir = dirname(__DIR__);
require_once $baseDir . '/config.php';
require_once $baseDir . '/lib/Store.php';
require_once $baseDir . '/lib/Collector.php';

// CLI 전용 — 웹에서 직접 호출하면 종료
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('CLI only.');
}

// ── 로깅 헬퍼 ────────────────────────────────────────────────────────────────

function cronLog(string $level, string $msg): void
{
    $ts = (new DateTime('now', new DateTimeZone('Asia/Seoul')))->format('Y-m-d H:i:s KST');
    fwrite(STDERR, "[{$ts}] [{$level}] {$msg}" . PHP_EOL);
}

// ── 이중 실행 방지 ───────────────────────────────────────────────────────────

$lockPath = Store::pathFor(Collector::LOCK_REL);
$lockDir  = dirname($lockPath);
if (!is_dir($lockDir)) {
    mkdir($lockDir, 0755, true);
}

$lockFp = fopen($lockPath, 'c+');
if (!$lockFp) {
    cronLog('ERROR', "락 파일을 열 수 없다: {$lockPath}");
    exit(2);
}

if (!flock($lockFp, LOCK_EX | LOCK_NB)) {
    // 다른 사이클이 실행 중
    cronLog('INFO', '앞 사이클이 아직 실행 중 — 종료');
    fclose($lockFp);
    exit(0);
}

// ── 주기 확인 ────────────────────────────────────────────────────────────────
// crontab 간격이 MGSCS_COLLECT_MIN 보다 촘촘할 때 빠른 연속 실행을 막는다.

$sc        = Collector::schedule();
$intervalS = $sc['intervalMin'] * 60;

$st     = Collector::lastStatus();
$ranAt  = $st['ranAt'] ?? null;
$elapsed = PHP_INT_MAX;

if ($ranAt) {
    try {
        $last    = new DateTime((string) $ranAt);
        $elapsed = time() - $last->getTimestamp();
    } catch (Throwable) {
        $elapsed = PHP_INT_MAX;
    }
}

// 수집이 꺼져 있으면 주기 준수 체크를 건너뛴다 (판정은 항상 시도)
$collectOn = Collector::collectEnabled();
$reviewOn  = Collector::reviewEnabled();

if (!$collectOn && !$reviewOn) {
    cronLog('INFO', 'MGSCS_COLLECT=0 이고 MGSCS_REVIEW=0 — 할 일 없음');
    flock($lockFp, LOCK_UN);
    fclose($lockFp);
    exit(0);
}

if ($collectOn && $elapsed < $intervalS) {
    $wait = $intervalS - $elapsed;
    cronLog('INFO', sprintf('주기 미충족 — %d분 %d초 남음. 종료.', intdiv($wait, 60), $wait % 60));
    flock($lockFp, LOCK_UN);
    fclose($lockFp);
    exit(0);
}

// ── 사이클 실행 ──────────────────────────────────────────────────────────────

$actor   = (string) (getenv('MGSCS_DEFAULT_ACTOR') ?: 'cron');
$catchUp = ($elapsed !== PHP_INT_MAX && $elapsed > $intervalS * 2.5);

cronLog('INFO', sprintf(
    '사이클 시작 — collect=%s review=%s interval=%dm actor=%s catchUp=%s',
    $collectOn ? 'on' : 'off',
    $reviewOn  ? 'on' : 'off',
    $sc['intervalMin'],
    $actor,
    $catchUp ? 'yes' : 'no'
));

$exitCode = 0;

try {
    $result = Collector::runCycle(actor: $actor, catchUp: $catchUp);

    cronLog('INFO', sprintf(
        '사이클 완료 — 판정 %d건 · 후보 %d건 · LLM실패 %d건 · 오늘 %d회차',
        (int) ($result['reviewed']    ?? 0),
        (int) ($result['candidates']  ?? 0),
        (int) ($result['llmFailed']   ?? 0),
        (int) ($result['cyclesToday'] ?? 1)
    ));

    if (!empty($result['llmWhy'])) {
        cronLog('WARN', '판정 LLM 오류: ' . $result['llmWhy']);
    }

} catch (Throwable $ex) {
    cronLog('ERROR', get_class($ex) . ': ' . $ex->getMessage());
    cronLog('ERROR', $ex->getTraceAsString());
    $exitCode = 1;
}

// ── 락 해제 ──────────────────────────────────────────────────────────────────

flock($lockFp, LOCK_UN);
fclose($lockFp);

exit($exitCode);
