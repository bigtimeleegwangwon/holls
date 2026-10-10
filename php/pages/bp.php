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
<title>mGSCS · BP 기준 정보</title>
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='4' fill='%231d3a6e'/%3E%3Ctext x='16' y='22' font-size='17' font-family='sans-serif' font-weight='800' fill='white' text-anchor='middle'%3Em%3C/text%3E%3C/svg%3E">

<?php include __DIR__ . '/_shared/theme.php'; ?>
<style>



*{box-sizing:border-box}
html{-webkit-font-smoothing:antialiased}
body{margin:0;background:var(--bg);color:var(--ink);font-family:var(--font-sans);
     font-size:var(--fs-base);line-height:1.7;letter-spacing:-.011em;font-variant-numeric:tabular-nums}
a{color:var(--navy-2);text-decoration:none}


/* ── 2단 : 좌측 메뉴 · 우측 본문 ── */
.shell{display:grid;grid-template-columns:224px 1fr;gap:0 20px;
       max-width:var(--page-w);margin:0 auto;padding:24px 24px 70px;align-items:start}

/* ── 좌측 ── */
.shell > .pagehead{grid-column:1 / -1}
.side{position:sticky;top:calc(var(--navbar-h) + 14px)}
.side h2{font-size:var(--fs-2xs);color:var(--ink-3);font-weight:700;letter-spacing:.07em;
         margin:0 0 7px;text-transform:uppercase}
.side section{margin:0 0 18px}
.side input[type=search],.side select{width:100%;padding:7px 9px;border:1px solid var(--line2);
     border-radius:var(--r-md);font:inherit;font-size:var(--fs-sm);background:var(--card);
     color:var(--ink);box-shadow:var(--sh);margin-bottom:6px}
.side input:focus,.side select:focus{outline:none;border-color:var(--navy-2)}

.mg-list{display:flex;flex-direction:column;gap:1px}
.mg-cat{font-size:var(--fs-2xs);color:var(--ink-3);font-weight:700;margin:11px 0 3px;
        padding:3px 9px;display:flex;align-items:center;gap:7px;cursor:pointer;border-radius:var(--r-sm)}
.mg-cat:hover{background:var(--surface);color:var(--ink-2)}
.mg-cat.on{background:var(--navy);color:var(--on-navy)}
.mg-cat.on .ln{background:rgba(255,255,255,.35)}
.mg-cat:first-child{margin-top:2px}
.mg-cat .ln{flex:1;height:1px;background:var(--line)}
.mg-cat .n{font-variant-numeric:tabular-nums;opacity:.75}
.mg{display:flex;align-items:center;gap:8px;padding:5px 9px;border-radius:var(--r-lg);
    cursor:pointer;font-size:var(--fs-sm);color:var(--ink-2);border:1px solid transparent}
