<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<meta name="theme-color" content="#1D3A6E">
<meta name="format-detection" content="telephone=no">
<title>mGSCS · 감지 검토</title>
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='4' fill='%231d3a6e'/%3E%3Ctext x='16' y='22' font-size='17' font-family='sans-serif' font-weight='800' fill='white' text-anchor='middle'%3Em%3C/text%3E%3C/svg%3E">

<?php include __DIR__ . '/_shared/theme.php'; ?>
<style>

*{box-sizing:border-box}
html{-webkit-font-smoothing:antialiased}
body{margin:0;font-family:var(--font-sans);font-size:var(--fs-base);letter-spacing:-.01em;
     background:var(--bg);color:var(--ink);font-variant-numeric:tabular-nums}
a{color:var(--navy-2);text-decoration:none}
.wrap{max-width:var(--page-w);margin:0 auto;padding:24px 24px 60px}
.pg-h{display:flex;align-items:baseline;gap:12px;margin-bottom:20px;flex-wrap:wrap}
.pg-h h1{margin:0;font-size:var(--fs-xl);font-weight:800;letter-spacing:-.02em}
.pg-h .sub{font-size:var(--fs-sm);color:var(--ink-3)}

.sec{border:1px solid var(--line);border-radius:var(--r-lg);background:var(--surface);
     padding:var(--sp-5) var(--sp-6);margin-bottom:var(--sp-5)}
.sec-h{display:flex;align-items:baseline;gap:var(--sp-3);flex-wrap:wrap;
       margin-bottom:var(--sp-4)}
.sec-h .t{font-size:var(--fs-md);font-weight:800}
.sec-h .m{font-size:var(--fs-xs);color:var(--ink-3)}
.sec-h .cnt{font-size:var(--fs-xs);font-weight:700;color:var(--on-navy);
       background:var(--navy);border-radius:var(--r-sm);padding:1px 8px}
.sec-h .cnt.zero{background:var(--ink-3)}
.why{font-size:var(--fs-xs);color:var(--ink-3);margin:-8px 0 var(--sp-4);max-width:76ch;
     line-height:1.7}
.empty{font-size:var(--fs-sm);color:var(--ink-3);margin:0;padding:var(--sp-3) 0}

/* 결정 대기 */
.dec{border-top:1px solid var(--line-2);padding:var(--sp-4) 0}
.dec:first-of-type{border-top:0}
.dec .top{display:flex;align-items:baseline;gap:var(--sp-3);flex-wrap:wrap}
.dec .ttl{font-size:var(--fs-base);font-weight:700;flex:1;min-width:240px}
.dec .age{font-size:var(--fs-2xs);color:var(--ink-3)}
.dec .age.late{color:var(--amber);font-weight:700}
.dec .meta{margin-top:5px;font-size:var(--fs-xs);color:var(--ink-3);
     display:flex;gap:var(--sp-4);flex-wrap:wrap}
.dec .meta b{color:var(--ink-2);font-weight:700}
.dec .acts{margin-top:var(--sp-3);display:flex;gap:var(--sp-2);align-items:center;
     flex-wrap:wrap}
button{font:inherit;cursor:pointer;border-radius:var(--r-md);
     border:1px solid var(--line2);background:var(--card);color:var(--ink-2);
     padding:5px 12px;font-size:var(--fs-xs);font-weight:700}
button:hover{border-color:var(--navy-line);color:var(--navy-2)}
button.pri{background:var(--navy);border-color:var(--navy);color:var(--on-navy)}
button.pri:hover{background:var(--navy-hover);color:var(--on-navy)}
button:disabled{opacity:.5;cursor:default}
select{font:inherit;font-size:var(--fs-xs);padding:5px 8px;border-radius:var(--r-md);
     border:1px solid var(--line2);background:var(--card);color:var(--ink)}
.msg{font-size:var(--fs-xs);color:var(--ink-3)}
.msg.ok{color:var(--green)} .msg.err{color:var(--red)}

/* 개선 대기열 — D안(막대 + 비율) */
.q{display:grid;grid-template-columns:150px 1fr 128px;gap:var(--sp-3);
   align-items:center;margin-bottom:7px}
