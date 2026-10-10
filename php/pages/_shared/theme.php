
<style>
@font-face{
  font-family:'Pretendard'; font-style:normal; font-display:swap; font-weight:45 930;
  src:local('Pretendard'),
      url("<?= url('/static/260611_junghoon/PretendardVariable.woff2') ?>") format('woff2-variations');
}

:root{
  /* 면 */
  --bg:#FFFFFF; --card:#FFFFFF; --panel:#FAFBFC;
  --surface:#FAFBFC; --surface-2:#F4F6F8;
  /* 선 */
  --line:#E8EBEF; --line-2:#F1F3F7; --line2:#D9DEE5;
  --navy-line:#C3D0E4; --accent-line:#C3D0E4;
  /* 글자 */
  --ink:#1A1F29; --ink-2:#4A535E; --ink-3:#6B7480;
  --sub:#4A535E; --mut:#6B7480; --disabled-ink:#9AA3AE;
  /* 강조 */
  --navy:#1D3A6E; --navy-2:#2C5CC5; --navy-3:#2C5CC5;
  --accent:#1D3A6E; --accent-2:#2C5CC5; --accent-soft:#EEF3FA;
  --p:#1D3A6E;
  --navy-hover:#17305C; --on-navy:#FFFFFF; --on-navy-dim:#A9BEDF;
  --navy-tint:#E8EEF9;
  /* 상태 — hi/md/lo 는 심각도, ok 는 완료·정상 전용 */
  --red:#C4283C; --amber:#8C6420; --green:#2E6B52; --ok:#2E6B52;
  /* RISK 감지 4단계 — **순서형이라 단일 색조 램프**를 쓴다(옅음 → 진함).
     단계를 바꿔 놓으면 뜻이 달라지므로 색에도 순서가 보여야 한다.
     뒤로 갈수록 진해지는 것은 "우리 일에 가까워진다" 는 뜻이다.
     명도 단조 확인: L 0.810 → 0.490 → 0.214 → 0.095 */
  --rk-1:#F7E4E7; --rk-2:#E9A9B3; --rk-3:#D2566B; --rk-4:#A81E31;
  /* **정상일 때 쓰는 무채색 램프.** 색은 이상에만 쓴다 —
     아무 일도 없는데 화면이 붉으면, 정작 일이 났을 때 그 붉은색이 안 보인다.
     ASM Consortium: "Many bright, saturated colors fail to draw attention to
     critical states" / "Selective use of color draws attention to abnormal states."
     Few 도 같은 말을 한다 — "When everything is visually prominent, nothing stands out." */
  --rq-1:#EDEFF2; --rq-2:#DCE0E6; --rq-3:#BFC6D0; --rq-4:#8A94A3;
  --hi:#C4283C; --hi-bg:#FDF7F8; --hi-line:#EBC7CD;
  --md:#8C6420; --md-bg:#FDFAF4; --md-line:#EADFC8;
  --lo:#6B7480; --lo-bg:#FAFBFC; --lo-line:#E8EBEF;
  --ok-bg:#F5FAF6; --ok-line:#C4DBCF;
  --red-ink:#7F1D28;
  --disabled-bg:#F4F6F8;
  /* 지도 */
  --map-land:#EDF0F4; --map-bp:#1D3A6E; --map-bubble:#2C5CC5;
  /* 글꼴 */
  --font-sans:'Pretendard','Pretendard Variable','Segoe UI','Malgun Gothic','맑은 고딕',
              -apple-system,BlinkMacSystemFont,'Apple SD Gothic Neo',Arial,sans-serif;
  --mono:ui-monospace,SFMono-Regular,Menlo,Consolas,'D2Coding',monospace;
  /* 크기 */
  --fs-2xs:11px; --fs-xs:12px; --fs-sm:13px; --fs-base:14.5px;
  --fs-md:16px; --fs-lg:19px; --fs-xl:25px; --fs-kpi:26px;
  /* 간격 */
  --sp-1:4px; --sp-2:8px; --sp-3:12px; --sp-4:16px;
  --sp-5:20px; --sp-6:24px; --sp-7:32px; --sp-8:40px;
  /* 모서리 · 음영 */
  /* 모서리 — 세 단뿐이다. 역할이 단을 정한다.
     sm 칩·뱃지·태그 · md 버튼·입력·탭·줄·접기 · lg 카드·패널·모달 */
  --r-sm:4px; --r-md:8px; --r-lg:12px;
  --sh:0 1px 2px rgba(26,31,41,.04);
  --sh-2:0 2px 6px rgba(26,31,41,.05), 0 1px 2px rgba(26,31,41,.04);
  --shadow:0 1px 2px rgba(26,31,41,.04);
  --navbar-bg:rgba(255,255,255,.95);
  /* 상단바는 좌측 메뉴로 대체됐다. 토큰은 남기되 0 — 아직 이걸 빼는 계산이 있다. */
  /* ── 폭 ──────────────────────────────────────────────────────
     실제 운영은 **사내 윈도우 데스크탑 16:9**(대개 1920×1080, 일부 1366×768)다.
     그렇다고 폭을 화면 끝까지 늘리지는 않는다 — 한 줄이 길면 눈이 다음 줄
     첫 글자를 못 찾고, 폼 입력칸이 1500px 로 늘어나면 쓰기 나빠진다.
     남는 여백은 낭비가 아니라 숨 쉴 자리다.

     화면 성격으로 셋을 둔다. 원래 화면마다 흩어져 있던 값(860~1320)을
     같은 성격끼리 모은 것이지, 넓히려고 만든 것이 아니다.

     page  다루는 화면 — 현황 · 명부 · 점검 · 변경. 표·지도·목록이라 폭이 필요하다
     form  채우는 화면 — 설정. 입력칸이 넓으면 어디를 봐야 할지 모른다
     read  읽는 화면 — 판정 기준. 문서는 줄이 짧아야 읽힌다

     레일 240 은 탑티어 권장 폭(240~300)의 아래끝. 접힘 64 는 권장(48~64)의 위끝.  */
  --navbar-h:0px; --rail-w:240px; --rail-w-min:64px;
  /* 본문 폭 — 두 가지뿐이다. 성격이 폭을 정한다.
     page 화면(목록·지도·표) · read 글(문서·설정) */
  --page-w:1120px; --read-w:900px;
  color-scheme:light;
}
:root[data-theme="dark"]{
  --bg:#0F1726; --card:#16223A; --panel:#131E31;
  --surface:#131E31; --surface-2:#1B2A45;
  --line:#26375A; --line-2:#1F2E4C; --line2:#31466E;
  --navy-line:#3D5A8A; --accent-line:#3D5A8A;
  --ink:#E9EEF6; --ink-2:#B9C5D8; --ink-3:#8797AF;
  --sub:#B9C5D8; --mut:#8797AF; --disabled-ink:#5F6F88;
  --navy:#2E5490; --navy-2:#8FB4E8; --navy-3:#8FB4E8;
  --accent:#8FB4E8; --accent-2:#A9C7F0; --accent-soft:#1A2B4A;
  --p:#2E5490;
  --navy-hover:#3A659F; --on-navy:#FFFFFF; --on-navy-dim:#B9CDEC;
  --navy-tint:#1E3358;
  --red:#E27585; --amber:#D3A75C; --green:#6FBA97; --ok:#6FBA97;
  /* 다크는 **anchor 가 뒤집힌다** — 어두운 바탕에서는 진한 쪽이 묻히므로
     램프가 진함 → 옅음으로 간다. 라이트를 뒤집은 것이 아니라 따로 고른 값이다.
     명도 단조 확인: L 0.021 → 0.076 → 0.230 → 0.396 */
  --rk-1:#3A2027; --rk-2:#7A3A47; --rk-3:#C2697A; --rk-4:#E8909E;
  --rq-1:#1E2739; --rq-2:#2A3547; --rq-3:#4A586E; --rq-4:#8797AF;
  --hi:#E27585; --hi-bg:#2C1B22; --hi-line:#6E3743;
  --md:#D3A75C; --md-bg:#2A2314; --md-line:#5E4D26;
  --lo:#8797AF; --lo-bg:#1B2438; --lo-line:#2C3C5C;
  --ok-bg:#152A21; --ok-line:#2F5847;
  --red-ink:#F0A9B2;
  --disabled-bg:#1B2438;
  --map-land:#1D2B45; --map-bp:#8FB4E8; --map-bubble:#7EA6E8;
  --sh:0 1px 2px rgba(0,0,0,.4);
  --sh-2:0 2px 8px rgba(0,0,0,.45), 0 1px 2px rgba(0,0,0,.35);
  --shadow:0 1px 2px rgba(0,0,0,.4);
  --navbar-bg:rgba(15,23,38,.92);
  color-scheme:dark;
}
/* 인쇄는 항상 라이트 지면 기준 */
@media print{ :root, :root[data-theme="dark"]{ color-scheme:light } }