.mg:hover{background:var(--surface);border-color:var(--line)}
.mg.on{background:var(--navy);color:var(--on-navy);font-weight:700;border-color:var(--navy)}
.mg .nm{flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.mg .n{font-size:var(--fs-xs);opacity:.8;font-variant-numeric:tabular-nums}

/* ── 우측 ── */
.mapbox{border:1px solid var(--line2);border-radius:var(--r-lg);box-shadow:var(--sh-2);
        background:var(--card);margin:0 0 14px;overflow:hidden}
.maphead{display:flex;align-items:center;gap:9px;padding:8px 13px;border-bottom:1px solid var(--line);
         font-size:var(--fs-xs);color:var(--ink-3)}
.maphead b{color:var(--ink-2)}
.maphead .sp{flex:1}
.maphead button{padding:3px 9px;border:1px solid var(--line2);background:var(--card);
                border-radius:var(--r-sm);font:inherit;font-size:var(--fs-2xs);cursor:pointer;color:var(--ink-2)}
.maphead button:hover{border-color:var(--navy)}
svg.map{display:block;width:100%;height:300px;background:var(--surface);
        cursor:grab;touch-action:pan-y}
svg.map.drag{cursor:grabbing}
.mapwrap{position:relative}
.zoom{position:absolute;right:9px;bottom:9px;display:flex;flex-direction:column;gap:3px}
.zoom button{width:27px;height:27px;padding:0;border:1px solid var(--line2);background:var(--card);
             border-radius:var(--r-md);font:inherit;font-size:15px;line-height:1;cursor:pointer;color:var(--ink-2);
             box-shadow:var(--sh)}
.zoom button:hover{border-color:var(--navy);color:var(--navy)}
.maphint{position:absolute;left:11px;bottom:11px;font-size:var(--fs-2xs);color:var(--ink-3);
         background:var(--navbar-bg);padding:2px 7px;border-radius:var(--r-sm);pointer-events:none}
svg.map .land{fill:var(--map-land);stroke:var(--line2);stroke-width:.4;vector-effect:non-scaling-stroke}
svg.map circle{cursor:pointer}
svg.map circle.hit{cursor:pointer}
.legend{padding:6px 13px;border-top:1px solid var(--line);font-size:var(--fs-2xs);color:var(--ink-3);
        display:flex;gap:14px;flex-wrap:wrap;align-items:center}
.legend i{display:inline-block;width:8px;height:8px;border-radius:50%;margin-right:4px;vertical-align:-1px}

.cnt{font-size:var(--fs-sm);color:var(--ink-3);margin:0 0 9px}

.bp{border:1px solid var(--line2);border-radius:var(--r-lg);box-shadow:var(--sh-2);
    background:var(--card);margin:0 0 8px;overflow:hidden;scroll-margin-top:70px}
.bp.hl{border-color:var(--navy)}
.bp-h{display:flex;align-items:center;gap:8px;padding:10px 13px;cursor:pointer;flex-wrap:wrap}
.bp-h:hover{background:var(--surface)}
.bp-h .nm{font-weight:700}
.bp-h .sp{flex:1}
.bp-b{border-top:1px solid var(--line);background:var(--surface)}
.tw{overflow-x:auto}
table{border-collapse:collapse;width:100%;font-size:var(--fs-sm);min-width:640px}
th,td{padding:6px 11px;text-align:left;border-bottom:1px solid var(--line);vertical-align:top}
th{font-size:var(--fs-2xs);color:var(--ink-3);font-weight:700;white-space:nowrap;
   background:var(--surface-2);letter-spacing:.02em}
tr:last-child td{border-bottom:0}
td.src{font-size:var(--fs-2xs);color:var(--ink-3);max-width:230px;word-break:break-all}

.tag{display:inline-block;padding:1px 6px;border:1px solid var(--line2);border-radius:var(--r-sm);
     font-size:var(--fs-2xs);font-weight:700;color:var(--ink-2);background:var(--surface);white-space:nowrap}
.tag.qd{color:var(--navy);border-color:var(--navy-line)}
.tag.tam{color:var(--amber);border-color:var(--md-line);background:var(--md-bg)}
.tag.no{color:var(--red);border-color:var(--hi-line);background:var(--hi-bg)}
.tag.ok{color:var(--green);border-color:var(--ok-line);background:var(--ok-bg)}
.tag.pl{background:var(--card)}

.cts{padding:9px 13px 4px;border-top:1px solid var(--line);background:var(--surface-2)}
.cts .cth{font-size:var(--fs-xs);font-weight:700;color:var(--ink-2);padding:0 2px 6px}
.cts .ctf{font-size:var(--fs-2xs);color:var(--ink-3);padding:5px 2px}
.hot{display:flex;gap:5px;flex-wrap:wrap;align-items:center;padding:9px 13px;
     border-top:1px solid var(--line);background:var(--card)}
.hot input{padding:6px 9px;border:1px solid var(--line2);border-radius:var(--r-md);
           font:inherit;font-size:var(--fs-sm);width:144px}
.hot button{padding:6px 13px;border:1px solid var(--navy);background:var(--navy);color:var(--on-navy);
            border-radius:var(--r-md);font:inherit;font-size:var(--fs-sm);font-weight:700;cursor:pointer}
.hot button:disabled{opacity:.5;cursor:not-allowed}
/* 지우기 — 저장과 같은 무게로 보이면 안 된다. 되돌리려면 다시 입력해야 하는 일이다. */
.hot button.clr{background:transparent;color:var(--ink-3);border-color:var(--line)}
.hot button.clr:hover{color:var(--bad,#b3261e);border-color:currentColor}
/* 연락처 이력 */
.hist{margin-top:8px}
.hist summary{cursor:pointer;font-size:var(--fs-xs);color:var(--ink-3);padding:4px 0}
.hist-b{display:flex;flex-direction:column;gap:2px;padding:6px 0 2px}
.hist-r{display:flex;flex-wrap:wrap;gap:4px 12px;font-size:var(--fs-xs);
  color:var(--ink-2);padding:3px 0;border-top:1px solid var(--line)}
.hist-t{font-variant-numeric:tabular-nums;color:var(--ink-3);min-width:9.5em}
.hist-o{font-weight:700;min-width:5em}
.hist-c b{font-weight:700;color:var(--ink)}
.hist-w{margin-left:auto;color:var(--ink-3)}
@media (max-width:640px){ .hist-w{margin-left:0;flex:1 0 100%} }
.hot .lb{font-size:var(--fs-xs);color:var(--ink-3)}
.hot .msg{font-size:var(--fs-xs)}
.hot .msg.ok{color:var(--green)} .hot .msg.err{color:var(--red)}

.empty{padding:28px;text-align:center;color:var(--ink-3);font-size:var(--fs-sm);
       border:1px solid var(--line);border-radius:var(--r-md);background:var(--surface)}
footer{border-top:1px solid var(--line);margin-top:28px;padding-top:14px;
       font-size:var(--fs-xs);color:var(--ink-3)}
.conf{color:var(--red);font-weight:700}
.tabsp{flex:1}
.tabbtn{padding:6px 11px;font-size:var(--fs-xs);font-family:var(--font-sans);cursor:pointer;
  text-decoration:none;border:1px solid var(--line2);border-radius:var(--r-md);
  background:var(--card);color:var(--ink-2)}
.tabbtn:hover{border-color:var(--navy);color:var(--navy)}
/* 표 — 긁어가기용. 셀 병합도 배경 무늬도 두지 않는다(붙여넣을 때 깨진다). */
.gridwrap{overflow:auto;max-height:70vh;border:1px solid var(--line);border-radius:var(--r-lg);
  background:var(--card)}
.gridtbl{border-collapse:collapse;width:100%;font-size:var(--fs-xs)}
.gridtbl th,.gridtbl td{padding:6px 9px;text-align:left;white-space:nowrap;
  border-bottom:1px solid var(--line-2)}
.gridtbl th{position:sticky;top:0;z-index:1;background:var(--surface-2);color:var(--ink-3);
  font-weight:700;border-bottom:1px solid var(--line)}
.gridtbl tbody tr:hover{background:var(--surface)}
.gridtbl td.miss{color:var(--hi)}
/* 소재군은 네모칸으로 감싼다 — 'Chemical Gas Pad' 가 한 덩어리로 읽히던 문제.
   다만 쉼표를 **진짜 글자로** 남긴다. 칸만 두면 복사했을 때 'ChemicalGasPad' 로 붙는다
   (CSS 로 만든 글자는 복사되지 않는다). 눈에는 거의 안 띄고 클립보드에는 남는다. */
.gridtbl td.mgcell{white-space:normal;min-width:150px}
.gridtbl .chip{display:inline-block;padding:1px 6px;border:1px solid var(--line2);
  border-radius:var(--r-sm);background:var(--surface);color:var(--ink-2);
  font-size:var(--fs-2xs);line-height:1.6}
.gridtbl .sep{font-size:0;color:transparent}
/* 사건 선택은 찾기줄 안에 산다 — 좁히는 곳이 둘이면 매번 어느 쪽을 쓸지 고른다. */
.findbar select{flex:0 1 260px;min-width:150px;padding:10px 9px;
  font-family:var(--font-sans);font-size:var(--fs-sm);
  border:1px solid var(--line2);border-radius:var(--r-md);
  background:var(--card);color:var(--ink)}
.findbar select:focus{outline:none;border-color:var(--navy)}
.riskhow{font-size:var(--fs-xs);color:var(--navy-2);margin:-6px 0 11px;min-height:1em}
/* 소재군 칩 — 한 BP 가 여러 군을 만들면 그만큼 붙는다. 자르지 않는다. */
.tag.mg{background:var(--surface);color:var(--ink-3);border:1px solid var(--line)}
.tag.mg.on{background:var(--navy);color:var(--on-navy);border-color:var(--navy);font-weight:700}

@media (max-width:900px){
  .shell{grid-template-columns:1fr;gap:14px;padding:14px 14px 60px}
  .side{position:static}
  /* 사이드바 열이 사라져 전체 폭이 되면 세로 20줄은 지도를 한참 아래로 민다.
     그래서 항목을 가로로 눕힌다 — 다만 **카테고리는 제 줄을 차지한다.**
     같이 눕히면 박스 없는 'Photo 31'(카테고리) 옆에 박스 친 'Photo 31'(항목)이
     서고, Chemical·Slurry·Pad 가 C&C 에 딸린 것인지 알 수 없어진다. */
  .mg-list{flex-direction:row;flex-wrap:wrap;gap:6px}
  .mg{border:1px solid var(--line2)}
  .mg-cat{flex:0 0 100%;margin:10px 0 0}
  .mg-cat:first-child{margin-top:0}
  svg.map{height:210px}
}

.hotlink{text-decoration:none;color:inherit;cursor:pointer}
.hotlink:hover{text-decoration:underline}

/* ── 찾기줄 ────────────────────────────────────────────────────
     지도 바로 위에 둔다. 사이드바 안에 있으면 지도를 보면서 못 찾는다.
     입력하는 즉시 지도와 목록이 같이 좁혀진다.
   ────────────────────────────────────────────────────────────── */
.findbar{display:flex;align-items:center;gap:9px;margin:0 0 11px}
.findbar input{flex:1;min-width:0;padding:10px 13px;font-size:var(--fs-base);
  font-family:var(--font-sans);border:1px solid var(--line2);border-radius:var(--r-md);
  background:var(--card);color:var(--ink)}
.findbar input:focus{outline:none;border-color:var(--navy);box-shadow:0 0 0 3px var(--accent-soft)}
.findbar .found{font-size:var(--fs-sm);color:var(--ink-2);white-space:nowrap;min-width:96px;text-align:right}
.findbar .found b{color:var(--navy);font-weight:700}
.findbar button{padding:9px 12px;font-size:var(--fs-sm);font-family:var(--font-sans);
  border:1px solid var(--line2);border-radius:var(--r-md);background:var(--card);
  color:var(--ink-2);cursor:pointer}
.findbar button:hover{border-color:var(--navy);color:var(--navy)}

/* ── 지도 툴팁 ──────────────────────────────────────────────────
     브라우저 기본 <title> 은 1초쯤 기다려야 뜨고 생김새도 제각각이다.
     사업장 하나를 확인하는 데 1초씩 걸리면 372곳을 훑을 수 없다.
   ────────────────────────────────────────────────────────────── */
.mtip{position:absolute;z-index:30;pointer-events:none;max-width:310px;
  background:var(--card);border:1px solid var(--line2);border-radius:var(--r-md);
  box-shadow:var(--sh-2);padding:9px 11px;font-size:var(--fs-xs);line-height:1.55;color:var(--ink-2)}
.mtip .t{font-size:var(--fs-sm);font-weight:800;color:var(--ink);
  display:block;margin-bottom:3px;letter-spacing:-.02em}
.mtip .s{color:var(--ink);font-weight:600}
.mtip dl{margin:5px 0 0;display:grid;grid-template-columns:auto 1fr;gap:2px 9px}
.mtip dt{color:var(--ink-3)}
.mtip dd{margin:0;color:var(--ink-2)}
.mtip .no{color:var(--red);font-weight:700}
.mtip .ok{color:var(--green);font-weight:700}

/* 목록 ↔ 지도 서로 가리키기 */
svg.map circle.dim{fill-opacity:.14;stroke-opacity:.3}
svg.map circle.act{stroke:var(--navy);stroke-width:1.4;fill-opacity:1}
.bp.near{border-color:var(--navy);box-shadow:0 0 0 2px var(--accent-soft)}

/* ── 탭 ────────────────────────────────────────────────────────
     같은 BP 데이터를 두 가지 목적으로 본다. 화면을 나누면 검색해 놓은 걸
     넘어갈 때마다 다시 쳐야 한다. 탭이면 좁혀 둔 조건이 그대로 이어진다.
   ────────────────────────────────────────────────────────────── */
.tabs{display:flex;gap:2px;margin:0 0 18px;border-bottom:1px solid var(--line)}
.tabs button{padding:9px 16px;border:1px solid transparent;border-bottom:none;
  background:none;font:inherit;font-size:var(--fs-base);font-weight:700;
  color:var(--ink-3);cursor:pointer;border-radius:var(--r-md) var(--r-md) 0 0;
  margin-bottom:-1px}
.tabs button:hover{color:var(--navy)}
.tabs button.on{background:var(--card);border-color:var(--line);
  border-bottom:1px solid var(--card);color:var(--navy)}
.tabs .badge{display:inline-block;margin-left:6px;padding:0 6px;border-radius:var(--r-sm);
  background:var(--hi-bg);border:1px solid var(--hi-line);color:var(--red);
  font-size:var(--fs-2xs);font-weight:800}
.tabs button.on .badge{background:var(--hi-bg)}

/* ── 연락처 채우기 패널 ── */
.warn{border:1px solid var(--hi-line);background:var(--hi-bg);box-shadow:var(--sh-2);
      border-radius:var(--r-md);padding:12px 15px;font-size:var(--fs-sm);
      color:var(--red-ink);line-height:1.7;margin:0 0 18px}
.prog{border:1px solid var(--line2);border-radius:var(--r-md);box-shadow:var(--sh-2);
      background:var(--card);padding:14px 16px;margin:0 0 16px}
.prog .top{display:flex;align-items:baseline;gap:10px;margin-bottom:9px}
.prog .big{font-size:var(--fs-kpi);font-weight:800;letter-spacing:-.03em;line-height:1}
.prog .of{font-size:var(--fs-sm);color:var(--ink-3)}
.prog .sp{flex:1}
.prog .pct{font-size:var(--fs-md);font-weight:700;color:var(--red)}
.track{height:7px;border-radius:var(--r-md);background:var(--surface-2);overflow:hidden}
.track i{display:block;height:100%;background:var(--green);border-radius:var(--r-sm)}
.split{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:8px;margin-top:13px}
.split div{border:1px solid var(--line);border-radius:var(--r-md);padding:9px 11px;background:var(--surface)}
.split .v{font-weight:800;font-size:var(--fs-md);line-height:1.3}
.split .l{font-size:var(--fs-xs);color:var(--ink-3)}

.hottools{display:flex;flex-wrap:wrap;gap:7px;align-items:center;margin:0 0 12px}
.hottools .cnt{font-size:var(--fs-sm);color:var(--ink-3);margin-left:auto}

.htw{border:1px solid var(--line2);border-radius:var(--r-md);box-shadow:var(--sh-2);
     background:var(--card);overflow:auto;max-height:calc(100vh - 320px)}
.htw table{border-collapse:collapse;width:100%;font-size:var(--fs-sm);min-width:900px}
.htw th,.htw td{padding:8px 11px;text-align:left;border-bottom:1px solid var(--line);vertical-align:middle}
.htw th{background:var(--surface);font-size:var(--fs-2xs);color:var(--ink-3);font-weight:700;
        white-space:nowrap;position:sticky;top:0;z-index:5}
.htw tr:last-child td{border-bottom:0}
.htw tr.done{background:var(--ok-bg)}
.htw td.bp{font-weight:700;white-space:nowrap}
.htw td input{padding:5px 8px;border:1px solid var(--line2);border-radius:var(--r-md);
              font:inherit;font-size:var(--fs-sm);width:100%;min-width:88px;background:var(--card)}
.htw td input:focus{outline:none;border-color:var(--navy-2)}
.htw td.act{white-space:nowrap}
.htw td.act button{padding:5px 11px;border:1px solid var(--navy);background:var(--navy);color:var(--on-navy);
                   border-radius:var(--r-md);font:inherit;font-size:var(--fs-xs);font-weight:700;cursor:pointer}
.htw td.act button:disabled{opacity:.45;cursor:not-allowed}
.htw td.act .m{font-size:var(--fs-2xs);margin-left:6px}
.htw td.act .m.ok{color:var(--green)} .htw td.act .m.err{color:var(--red)}

/* 지도 조작 버튼은 특히 작다. 손가락 기기에서만 키운다. */
@media (pointer: coarse) {
  .zoom button{width:42px;height:42px;font-size:var(--fs-md)}
  .zoom{gap:8px}
  .maphead button{padding:9px 13px}
  .htw td.act button{padding:10px 14px}
  .mapwrap{height:min(52vh, 420px)}
  .mtip{max-width:min(84vw, 320px)}
  .bp-h{padding-top:11px;padding-bottom:11px}
}

/* ── 명부 상태 — 이제 접기(.fold) 안에 산다. 바 4개·내보내기는 그대로다. ── */
.bars{display:grid;grid-template-columns:repeat(auto-fit,minmax(178px,1fr));gap:9px}
.bars .it{border:1px solid var(--line);border-radius:var(--r-md);padding:9px 11px;background:var(--surface)}
.bars .it .l{font-size:var(--fs-2xs);color:var(--ink-3);display:flex;gap:6px;align-items:baseline}
.bars .it .l .n{margin-left:auto;font-weight:800;color:var(--ink-2);font-size:var(--fs-xs)}
.bars .it .t{height:5px;border-radius:var(--r-sm);background:var(--surface-2);
             overflow:hidden;margin:6px 0 4px}
.bars .it .t i{display:block;height:100%;background:var(--navy-2)}
.bars .it.done .t i{background:var(--green)}
.bars .it.low .t i{background:var(--red)}
.bars .it .r{font-size:var(--fs-2xs);color:var(--ink-3)}
.bars .it.low .r{color:var(--red);font-weight:700}
.exp{font-size:var(--fs-xs);color:var(--ink-3)}
.exp a{margin:0 6px 0 0;padding:4px 9px;border:1px solid var(--line2);
       border-radius:var(--r-sm);color:var(--ink-2);text-decoration:none;
       font-weight:700;white-space:nowrap;display:inline-block}
.exp a:hover{border-color:var(--navy);color:var(--navy)}
.exp a.warn{color:var(--red);border-color:var(--hi-line);background:var(--hi-bg);
            box-shadow:none;font-size:var(--fs-xs);line-height:inherit}
#healthFold{margin:0 0 14px}
#healthFold>.fold-body>details.fold{margin-top:11px}
@media (pointer: coarse){ .exp a{padding:9px 12px} }

/* ── 접기 표준 보루 ────────────────────────────────────────────
     theme.html 의 .fold 가 새 규격으로 올라오기 전에도 같은 모양이 되게
     요약 줄 부속(.fold-hint · .fold-n)만 여기서 받쳐 둔다. 값은 표준과 같다.
   ────────────────────────────────────────────────────────────── */
details.fold>summary{display:flex;align-items:center;gap:2px 7px;flex-wrap:wrap;user-select:none}
.fold-hint{font-weight:400;font-size:var(--fs-xs);color:var(--ink-3)}
.fold-n{margin-left:auto;flex:0 0 auto;font-size:var(--fs-2xs);font-weight:700;
        color:var(--navy);background:var(--accent-soft);border:1px solid var(--navy-line);
        border-radius:var(--r-sm);padding:0 7px;line-height:1.75;white-space:nowrap}
.fold-n:empty{display:none}
:root[data-theme="dark"] .fold-n{color:var(--navy-2)}
details.fold.on{border-color:var(--navy-line)}
details.fold.plain{border:0;background:none}
details.fold.plain>summary{padding:6px 0}
details.fold.plain>.fold-body{padding:4px 0 10px}
#narrowBox>.fold-body{display:flex;flex-direction:column;gap:7px}

/* ── 참고 숫자 한 줄 — 행동을 부르지 않는 숫자는 전부 여기로 ── */
.refline{display:flex;flex-wrap:wrap;align-items:baseline;gap:2px 18px;
         margin:0 0 var(--sp-5);font-size:var(--fs-xs);color:var(--ink-3);line-height:1.95}
.refline .r{display:inline-flex;align-items:baseline;gap:5px;white-space:nowrap}
.refline .r b{font-weight:700;color:var(--ink-2);font-variant-numeric:tabular-nums}
.refline .src{margin-left:auto}
/* ── 행동 stat — 이것만 크다 ──
     칸이 하나뿐이니 폭을 다 먹지 않게 내용에 맞춘다. 빈 상자가 넓으면
     "여기 뭔가 더 있어야 하나" 싶어진다.
     .stat.warn 은 아래 큰 경고 패널(.warn)과 클래스가 겹친다 — 숫자만 붉어야 하므로
     패널 껍데기를 벗긴다.
   ────────────────────────────────────────────────────────────── */
.statbar.act{display:inline-flex;gap:var(--sp-7);padding:var(--sp-4) var(--sp-5);
             margin-bottom:var(--sp-3)}
.statbar.act .stat .v{font-size:calc(var(--fs-kpi) + 5px)}
.statbar .stat.warn{border:0;background:none;box-shadow:none;padding:0;margin:0;
                    border-radius:0;font-size:inherit;line-height:inherit;color:inherit}

/* ── 빈 상태 · 알림 한 줄 ──────────────────────────────────────
     "없습니다"로 끝내면 사람이 멈춘다. 다음에 누를 것을 같이 준다.
   ────────────────────────────────────────────────────────────── */
.empty b{color:var(--ink-2)}
.empty .go{display:inline-block;margin-top:9px;padding:6px 13px;border:1px solid var(--line2);
           border-radius:var(--r-md);background:var(--card);color:var(--ink-2);
           font-size:var(--fs-sm);font-weight:700;font-family:var(--font-sans);cursor:pointer}
.empty .go:hover{border-color:var(--navy);color:var(--navy)}
.mapnote{display:flex;align-items:center;gap:11px;flex-wrap:wrap;margin:0 0 11px;
         padding:9px 13px;border:1px solid var(--md-line);background:var(--md-bg);
         border-radius:var(--r-md);font-size:var(--fs-sm);color:var(--ink-2)}
.mapnote a{font-weight:700;margin-left:auto;white-space:nowrap}
.mapnote[hidden]{display:none}   /* flex 가 [hidden] 을 이겨 빈 띠가 남는 걸 막는다 */
</style>
</head>
<body>

<?php $here = 'bp'; ?><?php $here_label = 'BP 기준 정보'; ?>
<?php include __DIR__ . '/_shared/nav.php'; ?>

<div class="shell">
  <header class="pagehead">
  <h1 class="pagetitle">BP 기준 정보</h1>
  <p class="lead">사업장 · 등급 · 담당자. 판정과 발송이 여기를 본다.</p>
  
  <p class="lead"><b>소재군별 BP · 사업장 · Hotline</b> 기준 정보입니다.</p>
  
  <div class="refline" id="kpiRef"></div>
  </header>


  <aside class="side">
    <section>
      
      <input type="search" id="q" hidden>
      
      <details class="fold" id="narrowBox">
        <summary>조건 <span class="fold-hint">등급제 · 티어 · 국가 · 연락처</span><span class="fold-n"></span></summary>
        <div class="fold-body">
          <select id="fGrade" title="사내 등급제 적용 구분"><option value="">등급제 전체</option><option value="Q">품질 등급 (Q·QD) — 점검 대상</option><option value="D">개발 등급만 (D)</option><option value="-">등급 없음</option></select>
          <select id="fTier"><option value="">전체 티어</option><option>점검</option><option>탐색</option><option>미등록</option></select>
          <select id="fCountry"><option value="">전체 국가</option></select>
          <select id="fHot"><option value="">연락처 전체</option><option value="no">미확보만</option><option value="yes">확보만</option></select>
        </div>
      </details>
    </section>
    <section>
      <h2>소재군</h2>
      <div class="mg-list" id="mgList"></div>
    </section>
  </aside>

  <main>

    
    <div class="tabs" role="tablist">
      <button id="tabSites" class="on" role="tab">사업장 보기</button>
      <button id="tabHot" role="tab">연락처 채우기 <span class="badge" id="hotBadge"></span></button>
      
      <span class="tabsp"></span>
      <a class="tabbtn" href="api/export/hotline.csv"
         title="붙여넣기와 같은 양식. 엑셀에서 채워 '변경 · 연락처 일괄' 에 붙여넣으면 반영됩니다">
        연락처 양식 CSV ↓</a>
      <a class="tabbtn" href="api/export/material_survey.csv"
         title="m-BP Grading 대상만. 연관 여부·자재 코드·자재 명·비고 네 칸이 비어 있습니다 — 소재 엔지니어가 채웁니다">
        대상 소재 조사 양식 ↓</a>
      <button class="tabbtn" onclick="copySurveyMail(this)"
         title="조사 양식과 함께 보낼 메일 초안을 복사합니다">조사 요청 메일 복사</button>
      <button class="tabbtn" id="viewToggle"
        title="표로 보면 드래그해서 그대로 엑셀에 붙여넣을 수 있습니다">표로 보기</button>
    </div>

    
    
    <div class="findbar">
      <input type="search" id="q2" placeholder="BP · 사업장 · 도/현 · 권역으로 찾기 — 예: 熊本, 청주, TOK">
      <select id="riskSel" title="사건에 걸리는 BP 만 남긴다"><option value="">사건 전체</option></select>
      <span class="found" id="found"></span>
      <button id="qClear" title="검색어·사건 지우기">지우기</button>
    </div>
    <div class="riskhow" id="riskHow"></div>

    
    <details class="fold" id="healthFold">
      <summary>명부 상태 <span class="fold-hint">연락처·좌표·이력·출처가 얼마나 채워졌나</span><span class="fold-n"></span></summary>
      <div class="fold-body">
        <div class="bars" id="health"></div>
        <details class="fold plain">
          <summary>내보내기</summary>
          <div class="fold-body exp">
            <a href="api/export?fmt=csv" title="엑셀에서 바로 열립니다 · 연락처 제외">CSV</a>
            <a href="api/export?fmt=csv&amp;hotline=1"
               title="담당자 이름·전화·이메일이 함께 나갑니다. 대외비입니다."
               class="warn">CSV+연락처</a>
            <a href="api/export?fmt=json" title="다른 시스템이 읽을 형식 · 연락처 제외">JSON</a>
          </div>
        </details>
      </div>
    </details>

<div id="panelSites">
    <div class="mapnote" id="mapNote" hidden></div>
        <div class="mapbox">
      <div class="maphead">
        <b id="mapTitle">전체</b>
        <span id="mapSub"></span>
        <span class="sp"></span>
        <button id="mapFit">범위 맞춤</button>
        <button id="mapAll">세계</button>
      </div>
      <div class="mapwrap">
        <svg class="map" id="map" viewBox="0 0 1000 500" preserveAspectRatio="xMidYMid meet" role="img" aria-label="BP 사업장 지도">
          <path class="land" id="land" d=""></path>
          <g id="dots"></g>
        </svg>
        <div class="zoom">
          <button id="zIn"  title="확대" aria-label="확대">+</button>
          <button id="zOut" title="축소" aria-label="축소">−</button>
        </div>
        <div class="maphint">두 손가락으로 확대·이동 · 점을 눌러 정보 확인</div>
        <div class="mtip" id="mtip" hidden></div>
      </div>
      <div class="legend">
        <span><i style="background:var(--map-bp)"></i>생산</span>
        <span><i style="background:var(--map-bubble)"></i>본사</span>
        <span><i style="background:var(--lo)"></i>연구소·영업소</span>
        <span id="hint">점에 올리면 사업장 정보 · 누르면 목록에서 열림</span>
      </div>
    </div>

    <div class="cnt" id="cnt"></div>
    <div id="list"></div>
    
    <div id="grid" hidden><div class="gridwrap"><table class="gridtbl">
      <thead><tr>
        <th>BP</th><th>모회사</th><th>등급</th><th>티어</th><th>소재군</th><th>국가</th>
        <th>사업장</th><th>역할</th><th>행정구역</th><th>권역</th>
        <th>담당자</th><th>전화</th><th>이메일</th><th>확인일</th>
        <th>근거</th><th>출처 주소</th><th>조사일</th>
      </tr></thead><tbody id="gridRows"></tbody>
    </table></div></div>
    </div>

    <div id="panelHot" hidden>
      <div class="warn">
        <b>연락처는 사외로 나가지 않습니다.</b>
        경보(텔레그램·메일)는 외부 인프라를 경유하므로 담당자 이름·전화·이메일을 싣지 않습니다.
        알림에는 <b>대상 BP 명단까지만</b>나가고, 연락처는 이 화면에서만 봅니다.
      </div>

      <div class="prog">
        <div class="top">
          <span class="big" id="pHave">0</span><span class="of">/ <span id="pTot">0</span> 사</span>
          <span class="sp"></span>
          <span class="pct" id="pPct">0%</span>
        </div>
        <div class="track"><i id="pBar" style="width:0"></i></div>
        <div class="split" id="pSplit"></div>
      </div>

      <div class="hottools">
        <select id="fState">
          <option value="no">미확보만</option><option value="">전체</option><option value="yes">확보만</option>
        </select>
        <span class="cnt" id="hcnt"></span>
      </div>

      <div class="htw">
        <table>
          <thead><tr>
            <th style="width:150px">BP</th><th style="width:76px">등급제</th><th style="width:60px">티어</th>
            <th style="width:110px">파트</th><th style="width:118px">지역</th>
            <th>담당자</th><th>전화</th><th>이메일</th><th style="width:150px">저장</th>
          </tr></thead>
          <tbody id="hrows"></tbody>
        </table>
      </div>
    </div>

    <div class="nextstep"><span>대상 BP를 찾았다면 <b>연락처 채우기</b> 탭에서 담당자를 확인하고 점검을 요청합니다. 명부에 없는 BP·사업장이 나오면 명부 편집에서 넣습니다.</span><a class="go" href="<?= url('/revision') ?>">명부 편집 →</a></div>

    <?php include __DIR__ . '/_shared/foot.php'; ?>
  </main>
</div>

<?php include __DIR__ . '/_shared/grade.php'; ?>
<script type="application/json" id="d-rows"><?= $bp_rows_json ?></script>
<script>
// ── 조사 요청 메일 ────────────────────────────────────────────────
// 양식만 보내면 "이걸 왜 채워야 하나" 를 각자 짐작한다. 무엇에 쓰이는지와
// 채우는 규칙을 같이 보낸다 — 그래야 돌아오는 표가 쓸 수 있는 표가 된다.
function surveyMailText(){
  const d = new Date();
  const due = new Date(d.getTime() + 14 * 864e5);
  const f = x => `${x.getMonth()+1}월 ${x.getDate()}일`;
  return `제목: [소재전략] 재해 모니터링 대상 소재 확인 요청 (~${f(due)})

안녕하십니까, 소재전략 이정훈입니다.

해외 BP 사업장에 재해가 났을 때 **우리 라인에 영향이 오는지**를
자동으로 가려내는 체계(mGSCS)를 운영하고 있습니다.
지금은 소재군(Photo·Chemical·Gas 등 8축)까지만 알고 있어,
"그 공장이 우리에게 실제로 무엇을 대는가" 를 모릅니다.

그 한 칸이 없으면 판정이 이렇게 됩니다 —
  · 그 BP 의 **모든 사업장**이 후보가 됩니다(본사·연구소 포함)
  · 자재 단위로 재고·대체선을 못 붙입니다

첨부한 표에서 **네 칸만** 채워 주시면 됩니다.
나머지(BP·사업장·국가·지역·등급·소재군)는 저희가 채웠습니다.

  ① 연관 여부   O 또는 X — 그 사업장이 우리에게 대는 것이 있나
  ② 대상 자재 코드   여러 개면 쉼표로 (예: A1234, A1235)
  ③ 대상 자재 명
  ④ 비고        확실치 않은 것, 바뀔 예정인 것, 하고 싶은 말 무엇이든

  · 범위는 m-BP Grading 대상 ${SURVEY_N || ""}행입니다.
  · **모르는 줄은 비워 두십시오.** 빈칸은 '아직 모름' 으로 다루고
    판정에서 빼지 않습니다. 틀리게 채우는 것보다 비우는 편이 낫습니다.
  · 초안이라 완벽하지 않아도 됩니다. 채워 주신 만큼 바로 반영합니다.

회신은 ${f(due)}까지 부탁드립니다. 표 그대로 회신 주시면 됩니다.

감사합니다.
SK hynix 소재전략 담당자 드림
<담당자 메일>`;
}
let SURVEY_N = 0;
async function copySurveyMail(btn){
  try{
    const txt = await fetch("api/export/material_survey.csv").then(r => r.text());
    SURVEY_N = Math.max(0, txt.trim().split("\n").length - 1);
  }catch(_){ /* 건수를 못 세도 초안은 만든다 */ }
  try{
    await navigator.clipboard.writeText(surveyMailText());
    const o = btn.textContent; btn.textContent = "복사됨 ✓";
    setTimeout(()=>{ btn.textContent = o; }, 1600);
  }catch(_){
    window.prompt("복사가 막혔습니다 — 아래를 직접 복사하세요", surveyMailText());
  }
}
</script>
<script>window.MGSCS_LAND = "<?= url('/static/260611_junghoon/world50m.txt') ?>";</script>


<script>
const ROWS = JSON.parse(document.getElementById("d-rows").textContent);
const esc = (s) => String(s ?? "").replace(/[&<>"]/g, (c) =>
  ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;" })[c]);

/* 접은 것은 숨긴 것이지 없앤 것이 아니다. 접힌 채로도 무엇이 걸렸고 무엇이
   남았는지 요약 줄의 배지가 말해야 사람이 속지 않는다.
   theme.html 의 foldSync() 가 정본이다 — 아직 올라오지 않았으면 같은 일을 여기서 한다.
   openIt: 조건처럼 "지금 결과를 바꾸고 있는" 조건은 펼쳐 보여준다.
           명부 상태처럼 늘 조금씩 비어 있는 지표는 배지만 남기고 접어 둔다 —
           매번 저절로 펼쳐지면 접은 뜻이 없다. */
function foldMark(id, count, word, openIt) {
  const d = document.getElementById(id); if (!d) return;
  if (openIt && typeof window.foldSync === "function") return window.foldSync(id, count, word);
  const b = d.querySelector(".fold-n");
  if (b) b.textContent = count ? (count + (word || "개 적용 중")) : "";
  d.classList.toggle("on", !!count);
  if (openIt && count && !d.open) d.open = true;
}

const ROLE = { hq: "본사", plant: "생산", "r&d": "연구소", office: "영업소" };
const PREC = { verified_site: ["실측","ok"], address_geocoded: ["주소","pl"],
               city_centroid: ["시 중심","pl"], prefecture_centroid: ["도/현 중심","pl"] };
const DOT = { plant: "var(--map-bp)", hq: "var(--map-bubble)" };

// 연락처 확인일이 1년을 넘겼으면 다시 물어야 한다 — 담당자는 이동한다.
const STALE_MS = 365 * 24 * 3600 * 1000;
function staleBadge(v) {
  const t = Date.parse(v || "");
  if (!Number.isFinite(t) || Date.now() - t <= STALE_MS) return "";
  return `<span class="pill warn" title="확인한 지 1년이 넘었습니다 — 담당자·번호를 다시 확인하세요">재검증 필요</span>`;
}

// 대분류. tools/material_groups.py의 CATEGORY와 같은 정의다.
// 8축은 판정 축이므로 임의로 늘리지 않는다 — 늘리면 알림 매칭이 흔들린다.
const CATEGORY = [
  ["Photo",        ["Photo"]],
  ["C&C",          ["Chemical","Slurry","Pad"]],
  ["EDTW",         ["Gas","Precursor","Wafer","Target"]],
  ["WLP/PKG/기타", ["WLP+PKG","패키징 소재(EMC·언더필·TIM)","기판(글래스)","기판(ABF·유기)",
                    "카본·세라믹 부재","Raw Material-TBD","미지정"]],
];

// 소재군은 **여러 개** 고를 수 있다. 한 BP 가 여러 군을 만드는 경우가 24사(14%)라,
// 소재군 하나씩만 볼 수 있으면 "Chemical 과 Slurry 를 같이" 같은 실제 질문에 답을 못 한다.
// 빈 Set = 전체.
let SEL_MG = new Set();
let SEL_CAT = "";   // 대분류(파트) 선택. 소재군 선택과 배타적으로 쓴다.
let shown = [];              // 현재 목록에 보이는 site
const open = new Set();

const hasHot = (r) => { const h = r.hotline || {}; return !!(h.contactName || h.phone || h.email); };

function groupByMg() {
  const m = new Map();
  ROWS.forEach((r) => (r.materialGroups && r.materialGroups.length ? r.materialGroups : ["미지정"])
    .forEach((g) => {
      if (!m.has(g)) m.set(g, new Map());
      const b = m.get(g);
      if (!b.has(r.bpName)) b.set(r.bpName, []);
      b.get(r.bpName).push(r);
    }));
  return m;
}
const MG = groupByMg();

// ── 지도 ────────────────────────────────────────────────────────
// 판정과 같은 등장방형 투영을 쓴다. 좌표가 같은 뜻이어야 지도와 표가 어긋나지 않는다.
const px = (lon) => (Number(lon) + 180) / 360 * 1000;
const py = (lat) => (90 - Number(lat)) / 180 * 500;

fetch(window.MGSCS_LAND).then((r) => r.text())
  .then((d) => { document.getElementById("land").setAttribute("d", d); })
  .catch(() => { document.getElementById("hint").textContent = "해안선을 불러오지 못했습니다."; });

function fitTo(sites) {
  const svg = document.getElementById("map");
  if (!sites.length) { svg.setAttribute("viewBox", "0 0 1000 500"); return; }
  const xs = sites.map((s) => px(s.lon)), ys = sites.map((s) => py(s.lat));
  let x0 = Math.min(...xs), x1 = Math.max(...xs), y0 = Math.min(...ys), y1 = Math.max(...ys);
  // 점이 한 곳에 몰려도 지나치게 확대되지 않게 최소 폭을 준다.
  const padX = Math.max((x1 - x0) * 0.25, 18), padY = Math.max((y1 - y0) * 0.25, 12);
  x0 -= padX; x1 += padX; y0 -= padY; y1 += padY;
  const w = Math.max(x1 - x0, 40), h = Math.max(y1 - y0, 20);
  // 뷰포트 비율(2:1)에 맞춰 좁은 쪽을 넓힌다. 안 하면 점이 눌려 보인다.
  const want = 1000 / 500, got = w / h;
  let W = w, H = h;
  if (got < want) W = h * want; else H = w / want;
  // 세계 밖으로 나가지 않게 가둔다. 안 그러면 위아래로 빈 바다만 넓게 보인다.
  W = Math.min(W, 1000); H = Math.min(H, 500);
  let X = x0 + w / 2 - W / 2, Y = y0 + h / 2 - H / 2;
  X = Math.max(0, Math.min(X, 1000 - W));
  Y = Math.max(0, Math.min(Y, 500 - H));
  svg.setAttribute("viewBox", `${X.toFixed(1)} ${Y.toFixed(1)} ${W.toFixed(1)} ${H.toFixed(1)}`);
}

// 지도에 그려진 점 하나하나의 원본. 툴팁이 이걸 되짚는다.
let DRAWN = [];

// 점에 올렸을 때 보여줄 내용. 이름만 띄우면 "그래서 뭐하는 곳인데"가 남는다.
function tipHtml(s) {
  const hot = s.hotline && (s.hotline.contactName || s.hotline.phone || s.hotline.email);
  const [pl] = PREC[s.coordinateStatus] || ["좌표 미상"];
  const area = [s.admin1Ko || s.admin1, s.address].filter(Boolean).join(" · ");
  const grade = String(s.grade || "-");
  const row = (k, v) => v ? `<dt>${k}</dt><dd>${v}</dd>` : "";
  return `<span class="t">${esc(s.bpName)}</span>
    <span class="s">${esc(s.siteName || "사업장명 미상")}</span>
    <dl>
      ${row("역할", esc(ROLE[s.siteRole] || s.siteRole || "—"))}
      ${row("소재지", esc(`${s.country} · ${area || "—"}`))}
      ${row("권역", esc(s.regionKo || "—"))}
      ${row("소재군", esc((s.materialGroups || []).join(", ") || "—"))}
      ${row("등급제", esc(gradeTitle(grade)))}
      ${row("점검 티어", esc(s.tier || "미등록"))}
      ${row("좌표 근거", esc(pl))}
      <dt>연락처</dt><dd class="${hot ? "ok" : "no"}">${hot ? "확보" : "미확보"}</dd>
    </dl>`;
}

const mtip = () => document.getElementById("mtip");

// 기기가 터치를 지원하는가로 가르면 안 된다 — 터치스크린 달린 노트북은
// 마우스도 같이 쓰는데, 그 경우 hover 툴팁이 통째로 죽는다.
// 대신 "방금 들어온 입력이 손가락이었나"를 본다.
const COARSE = window.matchMedia("(pointer: coarse)").matches;
let lastTouchAt = 0;
const justTouched = () => Date.now() - lastTouchAt < 800;
document.addEventListener("pointerdown", (e) => {
  if (e.pointerType !== "mouse") lastTouchAt = Date.now();
}, true);
let TIP_FOR = null;      // 지금 툴팁을 띄운 점 (터치에서 두 번째 탭을 알아보려고)
let dragMoved = false;   // 지도를 끌었는지 — 끌던 손가락의 마지막 탭은 무시한다

function openInList(n) {
  if (!open.has(n)) open.add(n);
  renderList(true);
  const card = document.querySelector(`.bp[data-bp="${CSS.escape(n)}"]`);
  if (card) { card.scrollIntoView({ behavior: "smooth", block: "center" }); card.classList.add("hl"); }
}

function showTip(s, ev) {
  const box = mtip(), wrap = document.querySelector(".mapwrap");
  box.innerHTML = tipHtml(s);
  box.hidden = false;
  const r = wrap.getBoundingClientRect(), b = box.getBoundingClientRect();
  // 커서 오른쪽 아래가 기본. 가장자리에 닿으면 반대쪽으로 접는다 — 잘려서 안 보이면 소용없다.
  let x = ev.clientX - r.left + 14, y = ev.clientY - r.top + 14;
  if (x + b.width > r.width) x = ev.clientX - r.left - b.width - 14;
  if (y + b.height > r.height) y = ev.clientY - r.top - b.height - 14;
  box.style.left = Math.max(4, x) + "px";
  box.style.top = Math.max(4, y) + "px";
}
const hideTip = () => { mtip().hidden = true; };

// 지도와 목록이 서로를 가리킨다. 한쪽에 마우스를 올리면 다른 쪽이 반응해야
// 둘이 같은 것을 말하고 있다는 게 보인다.
function focusBp(name) {
  document.querySelectorAll("#dots circle:not(.hit)").forEach((c) => {
    const on = c.dataset.bp === name;
    c.classList.toggle("act", on);
    c.classList.toggle("dim", !!name && !on);
  });
  document.querySelectorAll(".bp").forEach((c) => {
    c.classList.toggle("near", !!name && c.dataset.bp === name);
  });
}
const blurBp = () => focusBp("");

// 좌표가 없으면 지도가 아무 반응도 안 한다 — 고장으로 보인다.
// 왜 안 되는지와 어디서 고치는지를 같이 말한다.
function alertNoCoord(name) {
  const el = document.getElementById("mapNote");
  if (!el) return;
  el.innerHTML = `<span><b>${esc(name)}</b>는 지도에 올릴 좌표가 없습니다 — 명부 편집에서 좌표를 넣으세요.</span>`
               + `<a href="revision">명부 편집 →</a>`;
  el.hidden = false;
  el.scrollIntoView({ behavior: "smooth", block: "center" });
}

function renderMap(sites) {
  DRAWN = sites;
  const vb = document.getElementById("map").getAttribute("viewBox").split(" ").map(Number);
  const k = Math.max(0.06, Math.pow(vb[2] / 1000, 0.9));   // 줌에 따라 점 크기 보정
  // 보이는 점은 작아야 372개가 겹치지 않는다. 하지만 3~4px 짜리를 마우스로
  // 맞추라는 건 무리다. 그래서 눈에 보이는 점과 손으로 잡는 판을 나눈다 —
  // 투명한 넓은 원을 위에 덮고, 그 원이 마우스를 받는다.
  const dots = sites.map((s, i) => {
    const c = DOT[s.siteRole] || "var(--lo)";
    return `<circle cx="${px(s.lon).toFixed(1)}" cy="${py(s.lat).toFixed(1)}" r="${(3.1 * k).toFixed(2)}"
      fill-opacity=".78" stroke-width="${(0.8 * k).toFixed(2)}" style="fill:${c};stroke:var(--card)"
      data-bp="${esc(s.bpName)}" data-i="${i}"></circle>`;
  }).join("");
  const hits = sites.map((s, i) =>
    `<circle class="hit" cx="${px(s.lon).toFixed(1)}" cy="${py(s.lat).toFixed(1)}"
      r="${Math.max(3.1 * k * (COARSE ? 3.6 : 2.4), COARSE ? 3.4 : 2.2).toFixed(2)}" fill="transparent"
      data-bp="${esc(s.bpName)}" data-i="${i}"></circle>`).join("");
  document.getElementById("dots").innerHTML = dots + hits;

  document.querySelectorAll("#dots circle.hit").forEach((el) => {
    const site = DRAWN[Number(el.dataset.i)];
    // 마우스는 올리면 뜨고 치우면 사라진다.
    el.addEventListener("mouseenter", (ev) => {
      if (justTouched()) return;              // 손가락이 만든 가짜 mouse 이벤트다
      showTip(site, ev); focusBp(site.bpName);
    });
    el.addEventListener("mousemove", (ev) => { if (!justTouched()) showTip(site, ev); });
    el.addEventListener("mouseleave", () => { if (!justTouched()) { hideTip(); blurBp(); } });

    // 터치에는 hover가 없다. 한 번 누르면 정보를 보여주고,
    // 같은 점을 다시 누르면 목록으로 넘어간다. 첫 탭에 바로 넘어가면
    // 툴팁을 볼 새가 없고, 잘못 눌렀을 때 되돌아오기도 번거롭다.
    el.addEventListener("pointerup", (ev) => {
      if (ev.pointerType === "mouse") return;
      if (dragMoved) return;                  // 지도를 끌던 손가락이면 무시
      ev.preventDefault();
      const n = el.dataset.bp;
      if (TIP_FOR === el) { hideTip(); openInList(n); }
      else { TIP_FOR = el; showTip(site, ev); focusBp(n); }
    });

    el.onclick = (ev) => {
      if (justTouched()) return;              // 터치는 pointerup에서 이미 처리했다
      openInList(el.dataset.bp);
    };
  });
  document.getElementById("mapSub").textContent =
    `사업장 ${sites.length} · 국가 ${new Set(sites.map((s) => s.country)).size}`;
}

// 이 화면에서 사람을 움직이는 숫자는 하나다 — 아직 담당자를 모르는 BP.
// 나머지 넷(BP·사업장·소재군·국가)은 규모를 가늠하는 참고값이라 한 줄로 내린다.
function renderKpi() {
  const bps = new Set(ROWS.map((r) => r.bpName));
  document.getElementById("kpiRef").innerHTML =
    `<span class="r">BP <b>${bps.size}</b></span>` +
    `<span class="r">사업장 <b>${ROWS.length}</b></span>` +
    `<span class="r">소재군 <b>${MG.size}</b></span>` +
    `<span class="r">국가 <b>${new Set(ROWS.map((r) => r.country)).size}</b></span>`;
}

// ── 사건으로 좁히기 ────────────────────────────────────────────
// "이 Risk 에는 누구에게 도나" 를 이 화면에서 바로 답한다.
// 판정은 점검과 **같은 엔진**(/api/match)을 쓴다 — 여기서 나온 BP 와
// 점검 대상이 어긋나면 어느 쪽을 믿을지 알 수 없다.
let RISK_BPS = null;   // null = 좁히지 않음. Set = 이 BP 만.

async function loadRisks() {
  const sel = document.getElementById("riskSel");
  if (!sel) return;
  let incs = [];
  try {
    const d = await fetch("api/incidents").then((r) => r.json());
    incs = (d.incidents || d || []).filter((x) => (x.status || "open") !== "closed");
  } catch (e) { return; }
  if (!incs.length) { sel.style.display = "none"; return; }
  incs.sort((a, b) => String(b.occurredAt || "").localeCompare(String(a.occurredAt || "")));
  sel.innerHTML = `<option value="">사건 전체</option>` +
    incs.map((x, i) => `<option value="${i}">${esc(x.title || x.id)}` +
      `${x.admin1 ? " · " + esc(x.admin1) : ""}</option>`).join("");
  sel.onchange = () => applyRisk(incs[sel.value] || null);
}

async function applyRisk(ev) {
  const how = document.getElementById("riskHow");
  if (!ev) { RISK_BPS = null; how.textContent = ""; renderList(); return; }
  how.textContent = "찾는 중…";
  try {
    // /api/match 가 아니라 이걸 쓴다 — 점검과 **같은 함수**로 고른다.
    // 그쪽은 좌표로 도/현을 만들고 점검은 사건의 adminAreas 를 쓴다.
    // 같은 사건이 2사와 5사로 갈렸다. 어느 쪽을 믿을지 모르면 둘 다 못 쓴다.
    const d = await fetch("api/incidents/" + encodeURIComponent(ev.id) + "/targets")
      .then((r) => r.json());
    if (!d.ok) throw new Error(d.error || "판정 실패");
    RISK_BPS = new Set(d.companies || []);
    const where = (d.areas || []).join("·") || "지역 미상";
    const mg = (d.materialGroups || []).length ? " · " + d.materialGroups.join("·") : "";
    how.textContent = RISK_BPS.size
      ? `${where}${mg} → BP ${d.bps}사 · 사업장 ${d.sites}곳`
      : `${where}${mg} → 걸리는 BP 없음`;
  } catch (e) {
    RISK_BPS = null; how.textContent = "판정하지 못했습니다 — " + e.message;
  }
  renderList();
}

// ── 표 보기 ────────────────────────────────────────────────────
// 카드는 눈으로 보기 좋고 표는 긁어가기 좋다. 같은 데이터를 같은 조건으로 본다.
// 셀 병합·배경 무늬를 두지 않는다 — 그대로 복사했을 때 엑셀에서 깨진다.
let GRID = false;

function renderGrid(entries) {
  if (!GRID) return;
  const tb = document.getElementById("gridRows");
  if (!tb) return;
  const out = [];
  entries.forEach(([name, sites]) => {
    const g = String(sites.find((x) => x.grade)?.grade || "-");
    const tier = sites.find((x) => x.tier)?.tier || "미등록";
    sites.forEach((r) => {
      const h = r.hotline || {};
      const miss = !(h.contactName || h.phone || h.email);
      out.push(`<tr>
        <td>${esc(name)}</td>
        <td>${esc(sites.find((x) => x.parentBp)?.parentBp || "")}</td>
        <td>${esc(gradeLabel(g))}</td><td>${esc(tier)}</td>
        <td class="mgcell">${(r.materialGroups || []).map((g, i) =>
          `${i ? `<span class="sep">, </span>` : ""}<span class="chip">${esc(g)}</span>`).join("")}</td>
        <td>${esc(r.country || "")}</td>
        <td>${esc(r.siteName || "")}</td>
        <td>${esc(r.siteRole || "")}</td>
        <td>${esc(r.admin1Ko || r.admin1 || "")}</td>
        <td>${esc(r.regionKo || "")}</td>
        <td class="${miss ? "miss" : ""}">${esc(h.contactName || (miss ? "미확보" : ""))}</td>
        <td>${esc(h.phone || "")}</td><td>${esc(h.email || "")}</td>
        <td>${esc(h.verifiedAt || "")}</td>
        <td>${esc(r.provenance || "")}</td>
        <td class="${(r.sources||[]).length ? "" : "miss"}">${(r.sources || []).length
          ? esc((r.sources || []).map((x) => x.url).join(" "))
          : "출처 미기입"}</td>
        <td>${esc(r.surveyedAt || "")}</td>
      </tr>`);
    });
  });
  tb.innerHTML = out.join("") ||
    `<tr><td colspan="17" class="empty">조건에 맞는 사업장이 없습니다.</td></tr>`;
}

function setGrid(on) {
  GRID = on;
  document.getElementById("grid").hidden = !on;
  document.getElementById("list").hidden = on;
  document.getElementById("viewToggle").textContent = on ? "카드로 보기" : "표로 보기";
  renderList();
}
document.getElementById("viewToggle").onclick = () => setGrid(!GRID);

function renderMgList() {
  const item = (g, n) =>
    `<div class="mg${(g ? SEL_MG.has(g) : !SEL_MG.size && !SEL_CAT) ? " on" : ""}" data-g="${esc(g)}"><span class="nm">${esc(g || "전체")}</span><span class="n">${n}</span></div>`;

  let html = item("", new Set(ROWS.map((r) => r.bpName)).size);
  const placed = new Set();
  CATEGORY.forEach(([cat, groups]) => {
    const rows = groups.filter((g) => MG.has(g)).sort((a, b) => MG.get(b).size - MG.get(a).size);
    if (!rows.length) return;
    // 대분류 옆 숫자는 그 분류의 BP 수다. 한 회사가 여러 소재군에 걸치면 한 번만 센다.
    const bps = new Set(rows.flatMap((g) => [...MG.get(g).keys()])).size;
    html += `<div class="mg-cat${SEL_CAT === cat ? " on" : ""}" data-cat="${esc(cat)}">`
          + `<span>${esc(cat)}</span><span class="ln"></span><span class="n">${bps}</span></div>`;
    rows.forEach((g) => { placed.add(g); html += item(g, MG.get(g).size); });
  });
  // 표에 없는 소재군이 생기면 조용히 사라지지 않게 따로 모아 보여준다.
  const rest = [...MG.keys()].filter((g) => !placed.has(g)).sort((a, b) => MG.get(b).size - MG.get(a).size);
  if (rest.length) {
    const bps = new Set(rest.flatMap((g) => [...MG.get(g).keys()])).size;
    html += `<div class="mg-cat"><span>미분류</span><span class="ln"></span><span class="n">${bps}</span></div>`;
    rest.forEach((g) => { html += item(g, MG.get(g).size); });
  }
  document.getElementById("mgList").innerHTML = html;
  document.querySelectorAll(".mg").forEach((e) => {
    // 누르면 더하고 다시 누르면 뺀다. '전체' 는 모두 해제.
    e.onclick = () => {
      const g = e.dataset.g;
      if (!g) SEL_MG.clear();
      else if (SEL_MG.has(g)) SEL_MG.delete(g);
      else SEL_MG.add(g);
      SEL_CAT = "";
      renderMgList(); renderList();
    };
  });
  // 대분류를 누르면 그 파트의 소재군을 한꺼번에 본다. 다시 누르면 해제된다.
  document.querySelectorAll(".mg-cat").forEach((e) => {
    e.onclick = () => {
      SEL_CAT = SEL_CAT === e.dataset.cat ? "" : e.dataset.cat;
      SEL_MG.clear();
      renderMgList(); renderList();
    };
  });
}

function siteRow(r) {
  const [pl, pc] = PREC[r.coordinateStatus] || ["미상", "pl"];
  const area = [r.admin1Ko || r.admin1, r.address].filter(Boolean).join(" · ");
  // 출처가 문자열이 아니면 그대로 찍어 [object Object]가 된다. 한 번 겪었다.
  const prov = typeof r.provenance === "string" ? r.provenance
             : r.provenance ? JSON.stringify(r.provenance) : "";
  const src = prov ? (/^https?:/.test(prov)
      ? `<a href="${esc(prov)}" target="_blank" rel="noopener">${esc(prov.replace(/^https?:\/\//, "").slice(0, 42))}</a>`
      : esc(prov.slice(0, 96))) : "—";
  return `<tr>
    <td>${esc(r.siteName || "—")}</td>
    <td><span class="tag pl">${esc(ROLE[r.siteRole] || r.siteRole || "—")}</span></td>
    <td>${esc(r.country)} · ${esc(area || "—")}</td>
    <td>${esc(r.regionKo || "—")}</td>
    <td><span class="tag ${pc}">${pl}</span></td>
    <td class="src">${src}${r.surveyedAt ? `<br>조사 ${esc(r.surveyedAt)}` : ""}</td>
  </tr>`;
}

function bpCard(name, sites) {
  const g = String(sites.find((s) => s.grade)?.grade || "-");
  const tier = sites.find((s) => s.tier)?.tier || "미등록";
  const groups = [...new Set(sites.flatMap((s) => s.materialGroups || []))];
  const countries = [...new Set(sites.map((s) => s.country))];
  const hot = sites.find(hasHot);
  const aliases = sites.find((s) => s.bpAliases)?.bpAliases || [];
  const isOpen = open.has(name);
  const gTag = gradeClass(g);
  return `<div class="bp" data-bp="${esc(name)}">
    <div class="bp-h" data-bp="${esc(name)}">
      <span class="nm">${esc(name)}</span>
      <span class="tag ${gTag}" title="${esc(gradeTitle(g))}">${esc(gradeLabel(g))}</span>
      <span class="tag ${tier === "점검" ? "" : "tam"}">${esc(tier)}</span>
      <span class="tag pl">${esc(countries.join(" "))}</span>
      <span class="tag pl">${sites.length} site</span>
      ${aliases.length ? `<span class="tag pl" title="병합된 이름">← ${esc(aliases.join(", "))}</span>` : ""}
      <span class="sp"></span>
      <a class="tag ${hot ? "ok" : "no"} hotlink" href="bp?tab=hot&bp=${encodeURIComponent(name)}"
            title="연락처 채우기 탭에서 이 BP 열기"
            onclick="event.stopPropagation()">${hot ? "연락처 있음" : "연락처 없음"}</a>
      ${hot ? staleBadge(hot.hotline.verifiedAt) : ""}
      ${/* 소재군은 낱개 칩으로. 한 줄로 이어 붙여 34자에서 자르던 것을 고쳤다 —
            소재군을 5개 만드는 BP 가 "…Slu" 에서 끊겨 누락처럼 보였다.
            지금 걸러 보는 군은 색을 채워 어느 것 때문에 걸렸는지 알린다. */""}
      ${groups.map((g) => `<span class="tag mg${SEL_MG.has(g) ? " on" : ""}">${esc(g)}</span>`).join("")}
      <button class="toMap" title="지도에서 이 BP의 사업장만 확대">지도</button>
    </div>
    ${isOpen ? `<div class="bp-b"><div class="tw"><table>
      <tr><th>사업장</th><th>역할</th><th>국가·행정구역</th><th>권역</th><th>좌표 근거</th><th>출처</th></tr>
      ${sites.map(siteRow).join("")}
    </table></div></div>
    ${contactList(sites)}
    <div class="hot" data-bp="${esc(name)}">
      <span class="lb">대표 연락처</span>
      <input class="h-n" placeholder="담당자" value="${esc(hot ? hot.hotline.contactName : "")}">
      <input class="h-p" placeholder="전화" value="${esc(hot ? hot.hotline.phone : "")}">
      <input class="h-e" placeholder="이메일" value="${esc(hot ? hot.hotline.email : "")}">
      <button>저장</button>
      ${hot && hasHot(hot) ? `<button class="clr" title="이 BP 의 연락처를 비웁니다">지우기</button>` : ""}
      ${hot && hot.hotline.verifiedAt
        ? `<span class="lb">확인 ${esc(hot.hotline.verifiedAt)}</span>${staleBadge(hot.hotline.verifiedAt)}`
        : ""}
      <span class="msg"></span>
    </div>
    ${hotlineHistory(sites)}` : ""}
  </div>`;
}

// 한 회사에 담당자가 여럿이다 — 부서·소재군별로 갈린다. 대표 한 명만 보이면
// 사건이 Photo 인데 Gas 담당자에게 묻게 된다. 그래서 전원을 편다.
// 연락처는 고치면 이전 값이 사라진다. 원장에는 남지만 원장을 열어 보는 사람은 없다.
// **누가 언제 무엇을 왜 바꿨는지가 그 자리에 보여야 이력이다.**
function hotlineHistory(sites) {
  const seen = new Set(); const rows = [];
  (sites || []).forEach(st => (st.hotlineHistory || []).forEach(h => {
    const k = `${h.ts}|${h.op}|${h.actor}`;
    if (seen.has(k)) return;
    seen.add(k); rows.push(h);
  }));
  if (!rows.length) return "";
  rows.sort((a, b) => String(b.ts).localeCompare(String(a.ts)));
  const OPKO = { "hotline.set": "대표 수정", "hotline.bulk": "일괄 등록", "hotline.clear": "비움" };
  const side = x => {
    if (!x) return "—";
    if (!x.n && !x.lead) return "없음";
    return `${esc(x.lead || "이름없음")}${x.n > 1 ? ` 외 ${x.n - 1}명` : ""}`;
  };
  return `<details class="fold hist"><summary>연락처 이력 <span class="lb">${rows.length}건</span></summary>
    <div class="hist-b">${rows.slice(0, 20).map(h => `
      <div class="hist-r">
        <span class="hist-t">${esc(String(h.ts || "").slice(0, 16).replace("T", " "))}</span>
        <span class="hist-o">${esc(OPKO[h.op] || h.op || "")}</span>
        <span class="hist-c">${side(h.before)} → <b>${side(h.after)}</b></span>
        <span class="hist-w">${esc(h.actor || "-")}${h.reason ? " · " + esc(h.reason) : ""}</span>
      </div>`).join("")}</div></details>`;
}

function contactList(sites) {
  const seen = new Set(), cs = [];
  sites.flatMap((s) => s.contacts || []).forEach((c) => {
    const k = (c.email || c.phone || c.name || "").toLowerCase();
    if (!k || seen.has(k)) return;
    seen.add(k); cs.push(c);
  });
  if (!cs.length) return "";
  return `<div class="cts"><div class="cth">담당자 ${cs.length}명</div><div class="tw"><table>
    <tr><th>담당자</th><th>담당 소재군</th><th>부서·비고</th><th>연락처</th><th>메일</th><th>송부</th></tr>
    ${cs.map((c) => `<tr>
      <td>${esc(c.name || "")}</td>
      <td>${esc((c.materialGroups || []).join(", "))}</td>
      <td>${esc(c.dept || "")}</td>
      <td>${esc(c.phone || "")}</td>
      <td>${c.email ? `<a href="mailto:${esc(c.email)}">${esc(c.email)}</a>` : ""}</td>
      <td>${esc(c.sent || "")}</td></tr>`).join("")}
  </table></div><div class="ctf">일괄 등록은 <a href="revision?tab=bulk">명부 편집 → 연락처 일괄</a></div></div>`;
}

// 대시보드에서 지목받은 회사 하나. 비어 있으면 평소처럼 전체를 본다.
let PIN = "";

function filtered() {
  const q = document.getElementById("q").value.trim().toLowerCase();
  const fg = document.getElementById("fGrade").value;
  const ft = document.getElementById("fTier").value;
  const fc = document.getElementById("fCountry").value;
  const fh = document.getElementById("fHot").value;

  // 소재군 하나 → 대분류 전체 → 전체 순으로 범위를 정한다.
  const catGroups = SEL_CAT ? (CATEGORY.find(([c]) => c === SEL_CAT) || [, []])[1] : null;
  const source = (() => {
    const m = new Map();
    // 고른 소재군 중 **하나라도** 만들면 나온다(합집합). 그래야 여러 군을 만드는 BP 가
    // 어느 군으로 걸러도 빠지지 않는다 — 24사(14%)가 그런 곳이다.
    const want = SEL_MG.size ? SEL_MG : (catGroups ? new Set(catGroups) : null);
    ROWS.forEach((r) => {
      if (want && !(r.materialGroups || []).some((g) => want.has(g))) return;
      if (!m.has(r.bpName)) m.set(r.bpName, []);
      m.get(r.bpName).push(r);
    });
    return m;
  })();

  const entries = [...source.entries()].filter(([name, sites]) => {
    if (PIN) return name === PIN;   // 지목받은 회사만. 다른 조건은 보지 않는다.
    if (RISK_BPS && !RISK_BPS.has(name)) return false;   // 사건으로 좁힌 상태
    const g = String(sites.find((s) => s.grade)?.grade || "-");
    const tier = sites.find((s) => s.tier)?.tier || "미등록";
    const hot = sites.some(hasHot);
    if (fg && gradeCode(g) !== fg) return false;
    if (ft && tier !== ft) return false;
    if (fc && !sites.some((s) => s.country === fc)) return false;
    if (fh === "yes" && !hot) return false;
    if (fh === "no" && hot) return false;
    if (q) {
      const hay = (name + " " + sites.map((s) =>
        [s.siteName, s.admin1Ko, s.admin1, s.address, s.regionKo].join(" ")).join(" ")).toLowerCase();
      if (!hay.includes(q)) return false;
    }
    return true;
  });

  entries.sort((a, b) => {
    const ga = gradeRank(a[1].find((s) => s.grade)?.grade);
    const gb = gradeRank(b[1].find((s) => s.grade)?.grade);
    return ga - gb || b[1].length - a[1].length || a[0].localeCompare(b[0]);
  });
  return entries;
}

let LAST = { bps: 0, sites: 0 };

// 검색줄과 목록 위 두 곳에 같은 숫자를 쓴다. 숫자가 갈리면 어느 쪽을 믿을지 알 수 없다.
function renderCount() {
  const q = document.getElementById("q").value.trim();
  document.getElementById("cnt").textContent = `BP ${LAST.bps}사 · 사업장 ${LAST.sites}`;
  document.getElementById("found").innerHTML = PIN
    ? `<b>${esc(PIN)}</b>만 보는 중`
    : q ? `<b>${LAST.bps}</b>사 · ${LAST.sites} site`
        : `전체 <b>${LAST.bps}</b>사`;
  document.getElementById("qClear").textContent = PIN ? "전체 보기" : "지우기";
}

function renderList(keepView) {
  const mn = document.getElementById("mapNote");
  if (mn) mn.hidden = true;          // 목록이 다시 그려지면 지난 안내는 지운다
  const entries = filtered();
  shown = entries.flatMap(([, s]) => s).filter((s) => Number.isFinite(Number(s.lat)));
  const sites = entries.reduce((n, e) => n + e[1].length, 0);
  LAST = { bps: entries.length, sites };
  renderGrid(entries);
  renderCount();
  document.getElementById("mapTitle").textContent =
    SEL_MG.size ? [...SEL_MG].join(" + ") : (SEL_CAT || "전체");
  document.getElementById("list").innerHTML = entries.length
    ? entries.map(([n, s]) => bpCard(n, s)).join("")
    : `<div class="empty">조건에 맞는 BP가 없습니다 — <b>조건</b>을 풀거나 검색어를 지우세요.
         <br><button class="go" onclick="clearNarrow()">조건 지우기</button></div>`;

  if (!keepView) fitTo(shown);
  renderMap(shown);

  document.querySelectorAll(".bp-h").forEach((el) => {
    el.onclick = () => {
      const n = el.dataset.bp;
      open.has(n) ? open.delete(n) : open.add(n);
      renderList(true);
    };
    // 카드에 마우스를 올리면 지도에서 그 회사의 사업장만 살아난다.
    // 목록의 이름과 지도의 점이 같은 것이라는 걸 이렇게만 보여줄 수 있다.
    el.addEventListener("mouseenter", () => focusBp(el.dataset.bp));
    el.addEventListener("mouseleave", blurBp);
  });

  // 카드마다 "지도에서 보기" — 회사 하나만 지도에 남기고 그 범위로 확대한다.
  document.querySelectorAll(".bp .toMap").forEach((btn) => {
    btn.onclick = (ev) => {
      ev.stopPropagation();
      const n = btn.closest(".bp").dataset.bp;
      const mine = shown.filter((x) => x.bpName === n);
      if (!mine.length) { alertNoCoord(n); return; }
      fitTo(mine); renderMap(shown); focusBp(n);
      document.querySelector(".mapbox").scrollIntoView({ behavior: "smooth", block: "center" });
    };
  });
  // 연락처 비우기 — 이유를 받아야만 진행한다. 원장은 append-only 라 지우는 것이
  // 아니라 "비었다"를 새로 적는다(hotline.clear). 왜 비웠는지가 안 남으면
  // 다음 사람은 "원래 없었다" 와 "누가 지웠다" 를 구별하지 못한다.
  document.querySelectorAll(".hot button.clr").forEach((btn) => {
    btn.onclick = async () => {
      const box = btn.closest(".hot"), msg = box.querySelector(".msg");
      const bp = box.dataset.bp;
      const reason = prompt(
        `${bp} 의 연락처를 비웁니다.\n\n왜 비우나요? (퇴사 · 오등록 · 협력사 삭제 요구 등)\n` +
        `이 문구가 이력에 남습니다.`);
      if (reason === null) return;
      if (!reason.trim()) { msg.className = "msg err"; msg.textContent = "이유를 적어야 비울 수 있습니다."; return; }
      btn.disabled = true; msg.className = "msg"; msg.textContent = "비우는 중…";
      try {
        const res = await fetch("api/ledger", {
          method: "POST", headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ op: "hotline.clear", bpKey: bp, reason: reason.trim() }),
        });
        const d = await res.json();
        if (!d.ok) { msg.className = "msg err"; msg.textContent = (d.problems || [d.error]).join(" / "); }
        else {
          msg.className = "msg ok"; msg.textContent = "비웠습니다 — 이력에 남았습니다.";
          setTimeout(() => location.reload(), 900);
        }
      } catch (e) { msg.className = "msg err"; msg.textContent = "실패: " + e.message; }
      finally { btn.disabled = false; }
    };
  });
  document.querySelectorAll(".hot button:not(.clr)").forEach((btn) => {
    btn.onclick = async () => {
      const box = btn.closest(".hot"), msg = box.querySelector(".msg");
      btn.disabled = true; msg.className = "msg"; msg.textContent = "저장 중…";
      try {
        const res = await fetch("api/ledger", {
          method: "POST", headers: { "Content-Type": "application/json" },
          // 원장은 변경 내용을 after 안에 담는다. 평면으로 보내면 빈 값이 저장된다.
          body: JSON.stringify({
            op: "hotline.set", bpKey: box.dataset.bp,
            after: {
              contactName: box.querySelector(".h-n").value.trim(),
              phone: box.querySelector(".h-p").value.trim(),
              email: box.querySelector(".h-e").value.trim(),
            },
            reason: "BP 기준 정보 · 연락처 입력",
          }),
        });
        const d = await res.json();
        if (!d.ok) { msg.className = "msg err"; msg.textContent = (d.problems || [d.error]).join(" / "); }
        else {
          // 응답만 믿지 않고 실제 반영분을 되읽어 확인한다.
          const rows = await fetch("api/resolved").then((r) => r.json());
          const n = rows.filter((r) => r.bpName === box.dataset.bp && hasHot(r)).length;
          if (n) { msg.className = "msg ok"; msg.textContent = `저장했습니다 · ${n}개 사업장에 반영`; }
          else { msg.className = "msg err"; msg.textContent = "원장에는 기록됐으나 반영되지 않았습니다."; }
        }
      } catch (e) { msg.className = "msg err"; msg.textContent = "실패: " + e.message; }
      finally { btn.disabled = false; }
    };
  });
}

// ── 확대·이동 ────────────────────────────────────────────────────
// viewBox 하나를 진실로 삼는다. 확대해도 점 크기가 그대로면 뭉치므로 renderMap이
// viewBox 폭을 보고 반지름을 다시 계산한다.
const svgEl = document.getElementById("map");

function getVB() { return svgEl.getAttribute("viewBox").split(" ").map(Number); }
function setVB(x, y, w, h) {
  // 세계 밖으로 나가지 않게 가둔다. 최소 폭은 12(≈4°) — 그 이상 들어가면 해안선이 뭉개진다.
  w = Math.min(Math.max(w, 12), 1000);
  h = w / 2;
  x = Math.max(0, Math.min(x, 1000 - w));
  y = Math.max(0, Math.min(y, 500 - h));
  svgEl.setAttribute("viewBox", `${x.toFixed(1)} ${y.toFixed(1)} ${w.toFixed(1)} ${h.toFixed(1)}`);
}

function zoomAt(factor, cx, cy) {
  const [x, y, w, h] = getVB();
  // 커서가 가리키던 지점이 제자리에 남도록 원점을 옮긴다.
  const rx = cx == null ? 0.5 : (cx - x) / w;
  const ry = cy == null ? 0.5 : (cy - y) / h;
  const nw = Math.min(Math.max(w * factor, 12), 1000);
  const nh = nw / 2;
  setVB(x + (w - nw) * rx, y + (h - nh) * ry, nw, nh);
  renderMap(shown);
}

function clientToMap(e) {
  const r = svgEl.getBoundingClientRect();
  const [x, y, w, h] = getVB();
  return [x + ((e.clientX - r.left) / r.width) * w,
          y + ((e.clientY - r.top) / r.height) * h];
}

svgEl.addEventListener("wheel", (e) => {
  e.preventDefault();
  const [mx, my] = clientToMap(e);
  zoomAt(e.deltaY > 0 ? 1.22 : 1 / 1.22, mx, my);
}, { passive: false });

// ── 끌기 · 핀치 ─────────────────────────────────────────────────
// 마우스는 한 손으로 끌고 휠로 확대한다.
// 터치는 다르다. 한 손가락으로 지도를 끌게 하면 페이지가 스크롤되지 않아
// 긴 목록을 내려볼 수 없다. 그래서 한 손가락은 페이지에 양보하고(탭은 받는다),
// 두 손가락일 때만 지도를 옮기고 넓힌다.
let drag = null;

svgEl.addEventListener("pointerdown", (e) => {
  if (e.pointerType !== "mouse") return;
  drag = { x: e.clientX, y: e.clientY, vb: getVB() };
  dragMoved = false;
  svgEl.classList.add("drag");
  svgEl.setPointerCapture(e.pointerId);
});
svgEl.addEventListener("pointermove", (e) => {
  if (!drag) return;
  const r = svgEl.getBoundingClientRect();
  const [x, y, w, h] = drag.vb;
  const dx = e.clientX - drag.x, dy = e.clientY - drag.y;
  if (Math.abs(dx) > 4 || Math.abs(dy) > 4) dragMoved = true;
  setVB(x - dx / r.width * w, y - dy / r.height * h, w, h);
});
["pointerup", "pointercancel", "pointerleave"].forEach((ev) =>
  svgEl.addEventListener(ev, () => {
    if (!drag) return;
    drag = null; svgEl.classList.remove("drag"); renderMap(shown);
    setTimeout(() => { dragMoved = false; }, 0);
  }));

// ── 두 손가락 ──
const dist = (t) => Math.hypot(t[0].clientX - t[1].clientX, t[0].clientY - t[1].clientY);
const mid  = (t) => ({ x: (t[0].clientX + t[1].clientX) / 2,
                       y: (t[0].clientY + t[1].clientY) / 2 });
let pinch = null;

svgEl.addEventListener("touchstart", (e) => {
  if (e.touches.length !== 2) return;
  pinch = { d: dist(e.touches), c: mid(e.touches), vb: getVB() };
  dragMoved = true;                 // 이 제스처가 끝난 뒤의 탭은 무시한다
  hideTip(); TIP_FOR = null;
}, { passive: true });

svgEl.addEventListener("touchmove", (e) => {
  if (!pinch || e.touches.length !== 2) return;
  e.preventDefault();               // 브라우저 페이지 확대를 막고 지도를 넓힌다
  const r = svgEl.getBoundingClientRect();
  const [x, y, w, h] = pinch.vb;
  const nd = dist(e.touches), c = mid(e.touches);
  const nw = Math.min(Math.max(w * (pinch.d / Math.max(nd, 1)), 12), 1000);
  const nh = nw / 2;
  // 두 손가락 중심이 가리키던 지점을 붙들고, 중심이 움직인 만큼 따라 옮긴다.
  const ax = x + ((pinch.c.x - r.left) / r.width) * w;
  const ay = y + ((pinch.c.y - r.top) / r.height) * h;
  const panX = (c.x - pinch.c.x) / r.width * nw;
  const panY = (c.y - pinch.c.y) / r.height * nh;
  setVB(ax - (ax - x) * (nw / w) - panX, ay - (ay - y) * (nh / h) - panY, nw, nh);
}, { passive: false });

["touchend", "touchcancel"].forEach((ev) =>
  svgEl.addEventListener(ev, (e) => {
    if (!pinch) return;
    if (e.touches.length < 2) { pinch = null; renderMap(shown);
      setTimeout(() => { dragMoved = false; }, 0); }
  }));

// 지도 밖을 누르면 툴팁을 닫는다. 터치에는 '치우기'가 없어 이 경로가 필요하다.
document.addEventListener("pointerdown", (e) => {
  if (e.pointerType === "mouse") return;
  if (e.target.closest("#dots") || e.target.closest(".mtip")) return;
  hideTip(); TIP_FOR = null; blurBp();
});

document.getElementById("zIn").onclick = () => zoomAt(1 / 1.5);
document.getElementById("zOut").onclick = () => zoomAt(1.5);
document.getElementById("mapFit").onclick = () => { fitTo(shown); renderMap(shown); };
document.getElementById("mapAll").onclick = () => {
  document.getElementById("map").setAttribute("viewBox", "0 0 1000 500"); renderMap(shown);
};

const fc = document.getElementById("fCountry");
[...new Set(ROWS.map((r) => r.country))].sort().forEach((c) => {
  const o = document.createElement("option"); o.value = o.textContent = c; fc.appendChild(o);
});
["q", "fGrade", "fTier", "fCountry", "fHot"].forEach((id) => {
  document.getElementById(id).addEventListener(id === "q" ? "input" : "change", () => {
    renderList();
    if (!document.getElementById("panelHot").hidden) renderHot();
  });
});

// 검색칸이 둘(지도 위·사이드바)이라 값이 갈리면 어느 쪽이 진짜인지 알 수 없다.
// 하나를 원본으로 두고 다른 하나는 항상 따라가게 묶는다.
const qMain = document.getElementById("q"), qTop = document.getElementById("q2");
function syncQ(from, to) {
  to.value = from.value;
  if (from.value) PIN = "";   // 직접 찾기 시작하면 지목은 푼다
  renderList();          // 지도도 같이 좁혀지고 그 범위로 다시 맞춰진다
}
qTop.addEventListener("input", () => syncQ(qTop, qMain));
qMain.addEventListener("input", () => { qTop.value = qMain.value; });
document.getElementById("qClear").onclick = () => {
  // 좁힌 것을 한 번에 다 푼다 — 검색어·지목·사건. 하나만 남으면 왜 안 보이는지 모른다.
  PIN = ""; qTop.value = ""; qMain.value = "";
  const rs = document.getElementById("riskSel");
  if (rs && rs.value) { rs.value = ""; applyRisk(null); } else renderList();
  qTop.focus();
};

renderHealth();
renderKpi(); renderMgList(); renderList(); loadRisks();

// ── 명부 상태 ───────────────────────────────────────────────────
// 화면에 보이는 것(필터 결과)이 아니라 명부 전체를 센다. 좁혀 놓고 보면
// 달성률이 올라가는데, 그건 채운 게 아니라 덜 본 것이다.
function renderHealth() {
  const bps = new Set(ROWS.map((r) => r.bpName));
  const hotBp = new Set(ROWS.filter(hasHot).map((r) => r.bpName));
  const items = [
    ["담당자 연락처", hotBp.size, bps.size, "사",
     "사건 발생 시 이 연락처로 점검 요청"],
    ["실측 좌표", ROWS.filter((r) => r.coordinateStatus === "verified_site").length,
     ROWS.length, "site", "거리 판정에 사용 가능한 좌표"],
    ["조사 이력", ROWS.filter((r) => r.surveyedAt).length, ROWS.length, "site",
     "정보 확인일 기록 여부"],
    ["근거 출처", ROWS.filter((r) => r.provenance).length, ROWS.length, "site",
     "정보 출처 기록 여부"],
  ];
  document.getElementById("health").innerHTML = items.map(([label, have, tot, unit, why]) => {
    const pct = tot ? Math.round(have / tot * 100) : 0;
    const cls = pct >= 100 ? "done" : pct < 25 ? "low" : "";
    const left = tot - have;
    return `<div class="it ${cls}" title="${esc(why)}">
      <div class="l">${esc(label)}<span class="n">${have}/${tot}</span></div>
      <div class="t"><i style="width:${pct}%"></i></div>
      <div class="r">${left ? `${left}${unit} 남음` : "다 채움"}</div>
    </div>`;
  }).join("");
  // 접어 둬도 "담당자 연락처가 몇 곳 비었나"는 요약 줄에 남는다.
  foldMark("healthFold", bps.size - hotBp.size, "곳 남음", false);
}


/* ══ 연락처 채우기 탭 ═══════════════════════════════════════════
   같은 filtered()를 쓴다. 검색·소재군·등급 필터를 한쪽에서 좁혀 놓고
   탭을 넘기면 그대로 이어진다. 두 탭이 서로 다른 목록을 보이면
   "아까 찾은 게 어디 갔지"가 된다.
   ══════════════════════════════════════════════════════════════ */
const CAT_OF = {};
CATEGORY.forEach(([c, gs]) => gs.forEach((g) => { CAT_OF[g] = c; }));

function hotBps() {
  return filtered().map(([name, sites]) => {
    const groups = [...new Set(sites.flatMap((x) => x.materialGroups || []))];
    const hot = sites.find(hasHot);
    return {
      name, sites,
      grade: String(sites.find((x) => x.grade)?.grade || "-"),
      tier: sites.find((x) => x.tier)?.tier || "미등록",
      parts: [...new Set(groups.map((g) => CAT_OF[g] || "기타"))],
      areas: [...new Set(sites.map((x) => x.country))],
      hot: hot ? hot.hotline : null,
    };
  });
}

// 채울 순서가 곧 우선순위다. 등급제가 걸린 곳부터, 그중 점검 대상부터.
function hotPriority(b) {
  return gradeRank(b.grade) * 10 + (b.tier === "점검" ? 0 : b.tier === "탐색" ? 2 : 1);
}

function renderProgress() {
  // 진행률은 화면에 보이는 것이 아니라 전체 기준이다. 필터를 걸었다고
  // 달성률이 올라가면 그건 거짓말이다.
  const all = [...new Map(ROWS.map((r) => [r.bpName, r])).keys()];
  const hotSet = new Set(ROWS.filter(hasHot).map((r) => r.bpName));
  const tot = all.length, have = hotSet.size;
  const pct = tot ? Math.round(have / tot * 100) : 0;
  document.getElementById("pHave").textContent = have;
  document.getElementById("pTot").textContent = tot;
  const pe = document.getElementById("pPct");
  pe.textContent = pct + "%";
  pe.style.color = pct >= 80 ? "var(--green)" : pct >= 40 ? "var(--amber)" : "var(--red)";
  document.getElementById("pBar").style.width = pct + "%";

  const meta = new Map();
  ROWS.forEach((r) => {
    if (!meta.has(r.bpName)) meta.set(r.bpName, { grade: "-", tier: "미등록" });
    const m = meta.get(r.bpName);
    if (r.grade) m.grade = String(r.grade);
    if (r.tier) m.tier = r.tier;
  });
  const buckets = [
    ["품질 등급 · 점검", (m) => gradeCode(m.grade) === "Q" && m.tier === "점검"],
    ["개발 등급만", (m) => gradeCode(m.grade) === "D"],
    ["등급 없음 · 탐색", (m) => gradeCode(m.grade) === "-" && m.tier === "탐색"],
    ["그 밖", (m) => gradeCode(m.grade) === "-" && m.tier !== "탐색"],
  ];
  document.getElementById("pSplit").innerHTML = buckets.map(([l, f]) => {
    const g = [...meta.entries()].filter(([, m]) => f(m));
    const h = g.filter(([n]) => hotSet.has(n)).length;
    return `<div><div class="v">${h}/${g.length}</div><div class="l">${esc(l)}</div></div>`;
  }).join("");

  const badge = document.getElementById("hotBadge");
  badge.textContent = tot - have ? `${tot - have} 남음` : "완료";
  badge.style.display = "";
}

function hotRow(b) {
  const h = b.hot || {};
  return `<tr class="${b.hot ? "done" : ""}" data-bp="${esc(b.name)}">
    <td class="bp">${esc(b.name)}</td>
    <td><span class="tag ${gradeClass(b.grade)}" title="${esc(gradeTitle(b.grade))}">${esc(gradeLabel(b.grade))}</span></td>
    <td><span class="tag ${b.tier === "점검" ? "" : "tam"}">${esc(b.tier)}</span></td>
    <td>${esc(b.parts.join(", "))}</td>
    <td>${esc(b.areas.join(" "))} · ${b.sites.length} site</td>
    <td><input class="h-n" placeholder="담당자" value="${esc(h.contactName || "")}"></td>
    <td><input class="h-p" placeholder="전화" value="${esc(h.phone || "")}"></td>
    <td><input class="h-e" placeholder="이메일" value="${esc(h.email || "")}"></td>
    <td class="act"><button>저장</button><span class="m">${h.verifiedAt ? "확인 " + esc(h.verifiedAt) : ""}</span>${staleBadge(h.verifiedAt)}</td>
  </tr>`;
}

function renderHot() {
  const fs = document.getElementById("fState").value;
  const list = hotBps().filter((b) => {
    if (fs === "no" && b.hot) return false;
    if (fs === "yes" && !b.hot) return false;
    return true;
  }).sort((a, b) => hotPriority(a) - hotPriority(b)
                 || b.sites.length - a.sites.length
                 || a.name.localeCompare(b.name));

  document.getElementById("hcnt").textContent = `${list.length}사`;
  // 비었다고 다 같은 빈 게 아니다. 다 채워서 빈 것과 조건이 좁아서 빈 것을 가른다 —
  // 앞은 축하하고 다음 단계로 보내고, 뒤는 조건을 풀어 준다.
  const allBps = new Set(ROWS.map((r) => r.bpName)).size;
  const doneBps = new Set(ROWS.filter(hasHot).map((r) => r.bpName)).size;
  const emptyRow = allBps && doneBps >= allBps
    ? `<tr><td colspan="9" class="empty">연락처를 다 채웠습니다. 이제 점검을 돌릴 수 있습니다.
         <br><a class="go" href="inspect">점검 현황 →</a></td></tr>`
    : `<tr><td colspan="9" class="empty">조건에 맞는 BP가 없습니다 — <b>조건</b>을 풀거나 검색어를 지우세요.
         <br><button class="go" onclick="clearNarrow()">조건 지우기</button></td></tr>`;
  document.getElementById("hrows").innerHTML = list.length
    ? list.map(hotRow).join("")
    : emptyRow;

  document.querySelectorAll("#hrows button").forEach((btn) => {
    btn.onclick = async () => {
      const tr = btn.closest("tr"), m = tr.querySelector(".m");
      const after = {
        contactName: tr.querySelector(".h-n").value.trim(),
        phone: tr.querySelector(".h-p").value.trim(),
        email: tr.querySelector(".h-e").value.trim(),
      };
      btn.disabled = true; m.className = "m"; m.textContent = "저장 중…";
      try {
        const res = await fetch("api/ledger", {
          method: "POST", headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ op: "hotline.set", bpKey: tr.dataset.bp, after,
                                 reason: "BP 기준 정보 · 연락처 채우기" }),
        });
        const d = await res.json();
        if (!d.ok) { m.className = "m err"; m.textContent = (d.problems || [d.error]).join(" / "); }
        else {
          // 응답만 믿지 않는다. 실제 반영분을 되읽어 확인한다.
          const fresh = await fetch("api/resolved").then((r) => r.json());
          const n = fresh.filter((r) => r.bpName === tr.dataset.bp && hasHot(r)).length;
          if (!n) { m.className = "m err"; m.textContent = "반영되지 않음"; }
          else {
            m.className = "m ok"; m.textContent = `${n} site 반영`;
            tr.classList.add("done");
            // 원본에도 반영해야 진행률과 명부 배지가 같이 움직인다.
            ROWS.forEach((r) => {
              if (r.bpName === tr.dataset.bp) {
                r.hotline = { ...after, verifiedAt: new Date().toISOString().slice(0, 10) };
              }
            });
            renderProgress(); renderKpi(); renderHealth(); renderList(true);
          }
        }
      } catch (e) { m.className = "m err"; m.textContent = "실패: " + e.message; }
      finally { btn.disabled = false; }
    };
  });
}

// ── 탭 전환 ──
function showTab(which) {
  const sites = which !== "hot";
  document.getElementById("panelSites").hidden = !sites;
  document.getElementById("panelHot").hidden = sites;
  document.getElementById("tabSites").classList.toggle("on", sites);
  document.getElementById("tabHot").classList.toggle("on", !sites);
  if (!sites) renderHot();
  // 주소에 남겨 새로고침·공유해도 같은 탭이 열린다.
  const u = new URL(location);
  sites ? u.searchParams.delete("tab") : u.searchParams.set("tab", "hot");
  history.replaceState(null, "", u);
}
document.getElementById("tabSites").onclick = () => showTab("sites");
document.getElementById("tabHot").onclick = () => showTab("hot");
// 좌측 메뉴에서 바로 들어올 수 있게 — bp?tab=hot
if(new URLSearchParams(location.search).get("tab") === "hot") showTab("hot");
document.getElementById("fState").addEventListener("change", renderHot);

// 접힌 채로도 무엇이 걸렸는지 보이게 — 숨긴 조건이 결과를 바꾸면 사람이 속는다
const NARROW = ["fGrade","fTier","fCountry","fHot"];
function syncNarrowBadge(){
  const on = NARROW.filter(id => (document.getElementById(id)||{}).value).length;
  foldMark("narrowBox", on, "개 적용 중", true);
}
// 빈 목록에서 부르는 되돌리기. 조건 넷과 검색어·지목을 한 번에 푼다 —
// 아무것도 안 나올 때 무엇을 풀어야 하는지 사람이 되짚게 하면 안 된다.
function clearNarrow(){
  NARROW.forEach(id => { const e = document.getElementById(id); if (e) e.value = ""; });
  PIN = ""; qTop.value = ""; qMain.value = "";
  syncNarrowBadge();
  renderList();
  if (!document.getElementById("panelHot").hidden) renderHot();
}
NARROW.forEach(id=>{
  const el=document.getElementById(id); if(el) el.addEventListener("change", syncNarrowBadge);
});
syncNarrowBadge();

renderProgress();

// ── 딥링크 수신 ─────────────────────────────────────────────────
// 순서가 중요하다: PIN(지목)을 먼저 굳히고 나서 탭을 연다.
// 탭을 먼저 열면 renderHot이 PIN 없는 목록을 그려 놓고 지나간다.
const qs = new URLSearchParams(location.search);

// 점검·재난 화면에서 권역을 누르면 ?region= 으로 넘어온다.
// 검색이 admin1·권역명을 이미 걸므로 검색칸 프리셋으로 푼다 — 별도 필터 축을 안 늘린다.
// 검색은 원본(#q)까지 함께 맞춰야 목록·지도·카운터(#found)가 같은 숫자를 말한다.
const qregion = qs.get("region");
if (qregion) { qTop.value = qregion; syncQ(qTop, qMain); }

// 대시보드에서 영향 가능 BP 칩을 누르면 ?bp= 로 넘어온다.
// 그 회사를 펼쳐서 사업장까지 바로 보여준다 — 넘어와서 또 찾게 하면 이어준 게 아니다.
const qbp = qs.get("bp");
if (qbp) {
  // 검색칸에 넣으면 "TOK"가 "Tokyo"까지 걸어 83곳이 나온다.
  // 대시보드가 지목한 건 회사 하나이므로 이름이 정확히 같은 것만 남긴다.
  PIN = qbp;
  open.add(qbp);
  // 기본 "미확보만"은 이미 연락처가 있는 지목 BP를 숨긴다 — 딥링크는 전체로 연다.
  document.getElementById("fState").value = "";
  renderList();
}

if (qs.get("tab") === "hot") showTab("hot");

if (qbp) {
  const el = document.querySelector(`.bp[data-bp="${CSS.escape(qbp)}"]`);
  if (el) el.scrollIntoView({ block: "center" });
}
</script>

</body>
</html>
