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
<title>mGSCS · 시스템 설정</title>
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='4' fill='%231d3a6e'/%3E%3Ctext x='16' y='22' font-size='17' font-family='sans-serif' font-weight='800' fill='white' text-anchor='middle'%3Em%3C/text%3E%3C/svg%3E">

<?php include __DIR__ . '/_shared/theme.php'; ?>
<style>



*{box-sizing:border-box}
html{-webkit-font-smoothing:antialiased}
body{margin:0;font-family:var(--font-sans);font-size:var(--fs-base);letter-spacing:-.01em;
     background:var(--bg);color:var(--ink);font-variant-numeric:tabular-nums}
a{color:var(--navy-2);text-decoration:none}

.wrap{max-width:var(--read-w);margin:0 auto;padding:24px 24px 60px}
.lead b{color:var(--ink)}

/* 설명문은 .fold 안으로 들어갔다 — 별도 상자 정의를 두지 않는다. */
details.fold > .fold-body{font-size:var(--fs-sm);color:var(--ink-2);line-height:1.75}

.row{display:grid;grid-template-columns:190px 1fr;gap:14px;align-items:start;
     padding:13px 0;border-bottom:1px solid var(--line-2)}
.row:last-child{border-bottom:0}
/* 설정 그룹 카드 — 그룹 하나가 카드 하나 */
.grp-card{margin:0 0 14px;padding:6px 16px 10px}
.grp-card .row:last-child{border-bottom:0}
.k{font-weight:700;font-size:var(--fs-sm);padding-top:7px}
.k .code{display:block;font-family:var(--mono);font-size:var(--fs-2xs);color:var(--ink-3);font-weight:400;margin-top:2px}
input[type=text]{width:100%;padding:8px 10px;border:1px solid var(--line2);border-radius:var(--r-md);
                 font:inherit;font-size:var(--fs-sm);background:var(--card);color:var(--ink)}
input[type=text]:focus{outline:none;border-color:var(--navy-2)}
.help{font-size:var(--fs-xs);color:var(--ink-3);margin-top:5px;line-height:1.6}
.toggle{display:inline-flex;gap:6px}
.toggle button{padding:7px 15px;border:1px solid var(--line2);background:var(--card);
               border-radius:var(--r-md);font:inherit;font-size:var(--fs-sm);cursor:pointer;color:var(--ink-2)}
.toggle button.on{background:var(--navy);border-color:var(--navy);color:var(--on-navy);font-weight:700}
.toggle button.off.on{background:var(--ink-3);border-color:var(--ink-3)}

.act{margin-top:24px;display:flex;gap:9px;align-items:center;flex-wrap:wrap}
.msg{font-size:var(--fs-sm);line-height:1.6}
.msg.err{color:var(--red)} .msg.ok{color:var(--green)} .msg.warn{color:var(--amber)}

h2{font-size:var(--fs-md);margin:34px 0 8px;letter-spacing:-.02em}
/* 접어 둔 절은 첫 화면을 차지하지 않되, 앞 블록과는 확실히 떨어뜨린다. */
details.fold{margin:22px 0 0}
pre{background:var(--surface);border:1px solid var(--line);border-radius:var(--r-md);
    padding:13px;font-family:var(--mono);font-size:var(--fs-xs);line-height:1.7;
    overflow-x:auto;margin:0;color:var(--ink-2)}
.hist{font-size:var(--fs-xs);color:var(--ink-3);line-height:1.8;margin-top:8px}
.conf{color:var(--red);font-weight:700}
@media (max-width:640px){ .row{grid-template-columns:1fr;gap:6px} .k{padding-top:0} }

#collectCard{margin:8px 0 20px;font-size:var(--fs-sm)}

.grp{margin:8px 0 2px;padding:0 0 5px;border-bottom:1px solid var(--line);
     font-size:var(--fs-xs);font-weight:800;color:var(--navy);letter-spacing:.02em}
:root[data-theme="dark"] .grp{color:var(--navy-2)}
.tbtn{padding:8px 14px;border:1px solid var(--navy);background:var(--card);color:var(--navy);
      border-radius:var(--r-md);font:inherit;font-size:var(--fs-sm);font-weight:700;cursor:pointer}
