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
<title>mGSCS · 판정 기준</title>
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='4' fill='%231d3a6e'/%3E%3Ctext x='16' y='22' font-size='17' font-family='sans-serif' font-weight='800' fill='white' text-anchor='middle'%3Em%3C/text%3E%3C/svg%3E">

<?php include __DIR__ . '/_shared/theme.php'; ?>
<style>



*{box-sizing:border-box}
html{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;scroll-behavior:smooth}
@media (prefers-reduced-motion:reduce){ html{scroll-behavior:auto} }
body{margin:0;background:var(--bg);color:var(--ink);
     font-family:var(--font-sans);font-size:var(--fs-base);line-height:1.75;
     letter-spacing:-.011em;font-variant-numeric:tabular-nums}
a{color:var(--navy-2);text-decoration:none}
a:hover{text-decoration:underline}

/* ── 상단 ── */

.wrap{max-width:var(--read-w);margin:0 auto;padding:24px 24px 90px}

/* ── 표제 ── */
.pagehead{margin-bottom:30px}
.eyebrow{font-size:var(--fs-2xs);font-weight:700;letter-spacing:.14em;color:var(--navy-2);
         text-transform:uppercase;margin:10px 0 0}
h1.pagetitle{margin:9px 0 0;line-height:1.3}

/* ── 무엇을 알고 싶나 · 바로가기 ──────────────────────────────
     문서를 처음부터 읽는 사람은 드물다. 가장 많이 묻는 셋을
     목차보다 위에 둔다. 목차는 그 다음이다.
   ────────────────────────────────────────────────────────── */
.jump{display:flex;flex-wrap:wrap;align-items:baseline;gap:4px 16px;
      margin:0 0 20px;font-size:var(--fs-sm);color:var(--ink-3);line-height:1.9}
.jump .lbl{font-size:var(--fs-2xs);font-weight:700;letter-spacing:.02em;color:var(--ink-3)}
.jump .r{white-space:nowrap}
.jump .r a{color:var(--ink-2);font-weight:700}
.jump .r a:hover{color:var(--navy-2)}

/* ── 목차 ── */
.toc{border:1px solid var(--line);border-radius:var(--r-lg);box-shadow:var(--sh);
     padding:15px 18px;margin:0 0 40px;background:var(--surface)}
.toc h2{font-size:var(--fs-xs);margin:0 0 9px;color:var(--ink-3);font-weight:700;letter-spacing:.06em}
.toc ol{margin:0;padding-left:19px;columns:2;column-gap:26px}
.toc li{font-size:var(--fs-sm);margin:2px 0;break-inside:avoid}
.toc a{color:var(--ink-2)}

/* ── 절 ── */
section{margin:0 0 42px;scroll-margin-top:64px}
h2{font-size:var(--fs-lg);margin:0 0 6px;letter-spacing:-.028em;font-weight:800}
h2 .num{color:var(--ink-3);font-weight:600;margin-right:9px;font-size:var(--fs-md)}
.dek{color:var(--ink-3);font-size:var(--fs-sm);margin:0 0 17px}
h3{font-size:var(--fs-base);margin:24px 0 7px;font-weight:700;letter-spacing:-.02em}
p{margin:0 0 12px}
ul{margin:0 0 12px;padding-left:19px}
li{margin:3px 0}
b{font-weight:700}
code{font-family:var(--mono);font-size:.88em;background:var(--surface-2);
     padding:1px 5px;border-radius:var(--r-sm);color:var(--ink-2)}

/* ── 상자 : 강조바 없이 테두리 + 음영 ── */
.box{border:1px solid var(--line2);border-radius:var(--r-lg);box-shadow:var(--sh-2);
     background:var(--card);padding:15px 17px;margin:0 0 14px}
.box .t{font-size:var(--fs-sm);font-weight:700;margin-bottom:6px}
.box p:last-child,.box ul:last-child{margin-bottom:0}
.box.q{background:var(--surface)}

/* ── 표 ── */
.tw{overflow-x:auto;border:1px solid var(--line);border-radius:var(--r-lg);
    box-shadow:var(--sh);margin:0 0 14px;background:var(--card)}
table{border-collapse:collapse;width:100%;font-size:var(--fs-sm);min-width:420px}
th,td{padding:9px 13px;text-align:left;border-bottom:1px solid var(--line);vertical-align:top}
th{background:var(--surface);font-weight:700;color:var(--ink-2);
   font-size:var(--fs-xs);letter-spacing:.02em;white-space:nowrap}
tr:last-child td{border-bottom:0}
td.n{font-variant-numeric:tabular-nums;white-space:nowrap}

pre{background:var(--surface);border:1px solid var(--line);border-radius:var(--r-md);
    box-shadow:var(--sh);padding:14px 15px;overflow-x:auto;margin:0 0 14px;
    font-family:var(--mono);font-size:var(--fs-xs);line-height:1.8;color:var(--ink-2)}

.tag{display:inline-block;padding:1px 7px;border:1px solid var(--line2);border-radius:var(--r-sm);
     font-size:var(--fs-2xs);font-weight:700;color:var(--ink-2);background:var(--surface)}
.tag.no{color:var(--red);border-color:var(--hi-line)}
.tag.ok{color:var(--green);border-color:var(--ok-line)}

footer{border-top:1px solid var(--line);margin-top:52px;padding-top:18px;
       font-size:var(--fs-xs);color:var(--ink-3);line-height:1.8}
.conf{color:var(--red);font-weight:700}

@media (max-width:620px){
  .toc ol{columns:1}
  .wrap{padding:26px 17px 70px}
  h1{font-size:21px}
}


/* ── 시스템 개요 (Risk 현황에서 이관) ── */
.ov-hero{background:var(--navy);color:var(--on-navy);border-radius:var(--r-lg);padding:22px 24px;margin-bottom:16px;}
.ov-hero-tag{font-size:var(--fs-2xs);letter-spacing:1.4px;text-transform:uppercase;color:var(--on-navy-dim);font-weight:700;}
.ov-hero-title{font-size:var(--fs-xl);font-weight:800;margin-top:7px;letter-spacing:.2px;}
.ov-hero-sub{font-size:var(--fs-sm);color:var(--on-navy-dim);margin-top:8px;line-height:1.65;max-width:660px;}
.ov-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:12px;margin-bottom:16px;}
.ov-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--r-lg);padding:15px 17px;}
.ov-card h4{font-size:var(--fs-sm);font-weight:700;color:var(--navy);margin:0 0 7px;display:flex;align-items:center;gap:7px;}
.ov-card h4::before{content:"";width:4px;height:14px;background:var(--accent);border-radius:var(--r-sm);}
.ov-card p{font-size:var(--fs-xs);color:var(--ink-2);line-height:1.7;margin:0;overflow-wrap:anywhere;word-break:keep-all;}
.ov-card .ov-big{font-size:var(--fs-lg);font-weight:800;color:var(--navy);font-variant-numeric:tabular-nums;letter-spacing:.2px;}
.ov-flow{background:var(--surface);border:1px solid var(--line);border-radius:var(--r-lg);padding:16px 18px;margin-bottom:16px;}
.ov-flow-title{font-size:var(--fs-sm);font-weight:700;color:var(--ink);margin-bottom:12px;}
.ov-steps{display:flex;flex-wrap:wrap;align-items:stretch;gap:8px;}
.ov-step{flex:1;min-width:128px;background:var(--surface-2);border:1px solid var(--line);border-radius:var(--r-lg);padding:10px 12px;}
.ov-step .n{font-size:var(--fs-2xs);font-weight:800;color:var(--accent);letter-spacing:.6px;}
.ov-step .t{font-size:var(--fs-sm);font-weight:700;color:var(--ink);margin-top:3px;}
.ov-step .d{font-size:var(--fs-2xs);color:var(--ink-3);margin-top:3px;line-height:1.5;}
.ov-note{font-size:var(--fs-xs);color:var(--ink-2);line-height:1.75;background:var(--surface-2);border:1px solid var(--line);box-shadow:0 1px 2px rgba(26,31,41,.05);border-radius:var(--r-lg);padding:13px 16px;}
/* 아코디언은 .fold.card (theme.html) 로 통일했다 — 로컬 정의를 두지 않는다. */
details.fold.card{margin-bottom:8px}
details.fold.card > .fold-body p{margin-bottom:6px}
.ov-fine{font-size:var(--fs-2xs);color:var(--ink-3);line-height:1.65;}
.ov-judge{display:flex;flex-direction:column;gap:7px;margin:8px 0 2px;}
.ov-judge-row{display:flex;gap:10px;align-items:flex-start;background:var(--surface-2);border:1px solid var(--line);border-radius:var(--r-lg);padding:8px 11px;font-size:var(--fs-xs);line-height:1.7;}
.ov-judge-k{flex-shrink:0;font-weight:800;color:var(--navy);background:var(--accent-soft);border:1px solid var(--accent-line);border-radius:var(--r-sm);padding:1px 8px;font-size:var(--fs-2xs);margin-top:1px;}
.ov-kw{font-size:var(--fs-2xs);padding:2px 9px;border-radius:var(--r-lg);background:var(--surface-2);color:var(--ink-2);border:1px solid var(--line);}
.ov-k{font-size:var(--fs-2xs);letter-spacing:1.5px;text-transform:uppercase;color:var(--ink-3);margin-bottom:6px;}
.ov-t{font-size:var(--fs-base);font-weight:800;color:var(--ink);}
.ov-s{font-size:var(--fs-xs);color:var(--ink-2);margin-bottom:13px;}
.ov-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:13px;font-size:var(--fs-2xs);}
.ov-cell{background:var(--surface-2);border:1px solid var(--line);border-radius:var(--r-lg);padding:8px 11px;}
.ov-cell b{color:var(--ink);}
.ovmoved{margin-top:18px}
/* 12개 절이 끝나고 다른 층의 문서가 시작된다는 것을 여백으로 알린다. */
.sec-h{margin:64px 0 4px;padding-top:22px;border-top:1px solid var(--line)}
.sec-lead{margin:0 0 20px;font-size:var(--fs-sm);color:var(--ink-3)}
</style>
</head>
<body>