html{scrollbar-gutter:stable;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;text-rendering:optimizeLegibility}
body{font-family:var(--font-sans);font-size:var(--fs-base);
     letter-spacing:-.011em;font-variant-numeric:tabular-nums;
     background:var(--bg);color:var(--ink)}
h1{letter-spacing:-.035em} h2{letter-spacing:-.03em} h3{letter-spacing:-.02em}
code,pre,.mono{font-family:var(--mono)}

/* 공통 상단 바는 좌측 레일로 바뀌면서 없어졌다(아래 '좌측 메뉴' 절).
   여기 남아 있던 선언들은 **선택자가 지워진 채** 남아, CSS 오류 복구가
   다음 `{` 까지를 선택자로 삼는 바람에 바로 뒤의 .theme-tg 규칙을 통째로
   삼키고 있었다. 규칙이 하나 죽어도 화면은 그럭저럭 떠서 아무도 몰랐다. */
.theme-tg{border:1px solid var(--line2);background:var(--card);color:var(--ink-3);
          border-radius:var(--r-md);padding:3px 8px;font-size:var(--fs-xs);
          cursor:pointer;font-family:inherit;line-height:1.5}
.theme-tg:hover{color:var(--navy-2);border-color:var(--navy-line)}

/* ── 페이지 헤더: 흐름 줄 + 제목 + 리드 ── */
.pagehead{margin:0 0 4px}
h1.pagetitle{margin:0;font-size:var(--fs-xl);font-weight:800}
p.lead{margin:7px 0 22px;font-size:var(--fs-base);color:var(--ink-2);line-height:1.65;max-width:80ch}
.flowline{display:flex;gap:6px;align-items:center;flex-wrap:wrap;margin:0 0 7px;
          font-size:var(--fs-xs);color:var(--ink-3)}
