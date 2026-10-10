<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<meta name="theme-color" content="#1D3A6E">
<meta name="format-detection" content="telephone=no">
<title>mGSCS · 점검 현황</title>
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='4' fill='%231d3a6e'/%3E%3Ctext x='16' y='22' font-size='17' font-family='sans-serif' font-weight='800' fill='white' text-anchor='middle'%3Em%3C/text%3E%3C/svg%3E">

<?php include __DIR__ . '/_shared/theme.php'; ?>
<style>

*{box-sizing:border-box}
html{-webkit-font-smoothing:antialiased}
body{margin:0;background:var(--bg);color:var(--ink);font-family:var(--font-sans);
     line-height:1.65;letter-spacing:-.011em;font-variant-numeric:tabular-nums}
a{color:var(--navy-2);text-decoration:none}
.wrap{max-width:var(--page-w);margin:0 auto;padding:24px 24px 80px}
h2{font-size:var(--fs-md);margin:26px 0 10px;letter-spacing:-.02em}
h2 small{font-weight:400;color:var(--ink-3);font-size:var(--fs-xs);margin-left:8px}
.hint{font-size:var(--fs-xs);color:var(--ink-3)}
textarea{width:100%;font-family:var(--mono);font-size:var(--fs-2xs);border:1px solid var(--line);
         border-radius:var(--r-md);background:var(--card);color:var(--ink);padding:10px;resize:vertical}
.msg{font-size:var(--fs-xs);margin-top:6px;min-height:16px}
.msg.ok{color:var(--ok)} .msg.err{color:var(--red)}

/* 사건 목록 */
.ev-list{display:flex;flex-direction:column;gap:6px}
.ev{display:flex;gap:10px;align-items:center;padding:10px 14px;border:1px solid var(--line);
    border-radius:var(--r-lg);background:var(--card);cursor:pointer}
.ev:hover{border-color:var(--navy-2)}
.ev.on{border-color:var(--navy);box-shadow:0 0 0 1px var(--navy)}
.ev .t{font-weight:600;font-size:var(--fs-sm);flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.ev .meta{font-size:var(--fs-2xs);color:var(--ink-3);white-space:nowrap}

/* BP 카드 큐 */
.bp-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(330px,1fr));gap:10px}
/* ── status 표 ────────────────────────────────────────────────
   한 줄이 한 BP 의 처음부터 끝까지. 칸마다 다른 질문에 답한다 —
   보냈나 · 답이 왔나 · 이상이 있었나 · 끝났나. */
.ins-t{width:100%;border-collapse:collapse;font-size:var(--fs-sm);margin-top:4px;table-layout:auto}
.ins-t th{text-align:left;font-size:var(--fs-2xs);font-weight:700;color:var(--ink-3);
  padding:0 10px 7px 0;border-bottom:1px solid var(--line);white-space:nowrap}
.ins-t td{padding:9px 10px 9px 0;border-bottom:1px solid var(--line-2);vertical-align:middle}
.ins-t tr.over td{background:var(--hi-bg)}
.ins-t .nm{font-weight:700;color:var(--ink);cursor:pointer;white-space:nowrap}
.ins-t .w{width:22%}
.ins-t .memo{color:var(--ink-2);display:block;overflow:hidden;text-overflow:ellipsis;
  white-space:nowrap;max-width:22ch}