<?php $here = 'system'; ?><?php $here_label = '판정 기준'; ?>
<?php include __DIR__ . '/_shared/nav.php'; ?>

<div class="wrap">

<header class="pagehead">
  <div class="eyebrow">SK HYNIX 소재전략 · MATERIAL GLOBAL SCM CONTROL SYSTEM</div>
  <h1 class="pagetitle">판정 기준</h1>
  <p class="lead">세상의 모든 재난이 아니라 <b>우리 BP가 있는 곳의 일</b>만 고른다.</p>
</header>

<div class="refline jump">
  <span class="lbl">무엇을 알고 싶나</span>
  <span class="r"><a href="#s2">어떻게 판정하나</a></span>
  <span class="r"><a href="#s4">데이터는 어디서 오나</a></span>
  <span class="r"><a href="#s8">연락처는 어디까지 나가나</a></span>
</div>

<div class="toc">
  <h2>목차</h2>
  <ol>
    <li><a href="#funnel">RISK 감지 4단계</a></li>
    <li><a href="#s1">무엇을 푸는가</a></li>
    <li><a href="#s2">판정 구조</a></li>
    <li><a href="#s3">왜 반경이 아니라 행정구역인가</a></li>
    <li><a href="#s4">데이터</a></li>
    <li><a href="#s5">BP 정체성 정리</a></li>
    <li><a href="#s6">사업장 전수조사</a></li>
    <li><a href="#s7">경보 흐름</a></li>
    <li><a href="#s8">연락처 경계</a></li>
    <li><a href="#s9">사외·사내 역할과 이관</a></li>
    <li><a href="#s10">조용한 실패 금지</a></li>
    <li><a href="#s11">화면과 운영</a></li>
    <li><a href="#s12">현재 상태와 남은 일</a></li>
    <li><a href="#serr">오류 번호표</a></li>
  </ol>
</div>

<section id="funnel">
  <h2><span class="num">00</span>RISK 감지 4단계</h2>
  <p class="dek">숫자가 뜻하는 것</p>

  <p>화면마다 나오는 <b>수집 · 재난 · 연관 · 점검 대상</b> 네 숫자는
  같은 신호가 네 관문을 지나며 줄어든 것입니다. 읽으면 이렇게 됩니다 —
  <b>재난 14건 중 연관된 것이 5건, 그중 2건을 점검한다.</b></p>

  <table class="tbl">
    <thead><tr><th>단계</th><th>이름</th><th>뜻</th><th>누가 가르나</th></tr></thead>
    <tbody>
      <tr><td>1</td><td><b>수집</b></td><td>브라우저가 훑어 온 전부</td><td>기계</td></tr>
      <tr><td>2</td><td><b>재난</b></td><td>재난·재해로 볼 것 (범주·낱말 선별)</td><td>기계</td></tr>
      <tr><td>3</td><td><b>연관</b></td><td>우리 BP 가 그 권역에 있고 소재군이 노출됐다 (5게이트 통과)</td><td>기계</td></tr>
      <tr><td>4</td><td><b>점검 대상</b></td><td>소재전략이 점검하기로 <b>정한</b> 것</td><td>사람</td></tr>
    </tbody>
  </table>

  <h3>이름을 이렇게 고른 이유</h3>
  <ul>
    <li><b>'총' 이 아니라 '재난'</b> — '총' 은 수식어지 명사가 아니고, 무엇의 총계인지도
      말하지 않습니다. 그 단계가 묻는 것은 "이게 재난인가" 하나뿐입니다.</li>
    <li><b>'영향' 이 아니라 '연관'</b> — 게이트가 확인하는 것은 "우리 BP 가 그 권역에 있고
      소재군이 노출됐다" 까지입니다. <b>실제로 타격을 입었는지는 BP 에게 물어야</b> 압니다 —
      그게 점검의 존재 이유입니다. "영향 5건" 이라 써 놓고 그 5건에
      "귀사 영향 있으십니까" 하고 묻는 것은 앞뒤가 안 맞습니다.</li>
    <li><b>'점검' 이 아니라 '점검 대상'</b> — 점검을 마친 수가 아니라 하기로 <b>정한</b> 수입니다.
      실제 점검(양식·발송·회신)은 그 뒤에 따로 돕니다. 게다가 점검 현황 화면은
      <b>BP 단위</b>로 셉니다(대상 24사). 사건 1건이 BP 8~35사를 부르므로,
      같은 낱말을 쓰면 두 화면에 10배 넘게 다른 숫자가 뜹니다.</li>
  </ul>

  <h3>띠는 남은 것이 아니라 떨어져 나간 몫입니다</h3>
  <p>네 칸을 더하면 수집 전체가 됩니다. 그래야 띠가 거짓말을 안 합니다.
  막대 길이로 크기를 말하지 않는 이유는, 수집 315 대 점검 2 라
  정직한 척도로 그리면 <b>정작 결론인 뒷단계가 실선처럼 지워지기</b> 때문입니다.
  크기는 숫자가 지고, 띠는 비율의 인상만 집니다.</p>

  <h3>거짓말 금지 규칙</h3>
  <ul>
    <li>결정 0건이면 정확도를 <b>쓰지 않습니다</b>. 100% 로 그리지 않습니다.</li>
    <li>대기 중인 것을 맞은 것으로 세지 않습니다.</li>
    <li>모르는 이유 코드는 오탐 쪽으로 셉니다 — 지표는 나쁜 쪽으로 틀리는 편이 안전합니다.</li>
    <li>단계마다 탈락 수를 같이 적습니다. 남은 수만 적으면 <b>좁힌 척</b>이 됩니다.</li>
  </ul>

  <h3>4단계는 곧 되먹임입니다</h3>
  <p>4단계에서 <b>안 한다</b>를 고르면 <b>이유를 함께 받습니다</b>. 자유 서술이 아니라 코드로 받고,
  코드마다 어느 층의 어느 규칙을 고쳐야 하는지가 미리 못 박혀 있습니다.
  그리고 <b>오탐과 무해 정탐을 가릅니다</b> — "영향이 경미해서 안 함" 은 기계가 제대로 잡은
  것입니다. 이걸 실패로 세면 제대로 작동한 탐지기가 벌을 받고, 그런 지표는 곧 아무도
  안 믿습니다.</p>
</section>

<section id="s1">
  <h2><span class="num">01</span>무엇을 푸는가</h2>
  <p class="dek">문제 정의</p>
  <p>구마모토에서 지진이 났다고 합시다. 알아야 할 것은 진도가 아니라 <b>"우리 포토레지스트가 거기서 오는가"</b>입니다.
     그 연결을 사람이 매번 기억으로 잇는 것은 불가능합니다. 명부의 BP는 백수십 사, 사업장은 수백 곳 규모입니다 —
     현재 숫자는 <a href="<?= url('/bp') ?>">BP 기준 정보</a> 상단 집계가 정본입니다.</p>
  <p>그래서 이 시스템은 세 가지만 합니다.</p>
  <div class="tw"><table>
    <tr><th style="width:130px">단계</th><th>내용</th></tr>
    <tr><td><b>수집</b></td><td>공식 재난 정보를 상시 수집 — JMA · USGS · GDACS</td></tr>
    <tr><td><b>판정</b></td><td>우리 BP가 있는 행정구역에서 발생한 사건인지 판정</td></tr>
    <tr><td><b>연결</b></td><td>대상 BP 명단 통지 · 담당자 연락처까지 연결</td></tr>
  </table></div>
  <div class="box">
    <div class="t">하지 않는 것</div>
    <p>피해 규모를 예측하지 않습니다. 대응을 대신 결정하지 않습니다. BP 담당자에게 자동으로 연락하지 않습니다.
       <b>판단과 발송은 사람이 합니다.</b> 오탐 한 번이 회사 대 회사의 신뢰를 깎기 때문입니다.</p>
  </div>
</section>

<section id="s2">
  <h2><span class="num">02</span>판정 구조</h2>
  <p class="dek">거르는 순서가 곧 설계다</p>
  <pre>1. 시간창    lookback 6시간 밖이면 이미 지나간 일이다