.flowline a{color:var(--ink-3);text-decoration:none;padding:1px 6px;border-radius:var(--r-sm)}
.flowline a:hover{background:var(--surface-2);color:var(--navy-2)}
.flowline .cur{color:var(--navy);font-weight:800;background:var(--accent-soft);
               padding:1px 6px;border-radius:var(--r-sm);border:1px solid var(--navy-line)}
:root[data-theme="dark"] .flowline .cur{color:var(--navy-2)}
.flowline .sep{color:var(--line2)}
.flowline .aux{color:var(--ink-3)}

/* ── 요약 스탯 바 (구 .sumbar/.kpi/.kpi-strip 4벌의 단일 후속) ── */
.statbar{display:flex;gap:var(--sp-5);flex-wrap:wrap;margin:var(--sp-4) 0 var(--sp-5);
         padding:var(--sp-3) var(--sp-4);border:1px solid var(--line);
         border-radius:var(--r-lg);background:var(--surface)}
.stat{min-width:88px}
.stat .v{display:block;font-size:var(--fs-kpi);font-weight:800;letter-spacing:-.02em;
         font-variant-numeric:tabular-nums;color:var(--ink);line-height:1.15}
.stat .v small{font-size:var(--fs-sm);font-weight:600;color:var(--ink-3)}
.stat .l{display:block;margin-top:2px;font-size:var(--fs-2xs);color:var(--ink-3)}
.stat.warn .v{color:var(--red)}
.stat.ok .v{color:var(--ok)}
.stat.dim .v{color:var(--ink-3)}

/* ── 카드 ── */
.card{background:var(--card);border:1px solid var(--line);border-radius:var(--r-lg);
      padding:var(--sp-4);box-shadow:var(--sh)}
.card.alert{border-color:var(--hi-line);background:var(--hi-bg)}
.card.quiet{background:var(--surface);box-shadow:none}

/* ── pill / tag — 상태 문법 정본 ─────────────────────────────
     색+글리프 이중 부호화: ▲높음 ●중간 ○낮음 ✓완료 !초과.
     .tag 는 .pill 의 동의어(이행기 호환) — 새 코드는 .pill 을 쓴다. ── */
.pill,.tag{display:inline-flex;align-items:center;gap:4px;padding:1px 8px;
     border-radius:var(--r-sm);font-size:var(--fs-2xs);font-weight:700;line-height:1.7;
     border:1px solid var(--lo-line);background:var(--lo-bg);color:var(--ink-2);
     white-space:nowrap;vertical-align:middle}
.pill::before,.tag::before{font-weight:400}
/* 심각도 */
.pill.sev-high{background:var(--hi-bg);border-color:var(--hi-line);color:var(--hi)}
.pill.sev-high::before{content:"▲"}
.pill.sev-med{background:var(--md-bg);border-color:var(--md-line);color:var(--md)}
.pill.sev-med::before{content:"●"}
.pill.sev-low{background:var(--lo-bg);border-color:var(--lo-line);color:var(--lo)}
.pill.sev-low::before{content:"○"}
/* 확실성 (certainty) */
.pill.st-conf{background:var(--accent-soft);border-color:var(--navy-line);color:var(--navy)}
:root[data-theme="dark"] .pill.st-conf{color:var(--navy-2)}
.pill.st-conf::before{content:"●"}
.pill.st-unconf{border-style:dashed;color:var(--mut)}
.pill.st-unconf::before{content:"○"}
/* 점검 진행 */
.pill.ins-pending{border-style:dashed;color:var(--mut)}
.pill.ins-pending::before{content:"…"}
.pill.ins-sent{background:var(--accent-soft);border-color:var(--navy-line);color:var(--navy)}
:root[data-theme="dark"] .pill.ins-sent{color:var(--navy-2)}
.pill.ins-sent::before{content:"↗"}
.pill.ins-replied{background:var(--ok-bg);border-color:var(--ok-line);color:var(--ok)}
.pill.ins-replied::before{content:"✓"}
.pill.ins-closed{color:var(--mut);background:var(--surface-2);border-color:var(--line2)}
.pill.ins-closed::before{content:"■"}
/* 기한 이진 배지 — 초과는 시스템에서 가장 시끄러운 배지다 */
.pill.due-over{background:var(--hi-bg);border:1.5px solid var(--hi);color:var(--hi)}
.pill.due-over::before{content:"!";font-weight:800}
.pill.due-in{color:var(--mut)}
/* 범용 판정 */
.pill.ok,.tag.ok{background:var(--ok-bg);border-color:var(--ok-line);color:var(--ok)}
.pill.ok::before,.tag.ok::before{content:"✓"}
.pill.warn,.tag.warn{background:var(--md-bg);border-color:var(--md-line);color:var(--md)}
.pill.no,.tag.no{background:var(--surface-2);border-color:var(--line2);color:var(--mut)}
.pill.mono{font-family:var(--mono);font-weight:600}
/* 개발 등급제 — 품질(기본 배지)과 눈으로 갈리게 */
.tag.dv{background:var(--accent-soft);color:var(--navy-2);border-color:var(--navy-line)}

