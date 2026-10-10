<?php
// -*- coding: utf-8 -*-
/**
 * index.php — 진입은 이 파일 하나다.
 *
 * 모든 URL 은 여기로 온다(.htaccess). 이 파일이 하는 일은 넷뿐이다.
 *   ① 부트스트랩   config → lib → routes
 *   ② 게이트       비로그인이 볼 수 있는 것은 $PAGES/$APIS 표의 public 칸이 정한다
 *   ③ 라우팅표     화면 13 · API 60 — 링크는 전부 이 표 한 곳에서 관리한다
 *   ④ 처리기 호출  Pages::xxx() / Api::xxx()
 *
 * 폴더
 *   lib/     도메인 모듈(정적 클래스)    routes/  처리기(Pages · Api)
 *   pages/   화면 템플릿                static/  정적 파일
 *   data/    데이터(웹 차단)            cron/    수집 사이클(CLI)
 *
 * 구조 원칙 — routes.py 의 것을 그대로 가져온다.
 *   "막을 칸을 세지 않고 열 칸만 센다." 표에 public 이 없으면 잠긴다.
 *   새 화면을 만들 때 표에 넣는 걸 잊으면 공개되는 게 아니라 잠기도록.
 */
declare(strict_types=1);

// ── ① 부트스트랩 ─────────────────────────────────────────────────
mb_internal_encoding('UTF-8');
date_default_timezone_set('Asia/Seoul');

$DEBUG = in_array(strtolower((string)getenv('MGSCS_DEBUG')), ['1', 'true', 'on', 'yes'], true);
error_reporting(E_ALL);
ini_set('display_errors', $DEBUG ? '1' : '0');
ini_set('log_errors', '1');

require_once __DIR__ . '/config.php';
foreach (glob(__DIR__ . '/lib/*.php') ?: [] as $f) {
    require_once $f;
}
require_once __DIR__ . '/routes/pages.php';
require_once __DIR__ . '/routes/api.php';

// 경고도 놓치지 않는다 — 디버그 모드에서는 예외로 올려서 화면에 보이게 한다.
set_error_handler(static function (int $no, string $str, string $file, int $line) use ($DEBUG): bool {
    if (!(error_reporting() & $no)) {
        return false;
    }
    if ($DEBUG && in_array($no, [E_WARNING, E_USER_WARNING, E_RECOVERABLE_ERROR], true)) {
        throw new ErrorException($str, 0, $no, $file, $line);
    }
    error_log(sprintf('[mbridgephp] %s in %s:%d', $str, $file, $line));
    return true;
});

// ── ③ 라우팅표 ───────────────────────────────────────────────────
// 화면:  경로 => [처리기, 공개여부, 메뉴 이름, 설명]
// 화면 라우트는 GET (login 만 GET+POST).
$PAGES = [
    '/'          => ['hub',       true,  '허브',        '최초 진입 — 화면·API 목록과 서버 상태'],
    '/home'      => ['home',      true,  '홈',          '운영 펄스 · 감지량 피라미드'],
    '/dashboard' => ['dashboard', true,  'Risk 현황',   '지도 · 사건 · 신호 (비로그인은 연락처 제외)'],
    '/inspect'   => ['inspect',   true,  '점검 현황',   '사건 → 대상 선별 → 메일 초안 → 회신 기록'],
    '/system'    => ['system',    true,  '판정 기준',   '왜 이것이 걸렸나 — 데이터 없는 문서'],
    '/bp'        => ['bp',        false, 'BP 기준 정보', '명부 · 연락처 · 소재군 (관리자)'],
    '/revision'  => ['revision',  false, '등록·수정',   'append-only 원장 · 재생성 (관리자)'],
    '/review'    => ['review',    false, '감지 검토',   '4단계 결정 · 되먹임 · 버린 것 표본 (관리자)'],
    '/settings'  => ['settings',  false, '시스템 설정', '경보 수신자 · LLM · 수집 (관리자)'],
    '/login'     => ['login',     true,  '로그인',      '최초 1회는 비밀번호 설정 화면'],
    '/logout'    => ['logout',    true,  '나가기',      ''],
    '/roster'    => ['roster',    false, '',            '옛 주소 → /bp'],
    '/hotline'   => ['hotline',   false, '',            '옛 주소 → /bp?tab=hot'],
];