2. 강도      진도·규모가 기준 미달이면 공급망에 닿지 않는다
3. 행정구역  사건의 도/시/현 후보군을 낸다
4. BP 매칭   그 안에 우리 BP가 없으면 우리 일이 아니다</pre>
  <p><b>4번이 핵심입니다.</b> 사건이 아무리 커도 우리 BP가 없으면 경보를 올리지 않습니다.
     그러지 않으면 매일 세계 어딘가의 지진으로 알림이 울리고, 사람들은 곧 무시합니다.</p>

  <h3>매칭은 두 겹이다</h3>
  <div class="tw"><table>
    <tr><th style="width:150px">유형</th><th>범위</th><th>쓰임</th></tr>
    <tr><td><b>동일 행정권역</b></td><td>사건이 난 도/시/현 안</td><td>1순위 점검 대상</td></tr>
    <tr><td><b>동일 광역권</b></td><td>같은 권역 — 규슈·오키나와, 영남권 등</td><td>근방 확대 조사 대상</td></tr>
  </table></div>
  <p>사건이 나면 근방을 어차피 다 조사하므로 광역권까지 후보에 넣습니다.
     구마모토 지진이면 규슈 전체를 봅니다.</p>

  <h3>좌표만 와도 행정구역을 복원한다</h3>
  <div class="tw"><table>
    <tr><th style="width:110px">소스</th><th>행정구역</th><th>위경도</th><th>비고</th></tr>
    <tr><td>JMA</td><td><span class="tag ok">있음</span></td><td>있음</td><td>도도부현·진도까지. 가장 정밀</td></tr>
    <tr><td>USGS</td><td><span class="tag no">없음</span></td><td>있음</td><td>한국 지진이 이 경로로 들어온다</td></tr>
    <tr><td>GDACS</td><td><span class="tag no">국가만</span></td><td>있음</td><td>태풍·홍수·화산·산불</td></tr>
  </table></div>
  <p>USGS·GDACS는 행정구역을 주지 않습니다. 복원하지 않으면 <b>한국 지진은 도/시 매칭이 아예 되지 않고
     국가 단위로 뭉개집니다.</b> 그래서 진앙 좌표로 도/현 후보군을 만듭니다.</p>
  <p>가장 가까운 하나가 아니라 <b>후보군</b>인 것이 중요합니다. 최근접만 취하면
     노토반도 지진이 <code>富山</code>, 경주 지진이 <code>울산</code>으로 가고 정작 <code>石川</code>·<code>경북</code>을 놓칩니다.
     행정구역은 넓고 진앙은 경계 근처에 자주 떨어집니다.</p>
  <pre>구마모토 M5.2  → 熊本 佐賀 長崎 大分 福岡 宮崎 鹿児島
노토반도 M6.0  → 富山 石川 長野 新潟
도호쿠  M7.0  → 宮城
포항    M5.4  → 경북 울산 대구 경남
경주    M5.8  → 울산 대구 부산 경북 경남</pre>
  <p>실제 사건 5건의 진앙 좌표로 고정해 테스트에 박아 두었습니다. 합성 좌표로는 경계 근처의 실패를 잡지 못합니다.</p>
</section>

<section id="s3">
  <h2><span class="num">03</span>왜 반경이 아니라 행정구역인가</h2>
  <p class="dek">정밀도의 상한을 정직하게 정한 결과</p>
  <p>"진앙에서 30km 이내" 같은 반경 판정은 그럴듯해 보이지만, 우리 데이터로는 <b>거짓말이 됩니다.</b>
     BP 좌표 대부분이 시(市) 중심점이라 실제 공장과 수십 km 어긋날 수 있기 때문입니다.</p>
  <div class="tw"><table>
    <tr><th style="width:170px">좌표 등급</th><th>근거</th><th>비중</th></tr>
    <tr><td><code>verified_site</code></td><td>실제 제조소를 확인한 좌표</td><td>극소수 — 사실상 전 site가 거리 계산에서 제외</td></tr>
    <tr><td><code>address_geocoded</code></td><td>주소 문자열 지오코딩 (±수 km)</td><td>다수</td></tr>
    <tr><td><code>city_centroid</code></td><td>조사로 시(市)까지만 확인 (±수십 km)</td><td>상당수</td></tr>
    <tr><td><code>prefecture_centroid</code></td><td>도/현 중심점 (±수십 km)</td><td>소수</td></tr>
  </table></div>
  <p>그래서 <b>판정은 행정구역으로 하고, 거리는 참고 표시로만</b> 씁니다.
     알림 본문에는 반드시 근거를 함께 적습니다.</p>
  <pre>진앙에서 약 15km (시 중심 기준(±수십 km))
진앙에서 15km (실측 좌표 기준)      ← verified_site일 때만 '약'을 뺀다</pre>
  <div class="box">
    <div class="t">이 결정이 없앤 일</div>
    <p>사실상 전 사업장에 걸린 좌표 실측 승급이 필수 작업에서 빠졌습니다. 반경 계산 자격을 얻으려던 작업이었는데,
       반경을 쓰지 않으니 할 이유가 없습니다. 대신 <b>연락처 확보가 1순위</b>가 됐습니다.</p>
  </div>
</section>

<section id="s4">
  <h2><span class="num">04</span>데이터</h2>
  <p class="dek">명부의 구조</p>
  <p>BP·사업장·소재군·국가별 <b>현재 규모는 <a href="<?= url('/bp') ?>">BP 기준 정보</a> 상단 집계가 정본</b>입니다 —
     이 문서에는 숫자를 박아 두지 않습니다. 문서는 낡아도 명부는 낡지 않게 하기 위해서입니다.</p>
  <div class="tw"><table>
    <tr><th>구분</th><th>분포</th></tr>
    <tr><td>국가</td><td>한국·일본에 집중 · 미국·중국·유럽·대만·동남아 순</td></tr>
    <tr><td>역할</td><td>생산이 과반 · 본사 · 영업소 · 연구소</td></tr>
    <tr><td>권역</td><td>수도권 · 간토 · 충청권 · 영남권 · 주부 · 간사이 · 화동권 · 규슈·오키나와 …</td></tr>
    <tr><td>등급제</td><td>품질·개발(QD)과 품질(Q)이 다수 · 나머지는 등급 없음 <span class="mut">(사업장 기준)</span></td></tr>
  </table></div>
  <p>US·EU·SG·MY·PH 사업장은 권역 축을 쓰지 않는 국가입니다. 빠뜨린 것이 아닙니다.</p>

  <h3>3계층</h3>
  <pre>bp_sites_source.json   사외 원본 · 직접 수정 금지
  + merge_rules.py     갈라진 BP 이름 병합
  + site_corrections   낡은 역할·지도 단위 좌표 보정
  + site_survey.json   사업장 전수조사 결과 (근거 URL 포함)
  + exploratory_bps    m-BP Grading 대상 중 미등록이던 BP
        ↓
bp_sites.json          산출물 · 직접 수정 금지
  + revision/ledger    사내 변경 원장 (append-only)
        ↓
bp_resolved.json       최종 · 매 실행 재생성</pre>
  <p>순수 함수입니다. 같은 입력이면 항상 같은 결과가 나옵니다.
     중간에 손편집하다 병합분을 날려먹은 뒤 이 구조로 바꿨습니다.</p>
</section>

<section id="s5">
  <h2><span class="num">05</span>BP 정체성 정리</h2>
  <p class="dek">같은 회사, 다른 이름을 하나로</p>
  <p>같은 회사가 조사 회차마다 다른 이름으로 들어와 있었습니다. <b>제외된 BP는 없습니다.</b>
     사라진 것은 회사가 아니라 이름 11개이고, 합치면서 오히려 사업장을 얻었습니다.</p>
  <div class="tw"><table>
    <tr><th style="width:210px">사라진 이름 → 정본</th><th>얻은 사업장</th></tr>
    <tr><td><code>Shin-Etsu</code> · <code>Shin-Etsu Chemical</code> → 신에츠</td><td>群馬 안나카 · 茨城 카시마 · 福井 타케후</td></tr>
    <tr><td><code>영문 표기</code> → 한글 정본명</td><td>같은 회사가 두 표기로</td></tr>
    <tr><td><code>Air X</code> → 에어엑스</td><td>본부·공장이 다른 표기</td></tr>
    <tr><td><code>자회사 (모회사)</code> → 모회사</td><td>괄호 표기</td></tr>
    <tr><td><code>구 사명 A</code> → 합병 후 사명</td><td>합병</td></tr>
    <tr><td><code>구 사명 B</code> → 인수 주체</td><td>인수</td></tr>
    <tr><td><code>구 사업부 C</code> → 분사 법인</td><td>분사</td></tr>
    <tr><td><code>JX Metals</code> · <code>Air Liquide</code> · <code>Kanto Denka</code></td><td>전부 기존 행과 중복 → 제거</td></tr>
  </table></div>
  <p>이어서 m-BP Grading 대상 145사 중 <b>등록조차 되어 있지 않던 61사</b>를 넣었습니다.
     포토마스크·글래스 기판·타깃·특수가스 소재군이 통째로 비어 있었습니다.</p>
  <div class="box q">
    <div class="t">대표 거점 1곳만 등록한 이유</div>
    <p>탐색군은 우리 제품과의 연결이 확정되지 않았습니다. 지금 사업장을 넓혀 봐야
       대상이 아니면 오탐만 늘고, 대상이면 도/현 단위로도 광역 경보는 걸립니다.
       <b>대상 여부를 사내에서 판정한 뒤</b> 넓히는 것이 순서입니다.</p>
  </div>