.tbtn:hover{background:var(--accent-soft)}
.tbtn:disabled{opacity:.5;cursor:not-allowed}
#llmOut{margin:9px 0 0;padding:11px 13px;border:1px solid var(--line2);border-radius:var(--r-lg);
        background:var(--surface);font-family:var(--mono);font-size:var(--fs-2xs);
        line-height:1.6;white-space:pre-wrap;word-break:break-all;max-height:340px;overflow:auto}
#llmOut.ok{border-color:var(--ok-line);background:var(--ok-bg)}
#llmOut.err{border-color:var(--hi-line);background:var(--hi-bg)}

/* ── 사내 API 연결 ── */
.llm-st{display:flex;flex-wrap:wrap;align-items:baseline;gap:4px 10px;
  padding:11px 14px;margin:0 0 4px;border-radius:var(--r-md);
  border:1px solid var(--line);background:var(--surface-2);font-size:var(--fs-sm)}
.llm-st b{font-size:var(--fs-base);font-weight:800;color:var(--ink)}
.llm-st span{color:var(--ink-2)}
.llm-st-k{margin-left:auto;font-size:var(--fs-2xs);color:var(--ink-3);
  border:1px solid var(--line2);border-radius:var(--r-sm);padding:1px 7px}
.llm-st.ok{border-color:var(--ok-line,#BBD9BE);background:var(--ok-bg,#F2F8F3)}
.llm-st.ok b{color:var(--ok,#2E7D32)}
.llm-st.err{border-color:var(--hi-line);background:var(--hi-bg)}
.llm-st.err b{color:var(--hi)}
.llm-st.wait{border-color:var(--navy-line);background:var(--accent-soft)}
.llm-st.wait b{color:var(--navy)}
.llm-st.off b{color:var(--ink-3)}
/* 연결 시험 결과 — 막혔을 때 어디서 막혔는지가 이 표에 있다. */
.ct-v{margin:8px 0 6px;padding:8px 11px;border-radius:var(--r-md);
  font-size:var(--fs-sm);font-weight:700;border:1px solid var(--line)}
.ct-v.ok{border-color:var(--ok-line,#BBD9BE);background:var(--ok-bg,#F2F8F3);color:var(--ok,#2E7D32)}
.ct-v.no{border-color:var(--hi-line);background:var(--hi-bg);color:var(--hi)}
/* 게이트웨이 차단 — 고장이 아니라 **설계대로**다. 파랑으로 둔다. */
.ct-v.gated{border-color:var(--navy-line);background:var(--accent-soft);color:var(--navy)}
.ct tr.gated td:nth-child(3){color:var(--navy)}
.ct{width:100%;border-collapse:collapse;margin:8px 0 4px;font-size:var(--fs-xs)}
.ct th{text-align:left;color:var(--ink-3);font-weight:700;padding:4px 8px 4px 0;
  border-bottom:1px solid var(--line)}
.ct td{padding:5px 8px 5px 0;border-bottom:1px solid var(--line-2);
  color:var(--ink-2);vertical-align:top}
.ct tr.no td{color:var(--ink)}
.ct tr.no td:nth-child(3){color:var(--hi);font-weight:700}
.llm-test{margin:14px 0 4px;padding-top:12px;border-top:1px solid var(--line)}
.llm-test-h{display:flex;flex-wrap:wrap;align-items:center;gap:10px}
.llm-test-h .help{flex:1 1 18rem;margin:0}
.llm-dirty{margin-top:10px;padding:8px 11px;border-radius:var(--r-md);
  border:1px solid var(--navy-line);background:var(--accent-soft);
  font-size:var(--fs-xs);color:var(--ink-2);line-height:1.6}
#llmOut{margin-top:10px}
select[data-k]{width:100%;padding:8px 10px;border:1px solid var(--line2);
  border-radius:var(--r-md);font:inherit;font-size:var(--fs-sm);background:var(--card);color:var(--ink)}
</style>
</head>
<body>

<?php $here = 'settings'; ?><?php $here_label = '시스템 설정'; ?>
<?php include __DIR__ . '/_shared/nav.php'; ?>

<div class="wrap">
  <header class="pagehead">
    <h1 class="pagetitle">시스템 설정</h1>
    <p class="lead">수집 · 판정 · 경보 · 사내 API 를 여기서 맞춘다.</p>
  </header>

  <h2>서버 수집</h2>
  <div id="collectCard" class="card">
    서버 수집 상태를 읽는 중…
  </div>

  <h2>경보 알림 · 점검 · AI 분석</h2>
  <p class="lead">
    누구에게, 어느 채널로, 어느 범위까지 보낼지를 정합니다.
    <b>수신자 주소는 개인정보</b>라 공개 화면이 아니라 이 화면(사내망 전용)에서만 다룹니다.
  </p>

  
  <details class="fold" id="whyFold" style="margin:0 0 18px">
    <summary>여기서 저장만 하는 이유 <span class="fold-hint">옮겨 심는 것은 사람이 합니다</span></summary>
    <div class="fold-body">
      경보는 사외 시스템에서 실행됩니다. 이 화면은 값을 저장하고, 사외에 옮겨 심을 형태로 보여줍니다.
      <b>옮기는 것은 사람이 합니다</b> — 여기서 원격 설정을 자동으로 바꾸지 않습니다.<br>
      사외 매핑을 사내로 들여오는 다음 단계가 되면, 이 파일이 곧 진짜 설정이 되고 옮기는 절차가 사라집니다.
    </div>
  </details>

  <div id="form"></div>

  <div class="act">
    <button class="btn pri" id="saveBtn">저장</button>
    <button class="btn" id="resetBtn">되돌리기</button>
    <span class="msg" id="msg"></span>
  </div>

  
  <details class="fold" id="exportFold">
    <summary>사외에 옮겨 심을 값 <span class="fold-hint">GitHub Actions Variables 에 붙여넣을 값</span></summary>
    <div class="fold-body">
      <p class="help" style="margin:0 0 8px">
        GitHub → Settings → Secrets and variables → Actions → Variables에 그대로 넣습니다.
        비밀번호·토큰은 여기 없습니다 — 그건 Variables가 아니라 Secrets로만 둡니다.
      </p>
      <pre id="export">-</pre>
    </div>
  </details>

  <details class="fold" id="histFold">
    <summary>변경 이력 <span class="fold-hint">누가 무엇을 바꿨나</span><span class="fold-n"></span></summary>
    <div class="fold-body">
      <div class="hist" id="hist">-</div>
    </div>
  </details>

  <div class="nextstep"><span>경보 발송은 사외 시스템이 실행합니다 — 이 화면은 <b>값 저장까지</b>입니다.</span><a class="go" href="<?= url('/dashboard') ?>">Risk 현황으로 →</a></div>

  <?php include __DIR__ . '/_shared/foot.php'; ?>
</div>

<script type="application/json" id="d-fields"><?= $fields_json ?></script>
<script type="application/json" id="d-values"><?= $values_json ?></script>
<script type="application/json" id="d-history"><?= $history_json ?></script>


<script>
const FIELDS  = JSON.parse(document.getElementById("d-fields").textContent);
const SAVED   = JSON.parse(document.getElementById("d-values").textContent);
const HISTORY = JSON.parse(document.getElementById("d-history").textContent);
let values = { ...SAVED };
// 사내 API 연결 상태 — 서버가 기억하고 있는 마지막 시험 결과와 어댑터 목록.
// 화면을 열 때마다 사내 API 를 두드리지는 않는다.
let LLM = { adapters: [], last: null, enabled: false, keySet: false, stale: false };

const esc = (s) => String(s ?? "").replace(/[&<>"]/g, (c) =>
  ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;" })[c]);

function rowHtml(f) {
  const v = values[f.key] ?? "";
  let body;
  if (f.type === "bool") {
    body = `<span class="toggle" data-k="${f.key}">
         <button type="button" data-v="true"  class="${v === "true" ? "on" : ""}">켬</button>
         <button type="button" data-v="false" class="off ${v === "false" ? "on" : ""}">끔</button>
       </span>`;
  } else if (f.type === "secret") {
    // 저장된 키는 화면으로 내려오지 않는다. 들어 있는지만 알려주고,
    // 비워 두면 "안 바꾼다" 는 뜻이다.
    const isSet = values[f.key + "_SET"];
    body = `<input type="password" data-k="${f.key}" data-secret="1" value=""
              autocomplete="new-password"
              placeholder="${isSet ? "저장돼 있습니다 — 바꾸려면 새로 입력" : "아직 없습니다"}">`;
  } else if (f.type === "select") {
    // 목록은 서버가 준다 — 화면에 적어 두면 어댑터가 늘 때마다 두 곳을 고쳐야 한다.
    const opts = (f.source === "adapters" ? (LLM.adapters || []) : (f.options || []));
    body = `<select data-k="${f.key}">` + opts.map((o) =>
      `<option value="${esc(o.name)}"${o.name === v ? " selected" : ""}>${esc(o.label)} · ${esc(o.name)}</option>`
    ).join("") + `</select>`;
  } else {
    body = `<input type="text" data-k="${f.key}" value="${esc(v)}">`;
  }
  return `<div class="row">
    <div class="k">${esc(f.label)}<span class="code">${esc(f.key)}</span></div>
    <div>${body}${f.help ? `<div class="help">${esc(f.help)}</div>` : ""}</div>
  </div>`;
}

function render() {
  // 그룹 하나 = 카드 하나. 그룹 없는 앞머리는 "경보 알림", 뒤에 남는 무그룹은 "판정".
  const cards = [];
  FIELDS.forEach((f) => {
    const g = f.group || null;
    if (!cards.length || cards[cards.length - 1].g !== g) cards.push({ g, rows: [] });
    cards[cards.length - 1].rows.push(rowHtml(f));
  });
  let hasLlm = false;
  const html = cards.map((c, i) => {
    const name = c.g || (i === 0 ? "경보 알림" : "판정");
    let extra = "";
    if (c.g === "AI 분석") { extra = llmTestBlock(); hasLlm = true;
      c.rows.unshift(llmStatusHtml()); }
    return `<section class="card grp-card"><div class="grp">${esc(name)}</div>${c.rows.join("")}${extra}</section>`;
  }).join("");
  document.getElementById("form").innerHTML =
    html + (hasLlm ? "" : `<section class="card grp-card">${llmTestBlock()}</section>`);

  document.querySelectorAll(".toggle button").forEach((b) => {
    b.onclick = () => { values[b.parentNode.dataset.k] = b.dataset.v; render(); };
  });
  document.querySelectorAll("#form input").forEach((i) => {
    i.oninput = () => { values[i.dataset.k] = i.value; renderExport(); markDirty(); };
  });
  document.querySelectorAll("#form select").forEach((s) => {
    s.onchange = () => { values[s.dataset.k] = s.value; renderExport(); markDirty(); };
  });
  wireLlmTest();
  renderExport();
}

// ── 지금 붙어 있나 ─────────────────────────────────────────────
// 이 화면에서 가장 먼저 알아야 하는 것. 값을 고치기 전에 지금 상태를 봐야 한다.
function llmStatusHtml() {
  const L = LLM.last;
  let cls, head, sub;
  if (!LLM.enabled) {
    cls = "off";  head = "AI 분석 꺼짐";
    sub = "수집·판정은 그대로 돌지만 LLM 추출은 안 합니다.";
  } else if (!L) {
    cls = "wait"; head = "아직 시험한 적 없음";
    sub = "아래 [지금 불러 보기] 로 한 번 확인해 주십시오.";
  } else if (LLM.stale) {
    cls = "wait"; head = "설정이 바뀌었습니다";
    sub = `마지막 시험(${fmtAt(L.at)})은 지금 값이 아닙니다 — 다시 불러 보십시오.`;
  } else if (L.ok) {
    cls = "ok";   head = "붙어 있습니다";
    sub = `${esc(L.model || "-")} · ${esc(L.base || "-")} · ${fmtAt(L.at)} 확인`;
  } else {
    cls = "err";  head = "못 붙습니다";
    sub = esc(L.error || "까닭 없음") + ` · ${fmtAt(L.at)}`;
  }
  return `<div class="llm-st ${cls}">
    <b>${esc(head)}</b><span>${sub}</span>
    <span class="llm-st-k">${LLM.keySet ? "API 키 있음" : "API 키 없음"}</span>
  </div>`;
}

function fmtAt(s) {
  if (!s) return "-";
  return String(s).slice(5, 16).replace("T", " ");
}

// 저장하지 않은 값이 있으면 말해 준다 — 시험은 지금 값으로 도는데
// 판정은 **저장된 값**으로 돈다. 그 차이를 모르면 "시험은 됐는데 안 돈다" 가 된다.
function markDirty() {
  const el = document.getElementById("llmDirty");
  if (el) el.hidden = false;
}

// ── 연결 시험 ──────────────────────────────────────────────────
// 사내 LLM Ops는 자체 형식이라 한 번에 맞을 리가 없다. 무엇을 보냈고
// 무엇을 받았는지 그대로 보여줘야 규격을 맞춰 갈 수 있다.
function llmTestBlock() {
  return `<div class="llm-test">
    <div class="llm-test-h">
      <button type="button" id="llmTest" class="btn pri">지금 불러 보기</button>
      <span class="help">저장하지 않아도 지금 화면의 값으로 한 번 부릅니다.
        보낸 요청과 받은 응답을 그대로 보여줍니다 — 사내 규격을 맞춰 가는 자리입니다.</span>
    </div>
    <div class="llm-dirty" id="llmDirty" hidden>
      고친 값이 아직 저장되지 않았습니다. <b>시험은 지금 값으로</b> 돌지만
      <b>판정은 저장된 값으로</b> 돕니다 — 맞으면 아래 [저장] 을 눌러 주십시오.
    </div>
    <pre id="llmOut" hidden></pre>
  </div>`;
}

function wireLlmTest() {
  const btn = document.getElementById("llmTest");
  if (!btn) return;
  btn.onclick = async () => {
    const out = document.getElementById("llmOut");
    out.hidden = false; out.className = ""; out.textContent = "부르는 중…";
    btn.disabled = true;
    try {
      const payload = {};
      ["LLM_FORMAT", "LLM_BASE", "LLM_MODEL", "LLM_EXTRA", "LLM_TIMEOUT", "LLM_KEY"]
        .forEach((k) => { if (values[k]) payload[k] = values[k]; });
      const d = await fetch("api/llm/test", {
        method: "POST", headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      }).then((r) => r.json());
      // 시험 결과를 화면 맨 위 상태에도 바로 반영한다 —
      // 서버가 기억했으니 새로고침해도 남지만, 지금 눈앞에서 바뀌어야 한다.
      LLM.last = { ok: !!d.ok, at: new Date().toISOString(),
                   base: values.LLM_BASE || "", model: values.LLM_MODEL || "",
                   error: d.error || "" };
      LLM.stale = false;
      const st = document.querySelector(".llm-st");
      if (st) st.outerHTML = llmStatusHtml();
      out.className = d.ok ? "ok" : "err";
      out.textContent =
        (d.ok ? "성공\n\n받은 본문\n" + (d.received || "") : "실패\n\n" + (d.error || "")) +
        "\n\n보낸 곳\n" + (d.sent ? d.sent.url : "-") +
        "\n\n보낸 헤더\n" + JSON.stringify(d.sent ? d.sent.headers : {}, null, 1) +
        "\n\n보낸 본문\n" + JSON.stringify(d.sent ? d.sent.body : {}, null, 1);
    } catch (e) {
      out.className = "err"; out.textContent = "실패: " + e.message;
    } finally { btn.disabled = false; }
  };
}

function renderExport() {
  // 사외에 넣을 값만 보여준다. 비밀번호는 애초에 이 화면에 없다.
  document.getElementById("export").textContent =
    FIELDS.filter((f) => f.type !== "secret" && !f.group)      // 비밀·사내 전용 설정은 사외로 옮기지 않는다
          .map((f) => `${f.key} = ${values[f.key] ?? ""}`).join("\n");
}

function renderHistory() {
  const el = document.getElementById("hist");
  // 접힌 채로도 몇 건이 쌓였는지는 보여야 한다.
  if (typeof foldSync === "function") foldSync("histFold", HISTORY.length, "건");
  if (!HISTORY.length) {
    el.textContent = "아직 바꾼 적이 없습니다. 저장하면 누가 무엇을 바꿨는지 여기 남습니다.";
    return;
  }
  el.innerHTML = HISTORY.slice(-12).reverse().map((h) => {
    const items = Object.entries(h.changed || {})
      .map(([k, [a, b]]) => `${esc(k)} <b>${esc(a)}</b> → <b>${esc(b)}</b>`).join(" · ");
    return `${esc(h.ts)} · ${esc(h.actor)} — ${items}`;
  }).join("<br>");
}

document.getElementById("resetBtn").onclick = () => {
  values = { ...SAVED };
  render();
  setMsg("저장된 값으로 되돌렸습니다.", "");
};

function setMsg(text, cls) {
  const m = document.getElementById("msg");
  m.className = "msg " + (cls || "");
  m.innerHTML = text;
}

document.getElementById("saveBtn").onclick = async () => {
  const btn = document.getElementById("saveBtn");
  btn.disabled = true;
  setMsg("저장 중…", "");
  try {
    const res = await fetch("api/settings", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(values),
    });
    const data = await res.json();
    if (!data.ok) {
      setMsg("저장하지 않았습니다 — " + (data.problems || []).map(esc).join(" / "), "err");
    } else {
      const n = Object.keys(data.changed || {}).length;
      const warn = (data.warnings || []).length
        ? `<br><span class="msg warn">${(data.warnings || []).map(esc).join("<br>")}</span>` : "";
      setMsg(n ? `저장했습니다. ${n}개 항목이 바뀌었습니다.${warn}`
                : `바뀐 값이 없습니다.${warn}`, "ok");
      if (n) setTimeout(() => location.reload(), 1400);
    }
  } catch (e) {
    setMsg("저장 실패: " + esc(e.message), "err");
  } finally {
    btn.disabled = false;
  }
};

// 어댑터 목록과 마지막 시험 결과를 먼저 받는다 — 형식 드롭다운과
// 맨 위 연결 상태가 둘 다 이 값을 쓴다. 못 받아도 화면은 그린다.
fetch("api/llm/status").then((r) => (r.ok ? r.json() : null)).then((d) => {
  if (d && d.ok) LLM = { adapters: d.adapters || [], last: d.last, enabled: d.enabled,
                         keySet: d.keySet, stale: d.stale };
  render();
}).catch(() => render());

render();
renderHistory();

// ── 서버 수집 카드 ── 상태 표시 + 연결 시험 + 수동 실행. 켜고 끄는 건 서버 환경변수다.
async function renderCollect(){
  const el=document.getElementById("collectCard");
  try{
    const d=await fetch("api/collect/status").then(r=>r.json());
    const sch=d.scheduler||{}, last=d.last;
    const srcLine = (last && Array.isArray(last.sources) && last.sources.length)
      ? last.sources.map(s=>`${s.name} ${s.ok?"OK":"실패"}`).join(" · ")
      : "아직 실행된 적 없음";
    const fn = (last && last.funnel) || null;
    const fun = fn
      ? `수집 ${fn.fetched ?? "-"} → 후보 ${fn.deduped ?? "-"} · 사건 등록 ${(last.incidentsRegistered||[]).length}건`
        + (last.ranAt ? ` · ${String(last.ranAt).slice(5,16).replace("T"," ")}` : "")
      : "";
    el.innerHTML = `
      <div style="margin-bottom:6px"><b>서버 수집(JMA·USGS·GDACS)</b> —
        ${sch.collectEnabled
          ? `<span style="color:var(--green);font-weight:700">가동 중</span> · ${sch.intervalMin}분 주기`
          : `<span style="color:var(--ink-3)">꺼짐</span> — 서버가 직접 나가려면 <code>MGSCS_COLLECT=1</code>`}</div>
      <div style="margin-bottom:6px"><b>서버 판정</b> —
        ${sch.reviewEnabled !== false
          ? `<span style="color:var(--green);font-weight:700">가동 중</span> — 브라우저가 올린 신호를 서버가 판정합니다`
          : `<span style="color:var(--red,#b3261e);font-weight:700">꺼짐</span> — <code>MGSCS_REVIEW=1</code> 로 켜세요.
             꺼져 있으면 신호는 쌓이는데 <b>결정 대기에 아무것도 안 올라옵니다</b>`}</div>
      <div style="color:var(--ink-3);font-size:var(--fs-xs);margin-bottom:6px">
        둘은 다른 스위치입니다. 사내망은 서버가 뉴스에 못 닿으므로(web gateway 403)
        <b>수집은 꺼 두고 판정만 켜는 것이 정상</b>입니다 — 수집은 브라우저가 합니다.</div>
      <div style="color:var(--ink-3);font-size:var(--fs-xs);margin-bottom:10px">${srcLine}${fun?" · "+fun:""}</div>
      <button class="btn sm" onclick="collectTest(this)">연결 시험</button>
      <button class="btn sm" onclick="collectRun(this)">지금 한 번 수집</button>
      <span id="collectMsg" style="font-size:var(--fs-xs);margin-left:8px"></span>`;
  }catch(e){
    // 빈 화면으로 끝내지 않는다 — 사실 · 다음 할 일 · 갈 곳.
    el.innerHTML = `서버 수집 상태를 읽지 못했습니다 — 잠시 뒤 새로고침하거나
      <b>기준</b>에서 데이터 흐름을 확인하세요.
      <div style="margin-top:9px"><a class="btn sm" href="system">기준 →</a></div>
      <div class="help">${esc(e.message)}</div>`;
  }
}
async function collectTest(btn){
  // **막혔을 때가 이 단추를 누르는 이유다.** 예전에는 실패한 소스에서
  //   s.error.split(":")  → s.error 가 없어(서버는 why 를 준다) 그 자리에서 터졌다.
  // 내 자리에서는 전부 성공이라 그 가지가 안 돌았고, 사내에서 전부 막히자
  // 정작 알아야 할 "어디서 막혔나" 대신 스크립트 오류만 떴다.
  btn.disabled=true;
  const m=document.getElementById("collectMsg");
  m.innerHTML="시험 중…";
  try{
    const d=await fetch("api/collect/test").then(r=>r.json());
    const srcs=Array.isArray(d.sources)?d.sources:[];
    const bad=srcs.filter(s=>!s.ok);
    const rows=srcs.map(s=>{
      const why=s.why||s.error||"";
      const cls = s.ok ? "" : (s.stage === "HTTP" && d.state === "gated" ? "gated" : "no");
      return `<tr class="${cls}">
        <td>${esc(s.name||"-")}</td>
        <td>${esc(s.kind||"")}</td>
        <td>${s.ok?"통과":esc(s.stage||"실패")}</td>
        <td>${esc(why)}</td></tr>`;
    }).join("");
    const px=Object.entries(d.proxy||{}).filter(([,v])=>v);
    m.innerHTML =
      // **게이트웨이가 막는 것은 붉게 칠하지 않는다.** 이 시스템은 서버가
      // 나가지 못하는 것을 전제로 지었다 — 붉게 칠하면 정상을 고장으로 읽는다.
      (d.verdict?`<div class="ct-v ${d.state==="open"?"ok":d.state==="gated"?"gated":"no"}">${esc(d.verdict)}</div>`:"")
      + (d.summary?`<div class="help">재해 ${esc(d.summary["재해"]||"-")} · 뉴스 ${esc(d.summary["뉴스"]||"-")}</div>`:"")
      + (rows?`<table class="ct"><thead><tr><th>소스</th><th>갈래</th><th>단계</th><th>까닭</th></tr></thead><tbody>${rows}</tbody></table>`:"")
      + `<div class="help">프록시 ${px.length?px.map(([k,v])=>esc(k)+"="+esc(v)).join(" · "):"설정 없음"}</div>`;
  }catch(e){
    m.innerHTML=`<div class="ct-v no">시험을 못 돌렸습니다 — ${esc(e.message)}</div>`;
  } finally{ btn.disabled=false; }
}
async function collectRun(btn){
  btn.disabled=true; const m=document.getElementById("collectMsg"); m.textContent="수집 중…";
  try{
    const d=await fetch("api/collect/run",{method:"POST"}).then(r=>r.json());
    const fn=d.funnel||{};
    m.textContent=`수집 ${fn.fetched ?? "-"} → 후보 ${fn.deduped ?? "-"} · 사건 등록 ${(d.incidentsRegistered||[]).length}건`;
    renderCollect();
  }catch(e){ m.textContent="실패: "+e.message; } finally{ btn.disabled=false; }
}
renderCollect();
</script>

</body>
</html>
