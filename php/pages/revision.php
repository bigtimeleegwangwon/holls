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
<title>mGSCS · 등록 · 수정</title>

<style>
:root{
  --navy:#13294B; --navy-2:#1E3D6B; --navy-3:#2C5BA0;
  --ink:#1A1A1A; --ink-2:#4B5563; --ink-3:#9CA3AF;
  --line:#E5E7EB; --line-2:#F3F4F6; --bg:#FFFFFF; --surface:#F9FAFB; --surface-2:#F4F6F9;
  --red:#C8102E; --amber:#B45309; --green:#15803D; --accent-soft:#EEF3FA;
  --mono:ui-monospace,SFMono-Regular,Menlo,Consolas,"D2Coding",monospace;
}
*{box-sizing:border-box}
body{margin:0;background:var(--bg);color:var(--ink);
  font:14px/1.7 -apple-system,BlinkMacSystemFont,"Malgun Gothic","맑은 고딕",sans-serif}
.top{border-bottom:2px solid var(--navy);padding:16px 24px;display:flex;
  align-items:baseline;gap:14px;flex-wrap:wrap;background:var(--surface)}
.top h1{margin:0;font-size:var(--fs-lg);letter-spacing:-.3px}
.top .sub{font-size:var(--fs-sm);color:var(--ink-2)}
.top .who{margin-left:auto;font:600 11px/1 var(--mono);color:var(--navy-2);
  background:var(--accent-soft);border:1px solid #CBD8EA;padding:5px 9px;border-radius:var(--r-md)}
.top a{font-size:var(--fs-sm);color:var(--navy-3);text-decoration:none;margin-right:10px}
.top a:hover{text-decoration:underline}
main{max-width:var(--page-w);margin:0 auto;padding:24px 24px 80px}
.kpi{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:9px;margin-bottom:20px}
.kpi .c{border:1px solid var(--line);border-radius:var(--r-lg);padding:10px 12px;background:#fff}
.kpi .k{font:700 9.5px/1 var(--mono);letter-spacing:1px;color:var(--navy-3);text-transform:uppercase}
.kpi .v{font-size:var(--fs-xl);font-weight:800;margin:5px 0 2px;letter-spacing:-.5px}
.kpi .d{font-size:var(--fs-2xs);color:var(--ink-2)}
.kpi .c.hl{border-color:#CBD8EA;background:var(--accent-soft)}
h2{font-size:var(--fs-md);margin:26px 0 8px;letter-spacing:-.2px;padding-bottom:6px;border-bottom:1px solid var(--line)}
p.note{margin:0 0 12px;font-size:var(--fs-sm);color:var(--ink-2);max-width:80ch}
.tabs{display:flex;gap:0;margin-bottom:14px;flex-wrap:wrap}
.tabs button{border:1px solid var(--line);background:#fff;padding:7px 14px;font-size:var(--fs-sm);
  cursor:pointer;font-family:inherit;color:var(--ink-2)}
.tabs button+button{margin-left:-1px}
.tabs button[aria-selected="true"]{background:var(--navy);color:#fff;border-color:var(--navy);font-weight:700}
.panel{display:none}
.panel.on{display:block}
.grid2{display:grid;grid-template-columns:repeat(auto-fit,minmax(230px,1fr));gap:10px;margin-bottom:12px}
label{display:block;font-size:var(--fs-xs);font-weight:700;color:var(--ink-2);margin-bottom:3px}
label .req{color:var(--red);margin-left:2px}
input,select,textarea{width:100%;padding:7px 9px;border:1px solid var(--line);border-radius:var(--r-md);
  font:13px/1.5 inherit;font-family:inherit;background:#fff;color:var(--ink)}
input:focus,select:focus,textarea:focus{outline:2px solid var(--navy-3);outline-offset:-1px;border-color:var(--navy-3)}
textarea{font-family:var(--mono);font-size:var(--fs-xs);min-height:110px;resize:vertical}
.hint{font-size:var(--fs-2xs);color:var(--ink-3);margin-top:3px}
.actions{display:flex;gap:8px;align-items:center;margin-top:14px;flex-wrap:wrap}
.btn{border:1px solid var(--navy);background:var(--navy);color:#fff;padding:8px 16px;border-radius:var(--r-md);
  font:700 13px/1 inherit;font-family:inherit;cursor:pointer}
.btn:hover{background:var(--navy-2)}
.btn.ghost{background:#fff;color:var(--navy);}
a.btn{text-decoration:none;display:inline-flex;align-items:center}
.gplink{color:var(--ink);font-weight:700;text-decoration:none}
.gplink:hover{color:var(--navy);text-decoration:underline}
.btn.ghost:hover{background:var(--line-2)}
.btn:disabled{opacity:.5;cursor:not-allowed}
.msg{margin-top:12px;padding:10px 13px;border-radius:var(--r-md);font-size:var(--fs-sm);display:none;white-space:pre-wrap}
.msg.on{display:block}
.msg.ok{background:#F5FAF6;border:1px solid #C4DBCF;box-shadow:0 1px 2px rgba(26,31,41,.05);color:#14532D}
.msg.err{background:#FDF7F8;border:1px solid #EBC7CD;box-shadow:0 1px 2px rgba(26,31,41,.05);color:#7F1D28}
table{border-collapse:collapse;width:100%;font-size:var(--fs-sm);margin-bottom:10px}
th,td{border-bottom:1px solid var(--line);padding:6px 8px;text-align:left;vertical-align:top}
th{background:var(--surface-2);font-weight:700;color:var(--navy);font-size:var(--fs-xs);
  position:sticky;top:0;border-bottom:2px solid var(--line)}
.scroll{overflow:auto;max-height:440px;border:1px solid var(--line);border-radius:var(--r-lg)}
.pill{display:inline-block;font:600 10px/1 var(--mono);padding:3px 6px;border-radius:var(--r-sm);border:1px solid var(--line)}
.pill.v{background:#F0F8F2;color:var(--green);border-color:#C6E4CF}
.pill.a{background:var(--surface-2);color:var(--ink-2)}
.pill.i{background:var(--accent-soft);color:var(--navy-2);border-color:#CBD8EA}
.pill.no{background:#FDF3F4;color:var(--red);border-color:#F0CDD3}
.usec{margin:14px 0 4px}
.uttl{font-size:var(--fs-xs);font-weight:700;color:var(--ink-2);margin:12px 0 6px}
.uttl.ok{color:var(--green)}
.ubox{border:1px solid var(--line2);border-radius:var(--r-lg);padding:9px 11px;margin:0 0 7px;background:var(--card)}
.ubox.done{background:var(--surface-2);border-color:var(--line)}
.uhd{display:flex;align-items:center;gap:7px;flex-wrap:wrap;margin:0 0 7px}
.uhd b{font-size:var(--fs-sm)}
.uhd .tag{font:600 10px/1 var(--mono);padding:3px 6px;border-radius:var(--r-sm);
          border:1px solid var(--line);background:var(--surface-2);color:var(--ink-2)}
.umeta{font-size:var(--fs-2xs);color:var(--ink-3)}
.urow{display:flex;align-items:center;gap:6px;flex-wrap:wrap}
.urow .or{font-size:var(--fs-2xs);color:var(--ink-3);margin:0 2px}
button.sug{display:inline-flex;align-items:center;gap:5px;padding:5px 9px;font:inherit;
           font-size:var(--fs-xs);border:1px solid var(--navy-line);background:var(--accent-soft);
           color:var(--navy-2);border-radius:var(--r-sm);cursor:pointer}
button.sug:hover{background:var(--navy);color:#fff;border-color:var(--navy)}
button.sug .sc{font:600 9px/1 var(--mono);opacity:.7}
input.pick{padding:5px 8px;font:inherit;font-size:var(--fs-xs);width:190px;
           border:1px solid var(--line2);border-radius:var(--r-sm)}
.rem{font-size:var(--fs-2xs);color:var(--ink-2);display:inline-flex;align-items:center;gap:4px;white-space:nowrap}
.urow>*{flex:0 0 auto}
.ubox.done .urow{gap:10px}
button.lnk{background:none;border:none;padding:0 4px;font:inherit;font-size:var(--fs-2xs);
           color:var(--ink-3);text-decoration:underline;cursor:pointer}
tbody tr.warn>td{background:#FDF7F8}   /* 사람이 골라야 하는 줄 */
#hb-table select{font:inherit;font-size:var(--fs-xs);padding:3px 5px;max-width:170px}
pre.report{background:var(--navy);color:#E8EEF6;border-radius:var(--r-lg);padding:14px 16px;
  overflow:auto;max-height:420px;font:11.5px/1.65 var(--mono);margin:0}
/* 강조바 대신 테두리와 옅은 음영으로 층을 만든다. */
.callout{border:1px solid var(--line2);box-shadow:0 2px 6px rgba(26,31,41,.05), 0 1px 2px rgba(26,31,41,.04);background:var(--surface);padding:12px 15px;
  border-radius:0 var(--r-md) var(--r-md) 0;margin:0 0 14px;font-size:var(--fs-sm)}
.callout.warn{border-color:#EADFC8;background:#FDFAF4}
.callout b{display:block;margin-bottom:3px}
.filter{display:flex;gap:8px;margin-bottom:8px;flex-wrap:wrap}
.filter input,.filter select{width:auto;min-width:150px}
</style>


<!-- ── mGSCS 통일 테마 · 260810 ────────────────────────────────────
     한 곳에서만 정의하고 전 화면에 같은 블록을 주입한다.
     흰 배경 + 네이비 단색. 다크모드는 쓰지 않는다(사내 인쇄·프로젝터 대비).
     Pretendard는 동봉본을 로컬 로드한다(R3·R6 — 외부 CDN 0).
     mTRACE와 같은 팔레트를 써 사내 포털에서 한 계열로 읽히게 한다.
────────────────────────────────────────────────────────────────── -->
<?php include __DIR__ . '/_shared/theme.php'; ?>
</head>
<body>

<?php $here = 'revision'; ?><?php $here_label = '등록 · 수정'; ?>
<?php include __DIR__ . '/_shared/nav.php'; ?>

<main>

  <h1 class="pagetitle">등록·수정</h1>
  <p class="lead">명부에 없던 BP · 좌표 · 연락처를 원장에 넣는 곳.</p>

  

<div class="callout">
  <b>이 화면이 하는 일</b>
  입력한 내용은 <code>revision/ledger.jsonl</code>에 <b>사건으로 append</b>됩니다. 기존 줄을 고치거나 지우지 않습니다.
  잘못 넣었으면 상쇄 사건(폐쇄 처리)으로 취소하고, 이력은 그대로 남깁니다.
  저장하면 즉시 재생성(rebuild)되어 다음 경보부터 반영됩니다.
</div>

<div class="kpi" id="kpi"></div>

<div class="tabs" role="tablist">
  <button role="tab" aria-selected="true" data-p="site">신규 사업장 추가</button>
  <button role="tab" aria-selected="false" data-p="coord">좌표 승급</button>
  <button role="tab" aria-selected="false" data-p="hotline">연락처 등록</button>
  <button role="tab" aria-selected="false" data-p="bulk">사업장 일괄</button>
  <button role="tab" aria-selected="false" data-p="hbulk">연락처 일괄</button>
  <button role="tab" aria-selected="false" data-p="gaps">연락처 빈 곳</button>
  <button role="tab" aria-selected="false" data-p="list">현황 조회</button>
  <button role="tab" aria-selected="false" data-p="report">빌드 리포트</button>
</div>

<!-- 신규 site -->
<section class="panel on" id="p-site">
  <h2>신규 사업장 추가</h2>
  <p class="note">기존 BP가 공장을 늘렸거나, 새 BP가 편입됐을 때 씁니다. 가장 자주 쓰는 기능입니다.</p>
  <div class="grid2">
    <div><label>BP 회사명 <span class="req">*</span></label><input id="s-bp" list="bpnames" placeholder="예: TOK"><datalist id="bpnames"></datalist></div>
    <div><label>site 이름 <span class="req">*</span></label><input id="s-name" placeholder="예: 고리야마 제2공장"></div>
    <div><label>국가 <span class="req">*</span></label>
      <select id="s-country"><option>JP</option><option>KR</option><option>CN</option><option>TW</option><option>US</option><option>EU</option></select></div>
    <div><label>소재군 <span class="req">*</span></label>
      <select id="s-mat" multiple size="4">
        <option>Photo</option><option>Chemical</option><option>Slurry</option><option>Pad</option>
        <option>Gas</option><option>Precursor</option><option>Wafer</option><option>Target</option>
      </select><div class="hint">Cmd·Ctrl 클릭으로 복수 선택</div></div>
    <div><label>공정 범위</label>
      <select id="s-proc" multiple size="4"><option selected>FAB</option><option>WLP</option><option>BE</option><option>TEST</option><option>COMMON</option></select></div>
    <div><label>행정구역</label><input id="s-admin" placeholder="예: 福島 / 충북"><div class="hint">현·도도부현·시도 단위</div></div>
    <div><label>주소</label><input id="s-addr" placeholder="예: Koriyama, Fukushima"></div>
    <div><label>위도</label><input id="s-lat" type="number" step="0.0001" placeholder="37.4006"></div>
    <div><label>경도</label><input id="s-lon" type="number" step="0.0001" placeholder="140.3597"></div>
    <div><label>좌표 신뢰등급</label>
      <select id="s-prec">
        <option value="address_geocoded">주소 기반 (거리 계산 제외)</option>
        <option value="verified_site">실제 제조소 확인 (거리 계산 사용)</option>
        <option value="prefecture_centroid">도도부현 중심점 (거리 계산 제외)</option>
        <option value="unknown">미확인</option>
      </select>
      <div class="hint">확인된 제조소 좌표만 거리 판정에 쓰입니다</div></div>
  </div>
  <div><label>근거 <span class="req">*</span></label><input id="s-reason" placeholder="예: BP 회신 확인 · 2026 신규 가동"></div>
  <div class="actions"><button class="btn" data-submit="site">원장에 추가</button></div>
  <div class="msg" id="m-site"></div>
</section>

<!-- 좌표 승급 -->
<section class="panel" id="p-coord">
  <h2>좌표 승급</h2>
  <p class="note">
    현재 <b>전 site가 거리 계산에서 제외</b>돼 있습니다. 사외에서는 실제 제조소 좌표를 알 수 없어 주소 기반으로만 넣었기 때문입니다.
    여기서 실측 좌표를 등록해 <code>verified_site</code>로 올리는 것이 정밀도 향상의 유일한 경로이고, 이 화면의 핵심 KPI입니다.
  </p>
  <div class="grid2">
    <div><label>site <span class="req">*</span></label><select id="c-site"></select></div>
    <div><label>위도 <span class="req">*</span></label><input id="c-lat" type="number" step="0.0001"></div>
    <div><label>경도 <span class="req">*</span></label><input id="c-lon" type="number" step="0.0001"></div>
  </div>
  <div><label>근거 <span class="req">*</span></label><input id="c-reason" placeholder="예: 공장 정문 좌표 실측 · 방문 확인"></div>
  <div class="actions"><button class="btn" data-submit="coord">verified_site로 승급</button></div>
  <div class="msg" id="m-coord"></div>
</section>

<!-- 연락처 -->
<section class="panel" id="p-hotline">
  <h2>연락처 등록</h2>
  <div class="callout warn"><b>개인정보입니다.</b>이 정보는 사내에서만 관리합니다. 사외 baseline에는 전량 공란으로 나가며, 외부 LLM에도 전송되지 않습니다.</div>
  <div class="grid2">
    <div><label>site <span class="req">*</span></label><select id="h-site"></select></div>
    <div><label>담당자</label><input id="h-name"></div>
    <div><label>연락처</label><input id="h-phone"></div>
    <div><label>이메일</label><input id="h-email" type="email"></div>
  </div>
  <div><label>근거 <span class="req">*</span></label><input id="h-reason" placeholder="예: 품질 담당 등록 · 2026 정기 확인"></div>
  <div class="actions"><button class="btn" data-submit="hotline">등록</button></div>
  <div class="msg" id="m-hotline"></div>
</section>

<!-- 엑셀 일괄 -->
<section class="panel" id="p-bulk">
  <h2>사업장 일괄 등록</h2>
  <p class="note">
    실무에서 신규 site 정보는 엑셀로 옵니다. 손으로 JSONL을 쓰게 하면 아무도 쓰지 않으므로,
    엑셀에서 복사한 표를 그대로 붙여넣을 수 있게 했습니다. 열 순서를 지켜주세요.
  </p>
  <div><label>열 순서: BP · 사업장명 · 국가 · 소재군(쉼표) · 행정구역 · 위도 · 경도 · 좌표 등급 · 근거</label>
    <textarea id="b-text" placeholder="TOK&#9;고리야마 제2공장&#9;JP&#9;Photo&#9;福島&#9;37.4006&#9;140.3597&#9;verified_site&#9;BP 회신 확인
신에츠&#9;니가타 신공장&#9;JP&#9;Photo,Chemical&#9;新潟&#9;37.9&#9;139.0&#9;address_geocoded&#9;공시 확인"></textarea>
    <div class="hint">탭 구분(엑셀 복사) 또는 쉼표 구분 모두 인식합니다. 빈 줄은 건너뜁니다.</div></div>
  <div class="actions">
    <button class="btn ghost" id="b-preview">미리보기</button>
    <button class="btn" data-submit="bulk" id="b-submit" disabled>일괄 등록</button>
  </div>
  <div class="msg" id="m-bulk"></div>
  <div id="b-table"></div>
</section>

<!-- 연락처 일괄 -->
<section class="panel" id="p-hbulk">
  <h2>연락처 일괄 등록</h2>
  <div class="callout warn"><b>개인정보입니다.</b>사내에서만 관리합니다. 사외 baseline·외부 LLM 으로 나가지 않습니다.</div>
  <p class="note">
    엑셀 한 줄이 <b>BP 한 곳과 담당자 한 명</b>의 짝입니다. 한 BP 에 담당자가 여럿이어도,
    한 사람이 여러 BP 를 맡아도 그대로 넣으면 됩니다. 회사별로 알아서 묶습니다.
  </p>
  <div>
    <label>열 순서: BP(표준) · 소재군 · BP명(자체) · BPcode · 담당자 · 연락처 · 메일 · 비고 · 송부</label>
    <textarea id="hb-text" placeholder="모사BP-001&#9;Chemical, Slurry&#9;모사BP-001(주)&#9;B1&#9;홍길동&#9;010-0000-0000&#9;hong@example.com&#9;품질팀&#9;Y"></textarea>
    <div class="hint">엑셀에서 표 전체를 복사해 붙여넣으세요. 머리글 줄은 자동으로 건너뜁니다.</div>
  </div>
  <div class="actions">
    <button class="btn ghost" id="hb-preview">미리보기</button>
    <button class="btn" id="hb-submit" disabled>반영</button>
  </div>
  <div class="msg" id="m-hbulk"></div>
  <div id="hb-sum"></div>
  <div id="hb-un"></div>
  <datalist id="hb-bpnames"></datalist>
  <div id="hb-table"></div>
</section>

<!-- 연락처 빈 곳 -->
<section class="panel" id="p-gaps">
  <h2>연락처 빈 곳</h2>
  <p class="note">
    채운 곳이 아니라 <b>빈 곳</b>을 셉니다. 사건이 났을 때 물어볼 데가 없다는 사실을
    그때 처음 알면 늦습니다. 급한 순서로 놓았습니다 — 품질 등급 · 점검 대상 · 사업장이 많은 곳.
  </p>
  <div class="kpi" id="g-kpi"></div>
  <div class="filter">
    <input id="g-q" placeholder="BP·소재군·국가 검색">
    <select id="g-grade">
      <option value="">등급 전체</option>
      <option value="Q">품질 등급</option>
      <option value="D">개발 등급</option>
      <option value="-">등급 없음</option>
    </select>
    <select id="g-tier"><option value="">티어 전체</option></select>
    <select id="g-state">
      <option value="empty">비어 있는 곳만</option>
      <option value="all">전체</option>
      <option value="filled">채운 곳만</option>
    </select>
    <a class="btn ghost" id="g-csv" href="api/hotline/gaps?fmt=csv"
       title="채우기 요청 목록으로 쓰세요. 연락처는 들어가지 않습니다.">CSV 내려받기</a>
  </div>
  <div class="scroll"><table id="t-gaps"><thead><tr>
    <th>BP</th><th>등급</th><th>티어</th><th>소재군</th><th>국가</th><th>사업장</th><th>담당자</th>
  </tr></thead><tbody></tbody></table></div>
  <div class="hint" id="g-count"></div>
</section>

<!-- 현황 -->
<section class="panel" id="p-list">
  <h2>현황 조회</h2>
  <div class="filter">
    <input id="f-q" placeholder="BP·사업장·행정구역 검색">
    <select id="f-country"><option value="">전체 국가</option></select>
    <select id="f-prec"><option value="">전체 좌표 등급</option></select>
    <select id="f-layer"><option value="">전체 출처</option><option value="baseline">사외 baseline</option><option value="revision">사내 리비전</option></select>
  </div>
  <div class="scroll"><table id="t-list"><thead><tr>
    <th>siteId</th><th>BP</th><th>소재군</th><th>국가·행정구역</th><th>site</th><th>좌표 등급</th><th>연락처</th><th>출처</th>
  </tr></thead><tbody></tbody></table></div>
  <div class="hint" id="l-count"></div>
</section>

<!-- 리포트 -->
<section class="panel" id="p-report">
  <h2>빌드 리포트</h2>
  <p class="note">마지막 재생성 결과입니다. 충돌·건너뜀이 있으면 여기에 전부 남습니다 — 조용히 넘어가지 않습니다.</p>
  <div class="actions" style="margin-top:0"><button class="btn ghost" id="r-rebuild">재생성 실행</button></div>
  <div class="msg" id="m-report"></div>
  <pre class="report" id="r-text"></pre>
</section>

<div class="nextstep"><span>넣은 내용은 <b>저장 즉시 판정에 반영</b>됩니다. 반영된 모습은 BP 기준 정보에서 확인합니다.</span><a class="go" href="<?= url('/bp') ?>">BP 기준 정보에서 확인 →</a></div>

  <?php include __DIR__ . '/_shared/foot.php'; ?>
</main>

<script>window.MGSCS_API="<?= url('/api/resolved') ?>".replace(/resolved$/,"");</script>
<!-- R7 · 데이터 임베드. 스크립트보다 반드시 앞에 둔다 -->
<?php include __DIR__ . '/_shared/grade.php'; ?>
<script type="application/json" id="d-rows"><?= $bp_rows_json ?></script>
<script type="application/json" id="d-taxonomy"><?= $taxonomy_json ?></script>
<script type="text/plain" id="d-report"><?= htmlspecialchars($build_report ?? '') ?></script>
<script>document.body.dataset.actor = <?= json_encode($actor) ?>;</script>


<script>
const ROWS = JSON.parse(document.getElementById("d-rows").textContent);
const ACTOR = document.body.dataset.actor || "";
const $ = (s) => document.querySelector(s);
const esc = (s) => String(s ?? "").replace(/[&<>"]/g, (c) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;" }[c]));

/* ── 탭 ── */
document.querySelectorAll('[role="tab"]').forEach((b) => {
  b.addEventListener("click", () => {
    document.querySelectorAll('[role="tab"]').forEach((x) => x.setAttribute("aria-selected", x === b));
    document.querySelectorAll(".panel").forEach((p) => p.classList.toggle("on", p.id === "p-" + b.dataset.p));
  });
});

/* ── KPI ── */
function renderKpi(rows) {
  const active = rows.filter((r) => r.active !== false);
  const verified = active.filter((r) => r.coordinateStatus === "verified_site");
  const hot = active.filter((r) => Object.values(r.hotline || {}).some((v) => v));
  const internal = active.filter((r) => String(r.siteId || "").startsWith("I-"));
  const pct = (n) => (active.length ? (n / active.length * 100).toFixed(1) + "%" : "0%");
  $("#kpi").innerHTML = [
    ["활성 site", active.length, `${new Set(active.map((r) => r.bpName)).size}개사`],
    ["정밀 좌표", verified.length, `${pct(verified.length)} · 거리 계산 자격`, true],
    ["연락처 채움", hot.length, pct(hot.length), true],
    ["사내 추가분", internal.length, "변경 이력으로 추가된 사업장"],
  ].map(([k, v, d, hl]) => `<div class="c${hl ? " hl" : ""}"><div class="k">${k}</div><div class="v">${v}</div><div class="d">${esc(d)}</div></div>`).join("");
}

/* ── 셀렉트 채우기 ── */
function fillSelects(rows) {
  const active = rows.filter((r) => r.active !== false);
  const opts = active
    .slice()
    .sort((a, b) => String(a.bpName).localeCompare(String(b.bpName)))
    .map((r) => `<option value="${esc(r.siteId)}">${esc(r.bpName)} · ${esc(r.siteName || "")} (${esc(r.coordinateStatus || "")})</option>`)
    .join("");
  $("#c-site").innerHTML = opts;
  $("#h-site").innerHTML = opts;
  $("#bpnames").innerHTML = [...new Set(active.map((r) => r.bpName))].sort()
    .map((n) => `<option value="${esc(n)}">`).join("");
  const uniq = (k) => [...new Set(active.map((r) => r[k]).filter(Boolean))].sort();
  $("#f-country").innerHTML = '<option value="">전체 국가</option>' + uniq("country").map((c) => `<option>${esc(c)}</option>`).join("");
  $("#f-prec").innerHTML = '<option value="">전체 좌표 등급</option>' + uniq("coordinateStatus").map((c) => `<option>${esc(c)}</option>`).join("");
}

/* ── 현황 표 ── */
function renderList(rows) {
  const q = $("#f-q").value.trim().toLowerCase();
  const fc = $("#f-country").value, fp = $("#f-prec").value, fl = $("#f-layer").value;
  const filtered = rows.filter((r) => {
    if (fc && r.country !== fc) return false;
    if (fp && r.coordinateStatus !== fp) return false;
    if (fl && (r.provenance || {}).layer !== fl) return false;
    if (!q) return true;
    return [r.bpName, r.siteName, r.admin1, r.admin1Ko, r.siteId].some((v) => String(v || "").toLowerCase().includes(q));
  });
  const precPill = (p) => p === "verified_site" ? '<span class="pill v">정밀</span>'
    : p === "address_geocoded" ? '<span class="pill a">주소</span>'
    : '<span class="pill no">' + esc(p || "미확인") + "</span>";
  $("#t-list tbody").innerHTML = filtered.slice(0, 400).map((r) => `<tr>
    <td><code>${esc(r.siteId)}</code></td><td>${esc(r.bpName)}</td>
    <td>${esc((r.materialGroups || []).join(", "))}</td>
    <td>${esc(r.country)} · ${esc(r.admin1Ko || r.admin1 || "")}</td>
    <td>${esc(r.siteName || "")}</td><td>${precPill(r.coordinateStatus)}</td>
    <td>${Object.values(r.hotline || {}).some((v) => v) ? '<span class="pill v">등록</span>' : '<span class="pill no">미등록</span>'}</td>
    <td>${(r.provenance || {}).layer === "revision" ? '<span class="pill i">사내</span>' : '<span class="pill a">baseline</span>'}</td>
  </tr>`).join("");
  $("#l-count").textContent = `${filtered.length}건 표시${filtered.length > 400 ? " (상위 400건만)" : ""} · 전체 ${rows.length}건`;
}
["#f-q", "#f-country", "#f-prec", "#f-layer"].forEach((s) =>
  document.querySelector(s).addEventListener("input", () => renderList(window.__ROWS)));

/* ── 서버 통신 ── */
function show(id, ok, text) {
  const el = $(id);
  el.className = "msg on " + (ok ? "ok" : "err");
  el.textContent = text;
}
async function post(path, body) {
  const r = await fetch(path, {
    method: "POST", headers: { "Content-Type": "application/json" },
    body: JSON.stringify(body || {}),
  });
  return { status: r.status, data: await r.json().catch(() => ({})) };
}
function refresh(stats) {
  return fetch(MGSCS_API+'resolved').then((r) => r.json()).then((rows) => {
    window.__ROWS = rows;
    renderKpi(rows); fillSelects(rows); renderList(rows);
  });
}
function statLine(s) {
  return s ? `\n활성 ${s["활성"]}건 · 정밀 좌표 ${s["정밀 좌표(verified_site)"]}건 · 연락처 ${s["연락처 채움률"]}` : "";
}

/* ── 사건 생성 ── */
const multi = (id) => [...$(id).selectedOptions].map((o) => o.value);
function buildEvent(kind) {
  if (kind === "site") {
    const bp = $("#s-bp").value.trim(), name = $("#s-name").value.trim();
    const mats = multi("#s-mat"), reason = $("#s-reason").value.trim();
    if (!bp || !name || !mats.length || !reason) throw new Error("BP·사업장명·소재군·근거는 필수입니다.");
    const key = bp.toLowerCase().replace(/[\s.\-()]/g, "");
    const seq = String(window.__ROWS.filter((r) => String(r.siteId).startsWith("I-")).length + 1).padStart(2, "0");
    const lat = $("#s-lat").value, lon = $("#s-lon").value;
    const prec = $("#s-prec").value;
    if (prec === "verified_site" && (!lat || !lon)) throw new Error("verified_site는 좌표가 필요합니다.");
    return {
      op: "site.add", bpKey: key,
      siteId: `I-${$("#s-country").value}-${key.toUpperCase().slice(0, 12)}-${seq}`,
      after: {
        bpName: bp, siteName: name, country: $("#s-country").value,
        materialGroups: mats, processScope: multi("#s-proc"),
        admin1: $("#s-admin").value.trim(), admin1Ko: $("#s-admin").value.trim(),
        address: $("#s-addr").value.trim(),
        lat: lat ? Number(lat) : null, lon: lon ? Number(lon) : null,
        coordinateStatus: prec, distanceEligible: prec === "verified_site",
        siteRole: "plant", tier: "점검",
      },
      reason,
    };
  }
  if (kind === "coord") {
    const lat = $("#c-lat").value, lon = $("#c-lon").value, reason = $("#c-reason").value.trim();
    if (!lat || !lon || !reason) throw new Error("좌표와 근거는 필수입니다.");
    return {
      op: "coord.verify", siteId: $("#c-site").value,
      after: { lat: Number(lat), lon: Number(lon), coordinateStatus: "verified_site", distanceEligible: true },
      reason,
    };
  }
  if (kind === "hotline") {
    const reason = $("#h-reason").value.trim();
    if (!reason) throw new Error("근거는 필수입니다.");
    return {
      op: "hotline.set", siteId: $("#h-site").value,
      after: { contactName: $("#h-name").value.trim(), phone: $("#h-phone").value.trim(), email: $("#h-email").value.trim() },
      reason,
    };
  }
  throw new Error("알 수 없는 입력");
}

document.querySelectorAll("[data-submit]").forEach((btn) => {
  btn.addEventListener("click", async () => {
    const kind = btn.dataset.submit;
    const msg = "#m-" + kind;
    if (kind === "bulk") return submitBulk();
    let ev;
    try { ev = buildEvent(kind); } catch (e) { return show(msg, false, e.message); }
    btn.disabled = true;
    try {
      const { status, data } = await post(MGSCS_API+'ledger', ev);
      if (status === 200 && data.ok) {
        show(msg, true, `등록됐습니다 — ${ev.op} ${ev.siteId || ev.bpKey}${statLine(data.stats)}`);
        await refresh();
      } else {
        show(msg, false, `실패: ${data.error || status}\n${(data.problems || []).join("\n")}`);
      }
    } catch (e) { show(msg, false, "통신 오류: " + e.message); }
    btn.disabled = false;
  });
});

/* ── 엑셀 일괄 ── */
let bulkRows = [];
function parseBulk(text) {
  return text.split(/\r?\n/).map((l) => l.trim()).filter(Boolean).map((line, i) => {
    const c = line.includes("\t") ? line.split("\t") : line.split(",");
    const t = (n) => (c[n] || "").trim();
    return {
      line: i + 1, bp: t(0), name: t(1), country: t(2) || "JP",
      mats: t(3).split(/[,;·]/).map((s) => s.trim()).filter(Boolean),
      admin: t(4), lat: t(5), lon: t(6), prec: t(7) || "address_geocoded", reason: t(8) || "엑셀 일괄 등록",
    };
  });
}
$("#b-preview").addEventListener("click", () => {
  bulkRows = parseBulk($("#b-text").value);
  const bad = bulkRows.filter((r) => !r.bp || !r.name || !r.mats.length);
  $("#b-table").innerHTML = bulkRows.length ? `<div class="scroll"><table><thead><tr>
      <th>#</th><th>BP</th><th>site</th><th>국가</th><th>소재군</th><th>행정구역</th><th>좌표</th><th>등급</th><th>상태</th></tr></thead><tbody>
      ${bulkRows.map((r) => `<tr><td>${r.line}</td><td>${esc(r.bp)}</td><td>${esc(r.name)}</td><td>${esc(r.country)}</td>
        <td>${esc(r.mats.join(", "))}</td><td>${esc(r.admin)}</td><td>${esc(r.lat)},${esc(r.lon)}</td><td>${esc(r.prec)}</td>
        <td>${!r.bp || !r.name || !r.mats.length ? '<span class="pill no">필수 누락</span>' : '<span class="pill v">확인</span>'}</td></tr>`).join("")}
      </tbody></table></div>` : "";
  $("#b-submit").disabled = !bulkRows.length || bad.length > 0;
  show("#m-bulk", !bad.length, bad.length
    ? `${bad.length}행에 필수값(BP·site명·소재군)이 없습니다. 고친 뒤 다시 미리보기 하세요.`
    : `${bulkRows.length}행 확인. 등록을 누르면 원장에 순서대로 추가됩니다.`);
});
async function submitBulk() {
  const btn = $("#b-submit"); btn.disabled = true;
  let ok = 0; const fails = [];
  for (const r of bulkRows) {
    const key = r.bp.toLowerCase().replace(/[\s.\-()]/g, "");
    const seq = String(window.__ROWS.filter((x) => String(x.siteId).startsWith("I-")).length + ok + 1).padStart(2, "0");
    const ev = {
      op: "site.add", bpKey: key,
      siteId: `I-${r.country}-${key.toUpperCase().slice(0, 12)}-${seq}`,
      after: {
        bpName: r.bp, siteName: r.name, country: r.country, materialGroups: r.mats,
        admin1: r.admin, admin1Ko: r.admin,
        lat: r.lat ? Number(r.lat) : null, lon: r.lon ? Number(r.lon) : null,
        coordinateStatus: r.prec, distanceEligible: r.prec === "verified_site",
        siteRole: "plant", tier: "점검",
      },
      reason: r.reason,
    };
    const { status, data } = await post(MGSCS_API+'ledger', ev);
    if (status === 200 && data.ok) ok++;
    else fails.push(`${r.line}행 ${r.bp}: ${data.error || status}${(data.problems || []).length ? " — " + data.problems.join(", ") : ""}`);
  }
  await refresh();
  show("#m-bulk", !fails.length, `${ok}건 등록 완료${fails.length ? `\n실패 ${fails.length}건:\n` + fails.join("\n") : ""}`);
  btn.disabled = false;
}

/* ── 연락처 빈 곳 ── */
let GAPS = null;
async function loadGaps() {
  try {
    const r = await fetch(MGSCS_API + "hotline/gaps");
    GAPS = await r.json();
    const c = GAPS.counts || {};
    $("#g-kpi").innerHTML = [
      ["명부 전체", c["전체"], "활성 BP"],
      ["빈 곳", c["빈곳"], `전체의 ${c["전체"] ? Math.round(c["빈곳"] / c["전체"] * 100) : 0}%`, true],
      ["품질 등급 빈 곳", c["품질등급빈곳"], `품질 등급 ${c["품질등급전체"]}곳 가운데`, true],
      ["점검 대상 빈 곳", c["점검빈곳"], "사건 때 바로 묻는 곳"],
    ].map(([k, v, d, hl]) => `<div class="c${hl ? " hl" : ""}"><div class="k">${k}</div><div class="v">${v ?? 0}</div><div class="d">${esc(d)}</div></div>`).join("");
    const tiers = [...new Set([...(GAPS.empty || []), ...(GAPS.filled || [])].map((g) => g.tier).filter(Boolean))].sort();
    $("#g-tier").innerHTML = '<option value="">티어 전체</option>' + tiers.map((t) => `<option>${esc(t)}</option>`).join("");
    renderGaps();
  } catch (e) { $("#g-count").textContent = "불러오지 못했습니다: " + e.message; }
}
function renderGaps() {
  if (!GAPS) return;
  const q = $("#g-q").value.trim().toLowerCase();
  const fg = $("#g-grade").value, ft = $("#g-tier").value, fs = $("#g-state").value;
  let list = fs === "filled" ? (GAPS.filled || [])
           : fs === "all" ? [...(GAPS.empty || []), ...(GAPS.filled || [])]
           : (GAPS.empty || []);
  list = list.filter((g) => {
    if (fg && gradeCode(g.grade) !== fg) return false;
    if (ft && g.tier !== ft) return false;
    if (q && ![g.bp, g.groups.join(" "), g.countries.join(" ")].join(" ").toLowerCase().includes(q)) return false;
    return true;
  });
  $("#t-gaps").querySelector("tbody").innerHTML = list.map((g) => `<tr>
    <td><a class="gplink" href="bp?tab=hot&bp=${encodeURIComponent(g.bp)}"
           title="BP 기준 정보에서 이 회사의 연락처 채우기">${esc(g.bp)}</a></td>
    <td><span class="pill ${gradeCode(g.grade) === "Q" ? "i" : "a"}">${esc(gradeLabel(g.grade))}</span></td>
    <td>${esc(g.tier || "")}</td>
    <td>${esc(g.groups.join(", "))}</td>
    <td>${esc(g.countries.join(" "))}</td>
    <td>${g.sites}</td>
    <td>${g.contacts ? `<span class="pill v">${g.contacts}명</span>` : '<span class="pill no">없음</span>'}</td>
  </tr>`).join("");
  $("#g-count").textContent = `${list.length}곳`
    + (fs === "empty" ? " · 이 목록이 곧 채워야 할 일감입니다." : "");
  $("#g-csv").href = "api/hotline/gaps?fmt=csv" + (fs === "empty" ? "" : "&all=1");
}
["g-q", "g-grade", "g-tier", "g-state"].forEach((id) => {
  const el = document.getElementById(id);
  el.addEventListener(id === "g-q" ? "input" : "change", renderGaps);
});
document.querySelector('[data-p="gaps"]').addEventListener("click", () => { if (!GAPS) loadGaps(); });

/* ── 연락처 일괄 ──
   못 맞춘 이름은 사람이 잇는다. 다만 줄마다 묻지 않는다 — 361행이면 같은
   회사가 여러 줄에 걸쳐 나오고, 줄마다 고르게 하면 같은 선택을 열 번 한다.
   이름 하나를 이으면 그 이름의 모든 줄에 한꺼번에 적용된다. */
let hbData = null, hbRows = [], hbAssign = {}, hbRemember = {}, hbOkd = {};

function bpNames() {
  return [...new Set((window.__ROWS || ROWS).filter((r) => r.active !== false).map((r) => r.bpName))].sort();
}
function bpOptions(sel) {
  return '<option value="">— 건너뜀 —</option>' +
    bpNames().map((n) => `<option value="${esc(n)}"${n === sel ? " selected" : ""}>${esc(n)}</option>`).join("");
}

/* 이름 하나를 표준 BP 에 잇는다. 그 이름이 나온 모든 줄이 함께 따라간다. */
function hbLink(raw, bp) {
  if (bp) hbAssign[raw] = bp; else delete hbAssign[raw];
  hbRows.forEach((r) => { if (r.bpRaw === raw) r.bp = bp || null; });
  hbPaint();
}

function hbUnmatched() {
  const un = (hbData?.unmatched || []).filter((g) => !hbAssign[g.raw]);
  const done = (hbData?.unmatched || []).filter((g) => hbAssign[g.raw]);
  const card = (g, linked) => `<div class="ubox${linked ? " done" : ""}">
    <div class="uhd">
      <b>${esc(g.raw)}</b>
      <span class="tag">${g.lines.length}줄</span>
      <span class="umeta">${esc(g.people.slice(0, 3).join(", "))}${g.people.length > 3 ? ` 외 ${g.people.length - 3}명` : ""}${
        g.selfNames.length ? ` · 자체명 ${esc(g.selfNames.join(", "))}` : ""}</span>
    </div>
    ${linked
      ? `<div class="urow"><span class="pill v">${esc(hbAssign[g.raw])} 으로 이음</span>
         <label class="rem"><input type="checkbox" data-rem="${esc(g.raw)}"${hbRemember[g.raw] !== false ? " checked" : ""}> 다음부터 자동</label>
         <button class="lnk" data-undo="${esc(g.raw)}">되돌리기</button></div>`
      : `<div class="urow">
          ${(g.suggestions || []).map((c) => `<button class="sug" data-raw="${esc(g.raw)}" data-bp="${esc(c.bp)}"
              title="유사도 ${c.score}${c.via ? " · " + esc(c.via) + "으로 찾음" : ""}">${esc(c.bp)}
              <span class="sc">${Math.round(c.score * 100)}</span></button>`).join("")
            || '<span class="umeta">닮은 이름이 없습니다.</span>'}
          <span class="or">또는</span>
          <input class="pick" list="hb-bpnames" data-pick="${esc(g.raw)}" placeholder="명부에서 찾기">
        </div>`}
  </div>`;
  // 이름이 똑같지 않은데 시스템이 알아서 이은 것. 여기가 가장 위험하다 —
  // 조용히 지나가면 틀린 회사에 남의 연락처가 들어간 채로 끝난다.
  const loose = {};
  hbRows.forEach((r) => {
    if (!r.bp || r.how === "일치" || hbAssign[r.bpRaw] || hbOkd[r.bpRaw]) return;
    (loose[r.bpRaw] = loose[r.bpRaw] || { raw: r.bpRaw, bp: r.bp, how: r.how, lines: [], people: [] }).lines.push(r.line);
    if (r.name && !loose[r.bpRaw].people.includes(r.name)) loose[r.bpRaw].people.push(r.name);
  });
  const lo = Object.values(loose);
  const loCard = (g) => `<div class="ubox">
    <div class="uhd"><b>${esc(g.raw)}</b><span class="tag">${g.lines.length}줄</span>
      <span class="umeta">${esc(g.people.slice(0, 3).join(", "))}</span></div>
    <div class="urow">
      <span class="pill i">${esc(g.bp)} 으로 이음 · ${esc(g.how)}</span>
      <button class="sug" data-okd="${esc(g.raw)}">맞습니다</button>
      <span class="or">아니면</span>
      <input class="pick" list="hb-bpnames" data-pick="${esc(g.raw)}" placeholder="다른 곳으로 바꾸기">
    </div>
  </div>`;

  if (!un.length && !done.length && !lo.length) return "";
  return `<div class="usec">
    ${un.length ? `<div class="uttl">이을 곳을 정해야 하는 이름 ${un.length}개</div>${un.map((g) => card(g, false)).join("")}` : ""}
    ${lo.length ? `<div class="uttl">이름이 똑같지는 않아 확인이 필요한 것 ${lo.length}개</div>${lo.map(loCard).join("")}` : ""}
    ${done.length ? `<div class="uttl ok">이어 준 이름 ${done.length}개</div>${done.map((g) => card(g, true)).join("")}` : ""}
  </div>`;
}

function hbPaint() {
  const sm = hbData?.summary || {};
  const okRows = hbRows.filter((r) => r.bp && (r.name || r.phone || r.email));
  const left = hbRows.filter((r) => !r.bp).length;
  const bps = new Set(okRows.map((r) => r.bp));

  $("#hb-sum").innerHTML = `<div class="kpi">
    <div class="c"><div class="k">읽은 줄</div><div class="v">${hbRows.length}</div><div class="d">담당자 ${
      new Set(okRows.map((r) => r.email || r.phone || r.name)).size}명</div></div>
    <div class="c hl"><div class="k">넣을 줄</div><div class="v">${okRows.length}</div><div class="d">BP ${bps.size}곳</div></div>
    <div class="c${left ? " hl" : ""}"><div class="k">남은 줄</div><div class="v">${left}</div><div class="d">${
      left ? "아래에서 이어 주세요" : "모두 이었습니다"}</div></div>
    <div class="c"><div class="k">연락처 없는 BP</div><div class="v">${(sm["연락처없는BP"] || []).length}</div><div class="d">이번 표에 없는 곳</div></div>
  </div>`;

  $("#hb-un").innerHTML = hbUnmatched();
  $("#hb-bpnames").innerHTML = bpNames().map((n) => `<option value="${esc(n)}">`).join("");

  $("#hb-table").innerHTML = hbRows.length ? `<div class="scroll"><table><thead><tr>
      <th>#</th><th>엑셀의 BP</th><th>맞춘 BP</th><th>상태</th><th>소재군</th>
      <th>담당자</th><th>연락처</th><th>메일</th><th>비고</th></tr></thead><tbody>
      ${hbRows.map((r) => `<tr${r.bp ? "" : ' class="warn"'}>
        <td>${r.line}</td><td>${esc(r.bpRaw)}</td>
        <td>${r.bp ? esc(r.bp) : '<span class="pill no">미정</span>'}</td>
        <td>${r.bp
              ? `<span class="pill ${hbAssign[r.bpRaw] ? "i" : "v"}">${hbAssign[r.bpRaw] ? "사람이 이음" : esc(r.how)}</span>`
              : `<span class="pill no">${esc(r.how)}</span>`}</td>
        <td>${esc((r.groups || []).join(", "))}${(r.unknownGroups || []).length
              ? ` <span class="pill no">모르는 소재군: ${esc(r.unknownGroups.join(", "))}</span>` : ""}</td>
        <td>${esc(r.name)}</td><td>${esc(r.phone)}</td><td>${esc(r.email)}</td><td>${esc(r.dept)}</td>
      </tr>`).join("")}</tbody></table></div>` : "";

  document.querySelectorAll(".sug").forEach((b) =>
    b.addEventListener("click", () => hbLink(b.dataset.raw, b.dataset.bp)));
  document.querySelectorAll("[data-okd]").forEach((b) =>
    b.addEventListener("click", () => { hbOkd[b.dataset.okd] = true; hbPaint(); }));
  document.querySelectorAll("[data-undo]").forEach((b) =>
    b.addEventListener("click", () => hbLink(b.dataset.undo, null)));
  document.querySelectorAll("[data-rem]").forEach((c) =>
    c.addEventListener("change", () => { hbRemember[c.dataset.rem] = c.checked; }));
  document.querySelectorAll("[data-pick]").forEach((inp) =>
    inp.addEventListener("change", () => {
      const v = inp.value.trim();
      if (!v) return;
      if (!bpNames().includes(v)) { inp.value = ""; show("#m-hbulk", false, `명부에 '${v}' 라는 이름이 없습니다.`); return; }
      hbLink(inp.dataset.pick, v);
    }));

  const extra = [];
  if ((sm["미확인소재군"] || []).length) extra.push(`모르는 소재군: ${sm["미확인소재군"].join(", ")}`);
  (hbData?.problems || []).forEach((p) => extra.push(`${p.line}행 — ${p.why}`));
  show("#m-hbulk", !left, (left
    ? `${left}줄이 아직 어느 회사인지 정해지지 않았습니다. 아래에서 이어 주세요 — 그대로 두면 그 줄은 넣지 않습니다.`
    : `${okRows.length}줄 모두 확인했습니다. 반영을 누르면 회사별로 묶여 원장에 남습니다.`)
    + (extra.length ? "\n" + extra.join("\n") : ""));
  $("#hb-submit").disabled = !okRows.length;
}

$("#hb-preview").addEventListener("click", async () => {
  const btn = $("#hb-preview"); btn.disabled = true;
  hbAssign = {}; hbRemember = {}; hbOkd = {};
  try {
    const { status, data } = await post(MGSCS_API + "hotline/preview", { text: $("#hb-text").value });
    if (status === 200 && data.ok) { hbData = data; hbRows = data.rows || []; hbPaint(); }
    else show("#m-hbulk", false, `읽지 못했습니다: ${data.error || status}`);
  } catch (e) { show("#m-hbulk", false, "통신 오류: " + e.message); }
  btn.disabled = false;
});

$("#hb-submit").addEventListener("click", async () => {
  const byBp = {}, aliases = {};
  hbRows.forEach((r) => {
    if (!r.bp || !(r.name || r.phone || r.email)) return;
    (byBp[r.bp] = byBp[r.bp] || []).push({
      name: r.name, phone: r.phone, email: r.email,
      materialGroups: r.groups || [], dept: r.dept,
      bpSelfName: r.bpSelfName, bpCode: r.bpCode, sent: r.sent,
    });
  });
  Object.entries(hbAssign).forEach(([raw, bp]) => {
    if (hbRemember[raw] === false) return;
    (aliases[bp] = aliases[bp] || []).push(raw);
  });
  const n = Object.values(byBp).reduce((a, v) => a + v.length, 0);
  const left = hbRows.filter((r) => !r.bp).length;
  if (!confirm(`${Object.keys(byBp).length}개사 · 담당자 ${n}명을 반영합니다.`
    + (left ? `\n${left}줄은 이을 곳이 없어 넣지 않습니다.` : "")
    + `\n같은 회사의 기존 연락처는 이번 표로 바뀝니다.`)) return;
  const btn = $("#hb-submit"); btn.disabled = true;
  try {
    const { status, data } = await post(MGSCS_API + "hotline/commit",
      { byBp, aliases, reason: "Hotline 엑셀 일괄 등록" });
    const lines = [];
    if (data.aliases) lines.push(`이름 ${data.aliases}건을 기억했습니다 — 다음 붙여넣기부터 저절로 붙습니다.`);
    if (data.missed && data.missed.length) lines.push(`붙지 않은 곳: ${data.missed.join(", ")}`);
    (data.errors || []).forEach((e) => lines.push(e));
    (data.skipped || []).forEach((e) => lines.push("건너뜀 — " + e));
    if (status === 200 && data.ok) {
      show("#m-hbulk", true, `${data.landed}개사 · 담당자 ${data.people}명 반영했습니다.`
        + (lines.length ? "\n" + lines.join("\n") : ""));
      await refresh();
      GAPS = null;
      if (document.querySelector('[data-p="gaps"]').getAttribute("aria-selected") === "true") loadGaps();
    } else {
      show("#m-hbulk", false, `일부만 반영됐습니다 (${data.landed || 0}/${data.written || 0}개사).\n`
        + (lines.join("\n") || data.error || status));
    }
  } catch (e) { show("#m-hbulk", false, "통신 오류: " + e.message); }
  btn.disabled = false;
});


/* ── 리포트 ── */
$("#r-rebuild").addEventListener("click", async () => {
  const { data } = await post(MGSCS_API+'rebuild', {});
  show("#m-report", data.ok, data.ok
    ? `재생성 완료 — 적용 ${data.applied}건 · 충돌 ${(data.conflicts || []).length}건 · 건너뜀 ${(data.skipped || []).length}건`
    : `오류 ${(data.errors || []).join(" / ")}`);
  const rep = await fetch(MGSCS_API+'report').then((r) => r.json());
  if (rep.ok) $("#r-text").textContent = rep.report;
  await refresh();
});

/* ── 초기화 ── */
window.__ROWS = ROWS;
renderKpi(ROWS); fillSelects(ROWS); renderList(ROWS);
$("#r-text").textContent = document.getElementById("d-report").textContent;
</script>


</body>
</html>