</section>

<section id="s6">
  <h2><span class="num">06</span>사업장 전수조사</h2>
  <p class="dek">97사 전수 · 근거를 행마다 남긴다</p>
  <p>회사 공식 사업장 안내를 근거로 97사를 전수조사했습니다. 조사해서 <b>추가할 것이 없던 경우도 기록</b>합니다 —
     남기지 않으면 "아직 안 본 곳"과 "봤는데 이대로가 맞는 곳"이 구분되지 않습니다.</p>
  <h3>드러난 누락은 한 가지 유형이었다</h3>
  <p>본사 국적으로만 등록돼 <b>현지 생산거점이 통째로 빠져</b> 있었습니다. 아래는 전부
     "그 지역에서 사건이 나도 잡히지 않던" 상태였습니다.</p>
  <pre>(모사 프로필 — 실제 누락 사례 목록은 사내 데이터라 꾸러미에서 뺐습니다. 유형은 세 가지였습니다: 국내 사업장이 본사 한 점으로만 등록 · 해외 BP 의 국내 공장 미등록 · 한 BP 의 복수 거점이 한 점으로 병합)</pre>
  <p>반대로 거점이 많은 일부 BP 와 국내 소형사는 기존 데이터가 정확했습니다.</p>
  <h3>좌표 보정</h3>
  <p>배경지도를 <code>land-110m</code> → <code>land-50m</code>으로 올렸더니 매립 산단이 제대로 육지로 잡히는 대신
     진짜 오차가 드러났습니다. 원좌표에서 <b>가장 가까운 육지로만</b> 옮겼습니다 — 최대 4.2km, 전부 원래 시(市) 안입니다.
     도/현 중심으로 스냅하면 평택이 60km 밀려 오히려 나빠집니다.</p>
</section>

<section id="s7">
  <h2><span class="num">07</span>경보 흐름</h2>
  <p class="dek">두 채널 · 상시 가동</p>
  <div class="tw"><table>
    <tr><th style="width:150px">경로</th><th>주기</th><th>내용</th></tr>
    <tr><td><b>시간별 경보</b></td><td>매시 07분</td><td>조건 충족 시에만. 목표 주 1~2건</td></tr>
    <tr><td><b>일일 브리핑</b></td><td>매일 07:00</td><td>이상이 없어도 반드시 발송 — 시스템 정상 동작 확인</td></tr>
  </table></div>
  <p>브리핑이 중요합니다. 경보는 조건 충족 시에만 나가므로 <b>파이프라인이 죽어도 아무도 모릅니다.</b>
     브리핑은 0건이어도 나가기 때문에, 오지 않으면 그 자체가 이상 신호입니다.</p>
  <div class="tw"><table>
    <tr><th style="width:150px">채널</th><th>수신</th><th>내용</th></tr>
    <tr><td>텔레그램</td><td>팀장 · 파트장 · 이광원 · 이정훈</td><td>사건 판정 + 대상 BP 명단</td></tr>
    <tr><td>메일</td><td>&lt;운영 담당 메일&gt;</td><td>동일 + BP 상세표</td></tr>
  </table></div>
  <p>수신 도메인은 <code>sk.com</code>으로 제한돼 있습니다. 그 밖의 주소는 발송 직전에 차단되고,
     설정 화면에서도 저장 자체가 거부됩니다. 오발송의 마지막 방어선입니다.</p>
</section>

<section id="s8">
  <h2><span class="num">08</span>연락처 경계</h2>
  <p class="dek">어디까지 자동이고 어디부터 사람인가</p>
  <div class="tw"><table>
    <tr><th style="width:110px">구간</th><th>하는 일</th><th style="width:80px">주체</th></tr>
    <tr><td>사외 알림</td><td>사건 판정 + 대상 BP 명단</td><td><span class="tag">자동</span></td></tr>
    <tr><td>사내 화면</td><td>연락처 연결 · 점검 요청 메일 양식 생성</td><td><span class="tag">자동</span></td></tr>
    <tr><td>발송</td><td>양식 검토 후 수동 발송</td><td><span class="tag no">사람</span></td></tr>
    <tr><td>회신</td><td>취합 → 점검 현황 · 후속 대응</td><td><span class="tag no">사람</span></td></tr>
  </table></div>
  <p>경보는 텔레그램·메일로 나가고 <b>둘 다 외부 인프라를 경유합니다.</b>
     연락처는 BP 담당자의 이름·전화·이메일이라 그 경로에 올릴 수 없습니다.
     연락처 자리에는 <code>사내 화면에서 확인</code>만 나갑니다.</p>
  <div class="box">
    <div class="t">데이터가 아니라 코드로 막았다</div>
    <p>종전에는 값이 있으면 그대로 실렸습니다. 안전했던 이유는 커밋된 매핑이 전량 공란이었기 때문이지
       설계 때문이 아니었습니다. 사내에서 채우는 순간 새어 나갑니다.
       테스트는 <b>일부러 연락처를 가득 채운 BP</b>를 넣고 그 값이 어디에도 나타나지 않는지 봅니다.</p>
  </div>
</section>

<section id="s9">
  <h2><span class="num">09</span>사외·사내 역할과 이관</h2>
  <p class="dek">사내에서 다시 만들지 않게 한다</p>
  <p>모든 것을 사외에서 짓고 하나씩 사내로 옮깁니다. 그래야 사내에서 처음부터 다시 만드는 일이 없습니다.
     지금 산출물 하나에 수집·판정·매핑·화면이 전부 들어 있습니다.</p>
  <div class="tw"><table>
    <tr><th style="width:66px">단계</th><th>사외</th><th>사내</th></tr>
    <tr><td><b>1 현재</b></td><td>수집 · 판정 · 매핑 · 알림</td><td>Risk 현황 · 점검 · 명부 편집 · 설정</td></tr>
    <tr><td><b>2</b></td><td>사건 피드(위치·좌표)만</td><td>매핑 · 연락처 · 점검 · 후속</td></tr>
    <tr><td><b>3</b></td><td>—</td><td>수집까지 내재화</td></tr>
  </table></div>
  <p>2단계로 가면 <b>대외비 BP 명단을 사외에 둘 이유가 없어집니다.</b> 지금의 노출 문제도 그때 자연히 풀립니다.</p>
  <h3>이미 옮겨진 것</h3>
  <pre>tools/detect_sources.py   JMA · USGS · GDACS 수집      표준 라이브러리만
tools/detect_rules.py     4단 판정 + 광역권 매칭        사외와 결과 일치
tools/geo_ref.py          좌표 → 도/현 후보군           실사건 6/6
tools/build_baseline.py   BP 데이터 재생성              순수 함수
tools/rebuild.py          리비전 원장 → resolved
tools/inspection.py       사건 → 대상 BP → 메일 초안     발송 코드 없음
tools/alert_config.py     알림 관리 + 오발송 방어</pre>
  <p>사내망은 아웃바운드가 막혀 있습니다. 실측에서 RSS 38개 중 9개만 응답했습니다.
     그래서 수집을 사내로 옮기는 것은 마지막이고, 그것도 허용된 범위 안에서만 합니다.</p>
</section>

<section id="s10">
  <h2><span class="num">10</span>조용한 실패 금지</h2>
  <p class="dek">이 시스템에서 가장 위험한 실패</p>
  <p>경보 0건은 정상일 수 있습니다. 문제는 <b>"잘 걸러낸 0"과 "파이프가 끊겨서 나온 0"이
     화면에서 똑같이 보인다</b>는 것입니다. 그래서 매 실행마다 단계별 처리 건수를 남깁니다.</p>
  <pre>단계별 처리 건수: 소스 3/3 · 수집 11 → BP매칭 5 → 판정통과 0 → 중복제거 0
