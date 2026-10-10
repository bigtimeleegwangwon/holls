<?php
// -*- coding: utf-8 -*-
/**
 * Store — JSON 저장소. store.py 완전 이식본.
 *
 * 원자적 쓰기 + 자동 백업(30개) + 감사로그.
 *
 * 설계 결정 (원본에서 그대로 가져온다):
 *   source/survey/ — 사외 원본. 손대지 않는다.
 *   revision/ledger — 사내 덧붙임. 쌓기만 한다.
 *   build/ — 두 층으로부터 매번 다시 만드는 산출물.
 *
 * 쓰기 경로 3곳: 원장 append, 알림 설정, 재생성 산출물.
 */
declare(strict_types=1);

require_once __DIR__ . '/../config.php';

final class Store
{
    private const MAX_BACKUPS = 30;
    private const MAX_AUDIT   = 500;

    // store.py 의 WATCHED — append 대상 원장 (백업 대상)
    private static array $watched = [
        'revision/ledger.jsonl',
        'revision/alert_config.json',
        'revision/incidents.json',
        'revision/inspection.jsonl',
        'revision/alerts.jsonl',
        'build/bp_resolved.json',
    ];

    // ── 경로 헬퍼 ─────────────────────────────────────────────────

    /**
     * 경로 탈출 방지(path traversal). store.py path_for() 이식.
     * 실제로 한 번 실패한 사고 — /api/report/daily?day= 로 자격 파일이 나갔다.
     *
     * @throws InvalidArgumentException  DATA_DIR 밖을 가리킬 때
     */
    public static function sandbox(string $rel): string
    {
        $root = realpath(Config::dataDir());
        if ($root === false) {
            // 디렉터리가 아직 없으면 만들고 재시도
            mkdir(Config::dataDir(), 0755, true);
            $root = realpath(Config::dataDir());
            if ($root === false) {
                throw new RuntimeException('데이터 디렉터리를 만들지 못했습니다: ' . Config::dataDir());
            }
        }
        // rel 을 DATA_DIR 기준으로 절대화
        $abs = $root . DIRECTORY_SEPARATOR . ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $rel), DIRECTORY_SEPARATOR);
        // symlink 등을 제거하기 위해 경로 정규화 (파일이 없어도 동작)
        $normalized = self::normalizePath($abs);
        if ($normalized !== $root && strpos($normalized, $root . DIRECTORY_SEPARATOR) !== 0) {
            throw new InvalidArgumentException('데이터 폴더 밖을 가리킨다: ' . $rel);
        }
        return $normalized;
    }

    /**
     * realpath() 는 파일이 없으면 false. 경로 컴포넌트만 정규화한다.
     */
    private static function normalizePath(string $path): string
    {
        $parts  = explode(DIRECTORY_SEPARATOR, str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path));
        $stack  = [];
        foreach ($parts as $p) {
            if ($p === '' || $p === '.') {
                continue;
            }
            if ($p === '..') {
                if ($stack) {
                    array_pop($stack);
                }
            } else {
                $stack[] = $p;
            }
        }
        return DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $stack);
    }

    // ── JSON 읽기 / 원자적 쓰기 ──────────────────────────────────

    /**
     * JSON 파일을 읽어 배열로 반환한다. store.py read_json() 이식.
     *
     * @param mixed $default  파일이 없거나 손상됐을 때 돌려줄 값
     * @return mixed
     */
    public static function readJson(string $rel, mixed $default = null): mixed
    {
        $path = self::sandbox($rel);
        if (!file_exists($path)) {
            return $default;
        }
        $fp = fopen($path, 'r');
        if ($fp === false) {
            return $default;
        }
        if (!flock($fp, LOCK_SH)) {
            fclose($fp);
            return $default;
        }
        $raw = stream_get_contents($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
        if ($raw === false || $raw === '') {
            return $default;
        }
        $d = json_decode($raw, true);
        if ($d === null && json_last_error() !== JSON_ERROR_NONE) {
            // 손상 기록 — 감사로그에 한 번
            try {
                self::appendAudit('store.corrupt', $rel . ' — JSON 파싱 실패: ' . json_last_error_msg(), 'system');
            } catch (Throwable) {}
            return $default;
        }
        return $d;
    }

    /**
     * JSON 을 원자적으로 쓴다 (임시 파일 → rename + flock).
     * store.py write_json() 이식.
     */
    public static function writeJson(string $rel, mixed $data, bool $makeBackup = true): string
    {
        $path = self::sandbox($rel);
        $dir  = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        if ($makeBackup && file_exists($path)) {
            self::autoBackup($path);
        }
        $tmp = $path . '.tmp.' . bin2hex(random_bytes(4));
        $fp  = fopen($tmp, 'w');
        if ($fp === false) {
            throw new RuntimeException('임시 파일을 열지 못했습니다: ' . $tmp);
        }
        if (!flock($fp, LOCK_EX)) {
            fclose($fp);
            @unlink($tmp);
            throw new RuntimeException('임시 파일 잠금 실패: ' . $tmp);
        }
        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        if ($json === false) {
            flock($fp, LOCK_UN);
            fclose($fp);
            @unlink($tmp);
            throw new RuntimeException('JSON 인코딩 실패');
        }
        fwrite($fp, $json);
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
        if (!rename($tmp, $path)) {
            @unlink($tmp);
            throw new RuntimeException('파일 갈아끼우기 실패: ' . $path);
        }
        return $path;
    }

    // ── JSONL ──────────────────────────────────────────────────────

    /**
     * JSONL 파일에 한 줄을 덧붙인다. store.py append_line() 이식.
     * append 는 그 자체로 원자적이지 않지만, 기존 줄을 건드리지 않아 안전하다.
     */
    public static function appendJsonl(string $rel, array $record): string
    {
        $path = self::sandbox($rel);
        $dir  = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $line = json_encode($record, JSON_UNESCAPED_UNICODE) . "\n";
        $fp   = fopen($path, 'a');
        if ($fp === false) {
            throw new RuntimeException('JSONL 파일을 열지 못했습니다: ' . $path);
        }
        if (!flock($fp, LOCK_EX)) {
            fclose($fp);
            throw new RuntimeException('JSONL 파일 잠금 실패: ' . $path);
        }
        fwrite($fp, $line);
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
        return $path;
    }

    /**
     * JSONL 파일을 읽어 배열의 배열로 반환. store.py read_json + lines 이식.
     *
     * @return array<int,mixed>
     */
    public static function readJsonl(string $rel): array
    {
        $path = self::sandbox($rel);
        if (!file_exists($path)) {
            return [];
        }
        $fp = fopen($path, 'r');
        if ($fp === false) {
            return [];
        }
        flock($fp, LOCK_SH);
        $lines = [];
        while (!feof($fp)) {
            $line = fgets($fp);
            if ($line === false) {
                break;
            }
            $line = trim($line);
            if ($line === '') {
                continue;
            }
            $d = json_decode($line, true);
            if ($d !== null) {
                $lines[] = $d;
            }
        }
        flock($fp, LOCK_UN);
        fclose($fp);
        return $lines;
    }

    // ── 백업 ──────────────────────────────────────────────────────

    /**
     * 파일을 backup/ 에 복사하고 오래된 것은 지운다.
     * store.py backup_file() / auto_backup() 이식.
     *
     * @param string $path  실제 파일 절대 경로
     */
    public static function autoBackup(string $path, int $slots = 30): void
    {
        if (!file_exists($path)) {
            return;
        }
        $root      = Config::dataDir();
        $backupDir = $root . DIRECTORY_SEPARATOR . 'backup';
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        // stem: revision_ledger.jsonl (경로 구분자 → 밑줄, 확장자 유지)
        $rel  = substr($path, strlen($root) + 1);
        $stem = str_replace(DIRECTORY_SEPARATOR, '_', $rel);
        $stamp = date('Ymd_His_') . sprintf('%06d', (int)(microtime(true) * 1000000) % 1000000);
        $dst  = $backupDir . DIRECTORY_SEPARATOR . $stem . '.' . $stamp . '.bak';
        copy($path, $dst);
        // 초과분 삭제
        $pattern = $backupDir . DIRECTORY_SEPARATOR . $stem . '.*.bak';
        $files   = glob($pattern) ?: [];
        rsort($files);
        foreach (array_slice($files, $slots) as $old) {
            @unlink($old);
        }
    }

    // ── 감사 로그 ─────────────────────────────────────────────────

    /**
     * 감사 로그에 항목을 추가한다. store.py append_audit(action, detail, actor) 이식.
     * revision/audit_log.json — 최대 500 항목.
     */
    public static function appendAudit(string $action, string $detail = '', string $actor = ''): array
    {
        $auditRel  = 'revision/audit_log.json';
        $auditPath = self::sandbox($auditRel);
        $dir       = dirname($auditPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        // 락 파일로 read-modify-write 직렬화
        $lf = fopen($auditPath . '.lock', 'c');
        if ($lf === false) {
            return ['action' => $action, 'detail' => $detail, 'actor' => $actor];
        }
        flock($lf, LOCK_EX);
        $log = self::readJson($auditRel, []) ?: [];
        $record = [
            'timestamp' => (new DateTime('now', new DateTimeZone('Asia/Seoul')))->format('Y-m-d\TH:i:sP'),
            'actor'     => $actor !== '' ? $actor : '-',
            'action'    => $action,
            'detail'    => $detail,
        ];
        $log[] = $record;
        if (count($log) > self::MAX_AUDIT) {
            $log = array_slice($log, -self::MAX_AUDIT);
        }
        self::writeJson($auditRel, $log, false);
        flock($lf, LOCK_UN);
        fclose($lf);
        return $record;
    }

    // ── 호환 별칭 · 공용 락 ───────────────────────────────────────

    /** store.py path_for() — sandbox 와 같다. */
    public static function pathFor(string $rel): string
    {
        return self::sandbox($rel);
    }

    /** store.py append_line() — appendJsonl 과 같다. */
    public static function appendLine(string $rel, array $record): string
    {
        return self::appendJsonl($rel, $record);
    }

    /**
     * store.py LOCK — 라우트에서 read-modify-write 를 한 락으로 묶는다.
     * 파일 핸들을 돌려주므로 flock($lock, LOCK_EX) / flock($lock, LOCK_UN) 으로 쓴다.
     * @return resource
     */
    public static function LOCK()
    {
        static $fp = null;
        if ($fp === null) {
            $path = self::sandbox('.store.lock');
            $fp   = fopen($path, 'c');
            if ($fp === false) {
                throw new RuntimeException('공용 락 파일을 열지 못했습니다: ' . $path);
            }
        }
        return $fp;
    }

    /**
     * 감사 로그를 읽는다. store.py read_audit() 이식.
     *
     * @return array<int,array>  최신순
     */
    public static function readAudit(int $limit = 100): array
    {
        $log = self::readJson('revision/audit_log.json', []) ?: [];
        $slice = array_slice($log, -$limit);
        return array_reverse($slice);
    }
}