// API:  "METHOD 경로" => [Api 메서드, 공개여부, 설명]
// 경로의 {iid} 는 한 조각(슬래시 포함 가능)을 받는다.
$APIS = [
    // 보는 것 — 재난 대응은 소재 조직 밖에서도 봐야 한다 (routes.py PUBLIC_ENDPOINTS)
    'GET /api/resolved'                 => ['apiResolved',           true,  '명부·좌표 (비로그인은 연락처 제외)'],
    'GET /api/incidents'                => ['apiIncidents',          true,  '사건 원장'],
    'GET /api/signals'                  => ['apiSignals',            true,  '수집 신호'],
    'GET /api/signals/funnel'           => ['apiSignalsFunnel',      true,  '감지량 — 건수만'],
    'GET /api/signals/judged'           => ['apiSignalsJudged',      true,  '판정된 재난(범주별)'],
    'GET /api/signals/reasons'          => ['apiSignalReasons',      true,  '제외 사유 낱말표'],
    'GET /api/collect/status'           => ['apiCollectStatus',      true,  '감시가 살아 있나'],
    'GET /api/alerts'                   => ['apiAlerts',             true,  '미확인 이상 (보기만)'],
    'GET /api/alerts/history'           => ['apiAlertsHistory',      true,  '경보 이력'],
    'GET /api/report/daily'             => ['apiReportDaily',        true,  '일간 보고'],
    'GET /api/errors'                   => ['apiErrors',             true,  '오류 번호표'],
    'GET /api/disasters'                => ['apiDisasters',          true,  '재난 원장 — 이름·출처만'],
    'GET /api/incidents/{iid}/targets'  => ['apiIncidentTargets',    true,  '사건별 대상 BP·진행 상태 (연락처 없음)'],

    // 관리자 — 표에 없으면 잠긴다
    'POST /api/settings'                => ['apiSettings',           false, '경보 설정 저장'],
    'GET /api/export'                   => ['apiExport',             false, '명부 내보내기'],
    'GET /api/report'                   => ['apiReport',             false, 'BUILD_REPORT'],
    'POST /api/hotline/preview'         => ['apiHotlinePreview',     false, '연락처 일괄 등록 미리보기'],
    'GET /api/export/hotline.csv'       => ['apiExportHotline',      false, '연락처 양식 CSV'],
    'GET /api/export/sources.json'      => ['apiExportSources',      false, '출처 원장'],
    'GET /api/export/material_survey.csv' => ['apiMaterialSurvey',   false, '소재 조사 양식'],
    'GET /api/hotline/gaps'             => ['apiHotlineGaps',        false, '연락처 공백'],
    'POST /api/hotline/commit'          => ['apiHotlineCommit',      false, '연락처 일괄 반영'],
    'POST /api/incidents/import'        => ['apiIncidentsImport',    false, '사건 가져오기'],
    'POST /api/incidents/status'        => ['apiIncidentsStatus',    false, '사건 상태 변경'],
    'GET /api/collect/test'             => ['apiCollectTest',        false, '수집 연결 시험'],
    'POST /api/collect/run'             => ['apiCollectRun',         false, '수집 즉시 실행'],
    'POST /api/signals/ingest'          => ['apiSignalsIngest',      false, '신호 적재'],
    'POST /api/signals/review'          => ['apiSignalsReview',      false, '신호 판정 실행'],
    'POST /api/signals/decide'          => ['apiSignalDecide',       false, '신호 결정'],
    'GET /api/signals/dropped/sample'   => ['apiSignalsDroppedSample', false, '버린 것 표본'],
    'GET /api/signals/pending'          => ['apiSignalsPending',     false, '검토 대기'],
    'POST /api/signals/dropped/misdrop' => ['apiSignalsMisdrop',     false, '오탈락 신고'],
    'GET /api/feedback/queue'           => ['apiFeedbackQueue',      false, '되먹임 대기열'],
    'GET /api/feedback/suggest'         => ['apiFeedbackSuggest',    false, '규칙 개선 제안'],
    'POST /api/alerts/ack'              => ['apiAlertsAck',          false, '경보 확인'],
    'POST /api/report/daily'            => ['apiReportDailyMake',    false, '일간 보고 생성'],
    'GET /api/report/preview'           => ['apiReportPreview',      false, '일간 보고 미리보기'],
    'POST /api/inspection/draft'        => ['apiInspectionDraft',    false, '점검 메일 초안'],
    'POST /api/inspection/draft.eml'    => ['apiDraftEml',           false, '점검 메일 .eml'],
    'POST /api/inspection/record'       => ['apiInspectionRecord',   false, '회신 기록'],
    'GET /api/inspection/reasons'       => ['apiInspectionReasons',  false, '점검 사유 낱말표'],
    'GET /api/inspection/history'       => ['apiInspectionHistory',  false, '점검 이력'],
    'POST /api/inspection/migrate'      => ['apiInspectionMigrate',  false, '브라우저 기록 이관'],
    'POST /api/gates'                   => ['apiGates',              false, '게이트 실행'],
    'POST /api/match'                   => ['apiMatch',              false, 'LLM 대조'],
    'POST /api/llm'                     => ['apiLlm',                false, 'LLM 호출'],
    'POST /api/llm/test'                => ['apiLlmTest',            false, 'LLM 연결 시험'],
    'GET /api/llm/status'               => ['apiLlmStatus',          false, 'LLM 상태'],
    'GET /api/llm/adapters'             => ['apiLlmAdapters',        false, 'LLM 어댑터 목록'],
    'POST /api/ledger'                  => ['apiLedger',             false, '원장 append'],
    'POST /api/rebuild'                 => ['apiRebuild',            false, '재생성'],
];