/* ── 버튼 ── */
.btn{display:inline-flex;align-items:center;gap:6px;padding:7px 13px;
     border-radius:var(--r-md);border:1px solid var(--line2);background:var(--card);
     color:var(--ink);font-size:var(--fs-sm);font-weight:600;cursor:pointer;
     text-decoration:none;font-family:inherit;line-height:1.5}
.btn:hover{border-color:var(--navy-line);background:var(--surface)}
.btn.pri{background:var(--navy);border-color:var(--navy);color:var(--on-navy);font-weight:700}
.btn.pri:hover{background:var(--navy-hover);border-color:var(--navy-hover)}
.btn.ghost{background:transparent;border-color:transparent;color:var(--navy-2)}
.btn.ghost:hover{background:var(--surface-2)}
.btn.danger{color:var(--red);border-color:var(--hi-line);background:var(--hi-bg)}
.btn.danger:hover{border-color:var(--hi)}
.btn.sm{padding:3px 9px;font-size:var(--fs-xs)}
.btn:disabled,.btn[aria-disabled="true"]{color:var(--disabled-ink);background:var(--disabled-bg);
     border-color:var(--line);cursor:not-allowed}

/* ── 표 ── */
.tbl-wrap{overflow-x:auto}
.tbl{width:100%;border-collapse:collapse;font-size:var(--fs-sm)}
.tbl th{font-size:var(--fs-2xs);font-weight:700;color:var(--ink-3);text-align:left;
        padding:7px 10px;border-bottom:1px solid var(--line2);background:var(--surface);
        white-space:nowrap;letter-spacing:.02em}
.tbl td{padding:7px 10px;border-bottom:1px solid var(--line-2);vertical-align:top}
.tbl tbody tr:hover{background:var(--surface)}
.tbl td.n,.tbl th.n{text-align:right;font-variant-numeric:tabular-nums;
                    font-family:var(--mono);font-size:var(--fs-xs)}

/* ── .fold — 접기 표준 ────────────────────────────────────────
   지금 할 일 하나만 펼쳐 두고, 나머지는 여기 들어간다.
   구조:
     <details class="fold" id="xxFold">
       <summary>이름 <span class="fold-hint">보조 설명</span><span class="fold-n"></span></summary>
       <div class="fold-body"> … </div>
     </details>
   변형: .plain 문서 본문 안(테두리 없음) · .card 문서 아코디언 · .menu ⋯ 팝오버
   배지: .fold-n 은 foldSync() 가 채운다 — 접힌 채로도 무엇이 걸렸는지 보여야 한다.
   ────────────────────────────────────────────────────────── */
details.fold{border:1px solid var(--line);border-radius:var(--r-lg);background:var(--surface)}
details.fold + details.fold{margin-top:8px}
details.fold > summary{display:flex;align-items:center;gap:7px;cursor:pointer;
        padding:10px 13px;font-size:var(--fs-sm);font-weight:700;color:var(--ink-2);
        list-style:none;user-select:none;border-radius:var(--r-md)}
details.fold > summary::-webkit-details-marker{display:none}
details.fold > summary::before{content:"▸";flex:0 0 auto;color:var(--ink-3);
        font-size:11px;line-height:1;transition:transform .12s ease}
details.fold[open] > summary::before{transform:rotate(90deg)}
details.fold > summary:hover{color:var(--ink);background:var(--surface-2)}
details.fold > summary:focus-visible{outline:2px solid var(--navy-2);outline-offset:-2px}
details.fold[open] > summary{border-bottom:1px solid var(--line-2);
        border-radius:var(--r-md) var(--r-md) 0 0}
details.fold > .fold-body{padding:11px 13px 13px}

/* 요약 줄 부속 */
.fold-hint{font-weight:400;font-size:var(--fs-xs);color:var(--ink-3)}
.fold-n{margin-left:auto;flex:0 0 auto;font-size:var(--fs-2xs);font-weight:700;
        color:var(--navy);background:var(--accent-soft);border:1px solid var(--navy-line);
        border-radius:var(--r-sm);padding:0 7px;line-height:1.75;white-space:nowrap}
.fold-n:empty{display:none}
:root[data-theme="dark"] .fold-n{color:var(--navy-2)}
details.fold.on{border-color:var(--navy-line)}

/* 변형 — 문서 본문 안 */
details.fold.plain{border:0;background:none}
details.fold.plain > summary{padding:6px 0}
details.fold.plain[open] > summary{border-bottom:0}
details.fold.plain > .fold-body{padding:4px 0 10px}