기각사유: outside_lookback=5 local_intensity_below_threshold=4 no_region_matched_bp=2</pre>
  <p>여기에 <b>"이러면 시스템이 고장 난 것"</b>을 오류 조건으로 정의했습니다. 걸리면 실행을 실패로 떨어뜨립니다.</p>
  <div class="tw"><table>
    <tr><th style="width:150px">조건</th><th>등급</th></tr>
    <tr><td>소스 전멸</td><td><span class="tag no">fatal</span></td></tr>
    <tr><td>BP 매핑이 저정밀 데이터로 대체됨</td><td><span class="tag no">fatal</span></td></tr>
    <tr><td>소스는 정상인데 파싱 0건</td><td><span class="tag no">fatal</span></td></tr>
    <tr><td>수집 건수 ≠ 사유 집계</td><td><span class="tag no">fatal</span></td></tr>
    <tr><td>소스 일부 실패</td><td><span class="tag">warn</span></td></tr>
  </table></div>
  <p>가장 신경 쓴 테스트는 <b>"조용한 정상 밤은 아무 문제도 만들지 않는다"</b>입니다.
     오류 조건이 평상시에도 걸리면 아무도 보지 않게 됩니다.</p>
  <div class="box q">
    <div class="t">같은 성격으로 잡은 것들</div>
    <ul>
      <li><code>熊本県</code> ≠ <code>熊本</code> — 접미사 하나로 구마모토 지진이 통째로 기각되고 있었다</li>
      <li><code>한 일본 BP / 치바</code> — 참조표에 <code>지바</code>만 있어 좌표 검증에서 조용히 빠져 있었다</li>
      <li><code>Hitachi</code> ⊂ <code>Hitachinaka</code> — 다른 도시를 같은 곳으로 보고 신규 사업장을 버렸다</li>
      <li>bpId에서 한글이 지워져 <code>A화학</code>과 <code>B화학</code>이 같은 ID를 갖고 서로를 덮어썼다</li>
    </ul>
  </div>
</section>

<section id="s11">
  <h2><span class="num">11</span>화면과 운영</h2>
  <p class="dek">무엇을 어디서 하는가</p>

  <p>사건 대응은 세 걸음입니다 — <b>재난을 보고, 점검을 돌리고, 담당자를 찾습니다.</b>
     뒤의 셋(명부 편집·기준·설정)은 흐름이 아니라 그것을 받치는 축입니다.
     이름은 <b>[대상]+[상태·행위]</b> 명사형으로 맞췄습니다.
     '대시보드'(화면 종류)·'Hotline'(영어 기능명)·'데이터 리비전'(추상어)이 섞여 있으면
     같은 층위로 읽히지 않고, 어디서 무엇을 하는지가 매번 다시 헷갈립니다.</p>

  <div class="tw"><table>
    <tr><th style="width:62px">순서</th><th style="width:118px">화면</th><th>쓰임</th></tr>
    <tr><td>①</td>
        <td><a href="<?= url('/dashboard') ?>">Risk 현황</a></td>
        <td>신호·지도·판정·누적 보관. 지도는 권역 축으로 묶여 있어 사건 권역을 눌러 확대 가능.
            영향 가능 BP 이름을 누르면 BP 기준 정보의 그 회사로 이동</td></tr>
    <tr><td>②</td>
        <td><a href="<?= url('/inspect') ?>">점검</a></td>
        <td>사건을 골라 점검 대상을 만들고, 누구에게 보냈고 누가 답했고 무엇이 밀렸는지를
            한 자리에서 기록한다. 발송은 사람이 한다 — 양식 생성과 기록까지가 화면의 일</td></tr>
    <tr><td>③</td>
        <td><a href="<?= url('/bp') ?>">BP 기준 정보</a></td>
        <td>탭 둘이 같은 데이터를 본다.
            <b>사업장 보기</b> — 소재지·역할·좌표 근거·출처. 지도에서 점에 올리면 사업장 정보가 뜬다.
            <b>연락처 채우기</b> — 점검을 요청할 담당자·전화·이메일과 채운 비율.
            검색·소재군·등급 필터는 두 탭이 함께 쓴다 — 한쪽에서 좁혀 놓고 넘어가면 그대로 이어진다</td></tr>
    <tr><td>축</td>
        <td><a href="<?= url('/revision') ?>">명부 편집</a></td>
        <td>신규 사업장·좌표 승급·연락처·엑셀 일괄 등록. 원장에 사건으로 누적되고 이력 보존</td></tr>
    <tr><td>축</td>
        <td><a href="<?= url('/settings') ?>">⚙ 설정</a></td>
        <td>수신자·채널·허용 도메인·후보 마진. 허용 도메인 밖 주소는 저장 거부</td></tr>
    <tr><td>축</td><td>기준</td><td>이 문서</td></tr>
  </table></div>

  <h3>화면을 하나로 묶는 것</h3>
  <p>색·글씨·모서리·음영은 <code>_shared/theme.html</code>, 상단 메뉴는 <code>_shared/nav.html</code>,
     등급 표기는 <code>_shared/grade.html</code> 한 곳에서만 정의하고 모든 화면이 끌어다 씁니다.
     화면마다 따로 정의하면 같은 시스템으로 읽히지 않고, 고칠 때 한두 군데를 빼먹습니다.
     값을 바꾸려면 그 세 파일만 고칩니다.</p>

  <h3>사내에서는 명부가 중심이다</h3>
  <p>사외에서는 이 명부가 재난 판정을 돕는 참조표였습니다. 사내로 옮기면
     <b>순서가 뒤집힙니다</b> — 표준 BP 기준 정보와 담당자 연락처가 중심에 서고,
     재난 감지는 그것을 쓰는 여러 갈래 중 하나가 됩니다.
     그래서 명부는 화면 안에만 있으면 안 됩니다. 다른 시스템에서도 쓸 수 있어야 기준 명부입니다.</p>
  <div class="tw"><table>
    <tr><th style="width:190px">경로</th><th>쓰임</th><th style="width:80px">연락처</th></tr>
    <tr><td><code>api/export?fmt=csv</code></td>
        <td>엑셀에서 바로 열린다. 사람이 검토·배포할 때</td><td>제외</td></tr>
    <tr><td><code>api/export?fmt=csv&amp;hotline=1</code></td>
        <td>담당자까지 필요할 때. <b>대외비</b></td><td>포함</td></tr>
    <tr><td><code>api/export?fmt=json</code></td>
        <td>다른 사내 시스템이 읽어 갈 형식</td><td>제외</td></tr>
    <tr><td><code>api/resolved</code></td>
        <td>화면이 쓰는 원본 그대로</td><td>포함</td></tr>
  </table></div>
  <p>모두 로그인이 필요합니다. 기준 명부라면 <b>무엇이 아직 비었는지</b>가 1급 정보이므로,
     BP 기준 정보 맨 위에 채움 상태를 항상 띄웁니다 — 연락처·실측 좌표·조사 이력·근거 출처입니다.</p>

  <h3>사내 LLM Ops 붙이기</h3>
  <p>지금까지는 브라우저가 LLM을 직접 불렀습니다. 그러면 API 키가
     <code>localStorage</code>에 평문으로 남고, 사내망에서는 CORS에 걸립니다.
     그래서 <b>서버가 대신 부릅니다</b> — 키는 서버에만 있고, 누가 언제 분석했는지도 남습니다.</p>
  <div class="tw"><table>
    <tr><th style="width:150px">경로</th><th>하는 일</th></tr>
    <tr><td><code>POST api/llm</code></td>
        <td>프롬프트를 받아 사내 LLM Ops에 넘기고 본문만 돌려준다. 로그인 필요</td></tr>
    <tr><td><code>POST api/llm/test</code></td>
        <td><b>연결 시험</b> — 보낸 요청과 받은 응답을 그대로 보여준다</td></tr>
    <tr><td><code>GET api/llm/adapters</code></td>
        <td>붙일 수 있는 형식 목록</td></tr>
  </table></div>

  <h3>두 가지 길 — 어느 쪽이든 결과는 같다</h3>
  <p>사내에서 LLM을 쓰는 방법은 둘입니다. <b>같은 프롬프트·같은 판정</b>을 쓰므로
     어느 쪽으로 가든 결과가 갈리지 않습니다.</p>
  <div class="tw"><table>
    <tr><th style="width:96px">길</th><th>이럴 때</th><th>하는 일</th></tr>
    <tr><td><b>가. API</b></td>
        <td>사내 LLM Ops에 서버가 닿을 때</td>
        <td><code>llm.py</code>의 <code>_InHouse</code> 어댑터에 함수 두 개를 채운다.
            [⚙ 설정] → AI 분석에서 엔드포인트·모델·키를 넣고 <b>연결 시험</b>으로 맞춘다.
            이후 [영향 분석 실행]이 자동으로 돈다</td></tr>
    <tr><td><b>나. 파일</b></td>
        <td>API를 못 붙이거나, 사람이 한 번 보고 넘기고 싶을 때</td>
        <td>[분석 요청문 내보내기] → 사내 LLM에 붙여넣기 →
            [LLM 결과 붙여넣기]. 자동 분석과 똑같은 판정을 거친다</td></tr>
  </table></div>

  <h3>사외에서 사내로 — 사건 맞추기</h3>
  <p>사외(맥북·Actions)는 <b>자동으로 상시</b> 돌며 신호를 모으고 판정합니다.
     사내는 그 결과를 받아야 같은 사건을 봅니다. 사내망은 아웃바운드가 막혀 있어
     서버끼리 잇지 못하므로, <b>메일로 옮깁니다.</b></p>
  <pre>사외 (자동)                              사내 (사람)