// ── 요청 분석 ─────────────────────────────────────────────────────
Auth::sessionStartSafe();
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
$key    = Http::routeKey();
$isApi  = str_starts_with($key, '/api/');

// 정적 파일 — Apache 는 .htaccess 가 먼저 서빙한다. php -S 나 재작성 없는 환경용 폴백.
if (str_starts_with($key, '/static/')) {
    $root = realpath(__DIR__ . '/static');
    $file = realpath(__DIR__ . $key);
    if ($root && $file && str_starts_with($file, $root . DIRECTORY_SEPARATOR) && is_file($file)) {
        $ext  = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $mime = ['woff2' => 'font/woff2', 'png' => 'image/png', 'txt' => 'text/plain; charset=utf-8',
                 'json' => 'application/json', 'css' => 'text/css', 'js' => 'application/javascript',
                 'svg' => 'image/svg+xml', 'jpg' => 'image/jpeg'][$ext] ?? (mime_content_type($file) ?: 'application/octet-stream');
        header('Content-Type: ' . $mime);
        header('Cache-Control: public, max-age=86400');
        readfile($file);
    } else {
        http_response_code(404);
        echo 'not found';
    }
    exit;
}

// ── 라우트 찾기 ───────────────────────────────────────────────────
$handler = null;   // [class, method, public, args]
if ($isApi) {
    foreach ($APIS as $spec => [$fn, $pub]) {
        [$m, $pattern] = explode(' ', $spec, 2);
        if ($m !== $method) {
            continue;
        }
        if ($pattern === $key) {
            $handler = ['Api', $fn, $pub, []];
            break;
        }
        if (str_contains($pattern, '{')) {
            $re = '#^' . preg_replace('/\\\\\{\w+\\\\\}/', '(.+)', preg_quote($pattern, '#')) . '$#u';
            if (preg_match($re, $key, $mm)) {
                $handler = ['Api', $fn, $pub, array_map('urldecode', array_slice($mm, 1))];
                break;
            }
        }
    }
    if ($handler === null) {
        // 경로는 있는데 메서드가 다른가
        $known = array_filter(array_keys($APIS), fn($s) => explode(' ', $s, 2)[1] === $key);
        Http::jsonResponse(['ok' => false, 'code' => $known ? 9005 : 9004,
            'error' => $known ? '허용되지 않은 메서드' : '없는 API', 'where' => $key], $known ? 405 : 404);
        exit;
    }
} elseif (isset($PAGES[$key])) {
    [$fn, $pub] = $PAGES[$key];
    if ($method !== 'GET' && !($method === 'POST' && $fn === 'login')) {
        http_response_code(405);
        echo '허용되지 않은 메서드';
        exit;
    }
    $handler = ['Pages', $fn, $pub, $fn === 'hub' ? [$PAGES, $APIS] : []];
} else {
    http_response_code(404);
    Http::render('hub.php', ['not_found' => $key, 'pages' => $PAGES, 'apis' => $APIS,
        'checks' => [], 'stats' => [], 'collect' => null, 'php' => PHP_VERSION, 'data_dir' => Config::dataDir()]);
    exit;
}