/* 변형 — 문서 아코디언(기준 화면) */
details.fold.card > summary{padding:12px 16px;font-size:var(--fs-base);color:var(--ink)}
details.fold.card > .fold-body{padding:14px 16px;font-size:var(--fs-sm);
        color:var(--ink-2);line-height:1.85}

/* 변형 — ⋯ 팝오버(카드 안 그 밖의 처리) */
details.fold.menu{position:relative;border:0;background:none;margin-left:auto}
details.fold.menu > summary{padding:4px 9px;color:var(--ink-3);font-size:var(--fs-base);
        font-weight:700}
details.fold.menu > summary::before{content:none}
details.fold.menu[open] > summary{border-bottom:0;background:var(--surface-2);color:var(--ink)}
details.fold.menu > .fold-body{position:absolute;right:0;top:100%;z-index:20;min-width:138px;
        display:flex;flex-direction:column;gap:4px;padding:6px;background:var(--card);
        border:1px solid var(--line);border-radius:var(--r-md);box-shadow:var(--sh-2)}

@media (pointer:coarse){ details.fold > summary{padding-top:13px;padding-bottom:13px} }
@media print{ details.fold > .fold-body{display:block !important} }

/* ── 참고 숫자 한 줄 — 행동을 부르지 않는 숫자는 전부 여기로 ── */
.refline{display:flex;flex-wrap:wrap;align-items:baseline;gap:2px 18px;
         margin:0 0 var(--sp-5);font-size:var(--fs-xs);color:var(--ink-3);line-height:1.95}
.refline .r{display:inline-flex;align-items:baseline;gap:5px;white-space:nowrap}
.refline .r b{font-weight:700;color:var(--ink-2);font-variant-numeric:tabular-nums}
.refline .r.warn b{color:var(--red)}
.refline .src{margin-left:auto}

/* ── 행동 stat — 화면당 최대 3칸, 이것만 크다 ── */
.statbar.act{gap:var(--sp-7);padding:var(--sp-4) var(--sp-5);margin-bottom:var(--sp-3)}
.statbar.act .stat .v{font-size:calc(var(--fs-kpi) + 5px)}

/* ── 구버전 안내 배지 ── */
.legacy-note{display:flex;gap:8px;align-items:center;padding:8px 12px;margin:0 0 10px;
        border:1px dashed var(--md-line);background:var(--md-bg);color:var(--md);
        border-radius:var(--r-lg);font-size:var(--fs-xs);font-weight:600}
.legacy-note a{color:var(--navy-2);font-weight:700}

/* ── 다음 단계 ── */
.nextstep{margin:26px 0 0;display:flex;align-items:center;gap:11px;flex-wrap:wrap;
          padding:13px 17px;border:1px solid var(--navy-line);background:var(--accent-soft);
          border-radius:var(--r-md);font-size:var(--fs-base);color:var(--ink-2)}
.nextstep b{color:var(--navy);font-weight:800}
:root[data-theme="dark"] .nextstep b{color:var(--navy-2)}
.nextstep .go{margin-left:auto;padding:8px 15px;border-radius:var(--r-md);
              background:var(--navy);color:var(--on-navy);font-weight:700;font-size:var(--fs-sm);
              text-decoration:none;white-space:nowrap}
.nextstep .go:hover{background:var(--navy-hover)}
/* ── 공통 꼬리말 ── */
.pagefoot{margin-top:34px;padding-top:15px;border-top:1px solid var(--line);
          font-size:var(--fs-sm);color:var(--ink-3);line-height:1.75}
.pagefoot b{color:var(--ink-2);font-weight:700}
.pagefoot .conf{color:var(--red);font-weight:700}

/* ── 손가락 기기 ── */
@media (pointer: coarse) {
  button, .btn, select, input[type=search]{min-height:40px}
  .tabs button{padding:12px 18px}
  a, button{-webkit-tap-highlight-color:rgba(29,58,110,.12)}
}
/* 노치·홈 인디케이터 여백. 앞서 빈 @media 와 선택자 없는 선언이 남아
   바로 아래 body 규칙까지 삼켰고, 그래서 **아이패드에서 본문 마지막 줄이
   홈 인디케이터에 물렸다.** 좌우는 레일이 이미 밀어 주므로 아래만 둔다. */
body{padding-bottom:env(safe-area-inset-bottom);
     padding-left:env(safe-area-inset-left);
     padding-right:env(safe-area-inset-right)}


/* ── 좌측 메뉴 ─────────────────────────────────────────────────
   본문을 미는 여백은 nav.html 쪽에 있다 — 부품이 있는 화면에만 걸려야 해서다.

   디자인 규약 v3 를 참고하되 두 가지는 사용자 결정을 따른다.
     · 흰 배경 · 활성 항목은 네이비로 채운다. 규약 조문 12 는 활성 표시를
       '인디케이터 + 웨이트' 로 제한하지만, 이 시스템이 원래 쓰던 상단바가
       네이비 채움이었고 좌측 메뉴에서도 '지금 여기' 가 더 분명하다.
     · 채움을 쓰므로 좌측 인디케이터는 겹치지 않게 뺀다 — 수단은 하나다.
   나머지는 규약대로다 — 간격·크기는 토큰만, 웨이트 400/700/800,
   좁은 화면에서 햄버거로 감추지 않는다(가로 스크롤 띠).                  */
