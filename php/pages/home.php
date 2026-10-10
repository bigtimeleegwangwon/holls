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
<link rel="apple-touch-icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 180 180'%3E%3Crect width='180' height='180' fill='%231d3a6e'/%3E%3Ctext x='90' y='124' font-size='104' font-family='sans-serif' font-weight='800' fill='white' text-anchor='middle'%3Em%3C/text%3E%3C/svg%3E">
<title>mGSCS</title>
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='4' fill='%231d3a6e'/%3E%3Ctext x='16' y='22' font-size='17' font-family='sans-serif' font-weight='800' fill='white' text-anchor='middle'%3Em%3C/text%3E%3C/svg%3E">

<?php include __DIR__ . '/_shared/theme.php'; ?>

<style>
/* ── 대문 = 요약 허브 ────────────────────────────────────────────
   숫자는 일간 보고서가 세는 것을 그대로 받는다(routes.home → summary).
   두 곳에서 따로 세면 언젠가 갈라지고, 그때 어느 쪽이 맞는지 알 수 없다.
   색·간격·라운드는 전부 _shared/theme.html 토큰. 새 hex 금지.        */
*{box-sizing:border-box}
body{background:var(--surface);}
main{max-width:var(--page-w);margin:0 auto;padding:var(--sp-6) var(--sp-6) var(--sp-8);}

.pg-h{display:flex;align-items:baseline;gap:var(--sp-3);flex-wrap:wrap;
  padding-bottom:var(--sp-3);border-bottom:2px solid var(--navy);margin-bottom:var(--sp-6);}
.pg-h h1{margin:0;font-size:var(--fs-xl);font-weight:800;letter-spacing:-.02em;}
.pg-h .sub{font-size:var(--fs-sm);color:var(--ink-3);}
/* 숫자만 있으면 "언제 것인지" 를 모른다. 감지·판정 시각을 같이 적는다. */
.rk-at{margin-left:var(--sp-3);font-size:var(--fs-2xs);color:var(--ink-3);
  font-variant-numeric:tabular-nums}
/* 신호등 — 색 하나로 끝내지 않는다. 글자가 같이 간다(색각 이상 대비). */
.rk-sig{margin-left:auto;display:inline-flex;align-items:center;gap:6px;
  font-size:var(--fs-xs);font-weight:700;padding:3px 10px;border-radius:999px;
  border:1px solid var(--line2);color:var(--ink-2)}