// ── ② 게이트 (routes.py @before_request _gate) ────────────────────
[$class, $fn, $public, $args] = $handler;
if (!$public && !Auth::loggedIn()) {
    if ($isApi) {
        Http::jsonResponse(['ok' => false, 'code' => 9001, 'error' => '로그인이 필요합니다'], 401);
    } else {
        $qs = (string)($_SERVER['QUERY_STRING'] ?? '');
        Http::redirect(Http::url('/login', ['next' => Http::url($key) . ($qs !== '' ? '?' . $qs : '')]));
    }
    exit;
}

// ── ④ 처리기 호출 ─────────────────────────────────────────────────
try {
    $class::$fn(...$args);
} catch (Throwable $ex) {
    // API 는 오류도 JSON 으로 답한다 (routes.py _api_error). 감사로그에 남긴다.
    try {
        Store::appendAudit($isApi ? 'api.error' : 'page.error',
            $key . ' — ' . get_class($ex) . ': ' . $ex->getMessage(), Auth::actorNow());
    } catch (Throwable) {}
    error_log(sprintf('[mbridgephp] %s %s — %s: %s @ %s:%d', $method, $key, get_class($ex), $ex->getMessage(), $ex->getFile(), $ex->getLine()));
    if (!headers_sent()) {
        http_response_code(500);
    }
    if ($isApi) {
        Http::jsonResponse(['ok' => false, 'error' => '서버에서 처리하지 못했습니다.', 'where' => $key,
            'detail' => $DEBUG ? get_class($ex) . ': ' . $ex->getMessage() . ' @ ' . basename($ex->getFile()) . ':' . $ex->getLine() : null], 500);
    } else {
        echo '<!doctype html><meta charset="utf-8"><title>오류</title><body style="font-family:sans-serif;padding:2rem">';
        echo '<h2>서버에서 처리하지 못했습니다.</h2><p>' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '</p>';
        if ($DEBUG) {
            echo '<pre style="white-space:pre-wrap">' . htmlspecialchars(get_class($ex) . ': ' . $ex->getMessage() . "\n" . $ex->getFile() . ':' . $ex->getLine() . "\n\n" . $ex->getTraceAsString(), ENT_QUOTES, 'UTF-8') . '</pre>';
        } else {
            echo '<p><a href="' . htmlspecialchars(Http::url('/'), ENT_QUOTES, 'UTF-8') . '">허브로</a> · 자세한 내용은 서버 오류 로그와 revision/audit_log.json 에 남았습니다.</p>';
        }
    }
}