.rail{line-height:1.45;position:fixed;top:0;left:0;bottom:0;width:var(--rail-w);z-index:70;
  transition:transform .16s ease;
  background:var(--bg);border-right:1px solid var(--line2);
  display:flex;flex-direction:column;overflow-y:auto;overscroll-behavior:contain;}

/* 브랜드와 메뉴 사이에 선을 둔다 — 아래(정보 영역)에는 있는데 위만 없으면
   제목이 첫 메뉴 항목처럼 읽힌다. 위아래가 같은 선이라야 가운데가 목록으로 묶인다. */
/* ── 메뉴 접기 ───────────────────────────────────────────────────
   BP 현황처럼 화면이 **자기 사이드바를 또 가진** 곳에서는 좌측이 두 겹이 된다.
   그때 본메뉴를 접어 본문에 폭을 돌려준다.

   접어도 **사라지지 않는다** — 폭만 222px → 64px 로 줄고 짧은 이름이 남는다.
   탑티어도 접힌 사이드바를 48~64px 로 줄여 아이콘만 남기지 통째로 숨기지 않는다.
   우리는 아이콘 대신 짧은 이름을 쓴다(Risk · 점검 · BP · 설정 · 기준) —
   이름이 이미 짧아 글자가 아이콘보다 정확하다.
   상태는 화면을 옮겨도 유지된다(mgscs_rail).                              */
.rail-fold{position:absolute;top:var(--sp-3);right:var(--sp-2);
  width:22px;height:22px;padding:0;cursor:pointer;
  background:none;border:0;color:var(--ink-3);font-size:var(--fs-sm);line-height:1;}
.rail-fold::after{content:"«"}
.rail-fold:hover{color:var(--navy-2);}
.rail-nav a i{display:none;}

body.rail-off .rail{width:var(--rail-w-min);overflow-x:hidden;}
/* 접힘에서는 **어떤 글자도 줄바꿈하지 않는다.**
   우리말은 글자 사이에서 끊기므로, 64px 안에 든 글이 조금만 길어도
   '나/가/기' 처럼 세로로 쪼개진다. 넘치면 자를지언정 찌그러뜨리지 않는다.
   이 한 줄이 앞으로 항목이 늘어도 같은 사고를 막는다. */
body.rail-off .rail,
body.rail-off .rail *{white-space:nowrap;word-break:keep-all;}
body.rail-off .rail-fold{position:static;margin:var(--sp-3) auto 0;display:block;}
body.rail-off .rail-fold::after{content:"»"}
body.rail-off .rail-ci{height:18px;margin:0 auto var(--sp-2);}
body.rail-off .rail-wm{padding:var(--sp-3) var(--sp-2);text-align:center;}
body.rail-off .rail-wm b,
body.rail-off .rail-wm .full,
body.rail-off .rail-wm span,
body.rail-off .rail-note,
body.rail-off .rail-own,
body.rail-off .rail-live span:not(.live-dot),
body.rail-off .rail-who,
/* '나가기' 는 64px 안에서 세로로 쪼개진다. 접힘에서는 감춘다 —
   한 번 펴면 바로 있으니 길이 사라지는 것은 아니다. */
body.rail-off .rail-act{display:none;}
body.rail-off .rail-nav{padding:var(--sp-2) 6px;}
body.rail-off .rail-nav a{padding:var(--sp-3) 2px;text-align:center;}
body.rail-off .rail-nav a b{display:none;}
body.rail-off .rail-nav a i{display:block;font-style:normal;
  font-size:var(--fs-2xs);font-weight:700;color:var(--ink-2);}
body.rail-off .rail-nav a.on i{color:var(--navy-2);font-weight:700;}
body.rail-off .rail-foot{padding:var(--sp-3) 6px;justify-content:center;gap:var(--sp-2);}
body.rail-off .rail-live{flex:0 0 auto;justify-content:center;}
body.rail-off .rail-theme{margin:0;}

@media(max-width:900px){
  /* 가로 띠일 때는 접을 것이 없다 — 이미 한 줄이다. */
  .rail-fold{display:none!important}
  body.rail-off .rail{width:auto}
  body.rail-off .rail-nav a b{display:block}
  body.rail-off .rail-nav a i{display:none}
}
.rail-wm{display:block;padding:var(--sp-4);text-decoration:none;color:var(--ink);
  border-bottom:1px solid var(--line2);}
/* CI 는 원본 비율(454×250) 그대로 높이로만 재운다. 폭을 지정하면 찌그러진다. */
.rail-ci{display:block;height:22px;width:auto;margin-bottom:var(--sp-3);}
.rail-wm b{display:block;font-size:var(--fs-md);font-weight:800;
  letter-spacing:-.02em;line-height:1.2;}
/* 약칭 바로 아래 풀네임, 그 아래 무엇을 하는지.
   풀네임은 이름의 일부라 약칭에 붙이고, 한글 부제는 한 칸 띄워 역할을 말한다. */
