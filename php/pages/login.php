<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="mGSCS">
<meta name="mobile-web-app-capable" content="yes">
<meta name="theme-color" content="#1D3A6E">
<meta name="format-detection" content="telephone=no">
<meta name="robots" content="noindex,nofollow">
<title>mGSCS · 로그인</title>
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='4' fill='%231d3a6e'/%3E%3Ctext x='16' y='22' font-size='17' font-family='sans-serif' font-weight='800' fill='white' text-anchor='middle'%3Em%3C/text%3E%3C/svg%3E">

<?php include __DIR__ . '/_shared/theme.php'; ?>


<style>
*{box-sizing:border-box}
body{margin:0;min-height:100vh;display:flex;flex-direction:column}
a{color:var(--navy-2)}

.bar{border-bottom:1px solid var(--line);padding:15px 20px;
     display:flex;align-items:center;justify-content:center;gap:13px}
.bar .wm{font-size:var(--fs-md);font-weight:800;letter-spacing:-.035em;color:var(--ink)}

main{flex:1;display:flex;align-items:center;justify-content:center;padding:32px 20px 56px}
.box{width:100%;max-width:392px}

h1{margin:0 0 6px;font-size:var(--fs-xl);font-weight:800;letter-spacing:-.03em}
.lead{margin:0 0 22px;font-size:var(--fs-sm);color:var(--ink-2);line-height:1.65}

label{display:block;font-size:var(--fs-xs);font-weight:700;color:var(--ink-2);margin:0 0 5px}
input{width:100%;padding:11px 13px;font:inherit;font-size:var(--fs-base);
      border:1px solid var(--line2);border-radius:var(--r-md);background:var(--card);
      color:var(--ink);box-shadow:var(--sh)}
input:focus{outline:none;border-color:var(--navy);box-shadow:0 0 0 3px var(--accent-soft)}
.field{margin:0 0 14px}
.hint{margin:5px 0 0;font-size:var(--fs-2xs);color:var(--ink-3)}

button{width:100%;padding:12px;margin-top:4px;border:1px solid var(--navy);
       background:var(--navy);color:var(--on-navy);border-radius:var(--r-md);
       font:inherit;font-size:var(--fs-base);font-weight:700;cursor:pointer}
button:hover{background:var(--navy-hover);border-color:var(--navy-hover)}

.err{margin:0 0 16px;padding:11px 14px;border:1px solid var(--hi-line);
     background:var(--hi-bg);border-radius:var(--r-md);
     font-size:var(--fs-sm);color:var(--red-ink);line-height:1.6}
.note{margin:0 0 18px;padding:12px 15px;border:1px solid var(--navy-line);
      background:var(--accent-soft);border-radius:var(--r-md);
      font-size:var(--fs-sm);color:var(--ink-2);line-height:1.7}
.note b{color:var(--navy)}
.note code{display:block;margin:8px 0 0;padding:9px 11px;background:var(--card);
           border:1px solid var(--line);border-radius:var(--r-sm);
           font-family:var(--mono);font-size:var(--fs-xs);color:var(--ink);
           overflow-x:auto;white-space:nowrap}

.open{margin-top:22px;padding-top:16px;border-top:1px solid var(--line);
      font-size:var(--fs-sm);color:var(--ink-3);line-height:1.8}
.open a{text-decoration:none;font-weight:700}
.open a:hover{text-decoration:underline}
</style>

</head>
<body>

<div class="bar"><span class="wm">mGSCS</span></div>

<main>
<div class="box">

<?php if ($setup): ?>
  <h1>처음 한 번, 비밀번호를 정합니다</h1>
  <p class="lead">이 서버에는 아직 로그인 비밀번호가 없습니다.
     기본 비밀번호를 미리 넣어 두지 않습니다 — 대개 그대로 남기 때문입니다.</p>

  <div class="note">
    <b>이 화면은 한 번만 열립니다.</b>
    정하고 나면 이 경로는 닫히고 평범한 로그인 화면이 됩니다.
    올린 직후 <b>바로 정하세요</b> — 먼저 여는 사람이 정하게 됩니다.
  </div>

  <?php if ($error): ?><div class="err"><?= htmlspecialchars($error ?? '') ?></div><?php endif; ?>

  <form method="post" autocomplete="off">
    <input type="hidden" name="next" value="<?= htmlspecialchars($next ?? '') ?>">
    <div class="field">
      <label for="actor">사번</label>
      <input id="actor" name="actor" inputmode="numeric" autocomplete="username" required autofocus>
      <p class="hint">누가 정했는지 감사 기록에 남습니다.</p>
    </div>
    <div class="field">
      <label for="password">새 비밀번호</label>
      <input id="password" name="password" type="password"
             autocomplete="new-password" minlength="8" required>
      <p class="hint">8자 이상. 해시로만 저장되고 평문은 어디에도 남지 않습니다.</p>
    </div>
    <div class="field">
      <label for="password2">한 번 더</label>
      <input id="password2" name="password2" type="password"
             autocomplete="new-password" minlength="8" required>
    </div>
    <button type="submit">정하고 들어가기</button>
  </form>

  <div class="note" style="margin-top:18px">
    서버 셸을 쓸 수 있다면 이렇게 해도 됩니다.
    <code>python3 tools/auth.py</code>
  </div>
<?php else: ?>
  <h1>로그인</h1>
  <p class="lead">BP 기준 정보·연락처·설정을 보려면 로그인이 필요합니다.
     Risk 현황은 로그인 없이 볼 수 있습니다.</p>

  <?php if ($error): ?><div class="err"><?= htmlspecialchars($error ?? '') ?></div><?php endif; ?>

  <form method="post" autocomplete="off">
    <input type="hidden" name="next" value="<?= htmlspecialchars($next ?? '') ?>">
    <div class="field">
      <label for="actor">사번</label>
      <input id="actor" name="actor" inputmode="numeric" autocomplete="username"
             value="<?= htmlspecialchars($actor_now ?? '') ?>" required autofocus>
      <p class="hint">누가 무엇을 고쳤는지 원장에 이 번호로 남습니다.</p>
    </div>
    <div class="field">
      <label for="password">비밀번호</label>
      <input id="password" name="password" type="password"
             autocomplete="current-password" required <?php if ($lock): ?>disabled<?php endif; ?>>
    </div>
    <button type="submit" <?php if ($lock): ?>disabled<?php endif; ?>>들어가기</button>
  </form>
<?php endif; ?>

  <div class="open">
    로그인 없이 볼 수 있는 화면<br>
    <a href="<?= url('/dashboard') ?>">Risk 현황</a> ·
    <a href="<?= url('/home') ?>">대문</a>
  </div>

</div>
</main>

</body>
</html>