.q .k{font-size:var(--fs-xs);color:var(--ink-2);font-weight:700;text-align:right}
.q .bar{display:flex}
.q .bar i{display:block;height:20px;border-radius:var(--r-sm);background:var(--navy-2)}
.q .bar i.ok{background:var(--navy-line)}
.q .v{font-size:var(--fs-xs);color:var(--ink-3)}
.q .v b{color:var(--ink);font-weight:800}
.tgt{font-size:var(--fs-2xs);color:var(--ink-3);margin:-2px 0 var(--sp-3) 158px}
.tgt code{background:var(--surface-2);border:1px solid var(--line-2);
     border-radius:var(--r-sm);padding:0 4px;font-size:var(--fs-2xs)}

table{border-collapse:collapse;width:100%;font-size:var(--fs-sm)}
th,td{text-align:left;padding:7px 10px;border-bottom:1px solid var(--line-2)}
th{font-size:var(--fs-2xs);color:var(--ink-3);font-weight:700;letter-spacing:.03em}
td.n{text-align:right;font-variant-numeric:tabular-nums}
tr.hot td{color:var(--amber)}

.word{display:inline-flex;align-items:baseline;gap:5px;border:1px solid var(--line2);
     border-radius:var(--r-sm);padding:3px 9px;margin:0 6px 6px 0;font-size:var(--fs-xs)}
.word b{font-weight:700;color:var(--ink)}
.word span{color:var(--ink-3);font-size:var(--fs-2xs)}

.drop{border-top:1px solid var(--line-2);padding:9px 0;display:flex;gap:var(--sp-3);
     align-items:baseline;flex-wrap:wrap}
.drop:first-of-type{border-top:0}
.drop .t{flex:1;min-width:220px;font-size:var(--fs-sm)}
.drop .r{font-size:var(--fs-2xs);color:var(--ink-3)}
@media(max-width:760px){
  .q{grid-template-columns:100px 1fr 96px}
  .tgt{margin-left:0}
  .sec{padding:var(--sp-4)}
}

/* 매일 하는 일(결정 대기)만 펼쳐 둔다. 나머지 넷은 **되먹임을 고칠 때** 보는 것이라
   늘 펼쳐 두면 오늘 할 일이 그 아래로 밀린다. */
.sec-fold{border:1px solid var(--line);border-radius:var(--r-md);background:var(--card);
  margin-bottom:12px;}
.sec-fold>summary{list-style:none;cursor:pointer;display:flex;align-items:baseline;gap:9px;
  padding:12px 16px;font-size:var(--fs-sm);font-weight:700;color:var(--ink);
  min-height:44px;box-sizing:border-box;}
.sec-fold>summary::-webkit-details-marker{display:none}
.sec-fold>summary::before{content:"▸";color:var(--ink-3);font-size:var(--fs-2xs);}
.sec-fold[open]>summary::before{content:"▾"}
.sec-fold>summary:hover{background:var(--surface-2)}
.sec-fold[open]>summary{border-bottom:1px solid var(--line)}
.sf-hint{font-weight:400;color:var(--ink-3);font-size:var(--fs-xs);}
.sec-fold>.sec{border:0;margin:0;background:none;}
@media(max-width:640px){ .sf-hint{flex:1 0 100%} }
</style>
</head>
<body>
<?php $here = ''; $here_label = '감지 검토'; ?>
<?php include __DIR__ . '/_shared/nav.php'; ?>