수집 → 판정 → 누적
  [사건 꾸러미 내보내기]  ──메일──▶  [사건 꾸러미 받기]
                                        ↓
                                   사건 원장에 쌓임
                                   (같은 사건은 다시 안 쌓임)</pre>
  <div class="tw"><table>
    <tr><th style="width:150px">항목</th><th>내용</th></tr>
    <tr><td>꾸러미에 담기는 것</td>
        <td>사건 일시·국가·행정구역·제목·등급·소재군·<b>판정된 BP 명단</b>·근거 문장·출처</td></tr>
    <tr><td>왜 BP까지 싣나</td>
        <td>사내에서 다시 판정하면 명부 버전이 달라 결과가 달라질 수 있음.
            그러면 "같은 사건"으로 볼 수 없음</td></tr>
    <tr><td>중복</td>
        <td>날짜·국가·행정구역·제목으로 구분. 두 번 붙여넣어도 한 번만 저장</td></tr>
    <tr><td>저장 위치</td>
        <td><code>data/revision/incidents.json</code> — 명부(ledger)와 따로 둔다.
            명부는 "우리 BP가 어디 있나", 사건은 "무슨 일이 났나"다</td></tr>
    <tr><td>조회</td><td><code>GET api/incidents</code> · <code>?since=2026-08-01</code></td></tr>
  </table></div>

  <h3>어댑터 — 사내에서 채울 자리</h3>
  <p>사내 LLM Ops는 자체 형식입니다. 그래서 <code>tools/llm.py</code>의
     <code>_InHouse</code> 어댑터에 <b>함수 두 개만</b> 채우면 붙도록 자리를 비워 두었습니다.
     호출·타임아웃·재시도·오류 문구·감사 기록은 이미 처리합니다.</p>
  <pre>build(cfg, prompt, max_tokens, temperature) → (url, headers, body)
parse(cfg, status, payload)                 → 본문 문자열

cfg["base"]   엔드포인트      cfg["model"]  모델 이름
cfg["key"]    인증 키         cfg["extra"]  사내 규격 추가 필드(JSON)</pre>
  <p>본문을 못 찾으면 <b>반드시 예외를 던져야 합니다.</b> 조용히 빈 문자열을 돌려주면
     "분석은 돌았는데 결과가 없다" 가 되어 원인을 찾을 수 없습니다.</p>
  <p>맞춰 가는 방법은 <b>[⚙ 설정] → AI 분석 → 연결 시험</b>입니다.
     저장하지 않아도 입력한 값으로 한 번 불러 보고, 보낸 것과 받은 것을 그대로 보여줍니다.
     대상 모델은 qwen3.5 · glm5.2 · 자체 LLM 입니다.</p>
  <p>API 키는 설정 화면에서 넣지만 <b>화면으로 다시 내려오지 않습니다.</b>
     변경 이력에도 값 대신 <code>(설정됨)</code>만 남고, 사외 반출 블록에서도 빠집니다.</p>

  <h3>옛 주소</h3>
  <p><code>/roster</code>·<code>/hotline</code>은 <code>/bp</code>로 넘겨줍니다.
     이미 나간 링크와 북마크가 있어 끊지 않았습니다.</p>

  <h3>등급제 표기</h3>
  <p>사내 등급제는 두 갈래입니다 — <b>품질 등급제(Q)</b>와 <b>개발 등급제(D)</b>,
     둘 다 걸린 곳이 <b>QD</b>입니다. 화면에서는 <b>QD와 Q를 나누지 않습니다.</b>이 시스템이 묻는 것은 "사건이 나면 점검해야 하는가" 하나뿐인데,
     품질 등급제가 걸렸다면 QD 든 Q 든 답이 같기 때문입니다.</p>
  <div class="tw"><table>
    <tr><th style="width:120px">화면 표기</th><th style="width:96px">원래 코드</th><th>뜻</th></tr>
    <tr><td><b>품질 등급</b></td><td>QD · Q</td>
        <td>품질 등급제 대상 · <b>사건 발생 시 점검</b></td></tr>
    <tr><td>개발 등급</td><td>D</td>
        <td>개발 등급제만 적용 · 양산 납품 전 단계</td></tr>
    <tr><td>등급 없음</td><td>—</td>
        <td>등급제 미적용 · 탐색 대상 대부분이 해당</td></tr>
  </table></div>
  <p>나누면 점검 대상이 QD와 Q 두 무더기로 쪼개져, 정작 알아야 할
     <b>"몇 곳을 챙겨야 하는가"</b>가 흐려집니다. 실제로 QD·Q는 전부 점검 티어라
     구분해도 점검 여부가 달라지지 않습니다. 원래 코드는 데이터에 그대로 두고,
     배지에 마우스를 올리면 나옵니다 — 사내 시스템과 맞춰야 하기 때문입니다.</p>
  <p>연락처를 채우는 순서도 이 차례를 따릅니다 — 품질 등급이 걸린 곳부터입니다.</p>

  <h3>데이터를 고치는 방법</h3>
  <p><code>baseline</code>을 직접 고치지 마십시오. 다음 갱신 때 전부 덮입니다.
     <a href="<?= url('/revision') ?>">명부 편집</a> 화면에서 입력하면 원장에 사건으로 쌓이고, 재생성이 그것을 반영합니다.</p>
  <div class="tw"><table>
    <tr><th style="width:110px">규칙</th><th>내용</th></tr>
    <tr><td>ID 발급</td><td>사내는 <code>I-</code> 접두어만 · <code>X-</code>는 사외 전용이며 사내 발급 시 차단</td></tr>
    <tr><td>삭제</td><td>없음 · 폐쇄로 표시하고 이력 보존</td></tr>
    <tr><td>좌표</td><td>정밀도가 높은 값이 우선 · 낮은 등급으로 덮어쓰면 충돌로 기록</td></tr>
    <tr><td>근거</td><td><code>reason</code> 필수 · 변경 이유가 없으면 저장 거부</td></tr>
    <tr><td>작성자</td><td>서버가 쿠키에서 읽음 · 클라이언트는 사번을 보내지 않음</td></tr>
  </table></div>
</section>