.ins-t .act{text-align:right;white-space:nowrap;width:1%}
.ins-t td.act .btn{margin-left:4px}
/* 칸 값 — 색만으로 가르지 않는다. 글자가 곧 값이다. */
.ins-t .c{display:inline-block;font-weight:700;color:var(--ink-3);white-space:nowrap}
.ins-t td:nth-child(2),.ins-t td:nth-child(3),.ins-t td:nth-child(5){white-space:nowrap}
/* 이상 칸 — 값·소통·재고가 한 줄에 눕는다. 쌓이면 줄 높이가 세 배가 된다. */
.ins-t td:nth-child(4){white-space:nowrap;min-width:13rem}
.ins-t th:nth-child(1){min-width:7rem}
.ins-t .c.ok{color:var(--ok,#2E7D32)}
.ins-t .c.warn{color:var(--navy-2)}
.ins-t .c.no{color:var(--hi)}
.ins-t .hint{font-weight:400;color:var(--ink-3);font-size:var(--fs-2xs)}
.ins-t .who-r td{padding:0;border:0}
.ins-t .who{display:none;padding:0 0 10px 0}
.ins-t .who.open{display:flex;flex-wrap:wrap;gap:6px 18px}
@media (max-width:900px){
  .ins-t thead{display:none}
  .ins-t td{display:block;padding:2px 0;border:0}
  .ins-t tr{display:block;padding:10px 0;border-bottom:1px solid var(--line)}
  .ins-t .act{text-align:left}
  .ins-t .w{width:auto}
}
.bp-card{border:1px solid var(--line);border-radius:var(--r-lg);background:var(--card);padding:12px 14px}
.bp-card.miss{border-style:dashed;opacity:.85}
.bp-card.overdue{border-color:var(--hi);background:var(--hi-bg)}
.bp-hd{display:flex;align-items:center;gap:8px;margin-bottom:4px;flex-wrap:wrap}
.bp-hd .nm{font-weight:700;font-size:var(--fs-base);cursor:pointer}
.bp-hd .st{margin-left:auto}
.bp-sub{font-size:var(--fs-2xs);color:var(--ink-3);margin-bottom:8px}
.who{font-size:var(--fs-xs);background:var(--surface-2);border-radius:var(--r-md);padding:8px 10px;margin:6px 0;display:none}
.who.open{display:block}
.who .p{font-family:var(--mono);font-size:var(--fs-xs)}
.who .p b{font-family:var(--font-sans)}
.bp-acts{display:flex;gap:8px;align-items:center;margin-top:10px}
.bp-acts .waiting{font-size:var(--fs-xs);color:var(--ink-3)}
.bp-acts .waiting.done{color:var(--ok)}
.phone-big{font-family:var(--mono);font-size:var(--fs-base);font-weight:700;color:var(--hi);margin-top:6px}

/* 결론이 숫자보다 위 — 이 사건에서 지금 할 일 한 줄 */
.verdict{display:flex;align-items:baseline;gap:11px;flex-wrap:wrap;margin:0 0 14px;
   padding:13px 16px;border:1px solid var(--line);border-radius:var(--r-md);background:var(--surface)}
.verdict.hot{border-color:var(--hi-line);background:var(--hi-bg)}
.verdict .v{font-size:var(--fs-md);font-weight:800;color:var(--ink)}
.verdict.hot .v{color:var(--hi)}
.verdict .m{font-size:var(--fs-xs);color:var(--ink-3)}
.verdict:empty{display:none}

/* 빈 상태 — "없습니다"로 끝내지 않는다 */
.empty{font-size:var(--fs-sm);color:var(--ink-3);display:flex;gap:10px;align-items:center;flex-wrap:wrap}
.empty b{color:var(--ink-2)}

.box{border:1px solid var(--line);border-radius:var(--r-lg);background:var(--card);padding:14px 16px;margin:10px 0}
.tl{font-size:var(--fs-xs);font-family:var(--mono);line-height:1.9;max-height:260px;overflow:auto}
.tl .op{display:inline-block;min-width:64px;font-weight:700}

</style>
</head>
<body>
<?php $here = 'inspect'; ?><?php $here_label = '점검 현황'; ?>
<?php include __DIR__ . '/_shared/nav.php'; ?>
<div class="wrap">

<header class="pagehead">
  <h1 class="pagetitle">점검 현황</h1>
  <p class="lead">누구에게 보냈고, 누가 답했고, 무엇이 밀렸나.</p>
  <!-- 행동을 부르는 숫자 둘. 이 둘만 크다. -->
  <div class="statbar act">
    <div class="stat" id="stRepliedWrap"><span class="v" id="stReplied">—</span><span class="l">회신</span></div>
    <div class="stat" id="stOverWrap"><span class="v" id="stOver">—</span><span class="l">기한 초과</span></div>
  </div>
  <!-- 나머지는 참고 숫자 한 줄. -->
  <div class="refline">
    <span class="r">열린 사건 <b id="stOpen">—</b></span>
    <span class="r">대상 BP <b id="stBp">—</b></span>
    <span class="r">발송 <b id="stSent">—</b></span>
    <span class="r">반송 <b id="stBounced">—</b></span>
    <span class="r">발송→첫회신 <b id="stLat">—</b></span>
  </div>
</header>

<h2 id="open">열린 사건 <small>사건을 고르면 아래에 점검 큐가 뜹니다</small></h2>
<div class="ev-list" id="evList"><span class="hint">불러오는 중…</span></div>

<!-- 4단계 관문 — 결정이 안 난 건은 점검 목록에 섞이지 않는다.
     섞으면 "아직 아무도 안 본 것" 이 "점검해도 되는 것" 처럼 보인다. -->
<div id="decBar" hidden></div>

<details class="fold" id="dismissFold" hidden style="margin-top:10px">
  <summary onclick="touchClosed()">제외한 사건 <span class="fold-hint">제외 — 이유와 함께</span><span class="fold-n"></span></summary>
  <div class="fold-body"><div class="ev-list" id="dismissList"></div></div>
</details>

<details class="fold" id="closedFold" hidden style="margin-top:10px">
  <summary onclick="touchClosed()">닫힌 사건 <span class="fold-hint">마무리한 사건 · 열람용</span><span class="fold-n"></span></summary>
  <div class="fold-body"><div class="ev-list" id="closedList"></div></div>
</details>

<div id="detail" style="display:none">
  <h2 id="evTitle"></h2>
  <div class="verdict" id="evVerdict"></div>
  <div style="display:flex;gap:8px;flex-wrap:wrap;margin:8px 0 14px">
    <button class="btn pri" onclick="makeDrafts()">점검 대상·메일 초안 만들기</button>
    <button class="btn" onclick="copyNotice()">현황 공지 복사</button>
    <button class="btn" onclick="addNote()">메모 남기기</button>
  </div>
  <div class="msg" id="topMsg"></div>

  <div class="bp-grid" id="bpGrid"></div>

  <h2 id="reply">회신 붙여넣기 <small>BP 회신 메일 본문을 그대로. 점검번호·협력사로 자동 매칭해 서버에 기록합니다</small></h2>
  <div class="box">
    <textarea id="replyBox" rows="6" placeholder="회신 블록(===== mGSCS 회신 =====)이 포함된 본문을 붙여넣으세요. 여러 BP 회신을 한 번에 붙여도 됩니다."></textarea>
    <div style="display:flex;gap:8px;align-items:center;margin-top:8px">
      <button class="btn pri" onclick="parseReplies()">파싱해서 기록</button>
      <span class="msg" id="replyMsg"></span>
    </div>
  </div>

  <div class="whynot" id="whyNot" style="display:none"></div>

  <details class="fold" id="tlFold" style="margin-top:22px">
    <summary>누적 기록 <span class="fold-hint">서버 원장 그대로 · 지워지지 않는다</span><span class="fold-n"></span></summary>
    <div class="fold-body"><div class="tl" id="timeline"></div></div>
  </details>

  <details class="fold" id="wdFold">
    <summary>사건 마무리 <span class="fold-hint">캠페인 봉인 · 사건 닫기</span></summary>
    <div class="fold-body">
      <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
        <span class="hint">사건 수명 종료 —</span>
        <button class="btn" onclick="sealCampaign()">캠페인 봉인</button>
        <button class="btn" onclick="toggleEvent()" id="evToggleBtn">사건 닫기</button>
      </div>
    </div>
  </details>

  <div class="nextstep">
    회신을 다 기록했으면 — <b>현황 공지 복사</b>로 알리거나, <b>BP 기준 정보</b>에서 빈 연락처를 채웁니다.
    <a class="go" href="<?= url('/bp') ?>?tab=hot">BP 기준 정보 →</a>
  </div>
</div>

<details class="fold" style="margin-top:30px">
  <summary>옛 브라우저 기록 이관 <span class="fold-hint">1회용 · 옛 회신 기록을 서버로</span></summary>
  <div class="fold-body">
    <div class="hint" style="margin-bottom:8px">
      Risk 현황 화면을 쓰던 브라우저에서 F12 → 콘솔에
      <code style="font-family:var(--mono)">copy(localStorage.getItem("mgscs_inspect_replies"))</code>
      입력 후 여기 붙여넣기. 같은 것을 두 번 붙여도 한 번만 쌓입니다.
    </div>
    <textarea id="migrateBox" rows="3" placeholder='[{"bp":"...","s1":"X",...}]'></textarea>
    <div style="display:flex;gap:8px;align-items:center;margin-top:8px">
      <button class="btn" onclick="migrateLegacy()">서버로 이관</button>
      <span class="msg" id="migrateMsg"></span>
    </div>
  </div>
</details>

</div>
<?php include __DIR__ . '/_shared/foot.php'; ?>

<style>
.whynot{margin-top:14px;border:1px solid var(--line);border-radius:var(--r-sm);
  background:var(--surface-2);padding:10px 14px;}
.whynot .wn-h{font-size:var(--fs-xs);font-weight:700;color:var(--ink-3);
  letter-spacing:.04em;margin-bottom:6px;}
.whynot .wn-r{display:flex;align-items:baseline;gap:10px;padding:3px 0;
  font-size:var(--fs-sm);}
.whynot .wn-n{font-weight:700;font-variant-numeric:tabular-nums;min-width:2.2em;
  text-align:right;color:var(--ink);}
.whynot .wn-l{color:var(--ink-2);}
.whynot .wn-x{margin-left:auto;color:var(--ink-3);font-size:var(--fs-xs);}
@media (max-width:640px){ .whynot .wn-x{display:none} }
</style>
<script>

"use strict";
const API = "api/";
// 기한(시간). ⚙설정(점검 기준)에서 정한다 — 서버가 렌더 시 넣어준다. 초과는 이진 판정.
const SLA_HOURS = <?= htmlspecialchars($sla_hours ?? '') ?>;
const BP_URL = "<?= url('/bp') ?>";

// 이 화면은 소재 밖(제조·구매 등)에도 열려 있다. 어디까지 보이나 —
//   BP 이름 · 사업장 · 진행 상태  → 누구나
//   담당자 이름 · 전화 · 메일     → 소재전략만
// 서버가 애초에 연락처를 안 보낸다(/api/incidents/<id>/targets). 여기서는 화면만 맞춘다.
const IS_ADMIN = <?= json_encode($is_admin) ?>;

const DASH_URL = "<?= url('/dashboard') ?>";

let EVENTS = [], CUR = null, DRAFTS = {}, RECORDS = [];
let SUMS = {};   // 사건별 진행 요약 캐시 {bp, replied, over}
let CLOSED_TOUCHED = false;   // 닫힌 사건 상자는 사람이 열기 전엔 접혀 있는다
function touchClosed(){ CLOSED_TOUCHED = true; }

// 접힌 채로 숨긴 것이 몇 건인지는 배지로 알린다. 여기 상자들은 "지난 일"이라
// 자동으로 펼치지 않는다 — theme 의 foldSync 대신 배지만 채우는 판을 쓴다.
function foldN(id, count, word){
  const d = document.getElementById(id); if(!d) return;
  const b = d.querySelector(".fold-n");
  if(b) b.textContent = count ? (count + (word || "건")) : "";
  d.classList.toggle("on", !!count);
}

const $ = s => document.querySelector(s);
const esc = s => String(s ?? "").replace(/[&<>"']/g, c => ({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"}[c]));
const jfetch = (u, opt) => fetch(u, opt).then(async r => {
  const d = await r.json().catch(() => ({}));
  if (!r.ok || d.ok === false) throw new Error(d.error || ("서버 오류 " + r.status));
  return d;
});
const post = (u, body) => jfetch(u, {method:"POST", headers:{"Content-Type":"application/json"}, body:JSON.stringify(body)});
function say(id, text, ok){ const el=$(id); el.textContent=text; el.className="msg "+(ok?"ok":"err");
  if(ok) setTimeout(()=>{ if(el.textContent===text) el.textContent=""; }, 4000); }

// 클립보드 — 기본 전달 수단. (.eml 은 사내 Outlook 검증 뒤 추가)
async function toClip(text, msgId, label){
  try{ await navigator.clipboard.writeText(text); say(msgId, label+" 복사됨 — 메일에 붙여넣으세요", true); }
  catch(_){ // http 환경 폴백
    const ta=document.createElement("textarea"); ta.value=text; document.body.appendChild(ta);
    ta.select(); document.execCommand("copy"); ta.remove();
    say(msgId, label+" 복사됨 — 메일에 붙여넣으세요", true);
  }
}

// 심각도·상태 pill 정본 (theme 문법)
const SEV = {high:["sev-high","높음"], medium:["sev-med","중간"], low:["sev-low","낮음"]};
const OPPILL = {draft:["ins-pending","초안"], sent:["ins-sent","발송함"], replied:["ins-replied","회신"],
                closed:["ins-closed","종결"], skipped:["no","해당없음"], bounced:["warn","반송"]};

// ── 사건 목록 ──
async function loadEvents(){
  const d = await jfetch(API + "incidents");
  EVENTS = (d.incidents || []).slice().reverse();   // 최신 위
  EVENTS.sort((a,b)=> (a.status==="closed") - (b.status==="closed"));
  $("#stOpen").textContent = EVENTS.filter(
    e => e.status !== "closed" && e.status !== "dismissed" && gateOk(e)).length;
  renderEvents();
  // 열린 사건 행에 진행 요약 pill — 캐시 미보유분만 프리페치
  const need = EVENTS.filter(e => e.status !== "closed" && e.status !== "dismissed"
                                  && gateOk(e) && !(e.id in SUMS)).slice(0, 12);
  if(need.length){
    await Promise.all(need.map(async e => {
      try{ SUMS[e.id] = sumFromHistory(await jfetch(API + "inspection/history?eventId=" + encodeURIComponent(e.id))); }
      catch(_){ SUMS[e.id] = null; }
    }));
    renderEvents();
  }
}
function sumFromHistory(h){
  const recs = h.records || [], sum = h.summary || {};
  const st = {};
  for(const r of recs){
    if(!r.bpName) continue;
    const o = st[r.bpName] = st[r.bpName] || {op:null, sentTs:null};
    o.op = r.op; if(r.op === "sent") o.sentTs = r.ts;
  }
  return {bp: sum.bp || 0, replied: sum.replied || 0,
          over: Object.values(st).filter(isOverdue).length};
}
function evHtml(e){
  const id = esc(e.id);
  const sv = SEV[e.severity] || ["sev-med", esc(e.severity || "-")];
  const s = SUMS[e.id];
  return `<div class="ev ${CUR===e.id?"on":""}" onclick="selectEvent('${id}')">
    <span class="pill ${sv[0]}">${sv[1]}</span>
    <span class="pill ${e.certainty==="confirmed"?"st-conf":"st-unconf"}">${e.certainty==="confirmed"?"확정":"미확인"}</span>
    <span class="t">${esc(e.title)}</span>
    ${s && s.bp ? `<span class="pill mono">회신 ${s.replied}/${s.bp}</span>` : ""}
    ${s && s.over ? `<span class="pill due-over">초과 ${s.over}</span>` : ""}
    <span class="meta">${esc(e.country||"")} ${esc(e.admin1||"")} · ${esc(String(e.occurredAt||"").slice(0,16).replace("T"," "))}</span>
    ${e.status==="closed"?'<span class="pill ins-closed">닫힘</span>':""}
  </div>`;
}
// 열린 사건만 첫 화면에. 닫힌 사건은 접어 둔다 — 지난 일은 지금 할 일이 아니다.
/* 4단계 관문을 통과했나.
   decision 이 undefined 면 관문이 없는 사건이다(손으로 들여온 것) — 통과로 본다.
   null 이면 판정은 됐는데 **아직 아무도 y/n 을 안 찍은 것**이다. */
function gateOk(e){ return e.decision === undefined || e.decision === "y"; }
function awaiting(e){ return e.decision === null; }

function renderEvents(){
  const el = $("#evList");
  const live = EVENTS.filter(e => e.status !== "closed" && e.status !== "dismissed");
  const open = live.filter(gateOk);
  const wait = live.filter(awaiting);
  const dismissed = EVENTS.filter(e => e.status === "dismissed");
  const closed = EVENTS.filter(e => e.status === "closed");

  const bar = document.getElementById("decBar");
  if(bar){
    bar.hidden = !wait.length;
    bar.innerHTML = wait.length
      ? `<div class="empty" style="margin:0 0 10px">점검 여부가 안 정해진 사건 <b>${wait.length}건</b>이 있습니다 —
          정해야 점검을 시작할 수 있습니다.
          <a class="btn sm pri" href="<?= url('/review') ?>">감지 검토 →</a></div>`
      : "";
  }
  const db = document.getElementById("dismissFold");
  if(db){
    db.hidden = !dismissed.length;
    const dl = document.getElementById("dismissList");
    if(dl) dl.innerHTML = dismissed.map(evHtml).join("");
    foldN("dismissFold", dismissed.length, "건");
  }
  if(open.length){
    el.innerHTML = open.map(evHtml).join("");
  }else if(closed.length){
    el.innerHTML = `<div class="empty">열린 사건이 없습니다 — 지난 기록은 아래 <b>닫힌 사건</b>에서 볼 수 있습니다.
      <a class="btn sm" href="${DASH_URL}">Risk 현황 →</a></div>`;
  }else{
    el.innerHTML = `<div class="empty">사건이 없습니다 — <b>Risk 현황</b>에서 영향 분석을 실행하고 사건 꾸러미를 받으면 여기 나타납니다.
      <a class="btn sm pri" href="${DASH_URL}">Risk 현황 →</a></div>`;
  }
  const box = document.getElementById("closedFold");
  if(box){
    box.hidden = !closed.length;
    document.getElementById("closedList").innerHTML = closed.map(evHtml).join("");
    foldN("closedFold", closed.length, "건");
    if(!CLOSED_TOUCHED) box.open = false;
  }
}

async function selectEvent(id){
  CUR = id;
  const e = EVENTS.find(x => x.id === id);
  $("#detail").style.display = "block";
  // 권역을 누르면 BP 기준 정보가 그 권역으로 걸러져 열린다 — "권역 클릭 → BP·담당자 리스트"
  const areas = (e && (e.adminAreas && e.adminAreas.length ? e.adminAreas : [e.admin1].filter(Boolean))) || [];
  $("#evTitle").innerHTML = esc(e ? e.title : id) + " " + areas.map(a =>
    `<a href="${BP_URL}?region=${encodeURIComponent(a)}" class="pill" style="font-weight:600">${esc(a)} ▸ BP</a>`).join(" ");
  $("#evToggleBtn").textContent = (e && e.status === "closed") ? "사건 다시 열기" : "사건 닫기";
  DRAFTS = {};
  await refresh();
  loadEvents();
  $("#detail").scrollIntoView({behavior:"smooth", block:"start"});
}

// ── 상태 새로고침 (서버 원장이 유일한 진실) ──
async function refresh(){
  // 소재 밖 사람은 이력 API(사번이 실린다)를 부르지 않는다.
  // 대상 BP 와 진행 상태만 연락처 없는 경로로 받아 그린다.
  if(!IS_ADMIN){
    RECORDS = [];
    await loadPublicTargets();
    renderCards();
    return;
  }
  const d = await jfetch(API + "inspection/history?eventId=" + encodeURIComponent(CUR));
  RECORDS = d.records || [];
  renderSummary(d.summary || {});
  renderCards();
  renderTimeline();
}

function stateOf(bp){
  let last = null, sentTs = null;
  for(const r of RECORDS){
    if(r.bpName !== bp) continue;
    last = r;
    if(r.op === "sent") sentTs = r.ts;
  }
  /* 이력(RECORDS)에는 사번이 실려 소재 밖으로 안 나간다. 그래서 비관리자는
     연락처 없는 경로가 준 op 만 쓴다 — 시각(sentTs)은 없으므로 기한 초과는
     계산하지 않는다. 모르는 것을 0 으로 단정하지 않고 비워 둔다. */
  if(!last && DRAFTS[bp] && DRAFTS[bp].op) return {op: DRAFTS[bp].op, sentTs: null, last: null};
  return {op: last ? last.op : null, sentTs, last};
}

// ── 한 줄이 한 BP 의 처음부터 끝까지를 말한다 ────────────────────
// 예전에는 마지막 op 하나로 카드에 뱃지를 찍었다. 그러면 "발송은 했는데
// 회신이 없다" 와 "아직 안 보냈다" 가 같은 칸에서 다투고, 이상 유무는
// 어디에도 안 남는다. 칸마다 따로 답하게 한다.
function trackOf(bp){
  const out = {sent:null, draft:null, replied:null, bounced:null,
               closed:null, skipped:null, skipReason:"", note:"",
               s:[], stock:"", commReq:false};
  for(const r of RECORDS){
    if(r.bpName !== bp) continue;
    if(r.op in out && !(r.op === "note")) out[r.op] = r.ts || true;
    if(r.op === "skipped"){ out.skipReason = r.reasonKo || r.reason || ""; }
    if(r.op === "bounced"){ out.skipReason = r.reasonKo || r.reason || ""; }
    // 회신에 실려 온 것 — 뒤에 온 것이 이긴다.
    ["s1","s2","s3"].forEach((k,i)=>{ if(r[k]) out.s[i] = r[k]; });
    if(r.stock) out.stock = r.stock;
    if(r.commReq) out.commReq = true;
    if(r.note) out.note = r.note;
  }
  if(!out.sent && !out.draft && DRAFTS[bp] && DRAFTS[bp].op === "draft") out.draft = true;
  return out;
}

// 이상 유무 — 회신의 O·△·X 를 한 칸으로 줄인다.
//   X 하나라도 → 있음 · △ 있으면 → 일부 · 전부 O → 없음
//   소통 요청은 그 자체가 신호라 따로 붙인다.
function riskOf(tr){
  const v = (tr.s || []).filter(Boolean);
  if(!tr.replied && !v.length) return {cls:"", txt:"—", why:"회신 전"};
  if(v.some(x => x === "X"))  return {cls:"bad",  txt:"있음", why:"소재·원재료·Source 중 X"};
  if(v.some(x => x === "△"))  return {cls:"warn", txt:"일부", why:"△ 가 있다"};
  if(v.length)                return {cls:"ok",   txt:"없음", why:"전부 O"};
  return {cls:"warn", txt:"미기재", why:"회신은 왔는데 O·△·X 가 없다"};
}

function renderPublicSummary(d){
  const bpN = d.bps || 0, replied = d.replied || 0;
  const sent = (d.rows || []).filter(r => r.op && r.op !== "draft").length;
  $("#stBp").textContent = bpN;
  $("#stSent").textContent = sent;
  $("#stReplied").innerHTML = `${replied}<small>/${bpN}</small>`;
  $("#stRepliedWrap").className = "stat" + (replied > 0 ? " ok" : "");
  $("#stOver").textContent = "—";          /* 발송 시각이 없어 계산할 수 없다 */
  $("#stOverWrap").className = "stat dim";
  $("#stBounced").textContent = (d.rows || []).filter(r => r.op === "bounced").length;
  $("#stLat").textContent = "—";
  SUMS[CUR] = {bp: bpN, replied, over: 0};
}
function isOverdue(st){
  if(!st.sentTs || ["replied","closed","skipped","bounced"].includes(st.op)) return false;
  return (Date.now() - new Date(st.sentTs).getTime()) > SLA_HOURS * 3600e3;
}

// 발송→첫회신 소요 (클라이언트 계산 — 계산 불가면 "—")
function fmtLatency(){
  const sent = {}, rep = {};
  for(const r of RECORDS){
    if(!r.bpName || !r.ts) continue;
    if(r.op === "sent" && !(r.bpName in sent)) sent[r.bpName] = +new Date(r.ts);
    if(r.op === "replied" && !(r.bpName in rep)) rep[r.bpName] = +new Date(r.ts);
  }
  const ds = [];
  for(const b in rep) if(sent[b] && rep[b] >= sent[b]) ds.push(rep[b] - sent[b]);
  if(!ds.length) return "—";
  const h = ds.reduce((a,c)=>a+c,0) / ds.length / 3600e3;
  if(!isFinite(h)) return "—";
  return h < 1 ? Math.round(h*60) + "분" : h < 48 ? (Math.round(h*10)/10) + "h" : Math.round(h/24) + "일";
}

function renderSummary(sum){
  const bpN = sum.bp || 0, replied = sum.replied || 0;
  const by = sum.byStatus || {};
  const bpsAll = [...new Set(RECORDS.filter(r=>r.bpName).map(r=>r.bpName))];
  const over = bpsAll.filter(b => isOverdue(stateOf(b))).length;
  $("#stBp").textContent = bpN;
  $("#stSent").textContent = by.sent || 0;
  $("#stReplied").innerHTML = `${replied}<small>/${bpN}</small>`;
  $("#stRepliedWrap").className = "stat" + (replied > 0 ? " ok" : "");
  $("#stOver").textContent = over;
  $("#stOverWrap").className = "stat" + (over > 0 ? " warn" : " dim");
  $("#stBounced").textContent = by.bounced || 0;
  $("#stLat").textContent = fmtLatency();
  SUMS[CUR] = {bp: bpN, replied, over};

  // 결론이 숫자보다 위 — 이 사건에서 지금 할 일 하나를 말로 먼저 준다.
  const left = bpN - replied;
  const v = $("#evVerdict");
  let cls = "", txt, act = "";
  if(over > 0){ cls = "hot"; txt = `기한 초과 ${over}곳 — 지금 할 일: 재요청`;
                act = "카드에서 [재요청 본문 복사]를 누르세요"; }
  else if(bpN && left > 0){ txt = `회신 ${replied}/${bpN} — 남은 ${left}곳 기다리는 중`;
                act = "회신이 오면 아래 상자에 붙여넣으세요"; }
  else if(bpN){ txt = `${bpN}곳 전부 회신 — 종결 처리만 남았습니다`;
                act = "각 카드의 [종결 처리]"; }
  else { txt = "아직 점검 대상이 없습니다";
                act = "[점검 대상·메일 초안 만들기]를 누르세요"; }
  v.className = "verdict " + cls;
  v.innerHTML = `<span class="v">${txt}</span><span class="m">${act}</span>`;

  renderWhyNot(sum.whyNot || []);
  renderEvents();
}

// **안 한 것도 결과다.** "해당 없음 12건" 에서 멈추면 그중 8건이 "연락처가 없어서" 인
// 사실이 아무 데도 안 나타난다. 그건 점검 결과가 아니라 연락처 채우기 과제다.
// 이유를 세어 다음 할 일까지 붙여 준다 — 이유를 코드로 받은 이유가 이것이다.
function renderWhyNot(rows){
  const box = $("#whyNot");
  if(!box) return;
  if(!rows.length){ box.innerHTML = ""; box.style.display = "none"; return; }
  box.style.display = "";
  box.innerHTML = `<div class="wn-h">안 한 이유</div>` + rows.map(r => `
    <div class="wn-r">
      <span class="wn-n">${r.n}</span>
      <span class="wn-l">${esc(r.label)}</span>
      <span class="wn-x">${esc(r.next && r.next !== "-" ? "→ " + r.next : "")}</span>
    </div>`).join("");
}

// ── BP 카드 (권역→BP→담당자 드릴다운) ──
// 큐 정렬: 미발송 → 기한 초과 → 반송 → 발송 대기 → 회신 → 해당없음 → 종결
function rankOf(bp){
  const st = stateOf(bp);
  if(isOverdue(st)) return 1;
  const op = st.op;
  if(!op || op === "draft") return 0;
  return {bounced:2, sent:3, replied:4, skipped:5, closed:6}[op] ?? 3;
}
// 소재 밖 사람에게 보여줄 목록 — 연락처가 실리지 않는 경로로 받는다.
async function loadPublicTargets(){
  if(!CUR) return;
  try{
    const d = await fetch(API + "incidents/" + encodeURIComponent(CUR) + "/targets")
      .then(r => r.json());
    if(!d.ok) return;
    DRAFTS = {};
    /* op 를 버리지 않는다. 서버가 이걸 주는 이유가 바로 이 화면 때문이다 —
       버리면 stateOf 가 늘 null 을 돌려주고 모든 카드가 '미발송' 이 된다.
       바로 위 배너는 "회신 N사" 라고 말하는데 카드는 전부 미발송이라, 화면이
       스스로와 모순된다. 소재 밖 사람에게는 그게 이 화면의 첫인상이 된다. */
    (d.rows || []).forEach(r => DRAFTS[r.bpName] = {
      siteCount: (r.sites||[]).length, hotlineMissing: !r.hasHotline, publicOnly: true,
      op: r.op || null,
    });
    say("#topMsg", `${(d.areas||[]).join("·")} → 대상 ${d.bps}사 ${d.sites}곳 · 회신 ${d.replied}사`, true);
    /* 요약 숫자도 갱신한다. 안 하면 사건을 바꿔도 머리말이 이전 사건 숫자를 든다. */
    renderPublicSummary(d);
  }catch(e){
    say("#topMsg", "대상을 불러오지 못했습니다 — " + e.message, false);
  }
}

function renderCards(){
  const grid = $("#bpGrid");
  let bps = Object.keys(DRAFTS);
  if(!bps.length){
    const seen = [...new Set(RECORDS.filter(r=>r.bpName).map(r=>r.bpName))];
    seen.sort((a,b) => rankOf(a) - rankOf(b) || a.localeCompare(b));
    grid.innerHTML = seen.length
      ? tableHtml(seen)
      : '<div class="empty">아직 점검 대상이 없습니다 — <b>점검 대상·메일 초안 만들기</b>를 누르면 이 사건에 걸리는 BP 가 뜹니다.</div>';
    return;
  }
  bps.sort((a,b) => rankOf(a) - rankOf(b) || a.localeCompare(b));
  grid.innerHTML = tableHtml(bps);
}

// ── status 표 ────────────────────────────────────────────────────
// **한 줄이 한 BP 의 처음부터 끝까지다.** 예전에는 카드마다 마지막 상태
// 뱃지 하나였는데, 그러면 "보냈나 · 답이 왔나 · 이상이 있었나 · 끝났나" 넷을
// 한 낱말에 욱여넣게 된다. 넷은 서로 다른 질문이라 칸도 넷이어야 한다.
function tableHtml(bps){
  const rows = bps.map(bp => rowHtml(bp, DRAFTS[bp])).join("");
  return `<table class="ins-t">
    <thead><tr>
      <th>BP</th><th>점검 메일</th><th>회신</th><th>이상</th>
      <th>완료</th><th class="w">비고</th><th></th><th></th>
    </tr></thead>
    <tbody>${rows}</tbody></table>`;
}

const dm = ts => ts && ts !== true ? String(ts).slice(5,10) : "";

function rowHtml(bp, d){
  const tr = trackOf(bp), st = stateOf(bp), risk = riskOf(tr);
  const over = isOverdue(st);
  const bq = esc(String(bp).replace(/\\/g, "\\\\").replace(/'/g, "\\'"));
  const miss = d && d.hotlineMissing;
  const pub = !IS_ADMIN || (d && d.publicOnly);

  const mail = miss ? `<span class="c no">연락처 없음</span>`
    : tr.sent   ? `<span class="c ok">발송 ${dm(tr.sent)}</span>`
    : tr.draft  ? `<span class="c warn">양식만</span>`
    :             `<span class="c">미발송</span>`;
  const rep = tr.bounced ? `<span class="c no">반송 ${dm(tr.bounced)}</span>`
    : tr.replied ? `<span class="c ok">받음 ${dm(tr.replied)}</span>`
    : tr.sent    ? `<span class="c ${over?"no":"warn"}">${over?"기한 초과":"대기"}</span>`
    :              `<span class="c">—</span>`;
  const rk = `<span class="c ${risk.cls}" title="${esc(risk.why)}">${risk.txt}</span>`
    + (tr.commReq ? ` <span class="c warn" title="BP 가 소통을 요청했다">소통</span>` : "")
    + (tr.stock ? ` <span class="hint">재고 ${esc(tr.stock)}일</span>` : "");
  const done = tr.closed  ? `<span class="c ok">종결 ${dm(tr.closed)}</span>`
    : tr.skipped ? `<span class="c" title="${esc(tr.skipReason)}">해당 없음</span>`
    :              `<span class="c">—</span>`;

  return `<tr class="${over?"over":""}">
    <td class="nm"${pub?"":` onclick="toggleWho('${cssId(bp)}')" title="담당자 보기"`}>${esc(bp)}</td>
    <td>${mail}</td><td>${rep}</td><td>${rk}</td><td>${done}</td>
    <td class="w"><span class="memo">${esc(tr.note || "")}</span></td>
    <td class="act">${pub ? "" : primaryAction(bp, bq, st, d, over, miss)}</td>
    <td class="act">${pub ? "" : `<details class="fold menu"><summary title="그 밖의 처리">⋯</summary>
      <div class="fold-body">
        ${d && !miss ? `<button class="btn sm" onclick="copyMail('${bq}')">본문 다시 복사</button>` : ""}
        <button class="btn sm" onclick="mark('${bq}','sent')">발송함</button>
        <button class="btn sm" onclick="mark('${bq}','bounced')">반송됨</button>
        <button class="btn sm" onclick="mark('${bq}','skipped')">해당 없음</button>
        ${tr.closed ? "" : `<button class="btn sm" onclick="mark('${bq}','closed')">종결</button>`}
        <a class="btn sm" href="${BP_URL}?bp=${encodeURIComponent(bp)}">BP 기준 정보 →</a>
      </div></details>`}</td>
  </tr>
  ${pub || !d ? "" : `<tr class="who-r"><td colspan="8"><div class="who" id="who-${cssId(bp)}">
      <div class="p"><b>담당</b> ${esc(d.contactName||"—")} <span class="hint">(${esc(d.routedBy||"")})</span></div>
      <div class="p"><b>메일</b> ${esc(d.to||"—")}</div>
      <div class="p"><b>전화</b> ${esc(d.phone||"—")}</div>
      <div class="p"><b>사업장</b> ${d.siteCount}곳</div>
    </div></td></tr>`}`;
}
function cardHtml(bp, d){
  const st = stateOf(bp);
  const overdue = isOverdue(st);
  const op = OPPILL[st.op] || ["ins-pending","미발송"];
  // **JS 이스케이프를 먼저, HTML 이스케이프를 나중에.** 순서가 뒤집혀 있었다 —
  // esc() 가 ' 를 &#39; 로 바꾼 뒤에 ' 를 찾으면 하나도 없어서 아무 일도 안 하고,
  // 브라우저가 속성값을 풀 때 &#39; 가 다시 ' 가 되어 onclick 이 깨진다.
  // L'Air Liquide 같은 사명에서 그 회사 버튼만 조용히 안 눌렸다.
  const bq = esc(String(bp).replace(/\\/g, "\\\\").replace(/'/g, "\\'"));
  const pub = !IS_ADMIN || (d && d.publicOnly);
  let who = "";
  if(d && !pub){
    who = `<div class="who" id="who-${cssId(bp)}">
      <div class="p"><b>담당</b> ${esc(d.contactName||"—")} <span class="hint">(${esc(d.routedBy)})</span></div>
      <div class="p"><b>메일</b> ${esc(d.to||"—")}</div>
      <div class="p"><b>전화</b> ${esc(d.phone||"—")}</div>
      <div class="p"><b>사업장</b> ${d.siteCount}곳</div>
    </div>`;
  }
  // 공개 모드에서는 접을 것이 없다 — 카드가 말하는 것은 '누가' 와 '어디까지' 뿐이다.
  const miss = d && d.hotlineMissing;
  const sub = pub
    ? (d ? `사업장 ${d.siteCount}곳 · 담당자 연락처는 소재전략에서 관리합니다` : "기록만 있음")
    : (d
      ? (miss ? `연락처 없음 — <a href="${BP_URL}?tab=hot&bp=${encodeURIComponent(bp)}">명부에서 먼저 채우세요</a>` : esc(d.contactName||""))
      : "기록만 있음(초안 재생성 가능)");
  return `<div class="bp-card ${miss?"miss":""} ${overdue?"overdue":""}">
    <div class="bp-hd">
      <span class="nm"${pub ? "" : ` onclick="toggleWho('${cssId(bp)}')" title="담당자 보기"`}>${esc(bp)}</span>
      ${overdue?'<span class="pill due-over">기한 초과</span>':""}
      <span class="st pill ${op[0]}">${op[1]}</span>
    </div>
    <div class="bp-sub">${sub}${pub ? "" :
      ` · <a href="${BP_URL}?bp=${encodeURIComponent(bp)}">명부에서 보기</a>`}</div>
    ${who}
    ${!pub && overdue && d ? `<div class="phone-big">☎ ${esc(d.phone||"전화번호 없음")} <span class="hint">메일 무회신 → 전화 폴백</span></div>` : ""}
    ${pub ? "" : `    <div class="bp-acts">
      ${primaryAction(bp, bq, st, d, overdue, miss)}
      <details class="fold menu"><summary title="그 밖의 처리">⋯</summary>
        <div class="fold-body">
          ${d && !miss ? `<button class="btn sm" onclick="copyMail('${bq}')">본문 다시 복사</button>` : ""}
          <button class="btn sm" onclick="mark('${bq}','bounced')">반송됨</button>
          <button class="btn sm" onclick="mark('${bq}','skipped')">해당 없음</button>
          ${st.op!=="closed" ? `<button class="btn sm" onclick="mark('${bq}','closed')">종결</button>` : ""}
        </div>
      </details>
    </div>`}
  </div>`;
}
// 상태마다 다음 할 일은 하나다. 여섯 개를 나란히 놓으면 무엇을 눌러야 하는지
// 매번 읽어야 한다 — 지금 할 일만 크게 두고 나머지는 ⋯ 뒤로 보낸다.
function primaryAction(bp, bq, st, d, overdue, miss){
  if(miss)  return `<a class="btn sm pri" href="bp?bp=${encodeURIComponent(bp)}">연락처 채우기 →</a>`;
  if(!st.op || st.op==="draft") return `<button class="btn sm pri" onclick="copyMail('${bq}')">메일 본문 복사</button>`;
  if(st.op==="sent")  return overdue
      ? `<button class="btn sm pri" onclick="copyRemind('${bq}')">재요청 본문 복사</button>`
      : `<span class="waiting">회신 기다리는 중 — 오면 아래에 붙여넣으세요</span>`;
  if(st.op==="replied") return `<button class="btn sm pri" onclick="mark('${bq}','closed')">종결 처리</button>`;
  return `<span class="waiting done">처리 끝</span>`;
}
const cssId = s => String(s).replace(/[^a-zA-Z0-9가-힣]/g, "_");
function toggleWho(id){ const el = document.getElementById("who-"+id); if(el) el.classList.toggle("open"); }

// ── 동작 ──
async function makeDrafts(){
  try{
    const d = await post(API + "inspection/draft", {eventId: CUR});
    DRAFTS = {};
    (d.drafts || []).forEach(x => DRAFTS[x.bpName] = x);
    say("#topMsg", `대상 ${d.targetBps}사 ${d.targetSites}곳 · 연락처 준비 ${d.hotlineReady}사 · 초안 ${d.drafts.length}건`, true);
    await refresh();
  }catch(e){
    if(/rumor|미확인/.test(e.message) && confirm(e.message + "\n\n그래도 만들까요?")){
      const d = await post(API + "inspection/draft", {eventId: CUR, force: true});
      DRAFTS = {}; (d.drafts||[]).forEach(x => DRAFTS[x.bpName] = x);
      await refresh(); return;
    }
    say("#topMsg", e.message, false);
  }
}
function copyMail(bp){
  const d = DRAFTS[bp]; if(!d) return;
  toClip(`받는사람: ${d.to || "(연락처 없음 — BP 기준 정보에서 채우세요)"}\n제목: ${d.subject}\n\n${d.body}`, "#topMsg", bp + " 메일");
}
function copyRemind(bp){
  const d = DRAFTS[bp]; if(!d) return;
  const body = `${d.contactName||"담당자"}님, 앞서 요청드린 점검 회신이 아직 확인되지 않아 다시 연락드립니다.\n원 메일의 회신 블록만 채워 회신 부탁드립니다. 급한 경우 유선(${d.phone||"-"})으로도 가능합니다.\n\n점검번호: ${CUR}`;
  // 반드시 보낸편지함의 원 메일에서 RE: 로 — 스레드가 매칭 근거다
  toClip(`제목: RE: ${d.subject}\n\n${body}`, "#topMsg", bp + " 재요청");
}
// 결과만 남기면 다음에 같은 판단을 처음부터 다시 한다. **안 했다·못 갔다는
// 이유 없이는 기록이 아니다** — 서버도 그 둘만 이유를 요구한다.
// 이유 목록은 서버에서 받아 온다. 화면에 적어 두면 서버가 받는 코드와 갈라진다.
let REASONS = null;
async function loadReasons(){
  if(REASONS) return REASONS;
  try{ REASONS = (await jfetch(API + "inspection/reasons")).reasons || {}; }
  catch(_){ REASONS = {}; }
  return REASONS;
}
async function askReason(op){
  const tbl = (await loadReasons())[op];
  if(!tbl || !tbl.length) return {reason: "other", note: "" };
  const lines = tbl.map((r, i) => `${i + 1}. ${r.label}`).join("\n");
  const pick = prompt(`왜 ${op === "skipped" ? "점검하지 않나요" : "반송됐나요"}?\n\n${lines}\n\n번호를 넣으세요`);
  if(pick === null) return null;
  const idx = parseInt(pick, 10) - 1;
  const r = tbl[idx];
  if(!r){ say("#topMsg", "목록에 없는 번호입니다.", false); return null; }
  let note = "";
  if(r.code === "other"){
    note = prompt("내용을 적어 주세요") || "";
    if(!note.trim()){ say("#topMsg", "'그 밖' 은 내용을 적어야 합니다.", false); return null; }
  }
  return {reason: r.code, note: note};
}
async function mark(bp, op){
  try{
    const payload = {op, eventId: CUR, bpName: bp};
    if(op === "skipped" || op === "bounced"){
      const r = await askReason(op);
      if(r === null) return;              // 취소 — 아무것도 기록하지 않는다
      payload.reason = r.reason;
      if(r.note) payload.note = r.note;
    }
    await post(API + "inspection/record", payload);
    await refresh();
  }catch(e){ say("#topMsg", e.message, false); }
}
async function addNote(){
  const note = prompt("사건 메모 한 줄"); if(!note) return;
  await post(API + "inspection/record", {op:"note", eventId: CUR, note}); await refresh();
}
async function sealCampaign(){
  const actions = prompt("후속 액션 1~3개 (봉인 요약은 자동 계산됩니다)"); if(actions === null) return;
  const d = await post(API + "inspection/record", {op:"campaign.close", eventId: CUR, actions});
  say("#topMsg", "봉인됨 — " + JSON.stringify(d.record.summary), true); await refresh();
}
async function toggleEvent(){
  const e = EVENTS.find(x => x.id === CUR);
  const to = (e && e.status === "closed") ? "open" : "closed";
  await post(API + "incidents/status", {id: CUR, status: to});
  await loadEvents(); selectEvent(CUR);
}
function copyNotice(){
  const e = EVENTS.find(x => x.id === CUR) || {};
  const st = {}; RECORDS.forEach(r => { if(r.bpName) st[r.bpName] = r.op; });
  const n = Object.keys(st).length;
  const replied = Object.values(st).filter(o => o === "replied" || o === "closed").length;
  const txt = `[mGSCS 점검 현황] ${e.title||CUR}\n대상 ${n}개사 중 회신 ${replied}건 (${n?Math.round(replied/n*100):0}%)\n지역: ${e.country||""} ${e.admin1||""} · 발생: ${String(e.occurredAt||"").slice(0,16).replace("T"," ")}\n상세: 사내 mGSCS → 점검 → ${CUR}`;
  toClip(txt, "#topMsg", "현황 공지");
}

// ── 회신 파싱 (화면·서버 공통 블록 양식) ──
function parseReplyChunk(text){
  const pick = labels => {
    for(const l of labels){
      const m = text.match(new RegExp(l + "\\s*[:：]\\s*([^\\n\\r]*)"));
      if(m && m[1].trim()) return m[1].trim();
    }
    return "";
  };
  const norm = v => { v = v.trim(); if(/^[oO○]/.test(v)) return "O"; if(/^[△▲]/.test(v)) return "△"; if(/^[xX✕×]/.test(v)) return "X"; return v; };
  const stock = (pick(["재고[^:：\\r\\n]*"]).match(/\d+/) || [""])[0];
  let bp = pick(["협력사","회사명","업체명","회사","업체"]);
  if(/귀사명/.test(bp)) bp = "";
  return {
    ref: pick(["점검번호"]),
    bp,
    s1: norm(pick(["소재\\s*제조\\s*(?:사업장|site)","소재제조","생산공급","생산"])),
    s2: norm(pick(["원재료\\s*제조\\s*(?:사업장|site)","원재료","원료"])),
    s3: norm(pick(["[Ss]ource[^:：\\r\\n]*","소스[^:：\\r\\n]*","3rd[^:：\\r\\n]*"])),
    stock,
    note: pick(["특이사항","비고"]),
    commReq: /필요|요청|예|yes/i.test(pick(["소통 요청","소통"])) && !/불필요|아니오|no/i.test(pick(["소통 요청","소통"])),
  };
}
async function parseReplies(){
  const text = ($("#replyBox").value || "").trim();
  if(!text){ say("#replyMsg", "붙여넣은 내용이 없습니다.", false); return; }
  const blocks = text.split(/=====+\s*mGSCS\s*회신/);
  const chunks = blocks.length > 1 ? blocks.slice(1) : [text];
  const known = new Set([...Object.keys(DRAFTS), ...RECORDS.filter(r=>r.bpName).map(r=>r.bpName)]);
  let saved = 0, misses = [];
  for(const ch of chunks){
    const p = parseReplyChunk(ch);
    if(!p.s1 && !p.s2 && !p.s3 && !p.bp) continue;
    // 매칭: 점검번호 → 현재 사건과 대조. 협력사명 → 알려진 BP 와 대조 (부분 일치 폴백)
    const eid = p.ref || CUR;
    let bp = p.bp;
    if(bp && !known.has(bp)){
      const hit = [...known].find(k => k.includes(bp) || bp.includes(k));
      if(hit) bp = hit;
    }
    if(!bp){ misses.push(p.bp || "(협력사 미상)"); continue; }
    await post(API + "inspection/record", {op:"replied", eventId: eid, bpName: bp,
      s1: p.s1, s2: p.s2, s3: p.s3, stock: p.stock, note: p.note, commReq: p.commReq});
    saved++;
  }
  if(misses.length){
    // 못 맞춘 이름은 명부와 표기가 다른 것이다 — 어디서 고치는지까지 말해 준다.
    const el = $("#replyMsg");
    el.className = "msg " + (saved ? "ok" : "err");
    el.innerHTML = `기록 ${saved}건 · 못 맞춘 ${misses.length}건: ${esc(misses.join(", "))}` +
      ` — 이름이 명부와 다르면 BP 기준 정보에서 확인하세요. <a href="${BP_URL}">BP 기준 정보 →</a>`;
  }else{
    say("#replyMsg", `기록 ${saved}건`, saved > 0);
  }
  if(saved){ $("#replyBox").value = ""; await refresh(); }
}

// ── 이력 ──
function renderTimeline(){
  const el = $("#timeline");
  foldN("tlFold", RECORDS.length, "건");
  if(!RECORDS.length){ el.innerHTML = '<div class="empty">아직 기록이 없습니다. 메일을 복사해 보내면 첫 줄이 쌓입니다.</div>'; return; }
  el.innerHTML = RECORDS.slice().reverse().map(r => {
    const extra = ["s1","s2","s3"].map(k => r[k]).filter(Boolean).join("/");
    return `<div><span class="hint">${esc(String(r.ts||"").slice(5,16).replace("T"," "))}</span>
      <span class="op">${esc((OPPILL[r.op]||[,r.op])[1])}</span> ${esc(r.bpName||"")}
      ${extra ? `<span class="hint">[${esc(extra)}${r.stock?` 재고${esc(r.stock)}일`:""}]</span>` : ""}
      ${r.note ? `<span class="hint">— ${esc(r.note)}</span>` : ""}
      ${r.summary ? `<span class="hint">${esc(JSON.stringify(r.summary))}</span>` : ""}
      <span class="hint">· ${esc(r.actor||"")}</span></div>`;
  }).join("");
}

// ── 이관 ──
async function migrateLegacy(){
  let items;
  try{ items = JSON.parse($("#migrateBox").value); }
  catch(_){ say("#migrateMsg", "JSON 배열 형식이 아닙니다.", false); return; }
  try{
    const d = await post(API + "inspection/migrate", {records: items, eventId: CUR || ""});
    say("#migrateMsg", `이관 ${d.added}건 · 중복 ${d.duplicated}건 · 형식오류 ${d.invalid}건`, true);
    if(d.added && CUR) refresh();
  }catch(e){ say("#migrateMsg", e.message, false); }
}

// 시작 — ?event=<id> 딥링크 수신 (Risk 현황 → 점검)
(async function init(){
  // 부팅이 실패하면 화면은 "불러오는 중…" 에서 영영 멈춘다. 그리고 사람에게
  // 아무 말도 안 한다 — 서버가 죽은 것인지 사건이 없는 것인지 구별할 수 없다.
  try{
    await loadEvents();
    let eid = null;
    try{ eid = new URLSearchParams(location.search).get("event"); }catch(_){}
    if(eid && EVENTS.some(e => e.id === eid)) selectEvent(eid);
  }catch(e){
    const list = $("#evList");
    if(list) list.innerHTML = '<p class="empty">사건 목록을 불러오지 못했습니다 — '
      + esc(e.message) + '<br>새로고침하거나, 계속되면 시스템 설정에서 서버 상태를 확인하세요.</p>';
    say("#topMsg", "사건 목록을 불러오지 못했습니다 — " + e.message, false);
  }
})();

</script>
</body>
</html>
