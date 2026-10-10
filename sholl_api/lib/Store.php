<?php
class Store {
  private $type; // 'users', 'analyses', 'shares'
  private $dir;

  public function __construct($type) {
    if (!in_array($type, ['users', 'analyses', 'shares'])) {
      throw new Exception("Invalid store type: $type");
    }
    $this->type = $type;
    $this->dir = DATA_DIR . '/' . $type;
    if (!is_dir($this->dir)) {
      mkdir($this->dir, 0700, true);
    }
  }

  private function getPath($id) {
    // ID 검증 (안전한 파일명)
    if (!preg_match('/^[a-zA-Z0-9_:.\-]+$/', $id)) {
      throw new Exception("Invalid ID format: $id");
    }
    return $this->dir . '/' . $id . '.json';
  }

  // 원자적 읽기
  public function read($id) {
    $path = $this->getPath($id);
    if (!file_exists($path)) {
      return null;
    }
    $content = file_get_contents($path);
    return $content ? json_decode($content, true) : null;
  }

  // 원자적 쓰기 (임시 파일 + rename)
  public function write($id, $data) {
    $path = $this->getPath($id);
    $tmpPath = $path . '.tmp';

    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if ($json === false) {
      throw new Exception("JSON encode failed");
    }

    // 임시 파일에 쓰기
    if (file_put_contents($tmpPath, $json, LOCK_EX) === false) {
      throw new Exception("Failed to write temporary file");
    }

    // 원자적 이동
    if (!rename($tmpPath, $path)) {
      unlink($tmpPath);
      throw new Exception("Failed to rename file");
    }
  }

  // 읽기 전용 파일 존재 확인
  public function exists($id) {
    return file_exists($this->getPath($id));
  }

  // 전체 조회 (관리자용, 배포에서는 사용 안 함)
  public function listAll() {
    $files = glob($this->dir . '/*.json');
    $result = [];
    foreach ($files as $file) {
      $id = basename($file, '.json');
      $result[$id] = $this->read($id);
    }
    return $result;
  }

  // 삭제
  public function delete($id) {
    $path = $this->getPath($id);
    if (file_exists($path)) {
      unlink($path);
    }
  }
}