<section id="s12">
  <h2 id="serr"><span class="num">13</span>오류 번호표</h2>
  <p class="lead">화면에 <b>[4010]</b> 처럼 네 자리 숫자가 붙습니다.
     사내망에서는 로그를 복사해 나올 수 없으니 <b>이 번호만 알려 주면</b> 됩니다 —
     문장은 옮겨 적다 보면 바뀌지만 숫자는 안 바뀝니다.
     앞자리가 구간이라 번호만 봐도 어디인지 압니다.</p>
  <div class="box" id="errBox">번호표를 불러오는 중…</div>
  <script>
  fetch("api/errors").then(function(r){ return r.ok ? r.json() : null; }).then(function(d){
    var el = document.getElementById("errBox");
    if(!d || !d.ok){ el.textContent = "번호표를 못 불러왔습니다."; return; }
    var by = {};
    d.rows.forEach(function(r){ (by[r.stage] = by[r.stage] || []).push(r); });
    var esc = function(s){ return String(s == null ? "" : s)
      .replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;"); };
    el.innerHTML = Object.keys(by).map(function(st){
      return '<h4 style="margin:14px 0 6px">' + esc(st) + '</h4>'
        + '<table class="tw"><thead><tr><th style="width:74px">번호</th>'
        + '<th>뜻</th><th>할 일</th></tr></thead><tbody>'
        + by[st].map(function(r){
            return '<tr><td><b>' + r.code + '</b></td><td>' + esc(r.means)
                 + '</td><td>' + esc(r.todo) + '</td></tr>'; }).join("")
        + '</tbody></table>';
    }).join("");
  }).catch(function(){
    document.getElementById("errBox").textContent = "번호표를 못 불러왔습니다.";
  });
  </script>

  <h2><span class="num">12</span>현재 상태와 남은 일</h2>
  <p class="dek"><?= htmlspecialchars($build_date ?? '') ?> 기준</p>
  <pre>데이터   전 BP·사업장 등록 · 근거 URL 전 건 보유
검증     바다 0 · 국가경계 이탈 0 · 도/현 반경 이탈 0
        admin1 미기입 0 · 행정구역 해석 실패 0 · recordId 중복 0
경보     텔레그램 · 메일 실제 발송 가동
탐지     Python 이식 완료 — 사외와 판정 일치</pre>
  <h3>남은 일</h3>
  <div class="tw"><table>
    <tr><th style="width:190px">항목</th><th>상태</th></tr>
    <tr><td>연락처 확보</td><td><b>1순위.</b> 최우선 공백 — 현황은 <a href="<?= url('/bp') ?>">BP 기준 정보</a>의 명부 상태에서 · 점검 대상부터 입력</td></tr>
    <tr><td>탐색 BP 대상 판정</td><td>사내 검토 후 대상만 사업장 확장</td></tr>
    <tr><td>메일 수신자 확대</td><td>소재 엔지니어 팀장·파트장 주소 대기</td></tr>
    <tr><td>사내 점검 화면</td><td><span class="tag ok">완료</span> <a href="<?= url('/inspect') ?>">점검 현황</a> 화면으로 구현됨(<code>/inspect</code>)</td></tr>
    <tr><td>GitHub Pages</td><td>전면 개선 완료 후 폐지</td></tr>
  </table></div>
  <div class="box">
    <div class="t">정밀도에 대한 정직한 고지</div>
    <p>이 시스템이 보장하는 것은 <b>"맞는 도/시 안에 있다"</b>까지입니다. 공장 단위 정밀도는 주장하지 않습니다.
       실측 확인된 좌표는 극소수라 사실상 전 site가 거리 계산에서 제외되고, 나머지는 주소·시·도 중심점입니다.
       알림 본문에 근거를 함께 적는 이유가 그것입니다.</p>
  </div>
</section>

<h2 class="sec-h">시스템 개요</h2>
<p class="sec-lead">Risk 현황에 있던 소개·이력 문서를 여기로 옮겼습니다 — 업무 화면은 신호와 판정만 답니다.</p>
      <section class="ovmoved">
        <div class="ov-hero">
          <div class="ov-hero-tag">mGSCS · material Global SCM Control System</div>
          <div class="ov-hero-title">반도체 소재 공급망 재난·위기 모니터링</div>
          <div class="ov-hero-sub">글로벌 재난·재해·지정학 리스크 신호를 상시 수집하고, 영향이 예상되는 이슈만 선별해 SK하이닉스 소재 공급망 점검으로 연결합니다.</div>
        </div>

        <div class="ov-grid">
          <div class="ov-card">
            <h4>목적</h4>
            <p>재난·재해·수출 통제 등 외부 리스크가 소재 조달에 미치는 영향을 <b>조기에 감지·점검</b>합니다. 기존 수동 점검 요청(BP 전수 메일)을 신호 선별로 대체해 불필요한 알림을 줄입니다.</p>
          </div>
          <div class="ov-card">
            <h4>감시 범위</h4>
            <p><span class="ov-big">24</span> 카테고리 · <span class="ov-big">250+</span> 키워드<br>한국어·영어·중국어·일본어 4개 언어 동시 매칭 · ISO BCP 기준과 정합성 유지.</p>
          </div>
          <div class="ov-card">
            <h4>공급망 맥락</h4>
            <p><b>8개 소재군</b> (Photo·Chemical·Slurry·Pad·Gas·Precursor·Wafer·Target) · <b>110 BP</b> · <b>4개 사이트</b> 기준으로 영향 가능성을 분류.</p>
          </div>
          <div class="ov-card">
            <h4>수집 소스</h4>
            <p>직접 소스(국내·글로벌 언론) + 현지어 검색 보강 + <b>공식 재난 경보</b>(GDACS·USGS·JMA·GVP). 최근 실측 기준 정상률 97~100%.</p>
          </div>
        </div>

        <div class="ov-flow">
          <div class="ov-flow-title">작동 흐름</div>
          <div class="ov-steps">
            <div class="ov-step"><div class="n">STEP 1</div><div class="t">신호 수집</div><div class="d">직접 소스 + 현지어 검색 + 공식 경보</div></div>
            <div class="ov-step"><div class="n">STEP 2</div><div class="t">키워드 필터</div><div class="d">4개 언어 250+ 키워드 1차 선별</div></div>
            <div class="ov-step"><div class="n">STEP 3</div><div class="t">AI 이중 검증</div><div class="d">관련성·신뢰도·중복 검증</div></div>
            <div class="ov-step"><div class="n">STEP 4</div><div class="t">공급망 영향 분석</div><div class="d">소재군·BP·사이트 기준 영향 분류</div></div>
            <div class="ov-step"><div class="n">STEP 5</div><div class="t">보고서</div><div class="d">조회 전용 · 요약 보고서</div></div>
          </div>
        </div>

        <div class="ov-note">
          <b>운영 원칙</b> — mGSCS는 <b>고정밀·저빈도</b> 알림을 지향합니다. 점검 1건이 110개 BP 전수 확인으로 이어질 수 있어, 대부분의 신호는 모니터링으로 유지하고 극소수만 엔지니어 검토 후보로 분류합니다. <b>현재는 조회 전용</b>이며, 메일 발송은 비용·운영 승인 후 선택적으로 활성화합니다. 접근은 Cloudflare(@sk.com 인증)로 제한하고, 저장소에는 공개 뉴스 메타데이터만 남깁니다.
        </div>

        <div class="ov-flow-title" style="margin:22px 0 9px;">상세 안내 <span style="font-size:10px;font-weight:500;color:var(--ink-3);">· 항목을 펼쳐 확인하세요</span></div>

        <details class="fold card" open>
          <summary>이 시스템은 무엇인가</summary>
          <div class="fold-body">
            <p>mGSCS는 글로벌 재난·위기 뉴스를 실시간 수집하고, AI 이중 검증을 거쳐 반도체 소재 공급망에 미치는 영향을 자동 분석하는 통합 모니터링 시스템입니다.</p>
            <p>기존 mGSCS 재난 점검 절차(BP 대상 수동 점검 요청)를 자동화하여, 이슈 감지 → 영향 분석 → 보고서 배포까지의 소요 시간을 단축합니다. 단순 뉴스 알림와 달리 <b>소재 공급망 맥락</b>에서 영향을 분류하고 <b>희소·고정밀</b> 신호만 부각합니다.</p>
          </div>
        </details>

        <details class="fold card">
          <summary>판정 체계 — 신호를 어떻게 거르나</summary>
          <div class="fold-body">
            <p>수집한 신호를 그대로 쌓지 않고 <b>3개 축</b>으로 분리해, "지금 봐야 하는가?"를 소재 담당자가 즉시 판단하도록 설계했습니다.</p>
            <div class="ov-judge">
              <div class="ov-judge-row"><span class="ov-judge-k">선별</span><span>모든 신호는 기본 <b>모니터링(조회 전용)</b>. 5단계 기준(① 지역 관련성 ② 소재군 관련성 ③ 공식·교차 확인 ④ 실제 공급 차질 정황 ⑤ 복수 출처)를 <b>모두</b> 통과한 극소수만 <b>엔지니어 검토 후보</b>로 승격합니다.</span></div>
              <div class="ov-judge-row"><span class="ov-judge-k">신뢰도</span><span><b>공식</b>(정부·기관 경보) · <b>교차 확인</b>(복수 매체 일치) · <b>추정</b>(단일·미확인) — 출처의 확실성을 한눈에 표시합니다.</span></div>
              <div class="ov-judge-row"><span class="ov-judge-k">교차검증</span><span>같은 사건을 보도한 <b>고유 출처 수</b>. 많을수록 사실 확인 수준이 높습니다.</span></div>
            </div>
            <p style="margin-top:8px;">→ 세 축을 분리하면 "커 보이지만 우리와 무관한 사건"과 "작아 보이지만 실제 공급 영향이 있는 사건"을 구분할 수 있습니다.</p>
          </div>
        </details>

        <details class="fold card">
          <summary>수집 소스 — 직접 언론 + 현지어 검색 보강(2단계)</summary>
          <div class="fold-body">
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:5px;">
              <div style="background:var(--surface-2);border:1px solid var(--line);border-radius:var(--r-md);padding:7px 10px;"><b style="color:var(--navy-2);">🇰🇷 국내 직접 소스</b><br><span style="font-size:10.5px;">JTBC·조선·한경·매경·전자신문·경향(+연합·KBS·MBC·SBS·YTN 방송사는 브라우저 전용)</span></div>
              <div style="background:var(--surface-2);border:1px solid var(--line);border-radius:var(--r-md);padding:7px 10px;"><b style="color:var(--navy-2);">🌐 글로벌 직접 소스</b><br><span style="font-size:10.5px;">BBC·CNN·Guardian·Al Jazeera·DW·CNBC·SCMP·Global Times·NHK·Yahoo!JP·MEE</span></div>
              <div style="background:var(--accent-soft);border:1px solid var(--navy-line);border-radius:var(--r-md);padding:7px 10px;grid-column:1 / -1;"><b style="color:var(--navy-2);">🛰 공식 재난 경보(공신력 있는 다중 재해 정보원)</b><br><span style="font-size:10.5px;">GDACS(전 지구 다중 재해 영향등급) · USGS(지진 M4.5+) · JMA(일본 기상청 지진·화산) · GVP(스미소니언 주간 화산)</span></div>
              <div style="background:var(--accent-soft);border:1px solid var(--navy-line);border-radius:var(--r-md);padding:7px 10px;grid-column:1 / -1;"><b style="color:var(--navy-2);">🔎 Google News 현지어 검색 보강(안정 소스 · 최근 5~7일)</b><br><span style="font-size:10.5px;">국내재난·폭발사고·산업화재·공급망·수출 통제(ko) · 일본재난(ja) · 중국공급(zh-CN) · 대만반도체(zh-TW) · 글로벌리스크(en)</span></div>
            </div>
            <p class="ov-fine" style="margin-top:7px;">※ 장기 차단·폐지된 소스(Reuters·CGTN·DIGITIMES·닛케이·TBS 등)는 제거하고 현지어 검색·공식 경보로 대체했습니다. 실행 환경 측정 기준 서버 측 정상률은 약 97~100%입니다. 국내 방송사는 데이터센터 IP가 차단되어 브라우저 전용으로 처리합니다.</p>
          </div>
        </details>

        <details class="fold card">
          <summary>카테고리·키워드 체계 (24개 카테고리 · 250+ 키워드 · ISO BCP 참고)</summary>
          <div class="fold-body">
            <p style="margin-bottom:7px;">EM-DAT·UNDRR-ISC HIP(센다이 프레임워크 지원용 위험 분류)를 <b>참고해</b> 정리한 <b>위험 유형 20종 + 공급망 위기(결과) + 지역 3</b> = 24 카테고리. <span class="ov-fine">※ '8 클러스터'는 보고 가독성용 개념 분류이며 표준과 1:1 매핑은 아닙니다.</span></p>
            <div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:8px;align-items:center;">
              <span class="ov-kw">🌍 지진</span><span class="ov-kw">🌊 쓰나미</span><span class="ov-kw">🌋 화산</span><span class="ov-kw">🌀 태풍</span><span class="ov-kw">🌧️ 홍수</span><span class="ov-kw">💧 가뭄</span><span class="ov-kw">🌡️ 폭염</span><span class="ov-kw">❄️ 한파</span><span class="ov-kw">🔥 화재</span><span class="ov-kw">💥 폭발</span><span class="ov-kw">🦺 산업 안전</span><span class="ov-kw">⚡ 정전</span><span class="ov-kw">🛡️ 사이버</span><span class="ov-kw">🦠 감염병</span><span class="ov-kw">✊ 파업</span><span class="ov-kw">⚔️ 전쟁</span><span class="ov-kw">🚫 무역 규제</span><span class="ov-kw">⚓ 해상 봉쇄</span><span class="ov-kw">💱 환율</span><span class="ov-kw">📉 부도</span><span class="ov-kw">🔗 공급망 위기</span>
              <span style="font-size:10px;color:var(--ink-3);margin:0 2px;">· 지역축:</span><span class="ov-kw">🇺🇦 우크라이나</span><span class="ov-kw">🇮🇱 이스라엘</span><span class="ov-kw">🇮🇷 이란</span>
            </div>
            <p>4개 언어 키워드를 동시에 매칭합니다. 자연재해(지진·쓰나미·화산·태풍·홍수·가뭄·폭염·한파), 기술·인프라(화재·폭발·산업 안전·정전·사이버), 생물(감염병), 사회·지정학(파업·전쟁·무역 규제·해상 봉쇄), 경제(환율·부도), J-Risk(2019) 소재명(불화수소·포토레지스트·불화폴리이미드), 핵심 원자재(갈륨·게르마늄·안티몬·황산·네온·헬륨)를 포함합니다. <span class="ov-fine">※ 신규 8종(쓰나미·가뭄·폭염·한파·정전·사이버·감염병·부도)은 대만 가뭄·텍사스 한파·COVID·후쿠시마 등 실제 소재 공급 중단 사례를 반영 — 상세: <b>docs/BCP_DISASTER_TAXONOMY.md</b></span></p>
          </div>
        </details>

        <details class="fold card">
          <summary>공급망 영향 분석 (SCM Impact)</summary>
          <div class="fold-body">
            <p>AI가 수집한 뉴스를 SK하이닉스 소재 공급망 맥락(8개 소재군, 110개 협력사(BP), 4개 사이트)에서 분석해, 실질적 영향이 예상되는 이슈만 선별합니다. <span class="ov-fine">※ BP=협력사(Business Partner)</span></p>
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:6px;margin-top:6px;">
              <div style="background:var(--surface-2);border:1px solid var(--line);border-radius:var(--r-md);padding:7px 9px;font-size:10.5px;"><b>8개 소재군:</b> Photo(감광) · Chemical(약액) · Slurry(슬러리) · Pad(패드) · Gas(특수가스) · Precursor(전구체) · Wafer(웨이퍼) · Target(타깃)</div>
              <div style="background:var(--surface-2);border:1px solid var(--line);border-radius:var(--r-md);padding:7px 9px;font-size:10.5px;"><b>조달 지역:</b>일본(Photo·Gas) · 한국(Chemical·Slurry·Pad) · 중국(Chemical·황산) · 미국·유럽(Gas·Target) · 중동(해상물류)</div>
            </div>
          </div>
        </details>

        <details class="fold card">
          <summary>보고서 · 배포</summary>
          <div class="fold-body">
            <p>매일 서버가 자동으로 신호를 <b>수집·저장(스냅샷)</b>합니다. <b>AI 분석·보고서 생성은 앱에서</b> 수행하며 결과는 <b>조회 전용</b>입니다. 메일 발송은 비용 검토 후 수신 동의 시에만 활성화됩니다(기본 비활성).</p>
            <p>보고서 구조는 <b>핵심 요약</b>(위험 수준 + 핵심 이슈 3줄) → <b>상세</b>(공급망 영향 → 긴급 → 주의 → 모니터링)입니다. HTML 저장 또는 메일 본문 텍스트 복사로 공유합니다.</p>
          </div>
        </details>

        <details class="fold card">
          <summary>개선 이력(v1.0 → <?= htmlspecialchars($app_version ?? '') ?>)</summary>
          <div class="fold-body">
            <table style="width:100%;border-collapse:collapse;font-size:11px;">
              <thead><tr style="background:var(--line-2);"><th style="padding:5px 8px;text-align:left;font-weight:600;color:var(--ink-3);border-bottom:2px solid var(--line);">버전</th><th style="padding:5px 8px;text-align:left;font-weight:600;color:var(--ink-3);border-bottom:2px solid var(--line);">개선 내용</th></tr></thead>
              <tbody>
                <tr><td style="padding:5px 8px;border-bottom:1px solid var(--line-2);font-weight:600;white-space:nowrap;">v1.0</td><td style="padding:5px 8px;border-bottom:1px solid var(--line-2);">초기 구축 — RSS 31개 소스, 12개 카테고리, AI 이중 검증, 뉴스 누적</td></tr>
                <tr><td style="padding:5px 8px;border-bottom:1px solid var(--line-2);font-weight:600;white-space:nowrap;">v2.x</td><td style="padding:5px 8px;border-bottom:1px solid var(--line-2);">보고서 생성·공급망 영향 분석, 카테고리 12→15개 확장, J-Risk(2019) 소재 키워드 60+ 보강, 소스 감사·확장, 폭발·현지어 검색 보강</td></tr>
                <tr><td style="padding:5px 8px;border-bottom:1px solid var(--line-2);font-weight:600;white-space:nowrap;">v3.0</td><td style="padding:5px 8px;border-bottom:1px solid var(--line-2);"><b>전면 재설계</b> — 네이비 톤 콘솔 UI, 크롤러 소스 정비(현지어 검색·공식 경보로 권역 대체), 선별·신뢰도·교차 검증 분리, 한국어 문구·용어 정비, 시스템 개요 통합</td></tr>
                <tr><td style="padding:5px 8px;border-bottom:1px solid var(--line-2);font-weight:600;white-space:nowrap;">v3.1</td><td style="padding:5px 8px;border-bottom:1px solid var(--line-2);"><b>기능·표준·견고성 강화</b> — 누적 보관 탭(검색·필터·CSV/JSON·서버 스냅샷 병합), 30일 추세 <b>변곡점 근거 상세 조회</b>(카드 클릭→그날 헤드라인), <b>ISO BCP 참고 24 카테고리</b>(쓰나미·가뭄·폭염·한파·정전·사이버·감염병·부도 신규), '기타' 불필요 신호 정리, 무인 모니터링 타이머·중복 저장 등 <b>버그 16건 수정</b></td></tr><tr><td style="padding:5px 8px;font-weight:600;white-space:nowrap;color:var(--navy);"><?= htmlspecialchars($app_version ?? '') ?></td><td style="padding:5px 8px;"><b>단일 시스템으로 통합</b> — 대시보드 단독에서 <b>단일 시스템</b>으로(대문 · Risk 현황 · BP 기준 정보 · 정보 관리 · 알림 관리 · 시스템 기준). BP 158사 372 사업장 전수조사, 판정 축을 반경에서 <b>행정구역+광역권</b>으로 전환, 판정 엔진 Python 이식(사외와 결과 일치), 테마·상단바 단일 정의, 지도 툴팁·목록 연동·통합 검색, 등급제 표기를 우리말로(QD→품질·개발)</td></tr>
              </tbody>
            </table>
          </div>
        </details>

        <div class="ov-fine" style="margin:16px 2px 0;text-align:center;line-height:1.8;">기획·개발 <b>이정훈 TL</b> · SK hynix 소재전략<br><span style="color:var(--ink-3);opacity:.8;">mGSCS <?= htmlspecialchars($app_version ?? '') ?> · 최근 업데이트 <?= htmlspecialchars($updated_at ?? '') ?></span></div>
      </section>

      <!-- 재난·BP 세계지도 (지진 실좌표 · 뉴스 빈도 coarse · BP 사이트) -->

<?php include __DIR__ . '/_shared/foot.php'; ?>

</div>
</body>
</html>