.rail-wm .full{display:block;font-size:var(--fs-2xs);font-weight:400;
  color:var(--ink-3);letter-spacing:0;line-height:1.35;margin-top:2px;}
.rail-wm span{display:block;font-size:var(--fs-2xs);font-weight:400;
  color:var(--ink-2);letter-spacing:0;margin-top:var(--sp-2);}

.rail-nav{flex:1;padding:var(--sp-2) var(--sp-2) var(--sp-4);}

/* 항목은 안쪽으로 물려 놓는다 — 그래야 활성 블록의 라운드가 읽힌다.
   인디케이터는 그 블록 밖, 메뉴 왼쪽 끝에 붙는다. */
/* 그룹 라벨 — '여기부터 관리자' 를 알린다. 비로그인에게는 아래가 안 보여 나타나지 않는다. */
.rail-grp .lk{margin-left:var(--sp-2);font-weight:400;letter-spacing:0;
  text-transform:none;color:var(--ink-3);}
.rail-grp .lk::before{content:"🔒 ";}
.rail-grp{padding:var(--sp-6) var(--sp-3) var(--sp-2);
  font-size:var(--fs-2xs);font-weight:700;letter-spacing:.14em;
  text-transform:uppercase;color:var(--ink-3);
  border-top:1px solid var(--line-2);margin-top:var(--sp-3);}
body.rail-off .rail-grp{padding:var(--sp-3) 0 var(--sp-2);text-align:center;letter-spacing:0;}
body.rail-off .rail-grp{font-size:0;}
body.rail-off .rail-grp::after{content:"관리";font-size:var(--fs-2xs);}
.rail-nav a{position:relative;display:flex;align-items:center;min-height:40px;
  padding:0 var(--sp-3);margin-bottom:2px;border-radius:var(--r-lg);
  text-decoration:none;color:var(--ink);}
.rail-nav a b{display:block;font-size:var(--fs-base);font-weight:500;color:var(--ink);}
.rail-nav a:hover{background:var(--surface-2);}
.rail-nav a:hover b{color:var(--navy-2);}
.rail-nav a.on:hover{background:var(--navy-tint);}
/* 활성 — **연한 바탕 + 왼쪽 굵은 선.** 예전에는 짙은 네이비로 채웠는데,
   링크 자체의 글자색(--ink-2, 진회색)은 그대로여서 <b> 밖의 글씨가
   짙은 바탕에 묻혔다. 사내 시험에서 "음영은 진한데 글자는 회색이라 안 보인다".
   짙게 채우려면 안의 글자를 **전부** 밝게 해야 하는데, 그러면 하위 메뉴까지
   따라가야 한다. 연한 바탕이면 원래 글자색이 그대로 살아 손댈 곳이 없다. */
.rail-nav a.on{background:var(--navy-tint);}
.rail-nav a.on b{color:var(--navy);font-weight:700;}
.rail-nav a.on i{color:var(--navy-2);}

/* 하위 메뉴는 레일에서 없앴다. 8화면 중 3곳에서만 나타나 항목이 2~3줄씩
   늘었다 붙었다 하니, 다른 메뉴를 누를 때마다 아래 것들이 뛰었다.
   화면 안에서 고르는 것은 화면 안 탭으로 옮겼다(Risk·BP 둘 다 같은 모양). */

.rail-foot{padding:var(--sp-3) var(--sp-4)
  calc(var(--sp-3) + env(safe-area-inset-bottom));
  border-top:1px solid var(--line2);
  display:flex;flex-wrap:wrap;align-items:center;gap:var(--sp-2);}
.rail-live{flex:0 0 100%;display:flex;align-items:center;gap:var(--sp-2);
  font-size:var(--fs-2xs);color:var(--ink-3);}
.rail-live .live-dot{width:var(--sp-2);height:var(--sp-2);border-radius:var(--r-md);
  background:var(--ink-3);flex:0 0 auto;font-style:normal;}
.rail-live .live-dot.loading{background:var(--navy-2);}
.rail-live .live-dot.error{background:var(--red);}
/* 신호등 — 초록 이상 없음 · 파랑 볼 것 남음 · 빨강 끊김.
   글자가 같이 가므로 색만으로 가르지 않는다. */
