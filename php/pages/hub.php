<?php
// 허브 — 최초 진입 화면. 화면·API 목록과 서버 상태를 한 장에 보인다.
// 데이터는 싣지 않는다(비로그인도 본다). 숫자는 집계만.
$h = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
$okAll = $checks && !array_filter($checks, fn($c) => !$c[1]);
?><!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $h(Config::APP_NAME) ?> · 허브</title>
<?php include __DIR__ . '/_shared/theme.php'; ?>
<style>
  body{margin:0;padding:0;background:var(--bg);color:var(--ink);font-family:'Pretendard',system-ui,sans-serif}
  .hub{max-width:1100px;margin:0 auto;padding:32px 24px 64px}
  .hub-head{display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:16px;margin-bottom:28px}
  .hub-head h1{margin:0;font-size:28px;letter-spacing:-.02em}
  .hub-head h1 small{font-size:14px;font-weight:500;color:var(--muted);margin-left:10px}
  .hub-head .meta{font-size:13px;color:var(--muted);text-align:right;line-height:1.7}
  .hub h2{font-size:15px;margin:32px 0 12px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.06em}
  .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:12px}
  .card{display:block;padding:16px 18px;border:1px solid var(--line);border-radius:12px;background:var(--card);text-decoration:none;color:inherit;transition:border-color .15s,transform .15s}
  .card:hover{border-color:var(--accent);transform:translateY(-1px)}
  .card b{display:block;font-size:16px;margin-bottom:4px}
  .card span{font-size:13px;color:var(--muted);line-height:1.5}
  .card code{font-size:12px;color:var(--muted);display:block;margin-top:8px}
  .badge{display:inline-block;font-size:11px;padding:1px 7px;border-radius:999px;border:1px solid var(--line);margin-left:6px;vertical-align:middle;color:var(--muted)}
  .badge.lock{border-color:#d9a300;color:#a67c00}
  .badge.open{border-color:#2e9e5b;color:#2e9e5b}
  .stats{display:flex;flex-wrap:wrap;gap:12px}
  .stat{flex:1 1 160px;padding:14px 16px;border:1px solid var(--line);border-radius:12px;background:var(--panel)}
  .stat b{display:block;font-size:24px;letter-spacing:-.02em}
  .stat span{font-size:12px;color:var(--muted)}
  table.chk{width:100%;border-collapse:collapse;font-size:13px}
  table.chk td{padding:8px 10px;border-bottom:1px solid var(--line);vertical-align:top}
  table.chk td:first-child{width:28px;text-align:center}
  table.chk td:nth-child(2){width:260px;font-weight:600}
  table.chk td:last-child{color:var(--muted);word-break:break-all}
  .ok{color:#2e9e5b}.ng{color:#d23c3c}
  table.api{width:100%;border-collapse:collapse;font-size:12.5px}
  table.api td{padding:6px 10px;border-bottom:1px solid var(--line)}
  table.api td:first-child{width:56px;font-weight:700;color:var(--muted)}
  table.api td:nth-child(2){font-family:ui-monospace,Menlo,monospace}
  table.api a{color:inherit;text-decoration:none;border-bottom:1px dotted var(--line)}
  .notice{padding:12px 16px;border-radius:10px;background:#fff6d6;border:1px solid #ecd58a;color:#5a4300;margin-bottom:20px;font-size:14px}
  .foot{margin-top:40px;font-size:12px;color:var(--muted);line-height:1.7}
  #probe{font-weight:600}
</style>
</head>
<body>
<main class="hub">
  <header class="hub-head">
    <h1><?= $h(Config::APP_NAME) ?> <small>material Global SCM Control System · PHP 배포판</small></h1>
    <div class="meta">
      <?= $h($app_version ?? '') ?> · 갱신 <?= $h($updated_at ?? '') ?><br>
      <?php if (!empty($logged_in)): ?>
        <b><?= $h($actor_now ?? '') ?></b> 로그인 중 · <a href="<?= url('/logout') ?>">나가기</a>
      <?php else: ?>
        <a href="<?= url('/login') ?>">관리자 로그인</a>
      <?php endif; ?>
    </div>
  </header>

  <?php if (!empty($not_found)): ?>
    <div class="notice">요청한 주소가 없습니다: <code><?= $h($not_found) ?></code> — 아래 목록에서 고르세요.</div>
  <?php endif; ?>
  <?php if (($data_profile ?? 'real') === 'mock'): ?>
    <div class="notice">모사 데이터 프로필입니다 — 구조는 실제, 값은 생성. <?= $h($data_profile_meta['swap'] ?? '') ?></div>
  <?php endif; ?>

  <?php if ($stats): ?>
  <section class="stats">
    <div class="stat"><b><?= $h($stats['sites'] ?? '—') ?></b><span>사업장</span></div>
    <div class="stat"><b><?= $h($stats['companies'] ?? '—') ?></b><span>BP</span></div>
    <div class="stat"><b><?= $h($stats['countries'] ?? '—') ?></b><span>국가</span></div>
    <div class="stat"><b id="probe">…</b><span>API 연결 (url 재작성 점검)</span></div>
    <div class="stat"><b><?= $h($collect['ranAt'] ?? '아직') ?></b><span>마지막 수집 회차</span></div>
  </section>
  <?php endif; ?>

  <h2>화면</h2>
  <div class="grid">
    <?php foreach ($pages as $path => [$fn, $pub, $label, $desc]): if ($label === '' || $fn === 'hub' || $fn === 'logout') continue; ?>
      <a class="card" href="<?= url($path) ?>">
        <b><?= $h($label) ?><span class="badge <?= $pub ? 'open' : 'lock' ?>"><?= $pub ? '공개' : '관리자' ?></span></b>
        <span><?= $h($desc) ?></span>
        <code><?= $h(url($path)) ?></code>
      </a>
    <?php endforeach; ?>
  </div>

  <?php if ($checks): ?>
  <h2>서버 점검 <?= $okAll ? '<span class="ok">— 전부 통과</span>' : '<span class="ng">— 확인 필요</span>' ?></h2>
  <table class="chk">
    <?php foreach ($checks as [$name, $ok, $why]): ?>
      <tr><td class="<?= $ok ? 'ok' : 'ng' ?>"><?= $ok ? '✓' : '✗' ?></td><td><?= $h($name) ?></td><td><?= $h($why) ?></td></tr>
    <?php endforeach; ?>
  </table>
  <?php endif; ?>

  <h2>API <span style="font-weight:400;text-transform:none;letter-spacing:0">— 공개 <?= count(array_filter($apis, fn($a) => $a[1])) ?> · 관리자 <?= count(array_filter($apis, fn($a) => !$a[1])) ?></span></h2>
  <table class="api">
    <?php foreach ($apis as $spec => [$fn, $pub, $desc]): [$m, $p] = explode(' ', $spec, 2); ?>
      <tr>
        <td><?= $h($m) ?></td>
        <td><?php if ($m === 'GET' && !str_contains($p, '{')): ?><a href="<?= url($p) ?>"><?= $h(url($p)) ?></a><?php else: ?><?= $h(url($p)) ?><?php endif; ?></td>
        <td><?= $h($desc) ?><span class="badge <?= $pub ? 'open' : 'lock' ?>"><?= $pub ? '공개' : '관리자' ?></span></td>
      </tr>
    <?php endforeach; ?>
  </table>

  <p class="foot">
    데이터 폴더 <code><?= $h($data_dir) ?></code> · PHP <?= $h($php) ?><br>
    수집은 웹이 아니라 cron 이 돈다 — <code>php cron/collect.php</code> 를 MGSCS_COLLECT_MIN(기본 60분)마다.<br>
    기획·개발 SK hynix 소재전략 이정훈 TL · PHP 이식 mbridgephp
  </p>
</main>
<script>
// URL 재작성이 살아 있는지 — API 하나를 실제로 불러 본다.
fetch("<?= url('/api/errors') ?>").then(function(r){ return r.ok ? r.json() : Promise.reject(r.status); })
  .then(function(){ var p=document.getElementById('probe'); if(p){p.textContent='정상'; p.className='ok';} })
  .catch(function(e){ var p=document.getElementById('probe'); if(p){p.textContent='실패 ('+e+')'; p.className='ng';} });
</script>
</body>
</html>