<div class="wrap">
  <div class="pg-h">
    <h1>감지 검토</h1>
    <span class="sub">점검할지 정하고, 그 이유로 감지를 고친다.</span>
  </div>

  
  <section class="sec">
    <div class="sec-h"><span class="t">결정 대기</span>
      <span class="cnt zero" id="pendCnt">0</span>
      <span class="m">오래 묵은 것부터</span></div>
    <p class="why">연관까지 올라온 건입니다. <b>점검한다</b> 를 누르면 점검 현황으로 넘어가고,
      <b>안 한다</b> 는 이유를 함께 받습니다 — 그 이유가 감지를 고치는 유일한 재료입니다.</p>
    <div id="pendList"><p class="empty">불러오는 중…</p></div>
  </section>

  
  <details class="sec-fold">
  <summary>개선 대기열<span class="sf-hint">가장 자주 틀리는 규칙</span></summary>
  <section class="sec">
    <div class="sec-h"><span class="t">개선 대기열</span>
      <span class="m">가장 자주 틀리는 규칙 · 최근 30일</span></div>
    <p class="why">막대는 <b>오탐</b>(걸리지 말았어야 할 것)만 셉니다.
      “영향 경미”처럼 <b>제대로 걸렀는데 안 하기로 한 것</b>은 옅은 색으로 따로 두었습니다 —
      고칠 게 없는 건을 개선 거리로 세면 멀쩡한 규칙을 건드리게 됩니다.</p>
    <div id="queueBox"><p class="empty">불러오는 중…</p></div>
  </section>
</details>

  
  <details class="sec-fold">
  <summary>출처별 오탐<span class="sf-hint">규칙인가 피드인가</span></summary>
  <section class="sec">
    <div class="sec-h"><span class="t">출처별 오탐</span>
      <span class="m">규칙인가 피드인가</span></div>
    <p class="why">한 피드가 오탐의 절반을 만들고 있다면 고칠 곳은 규칙이 아니라 그 피드입니다.</p>
    <div id="srcBox"><p class="empty">불러오는 중…</p></div>
  </section>
</details>

  
  <details class="sec-fold">
  <summary>제외 낱말 후보<span class="sf-hint">자동 적용 없음</span></summary>
  <section class="sec">
    <div class="sec-h"><span class="t">제외 낱말 후보</span>
      <span class="m">자동 적용 없음</span></div>
    <p class="why">“재난 아님”으로 뺀 제목에는 잦은데 <b>점검한 건에는 한 번도 안 나온</b> 낱말입니다.
      양쪽에 다 나오는 낱말을 빼면 진짜 사건까지 같이 사라지므로 걸러 두었습니다.
      <b>사람이 확인하고 반영합니다 — 시스템이 스스로 고치지 않습니다.</b></p>
    <div id="wordBox"><p class="empty">불러오는 중…</p></div>
  </section>
</details>

  
  <details class="sec-fold">
  <summary>버린 것 표본<span class="sf-hint">안 울린 것</span></summary>
  <section class="sec">
    <div class="sec-h"><span class="t">버린 것 표본</span>
      <span class="m">최근 3일 · 무작위 아님(같은 표본이 유지됩니다)</span></div>
    <p class="why">안 울린 것은 아무도 보지 않습니다. 그래서 강제로 눈앞에 올립니다.
      <b>이건 걸렀으면 안 됐다</b> 를 누르면 반대 방향 되먹임으로 쌓입니다 —
      오탐만 보면 필터를 한쪽으로만 조이다 아무것도 안 걸리게 됩니다.</p>
    <div id="dropBox"><p class="empty">불러오는 중…</p></div>
  </section>
</details>
</div>

<script>