.rail-live .live-dot.ok{background:var(--ok,#2E7D32);}
.rail-live{font-weight:700}
.rail-live:has(.live-dot.ok){color:var(--ok,#2E7D32)}
.rail-live:has(.live-dot.loading){color:var(--navy-2)}
.rail-live:has(.live-dot.error){color:var(--hi)}
.rail-badge{font-size:var(--fs-2xs);font-weight:400;color:var(--ink-3);}
.rail-who{font-size:var(--fs-2xs);color:var(--ink-3);}
.rail-act{font-size:var(--fs-2xs);font-weight:400;color:var(--navy-2);
  text-decoration:none;border-bottom:1px solid var(--navy-line);}
.rail-act:hover{font-weight:700;}
.rail-in{font-weight:700;}
.rail-note{flex:0 0 100%;font-size:var(--fs-2xs);color:var(--ink-3);line-height:1.5;}
.rail-theme{margin-left:auto;background:none;border:0;cursor:pointer;
  color:var(--ink-3);font-size:var(--fs-2xs);padding:0;}
.rail-theme:hover{color:var(--navy-2);}
.rail-own{flex:0 0 100%;font-size:var(--fs-2xs);color:var(--ink-3);
  line-height:1.5;margin-top:var(--sp-1);}
.rail-own a{color:var(--ink-3);}

/* 좁은 화면 — 감추지 않고 가로로 눕힌다. 무엇이 있는지는 계속 보인다. */
/* 좁은 폭 — 좌측 레일이 가로 띠가 된다.
   **한 줄이다.** 예전에는 메뉴·하위항목·관리자 라벨·상태가 각자 줄을 차지해
   내용이 시작되기 전에 세 줄이 지나갔고, 하위항목(오늘·이력)이 상위 메뉴와
   나란히 서서 계층이 섞였다.
   가로에서는 **상위 메뉴만** 보인다 — 하위는 화면 안에서 닿을 수 있고,
   관리자 구분선은 세로 레일의 장치라 한 줄에서는 뜻이 없다. */
@media(max-width:900px){
  .rail{position:static;width:auto;flex-direction:row;flex-wrap:nowrap;
    align-items:center;bottom:auto;overflow:visible;
    border-right:0;border-bottom:1px solid var(--line2);
    gap:0;padding:0 var(--sp-4);min-height:52px;}
  .rail-wm{display:flex;align-items:center;gap:var(--sp-2);flex:0 0 auto;
    padding:0 var(--sp-4) 0 0;border-bottom:0;margin-right:var(--sp-2);
    border-right:1px solid var(--line2);align-self:stretch;}
  .rail-ci{height:17px;margin-bottom:0;}
  .rail-wm .full,.rail-wm span{display:none;}
  .rail-wm b{font-size:var(--fs-sm)}

  .rail-nav{flex:1 1 auto;display:flex;gap:2px;min-width:0;padding:0;
    overflow-x:auto;overscroll-behavior-x:contain;border-top:0;
    scrollbar-width:none;}
  .rail-nav::-webkit-scrollbar{display:none}
  .rail-nav a{padding:8px 12px;white-space:nowrap;border-radius:var(--r-md);}
  .rail-nav a b{font-size:var(--fs-base)}

  /* 하위 항목·관리자 라벨·설명은 가로에서 접는다 — 한 줄을 지키기 위해서다 */
  .rail-grp,.rail-note,.rail-own,.rail-badge{display:none!important}

  .rail-foot{flex:0 0 auto;border-top:0;margin-left:auto;
    padding:0 0 0 var(--sp-3);display:flex;align-items:center;gap:var(--sp-3);}
  .rail-live{margin:0}
  .rail-who{display:none}
}
@media(max-width:560px){
  .rail-wm{display:none}          /* 아주 좁으면 로고도 접는다 */
  .rail-foot .rail-act{font-size:var(--fs-2xs)}
}
</style>

<script>
/* 테마 적용·토글 — 저장 키는 기존 mgscs_theme 그대로 (새 키 금지). 기본 라이트. */
(function(){
  var t = null;
  try { t = localStorage.getItem('mgscs_theme'); } catch(e){}
  if (t === 'dark') document.documentElement.setAttribute('data-theme','dark');
})();
/* 메뉴 접힘 — 테마와 같은 방식으로 저장한다. 화면을 옮겨도 유지되게. */
(function(){
  try { if (localStorage.getItem('mgscs_rail') === 'off')
          document.documentElement.setAttribute('data-rail','off'); } catch(e){}
})();
function mgscsApplyRail(){
  var off = document.documentElement.getAttribute('data-rail') === 'off';
  if (document.body) document.body.classList.toggle('rail-off', off);
}
function mgscsToggleRail(){
  var off = document.documentElement.getAttribute('data-rail') !== 'off';
  document.documentElement.setAttribute('data-rail', off ? 'off' : 'on');
  try { localStorage.setItem('mgscs_rail', off ? 'off' : 'on'); } catch(e){}
  mgscsApplyRail();
}
if (document.readyState === 'loading')
  document.addEventListener('DOMContentLoaded', mgscsApplyRail);
else mgscsApplyRail();

function mgscsToggleTheme(){
  var d = document.documentElement.getAttribute('data-theme') === 'dark';
  if (d) document.documentElement.removeAttribute('data-theme');
  else   document.documentElement.setAttribute('data-theme','dark');
  try { localStorage.setItem('mgscs_theme', d ? 'light' : 'dark'); } catch(e){}
}
/* 접힌 채로 숨긴 조건이 결과를 바꾸면 사람이 속는다 — 배지로 알린다.
   foldSync("narrowBox", 2)  →  "2개 적용 중" + 상자 강조 + 자동 펼침 */
function foldSync(id, count, word){
  var d = document.getElementById(id); if(!d) return;
  var b = d.querySelector('.fold-n');
  if(b) b.textContent = count ? (count + (word || '개 적용 중')) : '';
  d.classList.toggle('on', !!count);
  if(count && !d.open) d.open = true;
}
</script>