.rk-sig i{width:8px;height:8px;border-radius:50%;background:var(--ink-3)}
.rk-sig.ok{border-color:var(--ok-line,#BBD9BE);background:var(--ok-bg,#F2F8F3);color:var(--ok,#2E7D32)}
.rk-sig.ok i{background:var(--ok,#2E7D32)}
.rk-sig.warn{border-color:var(--navy-line);background:var(--accent-soft);color:var(--navy)}
.rk-sig.warn i{background:var(--navy)}
.rk-sig.bad{border-color:var(--hi-line);background:var(--hi-bg);color:var(--hi)}
.rk-sig.bad i{background:var(--hi)}

/* 세 줄 결론 — 화면 맨 위. 보고서 첫 세 줄과 같은 순서, 같은 질문. */
/* 아래 세 줄은 위 띠의 **설명**이다. 위와 한 덩이로 붙는다 —
   따로 떨어져 있으면 각자 다른 이야기로 읽힌다. */
.tri{border:1px solid var(--line);border-radius:0 0 var(--r-lg) var(--r-lg);overflow:hidden;
  background:var(--card);margin:0 0 var(--sp-5);}
.tri .r{display:grid;grid-template-columns:var(--sp-8) 1fr auto;gap:var(--sp-3);
  align-items:baseline;padding:var(--sp-3) var(--sp-4);border-bottom:1px solid var(--line-2);}
.tri .r:last-child{border-bottom:0}
.tri .k{font-size:var(--fs-2xs);font-weight:700;letter-spacing:.1em;color:var(--ink-3);}
.tri .v{font-size:var(--fs-base);color:var(--ink);}
.warn-s{display:block;margin-top:3px;font-size:var(--fs-xs);color:var(--hi)}
.tri .v b{font-weight:700}
.tri .go{font-size:var(--fs-xs);color:var(--navy-2);text-decoration:none;white-space:nowrap}
.tri .go:hover{text-decoration:underline}
.tri .r.warn{background:var(--md-bg)}
.tri .r.warn .v{color:var(--md)}
.tri .r.crit{background:var(--hi-bg)}
.tri .r.crit .v{color:var(--hi)}

/* 접히는 카드 — 중요한 하나만 펴 두고 나머지는 접는다. */
/* 넓은 화면(사내 16:9)에서 접힌 카드를 한 줄로 길게 늘이면 제목과 우측 '펼쳐보기'
   사이가 텅 빈다. 두 열로 나눠 폭을 쓴다 — 결론 세 줄과 '확인 대기' 는 가장 먼저
   읽혀야 하므로 폭을 다 쓰고, 그 아래 보조 카드만 나눈다. */
/* 한 줄로 세운다. 2열이면 접힘 높이가 서로 달라 아래가 들쭉날쭉해지고,
   무엇보다 **읽는 순서가 무너진다** — 오른쪽 칸이 왼쪽보다 먼저 읽히는 일이 생긴다.
   이 목록은 일하는 순서다: 확인 대기 → 점검 진행 → 명부 → 누적.
   순서가 뜻을 지고 있으므로 위에서 아래로 한 줄이어야 한다. */
.folds{display:flex;flex-direction:column;gap:var(--sp-3);}
.fold{border:1px solid var(--line);border-radius:var(--r-lg);background:var(--bg);
  margin-bottom:0;}
.fold.wide{margin-bottom:0;}
.fold>summary{list-style:none;cursor:pointer;display:flex;align-items:baseline;
  gap:var(--sp-3);padding:var(--sp-3) var(--sp-4);min-height:44px;box-sizing:border-box;}
/* 제목 너비를 고정한다 — 항목마다 제목 길이가 달라 요약문 시작점이 흔들렸다.
   같은 자리에서 시작해야 눈이 세로로 훑을 수 있다. */
.fold>summary .t{flex:0 0 9.5rem;font-weight:700;}
.fold>summary .m{flex:1 1 auto;color:var(--ink-2);font-size:var(--fs-sm);}
.fold>summary .x{margin-left:auto;}
@media(max-width:640px){
  .fold>summary{flex-wrap:wrap}
  .fold>summary .t{flex:0 0 auto}
  .fold>summary .m{flex:1 0 100%;margin-top:2px}
}
.fold>summary::-webkit-details-marker{display:none}
.fold>summary::before{content:"▸";font-size:var(--fs-2xs);color:var(--ink-3);
  transform:translateY(-1px)}
.fold[open]>summary::before{content:"▾"}
.fold>summary .t{font-size:var(--fs-md);font-weight:700;color:var(--navy);}
.fold>summary .m{font-size:var(--fs-xs);color:var(--ink-3);flex:1;}
.fold>summary .x{font-size:var(--fs-xs);color:var(--ink-3);}
/* 열림/닫힘 말은 CSS 가 뒤집는다 — 템플릿이 상태를 또 들고 있으면 어긋난다. */
.fold>summary .x::after{content:"펼쳐보기"}
.fold[open]>summary .x::after{content:"접기"}
.fold-b{padding:0 var(--sp-4) var(--sp-4);border-top:1px solid var(--line-2);}

/* 목록 한 줄 = 배지 + 제목 + 한 줄 설명 */
.li{display:grid;grid-template-columns:auto 1fr;gap:var(--sp-1) var(--sp-3);
  padding:var(--sp-3) 0;border-bottom:1px dotted var(--line);}
.li:last-child{border-bottom:0}
.li .bg{grid-row:1/3;display:flex;flex-direction:column;gap:3px;align-items:flex-start}
.li .t{font-size:var(--fs-sm);font-weight:700;color:var(--ink)}
.li .d{font-size:var(--fs-xs);color:var(--ink-3);line-height:1.55}
.li a{color:inherit;text-decoration:none}
.li a:hover .t{text-decoration:underline}
.badge{font-size:var(--fs-2xs);font-weight:700;padding:1px 6px;border-radius:var(--r-sm);
  white-space:nowrap;border:1px solid transparent}
.badge.hi{background:var(--hi-bg);color:var(--hi);border-color:var(--hi-line)}
.badge.md{background:var(--md-bg);color:var(--md);border-color:var(--md-line)}
.badge.lo{background:var(--lo-bg);color:var(--lo);border-color:var(--lo-line)}
.badge.sys{background:var(--surface-2);color:var(--ink-3);border-color:var(--line)}
.ackbtn{margin-left:auto;font:inherit;font-size:var(--fs-xs);padding:3px 10px;
  border:1px solid var(--line);border-radius:var(--r-sm);background:var(--surface);
  color:var(--ink-2);cursor:pointer;white-space:nowrap}
.ackbtn:hover{border-color:var(--ink-3);color:var(--ink)}
.ackbtn:focus-visible{outline:2px solid var(--accent);outline-offset:2px}
.ackbtn[disabled]{opacity:.5;cursor:default}
.ackbtn.pri{background:var(--ink);color:var(--surface);border-color:var(--ink);margin-left:0}
.ackmsg{font-size:var(--fs-xs);color:var(--ink-3);margin-left:var(--sp-3)}
.li.acked{opacity:.45;text-decoration:line-through}

.kv{display:flex;flex-wrap:wrap;gap:var(--sp-2) var(--sp-5);padding-top:var(--sp-3);
  font-size:var(--fs-sm);color:var(--ink-2)}
.kv b{font-weight:700;color:var(--ink);font-variant-numeric:tabular-nums}
.acts{display:flex;flex-wrap:wrap;gap:var(--sp-2);padding-top:var(--sp-3)}
.acts a{font-size:var(--fs-xs);color:var(--ink-2);text-decoration:none;
  border:1px solid var(--line2);border-radius:var(--r-md);padding:4px 10px}
.acts a:hover{color:var(--navy-2);border-color:var(--navy-line)}
.acts a.pri{background:var(--navy);color:var(--on-navy);border-color:var(--navy);font-weight:700}
.empty{font-size:var(--fs-sm);color:var(--ink-3);padding:var(--sp-4) 0}
.foot{margin-top:var(--sp-6);padding-top:var(--sp-4);border-top:1px solid var(--line-2);
  font-size:var(--fs-xs);color:var(--ink-3);line-height:1.8}
.foot b{color:var(--ink-2)}

/* ── RISK 감지 · 4단계 ──────────────────────────────────────────────
   막대 길이로 크기를 말하지 않는다. 수집 86 대 점검 2 라 정직한 척도로
   그리면 정작 결론인 뒷단계가 실선처럼 지워진다.
   **크기는 숫자가 지고, 띠는 비율의 인상만 진다.**                      */
.rk{border:1px solid var(--line);border-radius:var(--r-lg) var(--r-lg) 0 0;
  background:var(--card);border-bottom:0;
  padding:var(--sp-4) var(--sp-5);margin:0}
.rk-h{display:flex;align-items:baseline;gap:var(--sp-3);margin-bottom:var(--sp-3)}
.rk-h .t{font-size:var(--fs-sm);font-weight:800;color:var(--ink)}
.rk-h .m{font-size:var(--fs-2xs);color:var(--ink-3)}
.rk-f{display:flex;gap:var(--sp-6);flex-wrap:wrap;margin-bottom:var(--sp-3)}
.rk-f .f{display:flex;align-items:baseline;gap:6px}
.rk-f .st{font-size:var(--fs-2xs);font-weight:700;color:var(--ink-3);
  font-variant-numeric:tabular-nums}
.rk-f .k{font-size:var(--fs-xs);color:var(--ink-3)}
.rk-f .n{font-size:var(--fs-lg);font-weight:800;color:var(--ink);
  font-variant-numeric:tabular-nums;line-height:1;letter-spacing:-.02em}
.rk-f .f.fin .n,.rk-f .f.fin .k,.rk-f .f.fin .st{color:var(--navy)}
:root[data-theme="dark"] .rk-f .f.fin .n{color:var(--navy-2)}
.rk-s{display:flex;height:8px;border-radius:var(--r-sm);overflow:hidden;
  background:var(--line-2)}
.rk-s i{display:block;min-width:0}
.rk-c{display:flex;justify-content:space-between;gap:var(--sp-4);flex-wrap:wrap;
  margin-top:7px;font-size:var(--fs-2xs);color:var(--ink-3)}
.rk-c b{color:var(--ink-2);font-weight:700}
.rk-c .late,.rk-c .late b{color:var(--amber)}
.rk-none{font-size:var(--fs-sm);color:var(--ink-3);margin:0}
@media(max-width:640px){ .rk-f{gap:var(--sp-4)} .rk{padding:var(--sp-4)} }
@media(max-width:640px){ .tri .r{grid-template-columns:1fr;gap:2px} main{padding:var(--sp-4)} }
</style>
</head>
<body>
<?php $here = 'home'; $here_label = '홈'; ?>
<?php include __DIR__ . '/_shared/nav.php'; ?>

<main>
  <div class="pg-h">
    <h1>mGSCS Dashboard</h1>
    <span class="sub">오늘 무엇이 걸렸고 무엇이 남았나.</span>
  </div>


<?php if ($funnel): ?>
<?php
  $f = $funnel;
  $tot = max($f['collected'], 1);
  $_w = $summary ? $summary['watch'] : null;
  if ($_w && !$_w['ingestToday']) {
    $sig_cls = 'bad'; $sig_txt = '수집 끊김';
  } elseif ($f['waiting']) {
    $sig_cls = 'warn'; $sig_txt = '점검 대상 ' . $f['waiting'] . '건';
  } else {
    $sig_cls = 'ok'; $sig_txt = '이상 없음';
  }
?>
  <div class="rk">
    <div class="rk-h">
      <span class="t">Risk Monitoring</span>
      <span class="m">최근 24시간</span>
      <span class="rk-sig <?= htmlspecialchars($sig_cls ?? '') ?>"><i></i><?= htmlspecialchars($sig_txt ?? '') ?></span>
      <span class="rk-at">
        <?php if ($f['lastSignalAt']): ?>감지 <?= htmlspecialchars(substr($f['lastSignalAt'], 11, 5)) ?><?php else: ?>감지 —<?php endif; ?>
        <?php if ($f['lastReviewAt']): ?> · 판정 <?= htmlspecialchars(substr($f['lastReviewAt'], 11, 5)) ?><?php else: ?> · 판정 —<?php endif; ?>
      </span>
    </div>

    <?php if ($f['collected']): ?>
      <div class="rk-f">
        <span class="f"><span class="st">1</span><span class="k">수집</span><span class="n"><?= htmlspecialchars($f['collected'] ?? '') ?></span></span>
        <span class="f"><span class="st">2</span><span class="k">재난</span><span class="n"><?= htmlspecialchars($f['stage1'] ?? '') ?></span></span>
        <span class="f"><span class="st">3</span><span class="k">연관</span><span class="n"><?= htmlspecialchars($f['stage2'] ?? '') ?></span></span>
        <span class="f fin"><span class="st">4</span><span class="k">점검 대상</span><span class="n"><?= htmlspecialchars($f['waiting'] ?? '') ?></span></span>
      </div>

      <div class="rk-s">
        <i style="width:<?= round(($f['collected'] - $f['stage1']) / $tot * 100, 2) ?>%;background:var(--line2)"></i>
        <i style="width:<?= round(($f['stage1'] - $f['stage2']) / $tot * 100, 2) ?>%;background:var(--navy-line)"></i>
        <i style="width:<?= round(($f['stage2'] - $f['waiting']) / $tot * 100, 2) ?>%;background:var(--navy-2)"></i>
        <i style="width:<?= round($f['waiting'] / $tot * 100, 2) ?>%;background:var(--navy)"></i>
      </div>

      <div class="rk-c">
        <span>재난 아님 <b><?= htmlspecialchars($f['dropped1'] ?? '') ?>건</b> · 무관 <b><?= ($f['stage1'] - $f['stage2']) ?>건</b></span>
        <span>
          <?php if ($f['stage2']): ?>대기 <b><?= htmlspecialchars($f['waiting'] ?? '') ?></b> · 제외 <b><?= htmlspecialchars($f['no'] ?? '') ?></b><?php endif; ?>
          <?php if ($f['accuracy'] !== null): ?> · 탐지 정확도 <b><?= (int)round($f['accuracy'] * 100) ?>%</b><?php endif; ?>
          <?php if ($f['oldestWaitingH'] >= 24): ?> · <span class="late">대기 <b><?= (int)round($f['oldestWaitingH']) ?>시간</b></span><?php endif; ?>
        </span>
      </div>
    <?php else: ?>
      <p class="rk-none">최근 24시간 수집된 신호가 없습니다.</p>
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php if (!$logged_in): ?>
  <div class="tri">
    <div class="r">
      <span class="k">공개</span>
      <span class="v">재난 신호와 <b>BP 지도</b>는 로그인 없이 봅니다</span>
      <a class="go" href="<?= url('/dashboard') ?>">Risk 현황 →</a>
    </div>
    <div class="r">
      <span class="k">관리자</span>
      <span class="v">요약·점검·명부는 소재전략만 봅니다</span>
      <a class="go" href="<?= url('/login?next=' . urlencode($_SERVER['REQUEST_URI'])) ?>">관리자 로그인 →</a>
    </div>
  </div>
<?php else: ?>

<?php
  $w = $summary ? $summary['watch'] : null;
  if ($w) { $w['generatedAt'] = $summary['generatedAt']; }
  $t = $summary ? $summary['triage'] : null;
  $im = $summary ? $summary['impact'] : null;
?>
  <div class="tri">
    <div class="r <?= ($w && !$w['ingestToday']) ? 'crit' : '' ?>">
      <span class="k">수집</span>
      <span class="v">
        <?php if (!$w): ?>—
        <?php elseif ($w['ingestToday']): ?>
          정상 수집 중 · 오늘(<?= htmlspecialchars($w['generatedAt'] ? substr($w['generatedAt'], 5, 5) : '') ?>)
          <b><?= htmlspecialchars($w['ingestRuns'] ?? '') ?>회</b> · <b><?= htmlspecialchars($w['ingestToday'] ?? '') ?>건</b>
          · 마지막 <?= htmlspecialchars(substr($w['lastIngestAt'] ?? '', 11, 5)) ?>
          <?php if ($w['serverCollect'] && $w['failingSources']): ?>
            <span class="warn-s">서버 수집도 켜져 있는데 <?= htmlspecialchars(implode('·', $w['failingSources'])) ?> 에 못 닿습니다 [9002]</span>
          <?php endif; ?>
        <?php elseif ($w['serverCollect'] && $w['failingSources']): ?>
          <b>[9002] <?= htmlspecialchars(implode('·', $w['failingSources'])) ?></b> 에 못 닿습니다 — 서버 수집을 꺼도 됩니다
        <?php else: ?>
          <b>[1001] 오늘 올라온 것이 없습니다</b> — Risk 현황을 열어 두면 브라우저가 모읍니다
        <?php endif; ?>
      </span>
      <a class="go" href="<?= url('/dashboard') ?>">Risk 현황 →</a>
    </div>
    <div class="r">
      <span class="k">재난</span>
      <span class="v">지난 24시간 <b><?= $t ? htmlspecialchars($t['new'] ?? '') : 0 ?>건</b><?php if ($t && $t['high']): ?> · 중대 <b><?= htmlspecialchars($t['high'] ?? '') ?>건</b><?php endif; ?></span>
      <a class="go" href="<?= url('/dashboard') ?>">Risk 현황 →</a>
    </div>
    <div class="r <?= ($im && $im['withBp']) ? 'warn' : '' ?>">
      <span class="k">연관</span>
      <span class="v">
        <?php if ($im && $im['withBp']): ?>우리 권역 <b><?= count($im['withBp']) ?>건</b> · BP <b><?= htmlspecialchars($im['bpTotal'] ?? '') ?>사</b> — 점검 대상
        <?php elseif ($im && $im['noImpact']): ?><?= count($im['noImpact']) ?>건 모두 우리 권역 밖
        <?php elseif ($im && $im['unknown']): ?>미판정 <b><?= count($im['unknown']) ?>건</b> — 권역 미확정
        <?php else: ?>연관 사건 없음<?php endif; ?>
      </span>
      <a class="go" href="<?= url('/dashboard') ?>">Risk 현황 →</a>
    </div>
  </div>

  <details class="fold wide" <?php if ($alerts_pending): ?>open<?php endif; ?>>
    <summary><span class="t">확인 대기</span>
      <span class="m"><?php if ($alerts_pending): ?><?= count($alerts_pending) ?>건 · 확인을 누를 때까지 사라지지 않습니다<?php else: ?>없습니다<?php endif; ?></span>
      <span class="x"></span></summary>
    <div class="fold-b">
      <?php foreach ($alerts_pending as $a): ?>
      <div class="li" data-alert="<?= htmlspecialchars($a['id'] ?? '') ?>">
        <span class="bg"><span class="badge <?= htmlspecialchars(['high'=>'hi','medium'=>'md','low'=>'lo'][$a['severity']] ?? 'lo') ?>"><?= htmlspecialchars(['high'=>'중대','medium'=>'주의','low'=>'참고'][$a['severity']] ?? '참고') ?></span>
          <span class="badge sys"><?= $a['kind'] == 'incident' ? '사건' : '시스템' ?></span></span>
        <span class="t"><?= htmlspecialchars($a['title'] ?? '') ?></span>
        <span class="d"><?= htmlspecialchars($a['detail'] ?? '') ?><?php if ($a['at']): ?> · <?= htmlspecialchars(str_replace('T', ' ', substr($a['at'], 5, 11))) ?><?php endif; ?></span>
        <?php if ($is_admin): ?><button type="button" class="ackbtn" data-ack="<?= htmlspecialchars($a['id'] ?? '') ?>">확인</button><?php endif; ?>
      </div>
      <?php endforeach; ?>
      <?php if (empty($alerts_pending)): ?>
      <p class="empty">확인하지 않은 이상이 없습니다.</p>
      <?php endif; ?>
      <?php if ($alerts_pending && $is_admin): ?><div class="acts">
        <button type="button" class="pri ackbtn" data-ack-all="1">모두 확인</button>
        <span class="ackmsg" id="ackMsg"></span></div><?php endif; ?>
    </div>
  </details>

  <div class="folds">
  <details class="fold">
    <summary><span class="t">점검 진행</span>
      <span class="m"><?php if ($stat_open_events): ?>진행 중 사건 <?= htmlspecialchars($stat_open_events ?? '') ?>건<?php else: ?>진행 중인 사건 없음<?php endif; ?></span>
      <span class="x"></span></summary>
    <div class="fold-b">
      <?php if ($now_line): ?><p class="empty" style="color:var(--ink-2)"><?= htmlspecialchars($now_line ?? '') ?></p>
      <?php else: ?><p class="empty">지금 점검할 사건이 없습니다.</p><?php endif; ?>
      <?php if ($summary && $summary['unclosed']): ?>
      <?php foreach (array_slice($summary['unclosed'], 0, 5) as $x): ?>
      <div class="li">
        <span class="bg"><span class="badge <?= htmlspecialchars(['high'=>'hi','medium'=>'md','low'=>'lo'][$x['severity']] ?? 'lo') ?>"><?= htmlspecialchars(['high'=>'중대','medium'=>'주의','low'=>'참고'][$x['severity']] ?? '참고') ?></span></span>
        <span class="t"><a href="<?= url('/inspect') ?>"><?= htmlspecialchars($x['title'] ?? '') ?></a></span>
        <span class="d"><?= htmlspecialchars($x['country'] ?? '') ?> <?= htmlspecialchars($x['admin1'] ?? '') ?> · <?= htmlspecialchars(substr($x['occurredAt'] ?? '', 0, 10)) ?> ·
          BP <?= $x['bpCount'] === null ? '미판정' : htmlspecialchars($x['bpCount'] . '사') ?></span>
      </div>
      <?php endforeach; ?><?php endif; ?>
      <div class="acts"><a href="<?= url('/inspect') ?>">점검 현황 →</a></div>
    </div>
  </details>

  <details class="fold">
    <summary><span class="t">BP 기준 정보</span>
      <span class="m"><?= htmlspecialchars($stat_companies ?? '') ?>사 · 사업장 <?= htmlspecialchars($stat_sites ?? '') ?> · <?= htmlspecialchars($stat_countries ?? '') ?>개국</span>
      <span class="x"></span></summary>
    <div class="fold-b">
      <div class="kv">
        <span>BP <b><?= htmlspecialchars($stat_companies ?? '') ?></b>사</span>
        <span>사업장 <b><?= htmlspecialchars($stat_sites ?? '') ?></b>곳</span>
        <span>국가 <b><?= htmlspecialchars($stat_countries ?? '') ?></b></span>
        <span>좌표 검증 <b><?= htmlspecialchars($stat_verified ?? '') ?></b>곳</span>
        <span>연락처 확보 <b><?= htmlspecialchars($stat_hotline_pct ?? '') ?></b></span>
      </div>
      <div class="acts">
        <a href="<?= url('/bp') ?>">사업장 →</a>
        <a href="<?= url('/bp') ?>?tab=hot">연락처 채우기 →</a>
        <a href="<?= url('/revision') ?>">변경 →</a>
      </div>
    </div>
  </details>

  <details class="fold">
    <summary><span class="t">누적</span>
      <span class="m">신호 스냅샷 <?= htmlspecialchars($stat_snapshots ?? '') ?> · 일간 보고서</span>
      <span class="x"></span></summary>
    <div class="fold-b">
      <div class="kv">
        <span>신호 스냅샷 <b><?= htmlspecialchars($stat_snapshots ?? '') ?></b></span>
        <?php if ($summary): ?><span>오늘 신호 <b><?= htmlspecialchars($summary['watch']['signalsToday'] ?? '') ?></b>건</span><?php endif; ?>
      </div>
      <div class="acts">
        <a href="<?= url('/dashboard') ?>">지난 신호 →</a>
        <a href="<?= url('/api/report/daily') ?>">오늘 일간 보고서 →</a>
        <a href="<?= url('/system') ?>">판정 기준 →</a>
      </div>
    </div>
  </details>
  </div>

<?php endif; ?>

  <div class="foot">
    BP <?= htmlspecialchars($stat_companies ?? '') ?>사 · 사업장 <?= htmlspecialchars($stat_sites ?? '') ?> · 좌표 검증 <?= htmlspecialchars($stat_verified ?? '') ?> ·
    연락처 확보 <?= htmlspecialchars($stat_hotline_pct ?? '') ?> · 신호 스냅샷 <?= htmlspecialchars($stat_snapshots ?? '') ?><br>
    기획·개발 <b>SK hynix 소재전략 이정훈 TL</b><br>
    mGSCS <b><?= htmlspecialchars($app_version ?? '') ?></b> · 최근 업데이트 <?= htmlspecialchars($updated_at ?? '') ?>
  </div>
</main>

<script>
// 확인(ack) — 예전에는 이 화면이 목록만 보여 주고 [확인하러 가기 →] 가 Risk 현황으로
// 보냈는데, 거기엔 누를 것이 없었다. 그래서 미확인 이상이 영영 쌓였다.
// 누르는 자리는 목록이 있는 곳이어야 한다.
(function(){
  var box = document.getElementById("ackMsg");
  function say(t){ if(box) box.textContent = t; }
  async function ack(ids, btn){
    if(btn) btn.disabled = true;
    try{
      var r = await fetch("<?= url('/api/alerts/ack') ?>", {
        method:"POST", headers:{"Content-Type":"application/json"},
        body: JSON.stringify({ids: ids})
      });
      var d = await r.json();
      if(!r.ok || !d.ok) throw new Error(d.error || ("서버 오류 " + r.status));
      ids.forEach(function(id){
        var row = document.querySelector('[data-alert="' + CSS.escape(id) + '"]');
        if(row){ row.classList.add("acked"); var b = row.querySelector("[data-ack]"); if(b) b.remove(); }
      });
      say("확인했습니다 — " + d.acked.length + "건. 새로고침하면 목록에서 빠집니다.");
    }catch(e){
      say("확인하지 못했습니다 — " + e.message);
      if(btn) btn.disabled = false;
    }
  }
  document.addEventListener("click", function(ev){
    var b = ev.target.closest("[data-ack], [data-ack-all]");
    if(!b) return;
    ev.preventDefault();
    if(b.hasAttribute("data-ack-all")){
      var ids = Array.prototype.map.call(
        document.querySelectorAll(".li[data-alert]:not(.acked)"),
        function(x){ return x.getAttribute("data-alert"); });
      if(ids.length) ack(ids, b);
    }else{
      ack([b.getAttribute("data-ack")], b);
    }
  });
})();
</script>
</body>
</html>