const $ = s => document.querySelector(s);
const esc = t => String(t == null ? "" : t).replace(/[&<>"]/g,
  c => ({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;"}[c]));
let REASONS = [];

async function get(u){
  const r = await fetch(u, {headers:{"Accept":"application/json"}});
  if(r.status === 401 || r.status === 403) return {ok:false, why:"로그인이 필요합니다"};
  try { return await r.json(); } catch(e){ return {ok:false, why:"응답을 읽지 못했습니다"}; }
}
async function post(u, body){
  const r = await fetch(u, {method:"POST", headers:{"Content-Type":"application/json"},
                            body: JSON.stringify(body)});
  try { return await r.json(); } catch(e){ return {ok:false, why:"응답을 읽지 못했습니다"}; }
}

/* ── 1. 결정 대기 ── */
async function loadPending(){
  const d = await get("api/signals/pending");
  const box = $("#pendList");
  if(!d.ok){ box.innerHTML = `<p class="empty">${esc(d.why||"불러오지 못했습니다")}</p>`; return; }
  $("#pendCnt").textContent = d.total;
  $("#pendCnt").className = "cnt" + (d.total ? "" : " zero");
  if(!d.items.length){ box.innerHTML = '<p class="empty">결정을 기다리는 건이 없습니다.</p>'; return; }

  const opts = REASONS.map(r => `<option value="${esc(r.code)}">${esc(r.label)}</option>`).join("");
  box.innerHTML = d.items.map(it => {
    const late = it.ageH >= 24;
    const mats = (it.materials||[]).join(" · ");
    const area = [(it.country||""), ((it.areas||[]).join(" · "))].filter(Boolean).join(" ");
    return `<div class="dec" data-id="${esc(it.id)}">
      <div class="top">
        <span class="ttl">${it.url ? `<a href="${esc(it.url)}" target="_blank" rel="noopener">${esc(it.title)}</a>` : esc(it.title)}</span>
        <span class="age${late?" late":""}">${it.ageH}시간 대기</span>
      </div>
      <div class="meta">
        ${area ? `<span>지역 <b>${esc(area)}</b></span>` : ""}
        ${mats ? `<span>소재 <b>${esc(mats)}</b></span>` : ""}
        ${it.bpMatchCount != null ? `<span>BP <b>${it.bpMatchCount}사</b></span>` : ""}
        ${it.gateSummary ? `<span>${esc(it.gateSummary)}</span>` : ""}
        ${it.source ? `<span>출처 ${esc(it.source)}</span>` : ""}
      </div>
      <div class="acts">
        <button class="pri" data-act="y">점검한다</button>
        <select data-role="reason"><option value="">안 한다 — 이유 고르기</option>${opts}</select>
        <button data-act="n" disabled>안 한다</button>
        <span class="msg"></span>
      </div>
    </div>`;
  }).join("");

  box.querySelectorAll(".dec").forEach(row => {
    const sel = row.querySelector('[data-role="reason"]');
    const nBtn = row.querySelector('[data-act="n"]');
    /* 이유를 고르기 전에는 '안 한다' 를 못 누른다 — 이유 없는 n 은 되먹임이 없다. */
    sel.addEventListener("change", () => { nBtn.disabled = !sel.value; });
    row.querySelector('[data-act="y"]').addEventListener("click",
      () => decide(row, "y", ""));
    nBtn.addEventListener("click", () => decide(row, "n", sel.value));
  });
}

async function decide(row, decision, reason){
  const msg = row.querySelector(".msg");
  row.querySelectorAll("button,select").forEach(b => b.disabled = true);
  msg.className = "msg"; msg.textContent = "저장 중…";
  const d = await post("api/signals/decide",
                       {id: row.dataset.id, decision, reason});
  if(!d.ok){
    msg.className = "msg err"; msg.textContent = d.why || "저장하지 못했습니다";
    row.querySelectorAll("button,select").forEach(b => b.disabled = false);
    return;
  }
  msg.className = "msg ok";
  msg.textContent = decision === "y" ? "점검 대상으로 넘겼습니다" : "제외했습니다";
  setTimeout(() => { loadPending(); loadFeedback(); }, 600);
}

/* ── 2·3·4. 되먹임 ── */
async function loadFeedback(){
  const q = await get("api/feedback/queue");
  const qb = $("#queueBox"), sb = $("#srcBox");
  if(!q.ok){
    qb.innerHTML = sb.innerHTML = `<p class="empty">${esc(q.why||"불러오지 못했습니다")}</p>`;
  } else if(!(q.items||[]).length){
    qb.innerHTML = '<p class="empty">아직 제외 기록이 없습니다 — 결정이 쌓이면 여기에 순위가 생깁니다.</p>';
    sb.innerHTML = '<p class="empty">아직 셀 것이 없습니다.</p>';
  } else {
    const top = Math.max(...(q.items||[]).map(i => i.count), 1);
    qb.innerHTML = (q.items||[]).map(i => `
      <div class="q">
        <span class="k">${esc(i.label)}</span>
        <span class="bar"><i class="${i.detected?"ok":""}" style="width:${(i.count/top*100).toFixed(1)}%"></i></span>
        <span class="v"><b>${i.count}</b>건 · ${esc(i.layerKo)}</span>
      </div>
      <div class="tgt">고칠 곳 <code>${esc(i.target)}</code> — ${esc(i.how)}</div>`).join("")
      + `<p class="empty">오탐 <b>${q.misfire}</b>건 · 제대로 걸렀으나 안 하기로 한 것 <b>${q.benign}</b>건`
      + (q.missed ? ` · 잘못 걸렀다고 표시된 것 <b>${q.missed}</b>건` : "") + `</p>`;

    sb.innerHTML = q.bySource && q.bySource.length
      ? `<table><tr><th>출처</th><th class="n">오탐</th><th class="n">결정</th><th class="n">오탐률</th></tr>`
        + q.bySource.map(s => `<tr class="${s.rate>=0.5?"hot":""}">
            <td>${esc(s.source)}</td><td class="n">${s.misfire}</td>
            <td class="n">${s.decided}</td>
            <td class="n">${s.rate==null?"—":Math.round(s.rate*100)+"%"}</td></tr>`).join("")
        + `</table>`
      : '<p class="empty">아직 출처별로 가를 만큼 기록이 없습니다.</p>';
  }

  const w = await get("api/feedback/suggest");
  const wb = $("#wordBox");
  if(!w.ok){ wb.innerHTML = `<p class="empty">${esc(w.why||"불러오지 못했습니다")}</p>`; }
  else if(!w.candidates.length){
    wb.innerHTML = `<p class="empty">아직 후보가 없습니다 — “재난 아님”으로 뺀 건이 ${w.basis.rejectedNotDisaster}건입니다. 쌓이면 낱말이 드러납니다.</p>`;
  } else {
    wb.innerHTML = w.candidates.map(c =>
      `<span class="word"><b>${esc(c.word)}</b><span>${c.inRejected}건 · ${Math.round(c.share*100)}%</span></span>`).join("")
      + `<p class="empty">${esc(w.note)}</p>`;
  }
}

/* ── 5. 버린 것 표본 ── */
async function loadDropped(){
  const d = await get("api/signals/dropped/sample");
  const box = $("#dropBox");
  if(!d.ok){ box.innerHTML = `<p class="empty">${esc(d.why||"불러오지 못했습니다")}</p>`; return; }
  if(!d.sample.length){
    box.innerHTML = '<p class="empty">최근 3일 동안 1차에서 뺀 것이 없습니다.</p>'; return;
  }
  box.innerHTML = `<p class="empty">전체 ${d.total}건 중 ${d.sampled}건을 봅니다.</p>`
    + d.sample.map(s => `<div class="drop" data-id="${esc(s.id)}">
        <span class="t">${s.url ? `<a href="${esc(s.url)}" target="_blank" rel="noopener">${esc(s.title||s.id)}</a>` : esc(s.title||s.id)}</span>
        <span class="r">${esc(s.why||"")}</span>
        <button data-act="misdrop">걸렀으면 안 됐다</button>
        <span class="msg"></span></div>`).join("");

  box.querySelectorAll('[data-act="misdrop"]').forEach(b => {
    b.addEventListener("click", async () => {
      const row = b.closest(".drop"), msg = row.querySelector(".msg");
      b.disabled = true; msg.className = "msg"; msg.textContent = "저장 중…";
      const r = await post("api/signals/dropped/misdrop",
        {id: row.dataset.id, title: row.querySelector(".t").textContent.trim()});
      if(r.ok){ msg.className = "msg ok"; msg.textContent = "기록했습니다"; loadFeedback(); }
      else { msg.className = "msg err"; msg.textContent = r.why || "저장하지 못했습니다"; b.disabled = false; }
    });
  });
}

(async function boot(){
  const r = await get("api/signals/reasons");
  REASONS = (r && r.reasons) ? r.reasons : [];
  loadPending(); loadFeedback(); loadDropped();
})();

</script>
</body>
</html>
