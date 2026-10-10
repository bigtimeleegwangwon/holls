<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="mGSCS">
<meta name="mobile-web-app-capable" content="yes">
<meta name="theme-color" content="#1D3A6E">
<meta name="format-detection" content="telephone=no">
<link rel="apple-touch-icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 180 180'%3E%3Crect width='180' height='180' fill='%231d3a6e'/%3E%3Ctext x='90' y='124' font-size='104' font-family='sans-serif' font-weight='800' fill='white' text-anchor='middle'%3Em%3C/text%3E%3C/svg%3E">
<title>mGSCS · SCM Risk Monitor</title>

<style>
/* ════════════════════════════════════════════════════════════
   mGSCS Design System — Navy / Clean / Minimal
   소재전략 · material Global SCM Control System
   ════════════════════════════════════════════════════════════ */
/* 토큰 정본은 _shared/theme.html — 이 파일은 대시보드 컴포넌트 스타일만 정의한다(신규 hex 금지). */

/* 접근성 최소셋(P3) — 키보드 포커스 가시화 + 모션 감소 존중 */
:where(a,button,select,input,textarea,.btn,.sb-item,.dseg,.kpi,.cat-tab,.link-btn,.scm-analyze-btn,[tabindex]):focus-visible{
  outline:2px solid var(--accent); outline-offset:2px; border-radius:var(--r-md);
}
@media (prefers-reduced-motion:reduce){
  *,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;transition-duration:.001ms!important;scroll-behavior:auto!important;}
}
*{box-sizing:border-box;margin:0;padding:0;}
body{min-height:100vh;background:var(--bg);font-family:var(--font-sans);color:var(--ink);font-size:var(--fs-base);-webkit-font-smoothing:antialiased;}

/* ── Header ── */
#header{background:var(--surface);border-bottom:1px solid var(--line);padding:13px 24px 9px;position:sticky;top:0;z-index:200;box-shadow:0 1px 2px rgba(19,41,75,.04);}
.hd-top{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;}
.brand{display:flex;align-items:center;gap:12px;}
.brand-logo{height:26px;width:auto;flex-shrink:0;display:block;}
.brand-div{width:1px;height:26px;background:var(--line);flex-shrink:0;}
.brand-icon{width:36px;height:36px;border-radius:var(--r-lg);background:var(--navy);display:flex;align-items:center;justify-content:center;font-size:var(--fs-md);font-weight:800;color:var(--on-navy);flex-shrink:0;font-family:var(--font-sans);letter-spacing:-.5px;}
.brand-title{font-size:var(--fs-md);font-weight:800;color:var(--ink);font-family:var(--font-sans);letter-spacing:.2px;display:flex;align-items:center;}
.brand-sub{font-size:var(--fs-xs);color:var(--ink-3);margin-top:2px;}
.hd-right{display:flex;align-items:center;gap:7px;flex-wrap:wrap;}
.status-text{font-size:var(--fs-xs);color:var(--ink-2);text-align:right;}
.status-text.err{color:var(--hi);}
.update-time{font-size:var(--fs-2xs);color:var(--ink-3);text-align:right;margin-top:1px;}

/* ── Buttons — 기본형은 theme.html .btn. 변형은 .btn.* 이중 클래스로 theme 이후 로드에도 우선한다 ── */
.btn{white-space:nowrap;transition:all .15s;}
.btn.btn-red{background:var(--navy);border-color:var(--navy);color:var(--on-navy);} .btn.btn-red:hover:not(:disabled){background:var(--navy-hover);border-color:var(--navy-hover);}
.btn.btn-gray{background:var(--surface-2);color:var(--ink-2);border-color:var(--line);} .btn.btn-gray:hover{background:var(--accent-soft);border-color:var(--accent-line);}
.btn.btn-dark{background:var(--navy);border-color:var(--navy);color:var(--on-navy);} .btn.btn-dark:hover{background:var(--navy-hover);border-color:var(--navy-hover);}
.btn.btn-outline{background:var(--card);color:var(--ink-2);border-color:var(--line);} .btn.btn-outline:hover{background:var(--surface-2);}
.btn.btn-blue{background:var(--navy-2);border-color:var(--navy-2);color:var(--on-navy);} .btn.btn-blue:hover:not(:disabled){background:var(--navy);border-color:var(--navy);}
.btn:disabled{background:var(--disabled-bg);color:var(--disabled-ink);cursor:not-allowed;}

/* ── Source / category bars ── */
#sourceBar{display:flex;gap:4px;margin-top:9px;flex-wrap:wrap;}
.src-badge{font-size:var(--fs-2xs);padding:2px 7px;border-radius:var(--r-sm);border:1px solid var(--line);color:var(--ink-3);background:var(--surface-2);}
.src-badge.ok{border-color:var(--lo-line);color:var(--lo);background:var(--lo-bg);}
.src-badge.err{border-color:var(--hi-line);color:var(--hi);background:var(--hi-bg);}
#catTabs{display:flex;gap:5px;margin-top:9px;flex-wrap:wrap;align-items:center;}
.cat-tab{border-radius:var(--r-lg);padding:4px 12px;font-size:var(--fs-sm);cursor:pointer;border:1px solid var(--line);background:var(--surface-2);color:var(--ink-2);font-weight:600;transition:all .15s;font-family:var(--font-sans);}
.cat-tab:hover{background:var(--accent-soft);border-color:var(--accent-line);}

/* ── Modal ── */
.modal-overlay{display:none;position:fixed;inset:0;background:rgba(15,28,48,.5);z-index:500;align-items:center;justify-content:center;backdrop-filter:blur(2px);}
.modal-overlay.open{display:flex;}
.modal-box{background:var(--surface);border-radius:var(--r-lg);width:700px;max-width:95vw;max-height:90vh;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 24px 70px rgba(15,28,48,.28);}
.modal-head{padding:16px 22px 13px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;}
.modal-head h2{font-size:var(--fs-md);font-weight:800;color:var(--ink);}
.modal-close{background:none;border:none;font-size:var(--fs-xl);cursor:pointer;color:var(--ink-3);line-height:1;}
.modal-close:hover{color:var(--ink-2);}
.modal-body{padding:16px 22px;overflow-y:auto;flex:1;}
.modal-foot{padding:12px 22px;border-top:1px solid var(--line);display:flex;justify-content:flex-end;gap:8px;}

/* ── Keyword modal ── */
.kw-section{margin-bottom:18px;}
.kw-section-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;}
.kw-section-title{font-size:var(--fs-sm);font-weight:700;color:var(--ink-2);display:flex;align-items:center;gap:6px;}
.kw-del-cat{font-size:var(--fs-2xs);color:var(--hi);border:1px solid var(--hi-line);background:var(--hi-bg);border-radius:var(--r-sm);padding:2px 7px;cursor:pointer;font-weight:600;}
.kw-del-cat:hover{background:var(--hi-bg);}
.kw-tags{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:8px;}
.kw-tag{display:flex;align-items:center;gap:3px;padding:3px 8px 3px 10px;border-radius:var(--r-sm);font-size:var(--fs-sm);border:1px solid var(--line);background:var(--surface-2);color:var(--ink-2);}
.kw-tag-del{background:none;border:none;cursor:pointer;color:var(--ink-3);font-size:var(--fs-base);line-height:1;padding:0;}
.kw-tag-del:hover{color:var(--hi);}
.kw-add-row{display:flex;gap:6px;}
.kw-input{flex:1;padding:7px 10px;border:1px solid var(--line);border-radius:var(--r-lg);font-size:var(--fs-sm);font-family:var(--font-sans);outline:none;}
.kw-input:focus{border-color:var(--accent);}
.kw-add-btn{background:var(--navy);border:none;border-radius:var(--r-lg);color:var(--on-navy);padding:7px 13px;font-size:var(--fs-sm);font-weight:700;cursor:pointer;}
.kw-add-btn:hover{background:var(--navy-2);}
.kw-divider{border:none;border-top:1px solid var(--line-2);margin:4px 0;}
.add-cat-box{background:var(--surface-2);border:1px dashed var(--navy-line);border-radius:var(--r-lg);padding:14px 16px;margin-top:6px;}
.add-cat-box h3{font-size:var(--fs-sm);font-weight:700;color:var(--ink-2);margin-bottom:12px;}
.add-cat-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:10px;}
.add-cat-grid label{font-size:var(--fs-xs);color:var(--ink-2);display:flex;flex-direction:column;gap:3px;}
.add-cat-grid input,.add-cat-grid select{padding:6px 9px;border:1px solid var(--line);border-radius:var(--r-md);font-size:var(--fs-sm);font-family:var(--font-sans);outline:none;}
.add-cat-grid input:focus,.add-cat-grid select:focus{border-color:var(--accent);}

/* ── Content / states ── */
#content{max-width:1100px;margin:0 auto;padding:18px 24px;}
.state-screen{text-align:center;padding:70px 20px;}
.state-icon{font-size:40px;margin-bottom:12px;display:inline-block;}
.state-icon.spin{animation:spin 2s linear infinite;}
.state-title{font-size:var(--fs-base);font-weight:700;color:var(--hi);margin-bottom:6px;}
.state-sub{font-size:var(--fs-base);color:var(--ink-2);line-height:1.7;}
.progress-wrap{margin:16px auto 0;width:300px;}
.progress-label{font-size:var(--fs-xs);color:var(--ink-3);margin-bottom:5px;}
.progress-bar{width:100%;height:4px;background:var(--line);border-radius:var(--r-sm);overflow:hidden;}
.progress-fill{height:100%;background:linear-gradient(90deg,var(--navy),var(--accent));border-radius:var(--r-sm);transition:width .4s ease;}

/* ── News cards ── */
#newsList{display:grid;gap:8px;}
.news-card{background:var(--surface);border:1px solid var(--line);border-radius:var(--r-lg);box-shadow:0 1px 2px rgba(26,31,41,.05);padding:13px 16px;display:flex;align-items:flex-start;gap:12px;transition:box-shadow .15s,transform .15s;}
.news-card:hover{box-shadow:var(--shadow);}
.news-card.is-new{animation:flashNew 1s ease;}
.news-icon{width:32px;height:32px;border-radius:var(--r-lg);display:flex;align-items:center;justify-content:center;font-size:var(--fs-md);flex-shrink:0;margin-top:1px;}
.news-body{flex:1;min-width:0;}
.news-tags{display:flex;align-items:center;gap:5px;margin-bottom:5px;flex-wrap:wrap;}
.tag{font-size:var(--fs-2xs);font-weight:700;padding:2px 7px;border-radius:var(--r-sm);font-family:var(--font-sans);}
.src-tag{font-size:var(--fs-2xs);color:var(--ink-2);padding:2px 7px;background:var(--surface-2);border-radius:var(--r-sm);border:1px solid var(--line);}
.time-tag{font-size:var(--fs-2xs);color:var(--ink-3);}
.new-badge{font-size:var(--fs-2xs);padding:2px 7px;border-radius:var(--r-sm);background:var(--lo-bg);color:var(--lo);border:1px solid var(--lo-line);font-weight:700;}
.ai-badge{font-size:var(--fs-2xs);padding:2px 7px;border-radius:var(--r-sm);background:var(--accent-soft);color:var(--accent);border:1px solid var(--accent-line);}
.news-title{font-size:var(--fs-base);font-weight:600;color:var(--ink);line-height:1.5;margin-bottom:3px;}
.news-title-ko{font-size:var(--fs-sm);color:var(--ink-2);line-height:1.4;margin-bottom:5px;}
.news-summary{font-size:var(--fs-sm);color:var(--ink-2);line-height:1.6;padding:6px 10px;background:var(--surface-2);border-radius:var(--r-md);border:1px solid var(--line);}
.sum-label{font-size:var(--fs-2xs);font-weight:700;color:var(--ink-3);margin-bottom:2px;text-transform:uppercase;font-family:var(--font-sans);letter-spacing:.5px;}
.link-btn{background:var(--surface-2);border:1px solid var(--line);border-radius:var(--r-lg);color:var(--ink-2);padding:7px 12px;font-size:var(--fs-xs);font-weight:600;text-decoration:none;flex-shrink:0;white-space:nowrap;transition:all .15s;align-self:center;font-family:var(--font-sans);cursor:pointer;}
.link-btn:hover{background:var(--navy);color:var(--on-navy);border-color:var(--navy);}

/* ── 지난 신호(7일 초과~90일 TTL) — 통계 요약 + 컴팩트 리스트로 축약(무제한 풀카드 파싱 방지) ── */
.news-stale{margin-top:12px;border:1px solid var(--line);border-radius:var(--r-lg);background:var(--surface);padding:0 14px 12px;}
.news-stale>summary{cursor:pointer;list-style:none;padding:11px 2px;font-size:var(--fs-sm);font-weight:700;color:var(--ink-2);display:flex;align-items:center;gap:6px;}
.news-stale>summary::-webkit-details-marker{display:none;}
.news-stale>summary::before{content:"▸";color:var(--ink-3);font-size:var(--fs-2xs);display:inline-block;transition:transform .15s;}
.news-stale[open]>summary::before{transform:rotate(90deg);}
.news-stale-stats{display:flex;flex-wrap:wrap;gap:6px;margin:2px 0 10px;}

/* ── Error guide ── */
.error-guide{margin:18px auto 0;padding:15px 18px;background:var(--surface);border-radius:var(--r-lg);border:1px solid var(--line);text-align:left;max-width:460px;}
.error-guide h4{font-size:var(--fs-sm);font-weight:700;color:var(--ink-2);margin-bottom:7px;}
.error-guide p{font-size:var(--fs-sm);color:var(--ink-2);line-height:1.9;}
.retry-btn{margin-top:14px;background:var(--navy);border:none;border-radius:var(--r-lg);color:var(--on-navy);padding:9px 22px;font-size:var(--fs-base);font-weight:700;cursor:pointer;}
.retry-btn:hover{background:var(--navy-2);}

/* ── Footer / DB bar ── */
#footer{text-align:center;padding:22px;font-size:var(--fs-xs);color:var(--ink-3);font-family:var(--font-sans);letter-spacing:.3px;}
#dbBar{background:var(--surface);border-bottom:1px solid var(--line);padding:6px 24px;font-size:var(--fs-xs);color:var(--ink-2);display:flex;align-items:center;gap:12px;}
#dbBar strong{color:var(--navy);}
#dbBar span{color:var(--ink-3);}

/* ── API key modal ── */
.apikey-desc{font-size:var(--fs-base);color:var(--ink-2);margin-bottom:14px;line-height:1.7;}
.apikey-label{font-size:var(--fs-sm);color:var(--ink-2);font-weight:700;display:block;margin-bottom:6px;}
.apikey-input{width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:var(--r-lg);font-size:var(--fs-base);font-family:var(--font-sans);outline:none;}
.apikey-input:focus{border-color:var(--accent);}
.apikey-hint{font-size:var(--fs-xs);color:var(--ink-3);margin-top:8px;}
.apikey-hint a{color:var(--accent);text-decoration:none;}
.apikey-status{font-size:var(--fs-sm);margin-top:10px;padding:8px 12px;border-radius:var(--r-lg);display:none;}
.apikey-status.ok{background:var(--lo-bg);color:var(--lo);border:1px solid var(--lo-line);display:block;}
.apikey-status.err{background:var(--hi-bg);color:var(--hi);border:1px solid var(--hi-line);display:block;}

@keyframes blink{0%,100%{opacity:1}50%{opacity:.35}}
@keyframes spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}
@keyframes flashNew{0%{background:var(--accent-soft)}100%{background:var(--surface)}}

/* ── SCM (공급망 영향) 섹션 ── */
#scmSection{max-width:1100px;margin:0 auto;padding:16px 24px 0;}
.scm-header{display:flex;align-items:center;gap:8px 20px;margin-bottom:9px;flex-wrap:wrap;}
.scm-header-btns{margin-left:auto}
.scm-title{font-size:var(--fs-md);font-weight:800;color:var(--ink);display:flex;align-items:center;gap:8px;}
.scm-title-badge{font-size:var(--fs-2xs);background:var(--navy);color:var(--on-navy);border-radius:var(--r-sm);padding:2px 8px;font-weight:700;letter-spacing:.5px;}
.scm-header-btns{display:flex;gap:6px;flex-wrap:wrap;}
.scm-analyze-btn{background:var(--navy);border:none;border-radius:var(--r-lg);color:var(--on-navy);padding:6px 13px;font-size:var(--fs-xs);font-weight:700;cursor:pointer;font-family:var(--font-sans);}
.scm-analyze-btn:hover{background:var(--navy-2);}
.scm-analyze-btn:disabled{background:var(--disabled-bg);color:var(--disabled-ink);cursor:not-allowed;}
.scm-list{display:flex;flex-direction:column;gap:5px;margin-bottom:4px;}
.scm-row{display:flex;align-items:center;gap:8px;background:var(--surface);border:1px solid var(--line);border-radius:var(--r-lg);box-shadow:0 1px 2px rgba(26,31,41,.05);padding:8px 13px;transition:box-shadow .15s;}
.scm-row:hover{box-shadow:var(--shadow);}
.scm-row.level-high{border-color:var(--hi-line);background:var(--hi-bg);}
.scm-row.level-medium{border-color:var(--md-line);background:var(--md-bg);}
.scm-row.level-low{border-color:var(--lo-line);}
.scm-level-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}
.scm-level-dot.high{background:var(--hi);}
.scm-level-dot.medium{background:var(--md);}
.scm-level-dot.low{background:var(--lo);}
.scm-row-title{font-size:var(--fs-sm);font-weight:700;color:var(--ink);flex:1;min-width:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.scm-row-region{font-size:var(--fs-xs);color:var(--ink-3);flex-shrink:0;white-space:nowrap;}
.scm-mats{display:flex;flex-wrap:wrap;gap:3px;flex-shrink:0;}
.scm-mat{font-size:var(--fs-2xs);padding:1px 6px;border-radius:var(--r-sm);background:var(--accent-soft);color:var(--accent);border:1px solid var(--accent-line);font-weight:600;white-space:nowrap;}
.scm-bp-list{display:flex;flex-wrap:wrap;align-items:center;gap:4px;margin-top:8px;padding-top:8px;border-top:1px dashed var(--line);}
.scm-bp-lead{font-size:var(--fs-2xs);font-weight:700;color:var(--ink-2);margin-right:2px;}
.scm-bp{font-size:var(--fs-2xs);padding:1px 7px;border-radius:var(--r-sm);background:var(--surface-2);color:var(--ink);border:1px solid var(--line);font-weight:600;white-space:nowrap;}
.scm-bp-more{font-size:var(--fs-2xs);color:var(--ink-3);font-weight:600;}
/* 지진 근접 BP 패널 */
.quake-panel{margin-bottom:12px;border:1px solid var(--hi-line);border-radius:var(--r-lg);background:var(--hi-bg);padding:10px 12px;}
.quake-head{font-size:var(--fs-sm);font-weight:800;color:var(--navy);margin-bottom:8px;}
.quake-head .quake-sub{font-size:var(--fs-2xs);font-weight:500;color:var(--ink-3);margin-left:6px;}
.quake-row{display:flex;flex-direction:column;gap:4px;padding:7px 0;border-top:1px dashed var(--line);}
.quake-row:first-of-type{border-top:none;}
.quake-eq{display:flex;align-items:center;gap:8px;}
.quake-mag{font-size:var(--fs-xs);font-weight:800;color:var(--on-navy);border-radius:var(--r-sm);padding:1px 7px;}
.quake-mag.lvl-urgent{background:var(--hi);} .quake-mag.lvl-monitor{background:var(--md);} .quake-mag.lvl-minor{background:var(--ink-3);}
.quake-place{font-size:var(--fs-sm);font-weight:600;color:var(--ink);flex:1;}
.quake-bps{display:flex;flex-wrap:wrap;gap:4px;}
.quake-bp{font-size:var(--fs-2xs);padding:1px 7px;border-radius:var(--r-sm);font-weight:600;white-space:nowrap;border:1px solid var(--line);background:var(--surface-2);color:var(--ink);}
.quake-bp i{font-style:normal;font-weight:500;color:var(--ink-2);}
.quake-bp.lvl-urgent{background:var(--hi-bg);border-color:var(--hi-line);color:var(--hi);}
.quake-bp.lvl-monitor{background:var(--md-bg);border-color:var(--md-line);color:var(--md);}
.quake-bp.lvl-minor{background:var(--surface-2);border-color:var(--line);color:var(--ink-2);}
.scm-row-link{font-size:var(--fs-2xs);color:var(--accent);text-decoration:none;flex-shrink:0;}
.scm-row-link:hover{text-decoration:underline;}
.scm-empty{padding:14px 0;color:var(--ink-3);font-size:var(--fs-sm);}
.scm-loading{padding:14px 0;color:var(--ink-2);font-size:var(--fs-sm);}
/* triage: 점검 발송 후보(review) 강조 / 모니터링(monitor) 차분 */
.scm-ok{padding:12px 14px;background:var(--lo-bg);border:1px solid var(--lo-line);border-radius:var(--r-lg);color:var(--lo);font-size:var(--fs-sm);font-weight:700;margin-bottom:6px;}
.scm-sub{font-size:var(--fs-2xs);font-weight:700;letter-spacing:.3px;color:var(--ink-3);text-transform:none;margin:12px 0 6px;}
.scm-fresh{font-size:var(--fs-xs);color:var(--ink-3);font-weight:600;margin-bottom:9px;padding:5px 10px;background:var(--surface-2);border:1px solid var(--line);border-radius:var(--r-lg);display:inline-block;}
.scm-fresh.stale{background:var(--hi-bg);border-color:var(--hi-line);color:var(--hi);}
.scm-fresh b{font-weight:800;}
/* P1-4 열면 결론 — 대시보드 최상단 역피라미드 결론 배너 */
.verdict-slot{margin:0;padding:14px 24px 0;}   /* 결론이 숫자보다 위 — 첫 화면 최상단 슬롯(P1) */
.verdict-slot:empty{display:none;}
@media (max-width:640px){.verdict-slot{padding:12px 14px 0;}}
.scm-conclusion{border-radius:var(--r-lg);padding:14px 16px;margin-bottom:12px;border:1px solid var(--line);}
.scm-conclusion.concl-hi{background:var(--hi-bg);border-color:var(--hi-line);}
.scm-conclusion.concl-md{background:var(--md-bg);border-color:var(--md-line);}
.scm-conclusion.concl-ok{background:var(--lo-bg);border-color:var(--lo-line);}
.concl-line{display:flex;align-items:baseline;gap:9px;flex-wrap:wrap;}
.concl-icon{font-size:var(--fs-md);}
.concl-label{font-size:var(--fs-xs);font-weight:800;letter-spacing:.4px;color:var(--ink-3);text-transform:uppercase;}
.concl-verdict{font-size:var(--fs-md);font-weight:800;color:var(--ink);}
.concl-icon{font-size:1.05em;line-height:1}
.concl-hi .concl-icon{color:var(--hi)}
.concl-md .concl-icon{color:var(--md)}
.concl-ok .concl-icon{color:var(--ink-3)}
.concl-hi .concl-verdict{color:var(--hi);}
.concl-md .concl-verdict{color:var(--md);}
.concl-ok .concl-verdict{color:var(--lo);}
.concl-verdict b{font-weight:900;}
.concl-top{margin-top:7px;font-size:var(--fs-sm);color:var(--ink-2);line-height:1.5;}
.concl-top b{font-weight:700;color:var(--ink);}
.concl-top-reg{display:inline-block;font-size:var(--fs-2xs);font-weight:700;color:var(--ink-3);padding:1px 7px;border-radius:var(--r-sm);background:rgba(0,0,0,.05);margin:0 4px;}
/* 결론 신선도·출처 명시 — 배너 하단 메타 라인 */
.concl-meta{margin-top:9px;padding-top:8px;border-top:1px solid rgba(0,0,0,.07);font-size:var(--fs-xs);color:var(--ink-3);font-weight:600;line-height:1.5;}
.concl-src{display:inline-block;font-size:var(--fs-2xs);font-weight:800;letter-spacing:.3px;padding:1px 7px;border-radius:var(--r-sm);background:var(--accent-soft);color:var(--accent);margin-right:4px;}
.concl-src-server{background:var(--surface-2);color:var(--ink-2);}
.concl-src-none{background:var(--md-bg);color:var(--md);}
.concl-meta b.concl-stale{color:var(--hi);font-weight:800;}
/* 점검 대상은 줄이다. 이 구역에 있다는 것 자체가 이미 "봐야 할 것"이므로,
   줄마다 붉은 테·붉은 뱃지·붉은 띠를 덧대면 급한 것과 덜 급한 것이 안 갈린다.
   색은 심각도 점 하나에만 남긴다. */
.scm-n{font-size:var(--fs-sm);font-weight:800;color:var(--hi);margin-left:7px}
.scm-r{border-bottom:1px solid var(--line);}
.scm-r:last-of-type{border-bottom:0}
.scm-r>summary{list-style:none;cursor:pointer;display:flex;align-items:center;gap:9px;
  padding:9px 4px;font-size:var(--fs-sm);transition:background .12s;border-radius:var(--r-sm);}
.scm-r>summary::-webkit-details-marker{display:none}
.scm-r>summary:hover{background:var(--surface-2)}
.scm-r>summary::after{content:"▸";color:var(--ink-3);font-size:11px;margin-left:2px;transition:transform .15s}
.scm-r[open]>summary::after{transform:rotate(90deg)}
.scm-r[open]>summary{background:var(--surface-2)}
.scm-r-dot{width:7px;height:7px;border-radius:50%;flex:0 0 auto;background:var(--ink-3)}
.scm-r.lv-high .scm-r-dot{background:var(--hi)}
.scm-r.lv-medium .scm-r-dot{background:var(--rk-3)}
.scm-r-title{flex:1 1 auto;min-width:0;font-weight:700;color:var(--ink);
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.scm-r-reg{flex:0 0 auto;font-size:var(--fs-xs);color:var(--ink-3)}
/* BP 수는 오른쪽에 세로로 줄 세운다 — 여덟 줄을 훑으려면 숫자가 한 열에 있어야 한다.
   너무 멀어지는 것은 본문 폭 상한(--content-max)이 막는다. */
.scm-r-mats{flex:0 0 auto;display:flex;gap:3px}
.scm-r-bp{flex:0 0 auto;font-size:var(--fs-xs);color:var(--ink-2);font-weight:700;
  font-variant-numeric:tabular-nums;min-width:34px;text-align:right}
.scm-r-body{padding:4px 4px 13px 20px}
.scm-r-act{display:flex;align-items:center;justify-content:space-between;gap:10px;
  flex-wrap:wrap;font-size:var(--fs-sm);color:var(--ink-2);
  background:var(--surface-2);border:1px solid var(--line);
  border-radius:var(--r-lg);padding:7px 11px;margin-top:7px}
@media(max-width:760px){
  .scm-r-mats,.scm-r-reg{display:none}
}
.scm-review-title{font-size:var(--fs-base);font-weight:800;color:var(--ink);margin-bottom:3px;}
.scm-review-sum{font-size:var(--fs-sm);color:var(--ink-2);margin-bottom:7px;}
.scm-review-meta{display:flex;align-items:center;gap:6px;flex-wrap:wrap;margin-bottom:6px;}
.scm-review-why{font-size:var(--fs-xs);color:var(--ink-2);background:var(--surface-2);border:1px solid var(--line);border-radius:var(--r-md);padding:6px 9px;margin-bottom:7px;line-height:1.6;}
/* 긴급 점검 메일 버튼 + 회신 보드 */
.scm-mail-btn{display:inline-block;margin-left:8px;font-size:var(--fs-xs);font-weight:800;color:var(--on-navy);background:var(--hi);border:none;border-radius:var(--r-md);padding:4px 10px;cursor:pointer;}
.scm-mail-btn:hover{filter:brightness(1.08);}
.insp-note{font-size:var(--fs-xs);color:var(--ink-2);background:var(--surface-2);border:1px solid var(--line);border-radius:var(--r-lg);padding:9px 11px;line-height:1.6;margin-bottom:12px;}
.insp-board{margin-top:12px;}
.insp-summary{font-size:var(--fs-sm);font-weight:700;color:var(--ink-2);margin-bottom:8px;}
.insp-table{width:100%;border-collapse:collapse;font-size:var(--fs-sm);}
.insp-table th{text-align:left;padding:6px 8px;background:var(--surface-2);color:var(--ink-2);font-weight:800;font-size:var(--fs-xs);border-bottom:1px solid var(--line);}
.insp-table td{padding:6px 8px;border-bottom:1px solid var(--line-2);vertical-align:top;color:var(--ink-2);}
.insp-table td.ic{text-align:center;font-weight:900;width:64px;}
.insp-table td.note{color:var(--ink-3);font-size:var(--fs-xs);}
.insp-table tr.insp-alert{background:var(--hi-bg);}
.insp-table tr.insp-warn{background:var(--md-bg);}
.insp-o{color:var(--lo);}.insp-t{color:var(--md);}.insp-x{color:var(--hi);}.insp-u{color:var(--ink-3);}
.insp-del{border:none;background:transparent;color:var(--ink-3);font-size:var(--fs-md);cursor:pointer;line-height:1;}
.insp-del:hover{color:var(--hi);}
.insp-legacy{display:inline-block;font-size:var(--fs-2xs);font-weight:800;color:var(--md);background:var(--md-bg);border:1px solid var(--md-line);border-radius:var(--r-lg);padding:0 5px;vertical-align:middle;}
.insp-campaign{background:var(--surface-2);border:1px solid var(--line);border-radius:var(--r-lg);padding:12px 14px;margin-bottom:14px;}
.insp-campaign-head{display:flex;flex-wrap:wrap;align-items:baseline;justify-content:space-between;gap:6px;margin-bottom:8px;}
.insp-campaign-title{font-size:var(--fs-base);font-weight:800;color:var(--ink);}
.insp-campaign-meta{font-size:var(--fs-xs);color:var(--ink-3);}
.insp-gauge-wrap{margin-bottom:8px;}
.insp-gauge-label{font-size:var(--fs-xs);color:var(--ink-2);margin-bottom:4px;font-weight:700;}
.insp-gauge-bar{width:100%;height:8px;background:var(--line);border-radius:var(--r-sm);overflow:hidden;}
.insp-gauge-fill{height:100%;background:linear-gradient(90deg,var(--navy),var(--accent));border-radius:var(--r-sm);transition:width .4s ease;}
.insp-unresp{display:flex;flex-wrap:wrap;align-items:center;gap:4px;padding-top:8px;border-top:1px dashed var(--line);}
.insp-unresp-lead{font-size:var(--fs-2xs);font-weight:700;color:var(--hi);margin-right:2px;}
.insp-target-chip{font-size:var(--fs-2xs);padding:1px 7px;border-radius:var(--r-sm);background:var(--hi-bg);color:var(--hi);border:1px solid var(--hi-line);font-weight:600;white-space:nowrap;}
.insp-unresp-done{font-size:var(--fs-xs);color:var(--lo);font-weight:700;padding-top:8px;border-top:1px dashed var(--line);}
.insp-campaign-actions{display:flex;gap:8px;margin-top:10px;flex-wrap:wrap;}
.insp-campaign-actions .btn{padding:5px 12px;font-size:var(--fs-xs);}
.scm-tag-monitor{font-size:var(--fs-2xs);font-weight:700;color:var(--ink-3);background:var(--surface-2);border:1px solid var(--line);border-radius:var(--r-sm);padding:1px 7px;flex-shrink:0;}
.conf-badge{font-size:var(--fs-2xs);font-weight:700;padding:2px 7px;border-radius:var(--r-sm);}
.conf-official{color:var(--navy);background:var(--accent-soft);border:1px solid var(--accent-line);}
.conf-consensus{color:var(--lo);background:var(--lo-bg);border:1px solid var(--lo-line);}
.conf-estimate{color:var(--md);background:var(--md-bg);border:1px solid var(--md-line);}
.corr-badge{font-size:var(--fs-2xs);font-weight:600;padding:2px 6px;border-radius:var(--r-sm);background:var(--accent-soft);color:var(--accent);border:1px solid var(--accent-line);}
.scm-divider{border:none;border-top:1px solid var(--line);margin:16px 0 0;}

/* ── 보고서 모달 ── */
.report-preview{background:var(--surface);border:1px solid var(--line);border-radius:var(--r-lg);padding:0;overflow:hidden;}
.report-preview iframe{width:100%;height:55vh;border:none;}
.report-actions{display:flex;gap:8px;margin-top:12px;flex-wrap:wrap;align-items:center;}
.report-actions .info{font-size:var(--fs-xs);color:var(--ink-3);flex:1;}

/* ════════════════════════════════════════════════════════════
   APP SHELL — 좌측 사이드바 + 메인 (모니터링 콘솔 레이아웃)
   ════════════════════════════════════════════════════════════ */
.tnum,.kpi-num,.sb-badge,.conf-badge,.corr-badge,.update-time,.ring span,#dbBar strong{font-variant-numeric:tabular-nums;font-feature-settings:"tnum" 1;}
/* 좌측 메뉴는 공통 부품(_shared/nav.html)이 맡는다 — 여기는 본문만. */
#app{display:block;min-height:100vh;}

/* ── 사이드바 ── */
.hint-sub{font-size:var(--fs-2xs);color:var(--ink-3);margin-left:6px;}

/* ── 시스템 개요 (Overview 뷰) ── */
/* 드릴다운 아코디언 */

/* ── 이력 (재난 주제별) ── */
.arc-topic{border:1px solid var(--line);border-radius:var(--r-sm);margin-bottom:6px;
  background:var(--card);overflow:hidden;}
.arc-topic>summary{display:flex;align-items:center;gap:9px;padding:9px 12px;
  cursor:pointer;list-style:none;font-size:var(--fs-sm);}
.arc-topic>summary::-webkit-details-marker{display:none}
.arc-topic>summary::after{content:"▾";margin-left:auto;color:var(--ink-3);
  font-size:var(--fs-xs);transition:transform .15s}
.arc-topic[open]>summary::after{transform:rotate(180deg)}
.arc-topic>summary:hover{background:var(--surface-2)}
.arc-topic[open]>summary{border-bottom:1px solid var(--line)}
.arc-topic-t{font-weight:700;color:var(--ink);flex:1 1 12rem;min-width:0;
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.arc-place{font-size:var(--fs-xs);color:var(--ink-2);background:var(--surface-2);
  padding:1px 7px;border-radius:var(--r-sm);white-space:nowrap;}
.arc-weight{font-size:var(--fs-2xs);color:var(--navy-2);font-weight:700;white-space:nowrap;}
.arc-n{font-size:var(--fs-2xs);color:var(--ink-3);white-space:nowrap;
  font-variant-numeric:tabular-nums;}
.arc-srcs{display:flex;flex-direction:column;padding:4px 12px 9px;}
.arc-srcs .arc-link{display:flex;gap:9px;align-items:baseline;padding:5px 0;
  font-size:var(--fs-xs);color:var(--ink-2);text-decoration:none;
  border-bottom:1px solid var(--line-soft,var(--line));}
.arc-srcs .arc-link:last-child{border-bottom:0}
.arc-srcs .arc-link:hover{color:var(--navy-2)}
.arc-srcs .arc-src{flex:0 0 6.5rem;color:var(--ink-3);font-size:var(--fs-2xs);}
.arc-stat.dim b{color:var(--ink-3)}
@media (max-width:640px){
  .arc-topic>summary{flex-wrap:wrap}
  .arc-topic-t{flex:1 0 100%;white-space:normal}
  .arc-srcs .arc-src{flex:0 0 100%}
}

/* ── 누적 보관 (아카이브 뷰) ── */
.arc-head{display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;margin-bottom:13px;}
.arc-title{font-size:var(--fs-md);font-weight:800;color:var(--ink);}
.arc-sub{font-size:var(--fs-xs);font-weight:500;color:var(--ink-3);margin-left:8px;}
.arc-actions{display:flex;gap:6px;flex-wrap:wrap;}
.arc-stats{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px;}
/* 범주 칩은 지표와 줄을 나눈다 — 건수가 바뀌어도 자리가 안 흔들린다. */
.arc-chips{display:flex;flex-wrap:wrap;gap:6px;margin:8px 0 0}
/* 원장 — 90일로 잘리지 않고 계속 쌓이는 수. 다른 지표와 성격이 달라 표시를 가른다. */
.arc-stat.led{border-color:var(--navy-line);background:var(--accent-soft)}
.arc-stat.led b{color:var(--navy)}
/* 캐시가 줄었거나 못 썼을 때 — 조용히 얼어붙지 않게 그 자리에 적는다. */
/* 오류 번호 — 문장은 옮겨 적다 바뀌지만 네 자리 숫자는 안 바뀐다.
   사내에서 타이핑으로 전달해야 하므로 눈에 띄어야 한다. */
.errno{font-variant-numeric:tabular-nums;font-weight:800;letter-spacing:.02em}
.arc-cache{margin:8px 0 0;padding:7px 10px;border-radius:var(--r-md);
  border:1px solid var(--navy-line);background:var(--accent-soft);
  font-size:var(--fs-xs);color:var(--ink-2)}
/* 서버와 맞춘 시각. 단추가 아니라 **상태**다 — 자동으로 도는데 큰 단추를 두면
   "눌러야 맞춰지나" 로 읽힌다. 눌러서 지금 맞출 수는 있다. */
.arc-sync{font:inherit;font-size:var(--fs-2xs);color:var(--ink-3);cursor:pointer;
  background:none;border:0;padding:4px 2px;display:inline-flex;align-items:center;gap:5px}
.arc-sync::before{content:"";width:6px;height:6px;border-radius:50%;background:var(--ok,#2E7D32)}
.arc-sync:hover{color:var(--ink-2)}
.arc-sync.err{color:var(--hi)}
.arc-sync.err::before{background:var(--hi)}
/* 되돌릴 수 없는 단추는 한 칸 떼어 놓는다 — 내보내기 옆에 붙어 있으면 잘못 누른다. */
.arc-sep{width:1px;align-self:stretch;background:var(--line2);margin:2px 6px}
.arc-stat{background:var(--surface);border:1px solid var(--line);border-radius:var(--r-lg);padding:8px 15px;text-align:center;min-width:74px;}
.arc-stat b{display:block;font-size:var(--fs-lg);font-weight:800;color:var(--navy);font-variant-numeric:tabular-nums;line-height:1.1;}
.arc-stat span{font-size:var(--fs-2xs);color:var(--ink-3);}
.arc-chip{background:var(--surface-2);border:1px solid var(--line);border-radius:var(--r-sm);padding:0 12px;font-size:var(--fs-xs);color:var(--ink-2);display:flex;align-items:center;gap:6px;}
.arc-chip b{color:var(--navy);font-variant-numeric:tabular-nums;}
.arc-controls{display:flex;gap:8px;margin-bottom:10px;flex-wrap:wrap;}
.arc-input{padding:8px 11px;border:1px solid var(--line);border-radius:var(--r-lg);font-size:var(--fs-sm);font-family:inherit;outline:none;background:var(--surface);color:var(--ink);}
.arc-input:focus{border-color:var(--accent);}
#arcSearch{flex:1;min-width:180px;}
.arc-list{display:flex;flex-direction:column;border:1px solid var(--line);border-radius:var(--r-lg);overflow:hidden;background:var(--surface);}
.arc-row{display:flex;align-items:center;gap:10px;padding:8px 13px;border-bottom:1px solid var(--line-2);font-size:var(--fs-sm);}
.arc-row:last-child{border-bottom:none;}
.arc-row:hover{background:var(--surface-2);}
.arc-date{color:var(--ink-3);font-size:var(--fs-xs);white-space:nowrap;flex-shrink:0;width:90px;}
.arc-cat{font-size:var(--fs-2xs);font-weight:700;border:1px solid;border-radius:var(--r-sm);padding:1px 7px;white-space:nowrap;flex-shrink:0;}
.arc-link{flex:1;min-width:0;color:var(--ink);text-decoration:none;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:600;}
.arc-link:hover{color:var(--accent);text-decoration:underline;}
.arc-src{font-size:var(--fs-xs);color:var(--ink-3);white-space:nowrap;flex-shrink:0;max-width:130px;overflow:hidden;text-overflow:ellipsis;}
.arc-conf{font-size:var(--fs-2xs);font-weight:700;color:var(--navy);background:var(--accent-soft);border:1px solid var(--accent-line);border-radius:var(--r-sm);padding:1px 6px;flex-shrink:0;}
.arc-empty{padding:26px 14px;text-align:center;color:var(--ink-3);font-size:var(--fs-sm);line-height:1.7;}
.arc-note{font-size:var(--fs-xs);color:var(--ink-3);line-height:1.7;margin-top:12px;background:var(--surface-2);border:1px solid var(--line);box-shadow:0 1px 2px rgba(26,31,41,.05);border-radius:var(--r-lg);padding:11px 14px;}
@media (max-width:640px){ .arc-src{display:none;} .arc-date{width:62px;font-size:var(--fs-2xs);} }

/* ── 메인 ── */
#main{min-width:0;display:flex;flex-direction:column;background:var(--bg);}
#topbar{position:sticky;top:0;z-index:120;background:var(--surface);border-bottom:1px solid var(--line);padding:13px 24px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;}
.tb-left{min-width:0;display:flex;align-items:center;gap:11px;}
.tb-title{font-size:var(--fs-md);font-weight:800;color:var(--ink);letter-spacing:-.2px;}
.tb-right{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
.tb-prog{display:flex;align-items:center;gap:9px;}
.prog-lbl{font-size:var(--fs-xs);color:var(--ink-3);}
.ring{--p:0;position:relative;width:38px;height:38px;border-radius:50%;background:conic-gradient(var(--accent) calc(var(--p)*1%),var(--line) 0);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.ring::before{content:"";position:absolute;width:30px;height:30px;border-radius:50%;background:var(--surface);}
.ring span{position:relative;font-size:var(--fs-2xs);font-weight:800;color:var(--accent);font-variant-numeric:tabular-nums;}

/* ── KPI 스트립 ── */
.kpi{background:var(--surface);padding:14px 18px;}
.kpi-num{font-size:var(--fs-xl);font-weight:800;color:var(--navy);line-height:1.05;letter-spacing:-.5px;}
.kpi-den{font-size:var(--fs-base);color:var(--ink-3);font-weight:700;}
.kpi-lbl{font-size:var(--fs-2xs);color:var(--ink-3);margin-top:3px;letter-spacing:.2px;}
.kpi.hi .kpi-num{color:var(--hi);} .kpi.md .kpi-num{color:var(--md);} .kpi.lo .kpi-num{color:var(--lo);}
.kpi.review .kpi-num{color:var(--ink-3);}
.kpi-link{text-decoration:none;color:inherit;}
.kpi-link:hover{background:var(--accent-soft);} .kpi.review.active{background:var(--hi-bg);} .kpi.review.active .kpi-num{color:var(--hi);}

/* .kpi-strip 은 이제 스크립트 훅일 뿐이다 — 레이아웃은 .rk(4단계 상자) 가 정한다.
   서버 저장 줄은 그 상자의 마지막 칸(.rk-io)으로 들어갔다. */
/* 감지 4단계는 **네 칸이 한 줄**이어야 한다 — 좁아지는 모양이 곧 뜻이다.
   3열이면 마지막 칸이 다음 줄로 떨어져 빈 자리가 생기고, 그러면
   "수집 → 재난 → 연관 → 점검 대상" 이라는 흐름이 눈에 안 들어온다. */
#kpiStrip .actbar{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--line);
        border-top:1px solid var(--line);border-bottom:1px solid var(--line);}
#kpiStrip .actbar .kpi{background:var(--surface);padding:15px 20px;
        display:flex;flex-direction:column;justify-content:flex-end;min-height:78px;}
#kpiStrip .actbar .kpi-num{font-size:var(--fs-kpi);}
/* 뒤로 갈수록 옅게 — 좁혀진 결과라는 것을 색이 거든다 */
#kpiStrip .actbar .kpi:nth-child(1) .kpi-num{color:var(--ink-2);}
@media (max-width:900px){ #kpiStrip .actbar{grid-template-columns:repeat(2,1fr);} }
/* 배관 줄 — 4단계 상자의 마지막 칸이다. 위와 가는 선으로만 가른다. */
.rk-io{display:flex;flex-wrap:wrap;align-items:center;gap:2px 18px;
       margin-top:11px;padding-top:10px;border-top:1px solid var(--line);
       font-size:var(--fs-xs);color:var(--ink-3);}
.rk-io .kpi{background:none;padding:0;display:inline-flex;align-items:baseline;gap:5px;}
.rk-io .kpi-num{font-size:var(--fs-xs);font-weight:700;color:var(--ink-2);}
.rk-io .kpi-lbl{margin-top:0;font-size:var(--fs-xs);color:var(--ink-3);letter-spacing:0;}
.rk-io .kpi-den{font-size:var(--fs-xs);font-weight:600;color:var(--ink-3);}
.rk-io .src{margin-left:auto;color:var(--ink-3);}
/* 서버 저장 표시 — 여기가 파이프라인의 입구다. 안 올라가고 있으면 판정도 없다.
   클래스만 있고 스타일이 없어서, "로그인하면 서버에 쌓입니다" 가 회색 글씨로
   묻혀 있었다. 안 되고 있을 때가 눈에 띄어야 한다. */
.rk-io .srv-save{font-size:var(--fs-xs);padding:2px 8px;border-radius:var(--r-sm);
  border:1px solid var(--warn-line,var(--line));background:var(--warn-bg,var(--surface-2));
  color:var(--warn,var(--ink-2));white-space:nowrap;font-weight:600;}
.rk-io .srv-save.on{border-color:var(--ok-line,var(--line));
  background:var(--ok-bg,var(--surface-2));color:var(--ok,var(--ink-3));font-weight:400;}
.rk-io .srv-save a{color:inherit;text-decoration:underline;}
.rk-io #dbBar{background:none;border:0;padding:0;font-size:var(--fs-xs);
        color:var(--ink-3);display:inline-flex;align-items:baseline;gap:5px;}
@media (max-width:640px){
  #kpiStrip .actbar .kpi{padding:12px 14px;}
  .rk-io,#kpiStrip .actbar .kpi{padding-left:14px;padding-right:14px;}
  .rk-io .src{margin-left:0;flex:1 0 100%;}
}

/* ── 이력 바 / 본문 패딩 ── */
#dbBar{background:var(--surface-2);border-bottom:1px solid var(--line);padding:7px 24px;}
.main-pad{padding:18px 24px 8px;flex:1;}
/* ── 본문 폭 상한 ────────────────────────────────────────────────
   끝까지 늘리면 같이 읽어야 할 것이 멀어진다. 4단계 네 숫자는 한 문장이고,
   점검 줄은 제목과 BP 수를 한 번에 봐야 한다.
   탑티어도 상한을 둔다 — Carbon 그리드 기본이 1584px 이다. 그건 여러 열을
   까는 전제이고, 이 화면은 한 열이라 더 좁게 잡는다. */
/* 폭 토큰은 theme.html 의 --page-w 하나를 쓴다 */
/* 상단바·탭·참고줄은 **배경과 밑줄이 끝까지** 가야 화면을 가로지르는 선이 산다.
   그래서 상자를 줄이는 대신 안쪽 여백을 키워 내용만 상한 안에 세운다. */
#topbar, .vtabs{
  padding-inline:max(var(--sp-6), calc((100% - var(--page-w)) / 2 + var(--sp-6)));}
#verdictSlot, .main-pad, #kpiStrip{
  width:100%;max-width:var(--page-w);margin-inline:auto;}
#scmSection{max-width:none;margin:0 0 20px;padding:0;}
#content{max-width:none;margin:0;padding:0;}
.view-block{display:block;}
/* 재난·BP 세계지도 */
.map-wrap{background:var(--surface);border:1px solid var(--line);border-radius:var(--r-lg);padding:14px 16px;box-shadow:var(--shadow);}
.map-head{display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap;margin-bottom:8px;}
.map-title{font-size:var(--fs-md);font-weight:800;color:var(--ink);}
.map-title span{font-size:var(--fs-2xs);font-weight:500;color:var(--ink-3);}
.map-toggle{display:inline-flex;border:1px solid var(--line);border-radius:var(--r-sm);overflow:hidden;}
.map-toggle button{background:var(--surface);color:var(--ink-2);border:none;padding:4px 11px;font-size:var(--fs-xs);cursor:pointer;font-family:var(--font-sans);line-height:1.4;}
.map-toggle button+button{border-left:1px solid var(--line);}
.map-toggle button.on{background:var(--navy);color:var(--on-navy);}
.vtabs{display:flex;gap:2px;margin:0 0 16px;border-bottom:1px solid var(--line)}
.vtabs button{padding:9px 16px;border:1px solid transparent;border-bottom:none;
  background:none;font:inherit;font-size:var(--fs-base);font-weight:700;
  color:var(--ink-3);cursor:pointer;border-radius:var(--r-md) var(--r-md) 0 0;
  margin-bottom:-1px}
.vtabs button:hover{color:var(--navy)}
.vtabs button.on{background:var(--card);border-color:var(--line);
  border-bottom:1px solid var(--card);color:var(--navy)}
.vtabs .n{margin-left:6px;font-size:var(--fs-2xs);font-weight:700;color:var(--ink-3);
  font-variant-numeric:tabular-nums}
.map-svg{display:block;height:clamp(200px,27vh,300px);width:100%;background:var(--surface-2);border:1px solid var(--line-2);border-radius:var(--r-sm);}
/* ── 권역 패널 — 사건이 나면 이 단위로 넓혀 본다 ── */
.rgn-bar{display:flex;flex-wrap:wrap;gap:5px;margin-top:8px;align-items:center}
.rgn-chip{display:inline-flex;align-items:center;gap:5px;padding:4px 9px;border:1px solid var(--line2);
          border-radius:var(--r-sm);background:var(--card);font-size:var(--fs-xs);cursor:pointer;
          color:var(--ink-2);transition:border-color .12s,background .12s}
.rgn-chip:hover{border-color:var(--navy)}
.rgn-chip.on{background:var(--navy);border-color:var(--navy);color:var(--on-navy);font-weight:700}
/* 사건이 걸린 권역 — 지도의 BP 점과 **같은 색**이다. 칩과 점이 색으로 이어진다. */
.rgn-chip.hit{border-color:var(--rk-3);color:var(--ink);font-weight:700}
.rgn-chip.hit::before{content:"";width:6px;height:6px;border-radius:50%;
  background:var(--rk-4);margin-right:5px;flex:0 0 auto}
.rgn-chip.hit.on{background:var(--rk-4);border-color:var(--rk-4);color:#fff}
:root[data-theme="dark"] .rgn-chip.hit.on{color:#131E31}
.rgn-chip .n{font-variant-numeric:tabular-nums;opacity:.75}
.rgn-more{display:inline-block}
.rgn-more>summary{list-style:none;cursor:pointer;display:inline-flex;align-items:center;
  padding:4px 9px;border:1px dashed var(--line2);border-radius:var(--r-lg);
  font-size:var(--fs-xs);color:var(--ink-3);}
.rgn-more>summary::-webkit-details-marker{display:none}
.rgn-more>summary:hover{border-color:var(--navy);color:var(--navy-2)}
.rgn-more-b{display:flex;flex-wrap:wrap;gap:5px;margin-top:6px;width:100%}
.rgn-note{margin-top:6px;font-size:var(--fs-xs);color:var(--ink-3);line-height:1.6}
.map-svg .dim{opacity:.13}
.map-svg .land{fill:var(--map-land);stroke:var(--line);stroke-width:.4;vector-effect:non-scaling-stroke;}
.map-svg .map-q{cursor:pointer;}
.map-stage{position:relative;}
.map-zoom{position:absolute;top:10px;right:10px;display:flex;flex-direction:column;z-index:3;box-shadow:var(--shadow);border-radius:var(--r-sm);overflow:hidden;}
.map-zoom button{width:30px;height:30px;background:var(--surface);color:var(--ink-2);border:1px solid var(--line);font-size:var(--fs-lg);font-weight:700;line-height:1;cursor:pointer;font-family:var(--font-sans);display:flex;align-items:center;justify-content:center;padding:0;}
.map-zoom button+button{border-top:none;}
.map-zoom button:hover{background:var(--accent-soft);color:var(--navy);}
.map-scrollhint{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);background:rgba(19,41,75,.84);color:var(--on-navy);padding:7px 15px;border-radius:var(--r-lg);font-size:var(--fs-xs);font-weight:600;letter-spacing:.01em;pointer-events:none;opacity:0;transition:opacity .18s;z-index:4;white-space:nowrap;}
.map-scrollhint.show{opacity:1;}
/* 원 클릭 팝업(관련 뉴스·재난·BP 요약) */
.map-pop{position:absolute;z-index:6;width:min(280px,74%);background:var(--surface);border:1px solid var(--line);border-radius:var(--r-md);box-shadow:0 6px 24px rgba(0,0,0,.16);padding:10px 11px 9px;font-size:var(--fs-xs);color:var(--ink-2);}
.map-pop[hidden]{display:none;}
.mp-x{position:absolute;top:4px;right:7px;background:none;border:none;font-size:var(--fs-md);line-height:1;color:var(--ink-3);cursor:pointer;padding:0;font-family:var(--font-sans);}
.mp-x:hover{color:var(--ink);}
.mp-head{font-weight:800;color:var(--ink);font-size:var(--fs-sm);margin-bottom:6px;padding-right:14px;display:flex;align-items:center;gap:6px;flex-wrap:wrap;line-height:1.3;}
.mp-badge{display:inline-block;font-size:var(--fs-2xs);font-weight:700;color:var(--on-navy);background:var(--navy);border-radius:var(--r-sm);padding:1px 6px;}
.mp-b-news{background:var(--map-bubble);}
.mp-b-bp{background:var(--map-bp);}
.mp-grade{display:inline-block;font-size:var(--fs-2xs);font-weight:800;color:var(--ink-2);background:var(--surface-2);border:1px solid var(--line);border-radius:var(--r-sm);padding:0 6px;}
.mp-sub{font-size:var(--fs-2xs);color:var(--ink-3);margin-bottom:6px;}
.mp-item{display:block;text-decoration:none;color:var(--ink-2);padding:5px 6px;border-radius:var(--r-md);border:1px solid var(--line-2);margin-bottom:4px;}
.mp-item:hover{background:var(--accent-soft);border-color:var(--accent-line);}
.mp-h{font-weight:600;line-height:1.35;color:var(--ink);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.mp-s{font-size:var(--fs-2xs);color:var(--ink-3);margin-top:1px;}
.mp-bp{padding:3px 0;line-height:1.4;}
.mp-lvl{display:inline-block;font-size:var(--fs-2xs);font-weight:700;border-radius:var(--r-sm);padding:0 5px;margin-right:5px;color:var(--on-navy);}
.mp-urgent{background:var(--hi);}
.mp-monitor{background:var(--md);}
.mp-minor{background:var(--ink-3);}
.mp-empty{color:var(--ink-3);padding:4px 0;}
.mp-more{display:block;width:100%;text-align:left;margin-top:5px;background:none;border:none;color:var(--accent);font-weight:700;font-size:var(--fs-2xs);cursor:pointer;padding:3px 2px;font-family:var(--font-sans);text-decoration:none;}
.mp-more:hover{text-decoration:underline;}
.map-legend-fold{margin-top:7px}
.map-legend-fold>summary{list-style:none;cursor:pointer;display:inline-block;
  font-size:var(--fs-2xs);color:var(--ink-3);padding:2px 8px;border:1px solid var(--line);
  border-radius:var(--r-lg)}
.map-legend-fold>summary::-webkit-details-marker{display:none}
.map-legend-fold>summary:hover{color:var(--ink-2);border-color:var(--line2)}
.map-legend{display:flex;flex-wrap:wrap;gap:6px 14px;margin-top:8px;font-size:var(--fs-2xs);color:var(--ink-3);}
.map-legend span{display:inline-flex;align-items:center;}
.map-legend i{display:inline-block;border-radius:50%;margin-right:5px;}
.map-hint{margin-top:8px;font-size:var(--fs-xs);color:var(--ink-3);text-align:center;}
.feed-head{display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;margin-bottom:10px;}
.feed-title{font-size:var(--fs-base);font-weight:800;color:var(--ink);}
/* 도표 범례 (심플) */
.chart-legend{display:flex;flex-wrap:wrap;gap:7px 16px;margin-top:9px;font-size:var(--fs-xs);color:var(--ink-3);}
.chart-legend span{display:inline-flex;align-items:center;gap:5px;}
.chart-legend b{color:var(--ink-2);font-weight:700;}
.chart-legend .lg-line{display:inline-block;width:16px;height:0;border-top:1.6px solid var(--accent);}
.chart-legend .lg-up{color:var(--hi);font-weight:800;}
/* 추세 변곡점 근거 */
.trend-card{cursor:pointer;transition:box-shadow .12s,transform .12s;background:var(--surface);border:1px solid var(--line);border-radius:var(--r-lg);padding:9px 11px;}
.trend-card:hover{box-shadow:var(--shadow);transform:translateY(-1px);}
.trend-chips{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:13px;}
.trend-chip{font-size:var(--fs-xs);font-weight:700;border:1px solid var(--line);background:var(--surface-2);color:var(--ink-2);border-radius:var(--r-sm);padding:5px 10px;cursor:pointer;font-family:inherit;font-variant-numeric:tabular-nums;}
.trend-chip:hover{background:var(--accent-soft);border-color:var(--accent-line);}
.trend-chip.on{background:var(--navy);color:var(--on-navy);border-color:var(--navy);}
.trend-ev-head{font-size:var(--fs-sm);font-weight:700;color:var(--ink-2);margin-bottom:9px;}
/* 대시보드 포커스 토글 */
/* ── RISK 감지 4단계 ────────────────────────────────────────
   막대 길이로 크기를 말하지 않는다. 수집 315 대 점검 2 라 정직한 척도로 그리면
   정작 결론인 뒷단계가 실선처럼 지워진다.
   **크기는 숫자가 지고, 띠는 비율의 인상만 진다.** (대문과 같은 규격) */
#kpiStrip{padding:0 var(--sp-6);}
#kpiStrip .rk{border:1px solid var(--line);border-radius:var(--r-lg);
  background:var(--surface);padding:14px 16px;margin:0;}
/* **아무것도 안 걸렸으면 무채색이다.** 색은 이상에만 쓴다 —
   평시에 화면이 붉으면 정작 일이 났을 때 그 붉은색이 안 보인다.
   `.rk.quiet` 하나로 램프 전체가 바뀐다. 색을 쓰는 곳을 흩어 두지 않는다. */
#kpiStrip .rk.quiet{--rk-1:var(--rq-1);--rk-2:var(--rq-2);
                    --rk-3:var(--rq-3);--rk-4:var(--rq-4);}
.rk-h{display:flex;align-items:baseline;gap:11px;margin-bottom:11px}
.rk-h .t{font-size:var(--fs-md);font-weight:800;color:var(--ink)}
.rk-h .m{font-size:var(--fs-2xs);color:var(--ink-3)}
.rk-h .rk-go{margin-left:auto;font-size:var(--fs-2xs);color:var(--ink-3);text-decoration:none}
.rk-h .rk-go:hover{color:var(--navy-2);text-decoration:underline}
/* **네 칸 균등**. flex 로 두면 이름 길이에 따라 간격이 들쭉날쭉해져
   1·2·3·4 가 한 줄로 안 읽힌다. 단계는 같은 폭이어야 순서로 보인다. */
.rk-f{display:flex;flex-wrap:wrap;gap:8px 28px;margin-bottom:11px}
.rk-f .f{display:flex;align-items:baseline;gap:6px;text-decoration:none;
  padding-right:14px;border-right:1px solid var(--line);}
.rk-f .f:last-child{border-right:0}
@media(max-width:760px){
  .rk-f{gap:8px 20px}
  .rk-f .f:nth-child(2n){border-right:0}
}
/* 단계 번호를 **색 점**으로 단다 — 아래 블록·지도와 같은 색이라
   "이 숫자가 저 블록이다" 가 색만으로 이어진다.
   글자는 잉크색 그대로 둔다. 색은 표식이 지고 글자는 안 진다. */
.rk-f .st{font-size:var(--fs-2xs);font-weight:700;color:var(--ink-3);
  font-variant-numeric:tabular-nums;display:inline-flex;align-items:center;gap:5px}
.rk-f .st::before{content:"";width:9px;height:9px;border-radius:var(--r-sm);
  background:var(--rk-1);flex:0 0 auto}
.rk-f .f:nth-child(2) .st::before{background:var(--rk-2)}
.rk-f .f:nth-child(3) .st::before{background:var(--rk-3)}
.rk-f .f:nth-child(4) .st::before{background:var(--rk-4)}
.rk-f .k{font-size:var(--fs-xs);color:var(--ink-3)}
.rk-f .n{font-size:var(--fs-kpi);font-weight:800;color:var(--ink);
  font-variant-numeric:tabular-nums;line-height:1;letter-spacing:-.02em}
.rk-f .f.fin .n{color:var(--rk-4)}
.rk-f a.fin:hover .n{text-decoration:underline}
/* 띠 — 각 단계에서 떨어져 나간 몫. 칸 사이에 2px 바탕색 틈을 둬
   인접한 두 색이 서로 번지지 않게 한다. */
.rk-s{display:flex;height:9px;border-radius:var(--r-sm);overflow:hidden;
  background:var(--line2);gap:2px}
.rk-s i{display:block;min-width:0;transition:width .35s ease;}
.rk-c{display:flex;justify-content:space-between;gap:18px;flex-wrap:wrap;
  margin-top:7px;font-size:var(--fs-2xs);color:var(--ink-3)}
.rk-c b{color:var(--ink-2);font-weight:700}
.rk-c .pc{color:var(--ink-3);font-variant-numeric:tabular-nums}
.rk-c .den{color:var(--ink-3);opacity:.75;margin-left:8px;font-size:.94em}
.rk-c .late,.rk-c .late b{color:var(--amber,#9C5B12)}
@media(max-width:640px){ .rk-f{gap:20px} #kpiStrip .rk{padding:14px} }

/* ── ③ 재난 키워드 ──────────────────────────────────────── */
#kwSection{margin-bottom:12px}
.kw-head{display:flex;align-items:baseline;gap:11px;margin-bottom:10px}
.kw-title{font-size:var(--fs-md);font-weight:800;color:var(--ink);
  display:flex;align-items:center}
.kw-sub{font-weight:400;font-size:var(--fs-2xs);color:var(--ink-3);margin-left:9px}
.kw-clear{margin-left:auto;font:inherit;font-size:var(--fs-xs);padding:4px 11px;
  border:1px solid var(--line);border-radius:var(--r-lg);background:var(--surface);
  color:var(--ink-2);cursor:pointer}
.kw-clear:hover{border-color:var(--ink-3);color:var(--ink)}
.kw-bar{display:flex;flex-wrap:wrap;gap:6px}
.kw-empty{font-size:var(--fs-xs);color:var(--ink-3)}
/* 칩 — 범주색은 왼쪽 막대가 진다. 글자는 잉크색 그대로다.
   칩 전체를 범주색으로 칠하면 한 줄이 무지개가 되어 무엇이 큰지 안 보인다. */
.kw{font:inherit;font-size:var(--fs-xs);display:inline-flex;align-items:center;gap:7px;
  padding:6px 12px 6px 10px;border:1px solid var(--line);border-radius:var(--r-sm);
  background:var(--surface);color:var(--ink);cursor:pointer;
  box-shadow:inset 3px 0 0 var(--line2);min-height:34px}
.kw:hover{border-color:var(--ink-3)}
.kw .n{font-variant-numeric:tabular-nums;font-weight:700;color:var(--ink-3)}
.kw .hi{font-size:var(--fs-2xs);font-weight:700;color:#fff;background:var(--rk-4);
  padding:1px 6px;border-radius:var(--r-sm);white-space:nowrap}
.kw.hot{border-color:var(--rk-4);box-shadow:inset 3px 0 0 var(--rk-4)}
.kw.bp{box-shadow:inset 3px 0 0 var(--ink-3)}
/* **선택은 남색, 위험은 붉은색.** 둘이 같은 색이면 "내가 고른 것" 과
   "위험한 것" 이 구별되지 않는다. 빨강은 "지금 걸렸다" 에만 남긴다. */
.kw.on{background:var(--navy-tint);border-color:var(--navy);font-weight:700;
  box-shadow:inset 3px 0 0 var(--navy)}
.kw.on .n{color:var(--navy-2)}
.kw.on::after{content:"✓";margin-left:2px;font-size:.85em;color:var(--navy-2)}
.kw:focus-visible{outline:2px solid var(--navy-2);outline-offset:2px}
.kw .bpn{font-size:var(--fs-2xs);color:var(--ink-2);background:var(--surface-2);
  padding:1px 6px;border-radius:var(--r-sm);white-space:nowrap}
:root[data-theme="dark"] .kw .hi{color:#131E31}

/* 판정 한 줄 = **사건 하나**. 기사 목록이 아니다 —
   무엇이 났고 · 어디고 · 어느 소재군이고 · 우리 BP 가 몇 곳 걸렸나. */
.jd{display:flex;flex-wrap:wrap;align-items:baseline;gap:6px 11px;
  padding:9px 2px 9px 11px;border-bottom:1px solid var(--line);
  border-left:3px solid var(--line2);font-size:var(--fs-sm)}
.jd:last-child{border-bottom:0}
.jd-high{border-left-color:var(--rk-4);background:var(--rk-1)}
.jd-medium{border-left-color:var(--rk-3)}
.jd-low{border-left-color:var(--line2)}
:root[data-theme="dark"] .jd-high{background:transparent}
.jd-g{font-size:var(--fs-2xs);font-weight:700;color:var(--ink-3);
  flex:0 0 4.6em;white-space:nowrap}
.jd-high .jd-g{color:var(--rk-4)}
.jd-d{font-variant-numeric:tabular-nums;color:var(--ink-3);font-size:var(--fs-xs);
  flex:0 0 3.2em;white-space:nowrap}
.jd-t{font-weight:600;color:var(--ink);flex:1 1 16rem;min-width:0}
.jd-t a{color:inherit;text-decoration:none}
.jd-t a:hover{text-decoration:underline}
.jd-m{display:flex;flex-wrap:wrap;gap:5px;margin-left:auto}
.jd-m .tag{font-size:var(--fs-2xs);color:var(--ink-2);background:var(--surface-2);
  border:1px solid var(--line);padding:1px 7px;border-radius:var(--r-sm);white-space:nowrap}
.jd-m .tag.bp{color:var(--ink)}
.jd-high .jd-m .tag.bp{color:var(--rk-4);border-color:var(--rk-4);font-weight:700}
.jd-x{flex:1 0 100%;font-size:var(--fs-2xs);color:var(--ink-3);
  font-family:var(--mono);letter-spacing:-.02em}
@media(max-width:640px){
  .jd-m{margin-left:0;flex:1 0 100%}
  .jd-t{flex:1 0 100%}
}

/* 세부 — 고른 칩 바로 아래에서 펼쳐진다 */
.kw-detail{margin-top:11px;border-top:1px solid var(--line);padding-top:9px;
  display:flex;flex-direction:column;gap:2px}
.kwd{border-bottom:1px solid var(--line)}
.kwd:last-child{border-bottom:0}
.kwd>summary{list-style:none;cursor:pointer;display:flex;align-items:baseline;gap:10px;
  padding:8px 2px;font-size:var(--fs-sm);min-height:38px;box-sizing:border-box}
.kwd>summary::-webkit-details-marker{display:none}
.kwd>summary:hover{background:var(--surface-2)}
.kwd-d{font-variant-numeric:tabular-nums;color:var(--ink-3);font-size:var(--fs-xs);
  flex:0 0 3.4em;white-space:nowrap}
.kwd-p{font-size:var(--fs-2xs);color:var(--ink-2);background:var(--surface-2);
  padding:1px 7px;border-radius:var(--r-sm);white-space:nowrap}
.kwd-t{font-weight:600;color:var(--ink);flex:1 1 10rem;min-width:0;
  overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.kwd-w{font-size:var(--fs-2xs);color:var(--navy-2);font-weight:700;white-space:nowrap}
.kwd-n{font-size:var(--fs-2xs);color:var(--ink-3);font-variant-numeric:tabular-nums;
  margin-left:auto}
.kwd-b{display:flex;flex-direction:column;padding:2px 2px 9px 3.2em}
.kwd-b a{display:flex;gap:9px;align-items:baseline;padding:5px 0;
  font-size:var(--fs-xs);color:var(--ink-2);text-decoration:none}
.kwd-b a:hover{color:var(--navy-2)}
.kwd-s{flex:0 0 6.5rem;color:var(--ink-3);font-size:var(--fs-2xs)}
@media(max-width:640px){
  .kwd>summary{flex-wrap:wrap}
  .kwd-t{flex:1 0 100%}
  .kwd-b{padding-left:0}
  .kwd-b a{flex-wrap:wrap}
  .kwd-s{flex:0 0 100%}
}

/* 단계 표식 — 위 4단계의 색을 그대로 쓴다.
   같은 색이 위의 숫자와 아래의 블록을 잇는다. 사람이 "3번이 어디였더라" 를
   되짚지 않아도 되게. 번호와 색을 같이 쓰는 이유는 색만으로는 색각 이상에서
   순서가 안 읽히기 때문이다 — **표식은 늘 둘 이상이어야 한다.** */
.stg{display:inline-flex;align-items:center;justify-content:center;
  width:19px;height:19px;border-radius:var(--r-sm);margin-right:8px;
  font-size:11px;font-weight:800;font-variant-numeric:tabular-nums;
  color:var(--ink);background:var(--rk-1);flex:0 0 auto;}
.stg.s1{background:var(--rk-1);color:var(--ink-2)}
.stg.s2{background:var(--rk-2);color:var(--ink)}
.stg.s3{background:var(--rk-3);color:#fff}
.stg.s4{background:var(--rk-4);color:#fff}
:root[data-theme="dark"] .stg.s1,
:root[data-theme="dark"] .stg.s2{color:var(--ink)}
:root[data-theme="dark"] .stg.s3,
:root[data-theme="dark"] .stg.s4{color:#131E31}

/* ── 접기 블록 — 부수적인 것은 접어 둔다 ──────────────────
   화면을 열었을 때 답해야 하는 질문은 하나다: **지금 우리가 걸렸나.**
   그 답과 근거만 펼쳐 두고, 나머지는 접는다. 접어 두면 없는 것과 달리
   "있는데 지금 안 볼 뿐" 이 되어, 필요할 때 찾을 수 있다. */
.blk{border:1px solid var(--line);border-radius:var(--r-lg);background:var(--card);
     margin-bottom:12px;}
.blk>summary{list-style:none;cursor:pointer;display:flex;align-items:baseline;
     gap:9px;padding:11px 16px;font-size:var(--fs-md);font-weight:800;color:var(--ink);
     min-height:44px;box-sizing:border-box;}
.blk>summary::-webkit-details-marker{display:none}
.blk>summary::before{content:"▸";color:var(--ink-3);font-size:var(--fs-2xs);}
.blk[open]>summary::before{content:"▾"}
.blk>summary:hover{background:var(--surface-2)}
.blk[open]>summary{border-bottom:1px solid var(--line)}
.blk-hint{font-weight:400;color:var(--ink-3);font-size:var(--fs-xs);}
.blk-n{font-weight:700;color:var(--navy-2);font-variant-numeric:tabular-nums;}
.blk>section{padding:12px 16px 16px;}
.blk .map-wrap{border:0;}
@media (max-width:640px){ .blk-hint{flex:1 0 100%} }

/* 참고 숫자 접기 */
.refmore{margin-left:auto;flex:0 0 auto;}
.refmore>summary{list-style:none;cursor:pointer;font-size:var(--fs-xs);
     color:var(--ink-3);text-decoration:underline;}
.refmore>summary::-webkit-details-marker{display:none}
.refmore-b{display:flex;flex-wrap:wrap;gap:2px 18px;align-items:baseline;
     padding-top:6px;width:100%;}
.rk-io{justify-content:flex-start;}

.dash-seg{display:flex;gap:5px;margin-bottom:14px;}
.dseg{font-size:var(--fs-sm);font-weight:700;border:1px solid var(--line);background:var(--surface);color:var(--ink-2);border-radius:var(--r-lg);padding:7px 14px;cursor:pointer;font-family:inherit;display:flex;align-items:center;gap:6px;transition:background .12s;}
.dseg:hover{background:var(--surface-2);}
.dseg.on{background:var(--navy);color:var(--on-navy);border-color:var(--navy);}
.dseg-b{font-size:var(--fs-2xs);font-weight:700;background:rgba(19,41,75,.12);border-radius:var(--r-lg);padding:0 6px;min-width:16px;text-align:center;font-variant-numeric:tabular-nums;}
.dseg.on .dseg-b{background:rgba(255,255,255,.22);}
#catTabs{margin-top:0;}
#footer{padding:24px 0 28px;}

/* ── 소스 상태 패널 ── */
.src-sum{display:flex;gap:8px;margin-bottom:14px;flex-wrap:wrap;}
.src-sum .pill{font-size:var(--fs-sm);font-weight:700;border-radius:var(--r-sm);padding:7px 13px;border:1px solid var(--line);background:var(--surface-2);color:var(--ink-2);font-variant-numeric:tabular-nums;}
.src-sum .pill.ok{background:var(--lo-bg);border-color:var(--lo-line);color:var(--lo);}
.src-sum .pill.err{background:var(--hi-bg);border-color:var(--hi-line);color:var(--hi);}
#srcOverlay #sourceBar{display:grid;grid-template-columns:repeat(2,1fr);gap:5px;margin-top:0;}

/* ── 반응형 ── */
/* ── 반응형 (맥북 → 아이패드 → 모바일) ── */
@media (max-width:1120px){
  .kpi{padding:13px 14px;}
}
@media (max-width:1024px){           /* 아이패드 가로 — 라벨 사이드바(축소) */
  #app{grid-template-columns:185px 1fr;}
  .kpi-num{font-size:var(--fs-xl);}
  #topbar,.main-pad,#dbBar{padding-left:18px;padding-right:18px;}
}
@media (max-width:768px){            /* 아이패드 세로/모바일 — 햄버거 드로어 */
  #app{grid-template-columns:1fr;}
  #topbar{padding:11px 16px;}
  .btn,.scm-analyze-btn,.cat-tab,.modal-close{min-height:40px;}
}
@media (max-width:480px){            /* 모바일 */
  .kpi{padding:11px 12px;} .kpi-num{font-size:var(--fs-xl);} .kpi-lbl{font-size:var(--fs-2xs);}
  #topbar{padding:10px 14px;} .tb-title{font-size:var(--fs-md);}
  .main-pad,#dbBar{padding-left:14px;padding-right:14px;}
  .tb-right .btn{flex:1;text-align:center;}
  .modal-box{width:100%;max-width:100vw;border-radius:var(--r-lg) var(--r-lg) 0 0;}
  .scm-row{flex-wrap:wrap;} .scm-mats{width:100%;margin-top:4px;}
}
.scm-bp{text-decoration:none;color:inherit;cursor:pointer}
.scm-bp:hover{border-color:var(--navy);color:var(--navy)}
.scm-bp-how{display:block;margin-top:5px;font-size:var(--fs-2xs);color:var(--ink-3)}
.scm-alt-btn{margin-left:6px;padding:6px 12px;border:1px solid var(--line2);background:var(--card);
  color:var(--ink-2);border-radius:var(--r-md);font:inherit;font-size:var(--fs-xs);font-weight:700;cursor:pointer}
.scm-alt-btn:hover{border-color:var(--navy);color:var(--navy)}
#importBox{margin-top:10px}
#importBox textarea{width:100%;padding:9px 11px;border:1px solid var(--line2);border-radius:var(--r-md);
  font-family:var(--mono);font-size:var(--fs-2xs);line-height:1.6;background:var(--card);color:var(--ink);resize:vertical}
#importBox .ib-row{display:flex;align-items:center;gap:6px;margin-top:6px}
#importMsg{font-size:var(--fs-xs);margin-left:4px}
#importMsg.ib-ok{color:var(--green);font-weight:700}
#importMsg.ib-err{color:var(--red);font-weight:700}
</style>
<?php include __DIR__ . '/_shared/grade.php'; ?>
<!-- Flask 주입: R2 url_for · R7 데이터 임베드 -->
<script>
window.MGSCS_DATA = "<?= url('/static/260611_junghoon/mgscs/') ?>";
window.MGSCS_BP_RESOLVED = <?= $bp_rows_json ?>;
window.MGSCS_TAXONOMY = <?= $taxonomy_json ?>;
// LLM이 고를 수 있는 낱말 — 명부에 실제로 있는 국가·행정구역·소재군만.
window.MGSCS_MATCH_VOCAB = <?= $match_vocab_json ?>;
// 보고서 본문은 Jinja가 닿지 않는 구역(raw)의 JS 템플릿이다.
// 거기서 버전을 쓰려면 이렇게 상수로 넘겨야 한다.
// 주석에 raw 태그를 글자로 적으면 그 자리에서 실제로 구역이 열린다 — 적지 말 것.
window.MGSCS_VERSION = "<?= htmlspecialchars($app_version ?? '') ?>";
window.MGSCS_UPDATED = "<?= htmlspecialchars($updated_at ?? '') ?>";
</script>

<!-- ── mGSCS 공통 테마 v5 — 라이트 기본 + 다크 토글(mgscs_theme, nav.html의 ◐) ── -->
<?php include __DIR__ . '/_shared/theme.php'; ?>
</head>
<body>

<?php $here = 'dashboard'; ?><?php $here_label = 'Risk 현황'; ?>
<?php include __DIR__ . '/_shared/nav.php'; ?>
<?php if (!$logged_in): ?>
<div style="padding:9px 20px;background:var(--accent-soft);border-bottom:1px solid var(--navy-line);font-size:var(--fs-sm);color:var(--ink-2)">
  재난 신호와 <b>BP 지도</b>는 로그인 없이 볼 수 있습니다. <b>담당자 연락처</b>는 소재전략 창구로 일원화되어 로그인해야 보입니다 — <a href="<?= url('/login?next=' . urlencode($_SERVER['REQUEST_URI'])) ?>" style="color:var(--navy-2);font-weight:700">관리자 로그인</a>
</div>
<?php endif; ?>


<!-- API KEY 모달 -->
<div id="apiKeyOverlay" class="modal-overlay" onclick="if(event.target===this)closeApiKeyModal()">
  <div class="modal-box" style="width:480px;">
    <div class="modal-head">
      <h2>AI 분석 설정</h2>
      <button class="modal-close" onclick="closeApiKeyModal()">×</button>
    </div>
    <div class="modal-body">
      <p class="apikey-desc">
        신호 요약·AI 이중 검증을 사용하려면 API 키가 필요합니다.<br>
        키는 <b>이 브라우저에만</b> 저장되며, 분석 요청 시 입력하신 API·프록시 주소로만 전송됩니다.
      </p>
      <label class="apikey-label">API 키 입력</label>
      <input type="password" id="apiKeyInput" class="apikey-input" placeholder="sk-ant-api03-...">
      <div id="apiKeyStatus" class="apikey-status"></div>
      <p class="apikey-hint">
        키가 없으면 <b>서버 경유 분석</b>을 사용합니다 — <a href="settings">⚙ 설정</a>에서 서버 키를 관리합니다.
      </p>
      <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--line);">
        <label class="apikey-label" style="display:flex;align-items:center;gap:6px;">
          사내망 프록시 URL
          <span style="font-size:10px;font-weight:400;color:var(--ink-3);">(방화벽 때문에 API 직접 호출이 막힐 때 입력)</span>
        </label>
        <input type="text" id="proxyUrlInput" class="apikey-input" placeholder="https://your-worker.workers.dev" style="margin-bottom:6px;">
        <p style="font-size:11px;color:var(--ink-3);line-height:1.6;">비워두면 직접 호출을 시도합니다. 사내망에서는 서버 경유 분석을 사용합니다(<a href="settings">⚙ 설정</a>).</p>
        <label class="apikey-label" style="margin-top:12px;display:flex;align-items:center;gap:6px;">
          API 형식 · 모델 <span style="font-size:10px;font-weight:400;color:var(--ink-3);">(기본=Claude. Qwen 등은 OpenAI 호환 선택)</span>
        </label>
        <div style="display:flex;gap:6px;">
          <select id="apiFormatSelect" class="apikey-input" style="flex:0 0 46%;">
            <option value="anthropic">Anthropic (Claude)</option>
            <option value="openai">OpenAI 호환 (Qwen·vLLM·Ollama)</option>
          </select>
          <input type="text" id="modelInput" class="apikey-input" placeholder="모델 ID(비우면 기본)" style="flex:1;">
        </div>
        <p style="font-size:11px;color:var(--ink-3);line-height:1.6;margin-top:4px;">OpenAI 호환 선택 시 위 프록시 URL에 <b>베이스 주소</b>(끝의 <code>/v1</code> 제외)를 넣고, 모델 ID에 <code>qwen2.5-...</code> 등을 입력하세요. Qwen 키는 <code>sk-ant-</code> 형식이 아니어도 됩니다.</p>
      </div>
      <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--line);">
        <label class="apikey-label" style="display:flex;align-items:center;gap:6px;">
          BP 기준 정보 <span style="font-size:10px;font-weight:700;color:var(--hi);">· 대외비</span>
          <span style="font-size:10px;font-weight:400;color:var(--ink-3);">(소재군별 협력사·등급 — 재난 신호에 영향 BP 표시)</span>
        </label>
        <p style="font-size:11px;color:var(--ink-3);line-height:1.6;margin-bottom:6px;">
          소재군별 BP JSON을 붙여넣으면 점검 후보에 <b>영향 가능 BP</b>(소재군·지역이 일치하는 항목)가 표시됩니다.
          데이터는 <b>이 브라우저에만</b> 저장되며 서버·저장소로 전송되지 않습니다.
        </p>
        <textarea id="bpMasterInput" class="apikey-input" rows="3" placeholder='[{"bp":"협력사명","grp":"소재군","reg":"KR","grade":"QD"}, ...] · grade는 QD(품질/개발) · Q(품질) · D(개발) 중 선택 · 삭제하려면 [] 입력' style="font-family:monospace;font-size:11px;resize:vertical;"></textarea>
        <div id="bpMasterStatus" style="font-size:11px;margin-top:6px;color:var(--ink-3);"></div>
      </div>
      <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--line);">
        <label class="apikey-label" style="display:flex;align-items:center;gap:6px;">
          BP 사업장 좌표 <span style="font-size:10px;font-weight:700;color:var(--hi);">· 대외비</span>
          <span style="font-size:10px;font-weight:400;color:var(--ink-3);">(본사·제조 site 표기 · 지진 근접 · 클릭 시 BP 연동)</span>
        </label>
        <p style="font-size:11px;color:var(--ink-3);line-height:1.6;margin-bottom:6px;">
          BP 사업장 좌표 JSON을 붙여넣으면 지도에 <b>본사(◆)·제조(●) 점</b>이 찍히고, 점을 클릭하면 <b>사명·소재군</b>(BP 기준 정보 연동)이 뜹니다. site의 <code>role</code>에 <b>"본사"</b>/<b>"제조"</b>를 넣으면 마커가 구분됩니다. <b>지진(USGS 실좌표)</b> 발생 시 반경 내 사이트가 거리·위험도와 함께 표시됩니다. 데이터는 브라우저에만 저장됩니다.
        </p>
        <textarea id="bpSitesInput" class="apikey-input" rows="3" placeholder='[{"name":"협력사명","reg":"KR","sites":[{"n":"본사","lat":00.0,"lon":00.0,"role":"본사"},{"n":"제1공장","lat":00.0,"lon":00.0,"role":"제조"}]}]' style="font-family:monospace;font-size:11px;resize:vertical;"></textarea>
        <div id="bpSitesStatus" style="font-size:11px;margin-top:6px;color:var(--ink-3);"></div>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn btn-gray" onclick="closeApiKeyModal()">취소</button>
      <button class="btn btn-red" onclick="saveApiKey()">저장</button>
    </div>
  </div>
</div>

<!-- KEYWORD / CATEGORY 모달 -->
<div id="kwOverlay" class="modal-overlay" onclick="if(event.target===this)closeKwModal()">
  <div class="modal-box" style="width:740px;">
    <div class="modal-head">
      <h2>카테고리·키워드 관리</h2>
      <button class="modal-close" onclick="closeKwModal()">×</button>
    </div>
    <div class="modal-body" id="kwBody"></div>
    <div class="modal-foot">
      <button class="btn btn-gray" onclick="resetCategories()">기본값 초기화</button>
      <button class="btn btn-red" onclick="closeKwModal()">완료</button>
    </div>
  </div>
</div>


<!-- ════════ APP SHELL : 좌측 사이드바 + 메인 ════════ -->
<div id="app">

  <!-- ── 사이드바 ── -->

  <!-- ── 메인 ── -->
  <main id="main">
    <header id="topbar">
      <div class="tb-left">
        <div>
          <div class="tb-title" id="viewTitle">Risk 현황</div>
          <div class="status-text" id="statusText">시스템 준비 중</div>
        </div>
      </div>
      <div class="tb-right">
        <div class="tb-prog" id="progWrap" hidden>
          <div class="ring" id="progRing" style="--p:0"><span id="progPct">0%</span></div>
          <span class="prog-lbl" id="progressLabel">준비 중</span>
        </div>
        <div class="update-time" id="updateTime"></div>
        <button class="btn btn-red" id="refreshBtn" onclick="startFetch()">수집 실행</button>
      </div>
    </header>

    
    <div class="vtabs" role="tablist">
      <button type="button" class="on" role="tab" data-view="dashboard"
              onclick="setView('dashboard')">오늘</button>
      <button type="button" role="tab" data-view="archive"
              onclick="setView('archive')">수집 이력<span class="n" id="navArchive"></span></button>
    </div>

    <!-- 결론이 먼저다. 숫자는 결론의 근거로 뒤에 온다. -->

    <div id="verdictSlot" class="verdict-slot">
      <div class="scm-conclusion concl-ok">
        <div class="concl-line">
          <span class="concl-icon">◌</span>
          <span class="concl-label">오늘의 결론</span>
          <span class="concl-verdict">아직 오늘 판단이 없습니다 — <b>수집 실행</b>을 누르면 서버가 판정합니다</span>
        </div>
        <div class="concl-meta">
          <span class="concl-src concl-src-none">판정 전</span> · 서버 스냅샷을 읽는 중이면 곧 이 자리에 결론이 채워집니다.
        </div>
      </div>
    </div>

    <!-- KPI — 행동을 부르는 숫자 셋만 크게, 나머지는 참고 한 줄로. -->



<div class="main-pad tier1">
      <section id="mapSection" class="view-block" style="margin-bottom:18px;">
        <div class="map-wrap">
          <div class="map-head">
            <div class="map-title">연관 <span>· 사건 권역과 그 안의 BP</span></div>
            <div class="map-toggle" id="mapToggle">
              <button type="button" data-v="kr" onclick="setMapView('kr')">한국</button>
              <button type="button" data-v="asia" class="on" onclick="setMapView('asia')">한·중·일</button>
              <button type="button" data-v="world" onclick="setMapView('world')">세계</button>
            </div>
          </div>
          <div class="map-stage">
            <svg id="mapSvg" class="map-svg" viewBox="763.9 111.1 152.8 97.2" preserveAspectRatio="xMidYMid meet" role="img" aria-label="재난·BP 세계지도">
              <path class="land" id="mapLand" d=""></path>
              <g id="mapMonitor"></g>
              <g id="mapBubbles"></g>
              <g id="mapQuakes"></g>
              <g id="mapBp"></g>
            </svg>
            <div class="map-zoom" role="group" aria-label="지도 확대·축소">
              <button type="button" onclick="zoomMapBy(1/1.35)" aria-label="확대" title="확대">+</button>
              <button type="button" onclick="zoomMapBy(1.35)" aria-label="축소" title="축소">−</button>
            </div>
            <div class="map-scrollhint" id="mapScrollHint">Ctrl(⌘) + 스크롤로 확대 · 드래그로 이동 · 터치는 두 손가락</div>
            <div class="map-pop" id="mapPop" hidden></div>
          </div>
          <!-- 권역 축 — 사건이 나면 근방을 넓혀 보는 단위다. -->
          <div class="rgn-bar" id="rgnBar" hidden></div>
          <div class="rgn-note" id="rgnNote" hidden></div>
          <details class="map-legend-fold"><summary>기호</summary><div class="map-legend">
            <span><i style="width:11px;height:11px;background:var(--hi);"></i>지진 M6+</span>
            <span><i style="width:8px;height:8px;background:var(--md);"></i>M5+</span>
            <span><i style="width:6px;height:6px;background:var(--accent);"></i>M4.5+</span>
            <span><i style="width:11px;height:11px;background:var(--map-bubble);opacity:.4;"></i>뉴스 빈도(개략값 · 거리 판정 제외)</span>
            <span><i style="width:8px;height:8px;background:var(--map-bp);border-radius:1px;transform:rotate(45deg);"></i>본사</span>
            <span><i style="width:7px;height:7px;background:var(--map-bp);"></i>제조 site</span>
            <span><i style="width:10px;height:10px;background:var(--rk-3);border:1.5px solid var(--rk-4);border-radius:50%;"></i><b>사건 권역의 BP</b></span>
          </div></details>
          <div class="map-hint" id="mapHint" style="display:none;">BP 좌표를 불러오지 못함 — <button class="btn sm" onclick="openApiKeyModal()">좌표 붙여넣기</button> <span class="hint-sub">사내에서는 로그인하면 서버가 채웁니다</span></div>
        </div>
      </section>

</div>

    <div id="kpiStrip">
      <!-- 행동을 부르는 숫자 셋. 이 셋만 크다. -->
      
      <div class="rk" id="rkBox">
        <div class="rk-h">
          <span class="t">RISK 감지</span>
          <span class="m">최근 24시간</span>
          <a class="rk-go" href="<?= url('/system') ?>#funnel">단계 설명 →</a>
        </div>
        <div class="rk-f">
          <span class="f"><span class="st">1</span><span class="k">수집</span><span class="n tnum" id="kpiCollected">–</span></span>
          <span class="f"><span class="st">2</span><span class="k">재난</span><span class="n tnum" id="kpiDisaster">–</span></span>
          <span class="f"><span class="st">3</span><span class="k">연관</span><span class="n tnum" id="kpiLinked">–</span></span>
          <a class="f fin kpi-link" href="<?= url('/inspect') ?>"
             title="점검하기로 정한 것 — 점검 현황에서 발송·회신을 굴립니다"><span class="st">4</span><span class="k">점검 대상</span><span class="n tnum" id="kpiReview">–</span></a>
        </div>
        
        <div class="rk-s" id="rkBand">
          <i data-b="drop1"></i><i data-b="drop2"></i><i data-b="drop3"></i><i data-b="fin"></i>
        </div>
        <div class="rk-c">
          
          <span id="rkLeft">단계별 탈락 수</span>
          <span id="rkRight"></span>
        </div>
        
        <div class="rk-io">
          <span class="srv-save" id="srvSave">서버 저장 대기</span>
          <details class="refmore">
            <summary>자세히</summary>
            <div class="refmore-b">
    <span class="kpi"><span class="kpi-lbl">누적 수집</span><span class="kpi-num tnum" id="kpiTotal">0</span></span>
    <span class="kpi"><span class="kpi-lbl">최근 24시간</span><span class="kpi-num tnum" id="kpi24">0</span></span>
    <span class="kpi"><span class="kpi-lbl">정상 소스</span><span class="kpi-num tnum"><span id="srcOkCount">–</span><span class="kpi-den">/<span id="srcTotalCount">–</span></span></span></span>
    <div id="dbBar">누적 신호 <strong id="dbCount">0</strong>건<span id="dbOldestWrap" hidden><span>·</span><span id="dbOldest"></span></span></div>
    <span class="src">수집은 이 브라우저가 · <b>저장은 서버가</b></span>
            </div>
          </details>
        </div>
      </div>
      </div>


<div class="main-pad">
      
      <!-- ① 우리가 걸렸나 — **서버가 판정한 결과만** 보여준다.
           예전에는 여기에 [영향 분석 실행]이 있어 화면이 스스로 LLM 을 부르고
           게이트까지 돌렸다. 그러면 판정이 두 곳에서 따로 돈다 —
           화면은 브라우저에 쌓인 것을, 서버는 자기가 받은 것을 보고,
           둘이 다른 답을 낸다. 판정하는 곳은 하나여야 한다.
           수집한 것은 올리는 즉시 서버가 판정하므로 누를 것이 없다. -->
      <section id="scmSection" class="view-block">
        <div class="scm-header">
          <div class="scm-title">점검 대상<span class="scm-n tnum" id="scmN"></span><span class="scm-title-badge">오늘 할 일</span></div>
          <div class="scm-header-btns">
            <span class="scm-note" id="judgeNote">판정 결과 확인 중</span>
            <a class="btn sm" href="<?= url('/inspect') ?>">점검 현황 →</a>
          </div>
        </div>
        <div id="quakePanel" class="quake-panel" style="display:none;"></div>
        <div id="scmGrid" class="scm-list">
          <div class="scm-empty">걸린 사건 없음 · 수집분은 서버가 판정</div>
        </div>
      </section>


      
      <section id="kwSection" class="view-block">
        <div class="kw-head">
          <div class="kw-title">판정된 재난
            <span class="kw-sub" id="kwSub">최근 7일</span></div>
          <button class="kw-clear" id="kwClear" hidden onclick="selectKeyword(null)">전체 보기</button>
        </div>
        <div class="kw-bar" id="kwBar"><span class="kw-empty">판정 결과 확인 중</span></div>
        <div class="kw-detail" id="kwDetail" hidden></div>
      </section>

      
      <details class="blk" id="trendFold">
        <summary>재난 추이<span class="blk-hint">범주별 일별 · 급증</span></summary>
      <section id="trendSection" class="view-block" style="margin-bottom:0;">
        <div class="feed-title" style="margin-bottom:9px;display:none;">30일 카테고리 추이</div>
        <div id="trendGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:8px;"></div>
        <div class="chart-legend">
          <span><i class="lg-line"></i> 최근 30일 일별 수집 추이</span>
          <span><b>123</b> 우측 숫자 = 최근 날짜의 건수</span>
          <span><b class="lg-up">▲</b> 직전 7일 평균의 1.5배 이상 급증</span>
          <span><b>👆</b> 카드 클릭 → 변곡점 근거(그날의 헤드라인)</span>
          <span><b>기타</b> = 미분류(표준 24종에 안 맞는 신호)</span>
        </div>
      </section>
      </details>

      
      <details class="blk" id="newsFold">
        <summary>수집 신호<span class="blk-n" id="newsFoldN"></span><span class="blk-hint">판정의 원재료</span></summary>
      <section id="newsSection" class="view-block">
        <div class="feed-head">
          <div id="catTabs"></div>
        </div>
        <div id="content">
          <div class="state-screen" id="stateScreen">
            <div class="state-icon spin">◴</div>
            <div class="state-sub">재난·위기 신호를 수집하고 있습니다.</div>
            <div class="progress-wrap">
              <div class="progress-label" id="progressLabelInline">준비 중</div>
              <div class="progress-bar"><div class="progress-fill" id="progressFill" style="width:0%"></div></div>
            </div>
          </div>
          <div id="newsList" style="display:none;"></div>
          <div id="newsStaleWrap"></div>
        </div>
      </section>
      </details>

      <!-- ── 누적 보관 (수집 이력 아카이브) ── -->
      <section id="archiveSection" class="view-block" style="display:none;">
        <div class="arc-head">
          <div class="arc-title">수집 이력<span class="arc-sub" id="arcRange">—</span></div>
          <div class="arc-actions">
            <button type="button" class="arc-sync" id="arcSync" onclick="syncFromServer()">서버와 맞추는 중…</button>
            <button class="btn btn-gray" onclick="exportArchive('csv')">CSV 내보내기</button>
            <button class="btn btn-gray" onclick="exportArchive('json')">JSON 내보내기</button>
            <span class="arc-sep"></span>
            <button class="btn btn-outline" onclick="clearNewsDB()"
                    title="이 브라우저에 쌓인 기록을 지웁니다. 서버 기록은 그대로입니다">지우기</button>
          </div>
        </div>
        <div class="arc-stats" id="arcStats"></div>
        <div class="arc-chips" id="arcChips"></div>
        <div class="arc-cache" id="arcCache" hidden></div>
        <div class="arc-controls">
          <input type="text" id="arcSearch" class="arc-input" placeholder="제목·출처 검색" oninput="renderArchive()">
          <select id="arcCat" class="arc-input" onchange="renderArchive()"></select>
          <select id="arcSort" class="arc-input" onchange="renderArchive()">
            <option value="new">최신순</option>
            <option value="old">오래된순</option>
          </select>
        </div>
        <div id="arcList" class="arc-list"></div>
        <div class="arc-note"><b>재난·재해로 분류된 것만</b> 주제로 묶습니다.
주제를 누르면 그 사건을 다룬 기사가 매체별로 펼쳐집니다 — 여러 매체가 같은 사건을
말하면 그것이 근거의 무게입니다.
<br><b>서버가 원장입니다.</b> 이 화면은 3분마다 서버를 다시 읽으므로, 누가 어느 자리에서 열어도 같은 것을 봅니다(약간의 시차는 있습니다). 이 브라우저에는 사본이 90일·3,000건까지 남습니다.</div>
      </section>

      <div id="footer">
        mGSCS · material Global SCM Control System &nbsp;|&nbsp; Creating Solution, Enabling Future
      </div>
    </div>
  </main>
</div>

<!-- 변곡점 근거 모달 -->
<div id="trendOverlay" class="modal-overlay" onclick="if(event.target===this)closeTrendModal()">
  <div class="modal-box" style="width:680px;">
    <div class="modal-head">
      <h2 id="trendModalTitle">변곡점 근거</h2>
      <button class="modal-close" onclick="closeTrendModal()">×</button>
    </div>
    <div class="modal-body">
      <div style="font-size:11px;color:var(--ink-3);margin-bottom:10px;">건수가 많은 날부터 — 그날 이 카테고리를 끌어올린 실제 헤드라인입니다.</div>
      <div id="trendDayChips" class="trend-chips"></div>
      <div id="trendEvidence"></div>
    </div>
    <div class="modal-foot">
      <button class="btn btn-gray" onclick="closeTrendModal()">닫기</button>
    </div>
  </div>
</div>



<!-- 소스 상태 패널 -->
<div id="srcOverlay" class="modal-overlay" onclick="if(event.target===this)closeSrcPanel()">
  <div class="modal-box" style="width:580px;">
    <div class="modal-head">
      <h2>수집 소스 상태</h2>
      <button class="modal-close" onclick="closeSrcPanel()">×</button>
    </div>
    <div class="modal-body">
      <div class="src-sum" id="srcSummary"></div>
      <div id="sourceBar"></div>
    </div>
    <div class="modal-foot">
      <button class="btn btn-gray" onclick="clearNewsDB()">수신 이력 초기화</button>
      <button class="btn btn-red" onclick="closeSrcPanel()">닫기</button>
    </div>
  </div>
</div>

<!-- Toast -->
<div id="toast" style="position:fixed;bottom:24px;left:50%;transform:translateX(-50%);background:var(--navy);color:var(--on-navy);padding:10px 20px;border-radius:var(--r-md);font-size:12px;z-index:9999;box-shadow:0 4px 12px rgba(0,0,0,.3);transition:opacity .3s;opacity:0;pointer-events:none;"></div>

<script>
// ════════════════════════════════════════
// SK hynix 로고 (보고서 자체완결용 임베드 + 헤더 공용)
// ════════════════════════════════════════
const SKHYNIX_LOGO="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAcYAAAD6CAMAAAAMcxd5AAAAbFBMVEVMaXH/egDqACz/egDqACzqACz/egD/egD/egDqACzqACz/egD/egD/egD/egDqACzqACzqACzqACzqACz/egD/egD/egD/egD/egD/egDqACzqACzqACzqACzqACzqACzqACzqACzqACz/egDPxcvuAAAAInRSTlMAEPDwwIBAwIBAECBg4KDQIDCgYLDQMJBQcOCwcFCQ4tNK0WOQWwAAAAlwSFlzAAAsSgAALEoBd3p0TQAAEl1JREFUeJztned26zoORl3jXuIS12Rm7Pd/x1mSiwASICGRkulj7D93nbhefmZBIdBSFEVRFEVRFEVRFEVRFEVRFEVRFEVRlMaYDIfDr7aO93sz6V4zZvPp5NOH4p35uj7Z7n4+fTTeFiBjNilVyfcEy5jNyYNulO+HJeP1et18ffqovB+EjNfrTKfkm0HKeL12h3pyfScGjI7X61yFfB94GVXIN2LnkFGFfBuGThmv150edt6Bg0fGa3f46UP0DlCGo8FM7cjkEch4vW50i0wdiYy6siaPSMbrdatO86TZCnW86oRMGZf9rxPybZhLVezO9cSaLj77/44GPdLGa/9f9aT6BkgMx819Ji7PvUv/0wcsTQQyHm5ffNS7ZJzGnz5kKdL2Lqi3k833TcTL5bL4/vQxSxGfijcz4+9S0Bl9+pglyMwtY67icnFBrD990NLDbf9Ps+/73bkY6EEnNTYuFTctUsXLZfXpw5YaLvu/m0WoxkdbRdUxNVwy5lZ/j1Lxclmo4ZESLsMxM/vXtIqqY1o4ZJxnX5RcUlXH1HDY/3vXZFQd04KXMfuaC4eMqmNCsPb/NjumulS8XE6fPnjpwNr/2db465ZR7Y5kYGXMzI2+R0bVMRVYw1Eko/pXEyFQRtUxDfaBMnY0/pgCrP0vlPHSUbMjASacjINWq3UWyHhZfPoQJgEn4yxLwJHIqMfVFOhyOmaecZGMesxJANZwzEL/J5GMesx5PU43jss1DlDv6sthDceu36n65O/DB/H18PH/bFVdCXX8/fRhfDWs/Z+bHN9CGdV6fDGO+P8Pn4tjoUGr1/LDyzgXm46Xy38ul2Ovd+r3f0fLTx7Ol8HLeJ04s3HQovpf+K/Fqf+rRkizOGQcSm2Ozv+IP/b+1joxG8OR/99tC6fjmvO+dk5nlbIRXNc4hGGOP6fWx9WvnmNrx1XGcSZyAfT8dslprUrWi7OMw17gWF2M0QVIVfIlTF0ybvz5cZljXHacvazU2VMb7vv//nDVrz8RsuDY1xNPPTjsf0m4ql/C85pz0lvnteCUceM5q+ahf+Imq3NKapy5Bpwydt0OuTzSKIxKQiH7etyJjfv+f9tlcnTynU6WI2C8UoWMjFvGL9cZJ3ecSmPLKmStuNs47B334257nCgNkhTy/M+O6Qtwl3Ec8kHHe2aj8xKkm6MaktFwl3HkZbznGS+rq5g58jSgFQm3/c/KeLxvbZIbAi7+dIuMglvGAyPjMzlV6Ijj6agZGQWnjNxJ9TH20qQrF7pDxsAp44S2KJ514yTBDTe6PcbB1caB8eIUiXAlHXEWPXWxRsJl/9M+1SLbXx7ccIo4GXSnbz2GCeBq4zClDENw96ZUcMPkEeyY5N9go+0FgnDY/13SMCzWwXH1NbXzdw89th9upJm2bAnBYf+TOVXAh1Y+uHHnmQgw2YEblrqwBsAbjlmGozXh4O3iKsGNS6dIlZsYC/pcF9bK8DJOickI7/qXd8R1TudiY93bh6ut6lgZTsXsmLo0JiO6O1UmuNHprfq/IBNnsiPr1XV1g6wKo2I+M0xHHLLVyeDGqT/KzkDjUUa/3+9n/zUyqSYH1ljt7tMfsDShyzjmKppLKvJ/EmvqUZCNuqfn4RM96FSDtP+3VJFqfDvcdsStPCL+THeC1p+qYyWoNg4bSsUefncruPE8xE6+cp7P/Pn6Gg434uat86SHK1UI+z/vMGaqaFTcsIIbN0/r5CBv0qo6RsSScZA3iTfXTLP+jeWIy88xwtaeHg7vPJ4vwjAcB/li+L24XHp3bqunGRYk/QLiPrsedH8sDZJxQ3cxHo/MsKAV3PiONhevjzZnShmebs3tYSJ+mbmmdjI3N1uCrjS6PZYmvxw32O3LeMKsnICTtFmyjE0iY/OPYwU3+t4M9FLootoIVnDj19+dVY4mAzQDGUxmayWXZKYiNoQd3BjJGtD7URGbww5uyBrQexnQBo9SC3YulbMcpJS5itgs1o2ACMb/XG61KnGwcnRCZewONYHjBZjOuDAZZyriizjFk1EPp68DL6u96jIONP3mlZyjyKgWxqtZhMuoh9PXMzJkpJJ6XHRVxCQAToDyAQ61MFIBnHLKyjibqojJUKQjr13t5wkRP33kkqKYjqMS4UY9nGK+R+t+zno0ek19mafRsWy12jIRmaStz2R5XplZ24tTv3kxH19CGqfSw2nB8sxWFVr8kWVmvnsWbFmhkf3cjD755PvuuBAlVHWHKuKTka9wAlFln6iKQsvCXug/0hP9/uyVtxakWhiIpeim9sqoN1NCRrrSH9v69jYd1157Qw+nkLOw+IVRrFQuI1PLj12Cb8lVS89BdasiAsbShooX436vWEamsjg3de+unKP7hKMWBuJbXIN2YbxSKiOzMbrabI7u91fZrXGuqcOIb3k1IbMAtFRGpgSu04453uY+48NRC8PArIrhwuw+I5SR3hg77l425zy8QfZl0cOpxbhEVe+V+WKZjEwlP0+VxWX+BOJmoybYEJQps2dNH5GMzHT3lh8+k3fi1MKgkHaLvlgVM1pSGenpbs1sEjPyrwk2NCWKepvHVKGMdKVi4s0IJngyqoXBUKJYInWuFMhIb4wdmbMdeXD0cMoin4wnauD9MtIbI+uDwwCv+C3BZjcYzIelri9/BFw16EUWahyN1v3VzeLrmM5UsYz0xijsrvBcUh8Wxm12dncqJIL2h6/wiXTEWwZeGemN0T4r0dwPOLPDQ7bHIqslixEhU6UlkJGe7S4fHGIyMyyM517ZTXSfbO+Hw2nT3420Nsp0XPPISG+Mbh8coj3coXlXHHkGZf4/m+JR4LzhwxjlJDuWeQOPjOTGKDzekICTa4K2R/tZhbDZMrzU1uiIHdm4ZaQ3xpCmQ0DGBAsVgVqS3SYPYVTgoVQ/GaeM9MYY1CUTyDgLeZ9amEIjd9jgB1OLXqnJ4pKR3hhlPjgO6A4IeqM6QHdNmvyVUeMcbVElN8YSxxuKpGVEfsNrg6sqNdCxjjjkxij0wbG8kYwNHsFIV1wcg4PeGEO78SUtI3bjN/jBZG5FGYOAlZFODAnucKp7I0XJ3FEbTkY6pUDqg+NJWkaUw9dkcJtrBC0+5nAykikFPf/7+UhaRphwsm3yc9lo40JoPTIyku/LpPmXIm0ZCx2bbYrlaOq1cqet3aFlJDfGEB/ck8RlbE1zR07j6UKuqLFESFJGemOM0vg7dRlbrfbXV/PBF25zvOFv9EzKSG6MpbwKLOnL+BJ8rRJ9QlIykhtjmA/uicpI481SdQtJdX2nNsZAH9wTlZFGkPnvElKY5epJ85ejMjK4d8e7kKwKQhlLBb9cqIwcoquNf8yiKJMx2Af3hJJxMp3f/rwdDI2ge/v+UHfjMgG+ClyHzMl0N8iYDzmv96R4I/xFfob515jtyPdvDyAVA5VjUarqkbYXRDJGOt60SBmnuKIcNtnADZAZG3GA/SEer94Ps4HdgKZMU9gmkqlKB24qwFShaXG3j2wEgm4bVU4bEF5vJFuaSmSUpfnLMGWc2rcfUe4jvOPK3f6AlT4eY/j8bdznhv05VBN6WkbcRsTWEV+nrn5HRajjgnDDSGQMytowwDJO6OoOYKTgEHI5kWCaPTtYFYJkKT/k5xBNr5nZiCvCmC9ro19ISA+tpeyKI+FOEy2q0Q44hoxTrpEc0BEuWPQQQaWfQwx6RgzZz7EmFiMjvolirpo714PlGIuqqVw61llFJGM0cwMPCN+aE8w7tKKRWYfwnsjzj+COc5cvr2TqyMmI74XhXxNeUkNTBoQFVcz5KDupxjL+xTU5B/QryJxIsKbuir/KPsf4XXAyGg1h4LKKl9RdK5SlyPAw11Wh3RjtrCotrVr8qtGvnViy4HQFqsg+xogqsjLixAC4cqIldRYjvLWWTEhjYknvKseyHKUygnkHDQXCJgNL5rb8B+FllZcRT7ri2+ElNU6q+bgvEBInYoivnMcINpYpdFy8BCYBEwkyQOVD+Q/C05GXkdsC4W8sXobyUlDPAQlCyEj/FEIzG+8QozsYTvdDq6o8OCvAiWAZCbBYa9v1Qd35/utrvzPtR2TEOGQ0Vk/7BXHzPvxCoowaQsYefXMjQiIOMbrPAjlt49wKftlwOlo2x5R5zPhdFM6hA7Y+4Ax2ykhNPFzzJ+7tneWfZ2mF5gMlI1NoJzwtzpYR3jpGNyjg5ohqspguACAXmqm4Agj8nB/eenDKSGiGlD1YrwjEs0dCPSgZOa9QjGMONqSxlYUmEBxGKIlxpAdrahc9AF9j+GvQNrdlXkRcv0T258D8qdRxX9MpZAc8kZSRSbiLkVMFZTSzzybcqMD9D4sFpzA2KuEQm6sdWr+ZF1GqoN/gAU/Pmm5Pj+kdLgfIQcvI5BRE8ALAkbCMLDi8A+4BbCKAGYzFgjKan4PGn3kRJSNy5nQnhqo1wfsDgK+bkZEJfonv/LM4w8Zwd8TOMPAAWgXBPDVsEZeM6OzLvIhcI5EzJ5pH3Ifd9vsG8MkwMra+6VcGp8c5ZYSbFh5GOB3hrAPCG9PBKeOcedAnI9ttq967ycxZBdgOnIzcTyA0WdUpI5x0eBihwHAPBMNq7E1OGbkHvTK2meKwNRfCo3UEZxxWRqbiTmiww52Lw8rI7KlgTTVXtXpkZCpu17mk5tC3FIvHeRmZstS13jbmZYTDVyyfYK9yxJxiykiW3I7iEXdDmvLFK3gZOXdrnXf/eRnhqaQ4zBR7XNd8q7pkxCb/jQbuJZN7XPGwQ0YudTJWJY5yMk6pYSsMACsSWZuMdtHtJirDkHPK+XBxAGIslhAvQGUZ4XR8bEVgTbUmRIMyNlFCh5Jx4Xy4kJHZHkOCHdVlhEN/P5UWa6odwGpyUY1xwhm7E56olVFicOQwZT4DMh6rywgd5PfwR9f8A6AuGcl22xFcOP3LyWUEUO4Y4Bt3y8hUGws45lSXEQ7g7UAD1lTbodmowRHuUM0XPi61nznhgFCFR0amKG71YEeAjFZy+Nzx5IbN/+Dwxn3RZG4W06EK8FyfjMyFrcrBjgAZoQstf7RYU4mM/HpkZBs1BS6rxTXVnt2ckYlywK3NJyNXJKJqOYcQGY0cuGJ9o46KtciIltQZCj+Hhf5RROl0hpPkm8sCgIafV0buHmzFnI4QGY0qnjv4D4s6ZMStYb5QvCMoEce+M95b3ZpT9/i4sSeJwzi/cL2TqgU7gmQEk6HbBoYk5UWpQ0YU39gZ/w5Ji5Pl/DtkEvTh4C76VAp2BMkIXz0tzHCyLFgNMuJoY9ucndWX1TKNjZ4sPe9gnUKZmFWlY06YjMAjNyvWVDLjPr6MxpLaMrPAqjvIxT1UAXiyETLa6jBzvkqwI0xG6JErBtV34zSSjChnY8f/rTRVJqPhgbHfomN/C+4+cwUvQKCMOAnyBn24iC4jyot7zLyJPUPLU6Jx41Mk71UcShsmpaNCsCNQRruNIFdqMbaMjGCkuOUo0WXsiXkusWUkXbRcVk/pC6yBMlIuTXrsYsuIls+N/+9ySrT8e+C/psrYg8wuXDqnI1RGezoy0b7IMqKfD3Q34FlaISenwmS0XaGWjMz8YmJWpYMdoTLaPXaZkYsrIw4yoo9Ey2qF0KPwfjGYOoISDuxFDe44VfKYEywjLovB15KOKyMKMhpLp+sxCeMyXY2zKzbEexrqOCYXVw2rXLAjWEZzOnKn/Kgysktqy5qpVXzkS8m91Bt29YYcLCOp9APuXFzKCxAuo5FEwflOYsroKXyDRa4UehyvRVZHpy8pNuZ2k3JFBksFO8JlxGdD1iMdUUYcZCS+2NbzuIjln+/IeuRERDL2fNOK69xaJtgRQUY0N9g1LKKMuPANMdvCl9UbyzNZBPWmIdMO98ZDxuOfYG3s92hKOMl3oEae/Sh40OHZgpODPRlOXR/EPTilPn+CCvuRzoYDegr/zQUsf/s9c4FdrM4edb57vd6pv45UoKEZiFTHf43xaDQ6Z/HG9WgUr7ZUUsB4Uc1XYJT6ABaHlfKvvAvQHRde20t5EdD5lWi3csUPOOAk2axckUDerFLeDLgzNtrUTYmJpPa4kjpsyRXljUAZVbozvhHQpkCB9n/VD/dPsr9u9nf3N+qH0shNbSUWuedtO9gNzEKser55J7hOHU1Uv1BiQd/RVpPxzWBu9zbbB1wJhb5qryq+F3YpoYyBqvhekGtqtG4XSkMQzcjo/plK0kx2yOZnmtIq6dP+GuYNbnfDvWqoKIqiKIqiKIqiKIqiKIqiKIqiKIqiKMrraLVa/wcy6k4dHqDAvwAAAABJRU5ErkJggg==";

// ════════════════════════════════════════
// 기본 카테고리 정의
// ════════════════════════════════════════
const DEFAULT_CATEGORIES = [
  {id:"파업",    icon:"✊", color:"#B45309", kw:["파업","총파업","파업 돌입","전면파업","쟁의행위","노조 파업","항만 파업","물류 파업"]},
  {id:"화재",    icon:"🔥", color:"#C8001A", kw:["화재","산불","방화","불길","연소","화염","공장 화재","산업단지 화재","정유공장 화재","석유화학 화재","대형 화재"]},
  {id:"홍수",    icon:"🌧️", color:"#2C5BA0", kw:["홍수","침수 피해","범람","물난리","폭우 피해","수해","집중호우","호우경보","제방 붕괴"]},
  {id:"지진",    icon:"🌍", color:"#92400E", kw:["지진","강진","여진","진앙","진도"]},
  {id:"쓰나미",  icon:"🌊", color:"#0369A1", kw:["쓰나미","지진해일","해일","海啸","津波"]},
  {id:"태풍",    icon:"🌀", color:"#5B5B8A", kw:["태풍","폭풍","사이클론","허리케인"]},
  {id:"가뭄",    icon:"💧", color:"#0E7490", kw:["가뭄","물 부족","용수 부족","급수 제한","제한급수","단수","저수율","干旱","缺水","渇水","水不足"]},
  {id:"폭염",    icon:"🌡️", color:"#C2410C", kw:["폭염","이상고온","열파","불볕더위","찜통더위","高温","热浪","猛暑","熱波"]},
  {id:"한파",    icon:"❄️", color:"#1D4ED8", kw:["한파","폭설","대설","혹한","결빙","한랭","寒潮","暴雪","寒波","大雪"]},
  {id:"전쟁",    icon:"⚔️",  color:"#A21B2B", kw:["전쟁","공습","포격","침공","군사작전","전투","폭격","이란 전쟁"]},
  {id:"무역 규제",  icon:"🚫", color:"#1E3D6B", kw:[
    "관세","무역분쟁","관세폭탄","관세 부과","무역전쟁","보복 관세","관세 보복","통상 보복","무역 보복",
    "수출 금지","수출 중단","수출 통제","수출 규제","수출 제한","수출금지","수출중단","수출 통제",
    "수출 심사","수출 관리","수출 허가","수출허가 제한","수출 통제 강화","수출 제한 조치",
    "수입 금지","수입 규제","수입 제한","수입 통제",
    "전략물자","화이트리스트","화이트 리스트","백색국가","개별허가","포괄허가",
    "자원 무기화","원자재 무기화","경제 제재","제재 조치","교역 중단","통상 규제",
    "희토류 규제","희토류 수출","반도체 규제","반도체 수출","소재 규제","소재 수출",
    "갈륨 수출","게르마늄 수출","갈륨 규제","게르마늄 규제","안티몬 규제","텅스텐 규제",
    "불화수소 규제","불산 규제","포토레지스트 규제"
  ]},
  {id:"해상 봉쇄",  icon:"⚓", color:"#0E6E6E", kw:["봉쇄","해상 봉쇄","해상 봉쇄","해협 봉쇄","호르무즈","호르무즈 봉쇄","말라카 해협","수에즈 운하","수에즈 봉쇄","해상 물류","해운 차질","항로 차단","선박 통행","해적","나포"]},
  {id:"환율",    icon:"💱", color:"#047857", kw:["환율 급등","환율 급락","달러 환율","외환위기","환율 충격"]},
  {id:"부도",    icon:"📉", color:"#9F1239", kw:["부도","도산","파산","법정관리","기업회생","경영난","워크아웃","破产","倒闭","倒産","経営破綻"]},
  {id:"화산",    icon:"🌋", color:"#B45309", kw:["화산","용암","분화","분출","화산재"]},
  {id:"우크라이나",icon:"🇺🇦",color:"#2C5BA0",kw:["우크라이나","키이우","젤렌스키","러시아군"]},
  {id:"이스라엘", icon:"🇮🇱",color:"#234E84",kw:["이스라엘","가자지구","하마스","팔레스타인","네타냐후"]},
  {id:"폭발",    icon:"💥", color:"#C8001A", kw:["폭발","폭발 사고","공장 폭발","가스 폭발","폭발음","폭발 화재","폭탄","폭파","폭발물","테러 폭발","유증기 폭발"]},
  {id:"산업 안전", icon:"🦺", color:"#475569", kw:[
    "가스 누출","화학물질 누출","유독가스","유해물질 누출","약품 누출","질소 누출","암모니아 누출","염소 누출","불산 누출",
    "건물 붕괴","공장 붕괴","구조물 붕괴","교량 붕괴","천장 붕괴",
    "선박 침몰","좌초","산업재해","중대재해","질식 사고","감전 사고",
    "방사능","방사능 누출","원전 사고","원자력 사고"
  ]},
  {id:"정전",    icon:"⚡", color:"#A16207", kw:["정전","전력난","전력 부족","블랙아웃","단전","전력 대란","전력 위기","限电","停电","停電","電力不足","計画停電"]},
  {id:"사이버",  icon:"🛡️", color:"#0F766E", kw:["사이버 공격","랜섬웨어","해킹","데이터 유출","전산 마비","网络攻击","勒索软件","サイバー攻撃","ランサムウェア"]},
  {id:"감염병",  icon:"🦠", color:"#6D28D9", kw:["팬데믹","감염병","전염병","대유행","봉쇄령","검역","조류독감","구제역","疫情","封城","感染症","パンデミック"]},
  {id:"공급망 위기",icon:"🔗", color:"#13294B", kw:[
    "공급망 위기","공급망 충격","공급망 차질","공급망 마비","공급망 붕괴","공급 쇼크","공급 차단","공급 중단",
    "원자재 폭등","원자재 부족","원자재 대란","원자재 수급","원자재 가격","원료 수급","원료 부족",
    "황산 수출","황산 금지","황산 부족","유황 부족","유황 수급",
    "불화수소","불산","포토레지스트","감광액","플루오린 폴리이미드","EUV 레지스트",
    "네온가스","네온 가스","크립톤","크세논","헬륨 수출","헬륨 부족",
    "갈륨","게르마늄","안티몬","텅스텐","팔라듐","니켈 부족","코발트 부족",
    "슬러리 수급","CMP 수급","특수가스 수급","웨이퍼 수급","타겟 수급",
    "photoresist","hydrogen fluoride","specialty gas shortage","sputtering target","NF3","WF6","SiH4","TEOS","TMAH","HCDS",
    "가스 공급",
    "반도체 소재","반도체 원자재","반도체 원료","소재 수급","소재 부족","소재 대란"
  ]},
  {id:"이란",    icon:"🇮🇷",color:"#6B2B5A",kw:["이란","테헤란","호메이니","이란 제재","이란 공습","페르시아만"]},
];

function loadCategories(){
  try{
    const s=localStorage.getItem("disasterCats_v2");
    if(s){
      const saved = JSON.parse(s);
      // ★ 마이그레이션: 새로 추가된 카테고리가 없으면 자동 병합
      const newCatIds = ["무역 규제","해상 봉쇄","공급망 위기","이란","산업 안전","쓰나미","가뭄","폭염","한파","정전","사이버","감염병","부도"];
      const savedIds = new Set(saved.map(c=>c.id));
      // "관세" → "무역 규제" 마이그레이션
      const oldTariff = saved.findIndex(c=>c.id==="관세");
      if(oldTariff>=0 && !savedIds.has("무역 규제")){
        const upgraded = DEFAULT_CATEGORIES.find(c=>c.id==="무역 규제");
        if(upgraded){
          // 기존 관세 키워드 유지 + 신규 키워드 병합
          const mergedKw = [...new Set([...saved[oldTariff].kw, ...upgraded.kw])];
          saved[oldTariff] = {...upgraded, kw:mergedKw};
          savedIds.add("무역 규제");
        }
      }
      newCatIds.forEach(id=>{
        if(!savedIds.has(id)){
          const def = DEFAULT_CATEGORIES.find(c=>c.id===id);
          if(def) saved.push(JSON.parse(JSON.stringify(def)));
        }
      });
      // ★ 폭발 카테고리 키워드 백필 — 속보성 '폭발'/'공장 폭발'/'가스 폭발' 단독 표현 누락 방지
      // (기존 저장본은 옛 키워드 5개만 가지고 있어 1차 필터에서 누락되므로 신규 키워드 병합)
      const explosionBackfill = ["폭발","공장 폭발","가스 폭발","폭발음","폭발 화재"];
      const expIdx = saved.findIndex(c=>c.id==="폭발");
      if(expIdx>=0){
        saved[expIdx].kw = [...new Set([...saved[expIdx].kw, ...explosionBackfill])];
      }
      // ★ 표준 순서로 재정렬 — detectCategory가 first-match-wins이라 신규/복귀 사용자 간 분류 결정성 보장
      const order = new Map(DEFAULT_CATEGORIES.map((d,i)=>[d.id,i]));
      saved.sort((a,b)=>(order.has(a.id)?order.get(a.id):999)-(order.has(b.id)?order.get(b.id):999));
      return saved;
    }
  }catch(_){}
  return JSON.parse(JSON.stringify(DEFAULT_CATEGORIES));
}
function saveCategories(){
  try{localStorage.setItem("disasterCats_v2",JSON.stringify(CATS));}catch(_){}
}
function resetCategories(){
  if(!confirm("카테고리와 키워드를 기본값으로 초기화하시겠습니까?"))return;
  CATS=JSON.parse(JSON.stringify(DEFAULT_CATEGORIES));
  saveCategories(); renderKwModal();
}
let CATS=loadCategories();

// ════════════════════════════════════════
// ★ 뉴스 DB — localStorage 누적 저장
// ════════════════════════════════════════
const DB_KEY = "disasterNewsDB_v1";
const DB_MAX = 1200;          // 브라우저 캐시 한도. 정본은 서버다 —
                              // 예전 3000 은 요약을 빼고도 1.2MB 라 한도를 넘겨
                              // 저장이 통째로 실패하고 이력이 얼어붙었다.
const DB_TTL = 90 * 24 * 60;  // 90일(분) — 누적 보관 기간

// ── 공통 수집 이력 ────────────────────────────────────────────
// 브라우저 저장은 **이 기계의 캐시**다. 정본은 서버다.
// 수집은 각자의 브라우저가 하지만(사내 게이트웨이가 서버의 뉴스 접근을 막는다),
// 모은 것은 서버로 올라가고 **누가 접속해도 같은 이력을 본다.**
//
// 서버가 주는 것은 이미 **선별을 통과한 것**뿐이다 —
// 재난·재해와 무관한 것은 signals/dropped/ 로 빠져 여기 오지 않는다.
// 오탐 더미가 화면에 쌓이던 문제가 거기서 끊긴다.
async function pullServerSignals(){
  try{
    const d = await fetch("api/signals?days=3").then(r=>r.ok?r.json():null);
    if(!d || !Array.isArray(d.signals) || !d.signals.length) return;
    const have = new Set(allNews.map(n=>String(n.id||n.link||"")));
    const add = d.signals.filter(x=>{
      const k = String(x.id||x.link||"");
      return k && !have.has(k);
    });
    if(!add.length) return;
    allNews = [...allNews, ...add];
    saveNewsDB(allNews);
    renderNews(); updateKpis();
    setStatus(`서버에서 공통 이력 ${add.length}건을 받았습니다`, false);
  }catch(e){ /* 서버가 없어도 화면은 돈다 */ }
}

function loadNewsDB(){
  try{
    const raw = localStorage.getItem(DB_KEY);
    if(!raw) return [];
    const parsed = JSON.parse(raw);
    const cutoff = Date.now() - DB_TTL * 60 * 1000;
    return parsed.filter(n => {
      const t = new Date(n.pubDate).getTime();
      return isNaN(t) || t > cutoff;
    });
  }catch(_){ return []; }
}

// 저장할 때 **이력이 실제로 쓰는 칸만** 남긴다.
// summary 는 이력 화면에 안 나오는데 가장 긴 칸이라, 넣어 두면 한도를 그만큼 빨리 먹는다.
function slimForDB(n){
  return {id:n.id, title:n.title, titleKo:n.titleKo||undefined, link:n.link,
          source:n.source, pubDate:n.pubDate, category:n.category,
          official:n.official||undefined, corroboration:n.corroboration||undefined,
          severity:n.severity||undefined, confidence:n.confidence||undefined};
}

function saveNewsDB(news){
  // 브라우저 저장은 '빠른 캐시' 다. 정본은 서버다 — pushSignalsToServer() · syncFromServer().
  //
  // **여기가 조용히 멈추던 자리다.** 예전에는 한 번 시도하고, 넘치면
  // console.warn 만 남기고 끝냈다. 그러면 이력이 그 시점에 얼어붙는데
  // 화면에는 아무 말도 없어서, 며칠 지나 "8/19 이후로 안 늘어난다" 로 발견된다.
  // 3,000건이면 요약을 빼고도 1.2MB 라, 같은 오리진의 BP 명부와 합쳐 한도를 넘는다.
  const sorted = [...news].sort((a,b)=>new Date(b.pubDate)-new Date(a.pubDate)).map(slimForDB);
  for(const n of [DB_MAX, 600, 300, 120, 40]){
    try{
      const cut = sorted.slice(0, n);
      localStorage.setItem(DB_KEY, JSON.stringify(cut));
      updateDbBar(cut);
      setCacheNote(n < sorted.length ? n : 0, sorted.length);
      return cut.length;
    }catch(e){ /* 용량 초과 — 더 줄여서 다시 */ }
  }
  // 여기까지 왔으면 브라우저 저장은 못 쓴다. **숨기지 않는다.**
  setCacheNote(-1, sorted.length);
  console.warn("로컬 캐시 저장 실패 — 서버 기록은 그대로입니다");
  return 0;
}

// 캐시가 줄었거나 못 썼으면 이력 화면에 그대로 적는다.
function setCacheNote(kept, total){
  const el = document.getElementById("arcCache");
  if(!el) return;
  if(kept === 0){ el.hidden = true; return; }
  el.hidden = false;
  el.textContent = kept < 0
    ? `[1011] 이 브라우저에는 저장하지 못했습니다(저장 공간 부족) — 화면은 서버 기록으로 채웁니다.`
    : `[1010] 이 브라우저에는 최근 ${kept}건만 둡니다(공간 한도). 그 이전은 서버가 갖고 있습니다.`;
}

// ── 수집분을 서버에 쌓는다 ───────────────────────────────────
// 수집은 브라우저가 한다(사내 서버는 뉴스에 못 닿는다). 그러나 모은 것이
// 이 브라우저에만 남으면 기계를 바꾸는 순간 사라지고 남이 못 본다.
// 모으는 쪽은 그대로 두고 **두는 곳만 서버로** 옮긴다.
// 실패해도 수집을 막지 않는다 — 화면은 계속 돌고, 다음 주기에 다시 보낸다.
async function pushSignalsToServer(news){
  if(!Array.isArray(news) || !news.length) return null;
  const slim = news.map(n => ({
    id: n.id || n.link, link: n.link, title: n.title, titleKo: n.titleKo || "",
    summary: n.summary || "", source: n.source || "", pubDate: n.pubDate,
    category: n.category, severity: n.severity, confidence: n.confidence,
    official: !!n.official, corroboration: n.corroboration || 0,
  }));
  try{
    const r = await fetch("api/signals/ingest", {
      method:"POST", headers:{"Content-Type":"application/json"},
      body: JSON.stringify({ signals: slim, source: "browser" })
    });
    if(r.status === 401){
      // 조용히 지나가면 안 된다 — 탭을 열어 두면 모으고 있다고 믿게 된다.
      // 실제로는 이 브라우저에만 쌓이고 판정은 한 건도 안 돈다.
      setServerSaveState("[2001] 서버 저장 안 됨 · 로그인 필요", false, "login");
      return null;
    }
    const d = await r.json();
    if(d.ok){
      // LLM 에 못 닿았으면 그 말을 한다. "판정 0건" 만 보이면 "오늘 걸릴 게 없었다"
      // 와 구별할 수 없고, 그 상태로 며칠이 지나간다.
      if(d.llmDown) setServerSaveState(`서버 ${d.dayTotal}건 · [4010] 판정 멈춤`, false, "", "LLM 에 닿지 않아 판정이 서 있습니다");
      else setServerSaveState(`서버 ${d.dayTotal}건`, true);
      syncFromServer();   // 올린 것을 바로 되읽어, 다른 자리에서 올린 것도 같이 들어온다
      return d;
    }
    setServerSaveState("[2003] 서버 저장 실패", false, "", "서버가 보낸 모양을 못 읽었습니다"); return null;
  }catch(_){
    setServerSaveState("[2002] 서버 저장 실패", false, "", "서버가 응답하지 않습니다 — 다음 회차에 다시 시도합니다");
    return null;
  }
}
function setServerSaveState(text, ok, link, why){
  const el = document.getElementById("srvSave");
  if(!el) return;
  el.className = "srv-save" + (ok ? " on" : "");
  el.textContent = text;
  if(why) el.title = why; else el.removeAttribute("title");
  if(link === "login"){
    el.textContent = text + " ";
    const a = document.createElement("a");
    a.href = "login?next=" + encodeURIComponent(location.pathname);
    a.textContent = "로그인 →";
    el.appendChild(a);
  }
}

function mergeWithDB(newItems){
  const existing = loadNewsDB();
  // ★ BugFix: isNew 플래그 초기화 — 기존 뉴스에서 isNew 제거
  existing.forEach(n => { delete n.isNew; });
  const existingIds = new Set(existing.map(arcKey));  // ★ dedup 키를 arcKey(링크 기준)로 통일 — 서버 병합분(id="srv:")과 로컬 수집분 중복 방지
  const added = [];
  newItems.forEach(n => {
    const k = arcKey(n);
    if(!existingIds.has(k)){
      n.isNew = true;
      added.push(n);
      existingIds.add(k);
    }
  });
  const merged = [...added, ...existing]
    .sort((a,b) => new Date(b.pubDate) - new Date(a.pubDate))
    .slice(0, DB_MAX);
  saveNewsDB(merged);
  return { merged, addedCount: added.length };
}

function clearNewsDB(){
  if(!confirm(`저장된 뉴스 데이터를 모두 삭제하시겠습니까?\n(현재 ${loadNewsDB().length}건)`)) return;
  localStorage.removeItem(DB_KEY);
  allNews = [];
  updateDbBar([]);
  renderNews();
  if(activeView==="archive") renderArchive();
  setStatus("수신 이력을 초기화했습니다.", false);
}

function updateDbBar(news){
  const arr = news || loadNewsDB();
  document.getElementById("dbCount").textContent = arr.length;
  if(arr.length === 0){
    document.getElementById("dbOldest").textContent = "";
    document.getElementById("dbOldestWrap").hidden = true;   // 값이 없으면 구분점도 숨긴다
    return;
  }
  const oldest = arr.reduce((a,b)=>new Date(a.pubDate)<new Date(b.pubDate)?a:b);
  const d = new Date(oldest.pubDate);
  if(!isNaN(d)){
    document.getElementById("dbOldest").textContent =
      `최초 수집: ${d.toLocaleDateString("ko-KR")} ${d.toLocaleTimeString("ko-KR",{hour:"2-digit",minute:"2-digit"})}`;
    document.getElementById("dbOldestWrap").hidden = false;
  }
  updateArchiveBadge(arr.length);
}

// ════════════════════════════════════════
// 누적 보관 (아카이브) — 로컬 누적 + 서버 스냅샷 병합 · 비용 0
// ════════════════════════════════════════
function arcKey(it){ return (it.link||"").trim() || ((it.title||"")+"|"+(it.source||"")); }

function updateArchiveBadge(n){
  const b=document.getElementById("navArchive");
  if(b) b.textContent = n ? (n>999 ? (Math.floor(n/100)/10)+"k" : n) : "";
}

// ── 이력 — **재난재해를 주제별로 묶는다** ───────────────────
// 예전에는 수집한 기사를 한 줄씩 2,000건 늘어놓았다. 그러면 재난과 무관한
// 수집분까지 전부 보여, 보는 사람이 "이 시스템이 이런 것도 재난이라고 하나"
// 로 읽는다. 신뢰가 거기서 깎인다.
//
// 궁금한 것은 **수집을 몇 건 했나가 아니라 어떤 재난이 있었나**다.
// 그래서 재난으로 분류된 것만 주제로 묶고, 기사는 그 안에 접어 둔다.
function archiveTopics(rows){
  // 같은 사건의 여러 기사를 하나로 묶는다 — 범주 + 지역 + 날짜.
  // 제목으로 묶으면 매체마다 표현이 달라 안 묶이고, 범주만으로 묶으면
  // 한 달치 '화재' 가 한 덩어리가 된다.
  const bucket = new Map();
  rows.forEach(n => {
    const cat = n.category || "기타";
    const day = String(n.pubDate || "").slice(0, 10);
    const place = (n.region || n.admin1 || "").trim();
    const key = `${cat}|${day}|${place}`;
    if(!bucket.has(key)) bucket.set(key, {cat, day, place, items: []});
    bucket.get(key).items.push(n);
  });
  return [...bucket.values()].sort((a, b) =>
    String(b.day).localeCompare(String(a.day)) || b.items.length - a.items.length);
}

function renderArchive(){
  // 서버가 원장이고, 이 브라우저 캐시는 방금 모은 것(아직 안 올라갔을 수 있다)이다.
  // 둘을 합쳐 그린다 — 어느 쪽에만 있어도 보여야 한다.
  const seen = new Set();
  const all = [];
  [...SERVER_ROWS, ...loadNewsDB()].forEach(n => {
    const k = arcKey(n);
    if(!seen.has(k)){ seen.add(k); all.push(n); }
  });
  // **재난으로 분류된 것만** 남긴다. '기타' 는 재난이라 보지 않은 것이다.
  const disasters = all.filter(n => n.category && n.category !== "기타");

  const catSel = document.getElementById("arcCat");
  if(catSel){
    const cats = [...new Set(disasters.map(n => n.category))].sort();
    const cur = catSel.value;
    catSel.innerHTML = `<option value="">전체 주제</option>`
      + cats.map(c => `<option value="${esc(c)}">${esc(c)}</option>`).join("");
    if(cur && cats.includes(cur)) catSel.value = cur;
  }
  const q = (document.getElementById("arcSearch")?.value || "").trim().toLowerCase();
  const cat = document.getElementById("arcCat")?.value || "";
  const sort = document.getElementById("arcSort")?.value || "new";

  let rows = disasters.filter(n => {
    if(cat && n.category !== cat) return false;
    if(q){
      const hay = ((n.title||"")+" "+(n.titleKo||"")+" "+(n.source||"")).toLowerCase();
      if(!hay.includes(q)) return false;
    }
    return true;
  });
  let topics = archiveTopics(rows);
  if(sort === "old") topics.reverse();

  const rangeEl = document.getElementById("arcRange");
  if(rangeEl){
    if(disasters.length){
      const ds = disasters.map(n => new Date(n.pubDate)).filter(d => !isNaN(d)).sort((a,b)=>a-b);
      rangeEl.textContent = ds.length
        ? `${ds[0].toLocaleDateString("ko-KR")} ~ ${ds[ds.length-1].toLocaleDateString("ko-KR")} · 재난 ${topics.length}건`
        : `재난 ${topics.length}건`;
    } else rangeEl.textContent = "재난으로 분류된 이력 없음";
  }

  const stats = document.getElementById("arcStats");
  if(stats){
    const byCat = {};
    topics.forEach(t => { byCat[t.cat] = (byCat[t.cat] || 0) + 1; });
    const top = Object.entries(byCat).sort((a,b) => b[1]-a[1]).slice(0, 8);
    stats.innerHTML =
        `<div class="arc-stat"><b>${topics.length}</b><span>재난</span></div>`
      + `<div class="arc-stat"><b>${rows.length}</b><span>기사</span></div>`
      + `<div class="arc-stat dim"><b id="arcDropped">—</b><span>제외</span></div>`
      + (LEDGER_N ? `<div class="arc-stat led" title="2단계에서 재난으로 갈린 것의 이름과 출처. 90일로 잘리지 않고 계속 쌓입니다."><b>${LEDGER_N}</b><span>원장</span></div>` : "");
    // 범주 칩은 **줄을 따로 쓴다.** 지표와 한 줄에 섞어 두면 건수가 바뀔 때마다
    // 칩이 위아래로 올라갔다 내려갔다 해서 자리가 안 잡힌다.
    const chips = document.getElementById("arcChips");
    if(chips) chips.innerHTML = top.map(([c,n]) =>
      `<div class="arc-chip">${esc(c)} <b>${n}</b></div>`).join("");
  }

  const list = document.getElementById("arcList");
  if(!list) return;
  if(!topics.length){
    list.innerHTML = `<div class="arc-empty">재난으로 분류된 이력이 없습니다.<br>
      <b>[수집 실행]</b> 후 서버가 재난·재해로 가린 것만 여기 쌓입니다.</div>`;
    return;
  }

  // "재난 아님" 은 **서버가 센다.** 브라우저는 낱말로 거른 뒤 전부 올리므로
  // 자기가 무엇을 뺐는지 모른다 — 1층 선별은 서버(signal_filter)가 한다.
  fetch("api/signals/funnel?hours=720").then(r => r.ok ? r.json() : null).then(f => {
    const el = document.getElementById("arcDropped");
    if(el && f && f.ok) el.textContent = (f.dropped1 || 0).toLocaleString();
  }).catch(() => {});

  const CAP = 300;
  list.innerHTML = topics.slice(0, CAP).map(t => {
    const co = (CATS.find(c => c.id === t.cat) || {}).color || "#8794A8";
    const lead = t.items.slice().sort((a,b) =>
      (b.severity === "high") - (a.severity === "high"))[0] || {};
    const ds = /^\d{4}-\d{2}-\d{2}$/.test(t.day) ? t.day.slice(5) : (t.day || "—");
    const srcs = [...new Set(t.items.map(x => x.source).filter(Boolean))];
    // 여러 매체가 같은 사건을 말하면 그 자체가 근거의 무게다.
    const weight = srcs.length >= 2
      ? `<span class="arc-weight">${srcs.length}개 매체</span>` : "";
    return `<details class="arc-topic">
      <summary>
        <span class="arc-date tnum">${ds}</span>
        <span class="arc-cat" style="background:${co}14;color:${co};border-color:${co}40;">${esc(t.cat)}</span>
        <span class="arc-topic-t">${esc(cleanTitle(lead.titleKo || lead.title || ""))}</span>
        ${t.place ? `<span class="arc-place">${esc(t.place)}</span>` : ""}
        ${weight}
        <span class="arc-n">기사 ${t.items.length}</span>
      </summary>
      <div class="arc-srcs">
        ${t.items.map(n => `<a class="arc-link" href="${safeUrl(n.link) || "#"}"
            target="_blank" rel="noopener">
            <span class="arc-src">${esc(n.source || "")}</span>
            ${esc(cleanTitle(n.titleKo || n.title))}</a>`).join("")}
      </div>
    </details>`;
  }).join("")
  + (topics.length > CAP
      ? `<div class="arc-empty">상위 ${CAP}건 표시 · 주제·검색으로 좁혀 보세요(전체 ${topics.length.toLocaleString()}건)</div>`
      : "");
}

function exportArchive(fmt){
  const all=loadNewsDB();
  if(!all.length){ showToast("내보낼 수집 이력이 없습니다"); return; }
  const stamp=new Date().toISOString().slice(0,10);
  let blob,name;
  if(fmt==="json"){
    blob=new Blob([JSON.stringify(all,null,2)],{type:"application/json"});
    name=`mGSCS_누적_${stamp}.json`;
  }else{
    const head=["수집일시","카테고리","출처","제목","번역제목","신뢰도","링크"];
    const q=s=>`"${String(s==null?"":s).replace(/"/g,'""')}"`;
    const lines=[head.join(",")].concat(all.map(n=>[n.pubDate,n.category||"",n.source||"",n.title||"",n.titleKo||"",n.confidence?(CONF_LABEL[n.confidence]||n.confidence):"",n.link||""].map(q).join(",")));
    blob=new Blob(["﻿"+lines.join("\r\n")],{type:"text/csv;charset=utf-8"});  // BOM — Excel 한글 깨짐 방지
    name=`mGSCS_누적_${stamp}.csv`;
  }
  const a=document.createElement("a"); a.href=URL.createObjectURL(blob); a.download=name; a.click();
  setTimeout(()=>URL.revokeObjectURL(a.href),1000);
  showToast(`${all.length}건 내보냈습니다`);
}

let SYNC_AT = 0, SYNC_BUSY = false;
// 서버가 준 90일치. 메모리에만 둔다 — 이력 화면이 이것과 로컬 캐시를 합쳐 그린다.
let SERVER_ROWS = [];
// 재난 원장에 영구로 쌓인 건수 — 90일 창과 달리 줄지 않는다.
let LEDGER_N = 0;

async function syncFromServer(){
  if(SYNC_BUSY) return 0;
  SYNC_BUSY = true;
  try{
    // **재난 원장이 뼈대다.** 2단계에서 재난으로 갈린 것의 이름과 출처만
    // 영구로 쌓이고, 90일로 잘리지 않는다.
    // 신호(90일)는 그 원장이 생기기 전에 들어온 것을 메우는 용도다.
    const [led, sig] = await Promise.all([
      fetch("api/disasters").then(r => r.ok ? r.json() : null).catch(() => null),
      fetch("api/signals?days=90").then(r => r.ok ? r.json() : null).catch(() => null),
    ]);
    if(!led && !sig) throw new Error("서버 기록을 못 읽었다");
    LEDGER_N = (led && led.count) || 0;
    const d = { ok: true, signals: [
      ...((led && led.rows) || []).map(x => ({
        id: x.link, link: x.link, title: x.title, source: x.source,
        pubDate: x.pubDate || x.at, category: x.category,
        official: x.official, corroboration: x.corroboration, _led: 1,
      })),
      ...((sig && sig.signals) || []),
    ]};
    // 서버 신호가 비어 있으면 **옛 일일 스냅샷**을 한 번만 훑는다.
    // signals/ 가 생기기 전 기록은 그쪽에만 있다. 매번 하지는 않는다 —
    // 90개 파일을 받아 오는 일이라 값이 싸지 않다.
    if(!(d.signals || []).length && !window._snapTried){
      window._snapTried = true;
      try{ await mergeServerSnapshots(); }catch(_){}
    }
    const mapped = (d.signals || []).map(s => ({
      id: s.id || s.link, title: s.title, link: s.link,
      source: s.source || "서버", pubDate: s.pubDate || s.ingestedAt,
      category: s.category || "기타",
      official: s.official, corroboration: s.corroboration,
      confidence: s.official ? "official" : undefined,
    })).filter(m => m.title && m.pubDate);
    // 원장과 신호에 같은 것이 있으면 원장 쪽을 남긴다(앞에 있다).
    {
      const seen = new Set(); const uniq = [];
      for(const m of mapped){ const k = arcKey(m); if(!seen.has(k)){ seen.add(k); uniq.push(m); } }
      mapped.length = 0; mapped.push(...uniq);
    }

    // 받은 것은 **메모리에만** 둔다 — 90일치를 localStorage 로 밀어 넣으면
    // 한도를 넘겨 저장이 통째로 실패하고, 그때부터 이력이 얼어붙는다.
    // 브라우저 저장소는 '이 자리에서 방금 모은 것' 만 들고 있으면 된다.
    const before = SERVER_ROWS.length;
    SERVER_ROWS = mapped;
    const added = Math.max(0, mapped.length - before);
    SYNC_AT = Date.now();
    setSyncNote(true, mapped.length);
    updateKpis();
    if(activeView === "archive") renderArchive();
    return mapped.length;
  }catch(_){
    setSyncNote(false, 0);
    return 0;
  }finally{ SYNC_BUSY = false; }
}

// **맞춘 시각을 적는다.** 조용하면 '같다' 인지 '안 불러와졌다' 인지 구별이 안 된다.
function setSyncNote(ok, added){
  const el = document.getElementById("arcSync");
  if(!el) return;
  const hhmm = new Date().toLocaleTimeString("ko-KR", {hour:"2-digit", minute:"2-digit"});
  el.className = "arc-sync" + (ok ? "" : " err");
  el.textContent = ok
    ? `서버와 맞춤 · ${hhmm}` + (added ? ` · ${added}건` : "")
    : "[9010] 서버에 못 닿음 — 이 브라우저 기록만 보입니다";
  el.title = ok ? "서버가 원장입니다. 이 화면은 3분마다 서버를 다시 읽습니다."
                : "";
}

async function mergeServerSnapshots(){
  const btn=document.getElementById("arcMergeBtn");
  if(btn){ btn.disabled=true; btn.textContent="가져오는 중…"; }
  try{
    const idxRes=await fetch(MGSCS_DATA+"index.json",{cache:"no-cache"});
    if(!idxRes.ok) throw new Error("index.json 없음");
    const dates=(await idxRes.json()).slice(-90);
    const pulled=[];
    for(let i=0;i<dates.length;i+=8){   // 동시 과다요청 방지 — 8개씩
      const parts=await Promise.all(dates.slice(i,i+8).map(d=>
        fetch(`${MGSCS_DATA}${d}.json`,{cache:"force-cache"}).then(r=>r.ok?r.json():null).catch(()=>null)));
      parts.forEach(p=>{ if(p&&Array.isArray(p.matched)) pulled.push(...p.matched); });
    }
    const mapped=pulled.map(m=>({
      id:"srv:"+arcKey(m), title:m.title, titleKo:m.titleKo||"", link:m.link, source:m.source||"서버 기록",
      pubDate:m.pubDate||m.date, category:m.category||"기타",
      confidence:m.confidence||(m.official?"official":undefined), corroboration:m.corroboration, official:m.official, severity:m.severity
    })).filter(m=>m.title&&m.pubDate);
    const existing=loadNewsDB();
    const seen=new Set(existing.map(arcKey));
    let added=0;
    mapped.forEach(m=>{ const k=arcKey(m); if(!seen.has(k)){ seen.add(k); existing.push(m); added++; } });
    saveNewsDB(existing);
    allNews=loadNewsDB(); renderNews(); updateKpis();   // ★ 병합 결과를 KPI·피드에도 즉시 반영(다음 수집까지 불일치 방지)
    if(activeView==="archive") renderArchive();
    showToast(`서버 ${dates.length}일치 · 새로 ${added}건`);
  }catch(e){
    showToast("서버 스냅샷을 불러올 수 없습니다(로컬 파일 모드 등)");
  }finally{
    if(btn){ btn.disabled=false; btn.textContent="서버 기록 가져오기"; }
  }
}

// ════════════════════════════════════════
// API 키 관리
// ════════════════════════════════════════
// 다크모드(W3) — data-theme 수동 토글(localStorage 유지). 미설정 시 OS prefers-color-scheme 자동
function applyTheme(){
  const t=localStorage.getItem("mgscs_theme");   // "dark"|"light"|null(=OS 자동)
  const root=document.documentElement;
  if(t) root.setAttribute("data-theme",t); else root.removeAttribute("data-theme");
  const dark = t==="dark" || (!t && window.matchMedia&&window.matchMedia("(prefers-color-scheme:dark)").matches);
  const btn=document.getElementById("themeToggle"); if(btn) btn.textContent = dark?"☀️":"🌙";
}
function toggleTheme(){
  const cur=document.documentElement.getAttribute("data-theme");
  const dark = cur ? cur==="dark" : (window.matchMedia&&window.matchMedia("(prefers-color-scheme:dark)").matches);
  localStorage.setItem("mgscs_theme", dark?"light":"dark");   // 현재 반대로
  applyTheme();
}
function getApiKey(){ return localStorage.getItem("anthropic_api_key")||""; }
function getProxyUrl(){ return localStorage.getItem("anthropic_proxy_url")||""; }

// BP 기준 정보(대외비) — localStorage에만 저장, 저장소·서버 미전송
let BP_MASTER=[];
function loadBpMaster(){ try{ const d=JSON.parse(localStorage.getItem("mgscs_bp_master")||"[]"); BP_MASTER=Array.isArray(d)?d.filter(b=>b&&b.bp&&b.grp):[]; }catch(e){ BP_MASTER=[]; } return BP_MASTER; }
// BP 사업장 좌표(대외비) — 지진 근접 매칭용
let BP_SITES=[];
let RGN_SEL = new Set();     // 사용자가 고른 권역
let RGN_HIT = new Set();     // 최근 사건이 걸린 권역
function loadBpSites(){ try{ const d=JSON.parse(localStorage.getItem("mgscs_bp_sites")||"[]"); BP_SITES=Array.isArray(d)?d.filter(c=>c&&c.name&&Array.isArray(c.sites)):[]; }catch(e){ BP_SITES=[]; } return BP_SITES; }
// R7 — 서버가 주입한 resolved 데이터를 대시보드 형태로 바꾼다.
//
// 이 어댑터가 없으면 window.MGSCS_BP_RESOLVED는 선언만 되고 아무도 읽지 않는다.
// 실제로 그런 상태였고, 사내에서는 data/bp/*.json이 없으므로 fetch가 404로
// 떨어져 BP_MASTER·BP_SITES가 통째로 비었다. 지도도 영향 BP도 전부 빈칸이 된다.
// 260611 판이 갖고 있던 증상이 다른 경로로 되돌아온 것이다.
//
// resolved는 site 1행 = 1레코드다. 대시보드는 두 가지 형태를 쓴다.
//   BP_MASTER : [{bp, grp}]                     — 소재군 노출 판정용(BP×소재군)
//   BP_SITES  : [{name, sites:[{lat,lon,…}]}]   — 지리 근접 판정용(회사→사이트)
// 소재군 별칭 → mGSCS 8축. bp_master_canonical의 grpSrc→grp 대응을 그대로 옮겼다.
//
// matchBPs는 b.grp를 판정 결과(8축)와 소문자 완전일치로 비교한다.
// 'P-Gas'는 'gas'와 같지 않고 'NP Wafer'는 'wafer'와 같지 않다.
// 정규화하지 않으면 Gas·Wafer 계열 BP가 영원히 0건으로 잡힌다.
const GRP_ALIAS = {
  "p-gas":"Gas", "np wafer":"Wafer", "prime wafer":"Wafer",
  "웨이퍼(분류 확인 필요)":"Wafer", "pad":"Pad", "photo chemical":"Photo"
};
function normGrp(g){
  const s=String(g||"").trim();
  if(!s) return "";
  return GRP_ALIAS[s.toLowerCase()] || s;
}
function adaptResolved(rows){
  const master=[], byCompany=new Map(), seen=new Set();
  (rows||[]).forEach(r=>{
    if(!r || r.active===false || !r.bpName) return;
    const groups = Array.isArray(r.materialGroups) ? r.materialGroups
                 : (r.materialGroup ? [r.materialGroup] : []);
    groups.forEach(raw=>{
      const g=normGrp(raw);
      if(!g || g==="미지정") return;
      const k=r.bpName+"·"+g;
      if(seen.has(k)) return;            // 같은 BP·소재군이 여러 site로 중복 유입된다
      seen.add(k);
      // reg는 ISO 국가코드 자리다. matchBPs가 REGION 코드(JP·KR·EU·CN·TW·US)와
      // 대조하므로 한글 권역명('영남권')을 넣으면 전부 탈락한다.
      // 유럽 개별국(DE·FR…)은 matchBPs의 EU_ISO 집합이 EU로 등가처리한다.
      master.push({bp:r.bpName, grp:g, reg:r.country||"", tier:r.tier||"", grade:r.grade||""});
    });
    if(r.lat==null || r.lon==null) return;
    if(!byCompany.has(r.bpName)) byCompany.set(r.bpName, {name:r.bpName, sites:[]});
    byCompany.get(r.bpName).sites.push({
      // n·role은 지도 툴팁이 읽는 이름이다. 다른 이름으로 담으면 툴팁이 빈다.
      n: r.siteName || r.admin1Ko || r.admin1 || "",
      site: r.siteName || r.admin1Ko || r.admin1 || "",
      role: r.siteRole || "",
      lat: r.lat, lon: r.lon,
      addr: r.address || "",
      // 권역·도현. 사건이 나면 이 축으로 후보를 넓혀 본다.
      regionCode: r.regionCode || "",
      regionKo: r.regionKo || "",
      admin1: r.admin1Ko || r.admin1 || "",
      grade: r.grade || "",
      grp: normGrp(groups[0]||""),
      // 거리 계산 자격은 서버 판정을 그대로 따른다. 주소 지오코딩·현청소재지
      // 좌표로 반경을 재면 실제와 수십 km 어긋난 근접 경보가 나간다.
      eligible: r.distanceEligible===true,
      prec: r.coordinateStatus || r.precision || ""
    });
  });
  return {master, sites:[...byCompany.values()]};
}

// 저장소 커밋 기본값 seed — localStorage가 비어 있을 때만 채운다.
// 우선순위: 서버 주입(resolved) > 저장소 파일(data/bp/*.json) > 없음.
// ⚠ 대외비 전제: 저장소 private + 배포 사이트(production·preview·*.pages.dev 별칭) 전부 Cloudflare Access 게이팅. public 전환 시 data/bp 즉시 제거.
async function bootstrapBpData(){
  const pull=async(url,filt)=>{ try{ const r=await fetch(url,{cache:"no-store"}); if(!r.ok) return null; const d=await r.json(); return Array.isArray(d)?d.filter(filt):null; }catch(_){ return null; } };

  // 사내 Flask 경로 — 서버가 넣어준 것이 항상 최신이고, 외부 통신이 필요 없다.
  if(Array.isArray(window.MGSCS_BP_RESOLVED) && window.MGSCS_BP_RESOLVED.length){
    const a=adaptResolved(window.MGSCS_BP_RESOLVED);
    if(!BP_MASTER.length && a.master.length) BP_MASTER=a.master;
    if(!BP_SITES.length  && a.sites.length)  BP_SITES=a.sites;
    if(a.master.length || a.sites.length){
      try{ refreshQuakePanel(); if(window._lastScmIssues) renderScmIssues(window._lastScmIssues); }catch(_){}
      renderMap();
      return;   // 서버 주입이 있으면 파일 fetch로 내려가지 않는다(사내망 404 소음 제거)
    }
  }
  let changed=false;
  if(!BP_MASTER.length){ const d=await pull("data/bp/bp_master.json", b=>b&&b.bp&&b.grp); if(d&&d.length){ BP_MASTER=d; changed=true; } }
  if(!BP_SITES.length){ const d=await pull("data/bp/bp_sites.json", c=>c&&c.name&&Array.isArray(c.sites)); if(d&&d.length){ BP_SITES=d; changed=true; } }
  if(changed){ try{ refreshQuakePanel(); if(window._lastScmIssues) renderScmIssues(window._lastScmIssues); }catch(_){} renderMap(); }   // seed 지연 로드 완료 → 영향 BP 칩 재계산
}

// 사이트 좌표 유효성 — null/""/NaN/범위초과 배제(문자열 좌표는 수용). (0,0) 허위 근접경보의 근원 차단
function validSite(s){ if(!s||s.lat==null||s.lon==null||String(s.lat).trim()===""||String(s.lon).trim()==="") return false;   // 공백문자열 좌표도 배제((0,0) 허위경보 방지)
  const la=Number(s.lat), lo=Number(s.lon); return isFinite(la)&&isFinite(lo)&&Math.abs(la)<=90&&Math.abs(lo)<=180; }
function bpSitesLabel(arr){ let tot=0, ok=0; arr.forEach(c=>(c.sites||[]).forEach(s=>{ tot++; if(validSite(s)) ok++; }));
  return ok<tot ? `BP 사이트 ${arr.length}사 · 유효좌표 ${ok}/${tot}개(좌표 필드 확인 필요)` : `BP 사이트 ${arr.length}사·${tot}개 저장됨`; }

// BP JSON(대외비) 저장 헬퍼. 반환: undefined=무변경(빈 입력) / null=오류(덮어쓰기 안 함) / 배열=저장분.
// 빈 입력은 기존 데이터를 보존한다(모달이 원문을 프리필하지 않으므로 실수 삭제·노출 방지). 삭제는 '[]' 명시 입력.
function saveBpJson(elId, stId, key, filterFn, labelFn){
  const el=document.getElementById(elId), st=document.getElementById(stId);
  if(!el) return undefined;
  const raw=el.value.trim();
  if(!raw){ if(st){ st.textContent=""; } return undefined; }
  try{
    const d=JSON.parse(raw);
    if(!Array.isArray(d)) throw new Error("배열이어야 합니다");
    const clean=d.filter(filterFn);
    if(d.length>0 && clean.length===0) throw new Error(`${d.length}건 모두 형식 불일치 — 필드명(bp·grp 등) 확인`);   // 스키마 오류 → 기존 데이터 보존
    localStorage.setItem(key, JSON.stringify(clean));
    if(st){ st.textContent=labelFn(clean); st.style.color="var(--lo)"; }
    return clean;
  }catch(e){ if(st){ st.textContent="JSON 형식 오류: "+e.message; st.style.color="var(--hi)"; } return null; }
}

function saveApiKey(){
  const statusEl=document.getElementById("apiKeyStatus");
  // BP 기준 정보·사이트 저장(API 키와 독립). undefined=무변경, null=오류, 배열=저장
  const bm=saveBpJson("bpMasterInput","bpMasterStatus","mgscs_bp_master", b=>b&&b.bp&&b.grp, a=>`BP 기준 정보 ${a.length}건 저장됨`);
  if(Array.isArray(bm)) BP_MASTER=bm;
  const bs=saveBpJson("bpSitesInput","bpSitesStatus","mgscs_bp_sites", c=>c&&c.name&&Array.isArray(c.sites), bpSitesLabel);
  if(Array.isArray(bs)) BP_SITES=bs;
  const bpErr=(bm===null)||(bs===null);
  // BP 변경 즉시 반영(분석 실패 경로는 _lastScmIssues=null이라 낡은 이슈로 덮지 않음)
  if(window._lastScmIssues) renderScmIssues(window._lastScmIssues);
  refreshQuakePanel(); renderMap();   // BP 변경 → 지도 재렌더(BP 점·모니터 반경 갱신)
  // API 키·프록시 (프록시는 키 유무·경로와 무관하게 항상 저장)
  const val=document.getElementById("apiKeyInput").value.trim();
  const proxy=document.getElementById("proxyUrlInput")?.value.trim()||"";
  const fmt=document.getElementById("apiFormatSelect")?.value==="openai"?"openai":"anthropic";
  const model=document.getElementById("modelInput")?.value.trim()||"";
  const prevKey=getApiKey(), prevProxy=getProxyUrl(), prevFmt=getApiFormat(), prevModel=(localStorage.getItem("mgscs_model")||"");
  // 키 형식 검증은 Anthropic 모드에서만 — OpenAI 호환(Qwen)은 'sk-ant-' 아니어도 허용
  if(fmt==="anthropic" && val && !val.startsWith("sk-ant-")){ statusEl.textContent="Anthropic 키 형식 오류 — 'sk-ant-'로 시작해야 합니다. (Qwen이면 API 형식을 'OpenAI 호환'으로 선택하세요)"; statusEl.className="apikey-status err"; return; }
  if(proxy) localStorage.setItem("anthropic_proxy_url", proxy); else localStorage.removeItem("anthropic_proxy_url");
  localStorage.setItem("mgscs_api_format", fmt);
  if(model) localStorage.setItem("mgscs_model", model); else localStorage.removeItem("mgscs_model");
  let keyChanged=false;
  if(val){ localStorage.setItem("anthropic_api_key", val); updateApiKeyBtn(); keyChanged=(val!==prevKey); }
  else if(!prevKey){ statusEl.textContent="API 키를 입력해 주세요."; statusEl.className="apikey-status err"; return; }   // 키 전무(프록시·BP는 이미 저장됨)
  // 유효 키가 있고 키/프록시가 실제로 바뀌었으면 재수집(BP-only 저장은 재수집 안 함).
  // 이미 수집 중이면 startFetch가 no-op이므로 '시작' 문구를 쓰지 않는다(변경분은 다음 주기 반영).
  const changed = !!getApiKey() && (keyChanged || proxy!==prevProxy || fmt!==prevFmt || model!==prevModel);
  const willFetch = changed && !isLoading;
  if(willFetch) startFetch();
  if(bpErr){ statusEl.textContent="키·설정 저장됨 · BP JSON 오류 확인 후 닫으세요"; statusEl.className="apikey-status err"; return; }   // 오류 시 모달 유지(수집은 진행됨)
  statusEl.textContent = willFetch ? "저장 완료. 수집을 시작합니다." : "저장 완료."; statusEl.className="apikey-status ok";
  setTimeout(closeApiKeyModal, 700);
}

function openApiKeyModal(){
  const k=getApiKey(); if(k) document.getElementById("apiKeyInput").value=k;
  const p=getProxyUrl(); if(p) { const pi=document.getElementById("proxyUrlInput"); if(pi) pi.value=p; }
  const fs=document.getElementById("apiFormatSelect"); if(fs) fs.value=getApiFormat();
  const mi=document.getElementById("modelInput"); if(mi) mi.value=(localStorage.getItem("mgscs_model")||"");   // 미설정이면 빈칸(기본 모델 식별자는 화면 비노출 유지)
  // 대외비 BP 원문은 프리필하지 않음(공유 PC 노출 방지) — 요약만. 교체=붙여넣기, 삭제=[] 입력
  const bpEl=document.getElementById("bpMasterInput"), bpSt=document.getElementById("bpMasterStatus");
  if(bpEl) bpEl.value="";
  if(bpSt){ bpSt.textContent=BP_MASTER.length?`현재 ${BP_MASTER.length}건 로드됨 · 교체하려면 새 JSON 붙여넣기`:""; bpSt.style.color="var(--ink-3)"; }
  const bsEl=document.getElementById("bpSitesInput"), bsSt=document.getElementById("bpSitesStatus");
  if(bsEl) bsEl.value="";
  if(bsSt){ const n=BP_SITES.reduce((a,c)=>a+(c.sites?c.sites.length:0),0); bsSt.textContent=BP_SITES.length?`현재 ${BP_SITES.length}사·${n}개 로드됨 · 교체하려면 붙여넣기`:""; bsSt.style.color="var(--ink-3)"; }
  document.getElementById("apiKeyStatus").className="apikey-status";
  document.getElementById("apiKeyOverlay").classList.add("open");
}
function closeApiKeyModal(){ document.getElementById("apiKeyOverlay").classList.remove("open"); }
function openInfoModal(){ location.href="system"; }   // 시스템 개요는 ④ 기준으로 이관됨
function closeInfoModal(){}

function updateApiKeyBtn(){
  const st=document.getElementById("navApiState");
  if(st) st.className="sb-state"+(getApiKey()?" on":"");
}

// ════════════════════════════════════════
// 뷰 전환 (사이드바 내비게이션)
// ════════════════════════════════════════
const VIEW_TITLE={dashboard:"Risk 현황",archive:"수집 이력"};
let activeView="dashboard", dashFocus="all";   // 대시보드 내 포커스: all | scm | news
// 모바일 내비 드로어

function setView(view){
  // 하위 호환: 옛 점검/신호 뷰 호출 → 대시보드 포커스로 매핑
  if(view==="checklist"){ setView("dashboard"); setDashFocus("scm"); return; }
  if(view==="news"){ setView("dashboard"); setDashFocus("news"); return; }
  activeView=view;
  document.querySelectorAll("[data-view]").forEach(b=>b.classList.toggle("on",b.dataset.view===view));
  const t=document.getElementById("viewTitle"); if(t)t.textContent=VIEW_TITLE[view]||"Risk 현황";
  const isOv=false, isArc=(view==="archive"), isDash=(view==="dashboard");   // 개요는 ④ 기준으로 이관
  const arc=document.getElementById("archiveSection"); if(arc) arc.style.display=isArc?"block":"none";
  const kpi=document.getElementById("kpiStrip"); if(kpi) kpi.style.display=(isOv||isArc)?"none":"";
  const db=document.getElementById("dbBar"); if(db) db.style.display=(isOv||isArc)?"none":"";
  const seg=document.getElementById("dashSeg"); if(seg) seg.style.display=isDash?"flex":"none";
  if(isDash) applyDashFocus();
  else {
    ["mapSection","scmSection","newsSection","trendSection",
     "verdictSlot","kwSection","trendFold","newsFold"]
      .forEach(id=>{const e=document.getElementById(id);if(e)e.style.display="none";});
      }
  if(isArc){ renderArchive(); syncFromServer(); }   // 들어올 때마다 다시 읽는다
}
function setDashFocus(mode){
  dashFocus=mode;
  document.querySelectorAll("#dashSeg .dseg").forEach(b=>b.classList.toggle("on",b.dataset.f===mode));
  if(activeView==="dashboard") applyDashFocus();
}
function showTodayBlocks(){
  ["verdictSlot","kwSection","trendFold","newsFold"]
    .forEach(id=>{const e=document.getElementById(id);if(e)e.style.display="";});
}
function applyDashFocus(){
  showTodayBlocks();
  // 이 화면이 답하는 질문은 하나다 — "우리가 걸렸나".
  // 그 답은 서버 판정이고, 지도는 그 근거다. 신호 피드 400여 건은 판단의 원재료라
  // 기본 화면에서 내린다(따로 탭으로 본다). 추세는 배경 맥락이라 접어 둔다.
  const scm=document.getElementById("scmSection"), news=document.getElementById("newsSection"), trend=document.getElementById("trendSection");
  const map=document.getElementById("mapSection");
  const isAll=(dashFocus==="all"), isScm=(dashFocus==="scm"), isNews=(dashFocus==="news");
  if(scm)  scm.style.display  = (isAll||isScm) ? "block":"none";
  if(map)  map.style.display  = (isAll||isScm) ? "block":"none";
  if(news) news.style.display = isNews ? "block":"none";            // 전체에서는 감춘다
  if(trend) trend.style.display = (isAll && trend.dataset.ready==="1") ? "block":"none";
}
function openSrcPanel(){ renderSourceBar(window._lastSrcResult||null); document.getElementById("srcOverlay").classList.add("open"); }
function closeSrcPanel(){ document.getElementById("srcOverlay").classList.remove("open"); }

// ════════════════════════════════════════
// 추세 변곡점 근거 — 스파크라인 클릭 → 그날 헤드라인 (data/<date>.json · 비용 0)
// ════════════════════════════════════════
function closeTrendModal(){ document.getElementById("trendOverlay").classList.remove("open"); }
function showTrendEvidence(cat){
  const trend=window._trend;
  if(!trend){ showToast("추세 데이터가 없습니다(로컬 파일 모드 등)"); return; }
  const days=Object.keys(trend).sort();
  const series=days.map(d=>({date:d, n:(trend[d][cat]||0)})).filter(x=>x.n>0).sort((a,b)=>b.n-a.n);
  if(!series.length){ showToast("해당 카테고리 데이터가 없습니다"); return; }
  const icon=(CATS.find(c=>c.id===cat)||{}).icon||"";
  document.getElementById("trendModalTitle").textContent=`${icon?icon+" ":""}${cat} — 30일 변곡점 근거`;
  document.getElementById("trendDayChips").innerHTML=series.slice(0,8).map((x,i)=>{
    const md=x.date.slice(5).replace("-","/");
    return `<button class="trend-chip${i===0?" on":""}" data-date="${x.date}" data-cat="${esc(cat)}" onclick="selectTrendDay(this)">${md} · ${x.n}건</button>`;
  }).join("");
  document.getElementById("trendOverlay").classList.add("open");
  loadDayEvidence(series[0].date, cat);   // 최다일(피크) 자동 표시
}
function selectTrendDay(btn){
  document.querySelectorAll("#trendDayChips .trend-chip").forEach(b=>b.classList.toggle("on",b===btn));
  loadDayEvidence(btn.dataset.date, btn.dataset.cat);
}
async function loadDayEvidence(date, cat){
  const box=document.getElementById("trendEvidence");
  box.innerHTML=`<div class="arc-empty">${date} 근거 확인 중</div>`;
  let snap;
  try{ const r=await fetch(`${MGSCS_DATA}${date}.json`,{cache:"no-cache"}); if(!r.ok) throw 0; snap=await r.json(); }  // ★ 추세(trend.json)와 캐시 정책 통일 — 같은 날 재크롤 시 건수 불일치 방지
  catch(_){ box.innerHTML=`<div class="arc-empty">${date} 스냅샷을 불러올 수 없습니다(로컬 파일 모드 등).</div>`; return; }
  const items=(snap.matched||[]).filter(m=>(m.category||"기타")===cat)
    .sort((a,b)=>(b.corroboration||0)-(a.corroboration||0));
  const [_y,_m,_d]=date.split("-").map(Number);  // ★ 로컬 자정 기준 파싱 — 'YYYY-MM-DD' UTC 파싱으로 인한 -1일 표시 방지
  const dn=new Date(_y,_m-1,_d).toLocaleDateString("ko-KR",{month:"long",day:"numeric",weekday:"short"});
  if(!items.length){ box.innerHTML=`<div class="arc-empty">${dn} · ${esc(cat)} 근거 기사 없음</div>`; return; }
  const CAP=40;
  box.innerHTML=`<div class="trend-ev-head">${dn} · <b>${esc(cat)}</b> ${items.length}건 — 이날 이 라인을 끌어올린 신호</div>`
    +`<div class="arc-list">`+items.slice(0,CAP).map(m=>{
      const conf=m.confidence?`<span class="arc-conf">${esc(CONF_LABEL[m.confidence]||m.confidence)}</span>`:"";
      const corr=m.corroboration>1?`<span class="corr-badge">${m.corroboration}개 매체</span>`:"";
      return `<div class="arc-row">
        <a class="arc-link" href="${safeUrl(m.link)||"#"}" target="_blank" rel="noopener" title="${esc(cleanTitle(m.title))}">${esc(cleanTitle(m.titleKo||m.title))}</a>
        <span class="arc-src">${esc(m.source||"")}</span>${corr}${conf}
      </div>`;
    }).join("")+`</div>`+(items.length>CAP?`<div class="arc-empty">상위 ${CAP}건 표시(전체 ${items.length}건)</div>`:"");
}

// 30일 카테고리 추세 스파크라인 — data/trend.json 읽기 전용 (LLM·라이브러리 없음 · 비용 0)
async function loadTrend(){
  let trend;
  try{ const r=await fetch(MGSCS_DATA+"trend.json",{cache:"no-cache"}); if(!r.ok) return; trend=await r.json(); }
  catch(_){ return; }   // 파일 없음(로컬 file:// 등) → 조용히 숨김
  const days=Object.keys(trend).sort().slice(-30);
  if(days.length<2) return;
  window._trend=trend;  // 변곡점 근거 상세 조회에서 재사용
  const sec=document.getElementById("trendSection"); const grid=document.getElementById("trendGrid");
  if(!sec||!grid) return;
  const totals={}; days.forEach(d=>Object.entries(trend[d]).forEach(([c,n])=>totals[c]=(totals[c]||0)+n));
  const top=Object.keys(totals).sort((a,b)=>totals[b]-totals[a]).slice(0,8);
  if(!top.length) return;
  grid.innerHTML=top.map(cat=>{
    const series=days.map(d=>trend[d][cat]||0);
    const max=Math.max(1,...series), W=140, H=34;
    const pts=series.map((v,i)=>`${(i/(series.length-1)*W).toFixed(1)},${(H-v/max*H).toFixed(1)}`).join(" ");
    const co=(CATS.find(c=>c.id===cat)||{}).color||"#2C5BA0";
    const prevArr=series.slice(-8,-1), last=series[series.length-1];
    const prev7=prevArr.length?prevArr.reduce((a,b)=>a+b,0)/prevArr.length:0;   // ★ 실제 직전일 수로 평균 — 데이터 7일 미만 구간 ▲ 오탐 방지
    const up=prevArr.length>=4 && last>prev7*1.5 && last>=3;   // 표시만(자동 발송 없음)
    const icon=(CATS.find(c=>c.id===cat)||{}).icon||"";
    return `<div class="trend-card" data-cat="${esc(cat)}" onclick="showTrendEvidence(this.dataset.cat)" title="클릭 — ${esc(cat)} 변곡점 근거 보기">
      <div style="display:flex;justify-content:space-between;font-size:11px;font-weight:700;color:var(--ink-2);">
        <span>${icon} ${esc(cat)}</span>
        <span class="tnum" style="color:${up?'var(--hi)':'var(--ink-3)'}">${last}${up?' ▲':''}</span>
      </div>
      <svg viewBox="0 0 ${W} ${H}" width="100%" height="${H}" preserveAspectRatio="none" style="margin-top:4px;display:block;">
        <polyline points="${pts}" fill="none" stroke="${co}" stroke-width="1.6"/>
      </svg></div>`;
  }).join("");
  sec.dataset.ready="1";
  if(activeView==="dashboard") applyDashFocus(); else sec.style.display="none";
}

// KPI / 내비 카운트 갱신
// **숫자는 서버가 센 것을 쓴다.** 판정을 서버로 옮긴 뒤에도 여기서 브라우저 값을
// 세면 두 숫자가 갈린다 — 실제로 갈렸다. 브라우저는 등급을 안 매기므로 전부
// medium 이 되어 "주의 251건" 이라는 뜻 없는 수가 떴다.
function updateKpis(){
  const set=(id,v)=>{const e=document.getElementById(id);if(e)e.textContent=v;};
  const h24=Date.now()-24*60*60*1000;
  set("kpiTotal", allNews.length);
  set("kpi24", allNews.filter(n=>new Date(n.pubDate).getTime()>h24).length);
  const sn=document.getElementById("segNews"); if(sn)sn.textContent=allNews.length||"";
  // 접어 둔 채로도 얼마나 있는지는 보여야 한다 — 접기가 '없음' 으로 읽히면 안 된다.
  const nf=document.getElementById("newsFoldN"); if(nf)nf.textContent=allNews.length?`${allNews.length}건`:"";
  refreshFunnel();
}

// 감지 4단계 — 수집 → 재난 → 우리와 연관 → 점검 대상.
// 좁아지는 것 자체는 좋은 것도 나쁜 것도 아니다. 단계마다 무엇이 떨어져 나갔는지가
// 보여야 "제대로 좁혔나" 를 물을 수 있다.
async function refreshFunnel(){
  const set=(id,v)=>{const e=document.getElementById(id);if(e)e.textContent=v;};
  try{
    const f = await fetch("api/signals/funnel?hours=24").then(r=>r.ok?r.json():null);
    if(!f || !f.ok) return;

    const col = f.collected ?? 0, dis = f.stage1 ?? 0,
          lnk = f.stage2 ?? 0,    tgt = f.waiting ?? 0;
    set("kpiCollected", col); set("kpiDisaster", dis);
    set("kpiLinked", lnk);    set("kpiReview", tgt);

    const fin = document.querySelector(".rk-f .f.fin");
    if(fin) fin.classList.toggle("hit", tgt > 0);
    // 걸린 것이 없으면 램프를 죽인다. 색이 아껴져야 색이 일한다.
    const rk = document.getElementById("rkBox");
    if(rk) rk.classList.toggle("quiet", !(tgt > 0));

    // 띠 — **각 단계에서 떨어져 나간 몫**이다. 네 칸을 더하면 수집 전체가 된다.
    // 누적으로 그리면 뒷단계가 앞단계를 덮어 띠가 거짓말을 한다.
    const tot = Math.max(col, 1);
    const pct = n => (Math.max(0, n) / tot * 100).toFixed(2) + "%";
    const band = {
      drop1: pct(col - dis),      // 재난이 아니라고 뺀 몫
      drop2: pct(dis - lnk),      // 재난은 맞지만 우리와 무관
      drop3: pct(lnk - tgt),      // 연관은 맞지만 점검 안 하기로
      fin:   pct(tgt),
    };
    // 칸에 **이름을 단다.** 색이 위 숫자와 이어지긴 하지만, 띠만 보고는
    // 어느 칸이 무엇인지 알 수 없었다. 올려 두면 이름·수·비율이 뜬다.
    const seg = {
      drop1: ["재난 아님", col - dis, col,  "수집"],
      drop2: ["우리와 무관", dis - lnk, dis, "재난"],
      drop3: ["점검 안 함", lnk - tgt, lnk, "연관"],
      fin:   ["점검 대상",   tgt,       lnk, "연관"],
    };
    const paint = {drop1:"var(--rk-1)", drop2:"var(--rk-2)",
                   drop3:"var(--rk-3)", fin:"var(--rk-4)"};
    document.querySelectorAll("#rkBand i").forEach(el => {
      const k = el.dataset.b, [nm, n, den, denNm] = seg[k] || ["", 0, 1, ""];
      el.style.width = band[k] || "0%";
      el.style.background = paint[k];
      // **분모를 밝힌다.** 깔때기 오독의 가장 흔한 원인이 분모를 안 적는 것이다.
      el.title = den > 0
        ? `${nm} ${n}건 · ${denNm} 대비 ${Math.round(n / den * 100)}%`
        : `${nm} ${n}건`;
    });

    // 바닥글 — 단계마다 탈락 수를 같이 적는다. 남은 수만 적으면 좁힌 척이 된다.
    // **앞 단계 대비**를 적는다. 전체 대비는 315→2 에서 0.6% 같은 반올림 오차가 되어
    // 뒷단계에서 뜻을 잃는다. 앞 단계 대비여야 **어느 관문이 일하고 있나**가 읽힌다.
    // 절대 수는 그대로 둔다 — 2건과 3건은 운영에서 다른 일이지만 0.6%와 0.9%는 아니다.
    const pctOf = (n, den) => den > 0 ? ` <span class="pc">${Math.round(n/den*100)}%</span>` : "";
    const L = document.getElementById("rkLeft");
    if(L) L.innerHTML = col
      ? `재난 아님 <b>${f.dropped1 ?? (col - dis)}건</b>${pctOf(col - dis, col)}`
        + ` · 우리와 무관 <b>${dis - lnk}건</b>${pctOf(dis - lnk, dis)}`
        + ` <span class="den">앞 단계 대비</span>`
      : "최근 24시간 수집 없음";

    const R = document.getElementById("rkRight");
    if(R){
      const bits = [];
      if(lnk) bits.push(`대기 <b>${f.waiting ?? 0}</b> · 제외 <b>${f.no ?? 0}</b>`);
      // **결정한 게 없으면 정확도를 안 쓴다.** 0건으로 100% 를 그리면 그 지표는 거짓말이 된다.
      if(f.accuracy !== null && f.accuracy !== undefined)
        bits.push(`탐지 정확도 <b>${Math.round(f.accuracy * 100)}%</b>`);
      if((f.oldestWaitingH ?? 0) >= 24)
        bits.push(`<span class="late">최장 대기 <b>${Math.round(f.oldestWaitingH)}시간</b></span>`);
      R.innerHTML = bits.join(" · ");
    }
  }catch(_){ /* 못 읽으면 숫자만 안 바뀐다. 화면은 계속 돈다 */ }
}

// ════════════════════════════════════════
// 언론사 RSS
// ════════════════════════════════════════
// ── Google News RSS 빌더 ──
// 현지어 검색 소스는 클라우드 IP·사내망에서도 안정적으로 응답하며 쿼리로 7일치를 끌어와
// 헤드라인 피드에서 밀려난 지역 사고를 회수한다. (when:Nd = N일 윈도우)
function gnews(query, lang, country, ceidLang){
  return "https://news.google.com/rss/search?q=" + encodeURIComponent(query)
    + "&hl=" + lang + "&gl=" + country + "&ceid=" + country + ":" + (ceidLang || lang);
}

const SOURCES=[
  // ════════ 직접 피드 (general headline RSS) ════════
  // ── 한국 방송사/통신사 (browserOnly: 데이터센터 IP 차단으로 서버 스냅샷은 제외, 브라우저 수집만) ──
  //    서버측 공백은 아래 Google News 'ko' 검색 소스가 동일 권역으로 커버한다.
  {name:"연합뉴스", url:"https://www.yonhapnews.co.kr/rss/allheadlines.xml", browserOnly:true},
  {name:"KBS",     url:"https://news.kbs.co.kr/rss/rss.do?source=news", browserOnly:true},
  {name:"MBC",     url:"https://imnews.imbc.com/rss/news/news_00.xml", browserOnly:true},
  {name:"SBS",     url:"https://news.sbs.co.kr/news/SBSNewsRSS.do?pmd=G", browserOnly:true},
  {name:"YTN",     url:"https://www.ytn.co.kr/rss/rss.php?id=01", browserOnly:true},
  // ── 한국 (서버·브라우저 모두 정상) ──
  {name:"JTBC",    url:"https://fs.jtbc.co.kr/RSS/newsflash.xml"},
  {name:"조선일보", url:"https://www.chosun.com/arc/outboundfeeds/rss/?outputType=xml"},
  {name:"한국경제", url:"https://www.hankyung.com/feed/all-news"},
  {name:"매일경제", url:"https://www.mk.co.kr/rss/30000001/"},
  {name:"전자신문", url:"https://rss.etnews.com/Section901.xml"},
  {name:"경향신문", url:"https://www.khan.co.kr/rss/rssdata/total_news.xml"},
  // ── 글로벌 영어 ──
  {name:"BBC",        url:"https://feeds.bbci.co.uk/news/world/rss.xml"},
  {name:"Al Jazeera", url:"https://www.aljazeera.com/xml/rss/all.xml"},
  {name:"CNN",        url:"http://rss.cnn.com/rss/edition_world.rss"},
  {name:"Guardian",   url:"https://www.theguardian.com/world/rss"},
  {name:"DW",         url:"https://rss.dw.com/rdf/rss-en-all"},
  {name:"CNBC",       url:"https://www.cnbc.com/id/100727362/device/rss/rss.html"},
  // ── 중화권 영어 (반도체·대만해협·물류) ── ※ CGTN·DIGITIMES는 보편적 차단으로 제거(대만은 검색:대만반도체로 대체)
  {name:"South China Morning Post", url:"https://www.scmp.com/rss/91/feed"},
  {name:"Global Times",url:"https://www.globaltimes.cn/rss/outbrain.xml"},
  // ── 일본 ── ※ 닛케이(유료)·TBS는 차단으로 제거(일본 재난은 NHK+Yahoo!JP+검색:일본재난+공식:JMA로 대체)
  {name:"NHK",        url:"https://www3.nhk.or.jp/rss/news/cat0.xml"},
  {name:"Yahoo!JP",  url:"https://news.yahoo.co.jp/rss/topics/top-picks.xml"},
  // ── 중동 (호르무즈·수에즈 물류) ──
  {name:"Middle East Eye", url:"https://www.middleeasteye.net/rss"},

  // ════════ 공식 재난 경보 — 권위 다중 재해 피드 (구조화·저소음) ════════
  // 뉴스 기반 소스와 별개로 공공기관 경보를 직접 수신해 1차 감지의 신뢰도/속도를 높인다.
  // force:true → 이미 재해로 사전 필터된 피드이므로 키워드 필터를 우회하고 전건 통과시킨다.
  {name:"공식:GDACS",   url:"https://www.gdacs.org/xml/rss.xml", force:true},                                       // 전 지구 다중 재해(지진·홍수·태풍·화산·가뭄) 영향등급 경보 (cat은 제목으로 자동분류)
  {name:"공식:USGS지진", url:"https://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/4.5_day.atom", force:true, cat:"지진"}, // M4.5+ · 최근 24시간 전세계 지진
  {name:"공식:JMA지진화산", url:"https://www.data.jma.go.jp/developer/xml/feed/eqvol.xml", force:true, cat:"지진"},   // 일본 기상청 지진·화산 (Photo·Gas 핵심거점 직접·신속)
  {name:"공식:GVP화산",  url:"https://volcano.si.edu/news/WeeklyVolcanoRSS.xml", force:true, cat:"화산"},            // 스미소니언 주간 화산활동(화산재→항공·해상물류)
  // ※ NWS(api.weather.gov)·ReliefWeb는 빈 Extreme 피드/간헐 차단으로 단순 fetcher와 부적합 → 제거.
  //    미주 커버리지는 아래 '검색:미주재난'으로 대체(안정), 다중 재해는 GDACS가 권위 있게 커버.

  // ════════ Google News 검색 보강 (현지어·지역별 안정 소스) ════════
  // 과거 rsshub.app 기반 외신 피드(신화·인민·朝日·読売·毎日·産経·공동·AP·중앙통신사)는
  // 공용 인스턴스 차단으로 장기간 0건 → 동일 권역을 아래 현지어 검색 소스로 대체.
  {name:"검색:국내재난", url:gnews("지진 OR 침수 OR 산불 OR 정전 OR 단수 OR 공장 가동중단 OR 폭우 피해 when:5d","ko","KR")},
  {name:"검색:국내기상특보", url:gnews("기상청 특보 OR 호우경보 OR 강풍경보 OR 대설경보 OR 폭염경보 OR 한파경보 OR 지진 통보 when:3d","ko","KR")}, // KMA(기상청) 발표 신속 포착 — 국내 거점(이천·청주)
  {name:"검색:폭발사고", url:gnews("공장 폭발 OR 가스 폭발 OR 폭발 사고 OR 폭발 추정 when:7d","ko","KR")},
  {name:"검색:산업화재", url:gnews("공장 화재 OR 산업단지 화재 OR 물류창고 화재 when:7d","ko","KR")},
  {name:"검색:공급망",   url:gnews("반도체 소재 OR 특수가스 OR 불화수소 OR 공급망 위기 OR 원자재 대란 when:7d","ko","KR")},
  {name:"검색:수출 통제", url:gnews("수출 통제 OR 수출 규제 OR 전략물자 OR 희토류 OR 갈륨 OR 게르마늄 OR 반도체 규제 when:7d","ko","KR")},
  {name:"검색:일본재난", url:gnews("地震 OR 噴火 OR 台風 OR 洪水 OR 工場 火災 OR 爆発 OR 半導体 OR 輸出規制 when:5d","ja","JP")},
  {name:"검색:중국공급", url:gnews("地震 OR 洪水 OR 工厂 爆炸 OR 火灾 OR 出口管制 OR 半导体 OR 硫酸 OR 稀土 when:5d","zh-CN","CN","zh-Hans")},
  {name:"검색:대만반도체",url:gnews("地震 OR 台風 OR 火災 OR 台積電 OR 半導體 OR 缺料 OR 停電 when:7d","zh-TW","TW","zh-Hant")},
  {name:"검색:글로벌리스크",url:gnews("earthquake OR factory explosion OR export ban OR semiconductor OR chip OR strait blockade OR supply chain disruption when:5d","en","US")},
  {name:"검색:미주재난", url:gnews("hurricane OR tornado OR wildfire OR chemical plant OR refinery fire OR power outage OR flooding when:5d","en","US")}, // 미주 거점(Gas·Target) — NWS 대체 안정 소스
  {name:"검색:유럽재난", url:gnews("Europe chemical plant OR refinery fire OR gas supply OR earthquake OR flooding OR strike OR export control when:5d","en","GB")}, // 유럽 거점(Gas·Target·화학 공급사)
];

const PROXIES=[
  u=>`https://corsproxy.io/?${encodeURIComponent(u)}`,
  u=>`https://api.allorigins.win/raw?url=${encodeURIComponent(u)}`,
  u=>`https://api.codetabs.com/v1/proxy?quest=${encodeURIComponent(u)}`,
  u=>`https://thingproxy.freeboard.io/fetch/${u}`,
];

let allNews=[], activeCategory="전체", countdown=1800, cdInterval=null, isLoading=false;

// 오탐 제외 (비유·스포츠·연예·마케팅 표현) — snapshot.mjs EXCLUDE 미러
const EXCLUDE=/인기\s?폭발|관심\s?폭발|매출\s?폭발|득점\s?폭발|화력\s?폭발|분노\s?폭발|웃음\s?폭발|감동\s?폭발|흥행\s?폭발|환호\s?폭발|폭발적|지원\s?홍수|문의\s?홍수|주문\s?홍수|전화\s?홍수|문자\s?홍수|관심\s?홍수|물가\s?전쟁|가격\s?전쟁|치킨\s?전쟁|할인\s?전쟁|쩐의\s?전쟁|두뇌\s?전쟁|입시\s?전쟁|총성\s?없는\s?전쟁|태풍의\s?눈|태풍급|세금\s?폭탄|보유세\s?폭탄|종부세\s?폭탄|건보료\s?폭탄|요금\s?폭탄|청구서\s?폭탄|과태료\s?폭탄|금리\s?폭탄|예매\s?폭발|개봉\s?폭발|조회수\s?폭발|구독자\s?폭발|시청률\s?폭발|사격\s?훈련|모의\s?훈련|합동\s?훈련|음주운전|보이스피싱|성폭행|성추행|절도\s?사건|사기\s?혐의|마약\s?혐의|[Ss]hooting\s+drill|[Ff]iring\s+drill|[Mm]ilitary\s+drill|[Bb]lasts?\s+(?:back|at|out\s+at|over|critics?|rivals?|officials?)|[Ss]lams\s/;

function detectLang(text){
  if(!text)return"ko";
  const ko=(text.match(/[\uAC00-\uD7AF]/g)||[]).length;
  const zh=(text.match(/[\u4E00-\u9FFF]/g)||[]).length;
  const en=(text.match(/[a-zA-Z]/g)||[]).length;
  if(ko>2)return"ko";
  const ja=(text.match(/[\u3040-\u30FF]/g)||[]).length;
  if(ja>2)return"ja";
  if(zh>2)return"zh";
  if(en>5)return"en";
  return"ko";
}

// 직접 요청 → 실패 시 CORS 프록시 순차 폴백. validate(text)가 truthy면 그 값 반환(파싱 결과 포함 가능), 전부 실패 시 null.
// 타임아웃 타이머는 성공·실패·타임아웃 모두에서 해제한다.
async function fetchWithProxyFallback(url, validate, directMs=6000, proxyMs=8000){
  const grab=async(u,ms)=>{ const c=new AbortController(); const timer=setTimeout(()=>c.abort(),ms);
    try{ const r=await fetch(u,{signal:c.signal}); if(!r.ok) return null; return validate(await r.text()); }
    catch(_){ return null; } finally{ clearTimeout(timer); } };
  let v=await grab(url, directMs);
  if(v) return v;
  for(const pf of PROXIES){ v=await grab(pf(url), proxyMs); if(v) return v; }
  return null;
}

async function fetchOneRSS(url){
  // 피드 형태(항목 있거나 빈 채널/피드 루트)면 성공 — 항목 0건 정상 피드를 '실패'로 오탐하지 않음. HTML 오류페이지는 배제
  const isFeed=t=>((t.includes("<item>")||t.includes("<entry>")||t.includes("<channel")||t.includes("<feed"))?t:null);
  return await fetchWithProxyFallback(url, isFeed, 5000, 8000);
}

function parseRSS(xml){
  try{
    const doc=new DOMParser().parseFromString(xml,"text/xml");
    return Array.from(doc.querySelectorAll("item,entry")).map(el=>{
      const title=(el.querySelector("title")?.textContent||"").replace(/<!\[CDATA\[|\]\]>/g,"").replace(/<[^>]*>/g,"").trim();
      let link=el.querySelector("link")?.textContent?.trim()||el.querySelector("link")?.getAttribute("href")||"";
      if(!link){const g=el.querySelector("guid")?.textContent?.trim()||"";if(g.startsWith("http"))link=g;}
      const desc=(el.querySelector("description")?.textContent||el.querySelector("summary")?.textContent||"")
        .replace(/<!\[CDATA\[|\]\]>/g,"").replace(/<[^>]*>/g,"").trim().slice(0,200);
      const pubDate=(el.querySelector("pubDate")?.textContent||el.querySelector("published")?.textContent||el.querySelector("updated")?.textContent||"").trim();
      return{title,link,desc,pubDate};
    }).filter(i=>i.title&&i.title.length>2);
  }catch(_){return[];}
}

// ════════════════════════════════════════
// LLM 분석
// ════════════════════════════════════════
// 모델 식별자는 단일 지점에서만 관리하고 화면·보고서에는 노출하지 않는다("LLM 이중 검증"으로만 표기).
// 사내망/운영 환경에서 localStorage("mgscs_model")로 무중단 교체 가능.
// ※ 브라우저 직접 호출은 모델값이 네트워크 요청에 노출되므로, 완전 은닉이 필요하면
//   Cloudflare Worker 프록시(docs/CLOUDFLARE_ACCESS.md 참조) 뒤로 호출을 옮긴다.
function getLlmModel(){ try{ return localStorage.getItem("mgscs_model") || "HCP-Big-Latest"; }catch(_){ return "HCP-Big-Latest"; } }   // 사내 카탈로그 별칭(GLM-5.2 매핑) — 2026-08-19 사내 수정③
// API 형식 — "anthropic"(기본·Claude) | "openai"(Qwen·vLLM·Ollama 등 OpenAI 호환 게이트웨이)
function getApiFormat(){ try{ return localStorage.getItem("mgscs_api_format")==="openai" ? "openai" : "anthropic"; }catch(_){ return "anthropic"; } }

// 브라우저가 LLM 을 부르던 경로(약 316줄)를 지웁니다.
// **판정은 서버가 합니다** — 수집분을 올리면 signal_filter 가 거르고,
// signal_review 가 사내 LLM 으로 소재군·중단 정황을 뽑아 BP 를 맞추고
// 5게이트를 돌려 사건으로 등록합니다. 화면은 그 결과를 읽어 그릴 뿐입니다.
//
// 두 곳에서 판정하면 같은 신호에 다른 답이 나옵니다 — 화면은 이 브라우저에
// 쌓인 것을, 서버는 자기가 받은 것을 봅니다. 실제로 그렇게 갈렸습니다.

// AI 응답에서 JSON 배열만 안전 추출 — 직접 파싱 실패 시 문자열 인지 균형 괄호 스캔
function parseJsonArray(txt){
  const s=String(txt||"").trim();
  // 프리필 "[" + 모델이 재차 "["를 출력한 이중 배열([[…]]) 언랩
  const unwrap=v=>Array.isArray(v)&&v.length===1&&Array.isArray(v[0])?v[0]:v;
  try{ const v=unwrap(JSON.parse(s)); return Array.isArray(v)?v:null; }catch(_){}
  const start=s.indexOf("[");
  if(start<0) return null;
  let depth=0,inStr=false,escp=false;
  for(let i=start;i<s.length;i++){
    const c=s[i];
    if(inStr){ if(escp)escp=false; else if(c==="\\")escp=true; else if(c==='"')inStr=false; continue; }
    if(c==='"')inStr=true;
    else if(c==="[")depth++;
    else if(c==="]"){ depth--; if(depth===0){ try{ const v=unwrap(JSON.parse(s.slice(start,i+1))); return Array.isArray(v)?v:null; }catch(_){ return null; } } }
  }
  return null;
}

// ── 분석 프롬프트 ──────────────────────────────────────────────
// API 호출과 파일 내보내기가 **같은 프롬프트**를 써야 한다. 두 벌로 갈리면
// 사내 LLM에 붙여넣은 결과와 자동 분석 결과가 달라진다.

let _importMode = "llm";
function openImportBox(mode){
  _importMode = mode || "llm";
  const b=document.getElementById("importBox");
  const t=document.getElementById("importText");
  b.hidden=false;
  t.placeholder = _importMode === "incident"
    ? '사외에서 메일로 받은 사건 꾸러미를 그대로 붙여넣으세요 — {"source":...,"incidents":[...]}'
    : '사내 LLM이 준 JSON 배열을 그대로 붙여넣으세요 — [{"newsIndex":0, ...}]';
  document.getElementById("importMsg").textContent = "";
  t.focus();
}

async function applyImport(){
  const msg=document.getElementById("importMsg");
  const txt=document.getElementById("importText").value.trim();
  if(!txt){ msg.className="ib-err"; msg.textContent="붙여넣은 내용이 없습니다."; return; }
  msg.className=""; msg.textContent="반영 중…";
  try{
    if(_importMode === "incident"){
      const d = await importIncidentPackage(txt);
      msg.className="ib-ok";
      msg.textContent = `신규 ${d.added}건 · 이미 있던 것 ${d.duplicated}건`
        + (d.invalid ? ` · 형식 오류 ${d.invalid}건` : "") + ` (원장 ${d.total}건)`;
    }else{
      await importAnalysisResult(txt);
      msg.className="ib-ok"; msg.textContent="반영했습니다 — 아래 목록을 확인하세요";
      document.getElementById("importBox").hidden=true;
    }
  }catch(e){
    msg.className="ib-err"; msg.textContent=e.message;
  }
}

// ── 사건 꾸러미 — 사외에서 사내로 ────────────────────────────
// 사외(맥북·Actions)는 자동으로 돌며 신호를 모으고 판정한다. 사내는 그 결과를
// 받아야 같은 사건을 본다. 메일에 붙일 수 있게 텍스트로 내보낸다.
//
// 판정 결과(어느 BP가 걸렸나)까지 함께 싣는다. 사내에서 다시 판정하면
// 명부 판이 달라 결과가 갈릴 수 있는데, 그러면 "같은 사건"이 아니게 된다.
function buildIncidentPackage(){
  const src = window._lastIssues || [];
  const V = window.MGSCS_MATCH_VOCAB || {};
  const items = src.filter(Boolean).map((iss) => ({
    occurredAt: String(iss.pubDate || new Date().toISOString()).slice(0, 10),
    country: iss.country || "",
    admin1: iss.admin1 || "",
    region: iss.region || "",
    title: iss.title || "",
    level: iss.level || "medium",
    triage: iss.triage || "",
    materialGroups: iss.affectedMaterials || [],
    companies: (iss._served || []).map((b) => b.bp),
    matchedHow: iss._how || "",
    summary: iss.summary || "",
    evidence: iss.disruptionEvidence || "",
    why: iss.why || "",
    link: iss.link || "",
  }));
  return { source: "사외 자동 수집", exportedAt: new Date().toISOString(),
           roster: `${V.companies || 0}사 ${V.sites || 0}site`, incidents: items };
}

function exportIncidentPackage(){
  const pkg = buildIncidentPackage();
  if(!pkg.incidents.length){ showToast("내보낼 사건이 없습니다 — 서버가 판정한 사건이 있어야 합니다."); return; }
  const text = JSON.stringify(pkg, null, 1);
  const stamp = new Date().toISOString().slice(0,16).replace(/[-:T]/g,"");
  const blob = new Blob([text], {type:"application/json;charset=utf-8"});
  const a=document.createElement("a");
  a.href=URL.createObjectURL(blob);
  a.download=`mGSCS_incidents_${stamp}.json`;
  a.click(); URL.revokeObjectURL(a.href);
  if(navigator.clipboard) navigator.clipboard.writeText(text).catch(()=>{});
  showToast(`사건 ${pkg.incidents.length}건 내보냄 — 메일에 붙여 사내로 보내세요 (클립보드에도 복사)`);
}

// ── ① 분석 결과 → 점검 현황으로 넘기기 ──────────────────────────
// 여기까지가 "우리가 걸렸나" 의 답이다. 그 다음(누구에게 묻고 무엇을 받았나)은
// 점검 현황이 굴린다. 그런데 분석 결과가 사건 원장에 없으면 점검 현황에서 그 사건을
// 찾을 수가 없다 — 그래서 넘기는 순간 원장에 등록하고 그 사건으로 데려간다.
function scmIssueId(iss){
  // 같은 이슈를 두 번 넘겨도 한 건이어야 한다. 제목+지역+날짜로 고정 키를 만든다.
  const base = `${iss.title||""}|${iss.region||""}|${new Date().toISOString().slice(0,10)}`;
  let h = 0;
  for(let i=0;i<base.length;i++){ h = (h*31 + base.charCodeAt(i)) >>> 0; }
  return "SCM-" + new Date().toISOString().slice(0,10).replace(/-/g,"") + "-" + h.toString(36).toUpperCase();
}
async function sendIssueToInspect(idx){
  const iss = (window._reviewIssues||[])[idx];
  if(!iss){ showToast("이 분석 결과를 찾지 못했습니다 — 분석을 다시 실행해 주세요."); return; }
  const id = scmIssueId(iss);
  const areas = [iss.admin1, iss.region].filter(Boolean);
  const pkg = { source:"① Risk 현황", incidents:[{
    id, title: iss.title || "(제목 없음)",
    country: iss.country || "", admin1: iss.admin1 || "",
    adminAreas: areas,
    occurredAt: iss.pubDate || new Date().toISOString(),
    severity: iss.level==="high" ? "high" : (iss.level==="low" ? "low" : "medium"),
    certainty: iss.confidence==="official" ? "confirmed" : "rumor",
    official: iss.confidence==="official",
    status: "open",
    lat: iss.lat ?? null, lon: iss.lon ?? null,
    materialGroups: iss.affectedMaterials || [],
    sourceUrl: iss.newsLink || "",
  }]};
  try{
    const d = await importIncidentPackage(JSON.stringify(pkg));
    const note = d.added ? "사건으로 등록했습니다" : "이미 등록된 사건입니다";
    showToast(`${note} — 점검 현황으로 이동합니다`);
    setTimeout(()=>{ location.href = "inspect?event=" + encodeURIComponent(id); }, 600);
  }catch(e){ showToast("넘기지 못했습니다: " + e.message); }
}

async function importIncidentPackage(text){
  let pkg;
  try{ pkg = JSON.parse(text); }
  catch(_){ throw new Error("JSON을 읽지 못했습니다. 받은 내용을 그대로 붙여넣어 주세요."); }
  const d = await fetch("api/incidents/import", {
    method:"POST", headers:{"Content-Type":"application/json"},
    body: JSON.stringify(pkg)
  }).then(r=>r.json());
  if(!d.ok) throw new Error(d.error || "가져오기에 실패했습니다.");
  return d;
}

// ── 사내 LLM으로 손수 분석하기 ──────────────────────────────
// API를 붙이지 못하는 환경이 있다. 그때는 프롬프트를 파일로 내보내
// 사내 LLM에 붙여넣고, 받은 JSON을 되돌리면 된다.
// 자동이든 손수든 같은 프롬프트·같은 판정을 쓰므로 결과가 갈리지 않는다.



// ════════════════════════════════════════
// ★ 공급망 영향 분석 (mGSCS SCM Impact)
// ════════════════════════════════════════
// ── ③ 판정된 재난 — 범주별 ────────────────────────────────
// **화면이 세지 않는다.** 서버가 사내 LLM 으로 소재군·중단 정황·지역을 뽑고
// BP 를 맞추고 게이트를 돌린 결과를 그대로 읽는다(`/api/signals/judged`).
//
// 예전에는 브라우저에 쌓인 기사를 낱말로 세었다. 그러면 서버가 거른 것과
// 화면이 보여 주는 것이 어긋난다 — 실제로 '지진' 안에 연예 기사가 섞였다.
// 세는 곳과 판정하는 곳은 같아야 한다.
//
// 등급이 곧 뜻이다:
//   high    게이트 전부 통과 + 우리 BP 가 걸렸다   → 점검 대상
//   medium  한쪽만 서거나 아직 미판정             → 지켜봄
//   low     둘 다 아니다                       → 참고
let KW_SEL = null;
let JUDGED = {categories: [], total: 0};

const GRADE_KO = {high:"점검 대상", medium:"지켜봄", low:"참고"};

async function loadJudged(){
  const sub = document.getElementById("kwSub");
  try{
    const d = await fetch("api/signals/judged?days=7").then(r => r.ok ? r.json() : null);
    if(!d || !d.ok) throw new Error("판정 결과 없음");
    JUDGED = d;
    if(sub) sub.textContent = d.total
      ? `최근 7일 · ${d.categories.length}종 ${d.total}건`
      : "최근 7일";
  }catch(_){
    JUDGED = {categories: [], total: 0};
    if(sub) sub.textContent = "판정 결과 없음";
  }
  renderKeywords();
}

function renderKeywords(){
  const bar = document.getElementById("kwBar");
  const clr = document.getElementById("kwClear");
  if(!bar) return;
  const cats = JUDGED.categories || [];
  if(clr) clr.hidden = !KW_SEL;

  if(!cats.length){
    bar.innerHTML = `<span class="kw-empty">판정된 재난 없음 — 서버가 판정하면 여기 쌓입니다</span>`;
    renderKeywordDetail(); return;
  }
  // 범주 칩 — **걸린 수**가 순서를 정한다(서버가 이미 그 순으로 준다).
  // 건수로 줄 세우면 300건짜리 잡음 범주가 2건짜리 진짜 위에 선다.
  bar.innerHTML = cats.map(t => {
    const on  = KW_SEL === t.category ? " on" : "";
    const hot = t.high > 0 ? " hot" : "";
    // **우리와 얽힌 범주에 표식을 단다.** 기사 건수만 크게 보이면
    // BP 를 하나도 안 건드린 '지진 40건' 이 정통으로 맞은 '정전 1건' 보다 커 보인다.
    const hit = (!t.high && t.bpHit > 0) ? " bp" : "";
    return `<button type="button" class="kw${on}${hot}${hit}" data-k="${esc(t.category)}" title="신호 ${t.n}건 · 우리 BP 걸림 ${t.bpHit}건">`
      + `${esc(t.category)}`
      + (t.high ? `<span class="hi">점검 ${t.high}</span>`
                : (t.bpHit ? `<span class="bpn">BP ${t.bpHit}</span>`
                           : `<span class="n">${t.n}</span>`))
      + `</button>`;
  }).join("");
  bar.querySelectorAll(".kw").forEach(b => {
    b.onclick = () => selectKeyword(b.dataset.k === KW_SEL ? null : b.dataset.k);
  });
  renderKeywordDetail();
}

function selectKeyword(k){
  KW_SEL = k;
  renderKeywords();
  // 1단(지도)과 3단이 이어진다 — 고른 범주가 걸린 권역만 지도에 남긴다.
  try{
    RGN_SEL.clear();
    if(k){
      const t = (JUDGED.categories || []).find(x => x.category === k);
      (t ? t.items : []).forEach(it => (it.areas || []).forEach(a => {
        if(a) RGN_SEL.add(String(a).trim());
      }));
    }
    renderRegionBar(); applyRegionDim();
  }catch(_){}
  const d = document.getElementById("kwDetail");
  if(k && d) d.scrollIntoView({behavior:"smooth", block:"nearest"});
}

function renderKeywordDetail(){
  const box = document.getElementById("kwDetail");
  if(!box) return;
  if(!KW_SEL){ box.hidden = true; box.innerHTML = ""; return; }
  const t = (JUDGED.categories || []).find(x => x.category === KW_SEL);
  if(!t){ box.hidden = true; return; }
  box.hidden = false;

  // 판정 한 건이 한 줄이다. **기사가 아니라 사건**이다 —
  // 무엇이 났고, 어디고, 어느 소재군이고, 우리 BP 가 몇 곳 걸렸나.
  box.innerHTML = t.items.map(it => {
    const g = it.grade || "medium";
    const day = String(it.judgedAt || "").slice(5, 10) || "—";
    const areas = (it.areas || []).filter(Boolean).join(" · ");
    const mats  = (it.materials || []).filter(Boolean).join(" · ");
    const bp = (typeof it.bpCount === "number")
      ? (it.bpCount > 0 ? `<b>BP ${it.bpCount}사</b>` : "BP 없음")
      : "BP 미판정";
    return `<div class="jd jd-${g}">
      <span class="jd-g">${GRADE_KO[g] || g}</span>
      <span class="jd-d tnum">${esc(day)}</span>
      <span class="jd-t">${it.url
        ? `<a href="${safeUrl(it.url) || "#"}" target="_blank" rel="noopener">${esc(cleanTitle(it.title))}</a>`
        : esc(cleanTitle(it.title))}</span>
      <span class="jd-m">${areas ? `<span class="tag">${esc(areas)}</span>` : ""}
        ${mats ? `<span class="tag">${esc(mats)}</span>` : ""}
        <span class="tag bp">${bp}</span></span>
      ${it.gateSummary ? `<span class="jd-x">${esc(it.gateSummary)}</span>` : ""}
    </div>`;
  }).join("");
}

// ── 서버가 판정한 사건을 읽어 온다 ─────────────────────────
// 화면은 **판정하지 않는다.** 수집해서 올리면 서버가 선별하고, 사내 LLM 으로
// 소재군·중단 정황을 뽑고, BP 를 맞추고, 5게이트를 돌려 사건으로 등록한다.
// 여기서는 그 결과를 가져다 그릴 뿐이다.
//
// 예전에는 이 화면이 자기 LLM 을 부르고 자기 게이트를 돌렸다. 그러면 같은 신호를
// 두 번 판정하고 서로 다른 답을 낸다 — 화면은 이 브라우저에 쌓인 것을,
// 서버는 자기가 받은 것을 본다. **판정하는 곳은 하나여야 한다.**
async function loadServerIssues(){
  const note = document.getElementById("judgeNote");
  const say = t => { if(note) note.textContent = t; };
  try{
    const r = await fetch("api/incidents");
    if(!r.ok) throw new Error("서버 오류 " + r.status);
    const d = await r.json();
    const rows = (d.incidents || d.rows || d || []).filter(x => x && x.id);

    // 사건 → 화면이 쓰는 모양으로. 서버가 이미 정한 것은 그대로 쓴다.
    const issues = rows.map(x => ({
      id: x.id,
      title: x.titleKo || x.title || x.headline || "",
      summary: x.summary || x.detail || "",
      region: x.admin1 || x.region || "",
      regionText: x.admin1 || "",
      country: x.country || "",
      level: ["high","medium","low"].includes(x.severity) ? x.severity : "medium",
      triage: x.severity === "high" ? "review" : "monitor",
      affectedMaterials: x.materialGroups || x.materials || [],
      why: x.gateSummary || x.evidence || "",
      _how: x.areaSource ? `권역 ${x.admin1 || ""}` : "",
      _served: (x.bpCount != null)
        ? [{ bp: `${x.bpCount}사`, grade: "", tier: "", sites: x.siteCount || 0 }] : null,
      occurredAt: x.occurredAt || x.at || x.importedAt || "",
      certainty: x.certainty || "",
      status: x.status || "open",
      link: x.link || x.url || "",
      _server: true,
    }));

    window._lastScmIssues = issues;
    renderScmIssues(issues);
    const open_ = issues.filter(i => i.status !== "closed").length;
    // **언제 확인한 결과인지를 같이 적는다.** 그래야 빈 화면이 *확인했다* 로 읽힌다 —
    // 시각이 없으면 *안 불러와졌다* 와 구별이 안 된다.
    const now = new Date();
    const hm = `${String(now.getHours()).padStart(2,"0")}:${String(now.getMinutes()).padStart(2,"0")}`;
    say(issues.length
        ? `사건 ${issues.length}건 · 진행 ${open_}건 · ${hm} 확인`
        : `걸린 사건 없음 · ${hm} 확인`);
    return issues;
  }catch(e){
    say("판정 결과 없음 — " + e.message);
    return [];
  }
}

const SCM_KEY = "scmIssues_v1";

function loadScmIssues(){
  try{const r=localStorage.getItem(SCM_KEY);return r?JSON.parse(r):[];}catch(_){return [];}
}
function saveScmIssues(issues){
  try{localStorage.setItem(SCM_KEY,JSON.stringify(issues));}catch(_){}
}


const CONF_LABEL={official:"공식",consensus:"교차 확인",estimate:"추정"};

// ══════════ 긴급 점검 메일 생성 + 회신 파싱 ══════════
// 점검 캠페인·회신 기록은 **서버 원장(inspection.jsonl)으로 옮겼다.**
// 여기 있던 localStorage 경로(약 190줄)를 지운다 — 같은 일을 두 곳에서 하면
// 언젠가 갈린다. 실제로 갈렸다: 점검 화면은 서버를 읽는데 이 경로는 브라우저에만
// 쌓여, PC 를 바꾸면 이미 회신한 BP 에게 재요청이 나갈 수 있었다.
// 옛 브라우저에 남은 기록은 점검 현황의 [옛 기록 가져오기] 로 옮긴다.
// ════════════════════════════════════════
// 재난 신호 → 영향 BP 매핑 (m-BP Grade 소재군 마스터 기반, 대외비 데이터는 로컬 전용)
// ════════════════════════════════════════
// BP 기준 정보 소재군(grp)은 mGSCS 8소재군과 동일 명칭(Gas·Photo·Chemical·Slurry·Precursor·Wafer·Target).
// Pad는 대응 BP가 없어 자연히 빈 결과.
// 지역 정규식: '대륙'·'아메리카'·'미주' 등 광범위 부분매칭어는 제외(북미 대륙→중국, 남아메리카→미국 오탐 방지)
const REGION_MAP=[[/일본|japan|도호쿠|간토|규슈|홋카이도|오사카|나고야/i,"JP"],[/한국|korea|청주|구미|포항|이천|울산|여수/i,"KR"],[/중국|china|상하이|선전|우시|다롄/i,"CN"],[/대만|taiwan|타이완/i,"TW"],[/미국|united states|u\.s\.|텍사스/i,"US"],[/유럽|europe|독일|germany|프랑스|france|벨기에/i,"EU"]];
function regsFromText(t){ if(!t) return []; const out=[]; REGION_MAP.forEach(([re,code])=>{ if(re.test(t)&&!out.includes(code)) out.push(code); }); return out; }
// 반환: null=마스터 미설정 / [] = 매칭 소재군 없음 / [bp…] = 영향 가능 BP(지역정합, 지역일치 우선)
// 소재군·지역코드는 대소문자 무시 비교(LLM/데이터 형식 편차에도 매칭 유지)
/* SG-START — shared/pipeline.mjs와 동일 소스(드리프트 테스트로 강제) */
const MATERIAL_GROUPS = ["Photo", "Chemical", "Slurry", "Pad", "Gas", "Precursor", "Wafer", "Target"];

// 실제 중단 정황 어휘 — "일어났다"를 가리키는 표현
const DISRUPTION_RE = /*DR*/ /가동\s?중단|가동\s?중지|조업\s?중단|생산\s?중단|생산\s?차질|출하\s?중단|출하\s?지연|선적\s?중단|통관\s?중단|공장\s?폐쇄|조업\s?정지|정전|단수|급수\s?중단|항로\s?폐쇄|항로\s?우회|운항\s?중단|봉쇄\s?조치|셧다운|전면\s?중단|shut\s?down|shutdown|halted|suspended|suspension|closure|power\s?outage|production\s?stop/ /*DR*/;
// 추측·전망 어휘 — "일어날 수 있다"는 아직 근거가 아니다
const SPECULATION_RE = /*SP*/ /우려|전망|예상|가능성|검토\s?중|추진\s?중|계획\s?중|할\s?수\s?있|것으로\s?보인|may\s|could\s|expected\s+to|plans?\s+to|considering|potential/ /*SP*/;
// 부정 표현 — 백테스트에서 "생산 차질 없어"가 중단으로 오인되는 사례가 다수 확인됐다.
// 중단 어휘가 있어도 부정이 붙으면 오히려 '영향 없음'을 뜻한다.
const NEGATION_RE = /*NG*/ /없어|없다|없음|없이|없는|미발생|아니다|아닌|않았|않은|않기|차질\s?최소화|영향\s?최소화|차질\s?없|경미|미미한|no\s+impact|not\s+affected|unaffected|no\s+disruption|without\s+disruption|minimal\s+impact|limited\s+impact/ /*NG*/;

function gate(status, why) { return { status, why }; }

/**
 * 5게이트 판정. 입력은 전부 '코드측 결정값' 또는 'LLM이 뽑은 근거 문장'이다.
 * LLM의 triage/confidence 자기신고는 입력으로 받지 않는다.
 *
 * @param {object} i
 *  - affectedMaterials {string[]}  LLM 추출 소재군
 *  - disruptionEvidence {string}   LLM 추출 중단 정황 원문 문장
 *  - official {boolean}            공식 경보 회선 여부(코드 결정)
 *  - corroboration {number}        교차 확인 고유 출처 수(코드 집계)
 *  - bpMatchCount {number|null}    지역 정합 BP 수. null=마스터 미설정
 *  - opts.g3RequiresOfficial {boolean} 기본 false.
 *      69일 백테스트: 공식 경보 4,018건 중 중단 정황을 담은 것은 0건이었다.
 *      공식 경보는 '위험'을 알리고 중단 사실은 '뉴스'가 전하므로, G3를 official 필수로 두면
 *      G3∧G4가 한 레코드에서 성립할 수 없어 review가 구조적으로 0이 된다.
 *      따라서 G3는 "신뢰할 수 있는 출처"(공식 또는 교차 확인≥2)로 정의하고,
 *      G5는 "사실 확인"으로 역할을 나눈다. true로 주면 종전의 엄격 모드가 된다.
 */
function evaluateScmGates(i = {}, opts = {}) {
  const g3RequiresOfficial = opts.g3RequiresOfficial === true;
  const official = i.official === true;
  const corr = Number(i.corroboration) || 0;
  const mats = (i.affectedMaterials || [])
    .map(m => String(m).trim())
    .filter(m => MATERIAL_GROUPS.some(g => g.toLowerCase() === m.toLowerCase()));
  const bp = i.bpMatchCount;
  const ev = String(i.disruptionEvidence || "").trim();

  // G1 지리 근접 — 지역 정합 BP가 실제로 존재하는가
  const g1 = bp === null || bp === undefined
    ? gate("unknown", "BP 기준 정보 미설정 — 지리 판정 불가")
    : bp > 0 ? gate("pass", `지역 정합 BP ${bp}사`)
             : gate("fail", "영향권 내 등록 BP 없음");

  // G2 소재군 직접 노출 — 8축 유효 소재군이 있고, 그 소재군이 해당 지역에 있는가
  const g2 = !mats.length
    ? gate("fail", "8소재군에 해당하는 소재군 없음")
    : bp === null || bp === undefined
      ? gate("unknown", "BP 기준 정보 미설정 — 소재군 노출 확인 불가")
      : bp > 0 ? gate("pass", `${mats.join("·")} 노출`)
               : gate("fail", `${mats.join("·")} — 해당 지역 생산·출하 거점 없음`);

  // G3 출처 공식성
  const g3 = official
    ? gate("pass", "공식 기관 경보")
    : g3RequiresOfficial
      ? gate("fail", "공식 발표 아님")
      : corr >= 2 ? gate("pass", `교차 확인 ${corr}개 출처로 대체`)
                  : gate("fail", "공식 발표 아니고 교차 확인도 부족");

  // G4 실제 중단 정황 — 근거 문장이 '일어난 일'을 말하는가
  const g4 = !ev
    ? gate("unknown", "중단 정황 근거 문장 없음")
    : !DISRUPTION_RE.test(ev)
      ? gate("fail", "근거 문장에 실제 중단 표현 없음")
      : NEGATION_RE.test(ev)
        ? gate("fail", "부정 표현 — 오히려 영향 없음을 뜻함")
        : SPECULATION_RE.test(ev)
          ? gate("fail", "추측·전망 표현 — 실제 발생 아님")
          : gate("pass", ev.slice(0, 80));

  // G5 사실 확인 — 공식이면 단독도 충분, 아니면 독립 출처 2곳 이상
  const g5 = official
    ? gate("pass", "공식 발표 — 단독 출처로 충분")
    : corr >= 2 ? gate("pass", `독립 출처 ${corr}곳 확인`)
                : gate("fail", `교차 확인 부족(${corr})`);

  const gates = { g1, g2, g3, g4, g5 };
  const blockedBy = Object.entries(gates)
    .filter(([, g]) => g.status !== "pass")
    .map(([k]) => k.toUpperCase());

  return {
    gates,
    triage: blockedBy.length ? "monitor" : "review",
    blockedBy,
    gateSummary: Object.entries(gates)
      .map(([k, g]) => `${k.toUpperCase()}:${g.status === "pass" ? "○" : g.status === "fail" ? "×" : "?"}`)
      .join(" "),
  };
}
/* SG-END */

function matchBPs(affectedMaterials,regionText){
  if(!BP_MASTER.length) return null;
  const mats=new Set((affectedMaterials||[]).map(m=>String(m).toLowerCase()));
  if(!mats.size) return [];
  let cand=BP_MASTER.filter(b=>mats.has(String(b.grp).toLowerCase()));
  const regs=regsFromText(regionText);
  if(regs.length){
    const EU_ISO=new Set(["DE","FR","IE","BE","AT","GB","IT","ES","NL","CH","SE","FI"]);   // 데이터는 ISO 국가코드, REGION_MAP은 대륙코드 EU → 유럽 등가처리
    const inReg=cand.filter(b=>{ if(!b.reg) return false; const r=String(b.reg).toUpperCase(); return regs.includes(r)||(regs.includes("EU")&&EU_ISO.has(r)); });
    const noReg=cand.filter(b=>!b.reg);   // 지역 미상 BP는 배제 불가 → 후순위 포함, 타지역 BP만 제외
    cand=[...inReg,...noReg];
  }
  // 회사명 중복 제거(한 BP가 여러 소재군에 걸칠 때) — 지역정합 우선 순서 유지
  const seen=new Set();
  return cand.filter(b=>{ if(seen.has(b.bp)) return false; seen.add(b.bp); return true; });
}
function bpListHtml(iss){
  const bps = iss._served || matchBPs(iss.affectedMaterials, iss.region);
  if(bps===null || bps.length===0) return "";
  const shown=bps.slice(0,10);
  // 이름만 띄우면 거기서 끝난다. 눌러서 명부로 넘어가야 다음 단계(연락)로 이어진다.
  const chips=shown.map(b=>`<a class="scm-bp" href="bp?bp=${encodeURIComponent(b.bp)}" title="BP 기준 정보에서 이 회사의 사업장 보기">${esc(b.bp)}</a>`).join("");
  const more=bps.length>shown.length?`<span class="scm-bp-more">+${bps.length-shown.length}</span>`:"";
  const how = iss._how ? `<span class="scm-bp-how">${esc(iss._how)}</span>` : "";
  return `<div class="scm-bp-list"><span class="scm-bp-lead">영향 가능 BP · ${bps.length}사</span>${chips}${more}${how}</div>`;
}

// ════════════════════════════════════════
// 지진 거리 엔진 (USGS GeoJSON 실좌표 → 근접 BP 사이트, mRADAR haversine 이식)
// ════════════════════════════════════════
function haversineKm(lat1,lon1,lat2,lon2){
  const R=6371,dLat=(lat2-lat1)*Math.PI/180,dLon=(lon2-lon1)*Math.PI/180;
  const a=Math.sin(dLat/2)**2+Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLon/2)**2;
  return Math.round(2*R*Math.asin(Math.sqrt(a)));
}
// 아시아 박스(위18~50·경100~150)는 조밀해 임계 낮춤 / 그 외 글로벌
function quakeThresholds(lat,lon){ return (lat>=18&&lat<=50&&lon>=100&&lon<=150)?[50,150,500]:[100,300,1000]; }
// 실좌표 지진 → 근접 BP 사이트(거리순, urgent/monitor/minor). 좌표 없으면 호출 안 함(허위 urgent 방지)
function nearestBPSites(qlat,qlon){
  if(!BP_SITES.length) return [];
  const [u,m,mi]=quakeThresholds(qlat,qlon); const out=[];
  BP_SITES.forEach(c=>(c.sites||[]).forEach(s=>{
    if(!validSite(s)) return;   // null/""/NaN/범위초과 배제(문자열 좌표는 수용) → (0,0) 허위경보 방지
    const la=Number(s.lat), lo=Number(s.lon);
    const d=haversineKm(qlat,qlon,la,lo);
    const lvl=d<=u?"urgent":d<=m?"monitor":d<=mi?"minor":null;
    if(lvl) out.push({d,lvl,name:c.name,site:s.n,role:s.role});
  }));
  return out.sort((a,b)=>a.d-b.d);
}
// USGS GeoJSON(M4.5+ · 최근 24시간) 실좌표 지진 조회 — 직접→프록시 폴백. 10분 TTL 캐시 + 동시호출 dedup.
let _quakeCache={t:0,data:null}, _quakeInflight=null;
async function fetchUsgsQuakes(){
  if(_quakeCache.data && (Date.now()-_quakeCache.t)<600000) return _quakeCache.data;   // 신선(10분)하면 재요청 안 함
  if(_quakeInflight) return _quakeInflight;                                            // 진행 중이면 같은 약속 공유
  _quakeInflight=(async()=>{
    const url="https://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/4.5_day.geojson";
    const tryParse=t=>{ try{ const j=JSON.parse(t); return (j&&j.features)?j.features:null; }catch(_){ return null; } };
    const feats=await fetchWithProxyFallback(url, tryParse, 6000, 8000);   // 공통 폴백 헬퍼 재사용
    const out = feats ? feats.map(f=>{ const g=f.geometry&&f.geometry.coordinates, p=f.properties||{};
      if(!g||typeof g[0]!=="number"||typeof g[1]!=="number") return null;
      if(Math.abs(g[1])>90||Math.abs(g[0])>180) return null;   // 좌표 범위 sanity(프록시 조작 방어)
      const mag=(typeof p.mag==="number"&&p.mag>=0&&p.mag<=12)?p.mag:null;   // mag 범위 sanity
      return {lon:g[0],lat:g[1],depth:g[2],mag,place:p.place||"",title:p.title||"",time:p.time,url:p.url}; }).filter(Boolean) : [];
    if(feats) _quakeCache={t:Date.now(),data:out};   // 성공분만 캐시
    return feats ? out : (_quakeCache.data || []);   // 갱신 실패 시 직전 stale 캐시로 폴백(패널 유지)
  })();
  try{ return await _quakeInflight; } finally{ _quakeInflight=null; }
}
// 근접 BP 사이트가 있는 지진만 패널로 렌더 (모니터 반경 내)
async function refreshQuakePanel(){
  const panel=document.getElementById("quakePanel"); if(!panel) return;
  if(!BP_SITES.length){ panel.style.display="none"; return; }
  let quakes=[]; try{ quakes=await fetchUsgsQuakes(); }catch(_){ quakes=[]; }
  const now=Date.now();
  quakes=quakes.filter(q=>!q.time || (now-q.time)<=24*3600*1000);   // stale 캐시 폴백이 24시간 초과 지진을 '현재'로 표시하지 않도록 경과시간 제한
  const rows=[];
  quakes.forEach(q=>{ const near=nearestBPSites(q.lat,q.lon);   // urgent/monitor/minor 전부(거리순)
    if(near.length) rows.push({q,near}); });
  const rank={urgent:0,monitor:1,minor:2};
  rows.sort((a,b)=>(rank[a.near[0].lvl]-rank[b.near[0].lvl])||(a.near[0].d-b.near[0].d));
  if(!rows.length){ panel.style.display="none"; return; }
  panel.innerHTML=`<div class="quake-head">🌐 지진 근접 BP 사이트 · ${rows.length}건 <span class="quake-sub">USGS M4.5+ · 최근 24시간 실좌표 기준</span></div>`+
    rows.slice(0,6).map(({q,near})=>{
      const chips=near.slice(0,5).map(n=>`<span class="quake-bp lvl-${n.lvl}">${esc(n.name)} <i>${esc(n.site)}</i> ${n.d}km</span>`).join("");
      const more=near.length>5?`<span class="scm-bp-more">+${near.length-5}</span>`:"";
      const link=q.url?`<a href="${safeUrl(q.url)}" target="_blank" rel="noopener" class="scm-row-link">USGS↗</a>`:"";
      return `<div class="quake-row"><div class="quake-eq"><span class="quake-mag lvl-${near[0].lvl}">M${esc(String(q.mag??"?"))}</span><span class="quake-place">${esc(q.place)}</span>${link}</div><div class="quake-bps">${chips}${more}</div></div>`;
    }).join("");
  panel.style.display="";
}

const WORLD_PATH="M1000.0,294.9 999.6,295.3 999.3,295.7 998.9,295.9 998.8,296.5 999.2,296.2 999.6,295.9 999.7,296.3 999.2,296.5 998.5,296.7 998.1,296.4 997.4,296.7 996.9,296.9 996.4,297.2 996.2,296.8 995.8,296.6 996.1,296.2 996.5,296.2 997.1,295.8 997.5,295.7 998.0,295.6 998.5,295.3 999.0,295.1 999.4,295.1 1000.0,294.9ZM897.7,283.2 897.5,283.8 897.5,284.2 897.9,284.4 898.3,285.1 898.5,285.7 898.6,286.4 898.7,287.0 898.7,288.2 899.0,288.8 899.2,289.3 899.3,289.9 899.9,290.2 900.3,290.0 900.9,289.7 901.3,289.5 901.6,289.9 901.8,290.3 902.5,290.8 903.0,291.1 903.6,291.5 903.5,291.9 903.6,292.6 903.5,293.0 903.7,293.6 903.8,294.1 904.0,294.6 904.0,295.1 904.0,295.6 904.3,296.2 904.9,296.9 905.3,297.0 905.3,297.4 905.7,298.3 905.9,299.0 905.8,299.9 905.6,300.5 906.2,301.4 906.4,301.9 906.4,302.3 906.6,302.7 907.2,303.2 907.9,303.4 908.3,303.5 908.7,303.9 909.1,303.9 909.5,303.8 910.0,304.5 910.4,304.9 910.9,305.2 911.3,305.3 912.1,305.8 912.6,305.9 913.2,306.4 913.6,306.9 913.1,306.9 913.3,307.6 914.1,308.2 914.5,308.7 914.7,309.2 914.8,309.7 915.2,310.5 915.3,311.2 915.5,311.8 915.8,312.3 916.2,312.2 916.6,312.6 916.5,312.0 916.6,311.6 917.1,311.8 917.8,312.4 918.2,312.7 918.2,312.2 918.8,312.7 918.8,313.6 918.8,314.4 919.0,315.2 919.4,315.6 919.9,316.1 920.8,316.7 921.4,316.8 921.8,317.0 922.4,318.0 923.0,318.6 923.5,318.9 923.6,319.3 924.0,320.0 924.4,320.2 924.8,320.6 924.8,321.4 925.1,321.9 925.5,322.1 925.2,323.1 925.4,325.0 925.3,325.5 925.5,326.1 926.1,327.1 926.2,327.5 926.3,327.9 926.6,328.4 926.6,329.3 926.7,330.2 926.3,330.7 926.0,331.4 926.0,331.9 925.8,333.0 925.5,333.8 925.1,334.9 925.1,335.3 925.1,335.9 925.1,336.3 924.9,336.7 924.8,337.3 924.4,338.3 923.8,339.0 923.7,339.6 923.5,340.1 923.1,340.4 922.8,340.8 922.1,341.2 921.7,341.4 921.3,341.9 920.9,342.5 920.6,343.1 920.3,343.6 920.2,344.0 920.1,344.4 919.7,344.9 919.3,345.3 919.1,345.8 918.9,346.5 918.8,346.9 918.9,347.3 918.6,347.7 918.2,347.8 917.7,348.8 917.2,349.5 917.1,349.9 917.0,350.3 916.9,351.0 916.8,351.5 916.6,352.0 916.6,352.4 916.5,353.0 916.6,353.5 916.6,354.0 916.1,354.3 915.5,354.8 914.7,355.0 913.7,355.0 911.8,355.1 910.8,355.4 910.1,355.7 909.4,356.2 907.9,357.4 906.8,357.5 906.4,357.5 906.3,357.9 906.7,357.8 906.9,358.3 906.7,358.7 906.3,358.2 905.7,357.9 905.2,357.7 904.7,357.4 904.3,357.2 903.9,357.0 904.3,356.7 904.1,356.2 903.6,356.2 903.3,356.6 902.7,356.9 902.3,356.8 902.8,356.3 903.1,355.8 902.7,355.4 901.5,355.8 901.1,355.9 901.6,356.0 901.2,356.4 900.3,356.8 899.5,357.5 899.1,357.7 898.7,357.8 898.2,357.7 897.8,357.6 897.4,357.4 896.8,357.2 896.1,356.8 895.7,356.6 895.0,356.7 894.2,356.3 893.7,356.3 893.3,356.6 892.8,356.6 892.3,356.0 891.7,355.8 890.6,355.6 890.0,355.3 889.5,354.6 888.5,353.8 888.2,353.2 888.3,352.5 888.5,352.1 888.1,351.0 887.6,350.3 886.8,349.5 886.2,349.1 886.4,348.7 886.9,348.9 887.0,348.4 886.6,348.2 886.2,348.5 885.8,348.7 885.4,348.8 884.8,349.0 884.4,349.0 883.8,348.9 884.3,348.4 884.7,347.3 884.7,346.6 884.1,345.7 883.8,345.3 883.6,344.9 883.4,345.4 883.0,346.5 882.5,347.6 882.1,347.6 881.3,347.7 880.6,347.9 880.2,347.9 880.6,347.0 881.3,347.0 881.8,346.6 881.9,346.1 881.8,345.5 881.9,345.1 882.4,344.1 882.7,343.6 883.1,343.3 883.1,342.9 883.0,342.5 883.1,342.1 883.1,341.0 882.7,340.5 882.7,341.2 882.4,341.6 882.0,341.9 881.5,342.9 881.2,343.4 880.6,343.7 880.0,344.0 879.5,344.2 879.0,344.5 878.1,345.6 877.7,346.0 877.5,346.5 877.8,347.1 877.2,346.8 876.8,347.1 876.3,346.5 875.9,346.2 875.5,346.0 876.0,346.1 876.0,345.5 875.9,345.0 875.6,344.3 875.1,343.8 874.7,343.4 874.6,342.9 874.2,342.4 873.1,342.1 872.7,341.6 872.5,341.0 872.9,340.7 872.0,340.0 871.6,339.6 871.0,339.4 870.6,339.4 870.0,339.4 868.8,338.8 867.6,338.9 865.9,338.0 865.0,337.6 864.3,337.5 863.7,337.7 863.3,337.8 861.5,337.7 859.9,337.9 858.9,337.9 858.2,338.1 857.1,338.6 855.7,339.1 854.7,339.3 853.7,339.6 853.0,339.7 852.2,339.8 850.4,339.6 849.8,339.7 848.8,340.3 848.0,340.6 846.5,341.3 845.9,341.5 845.5,341.6 845.1,341.7 844.3,342.9 844.1,343.3 843.5,344.0 843.1,344.2 842.7,344.2 842.2,344.4 841.5,344.1 841.0,344.1 839.3,344.4 838.7,344.0 838.1,344.1 837.2,344.0 835.6,344.1 834.8,344.2 833.9,344.3 832.9,344.4 832.3,344.7 831.8,345.5 831.2,345.7 830.8,345.7 830.3,345.8 829.2,346.5 828.2,347.2 827.8,347.3 827.4,347.4 826.9,347.4 825.4,347.3 824.6,347.3 823.7,347.2 822.8,346.8 822.2,346.7 821.5,345.9 821.0,345.6 820.2,345.3 819.8,345.4 819.5,344.8 819.4,343.9 819.4,343.1 819.9,343.5 820.4,343.4 820.9,343.1 821.1,342.7 821.3,342.2 821.3,341.7 821.2,340.7 821.5,340.0 821.5,338.6 821.4,338.0 820.7,337.0 820.3,336.0 819.9,335.6 819.7,334.9 819.4,333.9 819.4,333.5 819.3,332.6 819.4,332.1 819.0,331.0 818.4,330.2 818.3,329.6 817.6,328.6 817.1,328.0 816.9,326.5 816.7,326.0 815.9,324.6 814.8,323.4 814.5,322.9 814.9,322.4 815.1,322.8 815.4,323.4 815.5,323.8 815.9,323.9 816.3,323.1 815.8,322.8 815.3,321.9 815.0,321.4 815.4,321.2 815.9,321.8 815.8,322.2 815.9,322.6 816.3,322.3 816.5,322.9 816.9,323.3 817.3,323.0 817.2,322.6 817.3,322.1 816.6,321.0 816.1,319.9 815.7,319.4 815.5,318.6 815.0,317.9 815.0,317.4 815.1,317.0 815.2,316.3 815.4,315.9 816.0,315.1 816.0,314.7 816.1,314.0 816.0,313.4 815.8,312.9 816.1,312.0 816.5,310.9 817.0,310.6 816.9,311.6 817.1,312.0 817.1,312.5 817.5,312.3 817.8,311.8 818.3,311.0 818.6,310.6 819.0,310.4 819.9,310.1 820.7,309.7 821.1,309.3 821.6,309.0 822.2,308.4 823.9,307.5 824.5,307.4 825.0,307.4 825.4,307.3 825.8,307.5 826.9,307.3 827.3,307.1 828.0,306.7 829.0,306.5 829.9,306.3 830.8,305.5 831.5,305.6 832.2,305.7 832.7,305.4 833.9,305.3 834.5,305.1 835.8,304.6 836.6,304.1 837.0,303.7 837.5,303.1 837.7,302.5 838.1,301.8 838.4,301.3 838.9,301.1 839.6,300.4 839.7,300.0 839.4,299.2 839.3,298.7 839.3,298.1 839.6,297.6 840.1,297.1 840.5,296.8 840.9,296.6 841.2,296.0 841.4,295.6 841.9,296.4 842.1,296.8 842.4,297.3 842.7,298.0 843.0,298.4 843.4,297.8 843.3,297.3 843.8,297.5 844.1,297.0 843.7,296.7 843.3,296.3 843.1,295.9 843.5,295.4 843.5,294.9 844.0,295.5 844.6,295.2 845.0,295.4 845.7,295.5 846.4,295.5 846.0,295.4 845.6,295.3 845.6,294.8 846.0,294.8 846.1,294.3 846.2,293.9 845.8,294.4 845.7,294.0 845.5,293.4 845.7,293.0 846.2,292.8 846.4,292.4 847.1,292.8 846.9,292.4 847.0,292.0 847.4,291.8 848.1,292.0 847.9,291.5 847.7,291.1 848.0,290.7 848.4,290.4 848.8,290.2 848.9,289.7 849.1,290.2 849.5,290.2 849.7,290.6 850.1,290.3 850.0,289.9 850.1,289.5 850.2,289.1 850.6,289.2 851.1,288.9 851.6,289.3 852.2,288.8 852.2,288.3 852.8,288.3 853.6,288.7 854.0,289.0 854.6,289.4 855.2,290.2 856.1,290.9 855.9,291.5 855.8,291.9 855.7,292.6 856.1,292.3 856.0,291.8 856.5,291.4 856.9,291.1 857.3,291.1 858.5,291.3 858.8,292.0 859.0,291.4 859.4,291.4 859.9,291.8 860.1,291.3 860.5,291.2 860.0,290.7 859.7,290.2 859.6,289.5 860.1,289.0 860.4,288.4 860.5,287.9 860.9,287.5 861.3,287.4 861.7,287.2 861.5,286.5 861.6,286.0 862.0,285.8 862.2,285.2 862.7,285.2 862.8,284.7 863.2,284.5 863.5,284.9 863.5,284.4 864.0,284.3 863.9,283.9 864.5,283.8 865.1,284.1 865.9,284.1 866.4,284.0 866.8,284.1 867.2,284.0 867.7,284.0 868.1,283.7 868.5,283.7 868.4,283.2 868.5,282.6 868.0,281.9 867.4,281.9 867.0,281.9 866.5,281.5 866.1,281.3 866.6,281.1 867.0,281.3 867.4,281.1 868.2,281.6 868.6,282.0 869.0,281.6 869.5,281.8 869.8,282.3 870.4,282.6 870.9,282.8 871.3,282.8 872.0,282.9 872.6,283.2 873.2,283.4 873.7,283.5 874.2,283.3 874.6,283.6 875.1,283.9 875.6,283.9 876.0,283.7 876.5,283.5 876.9,283.2 877.5,282.8 877.3,283.2 876.9,283.8 877.4,283.8 877.8,283.9 877.9,284.3 878.3,284.5 878.7,284.2 878.5,283.8 879.0,283.2 879.5,283.7 880.1,283.9 880.4,284.3 879.3,285.5 879.4,285.9 879.1,286.7 878.6,286.5 878.2,286.6 877.6,287.0 877.6,287.8 877.7,288.4 877.5,289.3 877.1,289.7 876.5,290.5 876.1,291.0 876.3,291.5 877.3,292.1 877.7,292.4 878.3,292.8 878.6,293.2 879.1,293.5 879.5,293.6 879.7,294.0 880.3,294.1 880.8,294.3 881.4,294.6 882.0,294.9 882.5,295.1 883.1,295.8 883.5,296.2 884.0,296.4 884.7,296.6 885.1,296.6 885.6,296.8 886.1,296.9 886.4,297.3 886.5,297.7 886.8,298.1 887.3,298.3 888.0,298.7 888.6,298.9 889.0,299.2 889.5,299.2 890.3,299.0 890.7,298.7 891.2,298.4 891.4,297.8 891.6,297.3 892.3,296.2 892.5,295.7 892.7,295.1 892.8,294.6 892.8,294.2 892.9,293.4 893.3,292.2 893.4,291.8 893.3,291.3 893.1,290.2 893.2,289.8 893.3,289.3 893.2,288.9 893.0,288.3 893.1,287.7 893.3,287.3 893.5,286.8 893.4,286.0 893.7,285.6 894.1,285.5 894.1,285.0 893.7,284.8 893.6,284.3 893.9,283.6 894.3,283.5 894.3,283.0 894.6,282.3 894.8,281.3 894.9,280.4 895.3,280.2 895.7,279.7 896.1,279.9 896.4,280.6 896.7,281.1 896.8,281.8 896.8,282.3 896.9,282.8 897.4,283.1ZM995.2,298.3 995.6,298.7 996.1,299.0 996.1,299.6 996.3,300.2 995.8,300.3 995.4,300.4 994.9,300.7 994.3,300.7 993.8,300.6 993.4,300.5 992.9,300.4 992.4,299.9 992.7,299.5 992.8,299.1 993.1,298.7 993.9,298.3 994.3,298.3 994.8,298.2 995.2,298.3ZM956.1,306.2 956.8,306.3 957.2,306.6 958.3,307.4 958.9,307.7 959.4,308.2 959.6,308.6 960.2,309.1 960.6,309.3 961.0,309.6 961.9,310.1 962.5,310.5 963.0,311.0 963.4,311.2 963.9,311.8 963.4,312.1 962.7,311.8 962.3,311.8 961.9,311.5 961.5,311.2 960.9,310.9 960.4,310.5 959.5,310.0 959.0,309.8 958.4,309.2 957.9,308.9 957.4,308.3 956.8,307.9 956.4,307.3 956.0,306.9 956.0,306.5 955.7,306.0 956.1,306.2ZM854.8,247.6 855.2,247.7 855.5,247.1 855.9,246.9 856.0,246.3 856.7,245.8 857.5,245.6 857.5,246.2 857.5,246.9 857.0,247.3 856.5,247.5 856.3,248.0 856.7,248.2 857.2,248.5 857.5,249.0 857.9,249.3 857.1,249.1 856.5,248.9 855.8,248.7 855.3,248.8 855.2,249.2 855.3,249.6 855.5,250.7 855.8,251.3 856.3,252.0 856.7,252.5 856.3,252.4 855.7,252.0 855.2,251.2 854.8,250.8 854.7,250.2 854.7,249.6 854.7,249.2 854.3,248.6 854.3,248.1 854.5,247.6 854.0,246.8 854.3,245.9 854.3,245.5 854.5,244.9 854.8,244.5 855.3,244.1 855.7,243.9 855.3,244.6 855.4,245.0 855.6,245.6 855.6,246.3 855.2,246.8 854.6,247.2 854.8,247.6ZM692.2,386.4 693.2,386.0 693.5,386.4 692.9,386.5 693.4,386.8 693.8,386.8 694.4,386.6 695.0,386.5 695.6,386.3 696.0,386.7 695.5,387.1 695.1,387.1 694.4,387.1 693.9,387.3 694.4,387.7 694.9,387.5 695.3,387.7 694.8,388.1 694.2,388.0 693.7,387.8 693.0,387.8 692.6,387.7 692.1,387.6 691.6,388.1 691.2,388.1 691.1,387.6 691.3,387.2 691.1,386.8 691.1,386.4 691.2,385.9 691.4,385.5 691.8,385.2 692.0,385.7 691.8,386.2 692.2,386.4ZM676.0,25.8 675.5,25.8 675.0,25.8 674.3,25.7 673.7,25.5 674.5,25.3 675.3,25.1 676.7,25.1 677.4,25.1 678.0,25.0 678.4,24.7 679.4,24.4 680.0,24.5 680.6,24.5 681.0,24.6 681.4,24.7 681.6,25.1 680.5,25.5 679.3,25.7 676.0,25.8ZM661.0,27.4 660.6,27.5 659.4,27.4 658.9,26.9 658.6,26.5 659.8,26.5 662.4,26.5 663.8,26.6 664.2,26.7 664.6,26.8 662.2,26.9 661.8,27.2 661.0,27.4ZM429.4,53.0 429.3,53.4 428.3,53.9 427.6,54.1 427.2,54.3 426.8,54.1 426.1,54.0 424.7,54.1 423.1,54.2 422.5,54.3 422.1,54.2 422.4,53.8 422.8,53.8 422.9,53.4 423.3,53.0 423.9,53.1 424.3,53.1 425.1,53.2 426.1,53.1 426.8,53.0 428.3,52.7 428.7,52.8 429.3,52.9ZM943.7,275.8 944.4,276.2 945.4,276.2 945.9,276.5 946.3,276.9 946.7,277.4 946.2,277.6 945.8,277.5 945.3,277.3 944.4,277.3 944.0,277.2 943.6,276.8 943.4,276.3 943.4,275.9ZM825.9,226.6 826.0,225.8 826.5,225.3 827.1,224.7 827.5,224.3 828.1,224.0 828.7,223.3 829.3,222.8 829.8,222.1 830.6,221.2 831.1,221.0 831.3,220.6 831.4,220.1 831.4,219.5 831.8,218.6 832.0,219.0 832.0,219.6 832.3,220.3 832.5,220.7 832.2,221.1 831.7,221.2 831.3,221.5 831.2,221.9 830.4,222.2 829.9,222.5 829.9,222.9 829.4,223.8 829.0,224.3 828.4,224.5 828.0,225.0 827.5,225.6 826.9,225.9 826.5,226.1 826.1,226.4ZM840.3,217.7 840.9,217.8 841.5,218.0 841.9,217.9 842.1,218.4 841.9,218.9 841.5,219.3 841.1,219.5 841.0,219.9 840.3,220.3 839.4,220.5 839.0,220.8 838.8,220.4 838.8,219.8 839.0,219.2 839.1,218.5 839.2,217.7 838.7,217.3 839.1,217.1 839.7,217.3 840.3,217.7ZM842.0,224.8 841.6,224.8 841.3,224.1 840.7,223.9 840.0,223.1 840.0,222.7 840.2,222.3 840.7,222.3 841.3,222.0 841.3,221.4 841.2,220.8 841.5,220.3 841.6,219.9 842.3,219.5 843.1,219.7 843.2,220.1 843.0,220.6 842.6,221.3 842.5,221.9 842.2,222.4 842.1,223.0 842.5,224.0 842.5,224.4 842.2,224.8ZM846.0,218.5 846.5,218.5 847.0,218.4 847.3,218.9 847.3,219.6 847.3,220.0 847.7,220.5 847.8,221.0 848.0,221.4 847.6,221.5 847.2,221.2 847.2,221.7 847.3,222.1 846.7,221.8 846.6,221.3 846.7,220.3 846.5,219.8 845.8,219.7 845.6,219.0 845.5,218.4 845.4,218.0 845.9,218.3ZM847.9,215.2 848.1,215.6 848.6,216.0 848.6,216.5 848.5,217.3 848.6,217.8 848.9,218.4 848.8,218.8 849.2,219.0 848.4,219.1 847.9,219.0 847.5,218.6 847.1,218.1 847.2,217.7 847.2,217.3 846.7,217.1 846.3,216.6 845.9,216.4 845.5,216.0 845.3,215.5 845.3,215.1 846.0,215.2 846.8,215.2 847.6,215.1ZM835.3,212.6 835.9,212.5 836.3,212.7 836.7,212.7 837.1,213.2 837.6,213.5 837.4,214.1 837.6,214.9 837.4,215.3 837.2,215.7 836.8,216.1 836.4,216.0 836.0,215.4 835.8,214.9 835.5,214.5 835.5,214.0 835.2,213.5 834.7,213.2 834.6,212.8 834.6,212.4 835.1,212.5ZM933.2,268.6 932.8,268.9 932.3,269.0 931.7,268.8 931.3,268.4 931.2,267.8 930.7,267.3 930.2,266.8 929.8,266.2 929.8,265.4 930.2,265.3 930.8,265.6 931.1,266.0 931.4,266.5 931.8,267.1 932.2,267.2 932.6,267.5 933.0,268.0 933.2,268.6ZM924.9,263.2 924.4,263.1 924.1,262.5 924.1,262.1 924.1,261.5 923.9,261.1 923.2,260.2 922.8,259.7 922.3,259.6 921.9,259.4 921.0,258.8 920.6,258.4 919.6,257.9 919.0,257.7 919.0,257.3 919.4,257.5 919.9,257.7 920.3,258.0 920.8,258.2 921.4,258.5 922.3,259.0 922.7,259.5 923.1,259.8 925.0,261.4 925.3,261.8 925.3,262.2 925.1,262.7 924.9,263.2ZM884.8,273.0 884.2,273.3 883.3,273.3 882.5,273.4 882.5,273.0 882.9,272.0 883.4,271.2 883.8,270.8 884.2,270.7 884.8,270.5 885.5,270.5 885.8,270.9 886.1,271.4 885.8,271.9 885.5,272.4 885.0,272.8ZM860.4,258.0 861.1,258.3 862.0,258.3 862.7,258.7 862.9,259.2 863.3,259.5 863.5,259.9 863.3,260.7 862.7,260.4 862.1,260.1 861.2,259.7 860.7,259.2 860.1,259.2 859.6,259.6 858.9,259.4 858.2,259.2 857.8,259.1 857.4,259.4 857.0,259.6 856.3,259.0 855.9,258.8 855.7,259.2 855.5,259.6 855.3,259.1 855.9,258.2 857.1,257.9 857.7,257.9 858.1,257.9 858.5,257.9 859.1,258.0 859.5,257.8 860.0,257.8 860.4,258.0ZM852.4,258.6 852.8,258.8 853.1,259.2 853.5,259.6 853.4,260.1 853.0,260.2 852.6,260.5 852.2,260.5 851.5,260.5 851.1,260.3 850.6,260.0 850.2,259.5 850.1,258.8 850.6,258.7 851.5,258.5 852.2,258.5ZM841.1,273.9 840.7,274.0 840.2,274.2 839.8,274.3 839.4,274.2 838.4,274.6 837.9,274.7 837.5,274.5 837.0,274.8 836.6,274.7 836.2,274.8 835.5,274.6 834.9,274.5 834.2,274.5 833.7,274.4 833.1,274.6 832.8,274.2 832.8,273.8 833.0,273.4 833.6,273.3 834.0,273.0 834.5,272.9 835.0,272.9 835.4,273.1 835.8,273.1 836.4,273.4 836.9,273.6 837.3,273.8 837.8,273.7 838.2,273.6 838.6,273.6 839.1,273.6 839.6,274.0 840.1,273.9 840.3,273.5 841.0,273.2 841.4,272.8 841.0,272.7 841.4,272.5 841.7,273.1 841.1,273.6ZM833.4,276.0 833.9,276.4 834.1,276.8 834.6,276.8 835.1,277.2 835.5,277.7 835.6,278.1 835.1,278.4 834.5,278.6 834.0,278.4 833.5,278.1 833.1,277.7 832.2,277.1 831.7,277.1 830.8,277.0 830.5,276.6 830.6,276.2 831.1,276.1 831.7,276.0 832.3,276.0 832.8,276.1 833.2,275.8ZM828.4,273.1 829.0,273.0 829.5,273.0 829.8,273.4 830.1,273.0 830.5,273.2 830.7,273.8 830.8,274.2 830.4,274.2 829.8,274.3 829.1,274.6 828.9,274.2 828.4,274.5 828.0,274.6 827.4,274.8 827.0,274.8 826.4,275.0 825.9,275.1 825.4,275.2 824.9,275.2 824.4,275.0 824.5,274.5 824.4,274.1 824.5,273.7 824.9,273.6 825.5,273.2 826.0,273.4 826.6,273.4 826.9,273.8 827.2,274.2 827.7,274.2 828.1,274.0 828.1,273.5 827.7,273.5 827.3,273.2 827.0,272.8 827.4,272.5 828.1,272.6 828.3,273.0ZM824.0,273.9 823.6,274.5 823.0,274.7 822.3,274.6 821.9,274.5 822.3,274.3 822.4,273.9 822.4,273.4 822.8,273.0 823.3,272.8 824.0,273.0 824.0,273.9ZM820.7,272.7 821.4,273.2 821.0,273.7 820.4,273.9 820.1,274.3 819.7,274.5 819.7,274.0 819.3,273.6 818.7,273.3 818.2,273.2 818.0,272.8 818.4,272.6 819.0,272.7 819.4,272.7 819.9,272.4 820.4,272.5ZM794.6,254.6 794.8,255.0 795.0,256.1 795.5,256.8 796.7,257.1 796.3,257.5 796.1,258.0 796.3,258.5 795.8,258.4 795.4,258.2 794.8,257.9 794.4,257.8 794.2,257.3 794.3,256.9 793.9,256.4 793.6,255.9 793.2,255.8 792.6,255.9 792.0,255.7 792.2,255.3 792.7,255.0 792.7,254.6 793.3,254.2 793.5,254.7 793.7,254.3 794.2,254.2 794.6,254.6ZM562.8,87.2 563.2,87.2 563.8,87.2 564.7,87.5 564.2,87.7 563.8,87.9 563.2,88.2 562.5,88.2 562.1,88.3 561.7,88.7 561.3,89.1 561.5,88.6 560.8,88.2 561.1,87.8 560.7,87.5 561.1,87.5 561.6,87.5 562.0,87.3 562.4,87.2 562.8,87.2ZM560.0,31.7 560.4,31.7 561.2,31.7 561.7,32.2 561.9,32.7 562.4,32.7 563.2,32.7 563.9,32.6 564.9,32.8 564.3,33.1 564.8,33.5 565.8,33.7 566.3,33.7 567.3,33.6 568.3,33.8 569.2,34.0 567.0,34.3 566.5,34.6 565.9,34.8 565.3,35.0 564.9,35.1 564.2,35.0 563.6,35.2 562.6,35.4 562.3,34.9 563.0,34.7 562.4,34.5 561.8,34.6 561.3,34.7 560.7,34.7 558.5,34.9 558.1,34.8 558.9,34.4 559.3,34.0 560.0,33.6 558.9,33.3 558.4,33.2 557.9,32.9 557.0,32.4 556.6,32.2 556.2,32.0 556.6,31.9 558.5,31.8 559.6,31.7 560.0,31.7ZM672.7,25.5 672.5,25.9 671.6,26.1 671.1,26.3 670.2,26.4 669.6,26.6 668.7,26.6 667.4,26.4 666.4,26.5 665.7,26.6 664.8,26.4 665.0,25.8 665.4,25.6 665.9,25.5 666.9,25.4 667.3,25.5 668.0,25.5 668.9,25.5 670.3,25.4 671.1,25.3 671.8,25.3 672.5,25.4ZM631.8,25.4 633.1,25.5 634.0,25.5 634.6,25.5 635.2,25.8 633.5,25.9 632.7,25.7 632.2,25.7 631.7,25.9 631.1,26.1 630.6,26.2 629.7,26.2 629.2,26.5 628.8,26.5 628.2,26.5 627.8,26.3 626.8,26.3 626.1,26.2 625.4,26.1 624.7,26.1 625.3,26.0 628.7,25.7 630.0,25.7 630.6,25.5 631.5,25.4ZM639.7,25.2 640.1,25.2 641.1,25.2 642.9,25.7 643.3,25.7 642.1,26.1 641.6,26.3 639.7,26.3 638.5,26.4 637.7,26.7 635.8,26.8 635.2,26.9 635.9,27.0 635.5,27.3 634.9,27.2 634.4,27.5 633.8,27.4 633.4,27.5 632.6,27.5 633.3,27.2 632.3,27.1 631.8,27.1 631.2,27.3 630.5,27.3 630.1,27.1 629.6,26.9 631.7,26.5 632.4,26.4 633.0,26.3 633.9,26.3 634.5,26.2 635.1,26.4 636.4,26.3 636.5,25.8 637.5,25.4 639.2,25.2 639.7,25.2ZM639.6,57.8 639.5,58.2 639.1,58.0 638.7,58.2 637.8,58.7 636.6,59.0 635.9,59.0 635.2,59.1 634.6,58.9 634.2,58.5 634.1,58.1 634.2,57.6 635.1,57.1 635.7,57.0 636.7,56.9 638.9,57.5 639.4,57.6ZM667.9,55.7 668.0,56.1 667.6,56.3 666.7,56.3 666.1,56.4 665.7,56.3 665.3,55.9 664.9,55.9 664.3,55.8 663.9,55.9 663.0,55.4 662.4,54.8 662.8,54.6 663.3,54.4 663.9,54.3 664.7,54.6 665.1,54.7 665.7,55.0 666.5,55.3 667.1,55.5 667.8,55.7ZM696.3,47.0 695.5,47.1 694.8,47.1 694.2,47.0 694.5,46.6 694.4,46.2 694.9,46.0 695.4,45.9 697.1,45.8 697.6,45.9 698.2,46.2 698.9,46.4 698.2,46.8 696.9,46.9 696.3,47.0ZM757.5,28.7 756.8,28.7 756.0,28.7 754.7,28.4 753.8,28.2 753.1,28.0 754.0,27.6 754.9,27.6 756.0,27.6 757.2,27.8 759.7,27.9 760.6,28.0 760.0,28.3 759.4,28.4 758.8,28.5 758.1,28.6 757.5,28.7ZM815.0,43.3 814.6,43.7 813.8,43.9 813.4,44.2 811.7,44.0 810.9,43.8 810.1,43.7 809.7,43.5 810.1,43.4 810.8,43.4 811.1,43.0 813.8,43.1 814.7,43.2ZM891.7,44.4 890.3,44.7 889.4,44.4 889.2,43.9 889.7,43.7 891.2,43.7 891.8,43.8 891.8,44.3ZM566.3,151.3 566.7,151.3 567.1,151.1 567.2,151.5 567.6,151.8 568.2,151.7 568.7,151.6 569.5,151.6 570.3,151.8 570.8,151.9 571.5,151.8 571.5,152.3 571.9,152.3 572.3,152.2 572.7,152.2 573.1,151.9 573.0,152.3 572.9,152.7 572.4,152.7 571.7,152.7 571.1,152.8 570.0,152.9 568.9,153.0 568.7,152.6 568.3,152.5 566.7,152.2 566.3,152.1 565.8,152.1 565.4,152.0 565.4,151.6 565.6,151.2 566.0,151.0ZM565.0,141.8 565.3,142.2 565.7,142.3 566.3,142.5 566.9,142.6 567.3,143.0 567.3,143.5 567.7,144.0 568.2,144.0 568.3,144.4 567.9,144.4 567.5,144.3 567.2,143.9 566.8,143.5 566.3,143.3 565.7,143.2 565.4,142.8 564.9,142.4 564.3,142.1 563.7,142.1 564.3,141.7 564.8,141.6ZM595.7,151.1 594.3,152.0 594.3,152.4 594.6,152.8 594.0,152.9 593.6,152.9 593.1,153.3 592.5,153.6 592.0,153.6 591.7,154.0 591.3,153.7 590.8,153.8 590.3,153.6 589.8,152.9 589.7,152.5 590.2,152.5 590.7,152.3 591.3,152.3 591.5,151.7 592.0,151.8 592.5,151.8 592.9,151.8 593.4,151.8 594.6,151.5 595.0,151.3 595.6,151.0 596.0,150.9ZM553.0,89.3 552.6,89.6 552.2,90.3 552.2,90.7 551.5,91.1 551.1,91.4 550.7,91.9 550.8,91.4 550.3,90.9 550.4,90.4 550.6,90.0 551.1,89.6 551.5,89.4 552.0,89.3 552.5,89.1 553.0,89.3ZM534.9,95.0 534.8,95.4 534.2,95.6 534.1,96.1 534.5,96.4 533.6,96.7 533.5,97.3 533.5,97.7 533.0,97.9 532.6,97.5 532.5,97.1 532.4,96.7 531.9,96.6 531.4,96.7 531.0,96.3 531.1,95.9 530.8,95.5 531.2,95.2 531.8,94.8 532.3,94.6 532.5,95.2 532.9,95.1 533.2,94.7 533.4,94.3 533.9,94.1 534.5,94.1 534.9,94.3 534.8,94.7ZM529.6,95.5 529.8,96.0 530.0,96.5 530.0,96.9 529.5,97.1 529.0,97.1 528.5,97.0 527.7,96.8 527.4,96.2 527.4,95.8 527.8,95.7 528.6,95.5 529.0,95.7 529.5,95.5ZM508.7,139.5 509.3,139.5 509.5,139.9 509.1,140.3 508.8,140.7 508.1,140.6 507.7,140.5 507.3,140.1 506.9,140.3 507.7,139.3 508.1,139.1 508.8,139.0 508.7,139.5ZM165.1,454.9 165.6,455.2 163.9,456.0 163.9,456.4 160.3,456.7 158.7,456.5 158.9,456.1 159.1,455.5 158.5,455.2 157.8,455.1 157.4,455.1 158.0,454.7 158.6,454.7 159.9,454.7 161.2,454.8 162.5,454.8 164.7,454.9 165.1,454.9ZM149.1,453.6 149.8,453.7 150.4,453.9 151.0,454.0 151.4,454.3 151.9,454.5 151.1,454.8 150.5,454.8 150.9,455.1 151.9,455.0 152.5,455.1 153.6,454.9 154.1,454.8 155.2,455.1 155.2,455.5 155.7,455.6 156.1,455.8 156.0,456.2 155.6,456.3 155.0,456.2 153.1,456.1 152.5,456.1 151.6,455.7 150.9,455.7 150.3,455.4 149.3,455.3 148.7,455.0 148.4,454.6 148.0,454.6 147.5,454.7 146.9,454.8 146.6,454.4 146.1,454.2 146.1,453.8 146.5,453.6 146.9,453.6 147.3,453.6 147.7,453.6 148.3,453.6 149.1,453.6ZM316.2,472.4 315.8,472.4 314.2,472.0 313.9,471.6 311.9,471.2 312.7,471.0 313.9,471.0 314.5,471.1 315.9,471.1 316.6,471.2 317.0,471.5 317.8,471.6 318.0,472.1 316.9,472.3 316.2,472.4ZM339.3,427.9 339.7,427.8 340.3,427.7 341.0,428.0 341.1,428.4 340.5,428.6 340.1,428.7 339.7,428.6 339.2,428.9 338.8,428.7 338.4,428.8 338.0,428.7 338.8,428.4 338.4,428.2 337.9,428.1 338.1,427.5 338.5,427.4 339.0,427.3 339.4,427.2 339.4,427.6ZM324.5,429.1 324.9,429.3 325.5,429.4 324.9,429.5 324.4,429.7 324.0,429.8 323.5,429.8 322.9,430.1 322.2,429.9 321.7,429.7 322.6,429.2 323.1,428.9 323.5,428.5 324.1,428.5 324.5,429.1ZM295.3,446.2 295.7,446.5 295.3,446.6 293.9,447.0 293.0,447.2 292.2,447.3 288.4,447.6 287.9,447.5 287.5,447.2 288.2,446.8 288.8,446.8 291.1,446.6 291.5,446.4 292.0,446.1 292.8,446.6 293.2,446.1 293.8,446.2 294.3,446.0 294.8,446.1 295.3,446.2ZM291.7,443.7 292.2,443.8 292.9,444.1 292.6,444.8 292.1,444.9 290.9,444.9 289.6,444.7 289.6,444.2 290.7,444.0 291.2,443.7 291.7,443.7ZM227.5,449.8 227.3,450.3 228.0,450.3 228.3,449.8 228.9,449.7 228.9,450.3 229.3,450.5 229.9,450.4 230.3,449.8 230.9,449.6 232.3,449.5 233.0,449.7 232.5,450.1 231.4,450.4 230.6,450.6 231.1,450.7 232.0,450.6 233.6,450.3 234.2,450.2 234.6,450.7 234.5,451.1 233.8,451.2 233.1,451.3 231.4,451.5 230.8,451.6 229.9,451.4 229.5,451.4 228.9,451.5 228.3,451.5 227.3,451.5 226.6,451.5 226.0,451.4 225.3,451.3 224.6,451.3 223.8,451.1 223.1,451.1 222.2,450.9 221.7,450.8 221.2,450.8 217.8,450.5 217.3,450.5 216.9,450.5 215.9,450.4 216.0,450.0 221.1,449.6 221.6,449.5 222.0,449.5 222.7,450.1 223.4,449.8 224.3,449.9 224.8,449.8 226.1,449.3 226.7,449.4 227.3,449.5ZM293.5,453.1 293.1,453.4 292.6,453.5 293.1,454.0 292.9,454.5 289.2,453.7 288.7,453.5 288.7,453.1 289.2,452.9 290.3,453.1 290.9,452.9 290.4,452.8 290.0,452.6 289.6,452.4 290.6,452.3 293.1,452.5 293.5,452.6 293.5,453.1ZM311.1,437.4 310.6,437.7 310.2,437.6 309.7,438.1 309.3,438.1 308.8,438.2 308.4,438.0 308.0,437.7 308.1,437.2 308.8,436.8 309.3,436.3 310.0,435.7 311.3,435.2 311.9,435.1 311.3,435.7 311.2,436.1 311.6,436.3 312.0,436.5 311.5,436.7 311.0,436.9 310.6,437.1 311.1,437.3ZM0.0,51.3 0.4,51.2 0.9,51.2 1.3,51.2 1.7,51.2 2.1,51.2 2.5,51.1 3.1,51.2 4.3,51.3 5.0,51.4 5.4,51.6 6.1,51.8 6.7,52.0 6.1,52.6 5.4,52.7 4.1,52.7 2.3,52.9 1.6,53.0 0.7,52.9 0.0,52.8 999.7,52.8 999.0,53.1 997.6,53.1 996.8,53.3 996.2,52.8 996.7,52.3 997.9,51.9 998.7,51.5 999.2,51.5 999.7,51.3 0.0,51.3ZM336.5,392.4 337.0,392.6 337.5,392.5 337.5,393.0 338.1,393.3 338.1,392.9 339.0,392.7 339.4,393.1 339.0,393.3 339.5,393.4 338.9,393.7 338.5,393.8 338.0,394.1 337.0,394.3 337.1,394.7 335.7,394.5 335.9,394.9 335.4,395.0 335.0,395.3 334.6,395.1 334.3,394.7 334.5,394.2 335.3,393.8 335.8,393.6 335.8,393.0 336.4,392.7ZM396.9,400.2 397.4,400.2 397.8,400.3 398.3,400.5 398.8,400.9 399.2,400.8 399.7,401.3 400.1,401.6 400.2,402.0 400.6,402.1 400.2,402.3 399.8,402.4 399.3,402.2 398.8,401.6 398.3,401.4 397.7,401.1 397.2,400.9 396.8,400.8 395.8,400.4 395.3,400.4 394.7,400.1 395.7,400.0 396.2,400.0 396.6,400.2ZM295.1,370.4 294.7,370.5 294.1,370.4 293.5,370.2 293.6,369.7 293.9,369.1 294.0,368.3 293.9,367.9 294.0,367.4 294.2,367.0 294.4,366.4 295.2,366.3 295.8,366.4 295.9,366.8 296.0,367.2 295.8,367.8 295.4,368.1 295.0,368.3 295.4,368.6 295.8,369.0 295.9,369.4 295.4,369.8 295.2,370.3ZM332.5,392.9 332.9,393.0 333.3,392.8 333.8,392.8 334.7,392.8 335.2,392.7 335.1,393.1 334.7,393.4 334.1,393.9 333.6,394.4 333.0,394.4 332.5,394.7 332.1,394.9 331.4,395.0 330.7,394.6 331.2,394.3 331.7,394.3 332.1,394.1 332.5,393.9 331.9,393.8 332.6,393.5 332.2,393.2 331.8,392.7 332.5,392.9ZM293.1,386.5 293.1,386.9 293.1,387.3 293.0,387.8 293.1,388.3 292.9,388.7 292.5,388.9 292.2,388.4 292.0,388.0 292.2,387.5 291.8,387.6 291.7,388.2 291.2,388.5 290.8,388.5 290.4,388.3 290.2,387.8 290.7,387.9 290.8,387.5 290.4,387.1 290.8,386.9 291.4,386.9 291.1,386.5 291.6,386.2 291.8,385.8 292.0,385.4 292.4,385.3 292.9,385.4 293.1,386.5ZM297.3,374.4 296.6,374.6 296.1,374.4 296.0,374.0 296.4,373.6 296.5,373.2 297.1,373.3 297.7,373.5 297.7,374.0 297.3,374.4ZM328.3,113.6 327.2,113.7 326.2,113.5 325.6,113.4 324.9,113.3 323.4,112.8 323.1,112.4 322.5,112.1 321.0,111.6 321.5,111.3 321.9,111.3 322.9,111.5 324.2,111.6 324.8,111.7 325.4,111.9 326.0,112.2 327.4,112.8 328.3,113.1 328.6,113.5ZM330.3,122.4 330.7,122.3 330.6,122.7 331.1,123.0 332.0,122.4 331.4,122.4 331.7,122.0 332.1,121.5 332.5,121.4 333.1,121.6 333.7,121.8 333.5,122.2 333.3,122.6 332.8,122.9 332.3,123.2 331.5,123.4 330.9,123.3 330.3,123.4 329.9,123.4 329.4,123.1 329.2,122.4 329.4,121.8 329.9,121.4 330.6,120.4 330.9,120.0 331.6,119.5 332.0,119.4 332.4,120.1 332.3,120.5 332.0,121.1 332.0,121.5 331.3,122.0 330.8,122.1 330.3,122.4ZM23.7,73.2 24.1,73.3 24.5,73.3 24.9,73.4 25.4,73.4 25.9,73.1 26.6,73.1 27.0,73.1 27.4,73.3 28.4,73.8 28.8,73.8 29.4,74.0 29.9,74.0 30.6,74.0 31.3,74.1 31.0,74.5 30.3,74.5 29.5,74.5 29.0,74.8 28.6,75.0 28.2,74.6 27.8,74.5 27.3,74.5 26.9,74.1 26.3,73.9 25.4,73.8 24.8,73.8 24.2,74.0 23.6,74.1 23.2,74.0 22.8,73.8 22.8,73.4 22.9,73.0 23.7,73.2ZM38.5,82.3 38.9,82.4 39.3,82.4 39.7,82.6 39.7,83.1 40.0,83.6 39.5,83.6 39.0,83.6 38.6,83.8 38.2,83.8 37.2,83.7 36.2,83.4 35.7,83.3 35.3,83.1 34.9,82.8 35.4,82.7 36.6,82.7 37.2,82.4 37.6,82.3 38.2,82.2ZM45.9,97.3 46.2,97.7 46.7,97.9 46.2,98.0 45.8,98.2 44.2,98.3 43.8,98.4 43.3,98.8 42.8,98.9 42.4,98.9 42.0,98.7 42.0,98.3 42.4,98.2 43.0,97.6 43.7,97.5 44.8,97.1 45.5,97.1 45.9,97.3ZM285.4,198.7 286.1,198.9 286.7,199.2 287.9,199.6 288.2,200.1 287.7,200.4 287.2,200.3 286.7,200.2 286.3,200.4 285.9,200.3 285.5,200.8 285.1,200.5 284.3,200.4 283.8,200.0 283.4,199.9 283.2,199.5 282.5,199.4 282.4,199.0 283.1,198.8 283.5,198.6 284.9,198.7 285.4,198.7ZM316.3,198.8 317.0,198.8 317.7,198.9 317.7,199.3 317.3,199.6 316.7,200.1 316.3,200.1 315.9,200.1 315.5,200.1 315.0,200.1 314.5,200.0 314.0,200.1 313.5,200.1 313.4,199.6 313.3,199.2 313.4,198.8 314.4,198.6 316.1,198.7ZM75.3,89.4 75.8,89.4 76.4,89.3 76.4,89.7 77.1,90.0 76.7,90.4 76.0,90.4 75.5,90.3 75.0,90.4 75.6,90.4 75.6,90.8 75.0,90.9 74.2,91.0 73.8,91.2 73.4,91.5 73.0,91.5 72.9,92.1 72.3,92.3 72.8,91.7 72.2,91.5 71.5,91.3 71.7,91.7 71.3,91.9 70.8,91.6 70.6,91.1 70.3,90.7 70.7,90.1 71.2,90.0 71.7,89.9 72.1,89.9 72.6,90.4 72.9,90.8 72.8,90.4 72.7,90.0 73.1,89.9 72.6,89.6 72.8,89.2 73.2,89.3 73.6,89.6 74.0,89.4 74.4,89.5 74.5,89.1 75.2,89.1ZM76.6,87.9 77.2,87.9 77.6,88.0 77.6,88.4 77.2,88.3 76.7,88.5 76.3,88.6 75.9,88.7 75.4,88.8 74.2,88.7 74.7,88.2 75.1,88.1 75.5,88.1 75.4,87.7 75.9,87.6 76.3,87.7ZM136.2,95.9 135.9,96.5 135.4,96.6 134.9,96.5 134.9,96.1 134.3,96.4 134.0,96.8 133.8,96.2 134.0,95.8 134.4,94.9 135.4,94.6 135.8,94.8 136.1,95.2 136.2,95.6ZM129.0,93.5 129.5,93.6 130.0,93.6 130.3,94.0 131.2,94.5 131.7,94.7 132.1,95.3 132.5,95.7 132.9,95.9 132.5,95.8 131.9,95.6 132.6,96.1 132.8,96.5 133.3,96.6 133.3,97.1 133.3,97.5 133.3,98.0 132.8,98.0 132.4,97.5 132.0,97.4 131.6,96.9 131.2,97.1 130.8,96.7 130.4,96.4 130.3,95.8 129.9,95.6 129.5,95.3 129.0,95.3 128.9,94.9 129.3,95.0 129.7,94.9 129.8,94.4 129.1,94.4 128.7,94.6 128.9,94.2 128.9,93.8ZM129.5,91.7 130.0,91.7 130.4,91.7 130.7,92.1 130.7,92.5 130.2,92.5 129.6,92.1 129.9,92.5 130.2,92.9 129.5,93.1 128.9,93.2 128.7,92.8 128.7,92.2 128.3,91.9 127.8,91.6 128.2,91.5 128.6,91.5 129.5,91.7ZM125.1,90.7 125.5,91.2 125.9,92.1 126.1,92.5 126.0,92.9 126.0,93.6 125.5,93.7 125.1,93.2 125.1,92.8 124.7,92.5 124.2,92.2 124.4,91.6 124.0,91.1 123.6,91.0 123.3,91.5 122.7,91.6 122.7,91.0 123.1,90.7 123.7,90.2 124.6,90.5 125.1,90.7ZM125.9,88.4 126.6,88.5 127.1,88.5 127.6,88.9 128.0,89.5 128.3,89.9 127.8,89.5 127.3,88.9 126.9,88.8 127.0,89.2 127.5,89.7 127.9,90.0 127.9,90.4 127.5,90.8 127.1,91.3 126.6,91.5 126.2,91.5 126.1,91.1 126.4,90.5 126.1,90.1 125.9,89.6 125.7,88.9 125.5,88.5 125.2,88.0 125.7,88.3ZM123.0,88.2 123.4,88.5 123.1,88.9 123.8,88.6 124.6,88.6 125.0,88.7 125.1,89.4 124.7,89.5 124.1,89.5 125.1,89.7 125.2,90.3 124.8,90.2 124.4,90.1 123.6,89.8 123.3,90.3 122.5,90.4 122.0,89.8 121.2,89.3 120.6,89.0 120.8,88.6 121.2,88.5 121.8,88.6 122.2,88.3 122.6,88.2 123.0,88.2ZM142.9,102.9 143.0,103.8 142.8,104.3 142.6,104.8 142.4,104.3 142.3,103.9 142.4,103.4 141.9,103.7 141.4,103.3 141.2,102.9 141.4,102.5 141.5,102.1 142.1,102.1 142.7,102.5 142.9,102.9ZM131.5,99.6 132.4,99.7 132.9,100.1 132.7,100.5 131.8,100.9 132.8,100.9 132.9,100.5 133.0,100.0 133.5,99.9 134.2,99.6 134.2,100.0 133.8,100.4 133.6,100.8 133.5,101.7 132.4,102.2 131.9,102.2 131.5,102.0 132.1,101.8 131.5,101.5 131.0,101.4 130.8,101.0 130.4,100.6 130.3,100.2 130.4,99.6 130.9,99.6 131.5,99.6ZM134.0,102.2 134.4,102.7 133.9,103.1 133.4,103.1 133.9,103.3 134.4,103.5 134.9,103.6 134.5,103.8 134.9,104.3 135.4,104.4 135.8,104.9 134.9,104.9 134.6,104.4 133.9,104.0 133.1,103.5 132.7,103.1 132.0,102.6 132.4,102.4 132.9,102.3 133.4,102.2 134.0,102.2ZM322.7,120.9 323.1,120.7 323.5,120.7 323.9,120.8 324.6,121.1 325.1,121.0 325.8,121.0 326.6,120.9 327.3,120.9 327.7,121.0 327.3,121.2 326.9,121.4 326.3,121.7 326.3,122.1 325.7,122.3 325.3,122.2 324.9,122.0 325.3,121.9 324.8,121.6 324.2,121.7 324.6,121.7 324.2,121.8 323.4,121.6 323.0,121.4 322.6,121.1 322.2,121.1 321.9,120.7 321.1,120.4 321.4,119.9 321.8,119.6 322.2,119.3 322.3,119.7 322.0,120.1 322.5,120.4 322.5,120.8ZM298.6,136.2 299.2,136.0 299.6,136.0 300.0,136.0 299.1,136.4 298.5,136.6 298.1,136.7 296.7,137.1 295.5,137.2 295.1,137.2 294.7,137.3 294.5,136.9 295.1,136.6 295.5,136.4 295.9,136.3 296.5,136.3 296.9,136.2 297.7,136.2 298.3,136.1 299.0,135.8 298.6,136.2ZM922.0,261.9 922.5,261.7 923.0,262.0 923.2,262.5 923.3,262.9 923.2,263.4 922.9,263.8 922.3,263.9 922.2,264.3 922.6,264.9 922.1,265.4 921.4,265.4 920.9,265.4 920.7,265.8 920.4,266.2 919.7,266.7 919.2,266.7 918.8,267.0 918.3,267.2 917.9,267.4 917.2,267.5 916.2,267.5 915.7,267.5 915.2,267.0 914.6,266.9 914.2,267.0 913.4,266.4 912.8,266.2 912.2,266.0 912.1,265.4 912.7,265.3 913.1,265.3 913.9,265.2 914.6,265.5 915.2,265.5 915.6,265.3 916.2,265.3 916.6,265.1 916.7,264.3 916.9,263.9 917.0,264.3 916.9,264.7 917.0,265.1 917.5,265.4 918.1,265.2 918.7,265.3 919.2,265.1 919.6,264.5 920.3,263.8 920.7,263.7 921.3,263.6 921.3,263.2 921.0,262.1 921.1,261.7 921.7,261.7ZM853.6,273.4 853.1,273.8 852.5,274.2 851.8,274.4 851.3,274.8 850.7,274.9 850.2,275.1 849.8,275.3 849.3,275.4 848.4,275.8 847.8,276.1 847.2,276.6 846.8,277.1 846.4,277.5 845.9,278.0 845.3,278.3 844.9,278.3 844.4,278.6 844.0,278.7 843.5,278.6 843.5,278.2 843.3,277.8 843.4,277.3 843.6,276.7 844.1,276.3 845.0,275.7 846.0,275.4 846.4,275.2 846.9,274.9 847.3,274.6 847.5,274.2 848.1,273.9 849.5,273.6 850.5,273.6 851.5,273.5 852.0,273.4 852.5,273.2 852.9,273.2 853.4,273.3ZM902.9,363.3 903.4,363.2 904.0,363.5 904.4,363.6 904.8,363.8 905.9,364.2 906.4,364.3 907.1,364.3 907.6,364.1 908.1,363.9 908.6,363.9 909.1,363.8 909.6,363.9 909.9,363.5 910.6,363.5 911.0,363.3 911.7,363.5 911.9,364.2 912.0,364.9 911.9,365.4 911.9,366.2 911.9,366.7 912.0,367.1 911.6,366.8 911.2,367.4 911.1,367.9 910.9,368.3 910.9,368.9 911.0,369.3 911.1,369.9 910.7,370.0 910.3,369.8 910.2,369.4 910.6,369.4 909.9,369.0 909.9,369.4 909.5,369.2 909.1,369.5 909.0,370.0 908.3,369.9 908.4,370.3 908.2,370.8 907.9,371.2 907.5,371.1 907.1,370.9 906.7,370.9 906.1,370.9 905.7,371.0 905.5,370.6 905.9,370.4 905.5,370.2 905.0,370.1 904.7,369.7 904.3,369.4 903.5,368.2 903.3,367.3 903.8,367.6 904.1,368.0 903.8,367.3 903.4,367.2 903.4,366.7 902.9,366.2 902.5,365.7 902.2,365.1 901.9,364.4 901.8,363.8 902.0,363.3 902.9,363.3ZM768.1,24.8 768.6,25.0 770.6,25.4 771.4,25.5 771.8,25.6 771.3,25.9 770.1,26.0 769.5,26.3 770.1,26.8 770.6,26.9 769.9,27.1 766.3,27.3 763.8,27.4 762.9,27.4 762.0,27.6 760.8,27.8 760.1,27.8 758.3,27.5 756.1,27.3 755.3,27.1 754.5,27.0 754.7,26.6 755.3,26.4 756.2,26.4 757.2,26.3 757.8,26.1 758.3,25.8 759.1,25.6 757.7,25.6 757.2,25.6 757.7,25.3 758.2,25.2 759.3,24.9 759.7,24.9 760.1,24.9 760.8,24.8 761.5,24.8 762.2,24.7 762.8,24.7 763.4,24.6 764.1,24.5 766.1,24.2 766.6,24.4 767.2,24.5 768.0,24.7ZM771.3,27.3 772.0,27.5 771.7,27.9 771.2,28.2 771.9,28.2 772.4,28.1 773.0,28.1 773.6,27.9 774.6,27.6 775.8,27.7 776.3,27.9 777.0,28.0 777.6,28.2 777.3,28.7 777.1,29.1 777.0,29.5 776.5,29.8 776.1,29.8 775.5,29.7 775.1,29.7 775.9,29.9 776.4,30.2 777.1,30.3 777.5,30.5 776.5,31.0 774.5,31.1 773.4,31.1 773.0,31.1 772.4,31.1 772.0,31.1 771.4,31.0 771.0,31.0 770.1,30.9 769.3,30.7 768.9,30.6 767.9,30.5 766.1,30.6 765.4,30.3 764.3,30.4 763.9,30.4 763.3,30.3 762.9,30.2 762.4,30.0 762.0,29.7 760.4,29.3 759.7,29.3 759.1,29.3 758.5,29.2 759.5,28.8 760.7,28.6 761.2,28.5 761.8,28.3 762.1,27.9 763.1,27.7 763.7,27.5 764.7,27.7 765.3,27.5 766.3,27.5 767.1,27.5 767.8,27.5 769.8,27.4 771.1,27.3ZM785.8,29.8 785.4,30.3 784.6,30.9 785.0,30.9 785.4,30.7 786.0,30.4 786.7,30.4 787.3,30.2 788.0,30.1 788.7,30.2 789.1,30.5 790.0,30.6 790.6,31.0 791.3,31.0 791.7,31.0 792.1,31.1 792.5,31.5 792.5,31.9 791.2,32.4 790.3,32.4 789.7,32.4 788.1,32.6 786.1,32.6 785.5,32.8 785.0,32.7 783.8,32.8 782.5,32.8 781.1,32.8 780.7,32.9 779.3,33.2 778.0,33.4 777.3,33.5 776.4,33.4 775.8,33.2 776.2,33.1 776.9,32.7 777.8,32.4 778.1,32.0 778.5,31.7 778.9,31.2 779.5,31.1 780.2,31.2 780.2,30.8 780.6,30.5 781.0,30.1 781.4,29.9 782.1,29.8 782.7,29.5 783.1,29.7 783.7,29.9 783.8,29.5 784.5,29.4 785.5,29.5 786.2,29.6 785.8,29.8ZM889.0,39.4 889.6,39.4 890.0,39.5 890.4,39.8 891.2,39.9 891.5,39.4 891.6,39.0 892.5,38.7 893.0,38.5 893.7,38.6 894.4,38.8 895.7,39.2 896.3,39.3 897.0,39.4 897.7,39.4 898.1,39.4 898.8,39.3 903.5,40.0 902.8,40.3 902.2,40.5 902.5,40.9 901.1,41.4 900.6,41.5 900.1,41.5 899.0,41.4 898.3,41.4 897.7,41.3 897.0,41.1 896.5,40.7 896.5,40.2 896.9,40.1 897.1,39.7 896.0,39.7 895.3,39.7 894.7,39.8 894.9,40.4 896.2,41.3 897.0,41.5 897.6,41.8 896.6,42.0 896.2,42.1 895.8,42.2 895.2,42.1 894.7,41.8 893.7,41.7 893.1,41.8 892.5,41.9 890.7,42.0 890.2,42.1 889.6,42.1 888.9,42.0 888.2,41.8 887.8,41.8 887.3,42.4 886.7,42.6 886.1,42.6 885.7,42.5 883.6,42.2 883.1,42.0 882.4,41.6 881.8,41.5 881.2,41.3 880.6,41.0 881.0,40.7 881.4,40.7 881.1,40.1 881.3,39.6 882.5,39.6 881.9,39.1 882.3,38.9 882.7,38.8 883.3,38.8 883.9,38.6 884.5,38.5 885.6,38.3 886.2,38.4 886.7,38.7 887.6,38.9 888.2,39.0 889.0,39.4ZM895.0,44.7 895.6,44.9 896.2,45.0 898.2,45.6 898.6,46.5 897.8,46.6 896.8,46.5 896.1,46.5 895.4,46.5 894.8,46.4 893.3,46.4 892.2,46.1 891.0,46.0 890.0,46.0 889.0,46.2 888.3,46.2 888.7,46.0 889.3,46.0 889.9,45.9 890.5,45.7 891.3,45.1 891.9,44.8 892.5,44.8 893.6,44.7 894.3,44.7 895.0,44.7ZM907.8,40.6 908.5,40.7 909.6,40.4 912.3,40.5 912.4,40.9 913.6,41.0 914.1,40.9 915.7,41.0 917.0,41.1 917.4,41.2 917.8,41.3 918.4,41.3 918.8,41.2 918.5,41.8 917.6,42.0 916.2,42.2 915.5,42.3 914.0,42.3 911.9,42.2 911.4,42.2 911.0,42.1 910.4,41.9 909.0,41.7 908.1,41.5 907.5,41.3 906.0,41.1 906.3,40.6 906.8,40.1 907.6,40.3ZM981.3,347.0 981.8,346.8 982.5,347.2 982.9,347.3 983.6,347.6 983.7,348.0 984.1,347.9 984.5,348.4 984.8,348.8 984.9,349.4 984.4,349.4 984.9,350.0 985.6,350.9 985.4,351.4 985.5,351.8 985.4,352.2 985.8,352.5 986.2,352.5 986.8,352.7 987.1,353.2 987.6,353.3 987.6,352.9 987.5,352.4 987.5,351.9 987.2,351.5 987.6,351.6 988.0,352.1 988.4,352.4 988.7,353.3 988.9,354.0 989.2,354.4 989.6,354.6 990.6,355.1 991.0,355.2 992.1,355.5 992.6,355.5 993.2,355.3 993.7,354.7 994.2,354.5 995.2,354.4 995.8,354.6 995.7,355.2 995.4,356.1 995.3,356.8 994.9,357.3 994.4,357.6 994.2,358.4 994.2,359.0 993.8,358.6 993.1,358.5 992.5,358.7 992.0,358.9 991.5,359.4 991.5,359.9 992.0,360.2 991.6,360.9 991.2,361.6 990.8,361.9 990.6,362.3 990.2,362.7 989.8,363.2 989.2,364.0 988.8,364.5 988.4,364.8 988.0,365.0 987.3,365.4 986.7,365.5 986.6,365.1 985.8,365.1 985.8,364.7 985.4,364.8 985.7,364.1 986.2,363.5 986.6,362.8 986.8,361.9 986.5,361.4 986.1,361.0 985.6,360.7 985.2,360.7 984.6,360.4 983.7,359.9 983.1,359.7 982.7,359.4 982.7,358.9 983.5,358.4 984.2,358.3 984.6,358.1 985.0,357.7 985.0,357.2 985.1,356.7 985.3,356.2 985.6,355.8 985.6,355.3 985.7,354.7 985.4,354.2 985.5,353.7 985.0,353.0 985.4,353.4 985.7,353.0 985.5,352.6 985.0,352.7 984.6,352.6 984.5,352.1 983.9,351.4 984.4,351.7 984.6,351.3 984.3,350.9 984.2,350.5 983.4,350.3 983.1,349.7 983.3,350.4 983.7,350.8 983.3,350.7 981.7,348.7 982.2,348.3 981.8,348.3 981.4,348.5 981.2,348.1 981.1,347.6 980.9,347.0 980.2,346.2 979.7,345.7 980.2,345.6 980.7,345.6 980.6,346.1 981.0,346.7ZM980.9,364.7 981.5,364.5 982.1,364.2 982.6,363.9 983.0,363.7 983.1,364.1 982.8,364.6 983.2,364.2 983.6,363.9 984.0,364.0 983.6,364.5 984.1,364.4 984.0,364.8 983.6,364.9 983.6,365.3 983.6,365.8 984.1,365.9 983.9,366.3 983.5,366.7 983.0,367.0 982.9,367.4 982.2,368.0 981.5,369.0 981.2,369.4 980.8,369.6 980.2,369.8 979.8,370.2 979.7,370.6 979.3,370.7 979.7,370.7 980.0,371.2 980.4,371.3 980.8,371.3 980.9,371.7 980.3,371.9 979.9,371.7 979.3,371.8 978.9,371.6 978.4,371.7 977.9,371.4 978.2,371.8 977.7,372.2 977.2,372.3 976.8,372.5 976.4,372.6 976.0,372.9 975.6,373.7 975.5,374.4 975.4,374.8 975.0,374.8 975.4,374.9 975.0,375.4 974.7,376.0 974.5,376.4 974.2,376.9 974.4,377.3 973.4,377.6 973.0,378.0 972.0,378.7 971.6,379.1 970.4,379.5 969.7,379.5 969.3,379.5 968.8,379.3 968.4,379.4 968.0,379.4 967.6,379.3 967.4,378.9 966.9,378.8 966.4,378.8 965.9,378.4 965.4,378.2 965.0,378.4 964.2,378.5 963.4,378.4 963.7,377.7 963.1,377.9 963.1,377.5 962.5,377.7 962.5,377.3 963.4,377.2 963.8,377.1 963.4,376.9 963.9,376.5 963.5,376.5 963.1,376.5 963.3,376.1 963.7,376.1 964.3,376.1 964.2,375.7 964.0,375.3 964.4,375.3 964.4,374.9 965.0,374.5 965.2,374.9 965.2,374.5 965.8,374.0 966.3,374.0 966.3,373.6 966.7,373.2 967.2,372.8 967.7,372.4 968.5,372.1 968.9,372.2 969.4,371.9 969.8,371.9 970.3,371.4 970.9,371.2 971.3,371.1 971.8,370.9 972.3,370.4 972.7,370.1 973.1,369.8 973.5,369.6 973.9,369.7 974.1,369.3 974.6,369.0 975.0,368.8 975.5,368.1 975.9,367.9 975.8,367.5 976.0,366.9 976.3,366.1 976.9,366.0 977.3,365.7 977.8,365.1 978.0,364.4 978.2,363.7 978.5,363.2 979.1,362.8 979.6,362.5 980.1,362.5 979.7,362.8 979.9,363.3 980.5,363.5 980.7,364.1 980.9,364.7ZM850.0,224.1 850.5,224.2 850.5,224.7 850.8,225.1 850.7,225.7 850.4,226.1 850.8,226.3 851.1,226.9 851.3,227.4 851.2,228.0 851.4,228.5 851.6,229.0 851.6,229.7 851.5,230.1 851.2,230.5 850.8,230.9 850.7,231.3 850.6,232.0 850.5,232.5 850.3,232.0 850.2,231.3 850.0,230.7 849.7,230.2 849.5,229.6 849.1,229.8 849.0,230.3 848.5,230.8 848.3,231.4 848.8,231.9 849.1,232.7 849.1,233.4 848.5,234.3 848.0,234.4 847.9,234.0 848.0,233.5 847.9,233.1 847.4,233.6 847.0,233.7 846.2,233.3 845.5,233.0 845.0,232.7 844.7,232.2 844.6,231.5 844.4,230.9 844.6,230.2 845.0,229.8 844.9,229.3 844.6,228.9 843.8,228.5 843.4,228.2 843.0,228.3 843.0,228.7 842.7,229.4 842.2,229.1 842.0,228.7 841.6,229.0 841.2,229.1 841.1,228.5 840.6,228.4 840.2,228.8 839.8,229.6 839.6,230.1 839.4,230.5 839.0,230.8 838.6,230.3 838.9,229.8 839.2,228.7 839.3,228.3 839.6,227.9 840.0,227.7 840.5,227.5 841.1,227.4 841.7,227.2 841.7,226.8 841.9,226.4 842.5,226.3 842.9,225.8 843.6,226.1 844.0,226.6 844.1,227.3 844.9,227.2 845.2,226.7 845.4,226.2 846.2,226.3 846.6,225.9 846.6,225.4 847.1,225.1 847.6,225.4 847.8,224.9 848.3,225.0 848.7,224.6 848.6,224.2 848.4,223.1 849.0,223.2 849.7,223.6 850.0,224.1ZM836.4,198.3 836.8,198.4 837.8,199.0 838.5,199.2 839.0,199.1 839.3,198.6 839.7,198.9 839.7,199.4 839.4,199.8 839.3,200.7 839.4,201.2 839.5,201.6 839.9,201.8 840.0,202.3 840.2,202.8 840.1,203.3 839.5,204.3 839.3,205.0 838.8,205.1 838.3,205.3 837.8,205.7 837.7,206.2 837.7,206.6 837.4,207.2 837.2,207.6 837.6,208.3 837.9,208.9 837.9,209.5 838.2,210.5 838.5,210.9 839.1,211.3 839.5,211.3 839.4,210.8 840.0,210.4 840.6,210.2 841.0,210.3 841.5,210.6 841.9,211.2 841.8,211.7 842.3,211.8 842.5,211.3 842.6,210.9 843.4,211.4 843.9,211.6 843.3,211.9 843.4,212.4 843.8,212.9 843.9,213.4 844.3,213.6 844.7,213.8 844.8,214.5 844.6,215.1 844.1,214.8 844.1,214.4 843.7,214.2 842.8,213.8 842.5,213.3 842.2,212.9 841.4,212.2 841.1,211.8 840.5,211.4 840.3,211.8 840.5,212.3 840.7,212.8 840.8,213.2 840.3,213.2 840.0,212.5 839.5,212.1 839.1,211.7 838.3,211.3 837.9,211.3 837.5,211.6 837.1,212.1 836.7,212.1 836.1,211.8 835.7,211.4 835.1,211.7 835.0,211.1 835.0,210.6 835.9,209.7 835.9,209.3 835.3,209.0 834.8,209.0 834.9,209.5 834.9,209.9 834.5,209.9 834.3,209.4 834.0,208.9 833.6,208.7 833.4,208.0 833.2,207.4 833.0,206.0 832.7,205.5 832.7,204.8 833.1,204.9 833.7,205.4 834.1,205.4 834.4,204.9 834.2,204.4 834.2,203.8 834.2,203.4 834.5,202.9 834.5,202.5 834.5,202.0 834.5,201.6 834.3,201.0 834.7,199.5 835.0,199.0 835.0,198.6 835.6,198.3 836.3,198.3ZM870.8,252.0 871.5,252.1 872.1,252.1 872.5,252.4 872.4,252.8 872.7,253.3 872.9,253.8 872.8,254.2 872.5,254.8 872.6,255.5 872.7,256.1 873.2,257.3 873.5,257.9 873.6,257.5 873.7,257.0 874.0,257.5 874.2,258.1 874.6,258.1 874.6,258.6 874.8,259.0 875.3,259.3 875.7,259.4 876.3,259.3 876.7,258.9 877.4,258.3 877.7,257.7 878.4,257.2 878.6,256.7 878.9,256.3 879.5,256.2 880.1,256.1 880.8,255.8 880.9,255.2 881.6,254.7 882.3,254.3 882.8,254.1 883.4,254.3 885.1,255.0 885.6,255.3 886.2,255.5 886.8,255.8 887.4,256.1 888.3,256.5 889.3,256.5 889.7,256.7 890.6,256.8 891.0,257.2 892.0,257.3 893.6,257.9 894.0,258.1 894.4,258.2 895.0,258.6 896.0,258.9 897.0,259.2 897.6,259.3 898.3,259.4 899.2,259.9 899.7,260.3 900.2,260.6 900.7,260.6 901.2,260.6 901.7,261.1 902.3,261.4 902.8,261.9 903.4,262.2 904.9,263.4 905.0,264.4 904.8,265.0 905.5,265.3 906.1,265.4 906.7,265.6 908.4,266.4 909.0,266.5 909.4,266.5 909.8,266.7 910.1,267.1 910.4,267.5 910.7,268.2 910.6,268.6 909.3,268.7 908.7,268.7 908.2,269.0 908.6,269.9 908.9,270.5 909.3,270.9 909.8,271.4 910.3,271.9 910.9,272.2 911.5,272.5 911.7,273.2 911.8,273.6 912.3,274.1 912.6,274.8 913.0,275.3 914.2,275.0 914.6,275.2 914.5,275.8 914.6,276.4 915.0,276.6 916.0,276.7 916.6,276.8 916.0,277.2 916.2,277.6 916.6,277.9 917.2,278.1 917.7,278.3 918.2,278.4 919.0,278.4 918.6,278.7 917.9,278.6 918.0,279.0 918.5,279.2 918.0,279.5 917.6,279.6 917.1,279.5 916.7,279.4 916.2,278.9 915.7,278.7 914.9,278.6 913.7,278.5 913.1,278.2 912.7,278.3 912.3,278.3 911.9,278.1 911.5,278.1 910.8,278.0 910.2,277.8 909.7,277.2 909.2,276.6 908.5,276.2 908.1,275.7 908.2,275.2 907.5,275.1 907.0,274.3 906.4,273.5 906.1,272.9 905.8,272.5 905.0,272.2 904.3,272.1 903.6,271.8 903.0,271.7 902.6,271.6 902.4,271.2 901.9,271.2 901.4,271.0 901.0,271.3 900.6,271.6 899.9,271.4 899.5,271.2 899.2,270.8 899.8,272.1 899.4,272.3 898.8,272.2 898.9,272.8 898.5,272.9 898.0,273.0 897.5,273.1 897.0,273.1 896.4,273.0 895.9,273.1 895.5,272.9 895.0,272.8 895.4,272.9 895.8,273.2 896.7,273.2 897.3,273.5 897.8,273.8 898.3,274.3 898.3,274.7 897.8,275.1 897.4,275.3 896.8,275.6 896.2,275.9 895.6,275.7 895.1,275.5 894.4,275.6 893.7,275.6 893.1,275.5 892.5,275.5 892.0,275.6 891.5,275.2 891.1,274.9 890.7,274.6 890.5,274.2 889.2,273.1 888.8,272.7 889.2,272.0 888.7,272.5 888.3,272.5 887.9,272.6 887.5,272.7 887.0,272.7 886.8,272.3 886.3,272.6 885.9,273.0 885.7,272.6 885.9,272.0 886.2,271.4 886.2,270.9 885.8,270.5 885.4,270.1 885.9,270.0 886.3,270.1 885.7,269.8 885.3,269.6 885.0,269.2 885.7,269.1 885.3,268.7 885.1,268.2 884.5,267.6 884.4,267.0 884.2,266.5 883.9,266.1 884.3,265.8 883.8,265.9 883.5,265.4 883.1,264.9 882.7,264.6 881.4,263.9 881.0,263.8 880.6,263.7 880.2,263.6 879.5,263.4 878.9,263.1 878.4,262.9 878.0,262.7 877.0,262.4 876.2,262.3 875.8,262.4 874.3,261.7 874.1,261.3 874.7,260.9 874.3,260.9 873.9,261.0 873.5,261.0 873.0,261.0 872.7,260.6 872.3,260.6 871.8,260.2 871.4,259.9 871.3,259.2 871.6,258.7 871.3,259.3 870.9,259.5 870.9,260.0 870.6,260.4 870.6,260.8 870.1,261.3 869.7,261.3 869.2,261.3 868.9,260.6 869.1,259.9 869.0,259.5 868.2,258.7 867.6,258.3 866.9,258.1 866.6,257.7 867.3,257.4 868.3,257.6 868.7,257.7 869.2,257.4 869.5,256.9 870.0,256.8 870.6,257.0 871.1,257.1 871.5,256.8 871.9,256.7 871.8,256.2 872.0,255.8 871.4,256.1 870.8,256.2 870.4,256.2 869.3,256.3 868.4,256.2 867.8,256.2 867.2,256.0 866.9,255.6 866.5,254.8 866.5,254.3 865.9,254.3 864.7,253.9 864.2,254.0 864.0,253.6 864.4,253.2 864.6,252.8 864.6,252.4 865.2,252.2 866.1,252.0 866.6,251.6 867.0,251.3 867.8,251.0 868.4,251.0 869.0,251.2 869.7,251.4 870.2,251.8 870.8,252.0ZM846.9,247.2 846.4,247.7 846.1,248.2 845.6,248.7 845.2,248.9 844.7,249.0 843.8,249.1 843.4,249.2 842.5,249.1 841.9,248.7 841.4,248.6 839.7,248.7 839.1,248.7 838.4,248.8 837.8,248.6 837.3,248.6 836.1,248.8 835.3,248.6 834.9,248.5 834.3,248.8 833.9,249.3 833.5,249.9 833.4,250.5 833.4,250.9 833.5,251.5 834.0,252.4 834.5,252.7 835.0,253.5 835.4,253.8 835.9,253.8 836.5,253.7 836.9,253.1 837.3,252.6 837.7,252.3 838.2,252.6 838.8,252.6 839.3,252.3 839.7,252.1 840.4,252.1 841.4,252.1 841.7,251.7 842.1,251.6 842.7,251.8 842.9,252.2 842.8,252.7 842.3,252.8 841.8,252.4 841.4,252.5 840.9,253.0 840.3,253.7 839.8,254.2 839.3,254.4 838.5,254.7 838.1,255.2 837.7,255.3 837.2,255.1 837.2,255.5 837.7,256.0 838.1,256.1 838.5,256.5 838.8,257.1 839.1,257.6 839.7,258.1 839.7,258.5 840.0,258.9 839.8,259.4 839.6,259.8 839.7,260.2 840.1,260.4 840.5,260.8 840.7,261.2 841.1,261.3 841.4,261.8 841.3,262.2 840.9,262.1 840.2,262.3 839.5,262.5 839.0,262.8 839.1,263.3 838.7,263.5 838.2,263.4 837.7,263.2 837.5,262.7 837.6,261.9 837.8,261.5 837.3,261.1 835.9,259.9 835.8,259.5 836.2,258.9 836.3,258.4 836.3,258.0 836.3,257.6 835.8,257.3 835.1,257.4 834.3,258.0 834.0,258.5 834.3,259.0 834.5,260.3 834.4,260.7 834.3,261.3 834.4,262.3 834.5,262.8 834.2,263.8 834.1,264.3 834.4,265.0 834.5,265.5 834.0,265.4 833.5,265.5 833.1,265.5 832.7,265.8 832.1,265.6 831.6,265.1 831.6,264.4 832.0,263.5 832.1,262.9 832.3,262.3 832.3,261.2 831.9,260.5 831.9,260.0 831.6,259.6 831.2,259.7 830.5,259.8 830.2,259.4 830.0,258.8 830.1,258.4 830.1,257.9 830.0,257.4 830.4,257.2 830.8,256.9 830.9,256.3 831.0,255.9 831.4,255.4 831.4,254.6 831.4,254.2 831.5,253.5 832.0,252.5 832.4,252.0 832.9,252.4 832.9,251.9 832.7,251.3 832.6,250.2 833.0,249.9 832.8,249.5 833.1,248.8 833.4,248.4 833.6,247.9 834.0,247.6 834.5,247.6 835.0,247.6 835.4,247.1 835.7,246.5 836.2,246.3 836.7,246.5 837.1,246.5 837.5,246.9 838.5,247.0 839.2,247.1 840.1,247.2 840.7,247.4 841.1,247.6 841.6,247.4 842.4,247.4 844.0,247.7 845.2,247.2 845.6,246.7 846.0,246.4 846.5,246.0 846.8,245.6 847.2,245.3 847.7,245.4 847.9,245.8 847.5,246.2 847.3,246.7 846.9,247.2ZM798.3,266.7 798.8,267.2 799.4,267.3 800.0,267.4 800.4,267.5 800.8,267.4 801.2,267.7 801.5,268.1 801.7,268.7 802.2,268.9 802.8,268.9 803.6,269.1 804.2,268.9 805.1,269.2 805.7,269.2 806.1,269.2 806.6,269.3 807.0,269.2 807.3,268.6 807.5,268.1 807.9,267.8 808.3,267.9 808.8,268.5 809.3,268.6 809.7,268.5 810.1,268.6 811.1,268.9 811.5,269.2 812.0,269.2 812.6,269.2 812.7,269.6 812.9,270.1 813.3,270.3 813.3,271.0 813.9,271.3 814.6,271.4 815.3,271.5 816.0,271.4 816.8,271.2 817.7,271.6 817.9,272.2 817.7,273.0 817.9,273.8 818.3,274.1 817.9,274.3 817.4,273.9 816.5,273.8 815.8,273.6 814.6,273.0 813.9,273.1 813.3,273.3 812.7,273.3 812.1,273.2 811.4,273.1 809.7,273.1 809.3,272.9 808.5,272.9 807.9,272.8 807.2,272.6 805.7,271.9 805.1,271.7 803.6,271.4 802.7,271.4 802.1,271.3 801.6,271.4 800.9,271.7 799.8,271.5 799.2,271.2 798.7,271.0 798.0,270.8 797.4,270.7 796.2,270.6 795.7,270.5 795.6,270.1 795.9,269.6 795.0,269.2 794.3,269.1 793.7,269.0 793.3,269.1 792.8,269.0 792.4,269.0 792.6,268.5 792.9,268.9 793.3,268.5 793.5,268.0 794.1,267.0 794.5,266.5 794.9,266.6 795.4,266.6 796.0,266.7 796.7,266.9 797.3,266.7 797.7,266.5 798.1,266.6ZM768.0,235.5 768.4,235.5 769.0,235.3 769.4,235.4 770.0,235.5 770.7,235.5 771.1,235.6 771.4,236.0 772.0,236.4 772.2,237.1 772.9,237.7 772.9,238.3 773.7,238.9 774.1,239.2 774.4,239.6 775.4,240.0 776.4,240.8 777.0,241.2 777.5,241.7 777.8,242.2 778.1,242.6 778.6,243.2 779.0,243.7 779.5,244.1 780.0,244.5 780.0,244.1 780.2,243.7 780.7,243.7 781.2,244.2 781.5,244.8 781.9,245.3 782.5,245.4 783.4,246.0 783.8,246.5 784.0,247.2 784.4,247.7 784.9,247.9 785.7,248.0 786.2,248.4 786.1,248.8 785.5,249.2 784.9,249.4 785.5,249.3 786.1,249.1 786.9,248.6 787.3,248.6 787.7,248.9 788.2,249.5 788.3,249.9 787.7,250.2 787.3,250.5 787.2,251.0 787.3,251.5 787.6,252.1 788.1,252.5 788.7,252.7 789.1,252.8 789.6,252.9 790.1,253.5 790.2,254.4 790.3,255.1 790.8,255.5 791.2,255.8 791.2,256.2 790.7,256.6 790.6,257.1 791.3,256.7 791.7,256.5 792.5,256.5 793.0,256.7 794.2,258.0 794.6,258.6 794.5,259.1 794.2,259.5 794.0,260.0 794.0,260.4 794.2,260.8 794.0,261.5 794.1,262.7 794.1,263.3 794.1,263.9 793.9,265.8 793.7,266.2 793.3,266.0 792.6,265.4 792.0,265.9 791.5,265.8 790.7,265.3 790.6,265.7 790.8,266.2 790.2,266.1 789.6,265.4 789.1,265.0 788.4,264.1 787.2,263.4 786.8,263.0 785.9,262.4 784.8,261.5 784.4,261.0 783.9,260.2 782.8,259.4 782.4,259.0 781.7,258.1 781.4,257.6 780.9,257.2 780.4,256.5 780.1,256.0 780.2,255.4 779.1,253.6 778.9,253.1 778.6,252.3 778.0,251.5 777.6,251.1 777.0,250.1 776.7,249.7 775.9,249.4 775.4,249.0 775.2,248.1 774.8,247.1 774.4,245.8 774.2,245.3 773.9,244.8 772.5,243.9 772.0,243.7 771.4,243.4 771.2,242.6 771.1,242.1 770.5,241.7 770.1,241.1 769.4,240.1 768.9,239.7 768.1,239.5 767.5,238.9 766.6,238.2 765.5,237.1 765.1,236.5 764.5,235.3 764.6,234.8 765.0,234.4 765.6,234.4 766.2,234.7 766.7,235.1 767.4,235.4 768.0,235.5ZM824.5,231.4 825.0,231.1 825.4,230.6 825.7,231.0 825.8,231.5 826.4,231.7 826.8,232.0 826.9,232.4 826.7,232.8 826.8,233.3 826.4,233.7 827.3,233.5 827.8,233.2 828.1,233.7 827.6,233.9 828.2,234.0 828.6,233.8 829.0,234.0 829.4,234.5 829.8,234.6 830.4,234.9 831.0,234.9 831.3,235.3 831.2,235.7 830.3,236.0 829.6,236.2 828.8,236.1 828.3,236.6 828.7,237.0 829.3,237.5 829.2,237.9 828.8,238.0 828.4,238.0 827.8,238.2 827.1,238.0 826.7,238.3 826.3,238.7 826.6,239.1 827.0,239.5 827.0,239.9 826.6,239.8 826.2,239.9 825.8,239.9 825.2,239.9 826.0,240.5 825.9,241.0 826.4,241.4 826.8,241.8 826.8,242.2 827.2,242.4 827.5,242.9 827.9,243.4 827.9,243.8 827.5,244.2 827.6,244.8 828.0,245.3 829.1,246.1 829.5,246.3 830.1,247.0 830.5,247.1 830.3,247.5 829.9,247.7 829.3,247.7 828.6,247.6 828.0,247.4 827.5,246.9 827.7,247.3 827.6,247.7 827.2,247.9 826.5,249.1 826.3,250.6 826.5,251.5 826.5,251.9 826.0,252.4 825.4,252.8 825.0,253.3 824.6,253.4 824.3,252.9 824.3,253.4 824.2,253.8 823.8,254.1 823.5,254.5 823.1,254.8 823.4,255.2 823.4,255.7 823.4,256.1 823.8,256.4 823.7,257.0 823.3,257.0 823.3,257.5 823.2,257.9 822.8,258.3 822.9,258.7 822.4,259.3 822.2,259.8 820.2,260.9 818.6,261.6 818.3,260.3 818.2,259.7 817.8,259.6 817.6,259.0 817.2,259.3 816.5,259.4 816.1,259.6 815.6,259.5 815.6,259.0 815.0,259.0 814.0,258.1 813.8,258.9 813.2,259.2 812.8,259.4 812.3,259.4 811.9,259.2 811.5,259.4 811.0,259.8 810.6,259.8 810.7,259.2 810.6,258.5 810.3,258.0 809.7,258.3 809.1,258.2 808.5,258.5 808.1,258.5 808.1,258.1 807.6,258.3 807.1,258.0 806.6,258.1 806.2,258.1 806.2,257.5 805.9,256.2 805.8,255.6 805.4,255.2 805.5,254.8 805.7,254.2 805.5,253.5 805.2,253.1 804.7,252.6 804.0,252.4 803.6,252.4 803.5,252.0 803.5,251.6 803.1,251.2 803.1,250.7 803.3,250.0 803.2,249.5 802.6,249.0 802.6,248.5 802.5,247.8 802.7,246.8 803.1,246.5 802.9,246.0 803.2,245.5 803.7,244.9 804.5,244.4 804.7,244.8 805.2,245.1 805.9,245.3 806.4,245.3 807.4,245.7 808.0,245.7 808.6,246.1 809.0,246.1 808.5,245.9 808.5,245.5 808.8,244.7 809.0,244.3 808.9,243.9 808.9,243.4 809.3,243.4 809.6,242.7 810.1,242.2 811.4,241.9 813.2,241.5 813.7,241.3 814.3,240.7 814.8,240.1 815.1,239.6 815.9,238.9 816.5,238.2 816.6,237.7 816.7,237.3 817.2,237.2 817.8,237.1 818.2,236.9 818.7,236.4 819.4,236.0 819.5,236.4 820.5,236.3 820.9,236.0 821.1,235.6 820.7,235.4 820.6,235.0 821.0,234.5 821.5,234.6 821.9,234.4 822.4,233.7 822.5,233.3 823.6,231.9 824.3,230.6 824.6,231.0 824.5,231.4ZM688.2,38.2 687.1,38.4 686.5,38.6 685.8,38.7 685.2,38.8 684.1,38.9 682.3,39.2 681.1,39.3 679.8,39.5 678.5,39.7 677.2,39.8 676.8,39.8 675.9,40.0 675.1,40.1 672.4,40.5 671.2,40.8 670.8,40.8 670.4,40.8 669.9,41.0 669.3,41.2 668.7,41.5 668.1,41.5 667.4,41.6 667.9,41.8 667.5,42.1 666.9,42.3 666.0,42.4 665.5,42.7 664.6,42.5 664.3,42.9 663.7,43.2 662.6,43.1 662.9,43.5 662.3,44.1 660.5,44.4 660.8,44.9 660.4,45.1 660.0,45.1 659.6,44.9 659.2,44.9 659.6,45.1 659.6,45.5 658.7,45.8 658.2,46.2 657.3,46.4 656.7,46.4 656.2,46.4 655.7,46.3 654.3,46.2 653.6,46.1 652.8,46.0 652.1,46.0 651.6,46.1 650.8,46.2 650.4,45.9 649.6,45.3 649.9,44.9 650.5,44.8 651.1,44.6 651.7,44.6 652.3,44.2 652.8,43.9 653.7,43.3 655.8,43.1 655.4,42.9 654.9,42.9 654.5,42.8 655.3,42.2 656.2,42.0 656.9,41.8 656.5,41.6 655.5,41.7 655.1,41.4 655.7,41.1 656.4,41.2 656.9,41.4 658.0,41.0 658.3,40.6 659.2,40.6 660.0,40.7 660.5,40.3 661.4,40.0 662.3,39.7 662.9,39.5 663.6,39.3 664.2,39.2 664.8,39.1 666.1,39.0 666.8,38.9 667.4,38.6 668.3,38.6 668.7,38.6 669.3,38.7 669.5,38.2 669.9,38.1 671.0,38.1 671.6,38.1 672.9,38.2 673.5,38.2 674.4,38.2 674.9,38.2 676.5,38.0 679.1,37.8 679.7,37.7 680.4,37.5 680.8,37.5 681.4,37.4 682.0,37.3 682.7,37.3 683.2,37.0 684.3,36.6 685.6,36.3 686.8,36.2 687.6,36.1 688.9,36.1 690.2,36.3 690.8,36.5 691.3,36.7 691.4,37.1 690.4,37.6 689.5,38.0 688.2,38.2ZM653.7,46.4 655.0,46.5 655.9,46.5 656.5,46.6 656.1,47.1 656.1,47.5 655.1,47.8 654.7,48.1 654.0,48.4 653.8,48.9 654.2,49.4 653.8,50.0 654.1,50.4 654.5,50.9 655.1,51.4 655.7,51.8 656.8,52.5 658.0,53.0 658.5,53.1 659.7,53.4 660.1,53.5 659.6,53.7 659.1,53.8 658.7,53.9 657.4,53.8 657.0,53.5 656.6,53.5 657.1,53.9 655.9,53.7 655.4,53.8 655.0,53.8 653.4,53.7 652.9,53.7 652.4,53.7 651.8,53.5 651.4,53.6 650.9,53.5 649.2,53.3 648.3,53.1 648.9,53.0 648.9,52.6 649.6,52.6 650.3,52.5 649.7,52.2 648.9,52.0 648.4,51.8 648.4,51.3 647.0,51.4 646.3,51.4 645.6,51.3 644.9,51.4 644.3,51.5 643.9,51.4 643.3,51.2 642.9,50.6 642.9,50.2 643.3,49.8 643.9,49.6 644.6,49.6 645.1,49.6 645.6,49.5 646.1,49.2 646.4,48.8 646.8,48.5 647.0,48.1 646.3,48.1 645.9,48.0 646.7,47.6 647.3,47.5 647.9,47.5 647.7,46.9 648.2,46.6 648.6,46.6 649.0,46.5 649.6,46.4 650.3,46.5 650.9,46.4 651.9,46.2 652.6,46.2 653.1,46.2 653.7,46.4ZM896.6,98.9 897.2,99.6 897.0,100.1 897.0,100.5 897.5,101.4 897.8,102.0 898.0,102.4 898.1,102.9 898.1,103.6 898.0,104.1 897.7,104.6 897.7,105.3 897.7,105.7 898.0,106.3 898.1,106.7 898.5,107.0 898.5,107.5 899.3,109.7 899.5,110.3 900.1,111.4 900.4,112.1 900.7,112.7 900.9,113.4 901.7,114.1 902.0,114.9 901.7,114.4 901.1,113.9 900.3,113.3 899.9,113.1 899.5,113.0 898.3,113.1 897.9,113.2 897.5,113.3 897.1,114.1 896.2,116.0 896.0,116.5 896.0,117.0 896.0,117.4 896.3,118.0 896.7,118.3 897.1,118.5 897.5,119.4 897.7,119.9 898.1,120.0 898.5,120.0 898.7,120.6 898.8,121.1 898.6,121.6 898.5,122.0 898.4,121.6 898.2,121.2 898.0,120.7 897.4,120.6 896.7,120.5 896.2,120.2 895.8,120.4 895.4,120.9 895.0,122.0 894.7,122.5 894.3,122.2 894.2,121.7 894.0,121.0 894.1,120.3 894.5,119.4 894.5,118.8 894.3,117.9 894.5,117.5 894.9,116.9 894.8,115.9 894.5,115.3 894.2,114.9 894.4,114.0 894.6,113.0 894.8,112.3 894.9,110.5 894.6,109.7 894.7,109.0 894.8,108.6 895.0,107.7 894.7,107.1 894.1,106.6 893.7,106.3 893.5,105.7 893.5,104.8 893.7,104.3 894.0,103.3 894.1,102.7 894.0,101.8 894.8,101.4 895.3,101.7 895.9,101.5 895.9,101.1 896.3,100.9 896.3,100.5 896.3,100.1 895.7,99.6 895.4,99.2 896.0,99.2 896.4,98.8ZM899.5,127.5 899.9,127.5 900.3,127.5 901.3,127.9 901.7,128.0 902.2,127.9 903.1,127.3 903.7,126.8 903.5,127.6 903.1,128.1 903.2,128.7 903.5,129.3 903.7,129.7 904.1,129.8 904.6,129.5 905.1,129.5 904.5,129.7 904.2,130.1 903.7,130.1 903.1,130.3 902.6,130.6 902.2,130.6 901.7,130.7 900.8,130.6 899.9,130.9 899.3,131.3 898.8,131.7 898.4,132.2 898.1,132.7 898.1,133.1 897.5,133.3 897.0,133.0 895.9,132.6 894.7,132.0 894.0,131.7 892.8,131.8 891.6,132.4 891.1,131.9 890.6,131.7 890.2,131.8 889.8,132.3 890.0,132.8 890.4,133.0 890.8,133.0 891.4,133.4 892.0,133.8 891.4,134.0 890.7,133.8 890.2,134.2 890.0,134.7 889.6,134.8 889.1,134.9 888.9,134.5 889.1,133.9 889.0,133.1 888.6,132.8 888.4,132.3 888.5,131.7 888.9,131.4 889.5,131.1 890.1,130.7 890.0,130.1 890.0,129.7 890.5,129.7 891.1,130.0 891.5,130.0 892.0,130.1 892.5,130.0 892.8,129.5 892.8,129.1 892.9,128.5 893.3,128.0 893.5,127.0 893.8,126.4 893.8,125.8 893.7,125.2 893.3,124.6 893.5,124.0 894.0,123.8 894.4,123.7 894.9,124.1 895.6,124.7 896.4,125.5 896.9,125.9 897.4,126.3 898.0,126.7 898.6,127.0 899.0,127.2 899.5,127.5ZM892.3,135.1 892.9,135.0 892.8,135.4 892.8,135.8 892.8,136.6 892.9,137.2 893.5,137.6 893.9,138.1 894.1,138.7 894.4,139.3 894.4,139.8 894.4,140.5 894.2,141.1 893.9,141.6 893.5,141.7 893.3,142.2 893.1,142.7 893.1,143.1 892.7,143.4 892.0,143.5 891.6,144.0 891.5,144.6 891.7,145.3 891.8,145.9 891.7,146.9 891.4,147.4 890.9,148.0 890.6,148.6 890.5,149.1 890.5,149.6 891.0,150.4 891.3,150.8 890.7,150.9 890.2,151.4 890.0,152.0 889.3,152.5 888.8,152.9 888.3,152.9 888.5,152.1 888.7,151.6 889.1,151.3 888.9,150.9 888.4,150.9 888.2,151.3 888.0,151.9 888.0,152.4 887.4,151.9 886.8,152.0 886.4,152.5 886.4,152.9 886.2,153.5 885.8,153.8 885.4,153.6 885.6,153.1 885.8,152.7 885.3,152.4 884.9,152.5 884.5,153.0 884.0,153.5 883.9,153.9 883.3,153.8 882.6,153.8 882.1,153.7 881.4,153.8 880.7,153.9 881.4,153.6 880.8,153.4 880.4,153.2 880.4,153.6 880.2,153.0 880.1,152.6 879.7,152.8 879.4,153.4 879.5,153.9 880.1,154.3 880.1,154.7 879.3,154.8 878.7,155.1 878.0,156.2 877.5,156.8 876.9,157.0 876.3,156.8 876.0,156.3 875.5,155.8 875.4,155.0 875.7,154.5 876.1,154.2 876.2,153.8 875.5,153.7 875.1,153.8 874.4,153.5 873.8,153.4 873.2,153.5 872.8,153.6 872.4,153.9 871.9,154.2 871.3,154.2 870.8,154.4 870.4,154.5 870.0,154.6 869.5,154.6 868.8,154.8 868.1,154.8 867.5,154.7 867.2,155.5 867.1,156.0 866.0,155.4 865.2,155.5 864.8,155.7 864.3,155.6 863.9,155.5 863.6,154.8 864.3,154.4 864.9,154.4 865.3,154.0 865.9,153.7 866.3,153.5 866.8,153.1 867.4,152.7 867.8,152.3 868.4,151.9 868.7,151.5 869.2,151.4 869.9,151.2 870.5,151.5 871.2,151.4 871.8,151.4 872.2,151.4 872.8,151.3 873.2,151.2 874.7,150.9 875.5,150.7 875.6,151.1 876.7,151.3 877.2,151.3 877.8,150.9 877.8,150.4 877.8,150.0 878.2,149.4 878.8,149.0 879.3,148.4 879.7,147.9 879.9,147.4 879.8,146.7 880.1,146.2 881.1,145.8 881.5,145.9 881.0,146.4 880.5,146.7 880.5,147.1 880.6,147.5 881.2,147.9 881.9,147.4 883.1,147.0 883.6,146.8 884.2,146.6 884.9,146.1 885.3,145.7 885.6,145.1 886.8,144.4 887.2,144.0 887.4,143.3 887.7,142.8 888.2,142.3 888.5,141.4 888.6,141.0 889.0,140.5 889.1,139.9 888.9,139.3 888.4,139.2 888.6,138.8 888.9,138.2 888.8,137.7 888.7,137.2 889.1,136.8 889.6,136.6 889.8,136.1 889.8,135.7 890.3,135.5 890.7,135.7 890.8,136.4 891.2,136.5 892.0,136.4 892.4,135.8 892.2,135.4 891.1,135.7 891.3,134.9 891.8,134.8 892.3,135.1ZM873.2,154.8 873.6,155.0 874.0,154.9 874.0,155.4 874.1,155.8 873.7,156.3 873.3,156.6 872.9,157.1 872.7,157.6 872.1,157.1 871.3,156.9 870.2,157.3 869.8,158.1 869.4,158.8 868.9,159.0 868.4,159.0 868.6,158.6 868.0,158.6 867.9,158.2 868.0,157.8 867.8,157.4 867.4,157.2 866.9,157.4 867.5,157.0 868.2,156.6 868.6,156.1 868.8,155.6 869.3,155.3 869.8,155.8 870.3,155.6 870.8,155.6 871.2,155.4 871.1,154.9 871.7,154.7 872.1,154.6 872.8,154.7 873.2,154.8ZM864.4,156.7 864.9,156.7 865.3,156.6 865.7,156.6 865.6,157.2 865.4,157.6 865.9,157.6 866.4,157.6 866.2,158.0 866.4,158.4 866.6,158.8 865.9,159.5 865.6,160.2 865.4,160.8 865.2,161.4 865.2,162.0 864.8,162.8 864.3,162.7 864.2,163.2 863.6,163.6 863.0,163.8 863.3,163.1 863.1,162.4 863.3,162.0 862.9,161.9 862.7,162.3 862.6,162.8 862.8,163.3 862.0,163.1 861.5,162.8 861.9,162.6 862.0,162.2 861.6,161.8 861.7,161.2 861.7,160.8 862.2,160.5 862.7,159.8 862.9,159.4 862.5,159.3 862.6,158.8 862.2,158.1 861.8,157.8 861.6,158.3 861.6,158.7 862.0,158.7 862.1,159.2 861.6,159.1 861.0,159.1 860.5,159.5 860.6,159.1 860.2,158.7 860.2,158.3 860.6,158.6 861.1,158.7 860.8,158.3 860.2,157.8 860.0,157.4 860.7,157.4 860.9,157.0 861.3,156.9 861.9,156.7 862.3,156.3 863.0,155.8 863.4,155.8 863.8,155.9 864.0,156.5 864.4,156.7ZM836.1,187.2 835.8,187.8 835.8,188.2 835.7,188.8 835.2,188.8 835.2,188.4 835.0,188.0 834.4,187.5 834.1,187.1 833.7,186.2 833.5,185.7 833.7,185.3 833.7,184.6 833.8,184.1 835.1,182.0 835.4,181.6 835.8,181.1 836.2,180.5 837.1,180.1 837.5,179.8 837.9,179.9 838.5,180.3 838.5,180.8 838.4,181.3 838.4,181.9 838.2,182.5 837.9,183.0 837.7,183.7 837.6,184.3 837.4,184.9 837.2,185.6 836.9,186.2 836.6,186.7 836.1,187.2ZM808.0,194.5 808.3,195.1 808.1,195.6 807.3,196.4 807.1,196.8 807.0,197.3 806.9,197.7 806.7,198.1 806.3,198.2 805.7,198.6 805.0,198.9 804.7,199.3 804.2,199.4 803.7,199.2 803.3,199.1 802.9,199.0 801.9,198.5 801.9,197.9 801.8,197.5 801.8,196.5 802.2,196.1 802.9,195.5 803.3,195.3 803.4,194.9 803.9,194.8 804.4,194.5 805.3,194.5 805.8,194.5 806.5,194.3 807.2,194.5 807.4,194.1 807.8,194.4ZM722.2,222.7 722.7,222.7 723.3,223.2 724.2,224.0 724.7,224.8 725.0,225.2 725.5,225.9 725.6,226.3 726.0,226.6 726.2,227.2 726.8,228.4 727.0,228.8 727.2,229.3 727.4,229.8 727.4,230.5 727.3,231.2 727.1,231.6 726.8,232.2 726.1,232.7 724.9,233.1 724.2,233.4 723.6,233.5 723.0,233.3 722.5,232.9 722.2,232.3 722.1,231.7 721.8,231.0 721.6,228.9 721.6,228.3 721.4,227.6 721.5,227.0 721.5,227.6 721.8,226.9 722.1,225.9 722.0,225.4 722.4,224.7 722.5,224.1 722.5,223.7 722.9,223.6 723.4,223.7 722.9,223.3 722.3,223.2 722.1,222.8ZM637.6,284.5 637.9,285.1 638.3,285.8 638.7,286.3 638.8,286.9 639.1,287.7 639.4,289.0 639.5,290.3 639.5,290.9 639.8,291.5 640.1,292.1 640.2,292.7 640.0,293.4 639.7,294.0 639.1,294.2 638.7,293.3 638.6,292.9 638.2,292.9 638.0,293.6 638.1,294.2 638.1,294.7 638.4,295.2 638.4,295.8 638.1,296.4 638.2,296.8 637.8,297.0 637.4,297.9 637.5,299.1 637.4,299.7 637.1,300.9 636.9,301.5 636.7,302.2 636.3,303.1 635.9,304.3 635.5,305.4 635.3,306.1 635.0,306.8 634.6,308.1 634.3,309.3 633.8,310.7 633.1,312.2 632.9,313.2 632.8,313.9 632.6,314.5 632.2,315.6 632.2,316.0 631.7,317.0 631.5,317.5 631.4,317.9 631.0,318.9 630.7,319.4 629.8,319.9 628.9,319.9 628.2,320.1 627.6,320.4 626.9,320.7 626.4,321.0 625.6,321.0 624.5,320.4 623.5,320.2 622.9,319.8 622.4,319.5 622.2,319.1 622.0,318.4 621.4,317.7 621.3,317.0 621.3,316.6 621.2,316.0 621.3,315.6 621.4,315.1 621.2,314.7 621.0,314.1 620.5,313.6 620.4,313.0 620.2,312.2 620.2,311.3 620.3,310.9 620.4,310.5 620.7,310.1 620.8,309.3 621.4,309.0 621.8,308.5 622.0,308.0 622.4,307.4 622.9,306.6 623.2,306.0 623.3,305.3 623.4,304.6 623.5,304.0 623.3,303.6 622.9,303.0 622.9,302.4 622.7,301.7 622.3,300.8 622.2,299.8 622.2,299.1 622.2,298.3 623.4,296.4 623.4,295.6 623.4,295.2 623.8,295.0 624.7,294.9 625.1,294.7 625.5,294.4 625.9,294.5 626.3,294.4 626.7,294.4 626.8,294.0 627.5,293.9 628.2,293.7 628.6,294.2 629.0,294.2 628.7,293.8 628.8,293.3 629.7,292.7 630.2,292.3 630.6,292.8 630.9,292.3 631.1,291.8 631.4,291.2 631.8,290.8 631.9,291.2 631.7,291.7 632.2,291.3 632.5,290.8 633.0,290.7 632.7,289.9 633.2,289.1 633.1,288.5 633.0,288.1 633.4,287.8 633.9,288.1 634.3,287.9 634.7,287.4 635.1,287.3 635.5,286.9 635.9,285.9 635.8,285.3 635.5,284.6 635.9,284.6 636.2,284.2 636.7,283.6 637.1,284.0 637.6,284.5ZM647.5,142.2 647.3,141.5 647.5,142.2ZM639.4,125.4 638.9,125.2 639.1,124.8 639.2,125.3ZM58.2,471.7 54.7,471.8 53.3,471.7 50.4,471.4 46.3,470.8 45.2,470.7 44.5,470.5 43.8,470.3 43.8,469.8 44.1,469.4 45.0,469.2 45.4,469.0 46.3,468.8 46.9,468.7 47.6,468.7 48.3,468.7 48.9,468.8 49.6,468.9 51.0,469.2 52.0,469.5 53.4,469.8 54.9,470.2 55.7,470.3 56.4,470.6 57.2,470.9 57.6,471.1 58.0,471.3 58.2,471.7ZM304.6,471.3 304.0,471.3 302.8,471.3 301.6,471.2 300.9,471.0 300.9,470.1 301.5,469.8 302.1,469.6 303.8,469.2 305.6,468.9 306.3,468.8 307.2,468.6 312.6,467.7 313.8,467.5 314.3,467.6 313.8,468.1 313.4,468.2 312.6,468.6 310.7,469.1 309.3,469.5 307.6,470.0 307.2,470.2 306.4,470.7 306.3,471.2 305.2,471.3 304.6,471.3ZM374.4,468.9 376.2,468.9 377.7,468.9 378.6,468.9 379.0,469.2 379.5,469.7 380.0,470.3 380.7,470.8 380.6,471.7 379.8,472.2 379.2,472.1 378.6,472.2 379.2,472.5 363.4,474.0 362.8,474.1 362.1,474.2 361.7,474.4 349.6,474.6 349.0,474.3 349.0,473.8 349.6,473.7 350.9,473.0 351.4,472.7 351.8,472.5 352.3,472.7 353.3,472.7 354.0,472.5 354.6,472.4 356.4,472.2 357.8,471.7 359.3,471.2 360.0,470.9 360.2,470.5 359.8,470.3 359.1,470.2 359.5,469.9 359.7,469.5 360.1,469.2 360.1,468.8 360.5,468.3 361.3,467.9 362.9,467.3 363.5,466.9 367.5,466.2 368.2,466.2 369.4,466.1 369.9,466.1 371.5,466.1 372.2,466.2 373.5,466.3 375.4,466.6 376.1,466.8 376.8,466.9 377.5,467.1 378.2,467.4 378.4,467.9 377.9,468.3 374.8,468.5 374.4,468.6 374.0,468.9 374.4,468.9ZM334.1,473.2 334.0,473.8 333.8,474.3 333.0,474.6 332.6,474.7 331.7,474.9 327.7,474.7 325.9,474.5 325.2,474.3 324.8,474.0 323.0,473.9 322.0,474.0 321.6,474.3 318.9,473.9 316.2,473.5 315.0,473.2 314.6,473.1 315.0,472.9 315.6,472.8 316.1,472.9 316.7,473.3 326.3,473.3 327.1,473.2 328.8,473.2 329.7,473.1 329.2,472.9 328.6,472.6 329.7,472.2 330.2,471.8 331.7,471.5 333.7,471.6 334.2,471.9 333.9,472.5 334.7,472.5 335.2,472.8 334.6,472.8 334.2,473.1ZM305.4,442.2 305.8,442.4 306.4,442.6 307.2,443.3 307.7,444.2 308.1,444.7 309.1,445.6 309.6,446.1 309.9,446.7 310.3,447.5 310.4,448.1 310.5,449.2 310.0,449.9 309.6,450.4 307.9,451.2 305.4,451.7 304.0,451.8 303.5,451.7 303.0,451.7 302.3,451.7 300.4,451.8 299.0,451.9 298.5,451.6 298.1,451.7 297.5,451.5 297.1,451.2 297.6,450.8 298.0,450.8 299.0,450.8 299.6,450.9 301.1,451.0 303.1,451.0 303.7,451.0 304.4,450.9 305.0,450.6 304.4,450.5 303.8,450.5 302.9,450.6 302.3,450.7 301.6,450.8 300.9,450.7 300.3,450.4 302.5,450.1 303.1,450.0 301.8,449.5 301.2,449.6 300.5,449.5 299.9,449.3 299.3,449.0 298.9,449.1 297.4,449.8 296.8,449.7 296.1,449.6 295.5,449.5 295.1,449.6 295.6,449.9 294.7,450.4 294.0,450.4 293.6,450.2 292.6,450.2 291.9,450.1 291.3,449.9 290.9,449.8 290.6,449.4 290.7,449.0 291.4,448.8 292.0,448.7 292.7,448.9 293.1,449.0 293.3,448.6 293.8,448.3 294.3,448.4 295.2,448.7 295.7,448.8 296.2,448.7 295.6,448.5 295.9,448.1 297.2,448.2 297.7,448.3 298.3,448.3 299.4,448.2 298.8,448.0 297.5,447.8 297.1,447.6 298.0,447.4 299.0,447.4 300.8,447.6 301.4,447.5 301.9,447.3 303.5,447.2 304.5,447.1 305.8,447.6 306.1,447.2 305.7,446.9 305.3,446.9 304.7,446.8 303.7,446.7 303.0,446.6 302.6,446.6 302.6,445.9 304.0,445.6 304.6,445.4 305.3,445.4 306.4,445.6 305.9,445.3 305.2,445.1 304.6,444.9 303.6,444.8 303.0,445.0 302.4,445.0 300.8,444.6 300.4,444.4 300.4,444.0 300.7,443.5 300.8,443.1 300.5,442.7 300.1,442.6 299.7,442.3 299.7,441.8 300.4,441.5 301.7,441.3 304.4,441.1 305.1,441.5 305.4,442.1ZM307.9,396.3 308.9,396.0 309.3,396.2 310.2,396.9 310.4,397.4 309.9,397.4 309.7,397.8 310.7,398.1 311.1,398.8 311.5,399.1 312.0,399.4 312.5,399.8 313.1,400.1 313.7,400.4 314.3,400.6 314.8,400.9 315.4,401.2 316.0,401.5 316.7,401.7 317.4,401.8 318.4,401.8 318.9,401.9 318.5,402.4 318.1,402.5 317.4,402.6 316.8,402.6 316.2,402.7 315.6,402.8 315.2,402.9 314.1,402.6 313.5,402.5 311.7,402.4 311.1,402.4 310.5,402.3 309.7,402.3 309.3,402.4 308.9,402.4 308.1,402.5 307.0,402.4 306.3,402.0 305.8,402.2 305.2,402.3 304.8,402.1 304.2,402.3 303.5,402.1 303.0,402.0 302.1,401.9 301.6,401.7 300.5,401.7 300.5,401.3 301.1,401.4 301.7,401.1 302.3,401.3 302.9,401.2 303.3,400.9 303.6,401.3 304.0,401.4 304.4,401.4 304.3,401.0 303.8,400.7 303.4,400.7 303.2,400.3 303.1,399.7 303.8,399.5 303.8,399.0 304.3,399.7 303.8,400.0 304.1,400.4 304.5,400.5 304.9,400.8 305.6,401.1 306.1,400.9 306.6,401.0 307.2,401.1 307.4,401.5 307.9,401.3 305.6,400.3 305.1,399.7 305.1,399.3 305.7,399.1 306.4,398.9 307.3,398.6 306.9,398.2 306.2,398.2 305.3,398.4 304.6,398.3 304.3,397.8 304.5,397.3 305.0,397.2 304.8,396.8 305.3,396.6 305.7,396.7 306.2,396.5 306.7,396.0 307.2,395.8 307.9,396.3ZM272.7,185.7 273.4,185.8 274.0,185.8 274.5,185.9 275.0,185.9 276.0,185.8 276.5,186.2 277.0,186.3 277.6,186.3 278.0,186.5 278.7,186.8 279.0,187.3 279.6,187.6 280.0,187.8 280.8,187.8 281.2,187.9 282.9,188.6 283.4,189.0 284.3,189.5 284.7,189.5 285.2,189.6 285.7,189.9 285.1,190.0 285.5,190.3 286.3,190.4 286.8,190.7 287.4,190.9 288.2,191.0 288.7,191.3 289.2,191.4 289.7,191.4 289.9,191.8 289.6,192.2 290.2,192.5 290.7,192.5 291.1,192.5 291.8,192.6 292.4,192.8 293.0,193.4 293.4,193.5 293.8,193.5 294.0,194.0 293.3,194.2 292.7,194.3 292.1,194.4 291.7,194.6 291.3,194.7 290.9,194.7 290.1,194.7 289.5,194.6 288.5,194.5 287.5,194.6 286.7,194.6 286.1,194.7 285.5,194.7 284.8,194.8 284.1,194.8 284.6,194.2 285.5,193.6 285.9,193.2 285.5,192.7 285.1,192.6 284.5,192.5 283.7,192.5 283.3,192.5 282.5,191.9 282.1,191.6 281.8,190.8 281.6,190.2 281.0,189.9 280.0,190.1 279.6,190.0 278.0,189.6 277.4,189.4 276.9,189.1 276.4,188.5 275.1,188.7 274.7,188.5 274.5,188.1 274.4,188.5 274.0,188.6 272.7,188.3 272.3,188.1 272.3,187.7 272.9,187.6 273.1,187.2 272.7,187.0 270.2,187.0 269.8,187.2 269.4,187.5 269.0,187.8 268.6,188.0 268.1,188.4 267.7,188.4 266.9,188.4 266.7,188.9 266.3,189.1 265.4,189.5 265.3,189.1 264.9,189.1 264.5,189.3 265.2,188.8 265.6,188.8 265.6,188.2 265.7,187.8 266.3,187.2 268.7,186.2 270.4,186.0 271.3,185.7 271.9,185.6 272.7,185.7ZM558.0,27.1 559.9,27.1 560.3,27.3 560.8,27.4 561.6,27.6 562.2,27.5 562.3,26.9 563.0,26.6 563.6,26.5 564.6,26.5 564.5,26.9 564.2,27.3 564.9,27.3 565.8,27.2 566.5,26.9 567.1,27.0 567.5,26.8 568.2,27.0 568.7,26.9 569.2,27.0 570.8,27.1 571.3,27.2 571.8,27.3 573.4,27.3 574.6,27.3 575.0,27.4 575.4,27.6 575.6,28.0 575.2,28.2 572.8,28.7 572.2,28.8 571.5,29.3 570.1,29.6 569.0,29.5 567.7,29.7 567.0,30.0 566.5,30.0 566.0,30.0 563.6,29.9 563.0,29.6 563.5,29.4 560.9,29.5 557.9,29.4 556.7,29.3 555.9,29.2 555.3,29.1 554.7,28.9 555.1,28.8 555.6,28.8 556.1,28.8 556.9,28.8 557.5,28.6 556.8,28.4 555.9,28.4 555.3,28.5 554.5,28.5 553.9,28.5 552.6,28.5 552.0,28.4 551.2,28.3 550.8,28.1 551.7,27.9 552.4,27.7 551.0,27.6 550.4,27.5 549.8,27.4 550.2,27.3 552.2,27.2 552.7,27.3 553.2,27.4 553.7,27.5 554.3,27.3 553.8,27.3 553.3,27.0 553.7,26.9 554.4,27.1 554.9,27.1 554.7,26.7 555.1,26.5 555.8,26.6 556.6,26.7 557.5,26.9 558.0,27.1ZM546.6,28.0 547.0,27.9 547.8,27.9 548.8,28.1 549.5,28.3 549.9,28.6 549.3,29.0 549.2,29.4 549.6,29.3 550.8,28.9 551.6,29.0 552.1,29.2 552.3,29.6 551.9,29.8 552.4,29.9 553.0,30.1 554.1,30.1 554.9,30.1 555.3,30.4 555.9,30.3 556.8,30.2 557.3,30.3 557.7,30.4 556.9,30.6 557.6,30.8 558.6,31.0 559.2,31.1 558.6,31.5 557.6,31.5 556.6,31.5 554.9,31.6 554.5,31.8 553.8,32.0 553.2,32.3 552.7,32.7 552.8,33.1 552.3,33.2 551.6,33.2 551.2,33.3 551.1,33.9 550.8,34.5 550.4,34.7 549.6,34.7 549.0,35.0 548.5,35.5 547.6,36.0 547.6,36.4 547.2,36.6 547.4,37.1 547.0,37.2 546.4,37.3 545.7,37.2 545.1,36.9 544.5,36.8 543.2,36.4 542.0,35.9 540.9,35.7 540.2,35.6 539.6,35.3 539.0,35.0 539.0,34.6 539.9,34.5 540.5,34.6 541.4,34.2 545.0,33.9 546.2,33.9 547.3,33.9 546.8,33.6 545.9,33.7 544.6,33.8 544.0,33.8 543.3,33.7 542.6,33.7 541.9,33.9 541.2,33.9 540.6,34.0 539.1,34.0 538.3,33.7 538.0,33.3 538.4,33.1 539.0,33.1 539.7,33.3 541.7,32.9 542.6,32.7 543.1,32.7 543.6,32.7 544.1,32.4 544.9,32.4 546.6,32.4 547.2,32.3 547.7,32.2 547.2,32.2 546.7,32.2 545.9,32.1 546.4,31.6 545.9,31.5 544.9,31.8 544.3,32.0 543.6,32.0 542.8,32.0 542.4,31.8 542.8,31.3 542.3,31.3 541.7,31.6 541.2,31.5 540.8,31.3 540.2,31.5 540.1,32.0 540.7,32.2 540.3,32.2 539.9,32.3 539.2,32.6 538.6,32.6 537.9,32.7 536.5,32.7 535.9,32.5 535.2,32.3 534.5,32.0 534.0,31.7 533.2,31.6 532.7,31.3 532.3,30.9 531.6,30.7 532.1,30.6 534.1,30.8 534.5,30.7 534.0,30.6 533.6,30.6 533.1,30.3 533.6,29.8 532.4,29.7 532.0,30.1 531.5,30.3 531.1,30.2 530.5,29.7 530.1,29.3 529.8,28.9 529.7,28.5 530.2,28.3 530.7,28.4 531.1,28.6 531.5,28.3 532.5,28.3 533.6,28.5 534.1,28.6 535.0,28.4 535.4,28.4 536.4,28.2 538.0,28.2 538.7,28.3 538.3,28.6 536.2,28.7 534.9,29.0 536.7,28.9 537.2,29.2 537.8,29.3 538.4,29.5 538.8,29.6 538.9,29.2 539.4,28.8 539.9,28.5 540.5,28.3 541.2,28.4 541.8,28.7 542.4,29.0 542.9,29.4 543.5,29.9 544.1,30.1 545.3,30.6 545.1,30.2 544.5,29.6 544.1,29.1 543.9,28.7 544.3,28.2 544.7,28.1 545.1,27.6 545.5,27.6 545.9,27.7 546.6,28.0ZM526.8,136.4 527.2,137.3 527.2,137.7 526.8,138.1 526.9,138.6 526.9,139.1 526.7,140.7 526.6,141.2 526.1,141.2 525.7,141.1 525.3,141.1 525.1,141.5 524.7,141.9 524.2,141.9 523.8,141.6 523.4,141.1 523.3,140.3 523.5,139.9 523.7,139.5 523.4,139.1 523.5,138.7 523.5,138.1 523.3,137.7 523.0,137.3 522.7,136.7 523.1,136.5 523.5,136.6 524.2,136.4 525.0,135.8 525.5,135.6 526.0,135.6 526.4,135.8 526.5,136.3ZM526.3,131.1 526.3,131.5 526.5,132.9 526.2,133.4 526.1,134.1 525.7,134.8 525.0,134.8 524.6,134.6 524.7,134.2 524.2,134.0 524.3,133.5 523.9,133.5 524.2,133.1 523.8,132.7 523.8,132.3 524.2,131.8 525.0,131.5 525.4,131.3 525.8,131.4 525.9,130.7 526.3,130.6 526.3,131.1ZM543.3,143.8 543.1,144.2 542.3,145.0 542.2,145.4 541.9,145.9 542.0,146.3 542.3,146.8 542.5,147.2 542.1,147.5 542.0,148.0 541.4,148.0 541.0,148.0 540.6,147.9 539.9,147.3 539.3,146.9 538.6,146.9 537.7,146.5 537.1,146.3 536.7,146.0 536.2,145.8 535.8,145.6 535.4,145.6 534.8,145.4 534.5,144.9 534.9,144.3 535.3,144.0 535.7,144.3 536.2,144.2 536.6,143.9 537.1,143.9 537.5,144.2 538.0,144.4 538.7,144.4 539.7,144.4 540.3,144.3 540.7,144.2 541.1,144.0 541.6,144.0 542.0,144.0 542.4,143.8 543.1,143.6ZM353.5,55.7 354.5,55.9 355.4,56.1 355.6,56.5 355.2,57.0 353.4,57.3 352.8,57.4 351.2,57.6 350.7,57.6 350.3,57.5 349.9,57.4 349.5,57.2 349.9,57.1 350.3,57.1 351.0,57.0 350.6,56.9 350.2,56.8 349.6,56.8 348.6,56.7 348.0,56.6 347.6,56.5 347.7,55.8 348.2,55.7 349.0,55.8 348.2,55.5 347.8,55.3 347.8,54.9 349.0,54.7 350.0,54.7 351.7,54.9 352.5,55.2 353.5,55.7ZM146.7,109.3 148.1,109.7 149.4,109.8 150.5,110.1 151.1,110.1 151.6,110.4 151.9,110.8 152.6,111.5 153.0,111.9 153.3,112.4 153.8,112.7 154.2,112.8 155.0,113.1 155.6,113.3 156.0,113.6 156.2,114.0 156.6,114.4 157.0,115.0 157.5,115.4 157.1,115.5 156.7,115.8 155.8,115.6 155.2,115.5 154.5,115.2 153.6,115.0 153.1,114.9 152.7,114.7 153.0,114.0 153.3,113.5 152.3,113.9 151.8,113.9 151.4,114.1 150.9,113.8 150.5,113.6 151.0,113.4 150.6,113.2 150.2,113.2 150.2,112.8 149.8,112.8 149.3,112.7 148.8,112.6 148.4,112.4 148.8,112.2 149.6,112.1 149.0,112.1 148.5,111.9 148.1,111.5 147.6,111.3 147.1,111.5 146.7,111.3 146.7,110.9 146.3,111.0 145.9,110.7 145.4,110.7 145.0,110.8 144.9,110.3 144.7,109.9 145.4,109.8 145.9,110.0 145.8,109.6 145.1,109.4 144.5,109.7 144.1,109.7 143.7,109.4 143.5,109.0 144.2,108.7 144.7,108.7 145.2,108.8 146.7,109.3ZM300.1,195.2 300.5,195.3 300.9,194.9 301.6,194.7 302.0,194.9 302.6,194.7 303.2,194.8 303.7,195.0 304.2,195.1 304.7,195.3 305.2,195.5 305.7,195.4 305.9,195.9 306.3,196.4 307.4,196.3 307.4,196.7 306.9,196.6 307.2,197.0 307.6,197.1 308.2,197.2 308.6,197.3 309.2,197.5 309.9,198.0 310.1,198.5 309.7,198.9 309.3,199.4 308.8,199.1 308.1,198.9 307.6,198.8 307.2,198.8 306.5,198.8 305.8,198.8 305.4,199.0 305.0,199.3 304.2,199.4 303.8,199.1 303.4,199.0 303.0,199.2 302.6,199.3 302.5,199.8 302.0,200.4 301.8,200.9 301.3,200.8 301.0,200.3 300.6,199.9 300.2,199.5 299.8,199.4 298.6,199.4 298.2,199.5 297.6,199.6 296.8,199.4 296.2,199.3 295.8,199.3 295.4,199.4 294.9,199.7 294.5,199.6 293.9,199.3 293.3,199.0 293.4,198.3 293.8,198.2 294.2,198.2 294.8,198.4 295.3,198.4 297.5,198.7 297.9,198.8 298.3,198.5 298.8,198.4 298.7,197.9 298.2,197.5 297.7,197.0 297.9,196.6 298.0,196.0 297.6,195.8 297.1,195.5 296.3,195.5 296.1,195.0 296.6,194.8 297.6,194.6 298.2,194.7 298.8,195.0 299.4,195.2 300.1,195.2ZM362.1,250.6 362.8,250.6 363.3,250.4 364.5,250.6 365.0,250.6 365.4,250.8 365.5,251.2 365.3,251.8 365.2,252.2 364.9,252.7 364.7,253.1 364.3,253.4 364.4,253.9 363.9,254.2 363.4,253.9 363.3,254.3 362.9,254.4 362.5,254.2 362.3,254.8 361.8,254.9 361.4,254.9 360.9,254.7 360.2,254.9 359.7,255.0 359.4,254.6 359.2,254.2 359.1,253.8 359.0,253.4 359.5,253.2 359.1,253.0 358.9,252.5 358.9,251.9 359.2,251.5 359.2,251.0 359.8,250.4 360.4,250.3 362.1,250.6ZM491.4,87.5 491.1,88.0 490.5,88.2 489.5,88.7 488.9,89.0 489.3,89.4 488.7,89.8 489.3,90.0 489.9,89.8 490.5,89.7 491.4,89.8 491.8,89.8 493.8,89.8 494.2,89.7 494.6,89.8 495.1,90.3 494.6,90.7 494.3,91.1 493.7,92.0 493.3,92.4 492.8,92.9 492.3,93.1 491.5,93.2 491.1,93.4 492.0,93.3 492.6,93.6 491.7,93.9 491.2,94.2 490.7,94.4 490.3,94.4 489.7,94.3 491.5,94.6 492.1,94.4 492.8,94.4 494.0,94.7 494.4,95.0 494.9,95.4 495.4,95.6 495.8,96.5 496.0,97.1 496.4,97.9 496.8,98.2 497.9,98.5 498.6,98.9 499.0,99.2 499.4,99.5 499.8,99.7 499.4,99.9 499.7,100.4 500.3,101.1 499.9,101.0 499.5,100.9 498.7,100.8 498.2,100.8 498.7,100.8 499.2,100.9 500.4,101.5 500.8,101.8 501.0,102.3 500.6,102.7 500.1,103.0 500.8,103.3 501.2,103.2 501.6,102.9 502.0,102.8 502.6,102.9 503.5,103.0 504.6,103.5 504.8,103.9 504.9,104.3 504.6,104.8 504.4,105.2 503.9,105.6 503.5,105.6 503.5,106.0 503.1,106.2 502.7,106.1 502.1,106.3 502.5,106.4 502.2,106.8 501.6,106.9 501.2,107.0 501.7,107.0 502.5,107.3 503.5,107.3 503.9,107.3 503.9,107.8 502.9,108.2 502.1,108.5 501.5,108.7 500.8,109.0 499.4,108.8 498.7,108.9 497.8,109.0 497.2,108.8 496.4,108.7 495.8,109.0 495.3,109.1 494.8,109.1 494.4,109.1 493.5,109.3 492.9,109.4 492.3,109.2 491.9,109.1 490.5,109.4 490.2,109.9 489.8,110.4 489.2,110.3 488.6,110.1 488.1,110.1 487.5,110.2 486.9,110.3 486.1,110.7 485.5,111.1 484.9,110.8 484.4,111.0 484.5,110.6 485.2,110.4 485.7,110.1 486.1,109.7 486.5,109.5 487.3,109.0 487.4,108.6 488.1,108.3 488.4,107.8 489.3,107.7 490.0,107.7 490.6,107.8 491.0,107.8 491.5,107.6 492.0,107.2 492.5,106.8 493.2,106.3 492.6,106.6 491.7,106.8 490.9,107.2 490.1,107.2 489.5,106.8 489.0,106.7 488.6,106.8 488.2,106.8 488.6,106.5 488.1,106.4 487.4,106.3 486.9,106.4 486.4,106.6 485.8,106.4 485.6,105.9 486.4,105.4 487.3,105.1 487.8,105.0 488.3,104.8 488.7,104.2 488.7,103.7 488.6,103.3 487.9,103.1 487.3,103.3 487.4,102.9 487.8,102.7 488.6,102.2 489.4,101.9 489.9,102.0 490.5,101.8 491.4,102.1 491.2,101.7 491.9,101.9 492.4,101.9 491.9,101.8 491.5,101.4 491.7,100.9 491.5,100.4 491.9,100.1 492.1,99.6 491.7,99.5 491.2,99.6 490.8,99.4 490.1,98.7 490.4,97.9 490.9,97.5 491.6,97.4 490.5,97.3 490.1,97.4 489.7,97.6 489.3,97.7 488.7,97.8 488.3,97.7 487.8,97.8 487.1,97.8 486.6,97.6 486.4,98.1 486.0,97.9 485.6,97.5 486.0,97.3 486.2,96.8 486.7,96.2 487.0,95.8 486.4,95.3 486.5,94.8 487.3,94.6 486.5,94.3 485.9,94.5 485.5,94.8 485.4,94.4 486.1,93.8 485.3,94.2 484.9,94.5 485.1,94.9 484.6,96.1 484.1,96.3 484.2,95.5 484.7,95.0 484.4,94.3 484.6,93.7 484.9,93.3 485.2,92.9 485.4,92.5 484.5,92.9 484.0,92.9 483.5,92.8 483.0,92.5 483.7,92.3 484.1,92.1 483.7,91.9 484.1,91.8 484.5,91.4 484.6,91.0 483.9,90.6 484.2,90.2 484.0,89.8 484.3,89.4 485.1,89.2 485.7,89.2 485.0,88.8 485.1,88.4 485.9,88.2 485.9,87.8 485.9,87.4 486.3,87.3 486.8,87.3 487.4,87.3 488.4,87.3 489.3,87.3 489.8,87.2 490.4,87.2 490.9,87.1 491.5,87.1 491.4,87.5ZM480.1,97.1 480.5,97.0 480.9,96.7 481.4,96.7 482.0,96.6 482.7,96.6 483.2,96.8 483.7,97.5 484.1,97.7 483.7,98.1 484.1,98.1 484.5,98.2 484.8,98.6 484.4,98.5 484.3,98.9 484.1,99.3 483.7,99.4 483.5,99.8 483.0,99.8 482.5,100.0 482.6,100.4 482.8,101.0 483.0,101.4 482.9,101.8 483.1,102.3 483.3,103.0 482.9,103.5 482.7,104.0 482.4,104.4 482.0,104.6 482.4,104.9 481.8,105.0 481.4,105.0 480.9,105.1 480.5,105.1 480.0,105.2 479.3,105.2 478.9,105.5 478.2,105.7 477.6,106.0 477.2,106.0 476.6,105.9 476.8,106.3 476.1,106.5 475.7,106.6 474.2,107.0 473.7,106.9 473.0,107.0 473.5,106.5 472.5,106.5 471.9,106.7 472.4,106.3 472.8,106.2 473.3,105.9 472.0,106.2 471.6,106.2 471.2,105.9 471.6,105.6 472.1,105.4 472.5,105.2 471.5,105.2 471.1,105.2 471.6,104.8 472.1,104.8 472.9,104.9 472.6,104.5 473.2,104.0 474.1,103.9 474.8,103.8 475.6,103.7 475.2,103.6 474.7,103.7 473.9,103.8 473.4,103.7 472.9,103.9 472.5,104.0 472.9,103.8 473.6,103.4 473.8,103.0 474.2,102.5 474.6,102.4 475.0,102.3 474.6,102.1 473.7,102.1 473.3,101.9 472.9,101.9 472.5,101.6 472.0,101.6 472.1,101.2 472.6,101.1 473.0,101.1 472.6,101.0 472.9,100.6 473.4,100.5 472.9,100.3 472.5,100.4 472.6,100.0 472.4,99.6 472.0,99.6 472.2,99.2 472.7,99.3 473.4,99.1 474.1,99.2 474.6,99.4 475.0,99.2 475.7,99.3 476.1,99.4 476.0,99.0 476.5,98.8 477.0,98.7 477.4,98.2 476.5,98.3 475.7,98.1 476.3,97.8 476.7,97.5 476.9,97.1 477.4,96.8 477.8,96.7 478.3,96.7 478.7,96.5 479.0,96.9 478.7,97.3 479.2,97.1 479.1,96.7 479.7,96.4 480.1,96.4 480.7,96.6 480.1,96.8ZM0.0,58.4 0.6,58.5 1.1,58.6 1.5,58.6 2.0,58.8 3.1,59.0 3.6,59.2 4.1,59.5 3.5,59.3 4.2,59.7 4.9,59.8 5.3,59.9 5.4,60.4 5.8,60.3 4.8,59.7 6.4,60.1 6.9,60.3 7.5,60.5 7.9,60.6 8.6,60.8 12.9,62.0 13.2,62.4 12.9,62.9 13.5,62.9 13.9,62.7 14.3,62.9 14.1,63.3 14.2,63.9 14.5,64.5 14.1,64.9 14.8,65.0 15.3,65.2 15.5,65.6 16.0,65.5 16.4,65.4 16.5,66.0 16.9,65.8 17.3,65.5 16.9,65.2 16.4,65.2 16.0,64.9 16.5,64.8 16.6,64.4 16.4,64.0 15.9,63.9 15.5,63.8 15.1,63.7 17.0,63.6 17.6,63.5 18.1,63.6 19.0,63.7 18.6,64.0 19.0,64.3 18.9,63.9 19.3,64.0 20.4,64.1 20.8,64.0 19.4,63.8 20.5,63.8 21.0,63.9 21.5,64.0 22.1,64.0 22.8,64.1 23.4,64.4 24.0,64.8 24.6,65.0 25.2,65.2 26.2,65.7 26.8,65.8 27.2,66.0 28.1,66.2 28.5,66.5 28.1,66.6 27.3,66.6 26.7,66.9 26.3,67.0 26.3,67.5 25.9,67.7 25.3,67.7 24.7,67.5 24.0,67.2 24.6,67.7 24.5,68.1 24.0,68.0 22.8,68.0 22.4,68.0 21.9,67.9 21.4,67.8 21.0,67.6 20.5,67.5 20.1,67.6 20.7,67.7 21.2,68.1 21.6,68.2 21.5,68.6 20.4,68.8 20.9,68.8 21.4,68.9 21.6,69.3 21.1,69.5 20.6,69.7 20.0,69.8 19.5,69.8 20.0,70.0 19.8,70.4 20.2,70.5 20.9,70.7 21.1,71.1 20.3,71.1 19.7,70.8 19.7,71.2 19.0,71.4 18.4,71.2 18.5,70.7 18.1,71.0 17.6,71.2 17.0,71.1 16.1,70.6 15.1,70.2 14.4,70.1 13.8,70.0 13.2,70.0 12.8,70.0 12.4,69.8 11.9,69.6 11.5,69.4 11.5,68.8 10.9,68.1 9.6,67.9 8.6,67.8 8.2,67.7 7.0,68.0 6.4,68.1 4.7,68.1 4.2,68.0 4.2,67.5 3.7,67.2 3.1,66.8 3.5,66.6 3.8,66.2 4.1,65.8 3.5,66.0 3.1,66.1 2.7,66.1 2.5,65.7 2.0,65.8 1.9,66.2 1.1,66.3 0.7,66.4 0.6,66.9 1.0,67.3 1.5,67.5 1.8,68.0 1.3,68.4 1.0,68.8 0.0,69.3 999.5,69.3 999.0,69.7 998.5,69.9 997.6,70.1 996.4,70.5 995.9,70.5 995.2,70.4 993.7,70.2 993.3,70.1 992.6,69.6 992.2,69.4 991.3,69.2 990.6,69.3 990.0,69.2 990.7,69.4 991.5,69.4 992.0,69.6 992.1,70.0 991.2,69.9 990.4,69.9 989.1,69.6 988.3,69.9 987.2,70.0 986.4,70.1 984.9,70.3 985.3,70.3 986.4,70.1 987.0,70.1 988.0,70.0 988.5,69.9 989.0,69.7 989.4,69.8 989.9,70.3 989.5,70.4 990.3,70.3 990.9,70.5 991.8,70.2 992.7,70.1 992.8,70.6 992.9,71.0 993.6,71.4 994.3,71.6 994.8,71.6 995.1,71.2 995.5,71.5 995.8,71.9 995.9,72.3 996.4,72.7 996.5,73.1 995.7,73.3 996.3,73.5 996.3,73.9 996.6,73.5 997.0,73.9 998.1,74.5 997.9,75.0 998.3,75.3 998.8,75.6 998.5,76.1 998.0,76.4 997.6,76.7 997.1,76.8 994.5,76.3 993.5,76.2 992.6,76.1 992.7,75.7 992.1,75.7 991.7,75.6 991.7,76.0 992.2,76.1 991.4,76.3 990.8,76.4 990.1,76.6 987.8,77.3 987.3,77.4 986.9,77.5 985.5,77.9 985.0,78.1 984.1,78.3 983.7,78.3 982.8,78.7 982.3,78.6 981.6,79.0 980.9,79.4 980.2,79.3 980.3,79.7 979.8,79.7 979.4,80.0 978.9,80.1 978.4,80.6 978.0,80.8 977.5,80.9 977.0,81.0 976.4,81.3 974.9,81.9 974.4,82.0 973.9,82.1 973.6,82.6 973.4,83.2 972.6,83.4 972.2,83.1 971.9,82.7 971.2,82.1 970.2,81.8 968.9,81.8 968.5,81.8 967.9,81.7 967.0,81.7 966.0,81.9 965.6,82.0 964.5,82.2 963.8,82.5 962.4,83.5 962.0,83.7 961.6,83.8 961.5,83.4 961.7,82.8 961.9,82.4 962.1,82.0 961.6,82.0 960.9,82.3 959.9,82.7 959.5,82.8 959.1,83.0 958.6,83.1 958.5,83.5 957.9,83.8 957.4,83.3 957.0,83.2 956.6,83.2 956.2,83.4 955.6,83.3 954.9,83.2 954.4,83.6 953.9,83.8 953.7,84.2 953.5,84.7 953.5,85.3 953.0,85.7 952.6,85.8 952.6,86.2 951.8,86.7 951.3,86.9 950.4,87.6 950.1,88.1 949.9,88.7 950.1,89.1 950.5,89.4 951.1,89.5 951.8,89.0 953.2,89.3 953.4,89.8 952.7,90.3 952.2,90.7 952.2,91.4 952.2,92.0 952.6,92.4 953.2,92.4 953.4,92.9 953.7,93.8 953.3,94.1 952.9,94.3 952.3,94.3 951.7,93.8 952.5,93.3 952.9,93.0 952.4,93.1 951.9,93.1 951.4,93.3 951.5,93.7 950.9,93.9 950.4,94.1 949.8,94.9 949.4,95.4 949.2,95.8 949.2,96.2 949.4,96.7 950.0,97.2 950.3,97.9 949.9,98.1 949.2,98.5 948.0,98.6 947.6,98.3 947.0,98.4 946.6,98.5 945.9,98.8 945.2,99.2 944.6,99.5 944.2,100.0 944.0,100.6 944.2,101.1 944.2,101.5 944.4,102.0 944.5,102.4 943.8,102.1 943.3,102.1 942.0,102.5 941.5,102.6 941.0,103.0 940.5,102.6 940.1,102.9 940.6,103.1 940.4,103.6 940.3,104.3 940.2,104.7 939.8,105.3 939.2,106.1 938.4,106.7 937.9,106.9 937.5,107.2 936.7,107.7 935.7,108.3 935.3,108.2 935.2,107.7 934.8,107.5 934.7,107.0 934.7,105.8 934.4,104.5 934.3,104.1 933.8,103.5 933.6,102.8 933.2,100.7 933.1,100.2 932.5,98.6 932.3,97.6 932.1,96.7 932.1,96.3 932.3,95.0 932.5,94.2 933.3,92.5 934.8,91.6 935.4,91.2 936.0,90.4 936.0,90.0 935.5,89.6 936.1,89.4 936.7,89.5 937.4,89.4 938.0,88.8 938.8,88.9 939.5,88.8 940.1,88.4 940.8,88.1 941.8,87.7 942.2,87.4 942.9,87.0 943.3,86.7 944.0,85.8 945.4,85.0 946.0,84.6 946.4,84.4 946.8,84.4 947.8,83.8 948.5,83.3 949.3,82.9 950.0,82.2 950.7,81.8 952.0,81.5 952.7,81.2 953.8,81.1 954.4,80.9 954.3,80.5 955.3,79.9 955.5,79.5 955.0,79.3 955.2,78.8 955.6,78.6 955.7,78.1 955.8,77.7 956.1,77.0 957.2,76.5 958.0,76.6 958.7,76.6 959.1,76.5 959.5,76.5 958.5,76.3 957.8,76.2 957.1,75.9 956.7,75.8 956.3,75.8 953.7,76.2 953.6,76.7 953.2,77.1 953.1,77.6 952.8,78.1 953.3,78.5 953.2,78.9 952.8,79.0 952.4,78.6 952.0,78.6 951.4,78.7 950.5,79.1 947.3,80.7 946.6,81.2 945.8,81.3 945.2,81.5 945.1,81.0 945.5,80.5 945.0,80.4 944.5,80.5 944.1,80.7 944.3,80.2 944.1,79.7 944.9,79.1 945.3,78.4 945.1,78.0 943.7,78.4 943.2,78.6 942.8,78.3 942.2,78.0 941.2,78.2 940.4,78.3 939.8,78.3 939.3,78.4 938.3,78.3 937.4,78.3 936.3,78.7 935.8,79.0 935.2,79.2 935.1,79.8 934.7,80.0 934.3,80.1 933.5,80.6 932.9,81.2 932.5,81.4 931.7,81.8 930.5,82.3 929.4,83.1 929.0,83.7 928.6,83.8 928.5,84.3 928.4,84.8 928.8,84.8 929.4,84.6 930.5,84.9 931.0,85.1 931.0,85.6 930.6,85.6 930.1,85.6 929.7,85.7 929.0,85.5 928.5,85.8 927.8,85.9 926.9,85.5 926.0,85.5 925.8,85.9 925.2,85.9 924.7,86.3 923.8,86.2 923.3,86.0 922.7,86.1 921.4,86.5 920.3,86.5 919.8,85.9 920.8,85.7 921.5,85.7 922.2,85.7 922.9,85.5 922.5,85.3 922.1,85.3 921.7,85.2 920.8,84.7 920.4,84.6 919.9,84.5 919.5,84.5 919.1,84.8 918.7,84.8 918.0,84.7 918.5,84.6 917.9,84.5 917.2,84.3 915.7,84.0 915.1,84.0 914.7,84.1 914.1,84.4 914.5,84.8 913.7,84.8 913.3,84.6 913.2,85.1 913.6,85.0 913.1,85.4 912.5,85.4 911.8,85.0 910.8,85.0 910.2,85.3 909.8,85.4 908.4,85.1 907.8,85.1 907.0,84.8 906.3,85.5 905.7,85.6 905.1,85.2 904.3,85.0 901.3,85.1 900.3,85.0 899.6,85.0 898.7,85.2 897.8,85.1 896.1,85.4 895.4,85.7 894.5,86.1 893.8,86.8 893.3,87.1 892.6,87.4 891.6,87.7 891.1,88.0 890.3,89.3 888.9,89.8 888.5,90.1 887.8,90.4 887.3,90.7 886.6,90.9 886.0,91.4 885.2,91.8 883.9,92.7 883.7,93.1 882.5,94.1 882.1,94.1 881.6,94.5 881.1,94.7 880.6,95.0 880.0,95.3 879.1,95.6 878.7,95.8 878.3,96.2 877.1,96.8 876.5,96.9 875.7,97.4 875.7,98.0 876.2,98.1 877.4,98.4 878.4,98.3 879.4,98.3 879.8,98.3 880.1,98.7 879.8,99.8 879.7,100.2 880.0,100.6 880.6,100.4 881.0,100.5 881.3,99.9 880.8,99.6 881.6,99.2 882.0,99.2 882.4,99.2 882.0,99.6 881.5,99.7 881.9,99.9 882.3,100.1 882.9,100.1 882.3,100.4 882.0,100.8 881.4,101.0 882.6,101.2 883.2,101.1 884.0,100.8 884.4,100.3 884.8,100.1 884.9,100.5 884.5,100.9 884.1,101.1 884.6,101.3 885.2,100.7 885.3,99.9 885.3,99.4 886.4,99.4 887.0,99.5 888.1,99.2 888.5,99.4 889.4,99.9 889.8,100.5 890.8,101.1 891.7,101.4 892.3,101.8 892.7,102.0 892.6,102.5 892.2,102.7 891.3,102.5 891.9,103.1 892.4,103.2 892.4,103.7 892.0,104.3 892.6,104.8 893.0,105.1 892.7,105.8 892.0,106.3 891.5,106.6 891.2,107.2 890.8,107.7 890.7,108.2 890.3,108.9 890.2,109.6 890.4,110.7 890.2,111.4 890.3,111.8 890.3,112.2 890.1,113.0 889.9,113.4 889.8,113.9 889.5,114.5 889.4,115.2 888.9,115.8 888.2,116.2 887.6,116.7 887.1,117.0 886.6,117.7 886.1,118.4 885.0,119.3 884.7,119.7 884.4,120.2 884.3,120.7 883.9,120.9 883.6,121.5 882.7,122.4 881.7,123.2 881.0,123.9 880.0,124.5 879.5,125.1 879.1,125.5 878.5,125.9 878.2,126.4 877.7,126.6 876.5,127.9 875.7,128.7 875.4,129.1 874.8,129.4 874.1,129.7 872.7,130.4 872.2,130.7 871.4,131.0 870.4,131.2 869.9,131.4 869.4,131.1 868.6,130.9 868.0,130.8 867.5,130.9 867.6,129.9 866.7,130.2 866.3,130.3 866.7,129.8 866.1,129.8 865.3,130.6 865.0,131.0 864.6,131.4 864.0,131.5 863.2,131.5 863.4,131.9 863.1,132.4 862.7,132.5 862.0,132.7 861.6,133.1 861.1,133.4 860.8,133.9 860.4,134.1 860.2,134.5 860.4,135.0 860.3,135.8 860.4,136.3 859.3,136.9 858.6,137.5 858.2,137.7 857.5,138.0 857.0,138.5 856.6,138.6 855.8,138.8 855.2,139.2 854.4,139.5 854.3,140.1 854.2,140.6 853.8,140.8 854.2,141.2 854.7,141.3 855.5,142.0 855.9,142.2 856.5,142.6 857.3,144.0 857.9,144.8 858.5,145.3 859.3,146.5 859.5,147.1 859.6,147.9 859.6,148.7 859.4,149.1 859.4,149.6 859.6,150.0 859.7,150.9 859.5,151.4 859.2,151.9 858.9,152.3 858.5,152.4 857.8,152.5 857.3,152.4 856.8,152.6 856.8,153.0 856.3,153.0 855.8,153.0 855.2,152.9 854.8,152.9 854.8,153.4 854.3,153.4 853.9,153.3 854.1,153.8 853.8,154.2 853.3,154.0 853.5,153.6 852.9,153.9 852.5,154.3 852.1,154.1 851.7,154.4 851.3,154.2 850.9,153.9 851.5,153.6 851.5,153.2 851.1,153.0 850.8,152.3 851.1,151.9 851.3,151.5 851.7,151.2 851.3,151.1 851.7,150.8 852.1,150.4 851.9,150.0 851.5,149.5 851.5,149.1 851.5,148.7 851.4,148.1 850.6,148.1 850.6,147.6 851.0,147.3 851.6,147.2 852.2,147.4 852.7,147.5 852.2,146.9 852.2,146.4 851.8,146.0 851.7,145.5 851.7,145.1 851.0,144.8 850.6,144.9 850.1,144.8 849.4,144.5 848.8,145.0 848.3,145.2 847.5,144.8 847.8,144.3 847.2,144.2 846.6,144.2 846.9,143.8 847.1,143.1 848.1,142.6 848.6,142.6 848.0,142.4 847.7,142.0 848.4,140.9 848.3,140.5 847.7,140.0 846.9,139.7 846.5,139.6 846.2,140.0 846.0,139.5 845.6,139.3 845.5,138.9 844.7,139.3 843.8,139.4 843.4,139.3 843.0,139.5 842.6,139.5 841.8,139.8 841.2,140.0 839.8,140.6 839.2,141.2 838.8,141.5 838.3,141.7 837.9,141.8 837.5,142.1 837.0,142.2 836.6,142.4 836.4,141.9 836.8,141.8 838.0,141.4 838.0,140.9 838.4,140.6 837.5,140.6 837.1,140.6 836.9,140.2 837.4,139.9 837.5,139.3 838.3,139.0 838.8,138.5 839.4,137.9 839.6,137.5 839.3,137.0 838.5,136.6 838.4,136.2 837.8,136.5 836.6,136.4 836.1,136.8 835.7,137.1 834.7,138.2 832.9,138.9 832.2,139.2 831.6,139.6 831.3,140.1 831.2,140.5 830.7,141.0 830.3,141.2 829.9,141.2 829.5,141.2 829.1,141.3 828.6,141.5 828.2,141.1 827.4,141.1 826.7,142.1 826.5,142.5 826.8,143.3 827.8,143.9 829.3,144.2 830.0,144.1 830.4,144.3 830.6,144.7 830.7,145.1 830.4,145.8 830.4,146.3 830.9,146.7 831.4,146.8 831.8,146.9 832.7,146.8 833.0,146.4 833.8,145.8 834.2,145.5 835.4,144.9 836.2,145.2 836.7,145.6 837.2,145.6 837.9,145.9 838.4,146.0 838.8,146.0 839.2,145.8 839.8,146.1 840.3,146.1 840.7,146.1 840.5,146.7 840.1,147.0 840.3,147.4 839.8,147.7 839.3,147.3 838.7,147.3 838.0,147.7 837.3,148.0 836.5,148.2 836.1,148.3 835.6,148.2 835.8,148.7 835.3,148.9 835.2,149.5 834.8,149.7 834.4,149.9 834.3,149.5 833.8,149.4 833.8,150.0 833.5,150.4 833.1,150.9 832.6,151.1 832.2,151.5 831.7,151.9 831.5,152.5 831.0,153.2 831.5,153.5 832.2,153.9 832.7,154.2 833.2,154.3 833.9,154.7 834.2,155.1 834.5,155.9 834.7,156.3 835.0,157.0 835.4,157.7 835.7,158.3 835.8,158.8 835.7,159.3 836.1,159.5 836.9,159.8 837.2,160.5 838.0,161.0 838.4,161.4 838.5,161.9 838.0,161.9 837.1,161.5 836.5,161.5 836.0,161.5 835.5,161.0 834.8,160.8 833.8,161.2 833.4,161.3 833.9,161.4 834.3,161.2 834.7,161.1 835.3,161.2 835.5,161.6 835.9,161.8 836.3,161.9 836.7,162.1 837.1,162.5 837.9,163.0 838.3,163.4 838.5,164.1 838.0,164.3 837.6,164.3 837.0,164.7 836.1,165.1 835.8,165.6 835.1,165.6 834.6,165.6 834.0,165.9 834.7,165.8 835.1,166.3 835.8,166.2 836.6,165.8 837.1,165.9 838.0,166.7 838.4,166.8 838.9,167.0 838.0,167.8 837.5,168.1 838.0,168.0 838.4,167.8 838.8,168.1 838.7,169.1 838.3,168.8 837.9,168.8 837.5,168.9 838.0,169.4 837.6,169.6 837.9,170.1 837.5,170.2 837.6,170.8 837.8,171.2 837.1,171.6 836.7,171.3 836.2,171.8 835.8,172.2 835.4,172.2 835.2,172.9 835.0,173.4 835.0,173.9 834.6,174.3 834.1,174.7 833.7,175.3 833.6,175.9 833.2,176.2 832.8,175.7 832.4,175.7 832.3,176.1 832.7,176.3 833.0,176.7 832.5,177.1 832.1,177.4 831.6,177.6 831.2,177.5 831.5,177.9 831.9,177.8 832.3,177.8 832.3,178.3 832.1,178.9 832.3,179.3 831.9,179.4 831.5,179.3 831.1,179.3 831.2,179.7 830.6,179.9 830.4,180.5 829.7,181.0 829.6,181.6 828.9,181.7 828.3,181.6 827.8,181.8 827.3,182.0 827.8,182.3 827.9,182.7 827.5,183.0 827.1,183.3 826.7,183.8 826.3,183.8 826.2,184.4 825.8,184.1 825.4,184.4 824.7,184.3 824.6,184.9 824.2,185.1 824.1,185.5 823.7,185.6 823.7,186.1 823.2,186.3 822.8,186.3 822.4,186.4 821.8,186.7 821.2,186.5 820.8,186.9 820.2,186.7 819.7,186.7 819.2,187.0 818.7,187.2 818.6,186.8 818.2,187.2 817.8,187.3 817.4,187.4 817.6,187.8 817.0,187.9 816.5,187.9 816.7,187.5 816.2,187.2 815.7,186.7 815.6,186.2 815.6,185.8 815.1,186.0 814.8,186.4 815.1,186.9 815.4,187.8 815.4,188.3 815.0,188.4 814.6,188.6 814.1,188.3 813.8,189.1 813.4,189.0 812.9,189.3 812.5,189.4 812.3,189.0 812.2,189.4 811.6,189.5 811.2,189.3 810.8,189.7 810.3,189.8 809.4,190.2 808.9,190.3 808.5,190.2 808.0,190.6 807.4,190.9 807.0,191.1 806.7,190.7 806.6,191.2 806.1,191.6 806.1,192.1 806.6,192.1 806.4,192.6 807.0,193.0 806.8,193.5 805.9,193.7 805.4,193.6 805.5,193.2 805.0,192.7 804.9,192.3 804.6,191.9 804.7,191.5 804.9,191.0 805.3,190.6 804.9,190.1 804.4,189.8 804.3,190.2 803.7,190.4 803.2,190.5 803.1,190.0 802.6,189.9 802.1,189.9 801.7,189.5 801.3,189.2 801.4,189.6 801.2,190.0 800.8,189.9 800.4,190.1 799.9,190.3 799.5,190.3 799.0,190.6 798.4,190.9 798.3,191.3 797.7,191.8 797.3,191.7 796.9,191.8 796.5,191.7 796.5,192.2 796.0,193.0 796.0,193.4 795.5,193.9 794.9,194.5 794.4,194.6 793.9,195.6 793.8,196.2 793.7,196.9 793.4,197.3 793.7,197.8 793.9,198.2 794.1,198.6 794.6,199.1 795.1,199.4 795.6,199.9 795.7,200.3 795.8,200.8 795.4,200.7 795.9,200.9 796.5,201.8 797.0,202.2 797.6,202.6 797.7,203.1 798.2,203.4 798.7,203.8 799.2,204.2 799.5,204.7 800.0,204.6 800.5,205.1 800.8,205.5 801.1,205.9 801.6,206.7 802.1,207.1 802.5,207.8 802.6,208.3 802.8,208.9 803.0,209.6 803.2,210.0 803.3,210.4 803.4,210.8 803.6,211.5 803.5,212.2 803.6,212.6 803.5,213.1 803.8,213.8 803.9,214.7 803.5,214.7 803.6,215.6 803.4,216.5 803.5,216.9 803.3,217.3 803.1,217.8 802.7,218.5 802.3,218.6 801.9,218.9 801.5,219.0 800.8,219.6 800.3,219.7 800.0,220.2 799.6,220.3 798.8,220.7 798.3,220.9 797.9,221.1 797.5,220.8 797.2,220.4 797.1,221.0 796.7,221.0 796.1,220.9 796.5,221.0 796.5,221.4 795.8,221.4 796.5,221.7 796.4,222.1 796.3,222.5 795.7,222.4 794.8,221.6 795.9,222.7 796.0,223.2 795.5,223.5 795.0,223.1 794.2,222.3 794.8,223.1 795.0,223.6 793.1,224.7 792.8,225.1 792.6,225.6 792.2,225.8 791.4,226.2 791.0,226.1 791.4,225.7 791.1,224.5 791.2,223.3 791.4,222.7 791.9,222.5 791.7,222.0 791.3,221.9 790.7,221.7 790.3,221.3 789.6,220.7 788.7,220.6 787.9,220.8 787.6,220.4 788.0,220.1 788.1,219.7 787.9,219.3 787.4,219.2 787.1,219.7 786.5,219.7 786.4,219.2 786.4,218.4 786.1,217.8 786.0,217.3 785.5,217.0 785.4,216.6 785.0,216.1 784.5,216.2 784.1,215.6 783.7,215.4 783.2,215.1 782.6,214.8 781.8,214.9 780.8,214.8 780.4,214.9 780.3,214.4 780.3,213.8 780.4,213.4 780.3,213.0 780.3,212.6 779.6,212.4 778.4,212.5 777.8,212.9 777.9,213.4 778.0,213.8 777.7,214.5 777.8,215.7 777.7,216.2 777.3,216.8 777.2,217.4 776.7,218.2 776.6,218.8 776.4,219.2 776.3,219.8 775.8,220.6 775.7,221.1 775.5,221.5 775.5,222.4 775.4,223.0 775.8,223.8 775.7,224.3 776.1,224.4 777.0,224.1 777.4,224.5 777.7,225.9 777.9,226.4 778.4,226.6 778.6,227.0 779.0,229.3 779.3,229.9 778.9,229.3 778.6,228.9 778.5,228.4 778.2,228.9 778.8,229.8 779.4,230.1 780.0,230.6 780.6,230.9 781.0,230.9 781.4,230.8 781.9,230.9 782.3,231.2 782.8,232.0 783.6,232.7 784.1,232.8 784.8,233.7 785.5,234.3 786.1,234.7 786.7,235.4 787.3,236.5 787.4,237.0 787.4,237.8 787.3,239.0 787.1,239.5 787.4,240.2 787.3,240.6 787.3,241.9 787.6,242.3 788.4,242.8 788.8,243.7 789.5,245.2 789.7,245.9 789.2,246.1 789.1,245.7 788.7,246.0 788.0,246.0 787.6,246.3 787.2,245.8 785.8,245.0 785.4,244.8 784.9,244.3 783.7,243.8 783.0,243.2 782.0,242.5 781.5,242.1 781.5,241.6 781.4,241.0 780.9,240.4 780.6,239.9 780.1,239.5 779.8,239.0 779.9,238.6 779.5,237.9 779.5,237.1 779.1,236.0 778.8,234.5 778.8,233.9 778.7,233.4 778.5,232.8 778.2,232.4 777.4,231.2 776.9,230.9 777.0,230.3 776.5,229.9 776.0,229.5 775.8,229.0 775.5,228.6 775.1,228.4 774.9,227.9 774.4,227.6 774.2,227.1 773.8,226.8 773.4,227.3 772.9,226.6 772.9,225.6 773.1,225.1 773.3,224.2 773.5,223.6 773.8,222.7 774.2,221.7 773.8,222.1 773.6,221.7 773.7,221.2 773.5,220.3 773.9,219.8 774.1,219.1 774.2,218.5 774.3,218.0 774.4,217.6 774.0,217.4 774.0,217.0 774.1,216.5 774.2,216.0 774.0,215.4 774.1,214.8 774.0,214.3 773.9,213.9 773.8,213.4 773.4,212.5 772.9,211.8 772.9,211.3 772.6,212.1 772.5,211.4 772.5,210.7 772.2,210.2 772.2,209.8 772.0,209.3 771.7,208.7 771.7,207.8 771.6,207.1 771.4,205.9 771.1,205.5 771.2,204.9 771.2,204.3 770.8,204.1 770.4,203.7 770.0,203.1 769.9,202.6 769.6,202.2 769.0,201.7 769.0,202.2 769.2,202.7 768.9,203.4 768.4,204.0 767.9,204.2 767.4,203.9 767.4,203.4 767.3,203.8 767.6,204.3 767.0,204.6 766.0,205.1 765.8,205.6 765.4,206.0 765.0,206.3 764.7,205.9 764.8,205.3 764.5,205.9 764.1,206.0 763.7,206.1 763.6,205.6 763.6,205.2 763.3,205.6 762.9,205.8 762.9,205.4 763.0,204.9 763.1,204.4 762.7,204.8 762.3,205.3 761.9,205.5 761.9,204.1 762.2,203.1 762.4,202.4 762.7,201.9 762.7,201.2 762.7,200.8 762.3,199.4 761.8,198.6 761.6,198.0 761.4,197.6 761.2,196.8 761.2,196.4 760.9,196.8 761.0,197.2 760.6,197.3 760.0,196.7 759.7,196.2 760.4,196.5 760.8,196.5 761.1,196.0 760.7,195.7 760.4,195.3 760.0,195.1 760.3,194.7 759.9,194.7 759.5,194.3 759.0,194.2 758.9,194.7 758.3,194.2 758.5,193.4 758.0,194.0 758.0,193.6 757.7,193.1 757.6,193.6 757.2,193.1 756.6,192.4 756.2,192.0 755.7,191.2 755.6,190.2 755.6,189.8 755.3,189.2 755.1,188.5 755.2,188.0 754.8,187.8 754.2,186.9 754.1,186.4 753.6,186.8 753.2,187.2 752.6,187.2 751.8,186.0 751.8,185.4 751.7,184.9 751.7,184.5 751.5,184.9 751.1,184.9 750.7,184.8 751.1,185.1 751.6,185.4 751.5,185.9 751.5,186.4 751.2,186.8 751.4,187.3 751.7,187.7 751.6,188.2 751.0,188.8 750.8,189.2 750.4,189.4 750.2,189.0 750.6,188.5 750.2,188.6 749.8,188.6 749.7,188.0 750.0,187.6 749.6,188.1 749.6,188.6 749.3,189.1 748.8,189.5 748.7,188.9 748.6,188.1 748.6,188.8 748.5,189.4 748.0,189.7 747.5,189.2 747.4,188.8 747.3,189.3 747.4,189.9 747.0,189.9 746.5,190.0 746.4,189.6 746.5,188.9 746.2,188.5 746.0,189.4 746.1,189.8 745.7,190.0 745.3,189.7 744.8,189.9 744.7,189.5 744.9,188.8 744.7,188.3 744.3,187.8 744.3,188.2 744.7,188.4 744.7,188.8 744.3,189.4 743.5,189.9 742.2,190.2 741.5,190.7 741.2,191.4 741.4,191.8 741.5,192.4 741.2,193.0 741.0,193.5 740.3,194.0 739.9,194.4 739.5,194.7 738.5,195.0 737.7,195.3 737.7,194.8 736.8,195.1 736.6,195.5 737.1,195.3 736.7,195.8 735.5,196.9 735.2,197.3 734.6,198.1 733.8,198.9 732.4,199.8 731.6,200.6 731.1,201.1 730.5,201.5 729.4,202.0 728.8,202.5 728.6,203.0 728.8,203.4 728.5,204.0 727.1,204.6 726.1,204.5 725.7,204.8 725.4,205.7 725.0,206.1 724.6,206.2 724.2,205.9 723.3,206.1 722.5,207.4 722.4,208.1 722.5,208.9 722.7,209.5 722.7,210.1 722.5,210.5 722.6,210.9 722.8,211.5 722.9,212.0 723.1,212.5 722.7,211.9 722.5,212.4 723.0,212.7 722.9,214.7 722.6,215.4 722.3,215.8 721.8,216.7 721.6,217.5 721.6,218.2 721.4,218.6 721.8,218.7 721.8,220.1 721.8,221.3 721.3,221.4 720.9,221.3 720.5,221.4 720.1,221.7 720.2,222.1 719.4,223.1 719.2,223.7 719.5,224.1 720.2,224.2 720.6,224.5 720.0,224.3 719.4,224.3 717.8,224.7 717.4,225.0 717.0,225.9 717.0,226.4 716.0,227.3 715.5,227.4 714.7,227.4 714.1,226.9 712.8,225.4 712.5,224.7 712.2,224.3 712.0,223.7 711.9,223.1 711.8,222.4 712.1,223.0 712.2,223.5 712.1,222.4 711.7,222.2 711.6,221.8 711.5,221.3 710.9,220.0 710.7,219.3 710.3,218.4 709.8,217.5 709.2,216.8 708.2,215.1 708.0,214.3 707.7,213.7 707.4,212.5 707.4,212.0 707.2,211.5 706.9,211.0 706.8,210.6 706.7,210.0 706.5,209.5 706.2,209.1 705.8,208.6 705.4,208.1 705.2,207.5 705.1,207.0 705.1,206.5 704.7,206.4 704.5,205.9 704.1,205.4 703.7,204.3 703.4,202.2 703.2,201.3 702.9,200.3 702.8,199.7 702.7,199.3 702.5,198.4 702.5,197.8 702.7,197.4 702.7,196.8 702.5,197.2 702.2,196.6 702.7,196.5 702.2,196.2 702.2,195.8 701.9,195.1 702.0,194.2 702.4,192.9 702.4,192.1 702.3,191.4 701.9,191.2 701.7,190.6 702.0,190.1 702.8,189.7 702.3,189.8 701.5,189.7 701.6,189.2 701.5,188.4 702.0,188.3 701.6,188.1 700.9,188.1 700.5,188.1 700.8,188.6 700.4,188.9 700.2,189.3 700.6,189.6 700.7,190.2 700.2,191.0 698.8,191.8 698.3,192.0 697.3,192.4 696.9,192.5 696.4,192.4 695.8,192.1 694.8,191.4 693.7,190.3 693.2,189.8 692.7,189.3 692.2,188.9 691.7,188.3 691.6,187.8 692.0,187.7 692.3,188.1 693.2,187.8 693.7,187.6 694.5,187.4 694.9,187.3 695.4,186.6 695.7,186.2 695.1,186.2 694.0,186.5 693.5,186.8 692.3,186.5 691.2,186.0 690.7,185.6 690.4,185.1 690.0,184.5 690.6,184.0 691.0,183.7 690.3,184.0 689.8,184.4 689.4,184.1 689.3,183.7 688.9,183.8 688.5,183.6 688.0,183.9 687.5,183.5 687.1,183.1 687.0,182.6 686.6,181.2 685.3,180.9 685.3,180.2 684.9,179.5 684.5,179.0 683.9,178.9 684.3,179.1 683.0,179.4 682.4,179.6 681.7,179.5 680.7,179.7 679.9,179.7 679.4,180.0 678.2,179.6 677.7,179.6 677.0,179.5 676.5,179.6 676.4,180.0 675.8,179.9 675.0,179.9 674.1,179.8 673.5,180.0 673.1,180.2 672.6,180.0 672.0,180.2 671.5,180.2 670.9,180.0 670.1,180.2 669.7,180.0 668.5,179.8 668.3,179.4 667.8,179.7 666.7,179.5 666.2,179.4 665.6,179.4 665.2,179.2 664.5,179.4 664.0,179.4 663.3,179.0 662.6,178.9 662.0,178.9 661.2,178.8 660.5,178.7 659.3,178.4 659.1,178.0 658.9,177.3 658.6,176.7 658.5,175.9 658.3,175.3 657.8,174.8 656.5,174.4 655.9,174.6 655.4,174.9 654.6,175.1 654.2,175.5 653.6,175.6 653.2,175.8 652.5,176.2 652.1,176.4 651.4,176.1 650.7,175.8 650.2,175.7 649.5,175.8 648.6,175.4 648.2,175.0 647.2,174.6 646.4,174.1 646.1,173.6 645.0,173.0 644.5,172.7 644.0,172.6 643.5,172.7 643.1,172.5 642.4,171.9 642.0,171.0 641.8,170.2 641.3,169.8 641.3,169.4 640.8,169.0 640.7,168.5 640.4,167.9 640.0,167.6 639.5,167.0 639.2,166.5 639.1,166.1 637.7,166.6 637.3,166.3 636.3,165.8 636.8,165.5 636.1,165.3 635.8,165.8 635.9,166.3 635.2,166.6 634.8,166.8 634.3,166.8 633.7,166.6 633.3,166.6 633.3,167.1 633.5,167.5 633.7,167.9 633.2,167.7 632.8,168.1 633.2,168.4 633.6,168.7 633.8,169.5 634.3,170.1 634.4,170.5 634.7,171.0 635.1,171.9 635.5,172.3 635.6,172.8 635.8,173.3 636.4,173.5 636.8,173.6 636.9,174.1 637.2,174.5 637.6,174.6 638.1,175.1 638.8,175.5 639.3,175.9 638.9,175.9 639.0,176.3 639.4,176.7 639.3,177.5 639.1,177.9 639.4,178.5 639.7,179.0 640.2,179.4 640.4,180.3 640.7,180.7 641.1,181.1 641.0,180.1 641.0,179.4 641.3,178.9 641.4,178.5 641.7,177.8 642.4,177.4 643.2,178.1 643.1,178.7 643.0,179.1 643.1,179.5 643.3,180.1 643.3,180.7 642.9,181.5 642.4,181.6 642.8,181.7 642.5,182.4 643.0,182.5 643.4,182.5 643.8,182.6 643.9,183.1 644.8,183.4 645.9,183.0 647.3,182.9 648.1,183.1 649.4,183.1 650.4,182.9 650.8,182.6 651.3,182.3 651.5,181.9 651.8,181.3 653.0,180.4 653.6,179.9 654.0,179.5 655.4,178.3 655.8,177.6 656.0,177.2 656.4,177.1 656.7,177.5 656.6,178.3 656.4,178.7 656.6,180.5 656.9,181.3 657.3,182.0 657.7,182.4 658.1,182.9 658.7,183.4 660.0,183.9 660.6,184.0 661.4,184.1 662.0,184.4 662.5,184.3 663.3,184.7 663.6,185.2 664.0,185.7 664.4,186.2 664.8,186.7 665.1,187.1 665.8,187.4 666.2,187.5 666.2,188.0 665.8,188.7 665.3,189.5 664.9,190.3 664.1,190.9 663.6,191.4 663.0,192.2 662.6,193.0 662.1,193.4 661.7,193.3 661.8,192.8 661.4,192.9 661.0,193.5 660.7,194.1 660.6,194.6 660.4,195.0 660.3,195.5 660.4,196.0 660.5,196.5 660.6,197.2 660.2,197.3 659.5,197.4 658.8,197.5 658.2,197.7 657.8,197.9 657.4,198.4 657.1,199.5 656.6,200.0 655.5,200.2 654.5,200.3 654.1,200.4 653.5,201.1 653.6,201.5 653.5,201.9 653.3,202.3 653.0,202.7 652.1,202.9 651.6,202.7 651.0,202.7 650.2,202.8 649.4,203.2 648.9,203.4 648.0,203.5 647.5,203.8 646.1,204.2 645.7,204.5 645.1,205.1 644.9,205.7 645.1,206.2 644.7,206.7 644.3,206.8 643.7,207.1 643.3,207.4 642.6,207.7 641.7,207.9 640.4,208.2 639.8,208.5 639.4,208.7 638.6,208.8 637.6,209.1 637.1,209.3 636.4,209.7 636.1,210.1 635.5,210.8 635.0,211.0 634.6,211.1 634.1,211.1 633.3,211.0 632.9,211.2 632.3,211.5 631.7,212.1 631.2,212.2 630.5,212.4 630.0,212.6 629.6,212.7 629.2,212.7 628.3,212.7 627.6,212.8 626.8,212.9 626.1,213.7 625.5,213.9 625.1,214.4 624.7,214.5 624.3,214.5 623.9,214.4 623.2,214.8 622.5,214.9 622.0,215.0 621.2,214.6 620.8,214.7 620.8,214.3 620.1,213.1 620.2,212.1 620.1,211.5 619.7,211.1 619.7,210.5 619.5,209.8 619.3,209.0 619.3,208.6 619.0,208.0 618.5,207.7 618.9,207.6 618.7,206.5 619.0,205.5 618.9,204.5 618.7,204.0 618.6,203.5 618.2,203.1 618.0,202.6 617.6,202.1 617.5,201.6 616.8,200.9 616.0,200.3 615.3,199.3 615.1,198.7 614.5,198.1 614.4,197.6 614.2,197.0 613.6,195.9 613.3,195.4 612.8,194.9 612.4,194.5 611.3,193.7 610.8,193.6 610.4,193.4 610.0,193.0 609.7,192.4 609.1,191.7 608.6,190.8 608.7,190.2 608.6,189.8 608.3,189.2 608.4,188.8 608.4,188.3 608.6,187.8 608.5,187.2 608.4,186.7 608.0,186.4 607.8,186.0 607.7,185.6 607.1,184.6 606.8,184.1 606.4,183.6 605.8,183.2 605.3,182.8 604.8,182.6 604.3,182.5 604.0,182.1 603.7,181.6 603.3,181.1 603.5,180.7 603.4,180.1 603.2,179.7 602.6,178.8 602.1,178.5 602.0,178.0 601.4,177.5 600.7,176.1 600.3,175.6 599.6,174.8 599.3,174.3 598.8,173.8 598.4,173.0 597.7,172.1 596.7,171.9 596.2,172.0 596.3,171.5 596.6,170.8 596.7,170.2 597.1,168.5 597.2,168.1 596.8,168.2 596.5,168.7 596.2,170.1 595.7,171.2 595.6,171.9 595.3,172.5 595.1,172.9 594.6,172.7 593.8,172.1 593.3,171.5 592.8,171.1 592.4,170.6 592.2,170.1 592.0,169.5 591.3,168.6 591.0,168.2 590.7,167.2 590.5,166.7 590.2,167.1 589.9,167.7 590.5,168.4 590.7,168.9 590.6,169.5 591.1,170.0 591.3,170.5 591.7,171.0 592.2,171.6 592.7,172.1 593.2,172.5 593.2,173.0 593.5,173.8 593.9,174.3 594.1,174.9 594.3,176.0 595.4,177.7 596.0,178.6 596.3,179.3 596.8,180.2 597.8,182.0 598.3,182.6 599.0,183.1 599.4,183.5 599.0,183.5 598.6,183.9 598.7,184.9 598.8,185.4 599.2,186.3 599.6,186.7 600.6,187.1 601.2,187.8 602.3,188.6 602.5,189.5 602.6,190.0 603.0,190.8 603.4,191.2 603.5,191.6 603.2,192.0 603.3,192.4 603.4,192.9 603.3,193.3 603.3,194.1 603.5,195.0 603.5,195.6 603.8,197.0 604.1,197.7 604.8,198.1 605.3,198.5 605.8,198.9 606.1,199.3 606.5,199.4 607.1,199.8 608.1,201.6 608.4,202.5 608.7,203.5 609.0,205.0 609.2,205.8 609.7,206.9 610.1,207.1 610.3,207.7 610.6,207.2 611.0,207.2 611.3,207.7 611.7,208.3 612.3,208.4 612.9,208.7 613.3,209.0 614.4,209.4 615.2,210.4 615.7,211.2 617.3,212.3 617.6,212.8 617.8,213.3 618.7,213.8 618.9,214.3 619.4,214.4 619.8,214.7 620.3,215.4 620.6,216.1 620.4,216.6 619.6,217.1 618.9,217.4 618.4,217.9 618.9,217.9 619.6,217.8 620.1,218.1 620.7,218.5 621.2,219.3 621.8,220.0 622.7,220.7 623.3,221.0 624.8,221.0 625.9,220.4 626.9,220.0 627.3,219.9 627.8,220.0 628.5,220.1 629.1,220.2 630.5,219.7 631.2,219.2 631.7,219.0 632.5,219.1 633.4,219.1 634.6,218.6 635.2,218.5 635.8,218.7 636.3,218.7 637.2,218.5 637.9,218.2 639.2,218.0 640.2,217.4 640.7,216.8 641.1,216.7 642.2,217.1 642.3,217.6 642.0,218.0 641.9,218.5 642.0,219.2 642.1,220.4 641.9,220.9 642.5,220.8 641.8,221.2 641.4,221.5 641.3,222.4 641.2,223.0 641.2,223.8 640.8,224.3 640.7,224.7 640.1,225.4 639.8,226.1 639.2,227.2 638.5,227.9 638.2,228.7 638.0,229.3 637.7,229.7 637.1,230.6 636.8,231.2 636.4,232.2 636.2,232.9 635.1,234.7 634.0,236.2 633.3,237.5 632.0,239.0 630.2,240.9 627.9,243.1 627.3,243.6 624.8,245.0 623.1,246.1 622.3,246.9 621.4,247.6 620.7,248.3 618.6,250.5 618.2,250.9 618.0,251.3 617.3,252.0 617.0,252.4 616.6,252.7 616.4,253.2 615.9,254.0 615.6,254.4 615.0,255.2 614.6,255.4 614.2,255.5 613.8,255.5 613.6,255.9 613.6,256.3 612.9,257.1 612.2,257.1 611.7,257.5 611.7,258.4 611.5,258.8 611.1,259.3 610.8,259.8 610.6,260.5 610.4,260.9 610.2,261.3 609.7,262.4 609.4,262.8 609.0,263.0 608.7,263.8 608.6,264.3 608.3,265.3 607.8,266.3 607.8,266.9 608.0,267.6 608.5,268.1 609.0,268.6 609.6,269.1 609.8,269.5 609.5,270.0 609.3,270.4 609.1,270.9 609.1,271.6 609.5,271.7 609.6,272.3 609.3,272.9 609.2,273.5 609.4,274.2 609.7,274.6 610.1,275.5 610.1,276.1 610.3,276.6 610.4,277.0 610.5,277.5 611.0,278.0 611.5,278.3 612.2,278.8 612.5,279.4 612.7,279.8 612.5,280.4 612.5,281.1 612.2,281.5 612.3,282.4 612.5,282.9 612.6,283.3 612.5,283.7 612.5,284.2 612.5,284.7 612.7,285.1 612.6,285.6 612.3,286.1 612.7,286.1 612.7,286.7 612.7,287.1 612.7,287.6 612.8,288.5 612.8,289.2 613.1,289.7 612.9,290.1 613.3,290.1 613.4,290.5 613.5,290.9 613.1,291.5 612.9,291.9 612.9,292.4 612.7,293.0 612.0,293.8 611.4,294.4 611.1,295.1 610.7,295.1 610.7,295.7 610.1,296.1 609.0,296.6 608.6,297.1 608.2,297.2 607.7,297.4 607.3,297.4 606.6,297.7 606.0,297.9 605.1,298.3 604.2,298.8 603.5,299.3 602.9,299.7 602.6,300.2 602.1,300.9 601.5,301.4 601.1,301.9 600.7,302.0 600.7,302.4 600.3,302.3 599.9,302.5 599.0,303.2 598.2,304.2 597.1,305.0 596.4,304.7 596.5,305.1 596.5,305.8 596.4,306.7 596.6,307.1 597.2,307.8 597.5,308.9 597.6,309.4 598.0,310.1 598.1,311.2 598.1,311.8 598.1,312.2 598.4,311.6 598.7,312.2 598.6,312.9 598.6,313.3 598.8,313.8 598.6,314.4 598.3,315.9 598.5,316.3 598.6,316.8 597.9,317.9 597.2,318.5 596.1,318.9 594.0,319.6 593.1,320.0 592.6,320.2 591.6,320.8 591.1,321.2 590.9,321.7 590.5,322.2 590.8,322.7 591.2,323.0 591.4,322.6 591.4,324.5 591.2,325.2 590.8,326.2 590.7,326.7 590.4,328.3 589.9,329.2 589.0,330.1 588.6,330.3 587.0,331.6 586.6,332.2 586.2,333.1 585.8,333.5 585.2,334.5 584.6,335.3 584.1,336.0 583.3,337.0 582.9,337.3 581.9,338.0 580.9,338.9 580.2,339.7 579.0,340.6 578.4,341.0 577.4,341.8 576.0,342.7 575.2,343.1 573.9,343.6 573.4,343.8 572.2,343.6 571.7,343.7 571.3,344.0 571.2,344.5 570.8,344.5 569.9,344.3 569.5,344.4 569.0,344.9 568.3,344.9 567.2,344.6 565.8,344.4 564.9,344.6 563.7,344.6 563.2,344.5 562.6,344.5 561.8,344.6 560.5,345.5 559.9,345.5 559.3,345.6 558.5,345.5 558.0,345.5 557.0,345.7 555.6,346.6 555.1,346.5 554.5,346.5 553.9,346.1 553.6,345.7 553.2,345.6 552.6,345.4 552.3,345.0 552.0,344.6 551.5,344.7 551.3,345.4 551.0,345.0 550.9,344.6 551.3,344.1 551.2,343.7 550.9,343.1 550.4,342.7 550.2,342.2 549.9,341.8 549.6,341.2 550.1,341.0 550.7,340.7 550.9,340.3 550.9,339.6 550.9,339.2 550.6,338.2 549.8,337.2 549.1,336.2 548.2,334.6 547.7,333.6 547.1,331.7 546.5,330.6 545.8,329.6 545.4,329.3 544.5,328.4 544.1,328.2 543.7,327.7 542.6,326.1 542.3,325.0 542.0,324.4 542.1,323.9 541.9,323.4 541.5,322.1 541.2,321.5 541.3,320.9 541.2,320.4 541.2,319.5 541.0,318.9 540.6,318.2 540.3,317.2 540.2,316.8 540.3,315.7 540.2,315.2 540.2,314.7 540.1,314.1 540.1,313.6 540.3,313.1 540.2,312.4 539.8,311.6 538.8,310.5 538.6,310.0 538.4,309.6 537.4,308.1 536.9,307.0 536.6,306.1 536.2,305.6 534.6,302.6 534.2,302.1 533.6,301.5 533.2,300.8 532.7,300.0 532.6,299.3 532.6,298.5 532.6,297.9 532.7,296.9 532.8,296.4 532.8,295.8 532.8,294.4 532.6,294.0 533.1,293.7 533.4,293.1 533.5,292.4 534.1,290.7 534.4,289.0 534.7,288.2 534.9,287.3 535.8,286.2 536.1,285.5 536.6,285.1 537.3,284.8 537.8,284.1 538.0,283.7 538.3,282.8 538.3,281.9 538.5,280.7 538.2,279.9 538.1,279.5 537.6,278.9 537.5,278.5 537.0,277.8 536.9,277.3 536.7,276.5 536.5,276.1 536.3,275.6 536.1,275.1 536.4,274.7 537.1,274.1 537.1,273.5 536.4,271.6 535.7,270.1 535.6,269.3 534.8,268.3 534.5,267.6 534.3,267.2 534.9,266.8 535.5,266.7 536.1,266.4 535.7,266.3 535.2,266.6 534.8,266.7 534.2,266.4 533.9,266.0 533.8,265.6 533.9,265.2 533.8,264.8 533.6,264.4 533.4,264.0 533.0,263.5 532.8,263.1 532.7,262.7 532.4,262.3 531.6,261.7 530.9,260.9 530.4,260.2 529.6,259.4 528.7,258.4 527.8,257.6 527.1,257.0 527.8,257.2 527.4,256.8 526.9,256.7 526.6,256.2 526.1,255.6 526.0,255.1 525.4,254.2 525.1,253.8 525.9,254.5 526.5,254.4 526.1,254.3 526.0,253.7 525.6,253.8 525.2,253.6 524.8,253.0 524.7,252.6 524.2,251.6 524.5,252.0 524.9,251.9 525.4,251.6 525.8,251.0 525.9,250.2 525.9,249.7 525.8,249.2 526.3,249.6 527.1,249.8 527.8,249.5 527.2,249.5 526.5,249.2 526.1,248.6 526.4,248.2 526.7,247.8 526.6,247.2 526.2,246.9 526.2,246.4 526.4,246.0 526.8,245.7 527.0,245.0 527.2,244.6 527.2,243.6 527.3,242.9 527.4,242.4 527.5,241.9 527.6,241.4 527.5,241.0 526.9,240.2 526.7,239.7 526.7,239.2 527.1,239.3 526.8,238.9 526.4,238.7 526.2,239.1 525.8,239.0 525.3,238.8 524.9,238.2 524.7,237.7 524.8,237.3 524.3,237.3 523.8,237.4 523.8,236.8 523.4,236.8 522.9,236.3 523.1,237.1 522.3,237.3 521.7,237.4 521.2,237.4 520.7,237.3 520.2,237.4 519.8,237.0 519.9,237.5 519.5,237.8 519.1,237.7 519.0,237.1 518.9,237.6 518.4,237.9 518.0,238.0 517.5,238.0 517.1,238.1 516.6,237.9 516.1,237.6 515.5,237.1 515.3,236.6 515.0,235.8 514.9,235.4 515.3,235.1 514.5,234.8 515.0,234.5 514.8,234.1 514.4,234.4 514.1,234.0 513.5,233.3 512.9,232.7 512.3,232.4 511.5,232.2 509.7,232.2 510.4,231.7 509.7,231.9 509.3,232.2 507.7,232.3 506.4,232.4 505.1,232.6 504.5,232.7 503.6,232.9 503.1,233.2 502.8,233.6 502.1,234.0 500.7,234.0 499.6,234.5 499.0,234.7 498.1,235.2 497.0,235.6 495.8,236.0 495.4,236.2 494.4,236.8 493.7,236.5 493.3,236.3 492.4,236.1 491.8,236.0 491.4,235.9 491.0,235.8 491.4,235.8 491.2,235.1 490.8,235.7 489.2,235.5 488.6,235.3 487.9,235.3 487.4,235.3 488.6,235.4 487.1,235.6 486.4,235.7 486.0,235.5 485.3,235.5 485.8,235.7 484.5,235.9 483.6,236.1 483.2,236.2 481.8,236.8 481.0,237.0 480.4,237.4 479.9,237.5 479.4,237.8 479.0,237.9 477.8,237.5 477.1,237.2 474.6,236.0 474.0,235.4 473.2,234.7 471.5,233.1 471.1,232.9 470.6,232.7 470.0,232.5 469.9,232.0 469.4,231.8 468.6,231.4 468.0,230.8 467.4,230.3 466.9,230.0 465.7,229.6 465.3,229.5 465.5,229.0 465.3,228.5 464.7,228.6 464.3,228.3 464.1,227.6 463.8,227.2 463.3,226.8 463.2,226.4 463.7,226.6 464.1,226.2 463.6,226.0 463.3,225.4 463.7,225.4 463.1,225.0 463.1,224.5 462.8,224.1 462.3,223.5 462.1,222.8 462.0,222.4 461.6,222.5 461.2,222.3 461.0,221.9 460.6,221.9 459.9,221.5 459.4,220.7 459.5,220.2 459.0,219.8 458.6,219.5 458.4,220.0 458.2,219.6 458.2,219.0 457.8,219.3 457.2,218.8 457.4,218.4 457.0,218.3 457.6,217.9 458.1,217.8 457.7,217.5 457.3,217.7 456.9,217.4 457.2,217.0 457.7,217.0 458.1,216.9 457.1,216.8 456.5,217.2 456.1,217.3 455.7,217.3 455.8,216.9 455.2,216.9 454.8,216.7 454.7,216.3 454.3,216.1 453.6,215.7 453.4,215.2 454.0,215.0 453.6,215.0 453.4,214.2 453.4,213.7 453.3,212.9 453.7,212.6 454.0,213.0 454.4,213.1 454.8,213.1 455.6,212.8 456.1,212.7 456.6,212.6 457.0,212.6 456.0,212.6 455.2,212.6 454.6,212.9 454.1,212.6 453.9,212.0 453.5,211.6 453.5,211.2 453.1,210.5 452.9,210.0 452.3,209.3 451.8,209.1 451.3,209.0 452.4,208.6 453.2,207.5 454.0,206.3 454.1,204.8 454.2,204.3 454.3,203.9 454.6,203.0 455.0,202.2 455.3,201.3 455.5,200.3 455.4,199.4 455.3,198.5 455.1,198.0 455.0,197.2 454.7,196.8 454.2,196.4 454.7,195.8 454.3,195.9 454.8,195.0 454.9,194.4 455.0,193.8 454.6,193.3 454.4,192.6 454.0,192.8 453.5,192.2 453.1,191.4 452.6,192.2 452.8,190.6 453.0,189.2 453.4,188.4 454.1,188.0 454.6,187.2 454.7,186.6 455.1,186.0 455.2,185.5 455.6,184.9 456.1,184.0 455.6,184.2 455.8,183.8 456.2,183.5 456.7,183.1 457.8,182.0 458.2,181.8 458.6,181.3 458.7,180.9 458.8,179.9 458.9,179.4 459.1,179.0 459.4,178.3 459.8,177.3 460.2,177.0 460.6,176.6 461.2,176.4 462.0,176.0 462.5,175.4 462.8,174.6 463.2,173.8 463.4,173.2 463.8,172.9 464.0,172.5 464.5,172.3 465.4,172.2 466.7,171.9 467.9,171.4 468.6,170.8 469.2,170.2 470.4,169.6 470.9,169.3 471.7,168.4 472.2,167.7 472.6,167.2 472.9,166.8 473.1,166.4 473.3,165.7 472.9,165.0 472.7,164.3 472.7,163.7 472.8,162.7 473.1,161.9 474.0,160.9 474.2,160.4 474.3,159.8 475.5,158.6 476.1,157.8 476.9,157.3 479.0,156.6 480.2,156.0 480.8,155.6 481.2,155.2 482.4,153.4 483.5,150.9 484.0,150.5 484.4,150.5 485.0,150.2 485.2,150.7 485.4,151.1 485.8,151.5 486.6,152.0 487.1,152.2 488.0,152.3 488.9,152.1 489.5,152.1 490.0,152.1 490.6,152.2 491.1,152.1 491.5,151.9 491.9,152.0 492.1,152.4 492.7,152.5 493.3,152.4 493.8,152.5 494.4,152.5 495.4,152.3 495.9,151.9 496.3,151.8 496.7,151.4 497.5,150.9 498.8,150.4 499.5,150.5 499.9,150.5 500.4,149.8 500.9,149.6 501.4,149.3 502.2,149.0 502.7,148.8 503.5,148.6 505.5,148.4 506.5,148.3 507.2,148.3 507.9,147.9 508.3,147.8 509.8,147.8 510.5,147.5 513.2,147.5 513.9,147.8 514.4,148.1 515.1,148.1 515.9,147.8 516.8,147.6 517.4,147.4 518.0,147.0 519.2,147.4 519.8,147.4 520.0,147.0 520.6,147.1 521.1,147.2 521.6,147.6 522.0,147.6 522.6,147.5 523.8,147.4 524.5,147.2 525.2,146.8 526.9,146.3 527.3,146.4 527.3,146.8 527.7,146.5 528.3,146.6 528.3,147.1 528.7,147.6 528.9,148.0 529.4,147.6 529.9,147.4 530.4,147.1 530.8,147.3 530.7,147.7 530.0,148.6 529.6,148.8 529.2,149.1 529.1,149.5 529.2,149.9 529.4,150.3 530.0,150.6 530.6,151.0 530.6,151.5 530.9,152.1 530.4,152.7 530.2,153.1 529.7,153.7 529.3,154.0 528.3,154.6 528.0,155.0 527.9,155.4 528.2,156.0 528.6,156.3 529.0,156.5 529.8,156.4 529.8,156.9 530.3,156.9 530.8,156.8 531.0,157.3 531.2,157.7 531.7,157.7 532.4,158.0 532.8,158.1 534.1,158.7 534.5,158.8 535.4,158.9 536.5,158.6 536.9,158.6 537.6,158.8 538.4,158.9 539.3,159.1 540.1,159.6 542.2,160.0 542.7,160.7 542.7,161.2 542.8,161.6 543.0,162.1 543.6,162.7 544.0,162.9 544.8,163.2 545.7,163.3 546.6,163.3 548.2,163.7 549.5,164.1 549.9,164.3 550.5,164.5 551.9,165.5 552.6,165.9 553.1,165.9 553.6,165.9 554.4,165.5 554.8,165.3 555.6,164.4 555.9,164.0 555.9,163.3 555.6,162.7 555.4,162.3 555.4,161.6 555.5,161.1 555.9,160.5 556.6,159.9 557.3,159.5 558.5,159.0 559.2,158.9 560.1,158.5 560.7,158.6 561.6,158.6 562.1,158.7 562.6,158.9 563.2,159.1 563.7,159.2 564.1,159.4 564.2,159.9 564.7,160.5 566.1,160.7 566.8,161.0 568.0,161.1 568.6,161.1 569.1,161.2 569.5,161.4 569.8,161.9 570.1,162.4 570.5,162.5 571.9,162.2 573.5,162.5 574.4,162.6 575.7,162.8 576.5,163.3 577.3,163.3 577.7,163.6 579.2,163.7 580.0,164.0 580.5,164.3 581.0,164.4 581.7,164.1 582.2,163.9 583.1,163.3 583.5,163.1 584.0,163.2 584.3,162.8 584.9,162.6 585.9,162.3 584.9,162.7 585.3,162.8 585.7,162.7 586.1,162.6 586.3,162.2 587.6,162.6 588.4,162.4 588.8,162.5 589.3,162.9 588.6,162.5 588.3,163.1 588.9,163.3 589.5,163.6 589.6,163.2 590.4,163.6 590.8,163.7 591.3,163.6 592.0,163.4 592.7,163.5 593.5,163.5 594.2,163.4 594.9,163.0 595.5,162.5 596.3,161.4 596.7,160.6 597.0,159.4 597.2,158.8 597.4,158.4 597.7,157.9 597.9,157.2 598.6,155.9 598.9,155.5 599.0,154.9 599.5,154.3 599.9,154.0 599.7,153.2 599.7,152.6 599.8,152.2 599.8,151.8 599.3,151.2 599.6,150.4 599.9,150.0 599.7,149.6 599.5,149.1 600.1,148.5 600.5,148.2 600.5,147.8 600.1,147.5 599.7,147.6 599.3,147.9 598.7,148.3 598.3,148.4 597.7,148.2 597.1,148.0 596.7,147.8 596.1,147.8 595.3,148.3 594.5,149.1 593.6,149.5 593.1,149.6 591.9,149.7 591.5,149.7 591.1,149.9 590.4,149.7 589.9,149.5 589.3,148.8 588.9,148.5 588.3,148.3 587.1,147.8 586.0,147.6 585.1,147.6 584.9,148.5 584.7,149.1 584.2,149.2 583.6,149.3 582.7,149.5 581.5,149.3 581.0,148.9 580.7,148.4 580.5,148.0 580.0,148.1 579.1,147.8 578.6,147.7 578.3,148.1 577.8,148.2 577.2,148.0 576.8,148.1 576.3,148.0 576.8,147.8 577.6,147.8 578.0,147.4 578.4,147.2 576.9,147.2 576.0,147.2 576.5,146.8 576.1,146.4 575.6,146.1 575.4,145.5 575.7,144.8 574.8,144.3 574.1,143.9 573.7,144.0 573.1,143.8 573.2,143.4 573.3,142.9 573.9,142.9 573.9,143.3 574.4,143.4 575.3,143.3 574.9,143.2 574.5,142.9 574.3,142.5 574.7,142.3 574.9,141.9 574.5,141.8 574.6,141.3 574.2,140.9 574.5,140.5 574.7,140.1 573.6,140.2 573.2,140.3 572.5,140.4 572.7,139.8 572.6,139.2 573.1,138.8 573.5,138.3 574.3,137.8 575.0,137.8 575.8,137.6 576.3,138.0 577.0,138.0 577.4,137.8 577.0,137.6 577.4,137.5 577.8,137.6 578.6,137.8 579.5,137.8 580.6,137.8 580.1,137.5 580.4,137.1 582.0,136.9 582.9,136.8 581.6,136.6 580.9,136.3 580.7,135.8 581.4,135.5 583.1,135.7 584.3,135.6 585.6,135.9 586.8,135.8 587.4,135.2 589.1,134.5 589.7,134.1 590.4,133.9 591.5,133.6 592.5,133.3 595.0,133.4 596.5,133.5 597.2,133.2 597.7,133.3 597.8,133.9 598.8,134.4 599.8,134.1 600.5,134.9 601.1,135.3 601.6,135.2 602.2,135.1 602.8,135.3 604.0,135.8 604.9,135.9 605.3,136.1 606.6,136.3 607.1,136.3 607.9,136.1 609.5,135.8 610.6,136.2 611.1,136.2 611.5,136.3 613.0,135.8 613.4,135.6 613.8,135.5 615.0,134.9 615.8,134.2 616.0,133.8 616.0,133.4 615.7,132.9 615.5,132.2 615.2,131.5 614.2,131.0 613.4,130.4 612.6,130.2 611.6,129.7 611.0,129.4 609.8,128.5 609.2,128.1 607.5,127.0 606.4,126.7 605.1,125.8 604.7,125.9 604.2,125.8 603.8,125.6 603.3,125.1 602.6,124.8 601.8,124.6 602.4,124.3 602.0,124.0 602.4,123.8 603.1,124.2 603.5,124.1 604.6,123.9 604.5,123.4 605.1,122.8 605.4,122.2 605.8,122.2 606.4,122.0 606.9,122.0 606.4,121.5 605.8,121.1 605.3,121.1 605.0,120.7 605.5,120.5 606.0,120.3 606.5,120.3 606.9,120.4 606.8,120.0 607.3,119.8 607.8,119.7 608.7,119.4 609.1,119.3 609.0,118.9 608.4,118.7 607.4,119.0 606.9,119.0 606.1,119.2 605.1,119.2 604.3,119.2 603.7,119.7 602.9,119.8 602.2,120.2 601.6,120.1 601.2,120.2 600.8,120.4 600.1,120.4 599.5,120.5 598.3,121.2 597.9,121.7 597.4,121.9 597.8,121.6 598.0,121.2 597.4,121.5 596.8,121.7 596.8,122.3 597.3,123.1 597.9,123.8 598.3,124.0 598.8,124.1 599.3,123.9 600.0,124.0 600.5,123.7 601.2,123.8 601.6,123.9 601.3,124.4 601.1,124.8 600.6,124.9 600.2,124.9 599.6,125.0 599.1,124.7 598.5,124.7 598.2,125.1 597.7,125.3 596.9,125.5 596.4,125.5 595.7,125.8 595.2,126.3 594.7,126.6 594.2,126.7 593.8,126.7 592.9,126.2 593.4,125.3 593.2,124.7 592.8,124.5 592.4,124.5 591.4,124.0 591.0,124.0 590.6,124.1 591.2,123.4 592.1,122.9 593.0,122.7 593.5,122.4 593.3,122.0 592.9,122.1 592.4,121.9 591.5,121.9 591.1,121.9 590.2,122.0 589.8,121.9 589.0,121.5 588.4,121.4 588.9,121.2 588.5,120.9 588.1,120.9 587.7,120.7 588.1,120.7 588.5,120.8 589.3,120.8 589.9,120.9 590.4,120.6 589.9,120.7 589.2,120.6 588.8,120.3 588.8,119.8 588.5,119.2 588.6,119.6 588.6,120.2 587.9,120.4 587.5,120.2 587.0,120.5 586.5,120.5 585.5,120.7 585.3,121.2 584.8,121.9 583.9,122.6 583.4,122.8 582.8,123.0 582.3,123.0 582.4,123.5 582.6,124.0 582.5,124.5 582.3,125.1 580.7,125.7 580.7,125.2 580.3,125.2 580.2,125.7 580.0,126.1 579.7,126.7 579.6,127.8 579.4,128.3 579.3,129.2 578.7,129.4 578.1,129.5 577.7,129.9 577.5,130.5 577.5,131.3 577.1,131.4 576.3,132.0 576.8,132.2 577.3,132.8 577.7,133.2 577.7,133.7 577.9,134.1 578.3,134.6 578.7,134.8 580.4,135.4 580.5,135.9 579.9,136.2 578.6,135.9 578.0,135.9 577.6,136.1 577.1,136.1 576.4,136.2 576.2,136.6 575.7,137.0 574.9,137.3 574.4,137.5 573.5,138.2 573.1,138.5 572.9,138.0 573.5,137.7 574.2,137.4 573.8,137.2 573.2,137.2 572.8,137.2 572.4,137.0 571.8,136.5 570.8,136.4 570.3,136.3 569.7,136.1 568.9,136.5 568.2,136.3 567.7,136.4 567.3,136.7 566.9,136.9 566.5,136.8 566.0,136.8 566.3,137.4 566.5,137.8 567.3,138.0 567.6,138.5 567.1,138.1 566.4,137.9 565.9,138.0 566.4,138.5 566.6,138.9 566.2,138.8 565.7,138.3 565.1,138.2 565.1,138.6 565.8,139.0 565.0,138.9 564.8,138.3 564.2,138.0 563.6,137.8 563.6,137.4 563.2,137.4 562.8,137.7 562.8,138.1 562.7,138.6 563.4,139.4 563.7,139.9 564.2,140.3 564.5,140.7 564.8,141.2 564.3,141.4 564.3,140.9 563.9,140.7 563.4,140.9 563.7,141.4 564.1,141.6 563.7,141.8 563.3,141.9 562.8,142.0 563.3,142.2 563.9,142.4 564.3,142.6 564.9,143.0 565.5,143.1 565.8,143.5 566.2,143.5 566.6,143.7 566.7,144.1 566.8,144.6 566.8,145.0 566.3,145.1 565.9,144.8 565.5,144.4 565.1,144.5 564.4,144.6 564.0,144.7 564.3,145.2 564.6,145.6 565.0,145.6 565.2,146.0 564.6,146.2 564.2,146.2 563.7,145.8 563.3,145.6 563.2,146.1 563.9,147.2 564.1,147.6 564.0,148.2 564.3,148.8 563.8,148.5 563.4,148.1 562.8,147.8 562.5,148.4 562.5,148.8 562.2,148.2 561.8,147.5 561.3,147.1 560.9,147.5 560.8,148.0 560.4,147.6 560.0,147.0 560.3,146.4 559.9,145.7 559.5,145.4 559.1,145.1 558.7,144.8 559.2,144.4 559.5,143.9 559.9,144.0 560.4,143.7 561.0,143.6 561.8,143.9 562.7,144.1 563.1,144.3 563.5,144.5 564.2,144.2 563.9,143.8 563.4,143.8 562.7,143.5 562.3,143.2 561.7,143.5 561.0,143.3 560.6,143.4 560.1,143.5 559.6,143.6 559.3,143.1 558.8,143.5 558.5,143.0 558.3,142.6 557.7,142.2 558.0,141.8 558.5,142.0 558.7,141.6 558.1,141.6 557.7,141.6 557.1,141.3 556.4,140.8 556.1,140.2 555.6,139.7 555.5,139.2 555.1,138.8 554.1,138.3 553.8,137.9 553.7,137.2 553.8,136.7 554.1,136.3 554.0,135.8 554.0,135.2 554.2,134.5 554.4,133.9 553.7,133.7 553.3,133.5 552.5,132.6 551.8,132.3 551.4,132.1 550.9,131.9 550.4,131.6 549.5,131.1 548.8,131.0 547.9,130.6 547.3,130.5 547.8,130.5 549.2,131.0 548.8,130.7 548.1,130.2 547.6,130.0 547.0,129.5 546.1,129.3 545.5,129.0 544.8,129.1 544.4,129.1 544.3,128.7 543.9,128.5 543.5,128.3 543.1,128.0 542.2,127.3 542.7,127.0 541.6,126.1 541.3,125.5 541.4,125.1 540.6,124.4 540.0,124.1 539.6,124.2 539.5,124.6 539.1,125.0 538.8,125.5 538.2,125.0 537.8,124.5 537.5,123.7 537.9,123.5 538.3,123.3 537.9,122.9 537.4,123.0 536.7,122.9 536.2,123.2 535.8,123.3 535.4,123.5 535.0,123.6 534.5,123.7 534.1,123.8 534.0,124.3 534.4,124.9 534.8,125.1 534.4,125.6 534.0,125.8 534.2,126.6 534.4,127.2 535.3,127.8 535.9,128.0 536.9,128.7 537.5,128.9 538.0,129.5 538.3,130.1 538.7,131.0 538.9,131.4 539.4,131.9 540.4,132.7 541.3,133.2 542.1,133.5 542.8,133.6 544.3,133.5 544.9,133.6 544.9,134.0 544.5,134.2 544.2,134.7 546.0,135.5 547.5,135.9 548.0,136.2 548.5,136.6 549.9,137.1 550.9,137.9 551.3,138.3 551.2,138.9 551.0,139.4 550.6,139.3 550.2,139.1 549.6,138.1 548.5,138.0 547.9,137.8 547.3,137.5 546.7,138.0 546.3,138.5 545.9,139.3 546.1,139.9 546.7,140.1 547.2,140.3 547.6,141.3 547.7,141.7 547.1,141.8 546.5,142.0 546.0,142.5 546.0,143.1 545.2,143.8 544.8,144.2 544.6,144.6 543.7,144.6 543.5,144.0 544.0,143.6 544.2,143.1 544.1,142.7 544.6,142.4 545.0,142.3 545.0,141.8 544.6,141.3 544.5,140.7 544.0,139.9 543.8,139.3 543.6,138.9 542.8,138.7 541.5,138.2 541.6,137.8 541.4,137.3 541.0,137.0 540.6,137.1 540.2,137.1 539.8,137.2 540.2,136.9 539.7,136.6 539.3,136.6 538.5,135.7 538.1,135.5 537.7,135.5 537.1,135.3 536.6,135.3 536.2,135.4 535.7,135.0 535.1,134.8 533.9,133.9 533.5,133.5 532.8,133.1 532.3,132.5 531.9,132.3 531.4,132.2 530.9,132.2 531.0,131.8 530.4,131.3 530.0,131.1 529.7,130.7 529.2,130.6 529.3,130.2 529.0,129.5 528.7,129.1 528.5,128.2 527.9,127.7 527.0,127.5 525.8,126.9 524.8,126.6 524.3,126.6 523.8,126.8 523.0,127.4 522.4,128.0 521.5,128.3 520.8,128.4 520.2,128.6 519.1,129.3 518.7,129.5 518.3,130.0 517.5,130.2 517.0,130.4 516.1,130.3 515.0,129.9 514.4,129.6 513.8,129.4 513.4,129.4 512.9,129.5 512.2,129.3 511.7,129.2 511.3,128.9 510.9,129.0 510.5,129.3 509.1,130.0 508.5,130.8 508.6,131.7 508.9,132.1 509.2,132.5 508.8,132.6 508.8,133.0 509.0,133.5 508.3,134.0 506.4,134.8 506.0,135.2 504.4,135.6 503.3,135.8 502.9,135.9 502.3,136.4 502.5,136.9 502.0,137.1 501.0,138.0 500.4,138.6 499.8,139.2 499.1,140.2 499.4,141.5 499.9,142.0 500.4,142.2 499.9,142.8 498.9,143.2 498.6,143.6 498.2,144.0 498.1,144.5 497.9,144.9 498.0,145.5 497.4,145.6 496.3,145.7 495.4,146.1 495.0,146.6 494.6,147.4 494.1,147.8 493.6,147.7 493.2,147.7 492.8,147.8 492.3,148.0 491.9,147.9 491.3,147.9 490.9,147.9 490.5,148.0 490.1,147.9 489.4,147.9 487.9,148.0 487.5,148.3 487.0,148.6 486.3,148.6 485.6,148.8 485.2,149.3 484.8,149.8 484.4,149.9 483.9,149.8 483.4,149.5 482.9,149.1 482.6,148.5 482.2,148.0 482.4,147.6 482.0,147.3 480.9,146.7 480.1,146.6 479.4,146.7 478.2,147.2 477.4,147.0 476.4,146.9 475.7,147.0 475.2,147.2 475.2,146.8 475.5,146.0 475.5,145.6 475.6,145.2 475.5,144.8 475.5,143.9 475.3,143.2 475.9,143.3 475.4,143.0 474.7,143.2 474.3,142.6 474.7,142.5 475.1,142.2 475.2,141.7 475.6,141.4 475.1,141.6 474.7,142.1 474.3,142.5 473.9,142.5 473.7,142.1 473.8,141.4 474.0,141.0 474.1,140.6 474.6,140.2 475.0,139.4 475.5,138.6 475.4,138.2 475.6,137.2 475.9,136.8 475.9,136.3 475.9,135.9 475.7,135.3 475.5,134.6 475.7,134.2 475.3,134.0 475.6,133.5 475.3,133.0 475.9,132.6 475.5,132.5 475.6,132.1 475.6,131.7 475.0,131.7 475.2,131.2 474.6,130.9 474.3,130.5 474.5,130.1 474.9,129.9 475.4,129.6 475.9,129.7 476.3,129.6 476.8,129.5 477.0,129.1 477.4,128.8 477.8,128.6 478.2,128.6 478.6,128.4 479.2,128.5 479.8,128.9 480.4,129.0 480.8,128.9 481.6,128.9 482.0,128.9 482.7,128.9 483.1,128.9 483.8,128.8 484.3,128.9 485.2,129.0 485.8,129.2 487.4,129.4 488.0,129.4 488.8,129.3 489.2,129.2 490.0,129.1 490.5,129.3 491.5,129.5 492.0,129.3 492.8,129.4 493.5,129.6 493.9,129.7 494.5,129.6 494.9,129.4 495.5,129.3 495.9,129.0 496.3,127.7 496.5,126.2 497.0,125.9 496.6,125.9 496.7,124.6 496.8,124.0 497.0,123.5 497.4,123.7 497.9,124.1 498.1,124.7 498.5,125.0 498.0,123.9 497.6,123.5 496.8,123.1 496.9,122.4 496.8,121.7 496.6,121.3 496.1,121.2 495.0,120.8 494.7,120.3 494.3,120.0 494.4,119.3 494.0,119.1 494.1,118.7 494.7,118.7 495.2,118.8 494.5,118.6 493.5,118.7 493.0,118.6 493.3,118.1 492.9,118.0 492.3,118.0 491.8,117.8 491.2,117.5 490.8,117.5 490.4,117.5 489.2,117.1 488.7,117.1 488.3,117.2 487.8,117.0 487.0,116.6 487.5,116.4 488.0,116.2 487.5,116.0 488.2,115.8 487.8,115.6 487.4,115.6 486.9,115.7 486.9,115.2 487.4,114.9 488.7,114.7 489.3,114.7 489.7,114.7 490.2,114.5 491.0,114.3 491.7,114.5 492.2,115.0 493.2,114.9 494.2,114.9 494.7,114.7 496.0,114.9 495.7,114.4 495.6,113.3 495.0,112.5 494.8,112.1 495.3,112.0 496.2,111.9 496.6,112.5 497.3,112.8 497.9,112.9 498.6,112.9 499.5,113.1 500.0,113.0 500.4,112.8 501.2,112.6 500.8,112.6 500.4,112.5 500.5,111.9 501.7,111.5 502.6,111.4 503.5,111.1 503.9,110.9 504.2,110.5 504.4,109.1 504.6,108.7 505.3,108.4 506.8,108.1 508.2,107.6 509.0,107.4 509.5,107.2 510.0,107.2 510.8,107.3 511.4,107.3 510.6,107.2 510.0,107.1 509.6,106.8 510.4,106.7 510.8,106.7 511.5,107.1 511.9,107.0 511.3,106.8 511.0,106.1 511.3,105.6 511.7,105.4 512.2,105.0 512.7,104.3 513.0,103.3 513.2,102.9 513.6,103.0 514.1,102.9 514.9,102.5 515.4,102.0 516.3,101.7 516.8,101.6 517.6,101.6 518.2,101.6 518.9,101.6 519.4,101.9 520.0,102.0 519.6,101.7 519.7,101.2 520.2,100.9 521.2,100.8 522.2,100.9 522.7,101.3 523.1,101.2 523.5,101.2 523.6,101.7 523.7,101.2 523.7,100.6 524.7,100.5 525.6,100.4 526.6,101.1 527.2,101.2 526.8,101.1 525.9,100.4 525.2,100.3 524.8,100.1 524.7,99.3 524.3,99.2 524.5,98.8 524.9,98.7 524.7,98.3 524.1,97.8 524.1,97.3 523.8,96.8 524.0,96.3 523.2,95.8 522.6,95.6 522.7,94.7 522.6,94.1 522.6,93.6 522.7,92.8 523.5,92.9 523.9,93.0 524.3,92.7 524.7,92.4 525.2,92.2 525.6,92.2 525.7,91.6 525.3,91.5 524.7,92.0 524.4,92.4 523.9,92.5 523.5,92.6 523.0,92.3 523.4,91.7 523.9,91.4 524.5,91.4 524.9,91.3 525.8,91.3 526.2,91.2 527.3,90.3 527.7,90.1 528.5,90.0 529.3,89.6 529.1,90.0 529.3,90.4 529.2,91.0 528.7,91.6 528.6,92.2 528.6,92.7 529.1,93.0 530.1,93.0 530.3,93.4 529.9,93.8 529.5,93.9 529.0,93.7 528.4,94.4 528.3,94.8 527.8,94.8 527.8,95.2 527.3,95.4 526.8,95.7 526.7,96.1 526.9,96.5 526.4,96.9 526.8,97.2 527.1,97.8 527.5,97.8 527.9,98.4 527.4,98.7 528.2,98.6 528.8,98.8 529.8,99.1 530.4,99.0 530.6,99.5 530.0,99.8 530.8,100.0 531.7,100.2 532.5,99.7 533.6,99.5 534.2,99.2 534.9,98.7 535.5,98.8 536.2,98.9 537.4,99.6 538.1,99.6 538.5,100.4 539.0,100.6 539.6,100.7 540.2,100.9 540.4,100.5 539.9,100.4 539.4,100.3 539.0,100.4 538.6,100.2 538.4,99.8 539.0,99.9 539.5,100.1 540.0,100.2 540.9,99.9 542.5,99.6 544.2,99.3 544.6,99.3 545.0,99.2 545.5,98.8 546.0,98.5 546.9,98.3 547.9,98.0 549.6,97.7 550.2,97.7 550.9,97.7 551.5,97.9 552.1,98.1 551.4,97.9 551.6,98.6 552.3,99.0 552.7,99.0 553.9,98.9 554.3,98.8 554.9,98.5 555.4,97.9 555.5,97.4 555.9,97.3 556.7,97.4 557.4,96.9 557.9,96.6 558.4,96.1 558.6,95.6 558.4,96.3 557.9,96.7 557.2,97.3 557.7,97.4 558.3,97.5 558.9,97.4 559.0,96.9 559.0,96.5 559.0,96.0 558.8,95.5 558.5,95.0 558.5,94.4 558.4,93.7 558.4,92.7 558.5,92.2 559.0,91.9 559.5,91.3 559.6,90.8 560.4,90.1 561.0,90.0 561.8,89.8 562.7,89.7 564.0,90.6 564.7,91.4 565.7,91.7 566.5,91.6 567.4,91.2 567.8,90.8 567.7,89.9 567.5,89.5 567.6,89.1 568.0,88.6 568.0,88.2 567.8,87.8 567.3,88.1 566.7,88.0 566.0,87.9 565.8,87.5 565.3,87.1 565.7,86.8 565.3,86.7 565.1,86.3 565.2,85.9 565.7,85.4 566.1,85.3 566.9,85.3 567.7,84.8 568.3,84.8 569.1,84.7 570.7,84.7 570.9,84.3 571.6,84.3 573.5,84.6 574.0,84.6 574.6,84.8 575.9,84.9 577.5,85.0 578.0,84.6 577.8,84.1 578.4,84.1 579.0,84.1 579.5,83.8 579.9,83.9 580.4,83.8 580.8,83.4 582.4,83.5 583.7,83.7 583.5,83.3 583.0,83.0 582.6,82.8 582.1,82.8 581.6,82.8 580.7,82.8 580.0,82.4 579.6,82.3 579.2,82.0 579.6,81.8 579.2,81.5 578.3,81.7 577.2,81.8 576.5,82.0 575.7,81.8 575.2,81.9 574.2,82.1 573.7,82.2 573.8,81.8 573.3,82.2 572.8,82.2 572.3,82.0 572.3,82.4 571.8,82.5 571.4,82.6 571.0,82.5 570.2,82.6 569.3,82.9 568.3,83.0 567.9,83.3 566.7,83.3 565.9,83.4 565.5,83.4 564.8,83.5 564.4,83.8 563.9,83.8 564.4,83.4 563.9,83.1 563.6,82.7 563.4,83.1 563.0,83.2 562.4,83.3 562.4,82.9 562.7,82.3 561.8,82.2 560.9,81.9 560.4,81.7 560.0,81.9 559.5,81.7 559.5,81.2 559.3,80.6 559.6,80.2 559.8,79.8 559.8,79.4 559.7,79.0 559.8,78.6 559.4,78.0 559.0,77.8 559.3,77.2 559.2,76.8 558.8,76.6 558.6,76.0 558.9,75.6 559.6,75.1 560.1,74.9 559.8,74.4 560.6,74.3 561.4,74.3 562.0,74.1 561.9,73.7 562.6,73.4 563.2,73.1 563.9,72.7 564.3,72.6 565.3,72.1 565.7,71.9 566.3,71.5 566.7,71.2 567.4,70.8 567.9,70.3 568.5,70.0 569.3,69.8 569.8,69.8 570.2,69.8 570.5,69.4 570.2,69.0 570.3,68.5 570.4,68.1 569.0,67.6 568.5,67.6 568.4,67.1 567.8,67.3 567.3,67.2 566.4,67.3 565.8,67.1 565.1,67.2 564.5,67.3 563.7,67.3 563.2,67.0 562.8,67.2 562.4,67.1 562.0,67.2 561.9,67.7 561.4,67.7 560.9,68.0 560.2,68.3 559.8,68.4 559.7,68.8 559.5,69.4 559.1,69.6 558.7,70.0 559.1,70.2 559.4,70.7 559.8,70.9 559.0,71.4 558.4,71.7 557.7,72.6 556.8,72.8 556.1,73.2 555.3,73.3 554.9,73.5 554.4,73.6 553.8,73.7 553.4,74.0 552.9,74.3 552.3,74.3 551.9,74.5 551.5,74.8 551.1,74.9 551.3,75.3 550.7,75.4 550.2,75.6 549.7,75.3 549.9,75.8 549.9,76.2 549.5,76.4 549.0,76.5 548.6,76.4 548.4,76.8 549.0,77.1 548.6,77.3 548.5,77.7 548.3,78.1 548.4,78.5 547.8,78.5 547.6,79.0 547.6,79.5 547.7,79.9 547.8,80.6 548.0,81.1 548.2,81.6 548.8,81.5 549.3,81.8 549.9,81.7 550.5,82.2 551.1,82.4 551.5,82.6 551.7,83.0 552.2,83.1 552.6,83.5 552.7,84.0 552.0,84.3 551.6,84.5 551.1,84.8 550.6,84.9 549.9,85.1 550.4,85.2 550.8,85.1 551.3,85.0 551.7,85.2 551.2,85.3 550.9,85.7 550.3,85.9 549.9,86.1 549.5,86.2 549.1,86.3 548.5,86.5 547.5,86.9 546.2,87.1 545.3,87.0 545.8,87.2 546.3,87.2 547.0,87.5 546.3,87.7 546.6,88.3 546.4,89.1 546.0,89.4 546.1,89.9 546.3,90.3 545.8,90.9 545.9,91.3 545.7,91.9 545.4,92.5 545.0,92.8 544.4,93.8 544.0,94.1 543.4,93.9 542.6,94.0 541.8,94.0 541.1,94.0 541.0,94.4 540.4,94.3 540.0,94.5 539.6,94.8 539.5,95.2 539.8,95.8 539.4,96.1 538.4,96.0 537.0,96.3 535.8,96.1 535.9,95.7 536.0,95.3 535.7,94.8 535.0,94.1 534.6,93.6 535.3,93.8 535.3,93.4 535.7,93.2 535.8,92.7 535.3,92.6 534.9,92.2 534.5,91.9 533.8,91.0 533.5,90.4 533.1,90.2 533.0,89.8 532.6,89.7 532.5,89.0 532.1,88.9 531.8,87.9 531.2,87.9 531.2,87.0 531.1,86.5 531.0,86.1 531.4,85.9 530.9,85.7 530.4,85.6 529.8,85.3 529.5,84.9 529.5,84.4 529.4,84.0 529.4,84.5 528.9,84.7 529.0,85.1 528.5,85.9 528.0,86.0 527.3,86.2 526.8,85.8 526.8,86.2 525.9,86.5 525.5,86.9 524.8,87.3 523.7,88.1 523.1,88.3 522.7,88.5 522.3,88.5 521.9,88.7 520.7,88.8 520.0,88.8 519.5,88.8 519.1,88.6 518.7,88.7 518.3,88.6 518.5,88.2 518.0,88.2 516.8,87.8 515.9,87.4 515.5,87.2 515.3,86.6 515.4,86.2 516.3,86.2 516.9,86.5 517.3,86.3 517.7,86.1 516.9,86.2 516.4,85.9 516.5,85.5 516.8,85.1 517.2,84.9 517.8,84.6 517.4,84.6 516.7,85.0 516.2,85.1 515.7,85.3 515.2,85.5 514.4,85.7 514.4,84.9 514.6,84.5 515.0,84.3 515.4,84.1 516.0,84.3 516.6,84.0 517.3,83.8 516.8,83.8 516.2,83.9 516.7,83.2 517.1,82.7 517.6,82.4 518.1,82.2 518.1,82.7 518.5,82.3 518.9,82.1 519.3,82.0 518.9,81.9 517.6,82.2 517.1,82.4 516.6,82.8 516.1,83.3 515.4,83.6 514.9,84.0 514.5,84.2 514.2,83.8 514.5,83.4 514.9,83.1 515.3,83.1 515.8,83.0 515.0,82.9 514.4,82.5 514.3,82.1 515.4,81.6 514.6,81.8 514.2,81.6 513.9,81.0 513.9,80.4 514.4,80.4 515.3,80.4 516.6,80.2 517.5,80.3 518.4,80.2 518.8,80.2 519.2,80.3 519.6,80.7 519.6,80.3 521.0,80.1 520.6,79.9 520.7,79.4 520.2,79.8 519.3,80.1 518.9,80.0 518.5,80.0 518.0,80.1 516.9,80.1 515.7,80.1 515.1,80.3 514.2,80.0 513.9,79.5 514.4,79.3 514.8,79.2 514.4,79.0 513.9,78.8 513.6,78.3 514.2,78.1 515.2,78.1 516.1,78.3 516.7,78.4 518.0,78.3 518.7,78.1 517.8,78.2 517.0,78.2 515.7,78.0 515.2,77.9 514.6,78.0 514.2,77.7 514.3,77.3 514.7,77.3 515.1,77.2 515.9,76.7 516.4,76.6 516.9,76.8 518.3,76.6 517.9,76.5 517.4,76.6 517.0,76.6 517.4,76.2 517.9,76.1 518.4,76.1 519.3,76.0 520.2,76.1 520.8,76.3 521.3,76.2 520.9,76.1 521.7,75.8 522.5,75.7 520.6,75.8 520.1,75.7 519.5,75.8 518.8,75.8 519.2,75.3 520.5,74.9 521.0,74.7 521.5,74.7 522.5,74.7 523.1,75.1 524.0,75.4 523.2,74.9 522.7,74.6 523.0,74.2 523.8,74.1 523.3,73.8 523.8,73.3 524.6,73.2 525.4,73.4 525.9,73.4 526.4,73.4 526.9,73.3 527.3,73.5 527.7,73.9 528.3,73.7 528.7,73.7 529.4,73.8 529.9,73.7 529.8,73.3 530.4,73.1 530.9,73.0 531.6,72.8 531.1,72.6 531.8,72.2 531.4,72.1 530.8,72.3 530.3,72.4 530.7,72.7 528.7,73.4 527.9,73.6 527.5,73.4 527.1,73.1 526.7,73.1 527.0,72.6 527.4,72.4 527.8,72.0 528.4,71.7 529.3,71.1 530.1,70.8 530.8,70.5 531.2,70.3 532.0,70.2 531.6,70.1 531.5,69.7 531.9,69.5 533.8,68.9 534.2,69.2 534.7,69.2 535.4,68.9 535.9,68.5 535.3,68.7 534.8,68.9 534.3,68.9 533.9,68.8 533.7,68.4 534.1,67.9 535.1,67.2 535.6,66.8 536.1,66.8 535.5,66.5 537.2,66.2 538.0,66.2 538.7,66.0 538.0,65.9 537.5,66.0 537.1,66.0 536.4,66.0 536.3,65.5 536.7,64.9 537.4,64.7 537.8,64.5 538.3,64.5 538.8,64.5 538.1,64.3 538.6,64.0 539.0,63.7 539.5,63.6 540.2,63.5 540.6,63.4 541.0,63.3 542.8,63.3 541.2,63.1 540.5,63.1 540.1,63.1 540.5,62.8 541.0,62.5 541.6,62.3 542.0,62.3 542.5,62.5 543.0,62.6 543.6,62.4 543.0,62.5 542.6,62.4 542.5,61.9 541.8,62.0 541.3,62.0 541.1,61.6 541.8,61.2 542.4,61.2 542.8,61.3 543.4,61.3 542.7,61.1 543.5,60.7 544.0,60.6 544.5,60.5 544.8,61.0 545.2,61.4 545.3,61.0 544.9,60.3 545.5,60.0 546.2,60.0 546.8,60.1 547.5,60.1 548.2,60.0 548.6,59.9 547.8,59.8 546.1,59.8 546.3,59.4 546.9,59.2 547.6,59.2 548.3,58.9 548.7,58.3 549.2,58.1 550.3,57.9 550.2,57.4 550.7,57.0 551.1,57.1 551.8,57.4 552.4,57.5 551.7,57.1 552.1,56.9 552.5,56.9 552.9,56.5 553.3,56.3 554.7,56.1 554.7,56.6 554.6,57.2 555.2,56.3 555.7,55.9 556.2,55.8 556.6,55.9 556.5,56.5 555.7,57.3 556.1,57.3 556.9,56.8 557.6,56.9 557.1,56.6 557.1,56.0 557.9,55.8 558.3,55.8 558.8,55.9 559.0,55.5 559.5,55.5 560.0,55.7 560.5,55.9 560.9,56.1 560.8,55.5 560.0,55.3 559.4,55.1 559.8,54.8 560.5,54.9 561.1,54.7 561.7,54.7 562.2,54.8 563.0,54.5 563.5,54.6 564.0,55.3 564.4,55.5 564.9,55.6 564.8,55.0 565.7,54.4 566.4,54.2 566.8,54.2 567.5,53.7 567.4,53.3 567.9,53.1 568.5,52.8 569.0,52.8 569.6,53.0 570.2,53.2 570.7,53.0 571.2,53.1 571.6,53.2 570.7,53.7 570.2,54.0 569.9,54.7 569.4,54.9 569.6,55.3 570.0,55.2 570.6,54.9 572.2,53.8 572.9,53.4 573.6,53.0 574.1,52.9 574.1,53.5 574.0,54.0 573.8,54.4 575.0,54.1 575.4,53.7 575.9,53.3 576.5,53.3 575.7,53.0 576.7,52.5 577.3,52.6 578.2,52.7 578.9,52.8 578.7,53.3 577.6,53.6 578.4,53.6 578.3,54.0 578.2,54.6 578.6,54.4 579.0,54.2 579.1,53.8 579.5,53.4 579.9,53.2 580.8,53.2 581.2,53.3 581.7,53.5 582.3,53.6 582.8,53.8 583.2,53.6 584.0,53.8 584.5,54.0 585.0,54.1 585.9,54.4 586.0,54.8 584.6,55.0 584.1,55.2 583.1,55.3 579.9,55.2 582.2,55.6 582.3,56.1 582.8,56.3 583.3,56.3 583.8,56.3 584.0,55.9 584.4,56.3 585.0,56.1 585.7,56.2 586.2,56.2 587.4,56.4 588.0,56.3 588.6,56.0 589.0,56.0 590.0,55.9 590.5,56.1 591.5,56.2 591.7,56.6 591.0,56.7 589.4,56.5 589.8,56.8 590.7,57.0 591.3,57.1 591.7,57.0 592.4,57.1 592.9,57.1 592.8,57.5 592.6,57.9 592.2,58.0 592.6,58.1 593.4,57.5 595.1,57.5 596.8,57.7 597.2,57.7 597.7,57.7 599.6,57.8 601.7,58.3 604.8,59.2 606.5,60.0 607.4,60.2 607.9,60.2 609.9,60.9 610.6,60.9 611.2,61.1 611.7,61.3 612.2,61.6 612.6,61.7 613.2,61.8 613.8,61.9 614.1,62.7 614.3,63.1 614.9,63.3 614.7,64.1 612.6,65.4 611.4,65.8 609.1,66.3 607.4,66.5 606.7,66.5 605.3,66.4 604.5,66.3 603.6,66.0 602.7,65.9 602.1,65.9 601.0,65.8 598.6,65.6 598.2,65.5 596.7,65.0 596.1,65.1 595.5,65.0 594.8,64.7 594.1,64.7 593.3,64.5 592.1,64.3 591.7,64.1 591.2,63.8 590.0,63.5 588.6,63.4 589.4,63.6 589.8,63.7 590.3,63.9 590.8,64.4 591.3,64.6 592.2,64.8 592.3,65.2 592.8,65.3 593.5,65.4 593.0,65.7 594.8,66.0 595.6,66.3 596.4,66.8 596.6,67.2 596.4,67.6 596.2,68.0 595.6,68.3 595.9,68.7 596.3,69.0 596.7,69.5 596.8,70.0 596.9,70.7 597.3,71.0 598.0,71.2 598.4,71.3 599.0,71.2 599.4,71.3 600.4,71.7 600.8,72.1 602.0,72.4 602.7,72.5 603.8,72.7 604.5,72.5 605.5,72.4 605.7,72.0 605.5,71.6 605.1,71.2 604.0,71.2 603.6,71.2 602.9,70.9 602.1,70.3 601.7,70.1 601.5,69.6 602.2,69.5 602.5,69.0 602.9,68.9 604.2,69.1 605.6,69.8 606.2,69.9 606.7,69.8 607.1,70.0 608.5,70.2 609.9,70.6 610.4,70.6 611.3,70.1 611.7,70.0 612.2,70.1 612.2,69.7 611.5,69.3 610.8,68.7 610.4,68.2 610.6,67.8 612.0,67.4 612.5,67.1 613.0,66.8 614.1,66.6 615.2,66.3 616.1,65.9 616.9,65.4 617.3,65.2 617.9,65.3 618.3,65.5 618.9,65.5 619.5,65.5 620.1,65.5 621.0,65.8 621.0,66.2 621.5,66.2 622.1,66.4 622.5,66.6 622.5,66.0 622.8,65.5 623.6,64.8 623.4,64.1 623.0,63.6 622.4,63.4 621.8,63.4 621.7,63.0 622.3,62.0 622.8,61.1 622.9,60.7 622.7,60.2 620.6,59.4 622.4,59.6 625.2,59.5 626.4,59.6 627.5,59.8 628.2,60.3 629.0,60.8 629.7,61.2 629.0,61.6 628.3,61.6 626.5,61.8 626.0,62.0 624.8,62.6 625.4,63.1 626.6,63.4 627.1,63.9 627.5,64.2 628.0,64.3 628.6,64.3 629.0,64.4 629.7,64.4 631.9,64.1 632.4,64.0 632.7,63.1 633.1,62.6 634.1,62.1 635.1,62.0 635.6,62.0 635.5,61.6 636.0,61.5 636.5,61.5 638.7,60.9 639.5,60.6 640.0,60.5 640.8,60.2 641.2,60.1 641.9,60.1 642.6,60.0 643.4,59.8 644.4,59.6 644.8,59.6 645.2,59.8 645.3,60.2 645.8,60.1 646.3,59.9 646.0,59.5 645.4,59.4 646.3,59.1 648.4,58.6 649.4,58.3 650.5,58.3 651.4,58.4 651.0,58.4 649.6,58.5 649.8,58.9 649.3,59.4 649.8,59.6 649.5,60.1 649.1,60.0 648.2,60.2 648.7,60.4 649.8,60.5 650.3,60.4 651.1,60.3 651.6,60.4 652.0,60.6 652.4,60.6 652.6,60.1 653.2,59.8 653.9,59.5 654.7,59.5 655.3,59.3 655.7,59.3 656.3,59.4 657.3,59.4 658.1,59.5 658.7,59.6 659.6,59.3 661.6,58.6 662.1,58.6 663.7,58.3 664.1,58.4 664.5,58.8 664.9,59.1 664.5,59.2 664.2,59.9 664.8,60.0 665.6,60.1 666.2,60.1 666.5,59.7 666.4,59.1 667.1,59.2 668.0,59.1 668.4,58.9 668.9,58.6 669.3,58.4 669.0,57.9 668.5,58.0 667.6,57.1 667.1,56.7 668.2,56.4 668.9,56.1 669.5,56.0 671.6,56.2 674.0,56.3 676.0,56.5 678.3,56.8 679.4,57.1 680.4,57.4 681.5,57.8 682.0,57.9 682.6,58.0 683.6,58.2 684.5,58.5 685.4,58.6 686.1,58.7 686.5,59.0 687.9,59.5 689.3,60.0 689.9,60.2 690.3,60.1 691.2,59.5 691.7,58.8 692.1,58.5 691.5,58.5 691.0,58.6 690.4,58.4 689.9,58.1 689.2,57.7 689.1,57.2 688.3,56.9 687.8,56.7 686.3,56.4 685.9,56.7 685.6,56.3 685.9,55.5 686.3,55.5 686.8,55.3 686.5,54.9 686.8,54.2 686.9,53.5 686.5,53.2 685.6,53.3 685.2,53.2 685.7,52.6 685.3,52.7 685.9,52.0 686.9,51.8 687.6,51.6 688.8,51.3 689.6,50.9 690.2,50.4 690.6,50.0 691.2,48.9 691.8,48.1 692.8,47.3 693.4,47.3 694.1,47.5 694.9,47.5 696.3,47.5 698.6,47.5 699.8,47.7 700.3,47.7 701.2,47.8 701.8,47.9 702.3,48.1 702.2,48.7 701.7,49.8 701.0,50.5 700.4,51.1 699.8,51.3 700.2,51.9 701.6,52.4 702.0,52.9 702.0,53.3 701.9,54.3 701.3,54.8 701.7,56.1 701.7,57.0 701.5,57.9 701.6,58.4 702.3,58.8 703.3,59.1 704.3,59.5 704.1,59.9 703.5,60.3 703.1,60.9 703.2,61.5 702.6,62.0 701.6,62.3 699.6,63.9 699.1,64.1 698.2,64.0 698.8,64.6 698.2,64.8 697.4,65.0 696.5,65.2 696.0,65.1 695.5,65.0 696.0,64.7 696.4,64.6 695.7,64.7 695.2,64.8 694.7,64.6 694.3,64.4 693.7,64.4 692.3,64.4 691.9,64.4 692.1,64.9 692.8,65.2 693.6,65.3 694.4,65.6 695.4,65.7 697.6,65.6 698.2,65.7 698.8,65.7 699.8,66.0 700.2,66.0 700.9,65.7 701.1,65.3 703.7,64.4 704.2,64.3 705.0,63.9 705.5,63.0 707.4,62.0 707.7,61.4 707.6,60.9 707.3,60.5 706.6,59.9 706.9,59.3 708.7,58.7 710.0,58.6 711.4,58.4 712.0,58.4 712.4,58.4 712.8,58.6 713.2,59.0 714.2,59.5 714.6,60.2 714.6,61.3 714.4,61.7 714.8,61.8 715.5,62.1 716.0,62.3 716.6,62.3 718.3,62.3 719.2,62.3 718.2,62.1 717.1,62.0 715.5,61.8 715.3,61.4 715.7,60.6 716.3,60.5 716.7,60.4 716.4,59.8 716.1,59.4 715.7,58.6 715.2,58.6 714.8,58.4 712.9,58.0 711.1,57.7 709.9,57.6 709.5,57.7 708.5,58.0 707.8,58.1 706.6,57.9 705.5,58.0 705.1,57.9 705.3,57.2 704.6,56.6 704.4,56.1 705.1,55.1 706.1,54.3 706.5,54.0 704.8,52.6 704.4,52.2 703.8,51.9 703.2,51.7 704.6,50.4 705.4,50.2 706.4,50.1 706.9,50.0 707.8,49.8 708.3,49.6 708.6,48.8 708.4,48.3 708.0,48.0 708.2,47.6 708.8,47.6 709.4,47.8 710.0,48.4 710.1,48.9 710.3,49.3 709.9,49.5 709.4,50.0 709.0,50.5 709.7,51.0 709.5,51.4 709.1,51.6 710.4,52.0 711.4,52.2 713.2,52.2 713.7,52.4 715.5,52.3 716.9,52.8 717.6,53.0 718.1,53.0 719.3,53.0 719.7,52.8 719.1,52.8 718.7,52.8 718.3,52.8 717.7,52.5 717.3,52.0 716.4,51.9 715.8,51.9 715.2,51.9 714.2,51.6 713.5,51.5 712.3,51.2 711.7,50.9 711.4,50.5 712.3,50.0 713.5,49.9 714.1,50.0 715.4,50.4 716.0,50.5 717.2,50.3 717.1,49.9 716.7,49.7 716.1,49.7 715.3,49.8 715.6,49.4 716.6,49.1 717.3,48.9 718.0,48.9 720.6,48.9 722.1,49.4 723.5,49.6 724.2,49.7 724.6,50.1 726.4,50.7 726.8,50.8 728.0,50.8 729.3,50.7 729.9,50.7 730.5,50.7 731.2,50.9 730.8,51.2 730.3,51.6 729.1,52.0 728.7,52.1 728.5,52.5 728.7,53.1 728.5,53.6 728.3,54.1 728.4,54.5 728.5,54.0 729.0,53.6 729.4,53.1 729.8,52.9 730.2,52.9 730.6,53.1 730.7,53.6 730.3,54.4 729.8,54.8 730.2,55.3 730.8,55.3 730.8,54.9 731.4,54.7 731.9,54.6 732.4,54.4 732.6,54.0 732.2,53.4 731.5,52.8 731.0,52.5 731.3,52.0 731.8,51.5 732.1,51.1 731.5,50.5 731.1,50.3 729.9,50.3 728.7,49.8 728.4,49.4 728.0,49.3 727.2,49.1 726.6,49.0 725.8,49.0 725.3,48.9 724.5,48.6 724.2,48.2 724.4,47.6 724.3,47.2 723.6,47.0 723.4,46.6 723.3,46.2 723.9,45.9 726.3,45.4 727.3,45.4 732.1,45.4 734.5,45.2 735.4,45.1 736.3,45.2 736.7,45.2 737.4,45.2 737.8,44.9 738.8,44.8 740.5,44.7 741.4,44.8 740.8,45.2 739.9,45.5 739.1,45.6 738.4,45.9 738.4,46.3 739.2,46.5 739.7,46.7 740.3,46.8 740.8,46.9 739.2,46.4 738.8,46.3 738.9,45.9 739.3,45.7 739.9,45.6 742.0,45.5 742.5,45.3 743.3,45.0 742.6,44.9 742.2,44.8 740.8,43.9 739.4,43.7 738.9,43.6 739.4,43.3 740.0,43.2 740.4,43.2 741.4,43.5 742.3,43.4 741.4,43.2 740.8,43.0 740.1,42.8 739.2,42.7 738.3,42.7 739.1,42.4 740.7,42.6 741.3,42.4 741.8,42.3 742.8,41.8 742.1,41.5 741.5,41.5 742.1,41.1 743.5,41.3 745.8,40.9 746.5,40.6 748.1,40.4 748.9,40.4 750.5,40.0 752.8,39.9 754.1,39.9 755.1,39.7 756.7,39.6 757.2,39.5 759.9,39.3 761.3,39.1 760.2,39.1 759.7,39.1 758.8,39.0 758.3,39.2 757.9,38.9 758.3,38.7 759.1,38.6 760.1,38.7 760.7,38.6 761.4,38.5 762.2,38.6 762.7,38.5 764.0,38.6 764.9,38.5 765.5,38.5 766.4,38.6 766.9,38.7 765.7,39.2 766.5,39.1 768.1,38.9 769.1,39.1 770.0,38.8 770.4,38.8 770.8,38.9 771.2,38.8 772.0,38.6 772.8,38.4 773.2,38.4 774.1,38.2 775.0,38.3 775.5,38.4 776.6,38.6 777.1,38.8 776.7,39.3 776.2,39.4 776.7,39.4 777.4,39.1 777.3,38.5 776.7,38.2 776.3,38.1 775.3,37.8 774.9,37.7 774.5,37.6 776.6,37.6 777.6,37.5 778.7,37.6 780.1,37.4 780.7,37.6 781.4,37.6 782.2,37.7 781.1,37.4 780.6,37.4 780.8,36.9 780.3,36.6 780.5,36.1 781.1,36.0 782.0,35.6 785.0,34.7 786.5,34.4 787.0,34.3 787.7,34.4 788.9,34.1 789.4,34.1 791.1,34.3 791.6,34.5 792.5,34.6 793.6,34.7 794.1,34.8 794.6,35.0 793.7,35.1 792.7,35.5 791.4,35.6 789.8,35.7 789.4,35.8 792.6,35.9 793.5,35.8 793.9,36.1 794.8,36.0 795.4,36.0 796.4,36.1 797.1,36.0 798.0,36.1 798.4,36.3 797.7,36.6 797.1,36.9 796.2,37.3 795.5,37.3 796.3,37.5 796.7,37.6 797.7,37.4 799.0,37.5 799.7,37.3 800.1,36.9 800.5,36.8 801.0,36.9 801.8,36.9 803.8,36.8 805.5,36.9 806.9,36.8 808.6,36.9 809.4,37.0 810.0,37.2 810.5,37.2 810.9,37.4 811.4,37.6 810.9,37.8 811.5,37.7 811.9,37.7 812.3,37.8 812.8,37.8 813.0,38.3 813.1,38.7 814.0,38.6 813.9,38.2 814.6,38.2 815.1,38.6 815.5,39.2 816.3,39.1 816.0,39.7 815.6,40.0 815.2,40.1 814.2,39.7 812.9,39.3 812.5,39.3 813.1,39.6 813.8,40.1 814.3,39.9 814.9,40.2 815.4,40.3 815.9,40.4 815.6,40.9 813.7,41.6 811.6,42.1 810.7,42.4 809.2,42.6 808.0,42.9 806.6,43.1 806.2,43.4 805.1,43.5 805.2,43.9 804.2,44.2 803.0,44.4 800.6,45.3 799.3,45.5 798.0,45.5 796.6,46.2 795.0,46.4 793.5,47.3 792.8,47.7 792.1,47.8 792.8,47.8 793.6,47.7 794.6,47.4 795.0,47.1 795.8,46.8 797.5,46.7 798.2,46.8 799.3,46.7 800.0,46.6 800.4,46.5 800.8,46.5 801.6,46.3 803.0,46.2 803.7,45.9 804.5,46.0 805.2,45.9 806.7,45.5 807.7,45.3 806.6,45.2 805.8,45.3 804.9,45.2 804.9,44.8 805.8,44.5 806.3,44.4 808.1,44.6 808.5,44.6 809.3,44.3 809.9,44.4 809.0,44.5 809.4,44.9 810.6,45.1 811.5,45.3 812.2,45.2 813.3,45.1 813.7,44.9 814.4,44.9 814.8,45.3 814.3,45.9 814.7,46.1 815.2,46.3 815.2,46.8 814.9,47.4 814.2,47.7 814.8,48.2 815.7,48.2 815.0,48.0 814.5,47.8 815.0,47.4 815.4,47.1 815.4,46.6 816.0,46.3 815.4,46.0 816.3,45.7 816.8,45.6 818.9,45.5 820.4,45.3 823.6,45.3 825.9,45.6 829.0,45.6 830.2,45.7 829.0,45.9 829.0,46.5 830.4,46.9 831.7,47.0 832.6,47.3 833.1,47.3 835.0,47.3 836.1,47.4 837.1,47.3 838.2,47.3 838.6,47.3 839.0,47.5 839.6,47.6 840.4,47.6 840.8,47.5 840.3,47.3 841.7,47.3 842.1,47.3 842.5,47.2 842.9,46.8 843.3,46.7 842.7,46.3 842.5,45.7 843.0,45.4 843.9,45.5 844.3,45.3 845.5,45.1 845.9,45.1 846.7,45.2 848.9,45.8 849.4,45.9 850.3,45.8 850.7,45.7 850.7,46.1 851.5,46.3 852.3,46.0 852.9,45.7 854.8,45.9 855.4,46.0 855.9,46.2 856.3,46.3 857.2,46.5 857.6,46.6 858.0,46.7 858.5,46.9 857.9,47.3 857.2,47.5 858.4,47.6 859.0,47.8 857.8,48.4 857.3,48.5 856.7,48.5 857.1,48.6 858.7,48.7 859.1,48.8 859.5,49.1 859.5,49.5 859.1,49.7 858.1,49.8 856.9,49.3 856.1,49.1 855.0,48.8 855.6,49.3 856.5,49.8 858.1,50.7 858.7,50.5 858.7,50.1 859.1,50.4 859.6,50.7 859.0,50.7 858.2,50.8 858.7,51.1 859.4,51.7 860.4,52.4 861.2,52.6 861.9,52.9 862.6,53.1 863.0,53.1 863.4,53.0 863.9,53.5 864.3,53.5 865.1,53.3 866.0,52.5 866.4,52.2 866.8,52.1 866.9,51.4 867.3,51.0 868.2,50.3 868.7,50.4 869.8,51.1 870.6,51.4 871.4,51.6 872.5,51.7 874.2,51.7 875.1,51.3 876.0,51.3 876.5,51.1 877.5,51.0 878.0,51.1 878.9,51.2 880.9,51.6 881.4,51.8 882.4,52.2 882.8,52.3 883.2,52.4 883.4,52.0 884.2,51.9 883.6,51.8 883.1,51.7 883.5,51.3 884.0,51.1 884.8,51.2 885.2,51.0 886.1,51.2 886.7,51.5 887.9,51.4 888.8,51.4 888.0,50.8 888.1,50.3 887.6,50.2 887.1,50.1 887.9,50.0 888.5,49.6 888.9,49.5 889.4,49.5 887.8,49.4 887.3,49.5 886.6,49.5 886.5,49.1 887.4,48.7 887.8,48.6 890.1,48.6 890.8,48.6 891.9,48.4 891.6,48.0 890.7,47.7 891.1,47.5 892.5,47.6 893.1,47.8 894.6,48.0 898.7,48.1 899.1,48.1 900.8,48.2 901.6,48.3 903.3,48.4 904.1,48.5 904.8,48.6 905.8,48.7 906.3,48.8 904.1,49.0 903.4,48.9 902.5,48.9 901.6,49.2 901.0,49.3 900.5,49.3 901.3,49.5 902.9,49.3 907.2,49.2 907.9,49.2 907.2,49.7 906.7,49.9 905.9,50.2 906.2,49.6 905.7,49.6 905.0,49.4 904.6,49.8 904.9,50.2 903.9,50.3 903.5,50.3 903.1,50.2 902.8,50.6 903.3,50.8 905.0,50.7 905.8,50.5 906.6,50.2 908.0,49.5 908.7,49.2 909.1,49.1 909.5,49.1 912.2,49.1 913.8,49.3 915.3,49.5 916.0,49.7 916.6,50.0 916.3,50.4 914.7,50.5 914.0,50.6 914.5,50.9 915.3,50.9 916.3,51.1 916.7,51.3 918.3,51.3 918.1,51.7 917.7,51.8 916.9,52.1 917.3,52.0 918.9,51.8 919.4,51.7 919.8,51.7 921.1,52.0 921.6,52.2 922.5,52.7 921.6,52.8 923.6,53.2 924.4,53.2 926.3,53.1 927.2,53.1 928.9,52.9 930.6,52.7 932.2,52.7 933.0,52.5 935.2,52.5 937.3,52.6 939.0,52.7 940.8,53.0 942.6,53.4 943.7,53.8 944.2,54.1 944.5,54.7 944.1,55.1 944.0,55.6 944.0,56.2 944.8,56.3 946.5,56.5 947.0,56.7 947.0,57.1 947.3,58.1 948.1,58.4 947.6,59.3 947.2,59.4 946.8,59.6 947.5,59.5 947.9,59.3 948.2,58.8 948.6,58.7 948.8,58.2 948.6,57.8 948.7,57.3 949.8,56.8 950.5,56.6 951.0,56.5 952.6,56.4 953.3,56.3 954.2,56.4 954.7,56.4 955.4,56.3 956.0,56.3 957.0,56.6 960.4,56.7 961.1,56.8 963.4,56.9 964.1,56.8 965.6,56.3 966.3,56.3 966.8,56.6 967.3,57.1 967.5,57.6 968.3,57.7 969.3,57.9 970.3,58.1 970.6,58.6 971.1,58.9 972.4,58.9 973.7,58.8 975.0,58.2 974.7,57.6 974.2,57.3 973.8,56.7 972.7,56.6 973.2,56.2 973.6,56.0 973.6,55.3 974.6,55.3 975.7,55.3 977.7,55.6 979.3,55.6 980.2,55.8 980.7,55.9 981.3,56.0 981.8,55.7 982.6,55.9 983.2,55.9 984.2,55.9 985.5,56.0 986.9,55.9 988.2,55.8 988.7,55.8 989.2,55.9 990.0,56.2 991.5,56.5 992.8,56.6 994.3,57.0 995.7,57.1 996.8,57.3 998.0,57.6 999.6,58.3 0.0,58.4ZM640.8,119.6 640.4,119.8 639.7,120.0 639.2,120.3 638.6,120.6 638.2,120.6 637.7,120.7 637.3,120.7 637.1,121.1 636.7,121.2 636.4,121.6 635.6,121.9 635.2,122.0 635.4,122.4 635.0,122.4 634.0,122.8 633.5,123.0 632.9,123.2 632.5,123.1 632.1,123.2 632.0,123.6 631.6,124.2 631.4,124.6 631.0,125.1 630.8,125.5 630.4,125.6 629.9,126.0 629.7,126.4 630.3,126.7 630.9,127.1 631.4,127.5 631.7,128.4 631.8,129.0 632.1,128.2 632.1,128.7 632.0,129.1 631.9,129.5 632.0,129.9 631.8,130.5 632.3,130.8 632.6,131.4 633.6,132.4 634.0,132.8 634.4,133.5 634.9,133.8 635.6,134.4 636.3,135.1 636.5,135.5 636.7,136.0 637.4,136.7 638.1,137.2 638.5,137.3 638.9,137.3 639.4,137.5 639.9,138.1 639.3,138.0 638.7,138.0 638.3,138.1 637.6,138.3 637.3,139.3 637.0,140.0 637.1,140.6 636.7,141.5 636.1,141.3 635.9,141.8 635.7,142.2 635.7,143.2 635.8,144.0 635.9,144.4 636.2,145.1 636.6,145.6 637.1,145.8 638.1,145.9 638.8,146.0 639.2,146.1 639.8,146.8 640.4,147.2 641.5,147.7 642.0,147.9 643.8,148.3 645.0,148.3 648.3,147.6 649.4,147.4 649.8,147.4 649.1,147.6 649.7,147.7 650.1,147.5 649.9,146.7 649.8,146.3 649.6,145.4 649.5,144.6 649.6,143.6 649.6,143.0 649.6,142.4 649.7,142.0 649.5,141.6 649.2,141.2 648.7,140.9 648.2,140.7 647.8,140.8 647.9,140.0 648.3,140.2 648.9,140.1 648.5,139.8 648.5,139.4 648.3,139.0 647.6,138.9 647.2,138.9 647.3,139.5 646.9,139.1 646.7,138.7 646.5,138.3 646.5,137.8 646.6,137.4 646.8,137.0 646.9,136.5 647.1,136.0 647.4,136.4 647.8,136.6 648.4,136.7 648.9,136.6 649.3,137.0 650.2,136.9 650.8,137.0 650.9,136.6 651.5,136.6 651.9,136.5 652.0,136.1 651.6,135.6 650.8,135.1 650.3,134.7 650.1,134.3 649.9,133.7 649.6,133.1 649.0,133.0 648.6,133.0 648.0,133.1 647.5,133.1 647.1,133.4 646.7,134.1 646.7,135.2 646.8,135.6 646.5,135.1 646.1,134.6 645.8,133.9 645.7,133.2 645.9,132.7 646.2,132.1 646.1,131.2 645.6,131.0 645.2,131.1 644.7,130.9 644.3,131.0 643.9,130.7 643.6,130.3 643.1,130.1 642.6,130.1 642.5,129.6 642.5,129.2 642.1,128.8 641.5,127.9 641.2,127.2 640.8,127.0 640.2,127.0 639.8,126.9 639.6,126.5 640.0,126.0 640.7,126.0 641.3,126.0 641.8,126.3 642.2,126.4 642.7,126.3 643.2,126.3 642.7,126.1 642.3,125.8 641.8,125.5 641.8,125.1 642.4,124.7 642.6,124.2 643.2,124.0 643.7,123.9 644.6,123.9 645.6,123.9 646.6,124.0 647.0,124.1 647.4,124.1 647.8,124.1 646.8,123.6 646.9,122.8 647.3,122.3 647.6,121.7 647.5,121.1 647.4,120.7 647.6,120.2 647.4,119.8 647.0,119.6 646.3,119.6 645.8,119.5 645.4,119.7 645.0,119.9 644.5,119.7 643.7,119.6 642.5,119.2 641.4,119.3 640.9,119.6ZM456.8,66.0 457.7,65.9 458.4,65.7 459.2,65.6 458.6,65.9 458.0,66.3 458.6,66.6 459.1,66.5 459.2,67.0 458.8,67.3 459.9,67.3 459.8,67.8 460.3,67.7 461.3,67.7 461.7,68.0 462.2,68.0 461.9,68.4 462.1,68.8 462.3,69.2 461.7,69.4 461.5,69.8 461.0,70.2 460.3,70.2 459.8,70.5 459.8,70.9 459.4,71.1 458.9,71.2 458.5,71.3 457.6,71.4 457.0,71.5 456.0,71.7 455.4,71.9 454.9,72.1 454.3,72.5 453.8,72.6 453.0,72.7 452.5,72.8 451.0,72.9 450.5,73.0 450.3,73.4 449.8,73.6 449.4,73.5 448.2,73.9 446.5,73.8 445.9,73.7 445.1,73.5 444.6,73.5 443.9,73.5 443.3,73.2 443.3,72.8 442.8,73.0 442.4,72.9 442.0,72.8 441.6,72.7 441.2,72.5 440.6,72.6 437.9,72.7 437.2,72.7 436.9,72.3 437.3,72.2 438.4,72.1 438.9,71.9 439.3,71.7 439.7,71.3 440.4,71.2 439.9,71.1 439.0,71.3 439.0,70.8 439.7,70.6 439.1,70.7 438.6,70.7 438.2,70.6 438.2,70.2 437.6,70.0 436.9,70.0 435.1,69.9 434.2,70.1 433.8,70.2 433.4,70.0 434.2,69.7 434.8,69.6 435.2,69.6 435.6,69.4 436.4,69.4 437.0,69.4 437.5,69.3 438.0,69.3 439.2,69.3 439.5,68.9 438.8,69.1 437.8,69.0 438.0,68.6 438.5,68.5 439.1,68.3 438.0,68.1 437.1,67.9 436.6,67.9 435.8,68.0 434.4,68.1 433.9,68.3 433.3,68.2 432.7,68.1 432.1,68.1 432.6,67.7 433.4,67.9 433.3,67.5 434.4,67.6 434.8,67.5 435.3,67.4 434.5,67.3 434.0,67.2 434.7,67.0 434.3,66.8 434.8,66.6 434.4,66.4 434.9,66.2 435.3,66.2 435.9,66.4 436.3,66.7 436.9,66.6 437.2,67.0 437.7,66.9 437.7,66.5 436.6,66.2 437.5,66.0 437.1,65.8 436.6,65.8 436.2,65.8 435.8,65.7 436.3,65.5 436.9,65.5 437.3,65.4 437.7,65.5 438.4,65.8 439.0,66.0 439.9,66.4 440.5,66.6 440.8,67.0 440.6,67.4 439.8,67.4 440.4,67.7 440.6,68.1 441.0,68.3 441.3,68.7 441.5,68.3 441.8,67.9 442.2,67.7 442.6,67.6 443.1,67.9 443.5,67.4 443.4,66.8 443.6,66.4 444.2,66.4 444.8,66.9 445.4,67.2 445.9,67.3 446.0,66.7 446.7,66.4 447.2,66.2 447.7,66.2 448.3,66.5 448.7,66.8 449.2,67.0 449.5,67.4 449.6,66.9 449.1,66.4 449.5,66.2 450.3,66.3 451.0,66.7 451.5,66.7 452.4,66.1 452.9,66.2 453.5,66.3 454.2,66.1 454.1,65.4 454.9,65.2 455.5,65.2 456.0,65.5 456.4,65.7 456.8,66.0ZM319.9,199.1 319.4,199.0 319.8,199.0ZM539.8,149.9 539.4,149.9 539.8,149.9ZM517.6,101.4 517.2,101.5 517.6,101.4ZM516.5,101.5 515.9,101.5 516.3,101.5ZM514.2,101.9 513.7,102.1 514.2,101.9ZM514.8,101.7 514.4,101.7 515.0,101.6 515.4,101.5 514.8,101.7ZM165.8,155.5 165.4,155.5 165.8,155.5ZM312.3,405.3 311.9,405.2 311.5,405.2 312.4,405.1ZM305.8,215.4 305.4,215.1 305.8,215.3ZM318.9,219.7 318.4,219.7 318.8,219.6ZM297.8,197.8 297.0,197.8 296.4,197.5 296.8,197.3 297.4,197.6 297.8,197.8ZM430.5,147.3 430.1,147.4 430.5,147.3ZM546.3,130.6 546.8,130.6 547.5,130.7 546.8,130.8 546.4,130.7ZM547.8,130.2 546.3,130.2 545.6,130.0 546.3,130.0 547.4,130.2 547.8,130.2ZM546.6,129.8 546.2,129.8 545.8,129.8 546.8,129.6ZM493.0,112.5 492.6,112.6 493.0,112.5ZM494.4,113.2 494.0,113.4 494.4,113.2ZM333.9,127.9 333.2,128.0 333.7,127.9 334.1,127.8ZM230.5,172.5 230.8,171.9 230.5,172.5ZM254.0,166.1 253.6,166.0 254.0,166.0ZM255.4,166.0 254.8,166.0 255.3,165.9ZM318.3,199.7 317.9,199.7 318.4,199.6ZM320.4,199.0 320.0,199.1 320.4,199.0ZM320.1,200.6 320.6,200.7 319.8,200.8ZM285.1,180.5 284.6,180.5 285.2,180.3ZM287.4,153.7 287.0,153.6 287.4,153.7ZM13.4,308.8 13.0,308.8 13.5,308.7ZM646.3,123.9 646.1,123.5 646.3,123.9ZM633.3,123.6 633.2,123.2 633.3,123.6ZM618.3,207.5 618.3,207.1 618.3,207.5ZM602.5,179.5 602.1,179.2 601.5,178.9 602.1,179.0 602.6,179.4ZM601.7,178.6 601.6,178.2 601.7,178.6ZM843.3,254.7 843.0,254.3 843.4,254.5ZM924.7,38.6 923.8,38.5 924.4,38.3ZM687.1,56.9 686.4,57.1 686.7,56.7 687.1,56.9ZM684.9,54.1 684.5,53.8 684.6,53.4 684.9,54.1ZM654.1,27.0 653.3,27.1 652.9,27.1 653.4,26.9 653.8,26.9ZM793.7,279.1 793.3,279.2 793.7,279.1ZM259.9,204.5 259.5,204.7 259.9,204.4 260.4,204.4 259.9,204.5ZM955.5,457.9 955.1,457.9 954.8,457.5 955.6,457.3 956.1,457.2 955.8,457.6ZM957.9,437.6 957.5,437.7 957.5,437.2 957.5,436.8 957.9,437.1 957.9,437.5ZM965.7,467.1 965.3,467.3 964.9,467.4 963.7,467.3 962.8,467.5 961.9,467.5 961.4,467.4 961.1,467.0 962.7,467.1 963.2,467.2 964.3,467.0 964.9,466.8 965.3,466.6 965.7,467.0ZM953.6,435.6 953.2,435.6 953.2,435.2 953.6,435.6ZM964.1,464.8 965.2,465.0 967.9,465.0 970.2,465.2 969.8,465.4 968.8,465.7 968.1,465.8 967.6,465.8 966.4,465.7 965.0,465.7 964.0,466.0 963.1,466.3 962.6,465.8 961.8,465.4 962.2,465.3 962.8,464.9 962.4,464.7 963.1,464.3 963.8,464.4 964.2,464.6ZM971.8,454.5 971.4,454.5 970.9,454.3 971.3,453.9 971.9,454.1 971.8,454.5ZM952.7,459.9 952.2,460.3 951.8,460.3 952.3,459.9 952.7,459.9ZM780.5,432.4 779.3,432.5 778.7,432.4 778.7,432.0 779.3,431.7 780.2,431.6 780.8,431.7 781.2,431.9 780.5,432.4ZM738.4,436.0 737.9,436.1 737.1,435.7 737.1,435.3 737.6,435.4 738.3,435.5 738.7,435.8ZM757.2,432.8 756.5,432.8 756.9,432.5 757.3,432.6ZM740.4,435.5 739.8,435.5 740.3,435.2 740.7,435.3ZM737.0,435.0 736.5,435.1 737.0,435.0ZM694.2,449.8 693.7,450.1 693.9,449.7ZM690.2,450.8 690.7,450.3 691.2,450.5 690.7,450.8 690.2,450.8ZM768.4,433.4 769.3,433.5 769.3,433.9 767.8,434.0 767.8,433.6 768.4,433.4ZM700.0,446.2 699.6,446.2 699.2,446.0 699.0,445.4 699.4,445.2 699.7,445.6 700.2,445.8 700.0,446.2ZM951.7,434.7 950.8,434.4 950.9,434.0 951.6,434.5ZM778.5,433.9 778.1,434.0 778.6,433.6ZM774.6,434.6 774.0,434.6 774.9,434.5ZM787.2,431.8 786.6,431.8 786.4,431.4 785.5,431.2 786.5,431.1 786.6,431.5 787.2,431.7ZM90.0,462.9 89.6,462.9 89.0,462.8 89.5,462.7 89.9,462.8ZM96.6,460.3 95.7,460.3 94.3,460.0 94.7,459.7 95.1,459.8 96.1,460.0 96.5,460.2ZM92.8,463.8 91.7,463.9 91.3,463.5 92.0,463.4 93.8,463.6 92.8,463.8ZM132.3,456.8 131.8,456.9 131.0,456.8 131.8,456.6 132.3,456.7ZM87.2,463.9 86.1,463.9 85.4,463.9 86.9,463.7 87.5,463.8ZM85.2,463.1 86.3,463.1 87.1,463.1 87.8,463.2 87.0,463.3 86.6,463.4 86.2,463.5 85.5,463.4 84.8,463.2 85.2,463.1ZM135.9,457.2 134.5,457.1 133.8,457.1 133.3,456.9 132.9,456.7 133.5,456.5 134.0,456.5 134.5,456.5 135.5,456.7 136.2,456.7 135.9,457.2ZM167.9,455.9 167.3,455.9 166.9,455.8 167.5,455.6 167.2,455.0 167.6,455.0 168.0,454.9 168.8,454.9 169.6,455.0 169.5,455.5 168.2,455.8ZM175.7,456.0 174.4,456.1 174.0,456.0 176.7,455.2 177.2,455.3 176.5,455.6 176.1,455.7 175.7,456.0ZM146.2,457.3 145.8,457.3 145.0,457.2 144.4,456.9 144.1,456.5 144.9,456.5 145.9,456.7 146.6,456.7 146.6,457.2 146.2,457.3ZM85.5,464.8 86.3,465.0 84.9,464.9 84.3,464.7 84.7,464.7 85.1,464.7 85.5,464.8ZM50.0,480.9 49.2,480.9 47.1,480.8 46.6,480.7 45.7,480.5 45.3,480.2 47.8,480.2 48.9,480.3 51.0,480.6 50.5,480.7 50.0,480.9ZM82.7,463.3 81.5,463.3 81.0,463.2 82.8,463.0ZM92.5,461.8 92.0,461.8 91.3,461.7 90.7,461.5 91.8,461.4 92.5,461.8ZM92.3,462.9 91.9,463.1 91.1,463.0 90.7,462.8 91.3,462.6 91.8,462.7 92.3,462.9ZM54.3,476.6 46.5,476.3 45.1,476.2 44.6,476.1 46.7,475.8 48.7,475.9 51.2,476.1 53.0,476.3 53.8,476.5 54.2,476.6ZM82.2,464.9 79.6,464.7 79.1,464.7 80.0,464.5 80.5,464.5 82.0,464.8ZM72.4,472.3 71.9,472.3 71.3,472.3 70.8,472.2 69.6,472.1 68.0,471.8 67.4,471.7 69.0,471.8 70.7,472.0 72.2,472.2ZM85.5,464.2 84.1,464.2 83.7,464.2 82.1,464.1 81.3,463.9 82.0,463.7 83.7,463.5 84.1,463.7 84.7,463.9 85.3,464.1ZM61.2,478.1 60.7,477.9 59.6,477.6 59.0,477.4 58.5,477.3 60.2,477.5 61.6,477.9 61.2,478.1ZM483.6,446.0 482.9,446.1 482.1,445.7 482.7,445.7 483.1,445.6 483.5,445.6 483.6,446.0ZM508.4,446.1 507.5,446.2 507.3,445.8 508.5,445.5 508.9,445.5 508.9,445.9 508.4,446.1ZM491.8,447.8 491.1,447.9 490.6,447.6 491.1,447.4 491.8,447.8ZM503.6,445.2 503.2,445.5 502.8,445.1 502.6,444.7 503.7,444.5 504.1,444.8 503.6,445.2ZM493.0,446.6 493.7,446.7 494.2,446.7 493.6,447.2 492.8,447.6 492.3,447.7 492.2,447.3 491.8,447.1 490.3,446.5 491.6,446.3 492.4,446.4 493.0,446.6ZM512.6,445.8 512.1,445.8 511.6,445.7 511.3,445.3 511.8,445.1 512.5,445.1 512.8,445.5ZM634.8,435.5 634.4,435.6 635.1,435.3 635.5,435.4 634.8,435.5ZM545.1,444.5 544.0,444.4 543.5,444.3 543.6,443.8 544.2,443.7 545.1,443.6 546.0,443.7 545.3,444.0 545.1,444.5ZM574.6,445.5 573.9,445.6 573.5,445.7 572.2,445.5 572.2,445.0 573.1,444.6 573.9,444.7 574.3,445.0 574.7,445.4ZM413.6,471.7 414.3,471.6 414.5,471.2 417.0,471.7 417.7,472.0 417.2,472.0 416.6,472.0 415.5,472.3 414.3,472.1 412.2,471.9 411.6,471.8 411.1,471.5 412.0,471.2 413.6,471.7ZM405.7,470.3 398.7,470.3 398.3,470.2 399.3,470.0 399.9,469.9 400.6,469.9 401.1,469.7 404.5,470.1 405.6,470.2ZM490.9,445.9 490.4,445.9 490.9,445.4 491.8,445.2 492.2,445.2 492.5,445.7 490.9,445.9ZM442.8,455.2 442.6,455.8 443.3,456.4 443.3,456.8 442.4,456.9 441.7,456.2 441.2,455.9 440.0,455.8 438.8,455.8 440.9,455.5 441.3,455.4 441.7,455.0 442.5,454.5 443.0,454.8 442.8,455.2ZM455.3,451.9 454.7,451.9 454.3,451.8 454.3,451.3 454.7,451.3 455.1,451.7ZM465.3,450.5 464.7,450.5 464.2,450.4 464.1,450.0 464.5,450.0 464.9,450.2 465.3,450.5ZM410.2,471.3 409.7,471.3 410.1,470.9 410.7,470.9 411.3,471.0 410.2,471.3ZM373.0,418.1 373.6,418.2 374.0,418.4 374.4,418.4 373.9,418.5 373.0,418.5 372.4,418.4 373.0,418.1ZM349.8,420.3 349.9,419.8 349.9,420.2ZM313.2,470.7 312.7,470.8 310.7,470.8 310.0,470.7 309.6,470.7 310.2,470.3 311.0,470.1 311.9,470.0 312.6,470.1 313.7,470.2 313.2,470.4ZM346.8,420.1 346.4,420.1 346.0,419.7 348.1,419.8 347.1,419.9ZM345.8,425.5 346.6,425.6 347.0,425.9 345.6,425.9 345.1,425.8 344.4,425.9 343.4,426.2 343.0,425.9 343.4,425.6 344.3,425.4 345.6,425.4ZM344.8,426.5 344.0,426.4 344.8,426.1 345.2,426.4 344.8,426.5ZM341.0,429.4 340.5,429.3 341.6,428.8 342.0,428.7 341.7,429.1 341.0,429.4ZM336.6,423.1 335.9,423.2 336.4,423.0ZM339.0,422.0 339.6,422.0 339.0,422.4 338.5,422.4 337.9,422.6 337.5,422.9 337.1,422.8 336.6,422.7 336.1,422.8 336.9,422.3 337.8,422.1 338.2,422.1 339.0,422.0ZM327.9,428.0 328.3,427.7 327.9,428.0ZM327.8,443.7 327.3,443.4 327.2,443.0 326.4,442.5 326.3,442.1 327.1,442.2 327.5,442.3 328.3,442.7 328.3,443.1 327.9,443.6ZM324.1,430.2 323.7,430.3 324.4,429.8 324.1,430.2ZM326.9,429.0 326.5,429.1 325.8,429.2 326.4,428.5 326.1,428.1 326.5,427.8 326.9,427.8 327.6,428.2 327.3,428.6 326.9,428.9ZM326.1,425.2 326.3,424.8 326.9,424.6 326.1,425.2ZM335.0,423.5 334.4,423.3 334.8,423.2ZM344.3,425.2 343.7,425.5 342.9,425.3 343.1,424.9 344.1,425.0ZM331.6,423.8 332.9,423.7 333.3,423.9 333.8,423.9 332.7,424.3 332.3,423.9 331.6,424.0 331.1,424.1 330.6,424.1 330.1,424.0 330.6,423.9 331.0,423.7 331.6,423.8ZM340.6,427.2 341.2,427.3 340.7,427.4 340.0,427.4 340.4,427.2ZM330.1,444.4 329.7,444.4 329.6,444.0 330.1,444.1ZM331.5,441.0 331.1,441.1 330.5,440.9 331.1,440.8 331.5,441.0ZM331.3,446.4 330.8,446.4 330.7,445.9 331.8,445.9 331.3,446.4ZM331.8,447.4 331.1,447.3 330.7,447.1 331.2,447.0 331.6,447.0 331.8,447.4ZM331.9,424.9 331.4,425.0 331.9,424.9ZM331.5,427.4 331.0,427.5 330.6,427.4 331.1,427.3 331.3,426.9 331.8,426.9 331.4,427.2ZM239.5,452.6 239.0,452.6 238.6,452.4 239.4,452.5ZM246.8,453.3 246.3,453.4 245.8,453.3 246.0,452.7 245.7,452.1 245.4,451.7 246.4,451.5 247.4,451.5 247.8,451.7 247.5,452.3 247.9,452.5 247.5,453.0 246.8,453.3ZM209.6,453.2 208.7,453.3 208.2,453.1 208.1,452.7 209.6,453.2ZM236.0,451.8 235.5,451.9 236.8,451.4 237.3,451.3 237.7,451.6 236.0,451.8ZM300.8,445.2 301.6,445.2 301.2,445.7 300.6,445.3ZM300.0,443.6 299.4,443.7 299.0,443.6 297.8,443.5 297.3,443.1 298.0,442.8 298.7,442.9 299.1,443.0 300.0,443.6ZM294.8,453.8 294.3,453.8 294.3,453.4 294.9,453.1 295.4,453.1 295.2,453.6 294.8,453.8ZM317.1,432.9 316.5,433.0 316.5,432.6 316.8,432.1 317.7,432.1 317.3,432.4 317.1,432.8ZM315.0,433.9 314.4,434.2 314.5,433.6 314.9,433.5 315.0,433.9ZM312.9,438.2 312.4,438.3 312.0,438.2 312.3,437.8 312.7,437.8 313.2,437.8 313.1,438.2ZM312.9,435.8 312.4,435.8 312.5,435.4 313.0,435.4 312.9,435.8ZM0.0,296.6 0.4,296.4 0.4,296.8 0.0,297.1 0.0,296.6ZM1000.0,297.1 1000.0,296.6 1000.0,297.1ZM280.8,227.0 280.7,226.6 280.8,227.0ZM930.0,113.0 929.5,113.1 930.1,112.1 930.0,112.6 930.0,113.0ZM664.8,24.2 664.2,24.2 663.1,24.1 663.6,23.9 664.1,23.9 664.7,24.0ZM651.2,26.5 650.8,26.6 649.5,26.5 649.7,26.1 650.5,26.2 651.1,26.3ZM669.8,25.1 669.0,25.2 667.6,25.1 666.8,25.0 668.3,24.8 670.7,24.7 669.8,25.1ZM648.7,27.3 646.8,27.3 646.2,27.3 645.4,27.2 645.0,27.0 646.0,27.0 646.4,26.8 646.8,26.7 647.7,26.6 648.1,26.7 648.6,26.9 649.6,27.0 649.0,27.2ZM658.6,26.8 658.7,27.2 658.3,27.6 656.1,27.6 655.0,27.5 655.4,27.3 655.5,26.9 657.4,26.9 658.2,26.8 658.6,26.8ZM665.8,27.9 664.8,28.0 664.4,27.9 663.7,27.8 665.4,27.4 666.1,27.5 665.8,27.9ZM315.4,403.4 315.0,403.5 315.5,403.3ZM290.3,385.5 289.9,385.5 289.9,385.0 290.2,384.2 290.1,383.8 290.6,383.4 290.9,383.9 291.2,384.5 291.2,385.1 290.5,385.3ZM290.8,390.8 290.4,390.8 290.5,390.4 291.2,390.3 290.8,390.8ZM293.4,397.0 294.3,397.1 294.8,397.3 295.4,397.4 295.8,397.6 296.4,397.9 296.8,398.2 296.1,398.1 295.6,398.1 295.0,397.6 294.5,397.4 294.1,397.5 293.7,397.4 293.3,397.2 292.9,397.0 292.5,396.6 292.9,396.6 293.3,396.8ZM306.4,402.6 308.6,402.8 309.3,402.7 309.8,402.7 309.5,403.1 310.1,403.3 310.2,403.7 310.7,404.0 311.0,404.4 310.5,404.4 309.8,404.1 309.2,404.0 308.7,404.0 308.6,403.5 308.2,403.5 307.8,403.3 307.3,403.5 307.8,404.1 307.2,404.0 306.6,403.7 306.0,403.4 305.6,403.2 305.9,402.4 306.4,402.6ZM291.4,385.7 290.9,386.3 290.3,386.8 289.9,386.7 290.3,386.3 289.9,386.0 290.3,385.7 290.8,385.6 291.3,385.5ZM333.0,392.8 332.6,392.8 332.6,392.4 333.1,392.5ZM427.0,412.3 426.6,412.3 427.0,412.2ZM649.3,214.9 649.8,214.8 650.5,214.8 651.4,215.1 650.8,215.4 650.4,215.7 649.2,215.8 648.6,215.5 648.1,215.2 648.4,214.8 649.0,214.7ZM648.1,182.6 648.4,182.2 648.3,182.6ZM663.1,193.8 663.3,193.1 663.6,192.6 663.8,193.0 663.4,193.3 663.3,193.7ZM656.1,175.2 655.8,175.6 655.4,175.8 654.9,175.9 654.3,176.1 653.7,176.2 654.3,175.8 654.9,175.5 654.9,175.1 655.3,175.2 655.8,175.0 656.3,175.1ZM649.8,182.8 649.4,183.0 649.0,182.9 649.5,182.6ZM616.6,203.6 617.1,203.6 617.1,204.0 616.6,203.7 616.1,203.4 616.3,202.9 616.5,203.3ZM611.5,206.4 612.2,206.6 611.7,206.7 611.0,206.6 611.0,206.1 611.4,206.0 611.5,206.4ZM640.6,178.1 640.2,177.9 640.1,177.3 640.5,177.1 640.6,177.8ZM634.1,167.7 633.7,167.6 633.6,167.2 633.8,166.8 634.3,167.3 634.1,167.7ZM998.2,300.3 998.0,299.9 998.2,300.3ZM967.9,296.6 967.3,296.7 967.0,296.2 967.4,296.3 967.9,296.6ZM967.5,295.4 966.7,295.3 966.8,294.9 967.2,294.8 967.4,295.2ZM967.9,298.7 968.2,299.1 967.8,299.5 967.4,299.4 967.2,299.0 967.6,298.8ZM966.4,292.9 965.9,293.0 966.2,292.5 966.7,292.5 966.4,292.9ZM963.2,291.2 963.4,292.1 963.8,291.8 964.2,292.0 964.4,292.7 964.1,293.3 963.7,293.3 963.2,293.4 962.9,292.8 962.9,292.3 962.6,291.3 962.7,290.7 963.2,291.2ZM967.3,294.4 967.0,293.6 967.1,292.9 967.4,294.1ZM967.2,292.6 967.0,291.7 967.2,292.2 967.2,292.6ZM965.0,294.7 965.7,295.2 966.1,295.5 966.0,295.9 965.6,295.8 965.1,296.0 965.0,295.6 964.9,295.1 964.5,294.9 964.4,294.2 964.8,294.2 965.0,294.7ZM964.5,293.7 964.1,293.6 964.5,293.5ZM944.1,273.7 943.7,273.5 942.6,272.9 941.9,272.5 941.5,272.3 941.0,272.0 940.5,271.6 940.2,271.0 940.9,271.1 941.3,271.5 941.7,271.8 942.2,272.0 942.7,272.2 944.0,273.1 944.0,273.5ZM937.5,270.4 937.0,270.5 936.4,270.3 935.8,269.9 935.3,269.2 934.7,268.8 934.6,268.4 935.0,268.4 935.5,268.8 936.2,269.1 936.5,269.8 937.0,270.2 937.4,270.3ZM836.6,233.1 837.2,233.3 836.9,233.7 836.3,233.6 835.9,233.6 836.2,233.1 836.6,233.1ZM873.2,154.2 872.7,154.2 873.1,154.1ZM291.4,382.9 290.9,382.7 291.4,382.5 291.9,382.6 291.5,382.8ZM292.6,371.1 292.2,371.2 292.6,371.1ZM295.5,374.5 295.0,374.1 295.2,373.7 295.5,374.1 295.5,374.5ZM292.9,385.0 292.5,385.0 291.9,385.1 291.5,384.4 291.2,384.0 291.0,383.5 291.7,383.1 292.1,382.9 292.1,383.4 292.5,383.7 292.6,384.2 292.9,385.0ZM320.7,402.0 321.6,402.0 322.1,402.0 322.6,402.0 322.1,402.2 321.3,402.2 320.8,402.3 320.2,402.4 320.6,402.1ZM328.1,358.8 327.7,358.8 328.1,358.8ZM330.5,393.9 330.9,393.9 330.7,394.3 330.2,394.1ZM297.4,398.6 297.8,398.8 298.7,398.9 299.2,399.2 299.2,399.6 298.9,400.0 298.4,400.2 297.8,400.3 297.4,400.3 297.8,399.9 297.4,399.6 297.0,399.7 296.6,400.0 296.4,399.5 295.9,399.3 295.4,398.8 294.9,398.7 295.3,398.4 296.0,398.4 296.9,398.6 297.3,398.4ZM313.7,403.2 313.2,403.6 312.8,403.5 312.4,403.3 312.0,403.5 310.9,403.4 310.6,403.0 310.8,402.6 311.5,402.6 312.7,402.7 313.2,402.7 313.6,403.0ZM302.8,402.4 303.3,402.7 303.8,402.6 304.4,402.5 304.8,403.0 304.4,403.2 304.0,403.2 303.6,403.1 302.9,402.9 302.4,402.6 302.0,402.5 301.6,402.5 302.2,402.3 302.8,402.4ZM313.1,404.9 312.3,404.8 312.8,404.4 313.2,404.8ZM292.9,392.4 292.7,392.8 292.2,392.8 291.8,392.9 291.3,393.1 290.9,393.4 291.0,392.9 291.2,392.4 291.6,392.5 292.0,392.4 292.4,392.2 292.9,392.4ZM291.5,389.7 291.0,389.9 290.4,389.8 290.6,389.4 290.8,388.9 291.3,389.0 291.7,389.1 292.1,389.4 291.5,389.7ZM292.2,393.4 292.3,394.0 293.0,394.3 292.6,394.9 292.1,395.2 291.6,394.5 291.4,393.9 291.9,393.7ZM295.0,371.7 294.6,372.0 294.1,371.9 294.5,371.7 294.9,371.6ZM301.7,400.1 302.3,400.3 302.7,400.3 302.8,400.7 302.4,401.0 301.9,400.9 301.5,400.6 300.9,400.6 300.5,400.8 300.1,400.8 299.7,400.3 299.6,399.8 300.0,399.7 300.8,399.8 301.2,399.9 301.7,400.1ZM294.1,394.3 293.7,394.2 293.3,394.0 293.7,393.9 294.1,394.1ZM295.2,373.3 294.8,373.5 294.4,373.9 294.8,374.2 294.9,374.6 295.0,375.1 295.2,375.5 294.9,375.9 294.4,376.0 294.2,375.5 293.7,375.2 293.5,374.8 293.1,374.3 292.7,374.0 293.1,373.8 293.3,373.4 293.9,373.4 294.1,373.0 294.5,372.6 295.0,372.9 295.2,373.3ZM293.6,376.9 293.2,377.1 292.6,377.0 292.9,376.5 293.1,375.8 293.6,375.5 293.6,376.3 293.8,376.7ZM295.7,123.7 295.1,123.7 294.7,123.8 295.3,123.4 295.8,123.1 295.7,123.5ZM295.3,123.4 294.8,123.4 295.2,123.1 295.6,123.1ZM330.7,123.6 330.3,123.5 330.8,123.4ZM328.0,118.7 328.4,118.7 327.9,118.8 328.0,118.3 328.8,117.8 329.2,117.6 328.7,118.1 328.2,118.4ZM320.8,117.0 320.5,117.4 320.4,117.0 320.8,117.0ZM302.7,119.8 303.1,119.5 302.7,119.8ZM314.6,125.9 314.2,126.0 314.4,125.5 314.6,125.9ZM310.6,126.9 310.2,127.1 310.1,126.6 310.5,126.6ZM816.2,269.7 815.7,269.8 815.2,270.0 814.4,270.0 814.0,270.0 813.2,269.8 813.2,269.4 814.1,269.1 816.6,269.1 816.3,269.6ZM941.3,402.0 941.4,401.4 941.3,402.0ZM79.4,296.9 79.3,296.5 79.4,296.9ZM62.9,244.8 63.4,245.2 62.7,245.0 62.3,244.7 62.7,244.9 62.5,244.4 63.0,244.5ZM113.8,276.9 114.2,277.1 113.8,277.3 113.4,277.1 113.8,276.9ZM85.2,299.1 85.6,299.3 85.2,299.3 84.8,299.3 84.4,298.9 85.1,298.7 85.2,299.1ZM110.9,274.8 110.5,274.8 110.4,274.4 111.0,274.4 110.9,274.8ZM21.3,287.4 21.7,288.0 21.3,288.3 20.9,288.3 20.4,287.9 20.1,287.5 20.8,287.5 21.3,287.4ZM2.9,298.0 2.8,297.6 2.9,298.0ZM57.4,239.1 57.6,239.5 57.2,239.2ZM23.7,289.0 23.0,289.0 22.6,288.9 22.1,288.6 22.6,288.4 23.3,288.6 23.7,288.8ZM25.8,289.9 26.2,289.6 25.8,289.9ZM28.3,303.0 27.9,303.0 28.4,302.9ZM14.1,309.2 14.1,309.6 14.1,309.2ZM873.9,229.5 873.7,229.1 874.0,228.6 874.0,229.2ZM904.9,208.0 905.0,207.6 904.9,208.0ZM902.1,213.2 901.8,212.7 902.2,212.4 902.6,212.3 902.2,212.7 902.1,213.2ZM995.8,302.7 995.4,302.9 995.0,303.0 994.5,303.2 994.9,302.9 995.4,302.6 995.8,302.7ZM965.5,289.6 965.1,289.7 965.3,289.3ZM965.2,288.6 965.0,288.2 965.4,288.3ZM971.9,306.1 971.5,306.1 971.9,306.1ZM970.8,304.3 970.4,304.5 970.0,304.1 970.1,303.7 970.4,304.1 970.8,304.3ZM970.4,302.6 969.4,302.4 969.4,302.0 969.8,301.8 970.2,302.1 970.4,302.6ZM966.7,309.5 967.0,310.0 966.6,310.1 966.1,309.4 966.5,309.4ZM962.6,307.5 962.7,307.1 962.7,306.7 962.8,307.2ZM965.0,308.8 964.6,308.6 964.1,308.3 964.4,307.8 964.0,307.7 964.5,307.4 964.7,308.0 965.1,308.5ZM944.3,303.6 944.3,303.1 944.3,303.6ZM963.7,282.4 963.3,282.4 963.7,282.4ZM961.5,279.9 961.0,279.9 960.6,280.1 960.7,279.7 961.2,279.6 961.6,279.7ZM946.0,282.8 945.5,282.7 944.9,282.3 944.4,282.2 945.7,282.5ZM946.5,273.1 947.2,273.9 947.1,274.4 947.7,274.9 947.8,275.3 947.9,275.9 948.2,276.4 947.7,276.1 947.3,275.9 946.9,275.4 946.6,274.9 946.3,273.9 946.1,273.3 946.5,273.1ZM949.2,278.9 949.6,279.0 950.1,279.1 950.8,279.8 950.3,280.1 949.7,279.9 948.7,279.4 948.6,279.0 948.0,278.7 948.1,278.3 948.5,278.4 949.0,278.8ZM948.7,276.7 948.8,277.1 948.4,277.0 948.4,276.4 948.2,276.0 948.7,276.7ZM942.2,275.3 941.8,275.2 942.2,275.1ZM944.9,275.0 945.3,275.2 944.9,275.2ZM943.6,273.6 943.2,273.6 943.6,273.6ZM938.2,272.9 938.6,273.6 938.2,273.7 937.7,273.5 937.7,273.0 937.1,273.0 936.7,273.0 937.1,272.5 937.5,272.1 937.8,272.7 938.2,272.9ZM939.4,274.5 939.5,274.1 939.4,274.5ZM939.2,274.1 938.7,274.3 938.6,273.9 939.2,273.7 939.2,274.1ZM937.2,274.2 936.7,273.8 937.1,273.4 937.2,273.8 937.2,274.2ZM935.2,272.0 934.7,271.4 934.9,271.0 935.3,271.4 935.3,271.9ZM935.0,272.7 934.8,272.3 935.0,272.7ZM936.6,272.5 936.2,272.5 936.0,272.1 936.4,271.8 936.7,272.3ZM932.9,269.7 932.4,269.7 933.0,269.6ZM939.8,231.1 939.4,231.1 939.3,230.7 939.7,230.7 939.8,231.1ZM987.6,350.8 987.1,350.6 987.2,350.2 987.6,350.8ZM983.1,363.5 982.7,363.7 982.8,363.3 983.2,363.1 983.1,363.5ZM963.2,376.8 962.8,376.9 963.2,376.8ZM10.6,371.5 10.1,371.9 9.7,371.6 9.9,372.1 9.7,372.5 9.6,372.1 9.4,371.7 8.9,371.8 9.3,371.6 10.6,371.5ZM969.9,395.8 969.5,395.8 969.9,395.8ZM961.7,391.0 961.3,391.2 960.8,391.2 961.3,390.8 961.4,390.4 961.8,390.4 961.7,390.8ZM967.1,380.2 967.4,380.6 966.7,380.9 966.1,381.0 965.4,381.3 965.6,380.8 965.9,380.4 966.0,380.0 966.5,379.7 967.1,380.2ZM880.8,293.8 880.4,293.6 880.8,293.8ZM868.3,281.4 868.0,281.0 868.2,280.6 868.4,281.0 868.3,281.4ZM914.0,306.4 913.8,306.0 914.0,306.4ZM906.3,300.6 906.5,301.1 906.1,301.0 906.2,300.6ZM895.2,279.7 894.8,279.8 895.0,279.4ZM895.4,278.3 895.0,278.4 895.4,278.3ZM887.5,296.0 887.1,296.4 886.6,296.5 886.7,295.9 887.4,295.7 887.8,295.6 887.5,296.0ZM879.8,288.3 880.2,288.2 880.1,288.6 879.8,289.1 880.2,289.4 879.6,289.7 879.1,289.5 878.7,289.5 878.9,288.9 879.0,288.5 879.4,288.1 879.8,288.3ZM878.4,288.4 878.4,288.0 878.4,288.4ZM879.4,281.6 879.6,281.1 879.8,280.6 879.8,281.1 879.4,281.6ZM878.7,282.2 878.3,282.4 879.0,281.9ZM862.4,282.4 862.8,282.7 862.0,282.7 861.5,282.8 861.3,282.4 861.7,282.4 861.5,281.9 861.9,281.5 862.2,282.0 862.4,282.4ZM862.8,281.6 863.2,281.6 863.6,281.4 864.3,281.3 864.8,281.2 865.2,281.6 865.2,282.0 864.7,282.5 863.7,283.1 862.9,282.6 862.5,282.3 862.3,281.8 862.2,281.4 862.7,281.4ZM846.1,292.8 845.9,292.4 846.1,292.8ZM820.7,307.7 820.3,307.9 820.6,307.4ZM814.4,322.4 813.8,321.6 813.6,321.0 814.2,321.7 814.3,322.1ZM909.3,370.5 908.8,370.8 909.0,370.4ZM909.5,370.1 909.3,369.7 909.5,370.1ZM899.8,361.4 899.5,360.8 899.6,360.4 899.9,360.0 900.3,360.5 900.4,361.0 899.8,361.4ZM912.0,362.0 912.4,362.3 912.0,362.3 911.4,362.4 912.0,362.0ZM911.1,360.4 911.6,360.9 911.9,361.3 911.7,361.8 911.3,361.8 910.8,361.2 910.8,360.8 910.9,360.4ZM911.8,362.5 911.4,362.6 911.8,362.5ZM882.2,349.3 882.9,349.3 883.5,349.3 883.5,349.7 882.9,349.6 882.4,349.7 882.2,350.1 881.8,350.2 881.1,350.0 880.6,350.1 879.9,350.1 879.4,349.8 879.5,349.3 880.8,349.1 881.5,348.9 882.0,348.9 882.2,349.3ZM904.1,356.5 903.7,356.7 904.1,356.5ZM903.6,356.9 903.1,357.0 903.6,356.9ZM926.5,326.2 926.3,327.0 926.1,326.4 926.5,326.2ZM926.2,325.9 926.0,325.4 926.2,325.9ZM925.2,321.5 924.9,321.0 925.1,320.4 925.1,320.0 925.5,319.6 925.7,319.2 925.5,318.8 925.8,319.2 926.0,319.6 925.4,320.9 925.2,321.3ZM919.8,315.3 920.2,315.7 919.5,315.4ZM916.5,311.6 916.4,311.2 916.5,311.6ZM825.2,228.1 824.9,227.7 825.2,228.1ZM833.1,220.9 832.7,220.7 833.2,220.5 833.1,220.9ZM832.9,218.0 832.9,218.4 832.8,218.0ZM833.6,216.2 834.0,216.1 834.2,216.6 833.8,216.6 833.4,216.6 833.0,216.2 833.0,215.8 833.5,216.1ZM833.4,217.5 832.9,216.8 833.3,216.9 833.5,217.3ZM840.7,220.9 840.3,220.9 840.4,220.5 840.8,220.2 840.8,220.8ZM842.7,223.8 842.6,223.4 842.8,222.5 843.1,221.8 843.3,221.4 843.6,220.9 844.0,220.2 844.2,219.5 844.3,219.1 844.5,218.7 844.5,219.2 844.6,219.7 844.5,220.1 844.6,220.6 844.5,221.1 844.1,221.5 843.6,221.9 843.4,222.4 843.0,223.4 842.7,223.8ZM846.1,222.8 845.6,223.2 844.8,223.3 844.3,223.3 844.0,222.9 844.2,222.4 844.6,222.2 844.9,221.8 845.4,221.8 845.8,222.0 846.0,222.6ZM846.7,224.6 846.3,224.6 846.7,224.6ZM843.6,224.3 843.4,224.7 843.0,224.5 843.4,224.3ZM834.0,235.4 833.6,235.6 833.2,235.9 832.8,235.9 833.3,235.5 833.8,235.2ZM839.1,232.1 838.6,231.9 838.4,231.5 839.0,231.3 839.7,231.6 839.4,232.0ZM850.2,222.9 850.0,222.4 850.4,222.4 850.5,222.8ZM849.1,222.5 848.7,222.0 848.7,221.4 849.0,221.0 849.0,221.5 849.2,222.0 849.1,222.4ZM834.1,211.8 833.6,211.7 834.1,211.8ZM838.6,212.4 839.2,212.6 839.0,213.1 838.5,213.1 838.4,212.7 838.5,212.3ZM839.1,215.7 838.9,216.4 838.8,216.0 838.7,215.6 838.9,215.0 839.3,214.9 839.1,215.7ZM840.7,215.8 840.3,215.6 840.8,215.5ZM842.4,214.3 842.7,214.7 842.1,214.2 841.6,213.8 842.2,214.1ZM843.8,215.4 843.4,215.1 843.8,215.4ZM843.7,215.9 844.2,216.2 844.6,216.8 844.6,217.2 844.0,216.9 843.5,216.5 843.1,216.1 842.5,216.6 842.1,216.9 842.2,216.4 842.3,215.8 842.3,215.3 842.9,215.3 843.3,215.5 843.7,215.9ZM845.4,212.1 844.9,212.4 844.5,212.0 844.8,211.2 845.3,211.3 845.5,211.8ZM839.4,211.0 838.8,210.7 839.4,211.0ZM839.0,208.3 838.9,209.0 838.5,208.6 838.4,208.2 838.8,208.2ZM837.6,196.2 837.2,196.3 837.6,196.2ZM911.2,266.2 910.8,266.0 910.5,265.6 911.3,265.6 911.3,266.0ZM908.8,265.1 908.4,264.8 908.7,264.4 908.9,264.9ZM905.6,263.1 905.2,263.0 905.5,262.6 905.6,263.1ZM910.8,256.3 910.4,256.4 910.8,256.3ZM908.5,255.4 909.4,255.6 908.9,256.1 908.5,256.1 908.1,256.1 907.6,256.0 907.1,256.1 907.2,255.6 907.7,255.5 908.5,255.4ZM929.6,265.1 929.4,264.5 929.5,264.0 929.8,264.5 929.7,265.0ZM924.1,258.7 923.7,258.6 924.1,258.7ZM922.5,258.2 922.1,258.0 922.5,258.2ZM922.1,257.9 922.1,257.5 922.1,257.9ZM916.0,254.3 915.4,254.1 915.9,254.0ZM917.9,257.4 917.3,257.4 916.9,257.2 916.9,256.7 917.3,256.6 917.9,256.9 917.9,257.3ZM919.7,277.8 920.2,277.6 920.1,278.3 919.3,278.0 919.1,277.4 918.8,277.0 919.6,277.6ZM917.6,276.4 917.0,276.0 917.2,275.6 917.6,275.7 917.7,276.1ZM918.1,276.0 918.5,276.2 918.9,276.2 919.1,276.6 919.0,277.0 918.5,276.8 917.9,276.7 917.9,276.2ZM919.7,274.3 919.7,273.9 919.6,273.5 919.7,274.3ZM924.0,274.9 924.5,274.9 924.9,275.2 924.9,275.6 924.3,275.5 923.6,275.0 924.0,274.9ZM919.2,279.3 918.8,279.5 919.2,279.3ZM928.6,281.6 928.1,281.7 928.6,281.6ZM926.5,281.9 926.9,282.0 926.5,282.3 926.0,282.1 925.6,281.7 926.5,281.9ZM898.8,273.6 898.2,273.4 898.7,273.3ZM898.9,274.0 898.5,273.9 898.1,273.7 898.9,274.0ZM876.1,251.8 876.7,251.9 877.1,252.0 877.5,252.0 878.0,252.4 878.6,253.0 878.2,253.4 877.8,253.2 877.3,253.1 877.1,252.3 876.5,252.2 876.1,252.0ZM876.3,254.4 877.4,254.6 878.3,254.6 878.9,254.8 879.8,254.8 880.3,255.0 879.7,255.1 879.1,255.2 878.7,255.2 878.3,255.2 877.9,255.1 877.4,254.9 876.4,254.6ZM874.9,253.1 874.5,252.9 874.9,252.9ZM885.8,273.3 885.0,273.3 885.2,272.8 885.8,273.2ZM871.0,261.8 870.4,261.6 871.0,261.8ZM862.1,254.7 862.2,255.2 861.9,255.6 861.5,255.7 860.8,255.5 860.4,255.3 861.1,254.9 861.7,254.8 862.1,254.7ZM863.9,253.7 863.5,253.7 863.2,253.3 863.1,252.9 863.6,252.5 864.0,252.5 864.0,253.3 863.9,253.7ZM863.6,252.2 862.2,252.6 862.6,252.3 863.1,252.3 863.6,252.2ZM862.8,251.5 862.4,251.4 862.8,251.2ZM863.4,250.0 863.8,250.1 864.7,250.4 864.8,250.8 864.4,251.0 863.9,251.0 863.4,250.6 863.0,250.2 863.2,250.8 863.6,251.0 863.2,251.2 863.0,250.8 862.6,251.0 862.1,250.7 861.9,250.3 862.3,250.3 862.7,250.1 863.1,250.1ZM859.9,250.5 859.4,250.2 859.8,250.4ZM856.8,244.3 856.4,244.3 856.2,243.6 856.5,243.1 856.9,242.9 857.5,243.1 857.3,243.8 857.1,244.2ZM854.0,250.0 854.0,249.6 854.0,250.0ZM853.5,251.4 853.1,251.4 853.1,250.8 853.6,250.8 853.5,251.4ZM853.6,252.2 853.2,252.1 853.4,251.7 853.6,252.1ZM854.3,250.9 854.7,251.3 854.5,251.7 855.0,251.9 855.1,252.4 854.6,252.3 854.2,252.2 854.1,251.8 853.6,251.4 853.7,250.9 854.1,250.9ZM856.0,254.6 855.3,254.7 854.8,254.7 854.3,254.8 853.9,254.6 854.0,254.0 854.4,253.7 854.8,253.8 855.3,254.0 856.0,254.5ZM874.3,265.9 874.3,266.3 874.3,266.8 874.3,267.2 874.1,267.6 873.4,267.6 873.0,267.2 872.6,266.8 873.1,266.7 873.2,266.2 872.8,266.0 873.2,265.9 873.5,265.4 873.9,265.1 874.0,265.5 874.3,265.9ZM873.7,267.9 873.6,268.3 873.2,268.9 872.8,269.2 872.4,268.8 872.5,268.0 872.5,267.4 872.9,267.3 873.4,267.7ZM869.2,266.4 869.3,265.8 869.8,264.7 869.8,265.5 869.4,265.9 869.2,266.4ZM868.9,266.3 868.5,266.3 868.6,265.9 868.9,266.3ZM857.6,259.9 857.2,259.8 857.6,259.9ZM857.1,260.0 856.6,260.1 857.0,259.8ZM856.3,260.2 855.9,260.4 855.5,260.5 855.6,260.0 856.3,259.8 856.3,260.2ZM842.2,262.6 842.2,263.2 841.8,263.2 841.7,263.6 841.6,264.3 842.1,264.5 842.0,265.0 841.6,265.0 841.2,265.7 840.8,265.7 840.5,265.2 840.7,264.8 841.0,264.4 841.1,264.0 841.2,263.4 841.3,262.8 841.5,262.3 841.9,262.2 842.2,262.6ZM840.7,264.6 840.5,265.0 840.0,264.8 839.8,264.3 840.0,263.9 839.8,263.5 840.3,263.1 840.7,262.9 841.0,263.7 840.6,264.3ZM839.0,265.1 838.5,264.9 838.4,264.5 838.6,264.1 839.0,264.3 839.0,265.1ZM842.3,261.4 842.1,261.8 841.6,261.5 841.7,261.1 842.3,261.1ZM850.1,256.8 849.8,256.3 849.6,255.8 850.0,255.6 849.9,256.0 850.2,256.6ZM850.1,255.0 850.9,255.1 849.9,255.3 849.5,255.3 848.5,255.4 848.5,255.0 849.2,255.0 850.1,255.0ZM847.1,254.7 847.5,254.8 847.9,254.9 847.2,255.4 846.8,255.3 846.2,255.5 845.6,255.6 845.4,255.2 845.5,254.7 846.3,254.5 847.1,254.7ZM842.3,253.3 842.3,253.9 842.6,253.5 843.0,253.5 843.1,254.0 842.7,254.2 842.3,254.4 842.1,253.6 841.6,254.2 841.1,254.0 841.2,253.6 841.6,253.3 842.1,253.2ZM838.5,251.1 838.1,251.4 838.5,251.1ZM852.3,238.8 852.1,238.4 852.0,237.9 852.1,237.4 852.5,237.9 852.4,238.4 852.3,238.8ZM852.0,239.2 851.8,238.8 852.0,239.2ZM849.0,240.5 848.6,240.4 848.6,240.0 848.5,239.6 848.8,240.1 849.0,240.5ZM848.4,242.6 848.3,242.2 848.4,242.6ZM834.8,267.5 834.7,268.0 834.6,267.4 834.6,266.9 834.6,266.3 834.8,267.5ZM866.6,270.0 866.2,269.9 866.6,270.0ZM863.5,273.1 863.9,272.5 864.4,272.6 864.0,272.8 863.6,272.9ZM864.8,272.2 864.4,272.2 864.1,271.8 864.3,271.3 864.6,270.8 865.0,270.4 865.4,270.1 865.8,269.8 865.7,270.2 865.8,270.7 865.6,271.2 865.2,271.6 864.9,272.1ZM860.7,272.1 860.3,272.3 860.0,271.8 860.6,271.7 860.7,272.1ZM857.4,270.0 857.0,269.8 857.4,269.7ZM855.1,272.5 855.5,272.6 855.9,272.7 855.1,272.7ZM852.2,271.3 851.6,271.7 851.3,272.1 850.9,272.0 850.5,272.0 850.1,271.9 849.5,272.2 849.6,271.7 849.9,271.3 850.6,271.4 851.0,271.3 851.7,271.0 852.2,271.3ZM849.0,272.6 848.8,273.1 848.8,272.7ZM842.8,278.6 842.9,278.2 842.8,278.6ZM841.5,280.3 841.2,279.9 841.8,279.7 842.4,279.2 842.8,279.5 842.3,280.0 841.7,280.2ZM838.6,279.4 838.1,279.4 838.5,279.0 838.9,279.0 838.6,279.4ZM846.0,272.6 846.5,272.7 847.0,272.7 847.4,272.7 847.6,273.1 845.7,273.5 845.6,273.0 845.9,272.6ZM845.2,273.1 845.0,273.6 844.6,273.8 844.2,273.5 844.5,273.1 845.1,272.8ZM844.2,273.0 843.8,273.1 843.4,273.4 843.2,273.8 842.8,273.9 842.4,273.7 842.9,273.2 843.3,273.0 843.8,272.7 844.2,272.9ZM842.5,273.2 841.7,273.3 842.0,272.9 842.6,273.0ZM831.8,274.3 831.6,273.9 831.7,273.5 832.1,273.6 831.9,274.0ZM820.5,269.4 820.1,269.3 820.6,269.0 821.0,269.3 820.6,269.3ZM813.1,266.1 812.7,266.1 813.1,266.0ZM827.6,238.7 827.0,238.9 826.8,238.3 827.5,238.4ZM826.8,240.9 826.5,240.5 826.9,240.5 826.8,240.9ZM823.4,259.6 823.3,260.1 823.3,259.5ZM823.1,260.7 822.5,261.3 822.4,260.6 822.3,260.0 822.4,259.6 822.9,259.1 823.0,259.6 823.1,260.3 823.1,260.7ZM802.6,254.5 802.2,254.4 802.7,254.3ZM804.7,253.3 804.2,253.6 804.0,252.9 804.5,252.7 804.9,253.1ZM800.9,239.8 800.5,239.9 800.5,239.5 800.1,239.2 800.0,238.8 800.6,238.3 801.1,238.9 801.1,239.3 800.9,239.8ZM793.8,242.0 793.6,241.6 794.0,241.7ZM782.5,244.2 782.7,244.6 782.6,245.0 782.2,245.2 781.7,244.7 781.8,244.3 782.3,244.1ZM784.5,247.2 784.1,247.0 784.0,246.5 784.0,246.1 784.5,246.5 784.5,247.0ZM784.7,245.9 784.7,246.3 784.3,246.1 783.8,245.9 783.4,245.7 784.6,245.8ZM786.2,247.9 785.5,247.8 784.8,247.7 784.7,247.3 784.9,246.9 785.3,247.3 786.0,247.5 786.2,247.9ZM786.6,247.6 786.2,247.5 785.8,247.2 785.3,247.1 786.1,247.0 786.6,247.6ZM786.9,248.5 786.5,248.5 786.6,248.1 786.9,248.5ZM787.4,248.2 787.1,247.8 787.5,248.0ZM789.0,246.7 789.1,247.2 788.7,247.1 789.0,246.7ZM790.5,246.6 790.7,247.1 790.6,247.6 790.2,247.5 790.1,247.1 789.7,247.2 789.9,246.7 790.3,246.7ZM789.6,247.7 789.2,247.5 789.6,247.7ZM790.8,249.8 790.3,249.4 790.7,249.7ZM791.0,250.5 791.4,250.6 790.8,250.6 790.2,250.6 790.4,250.0 790.9,250.3ZM790.2,250.9 790.5,251.3 790.3,251.7 789.9,251.8 789.6,251.3 790.2,250.9ZM788.2,251.0 787.8,251.1 787.4,251.0 787.6,250.6 788.1,250.8ZM800.6,258.3 800.5,258.7 799.9,258.9 799.5,258.6 799.1,258.9 798.9,258.5 798.8,258.1 799.0,257.6 799.1,257.1 799.5,257.0 800.2,257.2 800.6,257.5 800.8,257.9 800.6,258.3ZM796.9,258.4 796.5,258.2 797.0,258.1ZM792.4,268.4 792.0,268.4 792.4,268.1ZM770.8,245.9 771.4,246.7 772.0,247.2 771.9,248.3 771.3,248.3 771.1,247.7 770.7,247.4 770.3,246.7 769.7,246.0 770.1,246.0 770.8,245.9ZM784.4,265.2 783.7,264.9 784.4,264.9ZM779.0,258.8 779.1,259.2 778.7,259.0 778.7,258.6 778.3,258.3 778.3,257.8 779.0,258.3 779.1,258.7ZM778.3,257.6 777.8,257.8 777.8,257.4 777.7,257.0 778.3,257.4ZM777.3,256.5 776.9,256.3 776.5,256.0 776.6,255.6 777.0,256.1 777.3,256.5ZM775.5,254.9 774.6,254.6 774.0,253.5 774.1,252.7 774.6,252.5 774.9,252.9 775.2,253.4 775.4,253.8 775.6,254.3 775.8,254.8ZM773.5,251.5 773.1,251.5 773.2,251.1 773.4,250.6 773.1,250.0 773.6,250.5 773.7,251.1 773.5,251.5ZM770.4,244.2 769.7,243.8 770.1,243.8 770.4,244.2ZM768.0,243.4 767.6,243.4 766.7,242.8 766.3,242.7 765.9,242.3 766.1,241.9 766.7,242.3 767.2,242.6 767.8,243.0 768.0,243.4ZM764.9,233.9 764.5,233.6 764.9,233.7ZM809.4,243.3 809.2,242.4 809.4,242.8 809.4,243.2ZM825.4,230.1 825.4,229.6 825.8,229.7 825.4,230.1ZM588.9,121.7 589.3,121.8 588.9,121.8 588.1,121.6 587.7,121.5 588.9,121.7ZM563.7,86.6 563.3,86.7 562.9,86.9 562.4,86.9 562.3,86.5 561.6,86.4 562.4,86.2 562.9,85.9 563.6,86.1 563.9,86.6ZM564.8,87.4 564.1,87.2 564.8,87.1ZM599.5,68.9 599.5,69.3 599.1,69.3 598.7,69.0 599.2,68.9ZM618.6,64.7 618.0,64.6 618.4,64.5ZM574.7,31.5 574.2,31.5 573.5,31.3 574.4,31.3 575.0,31.4ZM580.7,30.8 581.5,30.8 582.3,30.8 581.4,31.0 580.2,30.9 579.2,30.9 577.9,31.0 577.5,31.0 578.1,30.8 578.8,30.8 579.2,30.6 580.1,30.6 580.7,30.8ZM639.0,27.6 637.7,27.4 638.6,27.1 639.6,27.2 639.1,27.5ZM642.8,27.9 641.9,28.0 640.1,28.0 639.1,27.8 640.2,27.7 640.8,27.6 641.5,27.5 642.4,27.6 642.8,27.9ZM590.3,27.4 587.7,27.6 591.7,27.2 592.7,27.1 593.4,27.2 590.3,27.4ZM660.6,23.5 661.2,23.7 662.3,23.7 662.7,23.8 662.1,23.9 660.7,24.0 661.2,24.3 660.5,24.5 659.6,24.6 658.8,24.5 657.8,24.3 657.4,24.4 657.0,24.5 656.6,24.5 656.1,24.4 654.8,24.5 654.4,24.4 654.9,24.1 656.0,24.2 656.7,23.9 657.6,23.8 658.3,23.6 659.3,23.5 660.3,23.4ZM652.0,24.7 654.1,24.9 656.0,24.9 656.9,25.0 658.1,25.2 659.9,25.5 660.3,25.6 659.9,25.7 657.8,25.9 656.4,26.0 655.2,26.0 654.8,26.0 654.3,25.8 653.1,25.7 651.9,25.7 651.5,25.6 651.0,25.6 650.2,25.5 650.7,25.3 651.2,25.0 651.8,24.7ZM641.0,24.9 640.6,24.9 640.0,24.8 640.5,24.5 640.9,24.5 641.3,24.6ZM662.8,24.9 663.2,25.0 663.6,25.3 662.9,25.6 661.9,25.7 660.9,25.6 660.4,25.3 659.5,25.2 658.9,25.0 659.5,24.9 660.2,24.9 661.2,24.7 661.6,24.7 662.5,24.8ZM676.8,23.3 674.7,23.3 673.8,23.2 672.5,23.1 673.0,23.0 674.4,23.0 677.0,23.1ZM661.9,23.0 661.0,23.1 661.5,22.7 664.6,22.6 665.0,22.7 661.9,23.0ZM552.1,26.9 551.5,27.1 550.5,27.0 551.4,26.8 552.1,26.9ZM531.3,31.6 531.7,31.8 532.3,32.0 532.8,32.1 533.5,32.5 532.7,32.4 532.2,32.3 531.6,32.1 531.1,32.1 530.1,31.5 529.5,31.2 529.3,30.8 529.9,30.9 530.4,31.0 530.9,31.2 531.0,31.6ZM553.4,43.4 552.5,43.3 553.3,43.0 553.4,43.4ZM647.6,52.1 647.4,52.6 646.8,52.5 646.0,52.1 645.6,52.1 645.2,52.0 645.9,51.7 646.5,51.8 647.0,51.8 647.4,52.1ZM707.4,47.6 706.8,47.5 706.1,47.3 706.1,46.9 706.7,46.9 707.2,46.9 707.6,46.9 708.2,47.0 707.6,47.1 707.5,47.5ZM709.7,46.0 709.3,46.0 709.9,45.7 710.9,45.6 711.3,45.7 710.8,45.9 709.7,46.0ZM713.2,46.0 711.8,45.9 711.3,45.8 711.8,45.7 713.2,46.0ZM715.6,49.2 714.3,49.2 713.6,49.2 714.3,48.8 714.9,48.4 715.5,48.2 716.0,48.2 717.4,48.5 716.7,48.9 716.1,49.1 715.6,49.2ZM720.8,48.0 719.1,47.9 718.6,47.8 719.9,47.0 720.4,47.1 720.9,47.5 720.9,47.9ZM729.7,44.2 729.1,44.2 728.7,44.1 729.2,44.0 729.7,44.1ZM732.1,44.2 731.7,44.3 731.0,44.2 730.0,44.2 731.0,44.0 732.0,44.1ZM735.4,43.2 734.5,43.2 735.2,43.0 735.8,43.0 735.4,43.2ZM740.7,41.7 741.7,41.7 741.5,42.1 740.8,42.1 740.0,42.1 739.6,42.0 740.3,41.8 740.7,41.7ZM728.3,40.5 727.9,40.7 727.5,40.9 726.9,40.9 726.5,40.7 727.0,40.4 727.6,40.5 728.2,40.2ZM768.1,38.1 767.6,38.3 767.0,38.5 766.2,38.4 765.8,38.4 764.8,38.3 765.5,38.2 766.1,38.1 767.1,38.1 768.1,38.1ZM771.1,37.2 770.6,37.2 771.1,37.2ZM767.5,36.0 766.9,36.1 766.3,36.2 765.8,36.1 764.9,36.1 766.3,35.8 768.1,35.5 767.8,35.9ZM748.6,35.6 748.1,35.6 747.7,35.5 748.9,35.2 748.6,35.6ZM711.8,28.7 712.4,28.8 714.9,29.0 715.4,29.1 713.4,29.2 712.9,29.2 712.4,29.0 711.5,28.9ZM722.3,25.4 719.7,25.5 720.0,25.1 721.7,25.1 723.0,25.1 723.4,25.2 722.3,25.4ZM754.4,24.6 753.4,24.8 749.9,24.7 753.1,24.4 754.1,24.5ZM799.2,33.0 798.6,33.2 798.2,33.1 797.2,33.1 795.6,32.9 796.1,32.9 798.6,32.8 799.2,33.0ZM795.2,32.8 794.5,32.7 795.4,32.6 796.2,32.4 795.8,32.6 795.2,32.8ZM798.4,35.4 798.0,35.3 798.6,35.1 799.0,35.2 798.4,35.4ZM812.4,37.2 812.9,37.4 812.2,37.5 811.5,37.4 811.1,37.2 811.9,37.2 812.4,37.2ZM834.1,47.0 833.4,47.1 832.8,47.1 832.3,46.9 832.7,46.8 833.2,46.8 834.0,46.9ZM845.9,44.9 845.5,44.8 846.0,44.6ZM877.6,40.5 877.1,40.6 876.3,40.6 876.6,40.1 876.6,39.7 876.9,39.3 877.4,39.6 878.1,39.9 877.7,40.2ZM914.3,37.1 912.2,37.1 913.1,36.8 915.0,36.7 914.6,36.8ZM878.3,44.7 877.9,44.6 877.0,44.3 876.2,43.9 876.7,43.8 877.9,44.2 878.5,44.5ZM883.2,51.4 882.5,51.6 882.0,51.5 881.5,51.5 880.8,51.4 881.3,51.2 882.8,51.1 883.2,51.4ZM946.4,53.3 945.8,53.3 946.2,53.1ZM970.0,56.7 969.2,56.7 967.6,56.5 967.1,56.4 966.6,56.2 966.2,56.1 966.8,55.6 967.2,55.5 967.7,55.5 970.5,55.9 970.3,56.3 970.1,56.7ZM954.5,87.2 954.1,87.5 954.4,87.1 954.8,86.7 955.0,86.3 956.1,85.8 957.0,85.5 957.4,86.2 956.3,86.6 955.4,86.8 954.5,87.2ZM966.8,98.4 965.8,98.1 965.2,97.8 965.9,97.9 966.3,98.1 966.8,98.4ZM962.9,97.7 962.9,98.1 962.4,97.7 962.0,97.6 961.4,97.1 961.1,96.7 960.4,96.4 960.9,96.2 961.7,96.3 961.8,96.8 962.2,97.2 962.7,97.5ZM934.5,109.3 933.9,109.2 934.4,108.7 934.5,109.3ZM933.1,110.3 932.2,110.6 931.8,110.9 931.4,110.9 931.2,110.5 931.8,110.1 932.4,110.0 933.0,109.2 933.6,109.0 933.6,109.6 933.1,110.3ZM928.0,114.5 928.4,114.2 928.0,114.5ZM932.3,108.8 931.9,108.7 932.3,108.6ZM922.2,119.7 921.7,120.0 922.3,119.4 922.7,119.1 922.2,119.7ZM915.8,123.2 915.4,123.4 915.7,122.7 916.1,122.6 916.6,122.2 917.5,121.7 918.2,121.6 917.3,122.2 916.8,122.6 916.3,122.8 915.8,123.2ZM912.8,124.1 912.3,124.3 911.8,124.4 911.1,124.8 910.5,125.1 910.1,125.2 909.2,125.9 908.6,126.3 908.0,126.7 908.3,126.2 908.7,125.9 909.0,125.4 909.5,125.2 909.9,124.8 910.5,124.5 910.9,123.9 911.3,124.3 912.0,124.2 912.8,123.7 913.3,123.5 913.3,123.9 912.8,124.1ZM918.3,86.1 918.5,85.7 918.3,86.1ZM883.2,97.0 883.8,97.1 883.4,97.5 883.0,97.9 882.6,98.2 882.0,97.7 881.3,97.8 881.6,97.2 882.1,96.8 883.1,96.9ZM881.0,96.9 880.7,97.4 880.3,97.3 879.9,97.4 880.5,97.0 881.0,96.9ZM907.5,128.5 908.0,128.3 907.5,128.5ZM906.1,126.4 906.5,126.6 907.1,126.6 906.4,127.0 906.0,127.0 905.3,127.5 904.9,127.9 904.4,128.2 904.3,128.7 904.0,128.3 904.6,127.8 904.9,127.4 905.4,127.0 905.9,126.4ZM845.3,181.9 845.1,182.3 844.7,182.1 845.3,181.7ZM844.1,182.6 843.7,182.5 844.3,182.3ZM848.5,181.3 848.0,181.3 848.0,180.9 848.5,181.3ZM856.3,176.0 855.9,176.2 855.4,176.5 855.3,176.9 855.0,177.3 854.6,177.5 854.8,176.9 855.0,176.5 855.4,176.1 855.8,175.9 856.2,175.6 856.3,176.0ZM858.3,173.0 858.1,172.5 858.4,172.9ZM859.6,171.6 859.1,171.7 859.2,171.2 859.6,171.1 860.0,170.9 859.8,171.4ZM862.8,165.9 862.3,165.9 862.5,165.4 862.9,165.6ZM863.8,165.6 863.7,165.1 863.9,164.5 864.0,164.9 863.8,165.6ZM857.4,158.9 857.8,159.0 857.6,159.5 857.4,158.9ZM861.3,160.5 861.2,159.8 861.6,159.6 861.7,160.2 861.3,160.5ZM862.2,159.9 861.8,159.9 862.4,159.7ZM870.5,149.4 870.1,149.5 870.3,149.1ZM884.3,144.9 884.2,144.5 884.2,144.0 884.6,143.6 884.6,144.1 884.7,144.7 884.3,144.9ZM887.4,133.1 887.5,132.7 887.5,133.1ZM891.9,124.1 891.6,123.7 891.9,124.1ZM892.5,124.7 892.1,124.6 892.5,124.5ZM874.8,154.8 874.2,155.0 874.3,154.5 874.7,154.1 874.7,154.5ZM858.5,158.8 858.4,158.4 858.6,158.0 858.8,158.4 858.5,158.8ZM859.7,157.7 859.8,157.3 859.7,157.7ZM859.1,155.2 858.9,154.7 859.3,154.8 859.1,155.2ZM859.4,154.6 859.2,154.1 859.6,153.7 859.7,154.1 859.4,154.6ZM868.3,155.2 867.9,155.3 868.3,155.2ZM867.4,155.7 867.9,155.8 867.4,155.9ZM857.6,153.3 857.0,153.3 857.4,152.8 857.6,153.3ZM855.7,153.3 855.3,153.5 855.1,153.1 855.5,153.1ZM850.9,157.7 850.5,157.5 850.9,157.1 851.9,156.8 852.5,156.9 852.5,157.3 852.0,157.6 851.6,157.7 850.9,157.7ZM850.6,154.5 850.6,154.1 851.0,154.0 850.9,154.4ZM851.4,145.2 851.0,145.1 851.4,145.0ZM838.5,162.5 837.6,162.4 837.0,162.1 836.7,161.7 837.4,161.8 838.4,162.4ZM839.7,166.8 839.3,166.7 838.8,166.5 839.2,166.3 839.7,166.5ZM839.4,167.6 839.0,167.4 839.4,167.6ZM832.8,179.3 832.5,178.9 832.9,178.9 832.8,179.3ZM806.6,191.4 807.0,191.4 807.0,191.8 806.5,191.7ZM798.9,191.1 798.5,191.4 798.5,191.0 798.9,191.1ZM798.7,191.9 798.3,191.9 798.7,191.6ZM797.3,192.4 797.1,192.0 797.3,192.4ZM789.1,221.1 789.1,221.6 789.0,222.1 788.8,221.5 788.8,221.0ZM784.5,216.7 784.1,216.3 784.5,216.6ZM788.8,246.3 788.4,246.5 787.9,246.3 788.4,246.0 788.8,246.1ZM789.5,242.4 789.4,242.0 789.5,242.4ZM778.6,235.3 778.3,234.9 778.7,235.0ZM777.4,232.0 777.5,232.5 777.1,232.6 776.8,232.2 777.3,232.1ZM776.8,231.9 776.8,231.4 776.8,231.9ZM778.0,223.4 777.7,223.8 777.6,223.4 778.0,223.4ZM773.9,228.0 773.7,227.5 773.9,228.0ZM773.4,228.0 773.1,228.4 772.9,228.0 773.1,227.4 773.3,227.9ZM760.8,231.0 760.3,230.6 760.2,230.2 760.6,229.9 760.9,230.6 760.8,231.0ZM760.4,229.6 760.0,229.7 760.4,229.6ZM759.6,228.1 759.2,227.9 759.6,228.1ZM759.8,227.6 759.7,227.2 759.8,227.6ZM756.9,220.7 756.5,220.1 757.0,219.7 757.1,220.3 756.9,220.7ZM757.5,218.4 757.3,218.0 757.5,218.4ZM757.6,218.0 757.2,217.4 757.0,217.0 757.3,216.6 757.4,216.1 757.6,215.7 757.6,215.2 757.7,214.8 757.6,214.4 757.9,213.9 757.9,213.2 758.1,212.5 758.5,212.4 758.5,212.8 758.5,213.2 758.3,213.6 758.1,214.0 758.3,215.2 758.0,216.0 757.8,216.4 757.7,216.8 757.7,217.3 757.7,217.7ZM773.7,219.6 773.7,219.2 773.7,219.6ZM772.8,219.6 773.0,220.1 772.7,219.7ZM772.8,218.1 772.9,217.7 773.0,217.3 773.0,217.8ZM773.7,216.9 773.5,216.5 773.9,216.8ZM772.4,217.5 772.2,217.1 772.4,217.5ZM773.8,217.4 773.7,217.9 773.3,217.5 773.7,217.2ZM772.4,215.6 772.0,215.7 772.4,215.6ZM773.4,215.0 773.5,215.4 773.1,215.7 773.1,215.2 773.1,214.8ZM773.1,213.6 773.1,214.1 772.9,213.6ZM771.0,204.9 770.7,204.3 771.1,204.2 771.0,204.9ZM763.3,206.1 763.4,205.7 763.3,206.1ZM762.4,205.7 762.5,205.3 762.4,205.7ZM760.2,198.1 759.7,197.6 760.4,197.6 760.3,198.0ZM759.7,194.7 759.8,195.1 759.5,194.6ZM758.4,194.7 758.1,194.2 758.4,194.7ZM760.3,195.7 760.8,195.9 760.8,196.3 760.4,196.3 760.2,195.9ZM755.4,190.3 755.2,189.7 755.4,190.3ZM753.2,188.4 753.0,187.4 753.2,187.9 753.2,188.4ZM754.3,187.8 753.9,187.6 754.0,187.2 754.3,187.7ZM752.2,188.6 751.7,188.7 751.9,188.0 751.8,187.4 751.6,187.0 751.4,186.6 751.9,186.6 752.0,187.1 752.4,187.5 752.3,188.4ZM721.9,224.9 721.5,224.7 721.9,224.9ZM475.1,53.2 474.7,53.2 475.1,53.0 476.3,52.7 476.8,52.4 477.8,52.3 477.8,52.7 476.9,52.8 476.0,52.9 475.1,53.2ZM67.8,197.2 67.0,197.0 67.0,196.2 66.8,195.6 66.5,195.1 66.9,194.7 67.0,194.0 67.7,194.0 68.9,194.5 69.3,195.1 69.9,195.6 69.9,196.0 69.3,196.3 68.6,196.5 68.0,196.9ZM63.3,191.1 63.9,191.1 64.6,191.2 63.8,191.4 63.1,191.4 63.2,191.0ZM65.3,191.9 65.7,191.8 66.3,192.0 66.7,192.3 66.4,192.7 66.0,192.7 65.5,192.8 65.3,192.2 64.8,191.9 65.2,191.7ZM55.1,189.3 55.3,188.8 55.1,189.2ZM61.7,190.4 62.1,190.7 61.7,190.9 61.2,190.8 60.8,190.8 60.5,190.3 61.1,189.7 61.5,190.1ZM57.3,189.1 56.9,189.2 56.5,189.0 56.1,188.8 56.7,188.3 57.4,188.3 57.4,188.7 57.3,189.1ZM64.3,192.3 63.9,192.2 64.3,192.0ZM998.5,107.3 998.0,107.3 997.0,106.8 996.5,106.7 997.0,106.6 997.5,106.9 998.0,107.2 998.4,107.2ZM999.2,105.8 998.7,105.9 999.0,105.5 999.4,105.6ZM992.8,105.9 992.4,105.9 993.0,105.6 993.2,105.2 993.3,105.6 992.8,105.9ZM982.6,104.6 981.7,104.4 982.4,104.2 982.6,104.6ZM980.0,102.7 980.5,102.8 981.2,102.9 981.8,103.2 981.4,103.3 981.0,103.3 980.4,103.5 979.9,103.3 979.4,103.0 980.0,102.7ZM20.2,82.1 20.8,82.2 21.2,82.2 21.6,82.5 21.1,82.4 20.5,82.4 19.6,82.0 19.2,82.0 19.7,81.6 20.2,82.1ZM27.3,91.2 26.8,91.2 27.5,91.0ZM54.6,96.3 54.2,96.4 54.6,96.2ZM53.7,96.3 54.1,96.6 53.6,96.7 53.2,96.4 53.6,96.1ZM49.2,97.6 48.8,97.5 49.2,97.3ZM48.5,98.9 48.0,98.9 48.5,98.9ZM55.9,96.9 55.5,97.1 55.1,97.4 55.1,96.9 55.5,96.7 55.9,96.5 55.9,96.9ZM56.9,96.8 56.5,97.0 56.7,96.6ZM28.6,103.2 28.1,103.3 28.5,103.1ZM25.7,103.9 26.1,103.7 25.7,103.9ZM39.3,99.8 38.8,99.9 38.6,99.5 39.0,99.4 39.5,99.6ZM40.1,99.6 40.0,99.2 40.4,99.4ZM33.4,101.8 32.6,102.1 32.1,102.5 31.7,102.7 31.3,102.9 30.4,103.2 30.8,102.9 31.2,102.6 31.4,102.1 32.1,102.1 32.2,101.6 32.8,101.3 33.4,101.2 33.8,101.4 33.4,101.8ZM37.2,100.3 37.7,100.2 38.3,100.2 37.5,100.6 38.0,100.8 37.4,101.1 36.9,101.3 36.5,101.5 35.7,101.6 35.3,101.8 34.9,101.9 34.5,102.0 33.9,101.9 34.3,101.7 34.9,101.6 35.5,101.4 35.9,101.0 36.4,100.8 36.8,100.8 36.4,100.7 36.0,100.6 36.0,100.2 36.5,100.1 36.9,100.0ZM38.3,100.8 38.6,100.4 38.3,100.8ZM20.9,104.8 20.5,104.8 20.9,104.5 21.4,104.6 20.9,104.8ZM17.9,105.2 18.5,105.3 19.1,105.3 18.4,105.4 17.6,105.4 17.1,105.4 16.7,105.3 17.2,105.3 17.6,105.2ZM14.8,105.5 13.3,105.6 14.1,105.3 14.8,105.2 15.4,105.0 15.8,105.0 15.7,104.6 16.2,104.4 16.6,104.6 16.2,104.9 15.7,105.3 14.8,105.5ZM11.1,106.1 10.5,106.0 10.9,106.0ZM9.5,105.9 9.9,106.1 9.0,106.6 8.4,106.7 8.7,106.1 9.1,106.0 9.2,105.6 9.6,105.7ZM11.1,105.5 10.7,105.5 11.1,105.4ZM7.9,106.3 7.3,106.4 6.7,106.4 7.4,106.2 7.8,106.0 8.2,105.8 7.9,106.3ZM5.9,106.5 5.4,106.5 5.4,106.1 5.0,105.9 5.7,105.8 6.1,106.0 6.5,106.0 6.0,106.4ZM704.7,397.6 704.1,397.7 703.7,397.3 704.4,397.3 704.8,397.5ZM692.4,386.3 692.1,385.9 692.7,385.8 692.6,386.2ZM644.0,379.0 643.6,379.0 644.0,379.0ZM605.2,380.4 604.5,380.4 605.0,380.1ZM577.3,150.2 577.1,149.8 577.2,149.4 577.5,149.0 578.3,148.8 578.2,149.4 578.0,149.8 577.3,150.2ZM575.5,151.5 575.2,151.1 575.4,150.6 575.4,151.0 575.6,151.4ZM575.1,147.3 574.7,147.0 575.1,147.2ZM574.9,148.0 575.6,147.5 576.0,147.6 575.5,147.8 575.1,147.9ZM571.0,147.3 570.5,147.3 570.9,146.7 571.1,147.1ZM570.2,147.0 569.8,147.2 570.1,146.8ZM574.5,145.0 574.9,145.1 574.4,145.4 573.9,145.2 574.3,145.0ZM572.3,145.8 572.8,145.4 573.2,145.4 572.8,145.6 572.3,145.8ZM571.8,147.8 572.2,147.4 571.8,147.8ZM573.5,148.4 573.1,148.6 573.2,148.2ZM567.9,146.3 567.9,145.9 567.9,146.3ZM568.2,147.9 567.6,148.2 568.2,147.9ZM569.4,145.1 568.9,144.9 568.8,144.5 569.3,144.7 569.4,145.1ZM570.2,145.6 569.6,145.5 570.1,145.5ZM572.5,143.8 571.9,143.8 572.1,143.3 571.8,142.8 572.3,142.8 572.7,142.9 572.7,143.6ZM573.4,140.8 573.7,141.2 573.8,141.6 573.3,141.7 572.7,141.6 572.9,141.2 572.4,141.4 572.0,141.3 572.0,140.9 572.5,140.8 573.2,140.6ZM566.1,141.3 565.7,141.4 566.1,141.3ZM568.5,142.2 567.9,142.0 568.3,142.0ZM571.3,137.7 570.7,137.5 571.2,137.5ZM570.7,138.9 570.4,139.4 570.0,139.3 569.6,139.3 569.6,138.9 570.1,138.9 570.5,138.8ZM568.8,137.2 568.1,137.1 568.4,136.7 568.8,136.9ZM572.1,138.5 571.5,138.6 571.9,138.2ZM455.2,219.3 455.3,218.9 455.2,219.3ZM454.5,195.3 454.5,194.9 454.5,195.3ZM452.2,158.7 452.6,158.8 453.0,158.8 453.4,159.0 452.7,159.3 452.3,159.1 452.2,158.7ZM454.6,171.2 454.4,171.8 454.0,172.1 453.3,171.8 453.2,171.4 454.0,171.1 454.7,170.7 455.2,170.6 454.6,171.2ZM461.9,169.7 461.5,169.8 461.6,169.4 462.1,169.1 462.5,168.9 462.6,169.4 461.9,169.7ZM460.6,171.8 460.2,172.1 459.8,172.0 460.5,171.6 460.7,171.1 461.0,170.5 461.4,170.2 461.6,170.6 461.5,171.1 461.3,171.5 460.6,171.8ZM465.2,229.3 464.0,229.0 465.0,228.8 465.2,229.3ZM457.2,171.8 457.3,172.2 457.3,172.6 456.8,172.9 456.4,172.8 456.1,172.2 456.4,171.8 457.1,171.8ZM430.1,202.9 429.7,203.0 429.6,202.6 430.2,202.2 430.6,202.5 430.1,202.9ZM430.9,203.3 430.5,203.3 430.9,203.2ZM452.3,172.2 451.9,171.9 452.4,171.8 452.3,172.2ZM455.8,218.2 455.7,217.8 455.8,218.2ZM455.6,217.0 455.1,217.1 455.5,217.0ZM450.3,172.8 450.0,173.2 449.6,173.0 450.2,172.6ZM436.3,204.9 436.7,204.9 436.9,205.4 436.4,205.6 436.3,205.1ZM432.5,208.7 432.1,208.8 432.0,208.4 432.4,208.3 432.5,208.7ZM450.5,170.9 450.0,170.1 450.6,169.9 450.7,170.3 450.5,170.9ZM433.1,203.8 432.7,203.9 432.2,203.8 432.6,203.8 433.1,203.8ZM436.4,203.7 436.1,203.3 436.4,203.7ZM435.6,208.0 435.4,207.6 435.8,207.6 435.6,208.0ZM434.9,208.3 434.3,208.5 433.9,208.1 434.0,207.7 434.5,207.9 434.9,208.3ZM623.5,283.6 623.7,284.2 623.3,284.0 622.8,283.8 623.2,283.8ZM621.6,284.2 621.2,284.1 621.6,284.2ZM625.5,286.0 625.3,285.5 625.3,285.1 625.6,285.6 625.5,286.0ZM620.7,283.1 620.3,282.9 620.2,281.8 620.5,282.3 620.8,282.9ZM610.3,272.2 610.3,271.8 610.7,271.5 610.6,271.9ZM613.9,256.0 614.3,255.7 613.9,256.0ZM609.7,267.2 609.9,267.7 609.5,267.6 609.0,267.4 608.9,266.9 608.9,266.5 609.2,265.9 609.4,266.5 609.5,267.0ZM660.1,306.9 659.4,307.0 659.3,306.5 659.5,306.1 659.8,305.7 660.2,305.5 660.5,306.1 660.5,306.5 660.1,306.9ZM655.0,309.3 654.6,309.4 653.8,309.1 653.4,308.5 653.6,308.1 654.0,308.0 654.4,308.0 654.8,308.4 655.1,309.1ZM634.3,287.1 633.9,287.2 633.9,286.8 634.3,287.0ZM638.7,297.0 638.4,297.5 638.5,297.0 638.8,296.4 638.7,297.0ZM610.7,263.6 610.7,264.3 610.5,265.0 610.1,264.9 610.3,264.2 610.2,263.7 610.7,263.6ZM654.3,263.0 653.9,262.9 654.3,263.0ZM524.3,239.6 524.7,239.6 524.4,240.6 524.2,241.0 523.5,240.9 523.5,240.5 524.0,240.1 524.1,239.6ZM518.5,249.7 518.1,249.8 518.0,249.4 518.4,248.9 518.7,249.3 518.5,249.7ZM520.3,237.7 519.8,237.8 520.1,237.4ZM377.4,314.3 376.9,314.5 377.1,314.1ZM365.0,323.3 365.2,322.7 365.2,323.1ZM365.3,327.1 365.2,326.6 365.4,326.2 365.5,326.6 365.3,327.0ZM374.3,316.4 373.9,316.5 374.2,315.9 374.3,316.3ZM531.3,153.5 530.9,153.7 531.3,153.3ZM530.4,156.3 530.0,156.3 529.8,155.9 530.3,155.9 530.7,156.2ZM487.7,99.5 487.2,99.8 486.8,99.8 486.9,99.4 487.5,99.0 488.0,99.3ZM481.6,92.8 482.0,92.6 481.6,92.8ZM485.8,96.0 485.3,96.0 485.0,95.5 485.4,95.2 485.8,95.6 485.8,96.0ZM484.0,93.5 482.8,93.6 482.4,93.6 482.8,93.5 482.9,93.1 482.5,92.9 482.9,92.6 483.3,92.8 483.8,93.0 484.0,93.5ZM483.0,94.6 483.1,95.0 483.1,95.4 482.6,95.5 482.5,95.1 482.1,95.3 482.1,94.9 482.5,94.8 483.0,94.6ZM483.4,95.0 483.6,94.5 483.9,94.1 483.4,95.0ZM497.1,81.9 497.0,81.3 497.1,81.7ZM496.4,81.8 496.8,82.2 496.9,82.8 496.7,83.3 496.4,83.7 496.4,83.2 496.3,82.8 495.9,82.9 495.5,82.7 496.2,82.4 496.0,82.0 495.6,82.0 496.1,81.7ZM497.8,81.1 497.4,81.4 497.7,81.0ZM491.2,86.7 490.6,86.6 491.0,86.5ZM488.3,101.9 488.8,101.9 488.3,102.2 487.9,102.4 487.4,102.1 487.3,101.7 488.0,101.6ZM492.9,85.5 493.3,85.3 492.9,85.5ZM491.5,86.0 492.0,86.2 491.2,86.3 490.8,86.2 490.8,85.7 491.2,85.7 491.6,85.9ZM481.6,78.3 481.2,78.3 481.6,78.3ZM481.4,79.3 480.9,79.1 481.3,79.0ZM480.0,77.4 480.4,77.6 479.8,77.7 479.4,77.4 479.9,77.4ZM481.6,77.1 481.5,77.5 481.0,77.4 481.3,77.8 480.5,77.5 480.1,77.0 480.7,76.9 481.1,77.0 481.6,77.1ZM492.4,85.6 491.8,85.3 492.2,85.4ZM482.2,77.1 481.8,77.2 481.8,76.8 482.2,77.1ZM482.8,87.9 482.4,88.4 481.8,88.6 482.2,88.7 481.7,89.1 481.1,89.4 480.7,89.6 480.3,89.4 480.7,89.3 480.4,88.9 480.3,88.5 480.7,88.3 481.1,88.3 481.8,87.8 482.5,87.5 482.8,87.9ZM482.6,91.8 482.1,91.6 482.6,91.6ZM482.9,90.3 482.9,90.7 483.7,90.9 484.2,90.9 483.9,91.3 483.5,91.5 483.2,91.1 482.6,91.2 482.1,90.8 481.5,90.7 481.4,90.3 482.3,90.0 482.9,90.0ZM480.0,89.8 480.0,90.2 479.1,90.0 479.5,89.9 480.0,89.8ZM479.9,91.3 479.5,91.3 479.4,90.8 479.8,90.6 479.9,91.3ZM472.4,100.2 472.0,100.1 471.5,100.1 472.2,100.0ZM479.4,91.8 479.0,91.7 479.4,91.7ZM561.1,82.4 560.6,82.3 561.0,82.2ZM554.6,82.8 554.2,82.8 554.6,82.8ZM555.5,82.4 556.0,82.5 555.6,82.9 555.0,83.1 554.6,82.7 555.0,82.7 555.0,82.3 555.4,82.3ZM558.9,74.3 559.3,74.3 558.7,74.4ZM553.2,89.1 553.6,89.0 553.2,89.1ZM545.9,93.6 545.5,93.1 546.2,92.0 546.8,91.4 547.1,91.0 547.5,90.7 547.4,91.1 546.9,91.7 546.8,92.1 545.9,93.6ZM541.9,97.2 541.3,97.1 540.8,96.9 541.0,96.4 542.0,96.8 541.9,97.2ZM538.1,98.9 537.4,99.1 536.6,99.1 536.6,98.6 536.8,98.2 537.3,98.1 537.9,98.4 537.8,98.8ZM534.9,97.3 534.3,97.3 533.8,97.5 534.1,97.0 534.5,97.1 534.9,97.3ZM535.2,95.6 534.8,95.5 535.2,95.6ZM531.6,97.5 532.1,97.7 532.6,97.8 532.4,98.2 531.8,98.3 530.7,97.9 530.7,97.5 531.3,97.4ZM529.1,97.6 528.7,97.6 528.3,97.3 528.7,97.5 529.2,97.6ZM527.9,97.5 527.5,97.5 527.1,97.1 527.8,97.3ZM529.8,97.9 529.7,97.5 530.2,97.1 529.9,97.8ZM529.5,95.0 529.2,94.6 529.6,94.8ZM531.3,98.8 530.9,98.8 531.3,98.8ZM530.7,91.0 530.2,90.9 530.8,90.7ZM523.1,97.8 523.3,97.1 523.3,97.5 524.0,97.5 523.2,97.6ZM496.7,122.5 496.2,122.3 496.7,122.5ZM497.0,109.2 496.5,109.5 495.8,109.3 496.2,109.1 496.8,109.1ZM513.6,102.6 513.1,102.7 513.6,102.3ZM528.9,131.0 528.9,131.4 528.4,131.3 528.8,131.0ZM523.6,141.5 523.2,141.6 523.6,141.5ZM511.9,139.3 511.0,139.0 511.7,138.8 512.0,139.2ZM504.4,142.6 503.9,142.6 504.4,142.5ZM504.0,141.9 503.5,142.0 503.6,141.6 504.3,141.3 504.2,141.9ZM413.5,140.5 413.1,140.6 413.3,140.2ZM424.8,142.7 424.2,142.6 424.3,142.2 424.9,142.4ZM422.8,142.9 422.0,142.7 421.4,142.4 422.3,142.7 422.8,142.9ZM420.4,143.0 419.9,142.8 420.3,142.7ZM421.8,143.2 421.3,143.3 420.7,143.2 421.1,142.9 421.8,143.2ZM428.8,144.9 429.8,144.9 429.3,145.2 428.5,145.1 428.2,144.7 428.8,144.9ZM540.5,150.4 540.1,150.5 539.9,150.1 540.4,150.3ZM542.3,127.6 541.9,127.4 542.3,127.6ZM541.1,125.1 540.6,125.0 540.1,124.8 540.5,124.4 540.8,124.8ZM541.2,125.7 540.8,125.6 541.2,125.7ZM542.2,126.8 541.8,126.7 541.4,126.4 541.0,125.9 541.4,126.1 542.0,126.6ZM542.2,128.0 541.4,127.4 542.2,128.0ZM540.2,125.9 539.8,125.3 539.8,124.9 539.8,124.5 540.0,124.9 540.1,125.4 540.2,125.8ZM548.9,131.2 549.3,131.4 548.2,131.1 548.9,131.2ZM557.3,143.4 557.5,143.8 557.7,144.2 557.2,144.1 556.8,143.8 557.1,143.3ZM558.0,145.0 557.8,145.4 557.3,144.9 557.7,144.9ZM557.5,142.8 557.1,142.8 557.2,142.3 557.6,142.2 557.5,142.7ZM557.7,143.5 557.3,143.1 557.7,143.5ZM555.8,140.5 555.2,140.4 555.0,140.0 554.6,139.7 555.1,139.4 555.1,139.8 555.4,140.3 555.8,140.5ZM564.0,149.5 563.6,149.4 563.7,149.0 564.2,149.3ZM511.0,106.3 510.6,106.4 511.0,106.3ZM358.3,56.8 357.9,56.9 357.5,56.5 357.4,56.1 357.8,55.8 358.5,55.8 359.2,56.0 358.6,56.2 358.4,56.6ZM397.1,68.0 396.7,68.0 396.6,67.5 397.1,67.4 397.3,67.8ZM351.3,52.7 350.3,52.5 350.8,52.0 351.2,52.0 351.6,52.3 351.3,52.7ZM347.2,47.8 346.8,48.0 345.8,48.4 345.3,48.3 344.9,48.2 344.3,48.2 343.8,48.0 344.2,47.9 345.4,47.8 345.8,47.8 346.6,47.7 347.1,47.7ZM356.5,53.2 356.1,53.2 355.2,53.1 355.6,52.8 356.1,52.9 356.6,53.1ZM371.5,81.2 370.8,81.4 370.4,81.2 370.0,81.2 371.1,80.8 371.7,80.7 371.5,81.2ZM158.9,114.8 158.4,114.9 158.9,114.8ZM158.8,115.5 158.7,115.1 158.8,115.5ZM158.3,115.3 157.9,115.3 158.3,115.2ZM159.5,116.2 159.7,116.6 160.1,116.7 159.7,116.7 159.4,116.3 159.0,116.0 159.4,115.6 159.2,116.0ZM155.1,112.4 154.6,112.3 154.2,112.0 153.8,111.8 154.4,111.9 155.1,112.4ZM157.3,114.2 156.8,114.0 156.4,113.6 157.0,114.0ZM157.1,114.6 156.7,114.1 157.2,114.5ZM152.8,111.0 152.8,110.6 152.8,111.0ZM148.2,112.2 147.7,112.1 147.5,111.7 148.0,111.5 148.3,112.0ZM152.3,110.8 151.8,110.2 152.2,110.0 152.5,110.4 152.3,110.8ZM171.3,158.8 170.9,158.8 170.6,158.3 171.2,158.8ZM171.6,169.6 171.1,169.1 171.5,169.2 171.6,169.6ZM166.5,155.8 166.0,155.5 166.5,155.5ZM167.0,155.3 167.6,155.5 167.2,155.6ZM171.3,157.3 170.9,157.3 171.3,157.3ZM294.1,139.8 293.8,140.2 294.2,139.6ZM293.9,137.4 294.3,137.1 293.9,137.4ZM320.2,160.3 319.8,160.3 320.3,160.0ZM304.1,135.1 303.4,135.2 303.8,134.8ZM302.1,134.7 302.0,134.3 302.1,134.7ZM305.6,135.4 304.9,135.3 305.3,135.2ZM289.5,152.3 289.0,152.4 289.5,152.3ZM290.7,144.8 291.0,144.2 291.3,143.8 291.1,144.2 290.7,144.8ZM290.2,152.1 289.8,152.2 290.2,152.0 290.3,151.4 290.3,150.7 290.4,151.2 290.3,152.0ZM287.5,153.8 288.2,153.0 287.5,153.8ZM231.2,171.8 231.9,171.3 231.2,171.8ZM229.6,174.2 229.7,173.5 230.2,172.8 229.9,173.5 229.6,174.2ZM236.0,169.0 236.5,168.6 236.0,169.0ZM230.1,177.3 229.8,176.9 229.4,175.5 229.4,174.7 229.6,175.6 230.0,176.9 230.1,177.3ZM264.1,167.7 263.6,167.7 264.2,167.6 264.6,167.4 264.1,167.7ZM273.8,164.0 273.7,164.6 273.7,164.2ZM252.2,166.4 251.8,166.5 252.3,166.2ZM245.0,168.1 244.5,167.9 245.0,167.8ZM253.3,167.2 253.1,166.5 253.3,166.9ZM284.3,182.6 284.2,183.0 284.6,183.0 284.6,183.4 284.5,184.1 284.0,184.0 283.7,183.2 283.3,182.7 283.7,182.7 284.2,182.5ZM284.3,189.0 283.9,189.0 283.6,188.6 284.0,188.7 284.4,188.7ZM283.3,188.1 282.8,188.0 283.2,188.0ZM281.6,187.4 282.0,187.4 282.4,187.4 281.8,187.6 281.4,187.4ZM285.5,178.0 285.0,177.7 285.4,177.3 285.5,176.8 285.5,176.4 284.7,175.4 283.9,175.3 283.5,175.3 283.9,175.2 284.6,175.3 285.1,175.7 285.7,176.1 286.0,176.8 285.6,177.1 285.6,177.9ZM294.3,186.9 293.8,186.9 294.3,186.9ZM293.3,183.1 292.9,183.4 293.0,183.0ZM297.1,191.1 296.8,191.7 296.1,191.8 295.4,191.8 295.4,191.4 295.8,191.1 296.4,191.2 296.8,191.1 297.2,190.8ZM293.9,188.3 294.3,187.8 294.7,187.4 294.5,187.0 294.9,186.9 294.9,187.4 294.5,187.9 293.9,188.3ZM287.1,179.2 287.5,179.5 287.9,179.6 288.4,180.0 288.5,180.9 288.4,181.5 288.1,181.1 288.3,180.7 288.4,180.2 287.9,179.7 287.5,179.6 287.0,179.3ZM289.8,184.9 289.0,184.5 289.4,184.6 289.8,184.9ZM292.1,186.4 291.7,185.9 291.3,185.8 291.3,185.4 291.0,184.8 290.8,184.3 291.2,184.9 291.5,185.7 292.1,186.1ZM290.8,182.8 290.4,182.9 290.3,182.4 290.0,182.0 289.6,181.5 290.2,182.1 290.8,182.8ZM283.7,188.5 283.3,188.4 283.8,188.5ZM272.1,176.5 271.7,176.4 272.1,176.5ZM284.0,181.4 284.1,182.0 283.7,182.2 283.2,182.5 282.9,182.0 282.3,181.8 282.7,181.5 282.8,180.8 282.7,180.0 283.2,180.2 283.6,180.7 283.8,181.1ZM277.3,174.2 276.7,173.2 276.6,172.6 276.8,173.1 277.3,174.2ZM270.7,190.1 269.9,190.4 269.3,190.4 268.9,190.0 269.3,190.1 269.2,189.5 269.5,189.1 270.1,189.1 270.5,189.5 270.6,189.9ZM272.0,176.2 271.8,175.8 272.0,176.2ZM279.6,187.0 279.1,186.9 279.5,187.0ZM282.0,175.8 282.5,175.8 283.1,175.8 283.5,175.7 282.7,176.0 281.9,176.2 281.5,176.4 281.1,176.3 280.6,175.9 281.1,176.2 281.6,175.9 282.0,175.8ZM276.7,180.2 276.2,180.7 276.4,180.3 276.8,179.9ZM255.7,200.2 255.8,199.6 255.7,200.2ZM256.0,201.6 255.8,202.0 255.8,201.6ZM245.3,198.1 245.7,197.9 245.3,198.1ZM274.0,196.3 274.6,196.3 274.2,196.5 273.8,196.2ZM258.5,193.6 258.4,193.1 258.8,192.9 258.5,193.6ZM326.0,202.1 325.6,201.9 326.0,202.1ZM330.8,211.6 330.4,211.7 330.4,211.3 330.7,210.9 330.8,211.6ZM331.0,209.7 330.4,209.8 330.5,209.4 329.9,208.9 330.5,208.8 330.7,209.2 331.0,209.7ZM330.5,221.9 330.1,222.0 328.9,222.0 328.4,222.0 328.0,222.0 328.7,221.7 329.1,221.5 329.3,220.7 328.8,220.3 329.3,220.1 330.1,220.0 330.8,219.9 330.5,220.4 330.5,220.9 330.6,221.3 330.5,221.8ZM310.5,216.3 310.1,216.0 310.5,216.0ZM331.2,218.9 331.8,218.5 331.2,218.9ZM334.7,213.7 334.3,213.5 334.3,213.0 334.8,213.3 334.7,213.7ZM322.6,219.1 322.7,219.5 322.2,219.8 321.8,219.6 321.2,219.6 321.5,219.2 321.9,219.4 322.5,219.0ZM325.0,199.4 324.6,199.5 325.1,199.3ZM328.6,216.6 328.6,216.2 328.6,216.6ZM328.5,201.2 328.1,200.9 328.5,200.9ZM329.9,205.9 329.9,205.5 329.9,205.9ZM328.6,202.7 328.2,202.7 328.3,202.3 328.7,202.5ZM329.8,207.6 329.4,207.2 329.3,206.7 329.7,206.7 329.9,207.3ZM329.6,204.9 329.1,204.9 329.1,204.4 329.5,204.4 330.1,204.8 329.6,204.9ZM328.9,205.5 328.4,205.4 328.4,204.7 328.8,204.6 329.0,205.4ZM330.1,213.5 329.8,213.1 330.2,212.9 330.2,213.3ZM297.1,187.7 297.7,187.8 297.3,187.9 296.9,187.9ZM309.0,216.5 308.3,216.3 307.9,215.8 308.3,216.0 308.8,216.2ZM300.9,189.5 300.5,189.5 300.9,189.4ZM297.5,190.3 297.1,190.4 297.5,190.3ZM300.3,189.3 299.9,189.1 300.3,189.3ZM299.1,189.3 299.6,189.4 299.2,189.6ZM53.0,87.3 52.6,87.3 52.8,86.8 53.4,86.7 53.0,87.3ZM75.0,91.3 74.6,91.4 74.2,91.7 74.0,91.3 75.2,91.2ZM77.7,82.3 77.9,81.9 77.8,82.3ZM76.4,87.5 76.0,87.4 76.5,87.2ZM74.3,89.3 73.7,89.1 74.2,89.2ZM67.9,94.9 67.4,95.0 67.8,94.7ZM70.3,93.2 70.8,92.8 70.3,93.2ZM93.4,82.1 93.9,82.1 93.3,82.4 92.8,82.6 92.5,82.2 92.9,82.0 93.4,82.1ZM96.9,82.4 96.4,82.4 96.9,82.2ZM98.4,83.8 98.8,83.5 99.3,83.3 98.9,83.6 98.4,83.8ZM89.6,83.9 89.2,83.8 89.6,83.5 90.0,83.2 90.4,83.1 91.1,82.5 91.6,82.4 90.8,83.1 90.4,83.4 90.0,83.7 89.6,83.9ZM88.8,83.2 88.1,83.2 88.7,82.9 89.1,83.1ZM89.8,82.1 89.7,82.6 89.3,82.7 89.3,82.3 89.7,82.0ZM89.1,81.0 88.7,81.1 89.1,80.9ZM130.9,97.5 131.4,97.4 131.5,97.9 131.1,98.0 130.5,97.6 130.0,97.0 129.4,96.6 130.3,96.6 130.6,97.1 130.9,97.5ZM127.8,92.1 128.3,92.3 128.4,92.8 128.1,93.4 128.0,93.9 127.6,94.1 127.1,93.9 127.2,93.3 127.0,92.8 126.7,92.4 127.0,91.9 127.4,91.9 127.8,92.0ZM129.7,95.7 129.4,96.1 129.0,96.3 128.5,95.8 129.0,95.8 129.6,95.7ZM135.2,97.0 135.5,97.4 135.0,97.5 134.8,97.1 134.5,96.7 135.0,96.6 135.2,97.0ZM131.3,93.0 130.9,93.0 130.9,92.5 131.5,92.5 131.6,92.9ZM133.0,94.1 133.0,94.6 132.5,94.6 132.1,94.3 131.7,94.3 131.4,93.9 131.8,93.5 132.3,93.1 132.8,93.4 133.2,93.8ZM131.2,93.8 130.5,93.5 131.4,93.2 131.5,93.6ZM126.9,88.3 126.5,88.3 126.1,88.2 126.7,88.1ZM141.8,104.1 141.4,104.0 141.0,103.6 141.7,103.7 141.8,104.1ZM136.3,98.7 136.3,98.3 136.7,98.3 136.3,98.7ZM136.0,105.7 135.8,105.3 136.0,105.7ZM138.2,100.1 137.8,100.4 137.4,100.2 137.0,100.4 137.1,100.0 137.5,99.8 138.0,99.9ZM139.3,102.3 138.5,101.8 138.0,101.6 137.5,101.3 138.4,101.3 138.8,101.4 139.6,102.1ZM144.7,107.0 144.3,107.0 144.0,106.6 144.4,106.4 144.7,106.9ZM143.4,104.4 143.2,104.0 143.2,103.6 143.8,103.4 143.6,104.0 143.5,104.4ZM140.8,102.8 140.3,102.5 140.7,102.6ZM141.2,102.5 140.8,102.4 141.0,102.0 141.2,102.5ZM113.8,56.7 113.2,56.7 113.7,56.5 114.1,56.6ZM330.6,225.4 330.7,224.8 330.8,225.3ZM277.4,258.3 277.0,258.3 277.1,257.8 277.6,257.4 278.0,257.6 277.6,257.9 277.4,258.3ZM356.0,254.0 356.1,253.3 356.5,252.4 356.8,251.8 357.2,251.6 357.6,251.5 357.6,252.8 357.0,253.4 356.6,253.7 356.0,254.0ZM331.1,224.6 330.7,224.7 331.1,224.4ZM376.4,258.2 376.2,257.7 376.4,258.2ZM392.4,286.4 392.5,286.0 392.4,286.4ZM375.3,253.7 374.9,253.8 375.3,253.5ZM361.8,249.3 361.6,250.0 361.1,250.1 360.3,249.9 360.4,249.4 360.8,249.4 361.4,249.2 361.8,249.3ZM360.3,244.6 359.8,244.7 359.7,244.1 360.1,244.0 360.3,244.5ZM359.3,250.4 358.5,250.9 358.3,250.5 358.8,250.1 359.3,250.2ZM362.7,250.3 361.9,250.4 361.9,250.0 362.5,249.8 362.9,250.0ZM359.9,249.6 359.9,250.0 359.4,249.8 359.4,249.4 359.9,249.1 359.9,248.5 360.2,248.9 360.2,249.3ZM360.7,248.9 360.3,248.9 360.4,248.4 360.8,248.3 360.7,248.9ZM249.1,252.1 248.5,251.9 248.7,251.4 249.3,251.3 249.5,251.8 249.1,252.1ZM251.6,252.5 251.2,252.6 251.4,252.2 251.9,251.9 251.8,252.3ZM273.3,229.6 272.9,229.5 272.6,229.1 273.0,228.8 273.0,229.2 273.3,229.6ZM246.0,251.3 245.5,251.2 245.4,250.8 245.9,250.7 246.1,251.2ZM246.5,249.9 246.7,250.6 247.3,251.2 247.3,251.6 247.8,252.1 247.5,252.6 246.9,252.8 246.2,252.8 245.8,252.4 246.3,252.0 246.8,251.7 246.5,251.0 246.0,250.1 245.6,250.0 246.2,249.7ZM248.4,250.9 247.8,250.9 247.7,250.5 248.1,250.5 248.5,250.8ZM196.4,325.4 196.0,325.5 196.5,325.3ZM204.2,190.0 203.8,190.0 204.2,190.0ZM191.9,197.9 191.5,197.8 191.9,197.9ZM192.9,180.5 193.0,180.9 192.6,180.6ZM195.0,182.9 194.7,182.4 195.0,182.8ZM185.7,169.3 184.7,168.6 184.5,168.2 185.0,168.1 185.1,168.5 185.6,168.6 185.6,169.1ZM180.1,172.0 179.6,171.9 179.8,171.3 180.1,171.7ZM189.7,182.2 188.9,181.9 189.3,181.8 189.7,182.2ZM191.4,177.7 191.1,178.2 191.2,177.7ZM188.3,169.4 188.1,170.1 187.5,169.9 187.6,169.0 188.1,168.8 188.3,169.4ZM188.7,181.8 188.4,181.3 188.3,180.7 188.4,179.8 188.3,180.9 188.5,181.3 188.7,181.7ZM848.0,222.3 847.6,221.8 848.0,222.3ZM841.5,229.4 841.1,229.7 841.4,229.3ZM849.4,230.7 849.2,230.0 849.4,230.7ZM849.9,223.4 849.9,222.9 849.9,223.4ZM846.1,218.1 845.6,218.0 845.4,217.6 845.9,217.5 846.2,217.9ZM855.5,258.2 855.1,258.4 855.5,258.2ZM854.5,259.2 854.1,259.1 854.5,259.2ZM835.5,269.8 835.1,269.8 835.5,269.6ZM841.6,273.7 842.0,273.5 841.6,273.7ZM826.5,273.2 826.4,272.7 826.8,272.6 826.5,273.2ZM694.5,65.3 694.0,65.3 693.5,65.1 693.0,64.7 693.4,64.6 693.9,64.6 694.7,64.7 694.7,65.1ZM769.0,38.3 769.0,37.9 769.4,37.9 769.0,38.3ZM778.2,28.9 777.5,28.9 778.0,28.6 778.6,28.7 778.2,28.9ZM948.5,58.6 948.2,58.2 947.7,58.1 947.7,57.4 947.5,57.0 948.1,56.8 948.6,56.6 948.7,57.1 948.3,57.2 948.3,57.8 948.6,58.3ZM860.3,162.1 860.5,161.7 860.3,162.1ZM815.4,186.7 815.0,186.4 815.4,186.7ZM813.3,190.0 813.2,189.6 813.3,190.0ZM816.7,188.3 816.3,188.3 816.8,188.0ZM523.9,98.0 523.5,98.1 523.9,98.0ZM259.5,52.7 260.2,52.7 260.8,52.6 261.6,52.4 262.1,52.4 263.6,52.4 264.3,52.8 264.7,51.8 264.8,51.3 264.3,50.9 263.8,51.0 263.2,50.9 262.8,50.8 262.2,50.4 261.6,50.1 262.0,49.8 262.4,49.7 262.8,49.4 263.8,49.4 265.0,49.6 265.7,49.9 264.9,49.5 264.5,49.3 264.9,49.0 264.3,48.9 263.7,48.9 262.9,48.8 262.5,48.6 262.1,48.2 262.2,47.7 262.6,47.4 263.2,47.3 263.9,47.4 266.0,47.8 263.6,47.2 262.8,47.1 263.8,46.3 265.0,46.1 265.5,46.0 266.4,45.9 267.3,46.1 266.9,45.8 267.4,45.6 268.3,45.5 269.4,45.3 269.9,45.2 270.4,45.2 271.7,45.2 272.4,45.2 273.3,45.3 273.9,45.5 274.3,45.9 274.6,46.4 275.5,46.6 275.9,46.8 276.1,47.2 276.4,47.6 277.0,47.9 276.6,48.1 275.9,48.4 275.0,48.8 274.4,49.1 275.7,48.7 276.1,48.7 275.8,49.1 275.2,49.4 275.9,49.7 275.4,49.7 275.2,50.1 275.6,50.2 276.7,49.6 277.3,49.4 278.0,49.5 277.5,49.2 278.0,48.8 278.4,48.8 278.9,49.1 279.4,49.1 280.0,49.0 280.6,49.2 280.5,49.7 281.2,50.2 281.6,50.3 281.1,49.9 281.1,49.3 281.7,49.1 282.1,49.2 282.5,49.2 283.0,49.2 284.1,49.5 284.7,49.5 284.2,49.3 283.5,49.2 282.5,49.0 282.1,48.8 282.2,48.4 283.3,48.1 284.0,48.0 284.5,48.0 285.4,48.0 286.4,48.0 287.0,48.1 287.6,48.2 288.4,48.4 289.0,48.4 289.4,48.4 290.9,48.7 291.3,48.9 291.6,49.3 290.6,49.9 290.2,50.0 289.5,50.5 289.1,50.7 289.7,50.4 290.5,50.0 291.3,49.8 291.9,49.7 292.5,49.7 293.0,49.8 293.4,49.8 293.9,50.1 292.7,50.6 292.3,50.7 291.1,50.8 291.8,50.9 292.5,50.9 292.1,51.2 292.1,51.7 291.6,52.1 292.3,51.8 292.8,51.2 293.3,50.9 294.1,50.9 294.5,50.7 295.0,50.6 294.8,51.1 293.9,51.7 294.3,51.6 294.9,51.3 295.5,51.3 295.9,51.4 296.5,51.9 296.4,51.4 297.5,50.9 298.0,51.0 298.4,51.1 299.0,51.1 299.7,51.1 300.3,51.2 301.0,51.3 301.5,51.5 301.9,51.7 302.3,52.0 301.7,52.4 301.1,52.5 300.4,52.5 299.9,52.6 299.2,52.9 298.8,53.1 298.3,53.2 299.1,53.2 299.6,52.9 300.0,52.7 300.7,52.6 301.7,52.8 302.3,52.8 302.7,52.6 303.1,52.5 303.7,52.6 303.8,53.0 303.4,53.4 302.7,53.7 302.2,53.8 301.7,53.9 301.2,54.0 300.8,54.2 300.3,54.4 300.8,54.5 301.2,54.2 301.6,54.0 302.0,54.2 301.6,55.2 302.7,54.1 303.2,53.8 304.0,53.5 304.6,53.4 305.3,53.3 305.7,53.2 306.1,53.2 306.8,53.4 307.2,53.4 307.9,53.4 308.6,53.6 309.7,53.9 310.1,54.1 309.6,54.4 308.9,54.7 308.1,54.8 307.5,54.8 307.1,54.9 306.4,55.0 305.4,55.4 305.8,55.5 306.6,55.2 307.0,55.1 307.7,55.0 308.6,55.0 309.0,55.0 308.3,55.6 309.0,55.7 309.5,55.5 310.0,55.4 310.5,55.2 310.5,54.8 310.9,54.7 311.5,54.8 311.9,54.9 312.9,55.5 313.3,55.9 313.3,56.3 311.7,56.2 311.1,56.2 310.6,56.3 310.1,56.5 309.3,56.5 308.8,56.6 308.0,56.7 307.6,56.9 308.1,56.9 308.9,56.8 309.7,56.7 311.0,57.0 311.4,57.1 311.9,57.0 312.9,57.0 313.7,57.2 314.5,57.4 314.8,57.8 314.4,57.9 313.3,57.9 312.5,57.9 311.8,57.8 311.3,57.6 310.6,57.8 310.0,57.7 309.4,57.8 308.2,58.1 310.0,57.9 310.8,58.0 311.6,58.2 311.7,58.7 311.1,58.9 310.2,58.8 309.6,58.8 309.1,58.9 307.7,58.7 308.7,59.0 309.6,59.0 310.2,59.1 310.7,59.2 311.0,59.6 311.5,59.7 312.1,59.6 312.6,59.7 313.0,59.8 313.6,59.8 314.3,59.8 314.7,59.9 313.9,60.1 314.4,60.5 314.1,60.9 314.6,60.8 315.0,60.8 315.2,60.4 316.1,60.3 315.9,60.8 315.5,61.4 316.0,61.2 316.7,60.9 317.0,61.3 317.5,61.2 317.9,61.2 317.9,61.7 318.3,62.0 318.2,61.6 318.6,61.3 319.3,61.0 319.7,61.0 319.4,61.4 319.9,61.7 320.5,61.6 321.1,61.8 321.8,62.2 322.2,62.1 322.6,62.3 322.0,62.5 322.2,62.9 321.4,62.9 320.9,62.9 320.3,62.9 321.2,63.1 321.7,63.2 322.7,63.2 323.1,62.9 323.6,62.9 324.1,63.0 324.9,63.2 324.5,63.6 324.1,63.9 323.1,64.4 323.7,64.3 324.6,64.1 325.1,64.0 325.5,64.1 326.1,64.1 326.7,64.1 327.4,63.8 327.9,63.8 328.3,64.1 329.1,64.5 329.6,64.8 329.1,65.1 328.5,64.9 328.0,64.8 327.4,64.9 328.7,65.3 328.2,65.8 327.3,65.7 326.7,65.5 326.2,65.5 326.7,65.8 326.3,66.0 327.1,66.3 327.7,66.5 327.1,66.7 326.5,66.6 326.1,66.6 326.5,66.8 326.4,67.2 325.9,67.7 325.5,67.6 325.1,67.7 324.5,67.6 323.7,67.1 323.8,67.5 323.2,67.6 323.6,67.7 324.1,67.7 324.0,68.8 323.7,69.4 323.3,69.6 322.8,69.3 322.3,69.1 321.8,69.3 321.3,69.0 321.4,68.5 320.9,68.7 320.7,69.1 320.1,68.8 319.5,68.4 319.1,68.2 318.9,67.8 318.5,67.5 318.7,67.0 319.4,66.7 319.9,66.6 320.4,66.1 321.0,65.8 320.4,65.9 319.8,66.3 319.4,66.4 318.6,66.6 317.9,66.7 317.2,66.7 317.6,66.1 317.1,66.3 316.5,66.3 316.1,66.1 315.5,66.0 314.7,65.4 314.3,65.0 313.9,64.9 313.4,65.2 313.0,65.1 313.4,65.5 313.0,65.8 312.3,65.6 311.8,65.4 311.4,65.4 311.9,65.9 312.4,66.1 313.1,66.4 313.1,66.8 312.4,66.9 311.6,66.8 311.2,66.6 310.7,66.3 309.8,66.0 309.0,66.1 309.5,66.1 310.5,66.4 310.4,66.8 310.8,67.1 311.2,67.2 311.2,67.7 311.9,67.7 312.3,67.7 312.8,67.8 313.6,68.2 313.0,68.5 313.4,68.6 313.9,69.0 314.3,69.2 314.6,69.8 314.9,69.4 315.7,69.7 316.1,69.9 316.6,69.9 317.3,69.8 317.7,70.1 318.2,70.2 318.7,70.5 318.0,70.8 319.0,70.8 318.9,71.4 318.5,71.3 318.0,71.3 318.5,71.6 318.9,71.9 319.4,72.2 320.0,72.1 320.4,72.2 320.0,72.5 320.5,72.4 320.8,72.8 320.7,73.4 320.8,74.0 320.4,74.3 319.8,73.5 318.9,72.9 319.2,73.3 319.4,73.8 319.4,74.2 319.7,74.7 320.1,75.0 319.7,75.3 319.1,75.1 319.3,75.8 318.9,76.0 318.3,75.6 317.9,75.4 317.4,75.2 317.0,75.2 316.0,74.7 315.5,74.9 315.0,74.4 314.9,74.8 314.5,74.6 314.1,74.4 313.4,74.2 312.5,73.7 311.9,73.2 311.5,73.0 311.8,73.6 310.4,73.2 309.7,73.0 308.7,72.9 308.9,73.3 309.3,73.6 310.1,74.0 310.5,74.4 311.3,74.7 311.7,74.7 312.6,75.1 313.1,75.4 313.9,75.8 314.7,76.0 315.2,76.4 315.7,76.8 316.4,77.1 316.3,77.5 316.5,77.9 316.0,78.1 315.5,78.1 315.1,78.0 314.4,77.7 313.4,77.6 313.0,77.5 310.1,77.1 309.6,77.1 309.1,76.9 308.1,76.7 307.3,76.2 306.8,75.7 306.1,75.6 305.7,75.6 304.9,75.7 304.0,75.4 303.3,75.3 302.8,75.1 302.9,74.7 302.1,74.9 301.4,74.6 300.4,74.0 300.0,73.8 300.5,73.8 301.1,73.8 301.5,73.6 301.0,73.2 300.5,73.0 299.4,73.0 299.5,72.6 298.8,72.7 298.4,72.5 298.0,72.1 297.5,71.9 296.7,71.4 296.2,71.2 296.5,70.7 296.1,70.6 295.5,70.5 295.0,70.6 294.6,70.9 294.2,71.0 294.1,70.5 293.3,70.5 292.9,70.2 292.4,69.9 291.9,70.0 292.4,70.4 292.5,70.8 292.0,70.9 291.5,71.0 291.1,70.9 290.3,70.7 289.7,70.8 289.4,71.2 288.8,71.1 287.8,71.4 287.3,71.4 286.9,71.6 286.5,71.6 286.0,71.5 285.3,71.4 284.7,71.3 284.0,71.2 283.4,70.9 282.9,70.5 282.8,70.1 283.1,69.6 283.7,69.2 284.9,69.0 284.8,68.5 285.2,68.2 285.9,68.2 286.7,68.3 287.6,68.4 288.7,68.7 289.4,68.8 289.9,69.1 290.3,69.4 290.1,69.8 290.5,69.8 290.7,69.4 290.3,69.1 289.5,68.7 290.8,68.7 291.2,68.7 291.7,68.4 292.1,68.4 292.6,68.4 293.1,68.4 293.8,68.1 294.5,68.0 295.3,68.1 295.7,68.1 295.4,67.6 294.9,67.2 294.4,67.0 293.7,66.6 293.3,66.4 294.6,65.7 295.6,65.3 296.0,65.0 296.4,64.8 297.1,64.6 297.4,64.2 297.8,63.8 298.7,63.6 299.4,63.2 299.0,62.9 298.4,62.1 298.0,61.6 297.5,61.3 297.0,60.8 296.3,60.4 295.6,60.3 295.1,60.2 294.8,59.8 295.1,59.4 294.6,59.1 294.2,59.1 294.5,59.5 293.9,59.6 293.5,59.6 292.6,59.1 292.2,58.9 292.2,58.5 291.8,58.4 292.3,58.3 291.8,58.3 291.4,58.5 290.7,58.5 290.2,58.5 289.3,58.8 288.2,59.1 287.8,59.2 287.3,59.2 287.3,58.8 287.3,58.4 287.8,58.2 288.6,58.3 289.0,58.2 289.5,58.0 289.9,57.7 289.5,57.4 288.8,57.3 288.4,57.2 288.0,57.2 287.6,57.0 287.6,56.6 288.2,56.5 287.7,56.4 287.3,56.5 286.8,56.7 286.3,56.6 285.9,56.6 286.5,56.3 286.1,56.0 285.5,56.0 284.7,56.0 284.3,55.8 284.2,55.4 284.0,54.9 282.9,54.9 282.5,54.9 282.0,54.7 281.6,54.6 281.2,54.3 280.8,54.1 280.4,53.9 279.9,54.1 279.5,54.3 280.5,54.7 280.9,54.9 281.2,55.3 280.3,55.8 279.7,55.8 279.1,55.9 277.3,55.6 276.7,55.5 275.9,55.4 275.5,55.4 274.7,55.3 273.4,55.2 273.8,55.5 274.5,55.6 274.9,55.8 275.4,56.1 273.4,55.7 272.3,55.9 271.8,56.0 271.4,56.0 270.9,55.9 269.7,55.6 269.2,55.5 268.0,55.7 267.1,55.7 265.2,55.5 264.5,55.5 264.1,55.3 263.7,55.3 262.7,55.2 261.7,55.5 260.6,55.3 260.2,55.2 259.8,54.8 259.8,54.4 259.4,54.4 258.9,54.5 258.0,54.4 256.9,54.6 256.5,54.7 256.1,54.8 255.1,54.5 254.4,54.3 253.7,54.2 253.2,54.1 252.2,53.4 251.7,52.8 252.7,52.7 253.6,52.7 254.1,52.7 254.7,52.8 255.5,52.9 256.0,52.9 256.9,52.9 257.8,52.8 257.3,52.6 256.7,52.5 256.2,52.3 255.4,52.1 253.9,52.1 252.6,52.0 251.6,51.8 250.9,51.6 250.5,51.5 250.3,51.1 250.2,50.7 249.9,50.3 250.2,49.9 250.9,49.6 250.5,49.4 250.4,49.0 250.8,48.4 251.3,48.1 251.8,47.8 251.9,47.4 252.0,47.0 252.5,46.7 253.4,46.4 255.1,45.6 255.8,45.4 256.3,45.2 257.0,45.1 259.0,44.9 260.0,44.8 261.3,44.9 263.6,45.0 264.0,45.2 263.3,45.5 262.5,45.8 262.0,45.9 261.1,46.4 259.8,47.3 259.4,47.6 259.3,48.0 260.1,48.5 260.2,48.9 260.1,49.3 260.2,49.7 260.5,50.3 261.0,50.6 261.8,51.0 262.4,51.2 263.0,51.4 263.7,51.7 262.8,52.1 261.8,52.2 261.3,52.3 260.6,52.5 259.8,52.7ZM307.0,19.4 309.2,19.4 310.0,19.4 310.8,19.6 311.3,19.6 312.2,19.5 312.8,19.6 315.0,19.6 315.5,19.6 315.0,19.8 314.3,19.9 310.1,20.3 310.6,20.4 311.8,20.4 312.8,20.4 313.9,20.2 314.3,20.2 315.0,20.2 316.3,20.0 317.4,19.9 317.9,19.9 318.3,20.0 318.8,19.9 319.5,19.7 320.1,19.8 320.5,19.9 321.0,20.1 321.9,19.9 322.3,19.9 323.2,20.0 323.6,20.0 323.2,20.2 323.9,20.4 324.8,20.7 324.3,20.9 326.5,20.8 328.6,20.9 329.2,20.9 329.7,21.1 328.8,21.7 327.9,21.9 327.3,22.1 326.4,22.2 323.4,22.6 321.9,22.8 321.0,22.9 320.6,23.0 318.8,22.9 318.3,23.0 317.5,23.2 316.7,23.3 314.9,23.3 314.5,23.4 314.1,23.7 309.2,24.2 309.6,24.3 310.2,24.3 317.4,23.6 318.8,23.6 320.1,23.6 318.1,24.2 315.8,24.6 314.6,24.9 311.7,25.4 309.4,25.9 308.4,26.1 307.2,26.6 306.8,26.7 306.3,26.8 305.7,26.7 305.2,26.7 304.4,26.5 303.8,26.3 305.0,27.0 302.5,27.3 301.5,27.4 300.9,27.4 300.2,27.4 299.8,27.4 301.1,27.6 303.1,27.4 303.5,27.4 304.0,27.5 303.5,27.8 301.8,28.0 302.5,28.1 302.0,28.4 300.1,28.6 299.4,28.6 298.8,28.6 296.0,28.3 295.0,28.2 294.0,28.1 293.4,28.1 292.6,28.2 294.3,28.4 295.4,28.4 295.9,28.5 296.6,28.8 296.2,29.2 294.8,29.2 294.4,29.2 293.9,29.3 293.3,29.3 292.2,29.3 290.9,29.4 290.3,29.4 289.5,29.4 288.7,29.2 287.8,29.2 286.4,29.1 287.0,29.2 288.1,29.4 288.6,29.7 289.0,29.7 290.0,29.9 290.7,29.9 291.4,30.0 292.4,29.9 293.1,29.9 293.0,30.4 291.0,30.5 290.2,30.4 289.1,30.2 288.5,30.3 287.8,30.3 287.4,30.3 286.7,30.3 285.0,30.4 284.1,30.4 283.4,30.3 282.6,30.3 281.7,30.3 282.2,30.4 282.7,30.5 283.2,30.7 283.7,30.7 284.2,30.7 284.7,30.6 286.6,30.5 287.4,30.5 288.2,30.5 288.7,30.6 289.5,30.9 290.6,30.9 291.4,30.9 292.7,31.2 293.1,31.2 292.9,31.6 292.0,31.8 290.6,31.9 289.0,31.9 287.9,31.9 288.5,32.0 290.3,32.2 291.0,32.3 290.1,32.7 289.3,33.3 288.7,33.4 287.9,33.4 286.9,33.5 286.2,33.5 284.8,33.5 283.3,33.5 283.1,34.0 283.2,34.4 282.9,34.8 282.5,35.0 282.0,35.1 281.4,35.2 280.9,35.2 280.2,35.2 278.0,35.3 277.0,35.3 276.2,35.2 275.4,35.1 274.0,34.8 273.6,34.7 273.2,34.7 273.6,34.9 274.0,35.0 273.5,35.2 272.9,35.3 272.1,35.3 272.7,35.5 273.5,35.5 274.2,35.4 274.7,35.4 275.6,35.4 277.0,35.7 278.0,35.6 279.2,35.6 279.6,35.7 279.7,36.2 280.6,36.4 281.1,36.4 281.5,36.4 282.1,36.2 282.5,36.2 282.9,36.3 283.3,36.5 283.0,37.1 282.5,37.3 280.7,37.6 280.2,37.8 279.8,37.9 279.1,38.0 277.9,38.2 277.3,38.2 275.9,38.4 275.1,38.4 275.3,38.0 275.1,37.6 274.5,37.5 274.0,37.5 273.4,37.5 273.0,37.5 272.1,37.1 271.6,37.1 271.1,37.0 271.5,37.3 268.4,37.7 267.0,37.6 266.0,37.0 265.9,37.9 263.5,38.0 262.9,38.0 262.0,37.9 260.8,37.7 260.3,37.5 259.9,37.3 259.2,37.8 258.4,37.7 257.4,37.6 257.0,37.3 257.0,37.8 255.3,37.7 254.5,37.8 254.2,37.3 254.2,36.7 253.9,37.1 254.0,37.7 253.3,37.6 251.8,37.6 251.2,37.5 251.3,37.1 251.4,36.6 253.4,36.1 254.0,35.9 254.5,35.8 255.1,35.8 256.0,35.7 256.6,35.8 257.3,35.7 258.2,35.7 258.7,35.6 258.1,35.3 257.6,35.2 257.1,35.1 256.7,35.0 256.2,34.7 255.7,34.4 255.3,34.1 256.2,33.8 257.0,33.7 257.7,33.6 258.3,33.6 259.0,33.7 260.0,33.9 260.6,34.0 261.4,34.4 261.9,34.7 262.3,34.8 264.0,35.1 264.6,35.1 265.3,35.1 266.7,35.0 267.4,35.0 267.8,34.9 268.8,34.5 269.7,34.1 270.3,33.8 270.6,33.4 268.6,34.2 267.3,34.6 266.9,34.7 266.2,34.7 265.3,34.6 264.3,34.7 263.6,34.7 263.1,34.6 263.1,34.0 262.4,33.5 263.2,33.3 263.8,33.2 265.0,32.8 266.1,32.8 265.6,32.8 265.1,32.6 264.1,32.7 264.5,31.9 263.8,32.5 263.1,32.8 262.7,32.9 262.3,33.0 260.5,33.1 260.9,32.8 261.3,32.4 260.9,32.5 259.9,32.8 259.2,32.9 258.6,33.0 257.4,33.0 256.8,32.8 257.0,32.2 257.9,31.8 258.5,31.5 258.9,31.2 260.4,31.0 262.0,31.0 263.3,30.8 263.9,30.8 264.5,30.9 266.9,31.0 267.9,31.1 268.4,31.2 269.0,31.1 269.5,31.0 271.0,31.0 271.4,31.0 271.8,30.9 272.3,30.8 272.8,30.7 272.1,30.7 271.6,30.8 271.0,30.8 270.4,30.8 269.3,30.7 267.3,30.7 266.3,30.7 265.8,30.6 265.3,30.5 266.0,30.2 266.5,30.3 267.2,30.4 267.8,30.4 266.7,30.1 266.1,29.9 265.6,29.7 265.2,29.5 264.3,29.2 263.6,28.9 263.1,28.7 262.6,28.6 261.0,28.6 259.9,28.2 259.7,27.7 259.7,27.1 260.3,26.9 263.4,27.0 264.8,27.0 266.5,27.1 267.4,27.1 268.5,27.4 269.4,27.6 270.3,27.8 271.2,28.0 272.1,28.4 272.6,28.5 273.1,28.6 273.7,28.7 274.9,28.9 275.9,28.9 276.5,28.9 277.0,28.8 277.4,28.7 277.0,28.7 275.8,28.7 275.0,28.6 274.5,28.5 274.0,28.4 273.2,28.1 272.6,27.9 271.3,27.6 270.3,27.3 269.6,27.0 270.0,26.8 270.7,26.7 275.1,26.5 277.6,26.3 278.7,26.0 282.3,25.6 284.7,25.5 285.6,25.4 286.5,25.4 285.8,25.3 285.0,25.3 283.3,25.3 281.8,25.2 281.3,25.1 282.0,24.7 282.5,24.5 284.6,24.1 286.0,23.9 286.4,23.8 283.4,24.1 282.4,24.3 281.3,24.6 280.7,24.7 280.0,24.7 279.4,24.9 279.0,25.3 278.4,25.4 277.4,25.7 275.0,26.0 274.2,26.0 273.5,26.0 271.2,26.2 270.5,26.2 269.8,26.2 271.3,25.8 270.8,25.7 270.1,25.7 268.3,25.8 267.6,25.9 267.0,26.1 266.5,26.2 266.1,26.3 265.5,26.3 263.5,26.3 263.0,26.3 261.9,26.2 260.8,26.2 260.4,26.2 259.6,26.1 260.4,25.6 262.1,25.2 263.2,25.0 264.8,24.9 268.5,24.7 264.9,24.7 261.7,24.9 261.2,25.0 260.5,25.1 258.1,25.8 257.4,25.9 256.4,26.0 255.5,25.9 254.9,25.8 253.8,25.6 253.0,25.5 252.6,25.5 252.2,25.3 254.4,25.0 257.3,25.0 258.5,25.0 259.8,24.9 261.6,24.7 263.7,24.3 264.1,24.2 263.3,24.2 262.8,24.2 261.5,24.3 259.4,24.7 257.6,24.8 253.1,24.8 251.7,24.9 251.0,24.9 250.6,24.8 250.1,24.7 251.2,24.4 252.1,24.3 251.0,24.2 251.6,23.9 253.1,23.7 253.8,23.6 255.2,23.6 256.6,23.6 255.3,23.4 254.2,23.4 252.8,23.5 249.2,23.9 248.8,23.9 248.3,23.8 250.4,23.3 249.1,23.2 248.7,23.2 248.3,23.2 247.7,23.2 246.9,23.4 246.4,23.4 245.3,23.2 246.0,22.9 246.6,22.8 247.4,22.7 248.6,22.6 249.5,22.5 251.0,22.5 251.7,22.5 252.3,22.3 253.1,22.2 254.0,22.1 255.4,22.0 256.6,22.0 257.2,22.1 257.7,22.2 258.3,22.3 258.8,22.1 259.4,22.1 260.1,22.1 260.7,22.2 261.5,22.3 262.1,22.4 262.8,22.3 263.8,22.3 263.4,22.2 263.0,22.1 259.5,21.7 260.6,21.5 261.3,21.4 261.7,21.4 262.6,21.2 263.1,21.1 264.2,21.0 264.6,21.0 265.1,21.1 265.6,21.2 267.2,21.2 267.8,21.3 269.0,21.7 269.4,21.8 270.1,22.0 270.5,22.0 271.2,22.0 270.4,21.8 271.5,21.6 273.4,21.9 276.3,22.2 277.4,22.3 278.0,22.4 278.7,22.5 279.3,22.6 278.8,22.4 277.4,22.1 273.7,21.7 272.2,21.4 271.5,21.3 271.0,21.1 271.5,20.9 272.2,20.9 273.0,20.8 272.3,20.7 271.9,20.6 272.8,20.4 273.4,20.4 274.5,20.6 275.4,20.6 274.6,20.2 275.0,20.1 276.0,20.1 277.6,20.3 280.5,20.3 281.3,20.3 280.0,20.2 278.8,20.0 278.2,20.0 277.4,19.7 278.1,19.6 280.1,19.6 281.9,19.7 283.4,19.7 284.4,19.7 284.8,19.8 285.5,19.9 287.7,20.4 288.2,20.5 288.9,20.7 289.6,20.6 290.1,20.5 288.6,20.2 287.8,20.0 286.4,19.7 286.0,19.5 289.6,19.3 293.3,19.4 293.9,19.5 294.3,19.6 294.7,19.7 295.3,19.9 296.5,20.1 298.2,20.2 297.8,20.1 296.6,19.9 296.0,19.7 297.7,19.2 299.8,19.1 301.7,19.5 302.4,19.7 302.9,19.7 302.2,19.5 301.7,19.4 302.5,19.2 303.1,19.2 305.6,19.1 306.2,19.2 306.8,19.4ZM238.0,50.7 238.4,50.7 239.4,50.6 239.6,51.0 240.1,51.2 240.5,51.3 241.0,51.5 241.6,51.8 242.0,52.4 241.9,53.0 242.3,53.3 242.7,53.6 243.4,53.7 243.9,54.2 244.3,54.5 244.7,54.7 245.1,54.6 245.7,55.1 244.8,55.2 244.1,55.1 243.6,54.9 243.2,55.1 244.1,55.3 244.5,55.5 243.7,55.9 242.4,56.4 242.0,56.5 242.7,56.5 243.1,56.4 243.6,56.5 244.7,56.9 245.2,56.8 245.7,56.6 246.2,56.5 246.7,56.5 246.0,56.8 246.4,56.8 247.4,56.9 247.8,56.9 248.2,56.9 248.8,57.0 248.3,57.1 247.9,57.2 247.5,57.6 247.1,57.5 246.6,57.5 247.9,58.0 248.4,58.5 248.5,58.9 248.5,59.4 248.4,59.8 248.8,60.0 249.2,60.3 249.6,60.3 250.0,60.0 250.3,59.5 250.6,59.1 250.8,58.5 251.2,58.1 251.8,57.7 252.2,57.6 252.6,57.6 253.3,58.0 253.8,58.2 254.7,58.5 255.4,58.9 255.8,59.5 256.0,59.9 256.0,60.3 255.6,60.4 255.2,60.4 254.6,60.3 254.7,61.1 255.0,61.8 255.6,62.2 256.9,62.9 257.2,63.3 257.6,63.4 258.1,63.1 258.5,62.9 258.9,62.8 259.4,62.6 259.7,62.1 260.0,61.7 261.2,61.0 261.7,60.2 261.9,59.7 262.0,59.2 262.5,59.0 263.0,59.0 264.3,59.0 263.6,58.8 264.1,58.4 263.6,57.9 263.2,57.9 262.8,57.7 262.7,57.2 262.7,56.8 262.6,56.3 263.0,56.0 263.4,56.1 263.8,56.1 264.4,56.0 264.9,56.0 265.8,56.0 266.9,56.3 267.6,56.4 269.5,56.4 270.2,56.4 271.2,56.5 270.8,56.9 270.1,57.0 271.4,57.2 271.5,57.6 272.4,57.6 273.0,57.6 273.9,57.8 273.3,58.3 272.9,58.5 272.4,58.6 273.1,58.7 273.7,58.7 274.1,58.8 274.3,59.2 273.5,59.6 272.7,59.8 272.2,59.8 271.6,59.7 271.1,59.8 270.7,59.8 271.1,60.1 271.6,60.7 272.0,60.6 272.0,61.0 272.3,61.5 273.0,61.9 273.6,62.1 274.2,62.5 274.2,62.9 273.9,63.4 273.3,63.9 272.6,63.9 272.2,64.1 271.7,64.5 271.2,64.7 270.7,64.9 269.6,65.1 268.9,65.5 268.3,65.6 267.8,65.6 267.4,65.2 266.9,64.8 266.5,64.6 266.1,64.6 265.7,64.4 265.2,64.0 264.7,63.8 264.3,63.8 263.8,64.0 264.2,64.1 264.6,64.1 265.0,64.2 265.8,64.7 266.2,64.9 266.8,65.5 267.2,65.9 266.6,66.0 265.9,65.9 265.4,66.2 264.9,66.1 264.1,65.9 263.6,65.8 263.0,65.4 262.2,65.1 261.7,65.2 260.9,65.2 259.4,65.2 259.0,65.5 259.5,65.8 260.3,65.9 260.8,66.0 261.2,66.2 261.0,66.6 259.2,67.6 258.5,68.0 258.1,68.2 257.5,68.5 257.1,68.5 256.5,68.5 255.6,68.5 255.2,68.3 254.5,68.0 253.9,67.8 253.5,67.6 252.9,67.5 252.5,67.4 251.6,67.1 251.1,66.9 250.7,66.8 250.3,66.8 249.7,67.0 249.1,66.9 248.6,66.9 248.2,66.9 247.7,66.8 247.2,66.8 246.4,66.8 246.0,66.8 246.4,67.0 247.1,67.1 248.3,67.0 249.6,67.2 250.2,67.3 250.6,67.4 251.1,67.6 252.1,68.2 252.9,68.5 255.0,68.7 255.8,68.7 257.2,68.7 258.0,68.8 258.3,69.3 257.8,69.6 255.9,71.1 255.6,71.5 254.5,72.0 253.7,72.2 253.3,72.2 252.9,72.2 252.2,71.9 252.5,72.3 251.7,72.1 251.2,72.2 250.7,72.0 249.9,71.8 250.4,72.3 249.6,72.3 250.0,72.7 249.6,73.1 249.0,73.3 248.5,73.2 248.0,73.3 247.4,73.4 246.9,73.3 245.7,73.0 244.7,72.9 244.3,72.7 243.9,72.8 243.5,72.8 242.9,72.7 241.8,72.4 240.5,72.1 239.7,71.8 240.0,72.2 240.1,72.6 240.5,72.7 240.9,72.7 240.5,72.4 241.0,72.4 243.0,72.9 243.5,73.0 243.9,73.0 243.2,73.4 243.6,73.4 244.2,73.2 244.6,73.1 245.3,73.2 245.9,73.4 246.3,73.6 246.9,73.7 247.3,73.8 247.9,74.0 248.1,74.7 247.8,75.1 247.2,75.2 246.3,75.5 244.8,75.5 244.4,75.4 244.0,75.4 243.4,75.5 243.8,75.9 244.6,76.0 244.2,76.3 243.7,76.1 243.3,76.2 242.9,76.3 242.5,76.6 242.7,77.0 242.0,76.9 241.2,76.8 241.7,77.0 241.5,77.4 240.7,77.7 240.1,77.9 240.5,78.3 239.7,78.9 239.1,79.2 239.2,79.6 238.7,79.7 238.7,80.2 238.0,80.9 237.7,81.3 237.5,81.7 237.0,81.8 237.1,82.2 236.8,83.0 236.7,83.5 236.7,84.8 236.7,85.4 236.5,85.9 236.9,86.4 237.3,86.5 237.7,86.8 238.1,86.9 238.0,87.9 238.3,87.1 238.7,86.8 239.5,86.7 240.3,86.8 240.9,86.8 241.3,87.3 241.9,88.3 242.1,88.7 242.4,89.3 243.1,90.4 243.2,90.8 242.9,91.4 242.4,91.8 243.0,91.6 243.6,91.5 244.4,91.5 246.9,91.0 247.5,91.0 248.4,91.0 249.0,91.3 249.8,91.5 250.6,91.7 251.8,91.9 252.2,92.0 252.9,92.1 253.3,92.2 253.7,92.4 254.3,92.8 254.8,93.0 255.3,93.1 255.9,93.5 256.8,94.3 257.5,94.5 258.6,94.7 260.1,95.1 260.7,95.2 261.2,95.3 261.6,95.4 262.0,95.6 262.6,95.9 263.1,96.2 262.9,97.0 263.3,96.6 263.7,96.4 264.1,96.4 264.7,96.5 265.2,96.5 265.7,96.4 266.1,96.4 266.6,96.4 267.6,96.5 268.9,96.6 269.5,96.6 270.0,96.8 270.6,96.8 271.1,97.0 271.6,97.6 271.2,98.7 271.0,99.3 271.5,99.8 271.8,100.3 271.8,100.7 271.7,101.1 271.8,101.8 271.6,102.2 271.4,102.6 271.7,103.0 272.2,103.3 272.6,103.7 273.3,104.4 272.8,104.8 273.2,104.9 273.7,105.0 274.2,105.3 274.6,105.4 275.1,105.6 275.8,106.1 276.1,106.5 276.4,106.9 276.5,107.3 275.9,107.6 275.4,108.0 275.9,107.8 276.5,107.5 277.0,107.5 277.5,107.5 277.9,107.7 278.7,108.3 279.3,108.7 278.8,108.2 278.5,107.8 278.6,107.4 279.0,107.0 279.6,106.6 280.1,106.9 280.5,107.2 280.7,107.6 281.0,107.1 280.6,106.3 280.9,105.8 281.4,105.5 281.8,105.0 281.9,104.4 281.3,103.8 281.2,103.4 281.0,102.8 280.7,102.2 280.6,101.6 280.4,101.2 280.2,100.8 280.7,100.5 280.3,100.2 279.9,99.7 279.6,99.3 279.2,98.9 278.9,98.3 280.8,97.6 282.0,97.2 282.5,97.0 283.0,96.8 283.6,96.6 284.0,96.4 285.2,95.7 285.7,95.4 286.3,94.8 286.8,94.5 287.1,94.1 287.4,93.4 287.4,92.5 287.4,92.0 287.3,91.2 287.1,90.6 286.7,90.0 286.4,89.6 285.7,88.8 284.8,88.3 284.2,88.1 283.7,87.9 283.3,87.8 282.4,87.3 282.0,87.2 281.9,86.8 282.1,86.4 282.7,86.0 283.1,85.6 283.8,85.3 283.8,84.9 284.1,84.5 284.5,84.2 285.0,84.5 284.8,84.1 285.2,83.9 285.1,83.5 284.6,83.2 284.3,82.3 284.5,81.9 284.1,81.7 284.3,81.1 283.7,81.2 283.3,81.1 282.8,81.1 283.5,80.5 284.0,80.1 284.0,79.5 284.7,79.0 284.2,78.8 283.6,78.5 283.1,78.0 283.0,77.5 283.0,77.0 283.6,76.6 284.4,76.3 285.1,76.2 285.5,76.2 286.4,76.3 287.2,76.5 289.4,76.9 289.8,77.1 289.4,77.2 290.3,77.0 290.7,76.9 291.4,77.0 291.9,77.1 292.7,77.5 293.3,77.0 293.9,76.9 294.3,76.7 294.8,76.6 295.3,76.5 296.0,76.8 296.4,76.9 297.1,77.2 297.6,77.4 298.0,77.4 298.2,77.9 298.6,78.0 299.0,78.1 299.4,78.2 299.8,78.5 299.3,78.9 299.9,78.9 300.4,78.6 301.0,78.8 300.4,79.3 301.0,79.4 301.2,80.0 301.6,80.1 302.3,80.1 302.7,80.2 303.5,80.4 304.1,80.4 304.5,80.4 305.0,80.4 305.2,80.8 305.6,81.0 306.1,80.8 306.5,80.7 306.9,80.4 307.2,80.8 307.0,81.2 306.6,81.4 306.3,81.8 306.2,82.3 306.6,82.7 306.5,83.1 306.1,83.3 305.7,83.3 304.1,83.3 303.7,83.3 304.3,83.4 306.1,83.5 306.5,83.7 306.7,84.1 306.5,84.5 306.4,85.0 307.2,85.2 307.1,85.6 307.1,86.0 306.9,86.5 306.5,86.6 306.2,86.2 305.6,86.6 305.1,86.7 305.5,86.8 305.9,87.0 306.5,86.9 307.3,86.5 307.9,86.4 308.5,86.4 309.2,86.4 309.8,86.6 310.2,87.2 310.5,87.8 310.2,88.3 309.8,88.7 308.8,88.9 308.2,89.1 308.9,89.0 309.7,88.9 310.1,88.6 310.6,87.8 311.1,87.5 311.4,88.0 310.9,88.5 311.5,88.1 312.0,88.2 312.0,88.6 312.8,88.1 313.4,87.9 313.8,87.7 314.2,87.6 314.7,87.5 315.1,87.1 315.7,86.7 316.2,86.9 316.6,87.3 316.6,87.7 316.9,87.3 316.6,86.8 317.1,86.5 317.5,86.2 317.9,86.1 318.3,86.0 317.8,85.8 317.8,85.4 318.3,85.2 318.1,84.8 318.5,84.8 319.2,85.1 318.7,84.7 318.3,84.6 318.1,84.2 318.5,83.9 318.9,83.9 319.3,84.0 318.9,83.7 319.2,83.2 319.6,82.6 320.0,82.4 320.8,82.6 320.8,83.1 320.2,83.2 320.7,83.2 321.1,83.2 321.7,83.4 321.8,83.8 322.3,84.0 322.7,84.5 322.6,84.9 322.8,85.3 323.2,85.2 323.8,85.6 323.2,85.9 322.5,85.9 323.4,86.0 323.9,85.9 324.3,85.9 324.4,86.3 324.9,86.4 325.2,86.8 324.7,87.4 323.9,87.6 323.5,88.0 324.2,87.7 324.6,87.6 325.5,87.6 326.1,87.5 325.9,88.0 325.5,88.3 324.8,88.5 324.3,88.8 325.1,88.6 325.5,88.5 326.1,88.4 326.9,89.0 327.5,89.0 327.9,89.1 327.8,89.5 327.9,90.0 327.5,90.1 327.1,90.2 326.7,90.3 327.2,90.4 328.0,90.5 327.9,90.9 328.3,91.1 328.8,91.2 329.6,91.6 329.5,92.1 329.5,92.6 329.1,92.6 327.6,92.5 326.8,92.3 326.4,92.2 327.5,92.6 328.2,92.8 327.8,93.2 328.6,93.3 329.1,93.4 328.6,93.6 329.0,93.9 329.4,93.8 329.7,94.2 329.3,94.4 330.0,94.6 330.6,94.8 331.0,94.6 331.6,94.9 332.0,95.0 332.4,95.0 332.2,95.4 332.8,95.9 332.3,96.2 332.1,96.6 331.6,97.1 332.4,96.7 333.5,96.5 334.0,96.4 334.4,96.7 334.9,96.7 334.1,97.4 334.9,97.1 335.4,96.7 335.9,96.7 336.2,97.1 336.7,97.7 337.5,97.8 338.3,97.7 338.7,97.5 339.1,97.9 339.7,98.1 340.3,98.2 340.3,98.6 339.7,98.9 339.2,98.9 338.5,99.0 337.9,99.3 337.3,99.7 336.9,99.9 336.3,99.9 335.6,100.2 334.7,100.5 334.3,100.5 333.8,100.5 333.3,100.7 333.0,101.1 332.6,101.1 332.2,101.0 332.9,101.3 332.5,101.7 332.9,101.9 333.4,101.7 333.8,101.4 334.4,101.3 335.2,101.0 335.8,100.7 336.3,100.3 337.1,100.1 338.0,99.9 338.6,99.8 339.1,99.7 338.7,99.6 338.0,99.7 338.4,99.4 340.0,99.5 340.5,99.5 341.1,100.2 341.3,100.6 340.3,101.0 340.7,101.5 341.1,101.3 341.6,100.9 342.1,100.7 342.5,100.7 343.0,100.6 343.5,101.0 344.1,101.1 344.5,101.5 344.8,101.9 345.0,102.4 344.7,102.8 344.8,103.3 344.9,103.7 344.0,104.0 343.6,104.0 344.3,104.1 344.9,104.1 345.3,104.4 344.7,104.5 345.3,104.9 344.4,105.8 343.7,106.1 342.9,106.4 341.7,107.1 340.8,107.0 340.4,107.0 339.5,107.1 338.8,107.4 338.1,107.5 337.7,107.5 337.2,107.6 336.0,108.7 335.1,109.2 334.4,109.7 333.8,110.0 333.1,110.4 332.1,110.4 331.6,110.5 331.1,110.4 330.7,110.5 330.1,110.6 328.5,110.8 328.0,110.5 327.3,110.4 326.8,110.3 326.3,110.3 325.8,110.3 325.1,110.3 324.6,110.3 323.4,110.4 323.0,110.3 322.6,110.2 322.2,110.3 321.8,110.4 320.8,110.3 319.8,110.3 318.9,110.3 317.3,110.4 316.8,110.3 316.3,110.6 315.6,110.5 315.1,110.7 314.6,110.9 314.1,111.1 313.2,112.2 313.2,112.6 312.6,113.0 311.0,113.2 310.3,113.3 309.6,113.7 309.3,114.1 308.5,114.4 307.7,115.1 307.3,115.6 306.8,116.0 306.2,116.1 305.6,115.9 304.5,115.7 302.7,115.4 303.2,115.6 303.7,115.7 304.2,115.7 305.2,116.0 305.6,116.1 306.2,116.4 306.0,116.8 305.6,117.4 304.7,118.0 304.3,118.3 303.6,119.1 302.9,119.4 302.5,119.7 302.0,120.0 301.0,120.3 300.3,120.3 299.9,120.5 299.4,120.7 298.1,121.4 297.7,121.5 297.3,121.6 296.8,122.0 296.4,122.5 295.9,123.0 295.2,123.0 294.7,123.4 294.3,123.6 293.6,123.5 294.4,123.8 293.5,124.4 292.5,125.0 292.9,124.9 293.7,124.5 294.3,124.3 295.1,123.9 295.7,123.8 295.9,123.4 296.2,122.9 296.8,122.2 297.3,121.9 298.0,121.7 298.6,121.2 299.0,121.1 299.5,120.8 300.3,120.5 300.9,120.4 301.6,120.2 302.1,120.1 302.8,119.9 304.1,119.4 304.5,119.1 305.0,118.6 305.4,118.4 306.1,117.7 306.7,117.2 307.0,116.8 307.5,116.5 308.4,115.9 308.8,115.7 309.6,115.4 310.5,114.9 311.4,114.6 312.3,114.3 313.6,114.0 315.0,113.5 316.2,113.3 317.0,113.3 318.0,113.1 319.9,113.4 320.6,113.6 321.5,114.1 320.8,114.3 321.5,114.7 321.5,115.1 321.3,115.5 320.5,115.7 320.1,116.0 319.6,116.2 318.7,116.6 318.1,116.6 317.3,116.4 316.9,116.1 316.4,116.4 316.0,116.3 315.4,116.3 314.7,116.6 315.5,116.5 316.1,116.7 317.1,116.9 317.6,117.5 318.1,117.5 318.5,117.3 319.4,117.1 319.8,117.2 320.3,117.4 319.9,117.9 319.7,118.4 319.2,118.8 318.6,119.2 319.3,119.2 319.9,119.3 319.7,119.8 320.0,120.3 320.3,120.8 320.4,121.2 320.7,121.6 321.6,121.6 322.5,121.8 322.1,122.2 322.6,122.3 323.0,122.6 323.4,122.6 324.0,122.7 324.4,122.9 325.2,122.8 325.8,122.9 326.2,123.2 326.6,123.2 327.2,123.0 327.9,122.6 328.1,123.0 328.7,123.2 329.2,123.1 329.6,123.4 329.8,123.8 329.3,124.0 330.3,124.0 330.3,124.4 329.8,124.3 329.2,124.6 328.8,124.6 328.4,124.8 327.7,125.0 327.0,125.2 326.4,125.4 325.6,125.6 324.9,125.8 324.2,126.0 323.7,126.0 323.3,125.9 323.5,126.3 322.9,126.4 322.5,126.3 322.1,126.1 321.4,126.3 321.5,126.8 320.9,127.3 320.3,127.7 319.9,128.0 319.2,128.5 318.8,128.5 318.5,129.0 318.1,129.1 317.6,129.1 317.1,128.5 316.7,128.3 316.3,128.3 316.1,127.6 316.4,126.8 317.0,126.2 316.3,126.6 316.6,126.2 317.3,126.0 317.7,125.9 318.1,125.7 317.4,125.8 319.7,124.7 320.1,124.5 321.0,124.3 321.6,124.7 322.0,124.4 322.9,124.1 323.7,124.1 323.3,123.9 322.5,123.9 322.0,123.9 321.3,123.9 320.6,123.9 320.1,124.1 319.7,124.0 320.7,123.3 321.1,122.9 320.7,122.6 320.1,123.2 319.7,123.3 319.3,123.5 318.7,123.7 317.9,124.1 317.0,124.4 316.4,124.1 316.0,124.5 315.4,124.7 314.7,124.8 314.2,124.8 313.7,124.6 313.7,125.0 313.9,125.4 313.4,125.9 312.9,125.8 312.3,126.0 311.9,126.2 311.4,126.5 311.0,126.7 310.6,126.3 310.2,126.5 309.8,126.5 309.7,126.9 309.1,126.8 309.0,126.4 308.4,126.6 308.2,127.3 308.0,127.7 307.4,127.8 307.0,128.0 306.6,128.1 306.2,128.2 305.8,128.3 305.4,128.2 304.8,128.7 304.6,129.2 304.1,129.6 303.8,130.2 303.4,130.7 303.3,131.2 303.7,131.5 303.2,131.8 302.7,132.4 303.3,132.6 303.8,133.2 304.1,133.9 304.7,134.1 305.2,134.0 305.6,133.8 305.3,133.4 304.9,133.1 305.3,133.1 305.7,133.9 304.4,134.4 303.7,134.6 303.7,134.1 302.9,134.6 302.3,134.8 302.2,134.3 301.8,133.9 301.6,134.4 301.5,134.9 300.6,135.2 300.2,135.2 299.8,135.2 299.3,135.3 298.7,135.3 297.6,135.4 297.2,135.5 296.7,135.6 295.6,136.0 295.1,136.4 294.7,136.6 294.8,136.0 294.8,135.6 294.7,136.1 294.4,136.8 294.0,137.0 293.7,137.4 294.3,137.7 294.5,138.2 294.4,138.7 294.3,139.1 294.2,139.5 294.3,138.9 294.0,139.6 293.7,140.0 293.3,140.1 293.3,140.6 292.8,140.8 292.2,141.7 291.8,141.8 292.0,141.3 291.5,141.1 291.0,140.9 290.2,140.3 290.4,139.7 291.2,139.3 291.5,138.9 290.8,139.3 290.4,139.5 290.0,139.9 290.1,140.3 290.5,140.9 290.6,141.4 290.8,141.8 291.2,142.2 291.3,142.7 291.6,143.3 291.2,143.7 290.9,144.1 290.0,145.5 289.5,145.9 289.3,146.4 289.1,146.8 289.0,146.1 289.2,145.5 289.5,145.1 289.8,144.6 289.3,144.5 289.3,144.1 289.3,143.7 288.9,143.5 288.3,143.4 288.2,142.8 288.6,142.8 288.3,142.3 287.9,142.5 288.4,142.1 288.0,141.9 288.4,141.7 288.5,141.3 288.5,140.8 289.0,140.6 289.2,140.2 288.7,140.1 288.6,140.5 288.2,140.4 288.1,140.8 287.7,141.0 287.3,140.9 287.7,141.3 287.3,141.5 287.4,142.0 287.4,142.4 287.5,143.0 287.8,143.4 287.3,143.2 287.1,142.8 287.1,143.2 287.8,143.7 288.0,144.1 287.6,144.0 287.2,143.8 286.8,143.7 286.4,143.6 286.1,143.2 285.7,143.3 285.5,142.9 286.0,142.5 286.0,142.0 285.9,142.4 285.4,142.8 285.2,143.3 285.8,143.4 286.4,143.9 287.1,144.1 287.6,144.4 288.2,144.7 288.0,145.2 287.5,145.3 286.7,144.6 286.3,144.2 285.9,144.0 286.3,144.4 286.9,145.0 287.4,145.4 288.0,145.6 288.2,146.0 287.8,146.2 287.4,146.4 286.8,145.8 287.2,146.3 287.8,146.6 288.1,147.1 287.6,147.1 287.2,146.8 286.1,146.3 285.4,146.3 286.1,146.5 286.8,146.7 287.1,147.1 287.5,147.3 288.2,147.4 288.9,147.5 289.1,147.9 289.6,149.4 290.1,150.3 289.6,149.7 289.4,149.2 289.2,148.4 289.0,148.8 289.3,149.3 289.4,149.7 289.0,149.4 288.5,149.2 288.5,149.6 288.1,149.5 287.7,149.8 287.3,150.0 286.9,149.6 286.9,150.1 287.5,150.1 287.9,150.1 288.3,150.0 288.7,150.1 288.7,150.6 289.3,150.1 289.6,150.7 289.0,151.4 288.6,151.7 288.1,151.8 287.7,151.6 287.3,151.3 286.8,151.6 286.4,151.5 286.0,151.3 287.2,151.9 287.2,152.4 286.7,152.8 285.9,152.3 286.2,152.7 286.8,152.9 287.6,152.8 287.7,153.2 287.2,153.4 286.7,153.6 286.0,153.6 285.4,153.8 285.0,153.6 285.0,154.0 284.3,154.6 283.7,155.1 283.5,155.7 283.5,155.1 283.4,155.6 282.2,155.8 281.7,155.9 281.0,156.3 280.2,157.2 280.0,157.7 279.9,157.3 279.9,157.8 279.4,158.2 278.9,158.3 278.5,158.8 278.0,158.9 277.9,159.3 277.4,159.5 277.0,159.6 276.5,159.7 276.0,159.7 276.5,159.9 276.2,160.3 275.8,160.2 275.7,160.7 275.3,160.9 275.2,161.3 274.8,161.4 274.7,161.8 274.5,162.2 274.4,162.6 273.9,162.9 274.0,163.4 273.7,163.9 273.6,164.4 273.7,164.9 273.9,165.9 274.1,166.3 274.3,167.2 274.7,168.2 275.3,169.3 276.2,170.7 276.2,171.2 276.2,171.7 276.5,172.5 276.1,171.7 276.0,171.3 276.1,170.8 275.9,171.2 275.8,170.8 275.6,170.3 275.6,170.7 275.7,171.2 276.0,171.7 277.2,174.4 277.4,174.8 277.6,175.5 277.7,176.2 277.5,177.4 277.4,178.2 276.9,178.8 276.9,179.4 276.4,179.9 275.7,180.1 275.0,180.2 274.5,179.9 275.1,179.9 274.7,179.5 274.4,178.9 274.0,178.5 273.4,178.1 273.0,177.8 272.7,177.4 272.6,176.6 272.5,176.1 272.1,176.2 272.0,175.8 272.0,175.3 271.5,175.4 271.0,174.8 270.5,173.9 270.5,173.5 270.8,173.1 271.1,172.7 270.6,172.3 270.5,172.8 270.1,173.0 269.9,172.6 270.1,171.6 270.4,170.9 270.4,170.1 270.1,169.3 268.6,168.2 267.5,166.9 266.5,166.4 265.8,166.5 265.7,166.9 265.1,166.9 264.4,167.3 264.0,167.4 263.4,167.5 263.0,167.6 262.7,167.1 262.5,166.7 262.0,166.3 262.2,165.9 261.8,166.0 260.6,165.7 259.9,165.6 260.4,165.5 260.8,165.5 260.4,165.3 259.9,165.3 259.4,165.5 258.4,165.6 257.8,165.7 258.4,165.5 258.3,165.1 257.9,165.2 257.6,165.6 257.0,165.9 256.6,165.9 255.5,166.0 256.1,165.9 256.0,165.5 255.8,165.1 255.5,164.7 255.3,165.1 255.2,165.5 254.6,165.6 253.6,165.7 253.1,165.5 252.6,165.6 252.2,165.7 251.5,166.0 251.1,166.2 250.1,165.9 249.7,165.6 249.1,165.9 248.9,166.3 249.5,166.6 250.0,166.5 250.5,166.3 250.9,166.3 250.5,166.6 251.0,166.9 251.4,166.5 251.8,166.9 251.5,167.3 251.1,167.4 250.9,167.9 251.4,168.3 252.1,168.5 252.5,168.8 252.5,169.2 252.1,169.2 251.7,169.5 251.5,168.9 251.1,168.6 250.6,168.5 249.6,168.0 249.9,168.5 249.7,168.9 249.3,169.1 249.2,168.7 248.6,168.6 248.1,169.0 247.2,168.9 246.4,168.6 246.8,168.6 246.5,168.2 245.8,167.9 245.4,167.4 244.9,167.4 244.4,167.2 244.1,167.6 243.7,167.9 242.6,167.8 242.2,167.7 241.8,167.5 241.2,167.3 240.6,167.3 239.7,167.3 239.3,167.3 239.5,166.9 239.0,167.2 238.6,167.6 237.3,168.1 236.8,168.4 237.4,167.9 236.9,168.0 236.9,167.6 236.4,167.6 236.1,168.0 236.4,168.4 235.7,169.0 235.4,169.5 235.0,169.7 234.3,170.2 233.7,170.4 233.3,170.6 232.8,170.8 233.3,170.5 232.6,170.4 232.1,170.6 231.7,170.2 231.9,170.9 231.5,171.3 231.1,171.3 231.0,171.7 230.5,171.8 230.1,171.8 230.5,172.0 230.1,172.6 229.5,172.6 229.8,173.1 229.5,173.8 229.1,174.1 228.7,173.9 229.2,174.3 229.2,174.7 229.0,175.1 229.2,175.7 229.3,176.4 230.0,177.6 230.1,178.5 229.9,178.9 229.4,179.9 229.1,180.5 228.7,182.3 228.6,183.4 228.5,183.9 228.4,185.3 228.5,186.3 228.3,186.7 228.2,187.2 228.4,188.1 228.4,188.6 228.9,189.4 229.6,190.0 229.6,190.4 229.4,190.9 229.4,190.4 229.3,190.0 228.9,189.6 228.5,188.8 228.8,190.0 229.2,190.5 229.6,191.4 230.0,192.2 230.2,192.7 231.4,193.9 232.1,194.8 232.3,195.6 232.5,196.3 233.0,196.7 233.4,197.1 233.6,197.5 234.0,197.8 233.5,197.6 234.4,198.1 235.6,198.1 236.1,198.4 236.7,198.6 237.0,199.0 237.4,199.5 237.8,199.5 238.4,199.5 239.2,199.2 240.1,198.8 241.0,198.8 242.0,198.7 242.4,198.4 243.1,198.2 243.9,198.1 244.5,198.0 244.6,198.4 245.0,198.7 245.6,198.8 246.0,198.5 246.5,198.3 246.4,197.9 246.3,197.5 245.9,197.7 246.8,197.1 247.3,196.8 247.9,196.2 248.1,195.2 248.6,194.7 248.7,193.8 248.7,193.4 248.7,192.9 248.7,192.5 249.0,191.6 249.5,191.3 250.3,191.0 253.1,190.5 253.5,190.4 253.9,190.2 254.3,190.1 254.9,190.1 255.5,190.0 256.2,190.1 257.0,190.4 257.6,190.4 257.7,190.0 258.2,190.0 258.6,190.4 258.8,191.0 258.8,191.7 258.5,192.3 258.2,192.7 257.7,193.0 257.2,193.8 257.0,194.2 257.1,194.7 256.7,195.1 256.4,195.5 256.9,195.6 257.0,196.0 256.6,196.2 256.7,197.1 256.5,197.8 256.3,198.2 256.2,198.8 256.0,199.3 255.7,198.8 255.5,198.0 255.0,198.0 254.8,198.6 254.6,199.0 255.2,199.0 255.3,199.4 255.0,200.4 254.8,201.1 254.8,201.7 254.7,202.2 254.8,202.9 254.7,203.8 254.4,204.2 254.0,204.7 253.6,204.9 253.1,205.5 253.1,205.9 253.6,206.1 254.1,206.0 254.9,206.3 255.4,206.2 255.8,205.9 256.4,205.8 256.8,206.0 257.3,206.0 258.6,206.2 259.0,206.1 259.8,206.1 260.6,205.9 261.3,205.7 261.7,205.5 262.5,205.8 263.4,205.8 263.8,205.6 264.9,205.9 265.3,206.1 265.9,206.0 267.3,207.1 266.7,206.9 267.0,207.4 267.5,207.7 268.0,207.7 267.6,207.3 268.4,207.7 268.6,208.1 269.0,208.4 268.7,208.9 268.3,208.8 268.9,210.2 268.7,210.7 268.3,211.1 268.1,211.8 267.9,213.0 268.0,214.0 267.9,215.0 268.0,215.5 267.8,215.1 267.8,214.7 267.6,215.1 267.5,215.5 267.6,215.9 267.6,216.6 267.2,217.0 267.6,217.4 267.4,217.9 267.1,218.3 267.1,219.1 267.5,219.6 267.8,220.0 268.2,220.9 268.5,221.3 269.1,222.1 269.8,222.9 270.5,223.3 271.2,223.8 271.3,224.4 271.7,224.4 271.5,224.9 271.9,225.1 272.5,225.1 272.5,224.7 273.0,224.9 273.5,225.5 274.0,225.6 274.4,225.6 274.8,225.5 275.5,225.3 275.9,224.9 276.3,224.8 277.4,224.4 277.8,224.0 278.2,223.9 278.7,223.4 279.6,223.4 280.0,223.5 280.5,223.6 281.4,223.8 281.9,223.9 283.1,224.3 283.8,224.8 284.2,225.3 285.1,225.9 285.4,226.4 285.8,226.7 286.1,227.1 286.5,227.5 286.3,227.9 286.7,228.0 286.7,226.9 286.6,226.5 286.4,226.1 287.0,225.8 288.1,225.0 288.5,224.3 289.2,223.8 289.6,223.8 290.0,223.5 289.8,223.0 290.0,222.2 290.2,221.7 289.7,221.8 290.1,221.3 290.3,220.8 290.9,220.2 291.3,219.8 291.9,219.3 293.2,219.5 293.2,220.0 293.6,219.6 293.8,219.2 293.9,218.7 294.3,218.5 294.7,218.6 295.3,218.7 296.4,218.6 298.0,217.5 298.8,217.2 299.2,217.0 299.5,216.5 299.6,216.1 300.1,216.0 300.8,215.5 301.4,215.5 302.1,215.7 302.3,216.2 302.0,216.9 301.6,217.3 300.4,217.7 300.1,218.1 300.5,218.9 301.0,219.4 300.9,219.9 301.1,220.4 300.9,221.0 300.3,221.8 299.7,222.7 300.0,223.2 300.4,223.8 300.6,224.3 300.9,224.8 301.3,224.9 302.0,224.7 302.5,224.0 302.6,223.6 302.6,223.0 302.2,222.2 302.1,221.8 301.7,221.5 301.5,220.9 301.3,220.5 301.3,220.1 301.5,219.5 302.0,219.4 303.3,218.9 304.0,218.7 304.9,218.4 305.3,218.0 305.9,218.2 306.1,217.6 305.0,217.7 304.8,217.0 305.0,216.4 305.5,216.2 305.9,216.5 306.1,217.1 306.2,217.6 306.6,218.1 307.7,218.0 308.2,218.2 308.8,218.2 309.4,218.6 310.0,219.0 310.2,219.6 310.2,220.0 310.5,220.6 311.5,220.9 312.3,220.8 313.5,220.6 313.9,220.5 316.0,220.5 316.4,220.6 317.1,221.5 317.6,221.6 318.1,221.8 318.6,221.9 319.1,222.0 319.6,222.0 321.7,220.9 322.7,221.0 321.8,220.6 321.4,220.5 322.6,220.4 323.6,220.4 324.5,220.2 324.9,220.2 325.8,220.1 327.1,220.3 328.1,220.2 327.7,220.4 327.1,220.5 326.7,220.7 325.9,220.7 325.2,220.7 325.4,221.1 325.8,221.3 325.9,221.7 325.7,222.1 326.1,221.9 326.7,222.4 326.9,222.8 327.3,222.7 327.3,222.3 327.7,222.3 328.2,222.8 328.5,223.2 328.4,222.7 328.8,222.7 329.7,223.2 330.5,223.5 330.9,223.7 331.0,224.3 330.6,224.4 330.4,224.9 330.2,225.4 330.1,225.8 328.8,226.1 329.3,226.4 329.7,226.6 330.5,226.4 330.9,226.2 332.0,226.3 332.4,226.0 332.9,226.1 333.3,226.3 333.8,226.7 334.3,226.8 334.8,227.1 335.6,227.6 336.6,228.5 336.9,228.9 337.5,229.5 337.6,230.4 337.3,231.0 337.2,231.4 337.2,231.9 337.5,231.3 338.1,230.9 338.7,231.1 339.5,231.7 340.0,232.1 340.7,232.6 341.1,233.1 341.2,233.7 341.1,234.5 341.3,234.1 341.5,233.5 343.2,233.5 343.8,233.7 344.6,233.9 344.9,233.4 345.4,233.4 346.2,233.5 346.8,233.4 347.7,233.4 349.0,233.6 349.6,233.7 349.9,234.1 349.8,234.7 349.6,235.1 350.0,234.2 350.4,233.9 351.5,234.5 352.0,234.6 353.1,234.9 353.4,235.4 354.3,236.1 354.7,236.3 354.7,236.7 355.4,236.9 355.7,237.5 355.5,237.9 355.8,237.4 356.2,237.3 356.4,238.1 356.5,238.5 356.8,238.0 357.4,238.3 358.1,239.8 358.2,240.9 358.4,241.5 358.8,242.6 358.9,243.1 359.2,243.9 359.5,244.4 359.8,244.9 360.3,245.0 361.0,245.2 361.4,246.1 361.4,246.5 361.3,246.9 360.9,247.2 360.3,247.7 359.8,248.2 359.5,248.8 359.0,249.4 358.6,249.6 358.1,250.1 357.5,250.2 357.2,251.1 356.8,251.5 356.4,252.1 356.3,252.8 355.8,253.3 355.7,253.7 354.9,253.8 354.0,254.2 354.7,254.3 355.7,254.4 356.5,253.9 356.9,253.8 357.5,253.4 358.3,252.9 358.8,252.8 358.6,253.2 358.8,253.6 358.9,254.1 359.2,254.6 359.3,255.0 360.0,255.6 360.4,255.3 360.8,255.2 361.4,255.2 361.9,255.4 362.3,255.2 363.0,254.8 362.8,255.5 362.6,256.1 362.4,257.0 362.1,257.4 362.6,257.0 362.8,256.5 363.3,255.3 363.9,255.1 364.7,254.1 365.2,254.4 365.7,254.1 365.3,253.7 365.4,253.2 365.8,252.9 365.9,252.5 366.3,252.2 366.8,252.1 367.2,251.8 367.6,252.0 368.1,252.1 368.3,251.7 368.7,251.8 369.1,252.1 369.6,252.1 370.0,252.2 370.4,252.5 370.8,252.8 371.3,252.9 371.8,253.1 372.3,253.3 372.8,253.5 373.2,253.7 373.7,253.8 374.0,254.4 374.1,254.8 374.5,254.2 374.9,254.2 375.5,254.6 375.6,255.0 376.0,254.8 376.3,255.4 376.2,255.9 375.8,256.2 375.9,256.6 376.2,256.2 376.6,256.0 376.7,256.6 376.3,256.7 376.1,257.1 376.0,257.7 375.8,258.7 376.6,258.2 376.7,257.6 376.9,257.0 377.3,256.9 377.2,257.5 377.7,257.3 378.2,257.2 379.3,257.0 379.5,256.6 379.9,256.6 380.7,256.8 381.5,257.2 382.6,257.8 383.3,257.8 383.7,257.6 384.1,257.8 384.8,258.1 385.2,258.2 385.6,258.0 386.5,258.0 387.6,257.8 388.2,257.8 389.0,258.0 389.5,258.3 390.0,258.5 390.7,258.9 391.6,259.4 392.0,259.7 392.5,260.1 393.1,260.3 393.4,260.8 394.3,261.7 395.0,262.2 395.5,262.8 396.4,263.1 396.7,263.6 397.3,263.7 397.9,264.0 398.4,264.2 398.9,264.1 399.6,264.2 400.1,264.0 401.3,264.2 401.7,264.6 402.1,265.5 402.4,266.4 402.5,267.2 402.8,267.8 403.0,268.8 403.1,269.2 403.3,270.2 403.2,270.9 403.1,271.4 403.2,271.9 403.1,272.5 402.9,273.4 402.3,274.8 401.8,275.6 401.1,276.5 400.7,276.9 400.3,276.9 400.3,277.4 399.8,278.0 399.4,278.4 398.9,279.1 398.2,279.4 397.4,280.1 397.0,280.7 396.7,281.1 396.3,281.6 396.3,281.2 396.0,281.7 395.9,282.4 395.3,283.6 394.6,284.7 393.8,285.7 393.3,286.0 393.0,285.5 392.6,285.1 392.3,285.5 392.3,285.9 392.1,286.5 391.8,286.9 391.5,287.4 391.7,287.8 391.6,288.2 391.6,288.9 391.5,290.7 391.6,291.5 391.7,292.4 391.8,293.2 392.0,294.0 391.8,295.0 391.5,295.8 391.3,296.6 391.2,297.3 391.1,297.7 391.1,298.1 391.2,299.0 390.9,299.6 390.5,299.8 389.9,300.7 389.6,301.8 389.6,302.3 389.7,303.5 389.6,304.0 389.5,304.4 388.9,304.8 388.5,305.5 388.3,306.1 388.0,306.7 387.8,307.1 387.2,307.7 386.7,308.1 386.2,309.0 386.0,309.7 386.1,310.9 385.8,311.3 384.5,311.8 383.4,312.7 383.4,313.2 383.2,313.7 381.7,313.7 381.0,313.8 380.5,313.7 380.4,313.3 379.9,313.2 380.0,313.6 379.5,313.9 379.1,314.0 378.5,314.1 378.1,314.2 378.7,313.9 378.2,313.6 377.6,313.7 376.8,313.9 376.0,314.0 375.9,314.5 375.1,314.9 374.4,315.5 373.8,315.8 373.2,316.0 372.7,316.0 372.3,316.1 370.5,317.0 369.8,317.3 369.1,318.0 367.8,318.8 367.1,319.3 366.7,319.5 366.9,319.9 366.1,320.6 365.5,320.2 365.5,320.6 365.1,320.7 364.6,320.5 365.3,320.9 365.2,321.7 364.8,321.8 364.9,322.7 364.7,323.2 364.8,323.7 364.8,324.2 365.0,324.7 365.0,325.2 365.0,325.7 364.9,326.6 365.0,327.3 364.9,328.0 364.9,328.4 364.5,329.0 364.4,329.4 363.8,329.7 363.1,330.2 362.5,330.8 361.8,331.6 361.0,332.8 360.3,334.5 359.4,335.8 359.0,336.3 358.6,336.8 357.9,337.4 357.1,338.1 356.1,338.6 355.4,339.2 355.4,338.8 355.4,338.4 355.9,338.5 356.4,338.3 357.1,337.7 357.6,337.4 357.9,336.9 357.9,336.4 358.4,336.4 358.5,335.8 359.0,335.6 359.1,334.5 359.5,334.6 359.6,334.2 358.5,334.4 357.8,333.9 357.6,333.5 357.6,334.0 357.8,334.5 357.5,335.0 357.5,335.4 357.1,335.9 357.0,336.3 356.3,336.8 355.8,337.1 355.6,337.5 355.2,338.0 355.0,338.6 355.1,339.1 355.0,339.5 354.6,340.1 354.1,341.3 353.7,342.0 353.4,342.4 353.0,342.8 351.7,343.7 351.3,344.5 350.7,345.1 350.6,345.5 350.0,345.9 349.5,346.3 349.0,346.5 347.5,347.0 347.0,346.9 346.6,346.9 346.2,346.7 345.4,346.6 344.8,346.7 344.1,347.0 343.4,346.8 342.1,346.3 341.2,345.7 340.2,345.7 339.4,345.8 339.0,345.3 338.3,344.7 337.8,344.2 337.7,343.7 337.7,343.1 337.9,342.4 338.1,342.0 338.5,341.8 338.7,341.4 338.5,341.0 338.4,340.5 338.4,341.6 338.0,341.9 337.6,342.5 337.4,343.5 337.6,344.2 337.8,344.6 337.8,345.0 337.4,345.3 337.6,345.7 338.1,346.3 339.5,346.9 340.1,347.3 340.8,347.7 341.2,348.2 341.2,348.6 340.7,349.2 340.6,349.7 340.7,350.1 341.5,350.8 342.4,351.0 342.6,352.0 342.6,352.4 341.4,354.0 340.6,354.8 340.3,355.3 340.1,355.8 338.4,356.8 336.1,357.4 334.2,357.8 333.8,357.9 330.8,358.3 330.2,358.3 329.5,358.3 328.9,358.3 328.2,358.2 327.6,358.1 326.8,357.8 326.9,358.3 326.8,358.7 327.2,359.1 327.6,359.4 327.5,359.9 327.4,360.6 327.0,360.8 326.7,361.7 326.6,362.1 327.1,363.0 326.9,363.4 325.6,364.0 325.1,364.2 324.4,364.3 323.3,364.3 322.9,364.3 321.9,363.9 321.2,363.7 320.5,363.5 319.9,363.4 319.3,363.3 319.0,363.7 319.0,364.2 319.1,364.6 319.4,365.5 319.4,366.0 319.3,366.6 319.5,367.0 320.3,367.3 320.7,367.4 320.6,367.8 321.1,367.9 321.5,367.8 321.9,367.8 322.1,367.4 321.5,367.4 322.0,367.2 322.5,367.0 323.0,367.1 323.3,367.5 323.4,368.2 323.3,368.6 322.1,369.1 321.6,368.8 321.3,368.3 320.9,368.1 320.4,368.1 320.0,368.4 319.5,368.5 320.5,369.2 321.0,369.3 320.2,369.8 319.5,370.3 318.9,370.9 318.6,371.6 318.8,372.4 318.7,373.0 318.4,373.5 317.6,374.1 317.8,374.7 317.3,375.0 316.1,374.9 315.7,375.1 315.3,375.3 314.2,375.6 313.2,376.6 312.8,377.2 312.3,377.7 312.2,378.2 312.3,378.7 312.8,379.3 314.5,380.6 314.9,380.7 316.7,380.8 317.1,381.0 317.4,381.5 317.3,382.1 317.0,382.5 316.6,382.7 316.0,382.9 316.4,382.9 316.9,382.9 316.9,383.3 315.6,384.3 315.0,384.5 314.5,384.8 313.8,385.1 313.2,385.6 312.6,386.0 312.0,386.8 311.7,388.5 310.7,389.1 310.0,389.0 309.5,388.5 308.6,388.8 309.0,388.9 309.5,388.9 309.9,389.3 309.5,389.5 309.0,389.7 308.5,389.9 308.2,390.3 307.9,391.0 307.7,391.5 307.3,391.7 307.8,391.7 308.2,392.5 308.3,392.9 307.7,393.2 307.3,393.2 307.8,393.5 308.2,393.4 308.6,393.7 309.2,394.5 309.7,395.0 309.9,395.4 308.3,395.2 307.7,395.0 307.1,395.2 306.8,395.6 306.2,395.8 305.8,395.9 304.5,396.3 304.0,396.3 303.3,396.6 303.3,397.1 302.9,397.9 302.8,398.3 302.9,398.8 302.8,399.4 302.0,399.7 301.5,399.6 300.9,399.5 300.4,399.2 299.7,399.1 299.0,398.5 299.2,397.9 299.8,397.9 300.2,397.9 300.5,398.3 300.3,398.7 300.7,397.9 301.7,397.5 302.3,397.0 301.7,396.6 300.3,397.2 299.6,397.4 299.2,397.6 298.7,397.9 298.5,398.3 298.0,398.4 297.2,398.0 297.5,397.6 297.5,397.0 298.0,396.6 298.7,396.7 299.7,396.3 300.1,396.2 300.6,396.3 301.1,396.3 300.5,395.9 299.4,395.9 298.8,396.2 298.2,395.9 297.8,396.0 297.8,396.4 297.4,396.6 297.2,397.2 296.3,397.4 295.9,397.1 295.4,396.8 296.3,396.5 296.8,396.1 296.2,396.1 295.6,396.3 295.2,396.3 294.7,396.4 294.3,396.0 294.3,395.6 293.8,395.0 294.3,394.9 294.9,395.1 295.3,395.0 295.7,394.9 296.3,394.9 296.8,394.8 297.4,394.6 297.8,394.3 298.2,394.7 298.4,395.2 298.0,395.4 298.4,395.4 298.6,395.0 298.3,394.5 298.5,394.1 298.5,393.6 297.9,393.3 296.9,392.9 297.8,393.4 298.2,393.6 297.4,394.1 296.7,394.4 296.2,394.6 295.8,394.6 295.4,394.0 295.0,393.9 294.5,393.8 294.0,393.6 294.6,393.4 294.7,392.6 294.1,392.2 293.5,392.2 293.0,392.1 292.5,391.9 291.7,391.3 291.4,390.8 292.1,390.8 292.5,390.7 292.3,390.2 292.7,389.9 293.4,390.2 293.9,391.0 294.9,391.5 295.2,390.8 295.4,390.3 294.7,391.1 294.2,390.9 293.9,390.2 294.6,390.3 293.6,390.0 293.0,389.6 293.2,389.1 294.4,389.0 294.0,388.6 293.5,388.3 293.6,387.8 294.2,387.7 294.6,387.8 294.2,387.3 294.4,386.8 294.4,386.4 294.1,386.8 293.9,387.2 293.5,387.3 293.4,386.2 293.4,385.5 293.5,385.0 294.0,384.7 294.4,384.7 294.0,384.5 293.5,384.7 293.1,384.6 292.8,384.1 292.8,383.3 293.3,383.4 293.8,383.5 294.9,383.4 295.8,383.9 296.2,383.8 295.8,383.6 295.5,383.2 295.2,382.4 294.9,383.0 294.2,383.2 293.8,383.2 293.4,383.0 292.9,382.7 292.8,382.3 293.3,382.2 293.8,382.4 293.5,382.0 293.1,381.8 293.8,381.1 294.0,380.5 293.6,380.0 293.2,379.9 292.5,380.2 291.7,379.8 291.7,379.2 291.3,379.4 290.7,379.6 290.3,379.6 290.6,380.2 289.9,380.2 289.7,379.7 290.5,379.1 291.0,378.8 291.5,378.4 291.9,378.2 291.5,377.8 291.5,377.4 292.3,377.3 292.7,377.3 293.2,377.3 293.6,377.2 294.0,377.1 294.2,376.7 294.2,376.3 294.6,376.1 294.8,376.6 294.6,377.3 294.4,377.9 293.5,378.4 293.9,378.4 294.5,378.2 294.8,377.4 295.2,377.3 295.3,377.7 295.0,378.8 294.6,379.2 295.1,379.2 295.4,378.6 295.4,378.2 295.5,377.7 295.6,377.2 295.1,377.0 295.2,376.3 295.7,376.3 296.2,376.1 296.7,376.0 297.3,376.3 296.6,375.7 296.0,375.7 296.1,375.3 296.2,374.9 297.0,374.8 297.9,374.3 298.1,373.9 298.2,373.4 297.7,373.3 297.2,373.0 296.8,372.9 296.6,372.4 296.6,371.9 297.0,371.8 297.2,371.2 296.9,370.7 297.0,370.3 297.4,370.0 297.6,369.6 297.9,369.2 297.6,368.5 297.9,368.1 297.8,367.5 298.2,367.2 298.8,367.9 298.7,367.2 298.6,366.6 298.0,366.7 297.8,366.2 298.2,366.0 298.7,365.9 299.1,365.3 298.8,365.7 298.3,365.8 297.8,365.4 297.4,365.3 296.7,366.0 295.8,366.1 295.2,366.0 295.5,365.6 295.0,365.3 294.8,364.8 294.5,364.2 294.5,363.8 295.0,362.4 295.2,361.8 295.4,361.3 295.9,360.7 296.5,359.5 296.6,359.0 295.9,357.3 295.7,356.6 295.9,355.9 295.8,355.3 295.4,354.7 295.5,354.1 295.4,353.7 295.6,353.3 296.2,353.4 296.6,353.2 296.8,352.4 296.9,351.9 297.3,351.5 297.6,351.1 297.8,349.9 298.4,349.3 298.3,348.8 298.7,348.2 299.4,347.5 299.5,347.0 299.8,346.2 299.9,345.6 300.0,345.2 300.2,344.5 300.4,344.1 300.9,343.5 301.0,343.1 300.8,342.5 300.7,341.9 301.1,341.6 301.5,340.7 301.6,340.0 301.4,339.5 301.3,338.4 301.2,337.5 300.9,336.6 301.0,336.1 300.8,335.4 300.9,334.3 301.7,333.7 301.8,333.1 301.9,332.4 301.9,331.8 301.4,331.1 301.3,330.4 301.7,329.9 302.0,329.2 302.3,328.8 302.4,328.0 302.5,327.3 302.9,326.7 303.0,325.9 303.1,325.5 303.3,324.9 303.6,323.9 303.6,323.4 303.7,322.8 303.8,322.2 303.6,321.8 303.8,321.0 304.2,320.5 304.3,320.1 304.0,318.8 304.0,317.6 304.1,317.0 304.1,316.6 304.2,316.1 304.4,315.7 304.1,315.2 303.9,314.6 304.0,314.0 304.5,313.8 304.8,312.7 304.9,311.6 305.0,311.0 305.2,310.1 305.3,309.7 305.3,309.3 305.0,307.6 305.0,307.0 305.1,306.2 305.1,305.0 305.0,304.5 305.0,304.1 304.8,303.5 304.6,302.3 304.6,301.7 304.6,301.1 304.2,300.8 303.3,300.1 302.9,299.8 301.8,299.1 301.7,298.4 301.3,298.0 300.6,297.8 300.1,297.4 299.7,297.2 299.3,296.9 298.7,296.4 297.8,296.2 297.3,295.9 296.5,295.5 296.1,295.3 295.2,295.0 294.0,294.2 293.4,294.0 292.9,293.6 291.4,292.8 290.9,292.2 290.2,291.4 289.6,291.1 289.1,290.6 288.5,289.8 288.1,289.3 288.1,288.7 288.2,288.3 288.4,287.5 288.3,287.1 287.7,286.4 287.3,285.6 286.8,284.8 286.6,284.3 286.1,283.9 285.7,283.5 285.7,283.1 285.5,282.4 285.3,282.0 284.4,281.4 284.3,280.6 284.1,280.1 283.1,278.5 282.8,278.0 282.6,277.2 282.3,276.8 282.1,276.0 281.7,275.4 281.5,274.9 281.2,274.3 281.2,273.9 280.8,273.3 280.5,272.8 280.1,272.4 279.7,272.0 278.9,270.6 278.4,269.6 278.0,269.2 277.8,268.8 275.5,267.4 274.8,267.0 274.5,266.5 274.7,266.1 275.2,266.2 275.3,265.7 275.2,265.2 274.5,264.4 274.7,264.0 274.5,263.6 274.2,263.2 274.2,262.0 275.3,260.8 275.6,260.4 276.0,260.1 276.4,259.7 276.9,259.4 277.3,259.2 277.7,259.0 278.0,258.6 278.3,257.7 278.5,257.2 278.3,256.5 278.2,256.0 278.1,256.7 278.0,257.1 277.8,256.5 277.4,257.0 277.1,257.4 276.7,257.4 275.9,256.7 275.4,256.5 275.1,256.1 275.6,255.8 275.7,255.4 275.5,254.5 275.6,253.8 275.3,253.0 276.0,252.5 276.4,251.9 276.8,251.7 276.5,251.2 276.9,250.5 277.4,250.0 277.7,249.6 277.7,248.9 277.6,248.4 277.5,247.8 278.0,247.6 278.5,247.3 278.9,247.3 279.3,247.1 279.9,246.9 280.8,246.7 280.9,246.0 280.5,245.5 280.7,245.1 281.1,244.9 281.7,245.1 281.8,244.7 281.6,244.3 281.6,243.6 282.1,243.1 282.5,243.0 283.0,243.1 283.4,242.9 283.6,242.5 284.0,242.3 284.2,241.9 284.4,241.5 284.8,241.0 285.2,240.3 285.9,239.1 285.5,239.3 285.4,238.8 284.9,238.7 285.0,238.3 285.0,237.9 285.2,237.2 285.3,236.7 285.1,235.9 285.1,235.5 285.0,235.0 284.6,234.6 285.2,234.2 285.2,233.3 284.8,232.8 285.1,231.9 284.9,231.4 284.4,231.0 283.9,230.2 283.5,229.8 282.9,229.0 282.3,228.1 282.2,227.6 282.6,227.4 282.8,226.9 283.2,227.0 283.8,227.4 283.3,226.9 283.1,226.4 282.7,226.7 282.3,226.4 282.3,226.8 281.9,226.0 281.5,225.7 281.0,225.4 280.3,225.0 279.9,224.9 279.3,225.0 278.6,225.4 278.5,225.8 277.4,226.8 276.8,227.0 276.5,227.4 276.8,227.8 277.1,228.2 277.6,228.7 277.7,229.2 277.0,229.4 276.6,229.8 275.9,229.9 275.4,229.9 275.2,229.3 274.9,228.6 274.8,228.1 274.5,228.7 274.0,228.7 273.6,228.6 273.1,227.7 272.6,227.3 272.0,227.2 271.6,227.1 271.2,227.0 270.7,227.0 270.3,226.9 269.8,227.1 269.9,227.5 269.3,227.0 269.1,226.4 268.7,225.9 268.1,225.8 268.6,226.4 268.2,226.6 267.8,226.4 267.4,226.1 267.7,225.5 267.7,225.1 267.4,224.6 267.0,224.2 266.3,223.9 265.3,223.5 264.8,223.2 264.9,222.8 263.8,221.9 263.3,221.7 263.4,222.2 264.0,222.4 263.9,223.1 263.5,223.3 263.0,222.7 262.2,222.5 261.7,221.9 261.5,221.4 261.9,220.7 262.0,220.3 261.6,219.9 261.8,219.5 261.6,218.9 261.2,218.5 259.8,217.4 259.3,216.7 259.0,216.2 258.0,215.5 257.1,214.6 256.5,214.2 256.7,213.8 257.2,214.1 257.4,213.7 257.1,213.3 257.0,212.9 256.4,212.9 256.0,212.8 255.9,213.3 255.5,213.4 255.1,213.4 254.4,213.3 253.9,213.1 253.1,213.1 252.0,212.6 251.3,212.5 250.5,212.3 250.1,212.0 249.7,211.8 248.7,211.4 248.3,211.3 246.8,211.3 246.2,211.1 245.4,210.8 244.9,210.5 243.8,209.6 243.0,208.8 242.2,207.9 241.6,207.5 241.2,207.1 240.2,206.3 239.6,205.9 239.1,205.4 238.7,205.2 238.2,205.0 237.8,204.8 238.2,205.1 238.8,205.4 237.6,205.0 237.1,205.0 237.2,204.6 236.8,204.7 236.4,204.4 236.0,204.7 236.5,204.9 235.7,205.1 234.8,205.6 234.0,205.9 232.7,206.4 232.2,206.4 231.1,206.3 230.0,205.8 228.5,205.6 227.4,205.0 226.3,204.7 225.7,204.1 225.3,204.0 224.0,203.7 223.1,203.6 222.2,203.0 221.5,202.8 221.0,202.6 219.9,202.2 219.4,202.0 219.0,201.7 218.4,201.4 217.8,201.0 217.3,200.4 216.9,200.1 216.1,200.1 215.2,199.9 214.7,199.8 213.8,199.5 212.7,199.1 212.3,198.7 212.0,198.2 211.4,197.7 211.0,197.5 210.3,197.2 209.4,196.8 208.5,196.4 208.2,196.0 207.5,195.3 207.0,194.5 206.8,193.8 206.5,193.4 207.3,193.0 207.7,192.7 207.2,192.3 207.2,191.9 207.7,191.3 207.7,190.6 207.1,189.9 206.9,189.4 206.5,188.9 206.5,188.0 206.1,187.1 205.7,186.7 204.9,185.9 204.4,185.6 204.0,184.9 203.5,184.4 203.0,183.7 202.5,183.3 200.7,182.0 201.3,182.3 201.3,181.9 200.9,181.9 200.5,181.8 200.1,181.6 200.0,181.2 199.4,180.6 199.8,180.5 199.0,180.0 198.1,179.5 197.8,179.1 197.1,179.2 197.5,178.6 197.0,178.8 196.5,178.9 196.2,178.5 196.0,177.7 196.5,177.1 196.9,177.1 196.6,176.7 196.5,176.3 195.9,175.8 195.3,175.8 194.9,175.6 194.7,175.1 193.7,174.5 193.1,174.1 192.9,173.7 192.7,173.2 192.8,172.8 192.3,172.5 191.9,172.5 191.3,172.3 190.9,171.9 190.4,171.2 189.8,170.9 189.4,170.4 189.1,170.0 188.4,169.4 188.3,168.7 187.8,168.5 187.7,168.0 187.3,167.4 187.1,167.0 186.8,166.3 186.6,165.8 186.2,165.3 186.0,164.9 185.8,164.5 185.9,164.1 185.8,163.7 185.9,163.3 185.5,163.2 184.8,163.1 184.4,162.9 184.2,162.4 183.5,162.1 183.1,162.5 182.6,162.3 181.8,161.9 181.4,161.7 180.7,161.4 181.1,162.1 180.9,163.5 181.2,164.0 181.4,164.5 181.4,164.9 181.6,165.3 181.5,166.0 181.8,166.6 182.2,167.0 182.8,167.4 183.2,167.8 183.8,168.2 184.6,169.2 184.7,169.7 185.1,169.6 185.2,170.0 185.9,170.8 186.5,171.0 186.5,171.4 186.6,172.0 186.8,172.5 187.4,173.2 188.0,173.5 188.1,174.0 188.4,174.5 188.9,174.8 189.2,175.4 189.3,175.9 189.7,176.2 189.4,175.7 189.5,175.3 190.1,175.8 190.2,176.2 190.4,176.6 190.5,177.0 190.7,177.4 190.8,178.0 190.9,178.4 191.3,179.0 191.6,179.4 192.0,180.2 192.3,180.6 192.6,181.1 192.4,181.5 192.6,182.4 193.3,182.8 193.6,182.4 194.4,182.8 194.7,183.2 195.1,183.7 195.3,184.3 195.8,184.4 196.1,184.8 196.0,185.5 195.5,185.9 194.9,186.3 194.4,186.4 193.9,185.2 193.6,184.7 192.7,184.1 192.3,183.7 191.6,183.0 190.5,182.4 190.1,182.1 189.6,181.8 188.7,181.0 188.5,180.4 188.7,179.7 188.7,179.2 188.6,178.8 188.6,178.4 188.4,178.0 187.8,177.2 187.4,177.0 186.1,176.2 185.8,175.8 185.7,175.1 185.4,175.6 184.9,175.6 184.5,175.8 184.0,175.4 183.5,175.0 183.0,174.7 182.4,174.6 182.0,174.2 181.8,173.8 181.3,173.5 180.6,173.0 181.0,172.7 181.8,172.8 182.3,172.7 182.7,173.0 183.1,173.1 182.8,172.7 182.8,172.2 183.1,171.6 183.2,171.0 182.9,170.5 182.5,170.2 181.5,169.2 180.9,168.7 180.1,168.3 179.7,168.0 179.0,167.6 178.5,166.8 178.4,166.4 178.3,165.8 177.8,165.5 177.7,165.1 177.7,164.7 177.0,164.0 176.9,163.5 176.5,162.9 176.1,162.5 175.9,161.9 176.1,161.5 175.4,161.1 175.2,160.6 174.8,160.2 174.6,159.6 174.5,159.2 174.3,158.7 174.1,158.1 173.7,157.5 172.8,156.8 172.4,156.6 172.0,156.3 171.5,156.2 171.1,156.3 170.8,155.5 169.9,155.5 169.0,155.2 168.7,154.8 168.3,154.6 167.8,154.4 167.1,154.4 166.5,154.3 165.6,154.3 165.1,154.0 164.9,153.5 164.8,152.9 164.8,152.4 164.3,152.2 164.3,151.8 163.8,151.4 163.1,150.9 162.7,150.4 162.0,149.6 161.5,149.1 161.3,148.4 161.7,148.0 161.6,147.6 160.7,147.2 160.0,146.6 160.0,146.2 159.7,145.7 159.7,145.1 160.1,145.4 160.5,145.7 160.9,145.9 160.7,145.5 160.3,145.0 160.0,144.6 160.5,144.3 160.9,144.3 161.9,144.3 162.3,144.3 161.8,144.2 161.4,144.2 161.0,144.1 160.5,144.2 160.0,144.0 159.7,144.6 159.2,144.7 158.7,144.4 158.3,144.5 158.4,143.8 158.0,143.2 157.5,143.0 157.2,142.6 156.4,141.9 156.3,141.4 156.1,140.6 156.2,140.2 156.0,139.5 155.3,138.6 154.7,138.2 154.5,137.5 154.8,136.9 155.2,136.2 155.2,135.7 155.4,135.0 155.2,134.4 154.9,133.9 155.0,133.4 154.6,133.0 154.4,132.5 154.4,131.7 154.1,131.1 154.2,130.7 154.6,129.6 155.0,129.4 155.0,128.7 155.2,127.6 155.3,126.9 155.4,126.3 155.4,125.6 155.7,123.9 155.8,123.4 155.7,122.7 155.7,121.8 156.5,121.7 156.9,121.6 157.4,121.8 157.0,121.5 156.5,121.5 155.8,121.5 155.4,121.4 155.4,120.9 155.4,120.5 155.7,121.0 155.9,120.4 155.4,120.2 155.2,119.8 156.0,119.5 155.6,119.5 155.2,119.3 155.0,118.9 154.7,118.3 154.5,117.6 153.8,116.9 153.6,116.2 153.6,115.6 154.4,115.8 155.1,116.0 155.6,116.2 157.5,116.3 157.9,116.2 158.3,116.4 158.7,116.4 159.1,116.6 159.3,117.0 159.0,117.4 158.2,117.9 158.0,118.4 158.6,118.3 158.2,118.3 158.4,117.9 158.9,117.6 159.4,117.1 159.4,117.5 159.5,118.0 159.5,118.6 159.1,118.6 158.6,118.6 158.4,119.0 158.9,119.0 159.4,119.0 159.7,118.6 160.1,118.4 160.1,118.0 160.0,117.6 160.0,117.2 160.4,116.6 160.0,116.2 159.6,116.1 160.0,115.9 159.6,115.5 159.7,115.1 159.6,114.6 159.2,114.5 158.9,113.9 158.5,113.7 158.1,113.9 158.1,113.5 157.7,113.2 158.1,113.1 158.5,113.1 157.9,112.9 157.5,112.9 157.7,112.2 157.4,112.6 156.9,112.8 156.0,112.5 155.5,112.2 155.6,111.8 156.0,112.1 156.6,112.1 156.2,112.1 155.8,111.7 155.8,111.2 156.2,110.9 155.7,110.6 155.7,111.1 155.6,111.5 155.2,111.7 154.8,111.7 154.4,111.7 153.6,111.2 153.0,110.4 152.6,109.9 153.0,109.6 153.2,109.1 153.2,108.7 153.0,109.1 152.8,109.5 152.2,109.8 151.5,109.7 151.3,109.3 151.1,109.8 150.7,109.8 150.1,109.8 149.7,109.7 149.3,109.7 148.9,109.6 149.3,109.4 149.8,109.3 150.3,109.2 149.0,109.3 148.6,109.2 149.0,108.8 148.6,108.8 148.7,108.4 148.2,108.6 147.3,108.6 146.5,108.6 146.0,108.4 145.6,108.1 145.2,107.6 145.5,107.1 146.1,106.6 147.3,106.5 148.0,106.4 147.1,106.3 146.3,106.4 145.6,106.8 145.2,107.0 144.9,106.5 144.8,106.1 145.2,105.6 144.8,105.6 145.0,105.0 145.4,104.9 146.0,104.6 146.6,104.5 147.2,104.7 147.7,105.2 147.5,104.8 146.9,104.5 146.7,104.1 147.2,103.7 147.2,103.2 147.2,103.6 145.8,104.6 145.2,104.7 144.7,105.1 144.5,105.7 144.2,106.1 143.9,105.6 143.5,105.1 144.3,104.7 144.3,104.3 143.9,104.4 143.9,103.8 144.1,103.3 143.4,103.3 143.0,102.4 142.6,102.1 142.0,101.9 141.4,101.8 141.2,101.3 141.6,100.9 142.1,100.8 141.9,101.2 142.9,101.6 143.5,101.5 144.1,101.6 144.6,102.0 144.1,101.5 143.4,101.4 143.0,101.5 142.6,101.2 142.3,100.8 142.9,100.4 142.5,100.2 142.0,100.5 141.6,100.6 141.1,101.0 141.0,101.4 140.4,101.8 139.8,101.8 139.4,101.6 139.1,101.2 138.7,101.2 138.2,101.0 138.2,100.4 138.7,100.1 138.7,99.7 139.5,99.5 139.9,99.4 139.5,99.3 138.7,99.5 138.1,99.3 137.7,98.8 137.9,98.3 138.3,98.0 138.6,97.5 139.0,97.0 139.5,96.4 140.1,95.9 139.7,96.0 139.3,96.3 138.9,96.9 138.9,96.5 138.8,95.9 138.6,95.5 138.7,95.1 138.5,95.6 138.7,96.1 138.4,96.8 138.3,97.4 137.9,97.7 137.4,97.9 136.5,97.8 136.3,97.4 136.0,96.8 136.8,96.4 136.4,95.9 136.4,95.5 136.2,95.0 135.8,94.6 136.0,94.2 135.3,94.4 134.4,94.6 133.9,94.8 133.8,95.2 133.5,95.7 133.0,95.6 132.7,95.2 133.1,94.9 133.8,94.0 134.6,93.9 133.8,93.8 133.4,93.5 133.0,93.3 132.6,93.1 132.3,92.7 132.0,92.3 131.6,92.2 131.1,92.0 131.1,91.6 129.3,91.2 129.3,90.7 129.1,90.1 128.9,89.7 129.6,89.9 130.2,90.1 129.3,89.6 129.1,89.2 130.0,89.3 129.2,89.1 128.8,89.3 128.3,89.1 127.7,88.7 127.6,88.3 128.0,87.6 127.4,88.1 126.9,88.1 126.4,87.9 125.9,87.8 125.2,87.1 124.8,86.7 124.4,85.9 124.1,85.4 124.0,85.0 123.8,85.4 123.9,85.9 124.1,86.4 124.4,87.0 124.6,87.5 124.8,87.9 124.2,88.2 123.8,87.8 123.4,87.7 122.6,87.8 122.6,87.3 122.1,86.7 122.7,86.4 122.2,86.5 121.9,86.1 121.9,86.5 121.4,86.7 121.0,86.5 119.9,86.2 119.5,86.0 119.3,86.5 119.8,86.4 120.2,86.5 120.6,86.7 121.1,86.9 121.6,87.2 122.1,87.6 120.9,88.0 120.5,88.2 119.8,88.0 119.2,87.8 117.9,87.3 117.4,86.9 117.0,86.7 116.6,86.3 116.0,86.0 115.4,85.8 114.7,85.6 114.2,85.5 112.9,85.1 112.3,84.8 111.9,84.7 112.5,84.2 112.3,83.8 112.6,83.4 113.0,83.7 113.0,84.1 113.4,83.8 113.8,83.8 113.2,83.6 112.8,83.3 112.2,83.4 111.5,83.8 110.5,84.1 109.9,84.1 109.3,84.1 108.8,84.0 107.4,83.7 107.5,83.3 107.1,83.3 106.5,83.4 105.3,83.2 104.0,83.1 102.9,83.1 101.4,83.2 100.5,83.3 100.1,83.3 99.6,83.3 99.5,82.9 99.1,82.8 98.5,82.8 98.1,82.6 97.6,82.5 97.6,82.1 97.8,81.7 97.3,81.8 96.8,82.2 96.2,82.3 95.7,82.1 95.2,82.0 94.7,82.0 95.1,81.8 94.0,81.5 93.5,81.3 93.1,81.4 93.0,81.0 92.7,80.6 93.7,80.2 92.8,80.2 92.0,80.5 91.6,80.6 91.1,80.6 90.7,80.8 90.2,80.6 89.8,80.8 89.4,80.9 88.9,80.7 89.6,79.9 88.9,80.3 88.5,80.3 88.1,80.3 88.1,80.7 87.6,81.0 88.1,81.4 88.0,81.8 87.6,81.7 87.2,81.9 87.7,81.9 88.1,81.9 88.6,81.7 89.0,82.0 88.5,82.2 88.3,82.6 88.0,83.0 87.7,83.4 87.3,83.4 86.8,83.5 86.1,83.4 85.4,83.3 85.0,83.0 84.8,83.4 84.5,84.0 84.2,83.6 83.9,84.0 83.4,83.9 82.8,84.5 82.4,84.5 82.0,84.6 81.6,84.6 81.0,85.2 80.4,85.3 80.0,85.3 79.5,85.4 78.8,85.6 78.0,85.4 78.1,85.0 78.6,84.8 79.1,84.8 79.8,84.5 80.4,84.0 79.4,84.3 78.4,84.2 78.4,83.6 78.9,83.1 79.3,82.8 79.7,82.0 79.6,81.5 80.7,81.0 81.2,80.8 82.1,80.5 82.6,80.6 83.0,80.7 83.7,80.7 84.4,80.7 85.6,80.9 84.8,80.7 83.4,80.2 83.7,79.8 84.1,79.5 84.5,79.4 85.2,79.2 84.4,79.2 83.8,79.4 83.4,79.8 83.0,79.8 82.0,79.8 81.6,79.7 80.7,80.0 80.1,80.3 79.3,80.5 78.9,80.6 78.5,80.8 78.5,81.2 77.8,81.4 77.0,81.9 77.0,82.3 76.3,82.6 75.8,82.7 75.2,82.5 75.7,82.8 76.0,83.2 75.7,83.6 74.7,83.7 74.9,84.1 74.3,84.2 73.9,84.3 73.2,84.3 72.9,84.7 72.0,85.1 71.7,85.7 72.5,85.9 73.2,86.0 73.8,86.2 74.0,86.6 73.3,87.1 72.7,87.2 72.2,87.5 72.0,87.9 71.4,88.0 71.5,88.4 71.1,88.5 70.6,88.6 69.4,88.8 69.0,89.2 68.6,89.4 68.0,89.6 67.4,89.9 66.7,90.2 66.2,90.4 65.5,90.7 65.6,91.1 65.0,91.5 64.5,91.7 64.1,91.8 63.7,92.1 63.3,92.2 62.8,92.2 62.4,92.6 62.0,92.7 61.5,92.7 61.0,92.8 60.6,93.1 60.1,93.2 59.6,93.5 60.0,93.6 59.9,94.1 59.5,93.9 59.2,94.3 57.1,94.9 56.8,95.3 56.5,94.9 56.1,94.9 55.7,95.0 54.9,95.4 54.5,95.5 54.0,95.7 53.5,95.8 53.1,95.8 52.7,96.0 52.3,96.1 51.7,96.2 51.5,95.8 51.9,95.7 51.5,95.5 51.1,95.6 50.8,96.1 50.1,96.7 49.5,96.8 49.1,97.1 48.7,97.0 48.6,96.6 48.2,96.6 48.3,97.0 47.7,97.4 47.2,97.1 46.9,97.5 46.4,97.7 46.4,97.2 46.9,96.7 47.3,96.7 47.8,96.4 48.2,96.2 48.6,96.0 49.0,95.6 49.6,95.2 50.2,94.9 50.8,94.7 52.2,94.4 52.9,94.5 53.2,95.1 53.6,94.8 54.1,94.8 54.6,95.0 54.2,94.6 54.3,94.1 54.7,93.6 55.1,93.3 56.2,92.9 57.6,92.5 58.4,92.1 58.9,92.2 59.3,92.1 59.2,91.7 59.8,91.1 60.2,90.8 60.7,90.6 61.4,90.2 61.8,90.1 62.4,90.3 62.0,89.9 62.2,89.2 62.2,88.7 62.7,88.4 63.4,88.3 63.0,88.2 62.5,88.2 62.4,87.7 63.3,87.1 64.0,86.8 64.1,86.2 64.4,85.7 64.0,86.1 63.5,86.5 62.0,86.8 61.1,87.1 60.6,87.2 60.0,86.8 59.8,86.3 60.2,86.1 60.6,86.2 59.9,85.9 59.5,86.1 59.0,86.2 58.8,86.7 59.0,87.4 58.5,87.8 58.1,87.6 57.3,86.9 56.5,86.4 56.0,86.6 55.1,86.4 54.6,86.0 54.1,86.1 53.7,86.2 53.3,86.5 52.2,86.7 51.8,87.0 50.7,87.2 49.6,87.1 50.0,87.0 50.4,86.9 50.8,86.7 50.6,86.2 51.0,85.8 50.6,85.8 50.1,85.7 49.9,85.3 50.4,84.9 50.5,84.5 50.3,84.1 49.6,83.4 49.3,82.8 48.8,82.5 49.2,82.1 49.6,81.6 50.2,81.4 49.8,81.4 49.4,81.6 48.7,82.2 48.3,82.5 48.6,83.0 48.4,83.4 48.0,83.4 47.6,83.5 46.6,83.8 45.3,83.9 44.7,83.9 44.1,83.6 43.1,82.9 42.6,82.5 42.2,82.5 41.5,82.2 41.0,81.9 41.6,81.3 41.9,80.9 42.6,80.9 43.0,81.1 43.4,81.1 43.6,81.5 44.1,81.4 44.5,81.2 44.9,81.5 45.8,81.5 45.8,81.1 44.7,81.0 45.4,80.9 44.5,80.9 43.2,80.9 42.4,80.7 41.5,80.8 41.7,80.4 41.3,80.2 40.9,80.1 40.3,80.3 39.9,80.1 39.8,79.7 39.3,79.6 39.5,79.2 39.0,79.0 38.6,79.1 38.4,78.7 39.4,78.7 38.8,78.5 39.4,78.2 40.0,78.1 39.6,77.7 40.4,76.9 41.1,76.5 41.7,76.3 42.3,76.4 42.8,75.9 42.2,76.0 42.1,75.6 42.2,75.2 42.6,74.9 43.3,74.9 43.2,74.5 44.2,74.3 44.6,74.3 45.2,74.5 45.2,74.9 45.8,74.7 46.2,74.9 47.1,74.8 47.8,74.4 48.3,74.3 49.0,73.7 49.5,73.5 50.1,73.7 51.4,73.7 52.0,73.6 52.5,73.5 53.0,73.2 53.4,72.7 53.0,72.1 52.8,71.5 52.2,71.1 51.7,71.0 52.2,70.8 52.6,70.7 53.0,70.6 53.2,70.1 52.6,69.7 51.5,70.0 51.0,70.0 50.4,70.2 49.5,70.3 49.1,70.5 48.2,71.0 47.8,71.2 47.6,70.8 46.7,70.4 47.1,70.8 46.5,70.9 45.9,70.7 45.2,70.6 43.6,70.6 42.5,70.8 42.1,70.8 41.7,71.0 41.3,70.9 40.4,70.8 38.5,70.6 38.0,70.5 37.6,70.2 37.7,69.6 36.6,69.2 37.4,69.0 38.1,68.7 38.6,68.7 37.2,68.5 35.0,68.3 33.4,67.9 33.3,67.4 34.5,67.3 35.0,67.1 35.9,67.0 36.4,66.8 36.8,66.5 37.4,66.4 37.8,66.3 38.3,66.2 38.7,66.3 39.7,66.4 40.1,66.2 39.3,66.0 40.4,65.5 41.1,65.4 41.5,65.5 42.6,65.1 43.2,65.0 44.3,65.0 45.2,65.0 44.7,65.1 45.1,65.2 44.7,65.6 44.5,66.0 45.3,66.4 46.8,66.5 47.5,66.4 48.0,66.5 48.4,66.5 49.4,66.5 50.2,66.5 51.2,66.0 51.8,66.0 52.2,66.1 52.7,66.1 52.4,65.7 51.3,65.5 50.5,65.6 49.5,64.7 49.1,64.6 48.7,64.6 48.3,64.2 48.7,64.1 49.3,64.1 50.0,64.5 50.3,65.1 51.1,65.4 51.8,65.3 52.3,65.3 53.4,65.6 53.8,65.6 54.9,65.5 54.8,65.1 53.8,65.0 53.2,64.8 52.6,64.9 51.7,65.1 51.2,65.0 50.4,64.7 50.8,64.1 50.1,63.7 48.9,63.8 48.4,63.8 47.9,63.8 47.2,63.8 45.7,63.6 45.2,63.3 44.6,62.6 44.1,62.2 40.6,61.0 39.0,60.7 38.2,60.3 37.8,60.3 37.3,60.2 36.7,60.1 37.1,60.0 37.7,60.0 38.1,59.5 38.4,58.9 40.3,58.7 41.5,58.7 42.0,58.6 43.6,58.5 44.0,58.4 44.8,58.2 45.7,57.9 46.5,57.4 46.9,57.1 47.0,56.6 47.4,56.2 49.0,55.3 49.8,54.9 50.3,54.6 50.7,54.8 50.2,55.0 49.8,55.1 50.6,55.0 51.0,54.9 52.8,54.7 53.8,54.4 55.2,53.9 55.7,54.0 55.3,54.2 55.3,54.6 55.8,54.6 56.1,54.2 57.3,54.1 56.3,54.1 55.7,53.8 55.3,53.8 56.4,53.4 57.5,53.1 57.5,53.5 58.1,53.4 59.4,53.3 59.8,53.2 61.1,53.2 62.2,52.9 63.0,52.7 63.4,52.5 64.0,52.1 64.5,51.9 65.4,51.6 65.4,52.0 67.2,52.3 67.7,52.3 67.2,52.8 66.3,53.0 66.7,53.2 67.5,53.2 68.6,52.7 69.0,52.5 69.6,52.5 70.0,52.6 70.4,52.8 70.0,53.1 70.6,53.2 71.1,53.2 71.7,53.3 72.5,53.1 73.1,53.1 73.6,53.1 74.4,53.0 75.6,53.1 76.4,53.1 76.9,53.2 76.5,53.7 77.0,53.8 78.4,54.0 77.9,54.3 79.9,54.4 80.6,54.3 81.5,54.1 82.2,54.3 82.6,54.4 83.7,54.1 84.6,54.1 85.0,54.2 85.4,54.2 86.5,54.4 87.0,54.4 87.6,54.7 88.2,54.6 88.8,54.7 89.3,54.7 89.7,55.0 91.5,55.1 92.4,55.0 93.7,55.0 94.3,55.1 94.9,55.1 96.0,55.4 96.6,55.5 98.3,55.6 98.8,55.4 99.8,55.4 100.7,55.3 101.2,55.3 101.8,55.3 102.2,55.2 103.6,55.5 104.4,55.7 106.4,56.2 106.9,56.3 107.4,56.5 108.1,56.5 108.7,56.6 110.0,56.7 111.2,56.6 113.4,56.9 114.8,57.5 115.9,57.7 116.3,57.9 117.0,58.1 118.7,58.4 119.3,58.5 120.2,58.6 120.8,58.6 121.9,58.7 122.6,58.8 124.0,59.2 124.4,59.2 123.9,58.8 123.2,58.6 122.6,58.6 123.1,58.3 122.6,58.1 123.1,57.5 123.6,57.4 124.2,57.5 124.5,57.1 125.4,57.0 126.4,57.0 126.5,56.6 127.1,56.5 127.6,56.8 128.1,56.9 127.7,57.3 127.3,57.6 127.9,57.5 128.6,57.3 129.2,57.2 129.7,57.2 130.1,57.1 130.5,56.9 131.0,56.5 131.9,56.5 132.4,56.2 132.9,56.4 133.5,56.2 134.5,55.9 134.9,55.8 135.5,55.8 136.0,55.6 137.0,55.2 137.5,55.2 138.1,55.3 138.8,55.3 139.2,55.3 139.6,55.0 140.2,55.3 137.6,56.2 136.9,56.4 136.2,56.6 135.5,56.6 134.8,56.7 133.7,56.8 133.0,57.2 132.4,57.5 132.0,57.6 131.4,57.6 130.6,58.0 129.9,58.4 129.5,58.6 129.6,59.0 130.0,59.1 129.6,58.8 131.4,58.8 131.9,58.7 131.4,58.6 131.2,58.2 131.8,57.9 132.3,57.9 132.7,57.8 133.6,57.5 133.9,57.1 134.4,57.1 135.2,57.1 135.7,57.1 136.1,57.1 136.2,57.6 137.1,57.0 137.5,56.8 137.9,56.5 138.6,56.3 140.1,56.0 140.9,56.0 141.4,55.9 142.0,55.6 141.3,56.0 141.5,56.4 142.1,56.2 142.5,56.1 143.4,55.7 143.7,55.3 144.2,55.1 145.1,54.9 144.5,54.7 144.1,54.5 144.1,54.1 144.5,54.0 145.1,54.1 146.2,54.5 146.6,54.7 147.4,55.4 147.9,56.0 148.3,56.3 149.3,56.8 149.8,57.0 150.3,57.2 150.8,57.3 151.3,57.4 151.7,57.4 152.3,57.1 151.8,56.6 152.2,56.3 152.6,56.1 153.1,55.7 153.5,55.3 154.0,55.1 154.3,55.6 154.4,56.2 155.2,56.4 154.5,57.0 155.2,57.3 156.6,57.3 157.1,57.2 157.6,56.9 158.0,56.3 158.5,56.1 158.9,56.1 160.0,56.1 160.9,56.1 161.8,56.1 162.4,56.2 163.0,56.3 164.0,56.5 164.4,56.6 165.9,57.2 166.3,57.3 167.1,57.4 169.8,57.6 170.2,57.7 170.9,57.9 171.4,58.1 172.0,58.2 172.7,58.3 174.1,58.5 174.6,58.6 176.3,58.7 176.9,58.7 177.6,58.8 177.1,58.4 178.0,58.4 178.8,58.4 179.3,58.5 179.9,58.6 180.6,58.7 181.6,59.0 182.2,59.3 182.7,59.6 183.1,59.9 183.3,60.3 182.6,60.4 181.2,60.4 180.2,60.7 180.1,61.1 179.4,61.3 179.8,61.5 180.2,61.6 181.0,61.6 181.5,61.7 182.1,61.8 182.6,61.9 183.2,61.9 183.6,61.9 184.2,61.9 185.5,61.9 185.9,62.0 186.4,62.0 187.5,62.0 188.0,61.9 188.6,61.9 189.7,61.8 190.1,61.8 190.9,61.6 191.4,61.7 192.2,61.6 193.4,61.2 193.8,61.2 194.2,61.1 194.6,61.4 195.1,61.6 195.5,61.9 196.6,61.9 197.0,61.9 197.3,62.4 197.7,62.7 198.0,62.3 198.4,62.2 199.0,62.8 200.0,63.2 200.0,63.6 199.4,63.7 199.0,63.7 198.6,63.6 199.4,64.1 199.9,64.3 200.7,64.8 201.4,65.3 202.0,65.6 201.2,64.9 200.8,64.6 200.7,64.0 201.4,64.1 201.9,64.1 202.3,64.2 201.9,63.8 201.4,63.3 201.0,62.8 200.7,62.3 200.1,61.9 200.3,61.5 200.7,61.4 200.6,61.0 201.4,60.9 201.8,60.9 202.2,60.9 202.8,60.8 203.2,60.8 203.6,60.5 204.1,60.5 204.8,60.0 205.2,60.0 205.7,59.9 206.2,59.7 205.7,59.3 204.9,59.5 204.3,59.7 203.9,60.1 203.4,60.0 202.9,60.1 202.4,60.3 202.0,60.3 201.4,60.2 200.7,60.3 199.9,60.7 199.3,60.7 198.1,60.3 199.0,59.5 200.7,59.3 201.6,59.2 203.3,58.9 204.7,58.6 205.1,58.6 205.5,58.6 206.1,58.7 206.7,58.9 207.1,59.5 207.3,60.0 207.8,60.2 208.2,60.3 208.6,60.4 209.0,60.4 209.4,60.8 209.8,60.9 210.6,61.0 211.4,61.0 212.1,60.9 212.6,60.8 213.0,60.9 213.8,61.3 214.3,61.5 214.7,61.6 215.6,61.8 216.1,61.9 216.5,61.8 217.0,61.8 217.5,61.9 217.9,62.0 219.2,61.8 219.8,61.7 220.2,61.6 220.8,61.6 221.6,61.6 222.9,61.6 223.7,61.7 224.2,61.8 224.6,61.9 225.2,61.9 225.8,61.7 226.5,61.7 226.1,61.4 225.8,61.0 226.3,61.0 227.6,61.8 228.0,61.9 228.9,62.1 229.3,62.2 229.8,62.0 230.2,61.7 229.6,61.4 229.0,61.2 228.5,61.2 228.0,61.2 227.5,61.4 226.6,60.9 226.4,60.5 226.0,60.2 226.5,60.1 227.2,60.2 228.4,60.0 228.0,59.9 228.8,59.8 229.2,59.7 229.6,59.8 230.2,60.1 230.6,60.4 231.6,60.4 232.1,60.2 231.3,60.9 231.7,61.0 232.1,60.7 233.1,60.5 232.9,61.6 232.7,62.0 232.3,62.4 232.7,62.8 233.3,63.1 233.7,63.1 234.1,63.0 234.0,63.4 234.4,63.3 235.0,63.5 235.0,63.9 234.6,64.0 234.0,64.0 233.5,63.9 233.1,63.9 232.7,63.9 232.2,63.7 233.7,64.6 233.9,65.0 233.3,64.3 234.4,64.1 234.8,64.1 235.2,63.5 235.3,62.9 235.0,62.5 234.4,61.9 234.8,61.1 235.5,60.9 236.2,61.0 236.8,60.9 237.5,60.6 238.2,60.3 238.6,60.0 239.1,59.8 239.9,59.6 240.3,59.4 239.9,59.4 239.8,58.9 239.7,58.5 239.3,58.3 238.9,58.8 238.3,59.0 237.6,59.0 237.2,58.9 237.6,58.4 238.2,58.2 238.7,58.0 238.3,58.0 238.1,57.6 238.5,57.4 239.3,57.3 239.9,57.2 239.4,57.6 240.1,57.5 240.5,57.3 239.9,56.9 239.5,56.9 239.1,57.1 238.4,57.1 238.0,57.0 237.5,56.7 237.1,56.5 236.7,56.7 235.3,56.5 234.7,56.3 234.1,56.2 233.4,56.1 233.0,55.9 232.6,55.6 232.0,55.2 231.8,54.6 232.4,54.2 233.0,54.0 233.7,54.0 233.7,53.6 232.8,53.8 232.3,53.7 231.8,53.3 232.0,52.8 231.9,52.4 232.2,52.0 232.6,51.8 233.2,51.6 233.8,51.8 234.4,51.9 234.9,51.5 234.2,51.4 233.8,51.3 234.4,50.9 235.6,50.3 236.4,50.1 236.9,50.0 237.3,50.1 238.0,50.7ZM275.7,103.5 275.0,103.4 274.0,103.2 272.7,102.9 272.2,102.7 272.5,102.3 274.1,102.2 274.6,102.2 275.3,102.7 275.7,103.0 275.8,103.4ZM295.5,61.7 294.1,61.7 293.4,61.7 292.9,61.6 292.4,61.2 293.4,60.9 294.1,60.9 294.8,61.0 295.6,61.1 296.0,61.2 296.1,61.6 295.5,61.7ZM283.7,73.7 284.2,73.8 284.6,74.2 283.9,74.6 283.5,74.7 282.6,74.3 282.0,74.0 282.7,73.6 283.5,73.7ZM272.2,75.1 272.3,75.5 271.9,76.0 271.1,76.3 270.6,76.7 269.4,77.2 268.7,77.1 267.5,77.3 267.3,76.9 266.9,76.7 267.4,76.2 268.4,75.3 269.1,75.3 269.5,75.4 270.3,75.2 270.9,75.2 271.6,75.1 272.1,75.1ZM279.0,76.6 279.6,77.0 279.7,77.5 279.5,77.9 279.1,78.3 278.7,78.8 278.3,78.9 277.8,78.6 277.2,78.4 277.0,77.8 277.1,77.4 277.7,76.8 278.1,76.7 278.6,76.7 279.0,76.6ZM209.6,35.7 209.1,35.8 208.3,35.7 207.7,35.6 207.3,35.4 206.8,35.1 206.4,34.8 206.0,34.5 205.4,34.1 205.9,34.0 206.7,34.1 207.1,34.2 207.5,34.3 208.1,34.6 208.5,34.9 209.0,35.0 209.6,35.2 209.7,35.7ZM234.8,33.9 235.5,34.0 236.2,34.0 237.0,34.0 238.9,34.0 240.1,34.0 240.8,34.1 241.3,34.3 240.7,34.4 240.2,34.8 239.6,34.8 237.8,34.8 233.4,34.8 232.6,34.5 232.9,34.1 234.2,33.9 234.8,33.9ZM225.6,27.8 225.6,28.2 225.2,28.5 224.4,28.4 223.6,28.1 222.6,28.1 222.2,28.1 221.9,27.7 222.8,27.4 223.8,27.4 224.6,27.4 225.0,27.5 225.5,27.7ZM250.5,35.4 249.7,35.5 247.2,35.2 246.8,35.0 246.7,34.6 247.2,34.3 247.7,34.3 248.1,34.3 248.8,34.4 249.5,34.5 250.4,34.7 250.8,34.9 250.7,35.3ZM240.2,41.6 240.4,42.1 240.1,42.5 238.9,42.7 238.3,42.6 237.4,42.7 237.0,42.7 236.2,42.5 235.3,42.2 234.9,42.2 233.7,42.1 233.1,41.9 232.6,41.9 231.8,41.7 232.3,41.1 232.8,41.0 233.5,40.4 234.3,40.2 236.0,39.9 236.5,39.9 237.1,39.9 237.7,40.0 238.2,40.2 239.1,40.5 239.6,40.7 240.0,41.0 240.2,41.4ZM195.0,31.5 195.4,31.7 195.8,31.7 196.2,32.0 196.1,32.4 195.3,32.5 194.4,32.4 193.6,32.5 192.4,32.5 191.6,32.3 191.2,32.3 190.8,32.4 190.2,32.6 189.6,32.5 188.5,32.3 187.3,32.4 186.1,32.5 185.6,32.5 186.5,32.0 187.1,31.9 188.3,31.8 189.7,31.7 190.2,31.7 190.6,31.5 191.5,31.4 192.0,31.3 192.7,31.2 193.3,31.2 194.1,31.4 194.6,31.4 195.0,31.5ZM171.3,40.1 170.5,40.2 170.0,40.2 169.2,40.1 168.6,40.0 169.4,39.5 170.5,39.2 171.2,39.0 171.8,38.9 172.5,38.7 172.9,38.6 173.6,38.6 173.3,39.0 172.5,39.4 171.6,40.0ZM207.5,47.4 207.1,47.4 206.7,47.3 206.1,47.0 205.4,46.7 204.1,46.1 203.5,46.0 203.0,45.9 203.6,45.4 204.6,45.2 206.9,45.1 207.5,45.1 208.0,45.2 208.8,45.4 209.3,45.5 209.6,45.9 209.4,46.4 208.9,46.8 208.4,47.0 207.8,47.4ZM222.2,44.6 224.6,45.2 225.6,45.1 226.3,45.0 227.4,44.9 228.0,44.8 228.7,44.8 229.6,44.8 230.1,44.9 230.5,45.3 230.1,45.6 229.5,45.7 228.9,45.7 229.3,45.9 229.8,46.0 229.2,46.3 228.3,46.4 227.3,46.9 226.7,47.1 227.3,47.2 227.9,47.1 228.5,47.1 229.2,47.2 229.6,47.4 230.3,47.9 230.2,48.3 230.9,48.1 231.5,48.0 232.0,48.3 232.0,48.8 231.6,49.1 231.1,49.1 231.5,49.2 231.6,49.6 231.2,49.9 231.6,50.1 231.6,50.5 230.7,50.6 230.2,50.8 229.3,51.0 228.9,51.0 227.3,50.9 226.9,50.7 226.5,50.6 227.1,51.2 227.2,51.6 226.6,51.8 225.9,51.9 225.3,51.8 224.5,51.8 223.9,51.2 223.4,51.0 223.0,50.7 221.9,50.2 221.3,50.0 220.6,49.6 220.0,49.4 219.5,49.4 218.9,49.1 218.1,49.2 217.4,49.1 216.9,48.8 215.6,48.3 214.8,48.0 214.9,47.5 215.3,47.2 215.7,47.0 216.1,47.0 216.6,47.0 217.1,47.2 217.7,47.5 218.2,47.7 218.7,48.0 219.2,48.0 219.7,48.0 220.9,47.9 221.1,47.3 221.6,47.5 222.0,47.4 221.6,47.0 221.0,46.9 221.0,46.5 221.6,46.5 222.0,46.6 222.7,46.6 222.2,46.6 221.5,46.3 220.8,46.3 220.1,46.4 218.2,46.0 218.5,45.6 219.1,45.6 219.5,45.6 219.9,45.6 220.3,45.8 220.8,46.0 220.5,45.6 220.1,45.5 219.7,45.4 219.6,45.0 220.9,44.9 221.7,45.0 222.2,45.0 221.8,44.9 222.2,44.6ZM264.1,68.7 264.5,68.6 265.0,68.2 265.9,68.4 266.3,68.8 266.9,68.9 267.4,69.0 268.1,69.1 268.8,69.5 269.5,69.7 270.4,70.1 271.5,70.2 272.1,70.4 272.8,71.0 273.1,71.6 272.5,72.1 273.0,72.2 274.1,72.0 274.7,72.1 275.2,71.9 275.9,72.2 276.5,72.6 277.1,72.8 276.4,73.1 275.8,73.3 275.1,73.7 274.5,73.7 274.0,73.5 272.3,73.2 271.8,73.1 271.2,73.0 270.9,72.4 269.6,72.2 269.3,71.8 268.9,71.8 268.1,71.9 267.7,72.2 267.6,72.6 266.6,73.2 265.9,73.3 265.3,73.9 264.9,74.1 264.5,74.3 264.0,74.5 263.2,74.6 262.8,74.7 262.3,74.2 261.9,73.2 260.3,73.2 259.5,73.2 258.8,73.4 258.2,73.4 257.8,73.3 258.2,72.7 258.7,72.4 259.9,72.1 260.3,72.0 260.4,71.6 260.1,71.2 260.1,70.8 260.2,70.4 260.5,69.7 260.8,68.3 261.1,67.7 261.6,67.1 262.4,66.9 263.2,67.2 263.6,67.7 263.2,68.0 263.7,68.2 264.1,68.7ZM289.8,60.2 291.2,60.5 291.5,60.9 291.4,61.8 291.3,62.4 290.8,62.8 289.5,63.1 288.8,63.2 288.0,63.2 287.0,63.2 286.5,63.2 286.1,63.1 285.7,62.8 285.3,62.0 285.5,61.5 286.3,60.9 286.8,60.5 287.2,60.3 287.9,60.2 288.4,60.3 288.9,60.2 289.3,60.2 289.8,60.2ZM279.1,45.4 279.5,45.4 282.5,45.4 283.2,45.4 285.1,45.7 285.5,45.8 286.0,46.2 286.8,46.4 287.2,46.6 287.6,46.9 288.0,46.9 288.2,47.3 288.6,47.6 287.8,47.7 286.1,47.7 283.8,47.5 282.5,47.6 281.8,47.6 280.2,47.9 279.7,47.9 279.2,47.9 278.3,47.7 277.8,47.5 277.5,47.0 277.3,46.6 276.1,46.5 275.6,46.3 275.6,45.9 275.4,45.4 276.1,45.1 276.6,45.1 277.4,45.3 278.1,45.3 279.1,45.4ZM228.6,37.6 228.5,38.0 229.0,38.3 228.9,38.7 228.7,39.1 228.1,39.6 229.3,39.8 229.6,40.5 228.7,40.3 228.1,40.5 228.2,40.9 228.6,41.1 228.2,41.3 227.7,41.1 227.9,41.5 227.4,41.6 227.0,41.6 226.2,41.6 225.8,41.6 225.0,41.6 224.6,41.6 224.1,41.5 223.3,41.7 222.4,41.7 221.6,41.6 221.2,41.5 220.9,41.1 221.8,41.0 221.2,40.9 220.5,40.8 221.4,40.4 222.3,40.1 222.9,40.0 223.4,39.8 224.4,39.8 222.5,39.8 219.7,39.9 218.9,40.0 218.2,40.0 215.2,40.2 214.7,40.2 215.5,39.7 216.0,39.5 216.8,39.2 217.8,39.4 218.3,39.5 218.7,39.6 219.1,39.5 219.5,39.4 218.7,39.5 218.3,39.3 218.2,38.9 217.5,38.9 217.0,38.7 218.0,38.3 216.9,38.2 216.3,38.1 216.8,37.8 217.3,37.6 218.3,37.7 219.1,37.9 219.7,38.3 221.6,38.9 222.2,39.1 222.6,39.1 223.1,39.0 222.3,38.8 221.9,38.6 222.8,38.5 223.5,38.5 222.7,38.4 222.2,38.3 221.7,38.3 221.1,38.2 222.1,38.1 221.7,37.9 220.4,37.8 219.9,37.7 220.6,37.3 221.1,37.2 222.0,37.1 222.7,37.1 223.1,37.2 224.1,37.4 224.5,37.6 225.3,37.6 224.9,37.2 225.8,37.0 226.3,37.0 227.0,37.2 227.9,37.4 228.3,37.4ZM212.7,29.7 213.4,29.7 214.1,29.9 214.9,30.3 215.0,30.7 215.5,30.7 216.1,30.4 216.7,30.3 217.5,30.3 218.6,30.6 219.0,30.6 219.4,30.8 219.0,31.0 219.7,31.2 221.0,31.1 222.2,31.3 222.8,31.6 223.3,31.7 222.7,32.1 222.9,32.5 223.4,32.6 224.6,33.0 225.0,33.3 224.6,33.7 224.1,33.8 223.2,33.8 222.3,33.9 221.5,33.8 220.6,33.6 220.1,33.4 219.6,33.0 219.2,32.8 218.6,32.8 217.1,32.6 216.5,32.6 215.9,32.6 215.0,32.6 214.5,32.5 212.0,32.4 211.3,32.6 210.2,32.6 209.7,32.5 209.0,32.4 208.4,32.0 208.8,31.7 210.5,31.8 211.8,31.9 212.3,31.8 211.1,31.6 212.3,31.4 212.9,31.3 212.5,31.2 211.1,31.2 210.6,31.2 211.5,30.8 211.1,30.7 210.7,30.6 210.0,30.7 209.1,31.0 208.6,31.1 209.1,30.6 208.6,30.4 207.5,30.5 206.8,30.5 206.8,30.1 207.1,29.7 208.8,29.7 211.2,29.6 211.9,29.6 212.7,29.7ZM244.8,24.6 246.5,25.4 247.1,25.6 248.1,25.9 248.5,26.2 249.4,26.3 250.4,26.4 250.9,26.3 251.3,26.3 251.9,26.3 252.3,26.4 252.4,26.8 252.7,27.2 253.2,27.3 254.1,27.4 254.6,27.4 255.0,27.5 254.5,27.2 253.9,27.1 253.8,26.7 254.4,26.6 255.2,26.6 255.7,26.6 256.5,26.7 256.6,27.2 255.9,27.4 256.5,27.6 257.4,27.6 257.8,27.7 258.4,28.1 257.9,28.7 257.5,28.9 258.5,28.9 259.3,28.8 260.2,28.8 260.6,28.9 261.1,29.2 261.7,29.0 262.1,28.9 262.5,29.1 263.4,29.5 263.8,29.8 263.4,29.9 260.9,30.3 259.9,30.4 259.4,30.6 258.6,30.6 258.1,30.9 257.7,31.1 257.0,31.3 256.6,31.5 255.9,31.4 255.7,31.0 256.1,30.5 255.4,30.6 255.0,30.9 255.1,31.3 255.5,31.6 255.4,32.0 254.8,32.0 253.9,31.7 253.5,31.7 253.9,32.2 253.5,32.7 252.9,32.8 252.5,32.8 251.5,32.3 251.0,32.1 250.2,31.7 249.8,31.8 250.7,32.3 251.1,32.6 250.4,32.7 249.9,32.5 249.2,32.4 248.7,32.4 248.3,32.4 248.7,32.6 249.1,32.8 248.3,32.9 247.5,32.9 246.1,32.8 244.7,32.7 243.5,32.5 242.6,32.3 242.2,32.1 243.6,31.9 244.8,31.8 242.5,31.7 241.7,31.6 240.9,31.6 240.1,31.4 241.1,31.2 240.7,31.1 239.2,30.9 238.6,30.8 239.0,30.5 240.9,30.2 241.5,30.1 242.1,30.1 242.5,30.0 242.9,29.8 244.8,29.7 246.3,29.6 245.3,29.5 243.8,29.5 243.1,29.3 242.7,29.3 242.2,29.3 241.6,29.4 240.6,29.5 240.1,29.6 239.1,29.7 238.6,29.7 239.1,29.5 238.6,29.4 238.1,29.4 236.5,29.6 236.0,29.7 235.2,29.6 234.3,29.5 234.3,29.1 235.3,29.0 237.4,28.7 237.8,28.5 237.3,28.5 236.2,28.7 235.3,28.7 234.6,28.7 234.1,28.7 233.7,28.7 233.3,28.6 232.1,28.2 231.7,28.0 231.2,27.4 233.9,27.6 235.0,27.6 237.1,27.6 238.0,27.3 237.3,27.2 235.6,27.4 235.0,27.4 234.3,27.1 233.6,27.2 232.7,27.1 232.3,27.0 233.0,26.7 234.0,26.8 234.6,26.8 233.6,26.5 232.9,26.2 233.5,25.8 234.1,25.8 234.7,25.9 235.5,25.9 236.0,26.0 236.4,26.2 236.9,26.2 237.5,26.2 239.1,26.2 238.3,26.1 237.2,26.0 236.7,25.7 235.6,25.5 234.7,25.5 235.4,25.0 236.2,24.9 237.4,24.9 238.3,24.8 239.4,24.7 240.4,24.8 240.9,24.7 240.5,24.4 239.2,24.4 238.6,24.4 238.2,24.2 238.7,24.0 240.0,24.0 240.7,24.0 241.6,24.0 243.3,24.2 243.9,24.3 244.5,24.5ZM238.1,36.4 238.6,36.4 239.0,36.3 239.4,36.3 240.0,36.5 240.5,36.6 240.9,36.7 240.9,37.1 240.5,37.4 241.7,37.2 242.5,37.2 243.6,37.2 245.0,37.0 245.7,37.0 246.4,37.0 246.9,37.0 248.0,37.3 248.5,37.5 247.6,37.5 246.5,37.5 246.1,37.5 247.6,37.7 252.0,38.1 249.1,38.5 247.7,38.4 246.5,38.2 246.1,38.3 247.2,38.5 247.8,38.6 249.3,38.7 249.9,39.0 250.6,39.1 251.0,39.3 251.4,39.3 252.0,39.5 251.8,39.9 251.0,40.0 251.8,40.1 253.0,40.4 253.2,40.0 253.6,39.8 254.0,39.9 255.0,40.2 256.3,40.1 256.7,40.3 257.3,40.0 258.9,40.3 259.6,40.4 260.5,40.5 261.2,40.6 261.2,40.2 262.3,40.1 262.9,40.1 263.9,39.9 264.6,39.9 265.0,39.9 266.3,39.5 266.9,39.4 267.4,39.4 268.8,39.6 269.2,39.6 270.7,39.4 271.2,39.4 271.8,39.4 273.2,39.5 274.3,39.6 274.7,39.8 276.3,39.9 276.9,39.9 277.3,40.1 278.5,40.4 278.9,40.6 278.8,41.1 277.8,41.3 276.8,41.5 277.4,41.7 278.5,41.6 279.1,41.7 277.9,42.1 277.2,42.0 276.8,41.9 277.3,42.1 277.2,42.5 274.4,42.9 273.3,43.0 272.8,43.1 272.4,43.1 272.0,43.1 271.1,43.0 270.4,43.0 269.6,42.9 269.1,42.5 268.8,42.1 268.3,42.0 267.9,42.0 268.4,42.2 268.4,42.6 268.0,42.8 267.0,42.9 266.0,43.0 265.5,43.0 264.8,43.0 264.4,42.9 263.9,42.8 263.5,43.0 262.9,42.9 262.5,42.9 261.6,43.1 261.2,43.1 260.8,42.9 260.2,43.0 259.3,42.9 258.4,43.1 257.3,43.0 256.7,43.1 255.5,43.1 254.4,43.1 254.0,42.9 254.5,42.4 254.1,42.1 253.7,42.2 253.2,42.5 252.8,42.3 252.2,42.4 252.1,42.8 251.5,42.9 250.4,42.9 250.0,42.9 249.0,42.7 248.5,42.7 247.8,42.5 247.3,42.5 246.9,42.4 246.3,42.6 245.8,42.6 245.4,42.5 244.8,42.4 244.2,41.8 244.3,41.4 243.9,41.2 243.5,41.0 243.3,40.6 244.1,40.0 244.2,39.6 243.6,39.1 243.1,38.9 242.5,38.6 242.0,38.3 241.4,37.9 240.8,37.9 240.1,38.0 239.3,38.1 237.8,38.1 237.3,38.1 236.8,38.1 236.1,38.2 235.4,38.2 234.9,37.9 233.8,37.7 233.4,37.7 233.9,37.4 234.3,37.3 233.7,37.3 231.6,36.9 231.0,36.9 231.4,36.8 232.1,36.7 231.5,36.5 231.1,36.3 231.8,36.1 232.3,36.1 233.2,36.0 233.8,35.9 234.3,35.9 235.8,36.1 237.2,36.2 238.1,36.4ZM232.8,31.9 233.4,31.9 234.6,31.9 235.0,32.0 236.0,32.1 236.4,32.3 235.4,32.6 235.8,32.8 236.3,33.0 235.9,33.4 235.1,33.4 234.3,33.5 233.3,33.6 232.0,33.7 231.0,33.9 230.6,33.9 230.3,33.5 229.4,33.4 228.8,33.2 229.9,33.0 230.4,33.0 229.7,32.8 228.3,32.7 227.6,32.4 227.1,32.1 227.6,31.8 227.0,31.4 227.7,31.1 228.9,31.1 229.5,31.2 230.1,31.2 230.7,31.3 231.7,31.4 232.6,31.7ZM193.2,33.0 195.4,33.1 195.1,33.5 193.9,33.6 192.4,33.7 192.0,33.8 192.4,33.9 193.6,33.9 194.0,34.0 194.1,34.4 193.4,34.7 192.6,34.9 192.0,34.9 191.5,34.9 191.0,34.9 189.0,35.2 188.4,35.2 187.9,35.1 187.1,34.9 186.3,34.8 185.7,34.6 185.6,34.2 185.3,33.8 186.1,33.6 186.7,33.5 188.0,33.3 191.1,33.1 192.0,33.1 192.4,33.1 193.2,33.0ZM179.0,35.1 177.2,35.6 177.6,36.0 178.2,36.2 177.5,36.3 177.1,36.4 177.7,36.7 177.6,37.1 177.2,37.2 176.5,37.3 175.0,37.4 174.9,37.8 174.4,38.1 174.0,38.1 173.6,38.1 173.0,38.0 172.2,37.8 172.3,37.3 172.8,36.9 172.0,36.7 171.4,36.8 171.0,37.3 170.6,37.4 170.2,37.4 170.5,37.9 170.0,38.1 169.6,38.4 169.2,38.5 168.8,38.4 168.4,38.3 168.0,37.9 167.6,38.1 167.7,38.5 168.0,38.9 167.6,39.0 166.9,39.3 166.2,39.3 165.7,39.4 165.2,39.0 164.6,38.5 164.2,38.4 163.8,38.8 163.3,38.9 162.7,38.9 162.0,38.8 161.4,38.8 161.0,38.8 160.3,39.0 159.6,39.0 159.6,38.6 159.2,38.4 158.6,38.5 159.0,38.3 159.7,37.9 160.1,37.8 162.2,37.7 163.3,37.2 163.9,37.0 165.3,36.7 165.7,36.4 166.1,36.3 167.1,35.9 168.1,35.6 168.5,35.4 169.2,35.3 169.9,35.2 172.2,35.1 173.8,35.2 174.2,35.2 174.6,35.1 175.4,35.2 175.8,35.1 174.9,34.9 175.5,34.6 176.4,34.6 176.8,34.6 177.2,34.7 177.8,34.8 179.0,35.1ZM199.2,38.7 200.0,38.7 200.4,38.7 200.8,38.9 200.1,39.3 200.8,39.2 201.3,39.2 202.2,39.2 202.6,39.3 203.0,39.8 203.6,39.4 203.2,39.1 203.7,38.8 204.1,38.7 204.5,38.7 205.8,38.9 206.4,39.0 206.8,39.2 207.0,39.6 206.5,40.3 205.9,41.1 205.3,41.4 203.9,41.6 202.9,41.8 202.4,41.9 201.5,41.8 200.5,41.7 199.9,41.7 199.4,41.8 199.0,41.8 198.4,41.8 197.9,41.7 197.2,41.6 195.8,42.0 194.0,42.1 193.4,42.2 192.9,42.3 192.4,42.5 191.8,42.7 190.9,42.8 189.6,43.1 187.4,43.3 186.1,43.3 184.7,43.2 184.2,43.2 183.8,43.1 182.9,42.8 182.3,42.6 183.0,42.3 183.7,42.2 185.2,42.0 186.6,41.7 187.1,41.7 188.4,41.6 189.0,41.7 189.5,41.6 190.3,41.5 191.0,41.3 191.5,41.1 190.4,41.1 189.9,41.2 189.5,41.2 188.9,41.3 188.3,41.3 187.6,41.1 187.2,41.1 186.7,41.3 186.2,41.4 185.2,41.4 184.1,41.5 183.7,41.3 183.7,40.9 184.7,40.6 183.7,40.6 183.3,40.5 183.0,40.9 182.5,41.0 182.1,40.9 182.3,41.3 180.9,41.7 180.5,41.7 180.1,41.5 179.6,41.3 179.2,41.4 178.7,41.7 177.4,41.6 176.5,41.2 175.4,41.2 175.0,41.2 173.6,41.1 173.6,40.7 174.1,40.4 174.6,40.4 175.3,40.3 177.2,40.3 177.6,40.3 179.6,40.0 180.2,39.8 177.7,40.0 176.6,40.0 174.9,40.0 174.5,39.9 174.9,39.7 175.6,39.5 176.7,39.4 178.2,39.3 179.2,39.3 180.1,39.3 180.6,39.2 178.9,39.2 176.8,39.2 176.0,39.1 176.1,38.7 176.5,38.5 177.2,38.4 177.6,38.3 178.4,38.4 180.7,38.4 181.2,38.4 180.5,38.3 178.7,38.2 178.3,38.2 178.9,37.7 180.6,37.5 181.2,37.5 181.9,37.5 182.5,37.6 182.9,37.7 183.2,38.1 183.8,38.3 185.1,38.2 185.6,38.2 186.2,38.2 187.0,38.3 188.0,38.7 189.3,39.1 188.8,39.3 189.2,39.4 189.7,39.4 190.1,39.4 190.6,39.8 191.2,40.1 192.0,40.1 192.4,40.1 193.2,40.1 197.0,40.3 197.4,40.2 197.5,39.8 195.0,39.3 196.0,38.8 195.3,38.3 194.7,38.3 193.9,38.1 193.7,37.7 194.5,37.5 195.8,37.0 196.3,36.8 197.0,36.6 197.7,36.6 198.5,36.8 198.2,37.2 198.5,37.6 199.0,37.8 199.5,38.0 199.2,38.5ZM241.2,44.0 242.3,44.1 242.8,44.2 243.6,44.5 244.8,44.4 245.5,44.4 247.0,44.4 248.3,44.6 248.7,44.7 248.7,45.1 247.9,45.6 247.4,45.8 247.0,46.1 246.5,46.4 245.7,46.6 245.5,47.2 245.0,47.5 244.1,47.9 243.4,48.0 240.7,47.8 240.1,47.8 238.3,47.9 239.1,48.0 239.5,48.1 240.1,48.4 240.2,48.8 239.3,49.3 238.8,49.9 237.5,49.9 236.1,50.0 235.6,49.9 235.7,49.5 235.6,49.0 235.4,48.6 234.6,47.8 234.4,47.4 234.5,46.9 234.4,46.3 234.3,45.7 234.4,45.3 234.9,45.1 236.1,45.3 236.6,45.4 237.0,45.4 236.4,45.2 235.9,45.0 235.7,44.6 236.2,44.3 236.9,44.2 237.6,44.1 239.1,44.1 239.5,44.1 240.1,44.0 240.5,43.9 241.2,44.0ZM229.3,56.5 229.7,56.5 230.3,56.6 230.9,56.9 231.4,57.0 232.5,57.4 233.2,58.0 233.7,58.5 234.5,58.8 234.9,58.7 235.4,58.8 234.8,59.0 234.4,59.0 233.9,59.2 233.3,59.4 232.6,59.7 232.2,59.8 231.7,59.8 230.5,59.6 229.8,59.6 229.2,59.6 228.6,59.4 228.1,59.2 227.1,59.1 226.7,58.8 226.3,58.9 225.8,58.9 225.4,58.7 224.8,58.6 224.3,58.7 223.8,58.6 223.4,58.2 224.8,57.9 225.2,57.9 225.8,57.7 226.4,57.5 226.3,57.1 226.3,56.7 226.7,56.8 227.2,57.0 227.7,57.1 227.3,56.9 226.9,56.5 227.2,56.1 227.6,56.0 228.1,56.0 228.6,56.0 229.4,56.3ZM181.9,48.4 182.4,48.4 182.9,48.3 183.5,48.2 184.2,48.1 184.7,48.1 184.7,47.7 185.3,47.4 185.9,47.2 186.8,47.3 187.6,47.4 188.8,47.5 190.4,47.9 190.9,48.0 190.7,48.4 190.0,48.8 189.4,48.9 189.8,49.2 190.2,49.0 190.8,48.7 191.2,49.0 191.8,48.8 192.3,48.5 193.0,48.3 193.9,48.2 193.7,47.8 192.9,47.6 192.5,47.4 193.0,47.2 194.2,47.2 195.5,47.6 195.9,47.8 196.9,48.0 197.3,48.1 197.8,48.4 198.3,48.9 198.7,49.6 199.2,50.3 199.4,50.7 199.9,50.9 200.5,51.0 200.9,50.8 201.8,50.5 201.3,49.9 200.8,49.6 200.6,49.2 200.5,48.8 200.2,48.4 199.3,46.9 199.7,46.7 200.1,46.6 200.8,46.3 201.4,46.4 202.5,46.7 202.9,46.5 203.3,46.5 204.2,46.7 205.3,47.0 206.1,47.2 206.6,47.4 207.0,47.6 207.2,48.0 207.5,48.4 207.7,48.8 208.7,50.1 209.7,50.8 210.0,51.2 210.1,51.6 209.8,52.1 209.5,52.5 210.7,53.0 211.2,53.4 212.3,53.8 213.1,54.0 213.6,54.1 213.8,53.7 214.6,54.1 215.0,54.3 215.6,54.4 216.7,54.8 217.4,54.8 217.9,55.2 218.8,55.1 219.2,55.2 219.7,55.9 219.6,56.3 218.8,56.4 218.3,56.3 217.9,56.2 217.4,56.4 216.4,56.0 216.0,56.0 215.2,56.2 215.2,56.6 214.6,56.8 214.1,56.8 213.7,56.7 213.0,56.5 212.6,56.5 213.1,56.8 213.5,56.9 213.8,57.3 213.6,57.7 214.2,57.4 215.2,57.1 216.2,57.0 216.7,57.2 216.5,57.6 216.9,57.6 217.3,57.8 217.1,58.3 216.7,58.4 215.7,58.5 215.3,58.6 214.6,58.7 214.2,58.8 213.4,58.8 212.6,58.9 211.6,58.8 210.9,58.7 210.1,58.5 209.5,58.7 208.0,58.6 208.3,58.2 207.6,58.1 206.9,58.0 206.1,57.9 205.5,57.9 204.8,57.8 204.6,57.4 204.1,57.1 203.7,57.1 203.2,57.4 202.7,57.8 201.8,58.2 200.4,58.5 199.0,58.5 198.5,58.6 198.0,58.8 197.4,59.0 195.9,59.2 194.6,59.4 193.1,59.4 192.1,59.5 191.3,59.5 190.8,59.6 190.2,59.6 188.0,59.7 187.0,59.8 186.5,59.8 186.1,59.8 185.5,59.6 184.6,59.0 184.5,58.4 184.2,57.8 183.1,57.6 182.4,57.6 181.4,57.6 180.1,57.6 178.8,57.5 178.2,57.5 177.5,57.4 176.4,57.2 175.8,56.7 175.0,56.3 174.6,55.9 174.5,55.4 176.2,55.1 179.1,54.8 181.7,54.7 182.9,54.7 183.6,54.8 184.3,54.8 185.5,54.8 187.1,54.9 188.2,54.8 189.5,54.8 189.9,54.7 188.6,54.3 185.7,53.8 185.0,53.7 184.0,53.6 183.4,53.6 182.7,53.7 181.7,53.8 181.0,53.8 179.7,53.9 177.8,53.9 177.2,53.8 175.0,53.9 173.4,53.8 171.5,53.1 171.5,52.7 172.4,52.4 174.1,52.2 175.5,52.0 176.6,51.8 177.1,51.8 177.7,51.8 178.1,51.7 177.7,51.6 178.5,51.4 179.2,51.5 179.6,51.5 178.9,51.3 175.6,51.5 174.1,51.6 173.0,51.7 172.4,51.7 171.7,51.6 172.6,51.2 171.2,51.0 170.6,51.0 169.8,50.9 169.5,50.5 169.6,50.1 170.6,49.5 171.2,49.4 171.6,49.3 171.1,49.0 171.2,48.5 171.9,48.2 173.5,47.7 174.3,47.5 175.1,47.3 176.2,47.1 179.0,46.6 181.6,46.2 182.5,46.3 182.9,46.5 183.2,47.0 183.1,47.5 182.6,47.9 182.0,48.3ZM167.4,44.1 168.1,43.9 168.6,43.9 169.0,43.9 169.1,44.4 170.2,43.9 170.7,43.8 171.7,43.7 172.3,43.7 173.0,43.7 173.6,43.8 174.5,44.0 175.1,44.2 175.8,44.4 177.9,45.1 178.8,45.4 179.3,45.6 179.3,46.0 177.8,46.3 177.1,46.4 176.4,46.5 174.8,46.9 173.7,47.1 172.3,47.5 169.6,48.1 169.2,48.2 168.3,49.0 167.3,49.3 166.4,49.4 165.8,50.0 165.4,51.0 164.9,51.4 164.1,51.5 163.4,51.6 162.6,51.7 162.2,51.5 161.8,51.5 160.7,52.0 159.6,52.2 159.1,52.4 158.5,52.5 158.1,52.5 157.5,52.3 156.7,51.6 156.2,51.3 155.7,51.0 153.4,50.5 152.4,50.2 152.0,50.1 150.7,50.1 150.6,49.6 151.2,49.5 151.4,49.1 151.9,48.7 152.3,48.5 152.8,48.4 152.7,48.0 153.0,47.6 153.9,47.4 153.5,47.2 153.8,46.7 154.4,46.1 155.2,45.8 155.5,45.4 156.1,45.1 155.3,44.8 154.8,44.6 154.0,43.8 153.6,43.5 157.0,43.2 159.4,43.2 161.8,42.9 162.5,42.9 163.0,43.0 163.5,43.1 164.2,43.3 165.1,43.5 166.8,43.7 167.9,43.8 167.5,44.0ZM237.4,39.6 236.8,39.5 236.4,39.1 236.8,39.0 237.4,38.9 238.0,39.4 237.4,39.6ZM279.5,105.7 279.1,105.7 279.6,105.3 279.5,105.7ZM281.0,94.0 281.0,93.6 281.3,93.2 281.5,93.6 281.2,94.0ZM280.7,93.7 280.3,94.3 280.1,94.8 280.2,94.1 279.4,94.6 278.9,94.8 278.4,95.0 279.2,94.2 278.4,94.6 278.0,94.8 278.4,94.1 278.9,93.8 279.2,93.3 279.3,92.9 279.2,93.6 279.6,93.4 279.8,92.8 280.2,93.0 280.6,93.2 280.8,93.6ZM278.6,90.2 278.2,90.3 278.6,90.1ZM277.8,93.9 278.1,93.5 278.5,93.4 278.9,93.2 277.8,93.9ZM278.0,101.9 277.6,101.9 278.0,101.9ZM277.6,84.0 278.1,83.7 277.6,84.0ZM279.1,92.6 279.0,92.2 279.2,92.6ZM231.2,47.4 230.7,47.4 230.3,47.2 230.5,46.8 230.9,46.7 231.3,46.9 231.7,47.0 231.4,47.4ZM229.6,43.0 228.7,43.1 229.1,42.8 229.7,42.8ZM227.0,44.8 226.2,44.9 225.7,44.9 225.1,45.0 224.2,44.8 223.8,44.7 224.7,44.6 225.1,44.5 225.5,44.4 226.2,44.3 227.6,44.2 228.3,44.1 228.7,44.2 228.2,44.5 227.4,44.8 227.0,44.8ZM249.4,57.2 249.3,57.6 248.7,57.4 249.1,57.1ZM248.6,57.7 248.1,57.6 248.5,57.4ZM294.4,76.1 293.7,76.1 293.1,75.9 292.7,75.8 293.1,75.8 294.1,75.9 294.5,76.0ZM292.0,60.1 291.5,60.0 290.8,59.8 290.6,59.3 291.1,59.2 291.5,59.2 292.0,59.6 292.2,60.0ZM281.9,81.3 281.5,81.3 282.2,81.1 282.7,81.1 282.3,81.2 281.9,81.3ZM280.6,60.6 280.1,60.5 280.7,60.1 280.6,60.6ZM287.0,73.9 286.3,73.9 285.1,73.4 285.7,73.1 286.8,73.4 287.0,73.9ZM319.9,76.2 320.5,76.3 320.9,76.3 320.7,76.7 319.9,76.6 319.9,76.2ZM325.9,63.7 325.5,63.8 325.9,63.5 326.5,63.4 325.9,63.7ZM310.5,82.7 310.1,82.6 310.5,82.1 310.9,81.7 311.4,81.8 311.5,82.2 311.1,82.5 310.5,82.7ZM304.6,76.3 304.1,76.2 303.7,76.2 303.2,76.0 302.8,75.6 302.4,75.5 303.2,75.4 303.7,75.5 304.3,75.7 304.7,76.1ZM319.9,79.5 319.3,79.4 318.4,79.0 319.1,78.6 319.6,78.7 320.0,78.7 320.4,78.9 320.2,79.3ZM311.3,56.8 310.6,56.7 311.1,56.4 311.6,56.5ZM279.4,56.1 279.0,56.6 278.1,56.6 277.6,56.6 277.1,56.8 276.7,56.6 275.6,56.5 276.0,56.3 276.5,56.3 277.0,56.1 277.4,56.3 277.9,56.3 278.6,56.1 279.4,56.1ZM283.3,56.3 282.5,56.8 282.0,56.9 281.5,56.9 281.1,57.0 281.7,56.6 282.2,56.5 282.6,56.4 283.3,56.3ZM284.3,72.2 283.5,72.3 284.1,72.1 284.5,72.1ZM269.1,65.9 269.6,65.9 269.0,66.0ZM280.0,58.8 279.6,58.7 279.9,58.2 280.8,58.0 281.2,57.6 281.7,57.3 282.1,57.3 282.5,57.3 282.1,57.8 281.7,58.1 281.2,58.5 280.4,58.7 280.0,58.8ZM286.1,57.9 285.5,57.9 285.1,57.6 285.2,57.2 285.6,57.1 286.1,57.2 286.8,57.2 286.6,57.6 286.1,57.9ZM319.4,78.1 319.8,78.3 319.0,78.3 319.0,77.9 319.4,78.1ZM258.6,55.2 259.2,55.2 259.6,55.4 259.1,55.6 258.7,55.6 258.2,55.6 257.8,55.5 257.4,55.3 257.9,55.2 258.6,55.2ZM267.4,67.2 267.8,67.3 268.7,67.4 268.1,67.6 267.7,67.5 267.3,67.6 266.8,67.3 266.3,67.3 266.3,66.9 265.9,66.7 265.4,66.5 266.3,66.4 266.8,66.6 267.3,66.8 267.4,67.2ZM259.5,61.8 258.7,61.6 258.8,61.1 258.4,60.8 258.8,60.5 259.2,60.3 259.9,60.5 259.9,60.9 260.1,61.3 259.8,61.7ZM264.8,67.8 264.4,67.8 263.7,67.4 263.5,67.0 263.5,66.6 264.1,66.6 264.6,67.1 265.0,67.6ZM241.5,78.2 241.1,78.0 241.5,78.0ZM216.0,38.8 216.6,39.0 215.8,39.2 215.2,39.4 214.0,39.5 213.0,39.5 213.8,39.1 213.3,39.0 211.8,39.2 211.2,39.1 211.7,38.8 211.2,38.8 210.4,38.8 210.0,38.6 211.1,38.3 212.3,38.2 213.6,38.0 214.6,38.0 215.0,38.1 215.3,38.6 216.0,38.8ZM218.8,37.3 218.1,37.3 217.7,37.2 219.0,37.0 219.6,36.9 220.5,36.8 220.9,36.8 221.5,36.9 220.2,37.1 218.8,37.3ZM211.1,37.3 211.6,37.2 212.2,37.3 213.4,37.6 213.8,37.7 213.3,37.9 212.6,38.0 210.4,38.0 210.0,37.9 209.5,37.4 210.5,37.0 210.9,37.0ZM213.9,32.9 213.2,32.9 213.6,32.7 214.0,32.6 214.4,32.6 213.9,32.9ZM217.5,34.2 217.1,34.2 216.4,34.2 215.6,34.1 215.4,33.7 215.9,33.6 216.9,33.6 217.7,33.6 218.6,33.7 219.1,33.9 218.3,34.1 217.8,34.1ZM250.8,37.5 250.2,37.5 249.5,37.4 248.8,37.0 248.4,36.8 248.9,36.6 249.6,36.6 250.1,36.6 250.6,36.7 250.6,37.1 250.8,37.5ZM233.1,40.3 232.7,40.3 232.1,40.3 231.6,40.5 231.0,40.6 230.6,40.5 231.0,40.2 231.9,40.0 232.3,39.9 232.9,40.0 233.4,40.1ZM235.3,43.0 234.9,43.0 234.0,42.9 234.7,42.7 235.1,42.8ZM183.8,34.0 183.0,34.1 182.5,34.1 181.6,34.0 180.6,33.6 181.1,33.4 181.7,33.2 182.4,33.1 182.8,33.3 183.6,33.6 184.0,33.6 184.4,33.8 183.8,34.0ZM163.7,39.6 163.2,39.6 163.8,39.3 164.2,39.1 164.0,39.5ZM184.6,36.8 184.1,36.9 181.2,36.8 182.2,36.5 183.6,36.4 184.1,36.5 184.5,36.5ZM210.8,41.6 210.3,41.6 209.4,41.5 208.8,41.3 209.2,40.8 209.8,40.5 210.2,40.5 210.9,40.5 211.3,40.6 211.7,40.7 212.0,41.1 211.6,41.4 210.8,41.6ZM345.9,106.8 345.6,107.3 345.2,107.3 344.6,107.4 344.4,107.8 344.8,107.8 345.0,108.2 344.8,108.6 344.2,108.9 344.1,109.3 343.4,110.0 343.1,110.4 342.5,110.9 342.4,111.4 342.1,111.8 342.2,112.2 342.7,111.7 343.1,111.5 343.6,111.1 344.0,110.8 344.0,111.2 344.6,111.1 345.1,111.2 345.8,111.1 345.2,111.6 344.3,111.9 344.7,112.0 344.2,112.6 345.0,112.5 345.9,112.6 346.3,112.9 346.4,112.5 346.6,113.0 346.3,113.4 346.7,113.2 347.2,113.0 347.7,112.9 348.2,112.7 348.6,112.4 348.7,112.8 349.1,112.7 350.1,112.7 350.7,112.8 351.1,113.0 351.2,113.4 350.7,113.8 350.3,114.2 349.6,114.5 350.1,114.4 350.3,114.8 350.8,114.8 350.6,115.2 349.8,115.5 350.2,115.5 350.6,115.4 351.0,115.2 351.6,115.1 352.0,115.1 352.4,114.9 352.6,115.3 352.2,115.7 351.7,115.8 351.3,116.0 351.3,116.4 350.8,116.5 350.4,116.6 351.0,116.6 350.4,117.2 350.5,117.6 350.9,117.6 351.4,117.4 352.0,116.7 352.5,116.5 353.0,116.3 352.8,116.7 352.5,117.2 352.3,117.6 352.3,118.0 352.9,117.9 353.3,117.4 353.7,117.7 353.7,118.1 353.0,119.2 352.9,119.9 352.6,120.3 352.2,120.4 351.7,120.2 351.3,120.5 351.1,119.8 351.1,119.4 351.1,119.0 350.6,119.4 350.0,119.9 349.6,119.9 349.6,119.5 350.1,118.7 350.4,118.2 350.2,117.7 350.0,117.3 349.5,117.1 348.9,117.9 348.7,118.3 348.7,117.9 348.2,118.3 347.8,118.3 347.0,119.0 346.5,119.6 346.1,119.7 345.7,119.7 345.0,119.8 344.6,119.6 344.9,119.2 345.5,119.1 345.9,119.0 346.7,118.2 347.3,118.0 347.8,117.7 347.4,117.7 347.0,117.6 346.2,117.6 346.0,118.1 345.6,118.2 345.1,118.1 344.2,118.1 344.8,117.8 344.9,117.3 344.4,117.3 344.0,117.3 343.5,117.6 342.4,117.8 341.8,117.8 340.4,117.7 339.8,117.7 339.2,117.6 338.2,117.6 337.7,117.5 337.2,117.7 336.3,117.8 335.8,117.9 335.4,117.7 335.1,117.0 336.2,116.2 336.9,115.8 337.5,115.4 338.0,115.2 337.5,115.2 336.9,115.2 336.3,115.2 335.6,115.2 336.6,114.6 336.5,115.0 336.9,115.0 337.1,114.6 337.4,114.2 337.8,113.7 338.4,113.7 338.8,113.9 338.8,113.4 338.4,113.2 338.3,112.8 338.8,112.4 339.3,112.6 339.2,112.2 339.7,111.0 340.0,110.6 340.4,109.8 340.8,109.4 341.2,109.4 340.8,109.2 341.3,108.9 341.7,108.5 342.2,108.0 342.4,107.6 343.0,107.2 343.9,107.0 344.4,106.8 344.8,106.9 345.3,107.0 345.8,106.7ZM348.5,112.3 348.0,112.4 347.6,112.3 348.0,112.3 348.4,112.1ZM349.7,111.8 350.1,112.0 349.3,112.2 349.6,111.8ZM343.7,119.9 343.4,119.3 343.6,119.7ZM349.4,118.2 349.4,117.8 349.4,118.2ZM221.6,58.9 221.2,59.1 220.8,58.9 220.6,58.5 221.1,58.3 221.7,58.5 221.6,58.9ZM222.2,58.3 221.8,58.4 221.7,58.0 222.1,58.1ZM221.4,54.2 220.7,54.1 220.3,53.8 220.7,53.7 221.5,53.9ZM234.7,56.7 235.1,56.9 234.9,57.3 234.5,57.4 234.1,57.4 234.2,56.9 233.8,56.8 233.7,57.3 233.4,56.9 233.9,56.6 234.4,56.7ZM219.0,57.2 218.6,57.1 218.6,56.7 219.2,56.8 219.1,57.2ZM217.1,59.5 215.9,59.3 216.5,59.0 217.1,58.9 217.4,59.3ZM209.6,60.0 209.2,59.9 208.7,59.8 208.2,59.6 208.6,59.5 209.2,59.5 209.8,59.7ZM200.3,62.8 199.8,62.8 199.6,62.4 199.9,62.0 200.1,62.4 200.3,62.8ZM196.8,61.2 197.3,61.2 196.8,61.2ZM199.7,63.9 200.1,63.9 200.5,63.9 200.2,64.3 199.8,64.0ZM196.3,61.1 195.8,61.0 196.3,61.0ZM330.6,94.3 330.2,94.4 330.7,94.2ZM328.5,90.1 328.8,90.5 328.3,90.5 327.8,90.3 328.2,90.1ZM307.9,86.0 307.5,86.2 307.4,85.8 307.8,85.7ZM561.6,82.3 562.1,82.3 562.0,82.7 561.6,82.7 561.5,82.3ZM559.6,81.9 559.2,82.0 559.0,81.6 559.6,81.9ZM321.1,82.3 320.7,82.4 320.2,82.3 319.9,81.9 320.4,81.9 320.8,82.1ZM850.5,153.5 850.1,153.2 850.5,153.2ZM701.4,270.5 701.0,270.2 701.4,270.2ZM937.9,274.3 937.4,274.2 937.8,274.3ZM557.3,83.2 556.9,83.2 557.3,83.2ZM560.1,83.0 559.7,83.0 560.1,82.9ZM569.0,69.5 568.6,69.6 568.5,69.2 569.4,69.3 569.0,69.5ZM583.2,56.1 582.7,56.2 582.9,55.8 583.3,55.9ZM513.8,80.3 513.3,80.3 513.7,80.0ZM522.5,74.1 521.9,74.0 522.4,73.7 522.5,74.1ZM523.5,73.1 523.0,73.1 523.5,73.0 524.2,72.8 523.5,73.1ZM534.7,66.9 535.1,66.6 535.5,66.7 534.7,66.9ZM534.5,66.5 534.8,66.1 534.9,66.5 534.5,66.5ZM554.9,55.0 555.3,55.0 555.8,55.2 555.3,55.4 554.9,55.3 554.5,54.9 554.9,55.0ZM565.1,53.3 564.7,53.6 564.1,53.9 563.7,54.0 562.9,54.0 562.3,54.1 561.6,54.0 561.1,53.7 561.6,53.7 562.1,53.7 562.7,53.6 563.5,53.5 564.5,53.3 565.0,53.2ZM557.7,55.3 557.3,55.4 556.8,55.3 557.4,54.9 557.8,55.0ZM571.1,52.4 571.8,52.5 572.4,52.7 571.6,52.9 571.1,52.9 570.3,52.7 571.1,52.4ZM565.6,54.0 565.4,54.4 564.8,54.6 564.3,54.8 563.9,54.6 564.0,54.2 564.6,54.2 565.4,53.8ZM566.7,54.0 566.2,54.1 565.7,53.9 566.1,53.5 566.5,53.6 566.9,53.7ZM538.5,60.4 539.1,60.4 538.6,60.6 537.9,60.8 537.5,61.0 537.1,61.1 536.7,61.1 536.9,60.7 537.3,60.7 538.0,60.3 538.5,60.4ZM536.0,61.5 535.6,61.6 536.0,61.1 536.5,61.0 536.0,61.5ZM548.6,56.7 549.0,56.8 549.4,56.8 549.8,56.9 550.2,57.2 549.8,57.4 549.9,57.8 549.4,57.9 548.8,57.9 548.1,58.0 547.7,58.3 547.1,58.1 546.7,58.1 547.1,57.9 547.1,57.5 547.5,57.2 548.2,57.3 547.9,56.9 548.5,56.9ZM553.5,55.4 554.0,55.5 554.5,55.5 554.0,55.8 553.3,56.1 552.8,56.2 552.2,56.6 551.1,56.8 550.4,56.8 550.7,56.4 551.1,56.2 551.6,56.1 552.3,55.7 552.7,55.4 553.1,54.9 553.5,55.1ZM543.8,59.6 544.2,59.3 544.6,59.2 544.9,58.8 545.4,58.7 545.8,58.9 545.9,59.4 545.4,59.5 545.0,59.6 544.6,59.8 544.2,60.0 543.8,60.0 542.9,60.2 542.4,60.1 541.9,60.3 541.5,60.3 540.6,60.6 539.9,60.6 540.1,60.2 540.5,60.0 541.9,59.9 542.8,59.4 543.0,58.9 542.9,58.4 543.5,58.0 544.1,57.6 544.6,57.5 544.4,58.0 543.9,58.3 544.2,58.8 543.9,59.4ZM542.2,58.5 542.6,58.8 542.6,59.2 541.7,59.4 541.1,59.3 540.6,59.3 540.0,59.3 540.4,58.8 540.8,58.8 541.2,58.8 541.8,58.6 542.2,58.4ZM531.2,69.8 530.7,69.8 530.1,69.9 530.6,69.5 531.2,69.7ZM514.1,82.5 513.7,82.6 513.7,82.2 514.1,82.5ZM533.2,67.7 532.7,67.8 533.3,67.5ZM280.4,39.1 279.6,39.4 279.0,39.4 278.6,39.2 279.0,39.0 279.5,38.9 280.1,38.6 280.5,38.5 281.0,38.6 280.4,38.9ZM300.9,35.2 299.9,35.2 299.0,35.1 298.6,35.0 299.3,34.8 299.8,34.8 300.7,34.9 301.2,35.0 301.6,35.0 300.9,35.2ZM375.4,22.0 374.8,22.0 373.6,21.7 371.8,21.4 370.1,21.3 368.6,20.7 370.0,20.4 371.1,20.3 373.9,20.6 375.2,20.9 375.7,21.1 375.6,21.5 375.4,22.0ZM448.2,22.6 447.1,22.7 446.2,22.5 445.6,22.2 446.3,21.9 447.0,22.1 447.7,22.4 448.2,22.6ZM451.1,28.3 449.9,28.6 448.2,28.6 447.1,28.4 447.2,27.9 448.5,27.7 450.0,27.6 451.5,27.7 451.1,28.3ZM447.2,33.4 446.6,33.2 446.4,32.8 446.3,32.4 446.9,32.2 447.4,32.2 447.4,32.7 447.2,33.4ZM448.4,38.8 447.0,37.7 447.0,37.3 447.5,36.9 448.0,37.1 448.2,37.8 448.4,38.8ZM450.0,40.5 450.3,41.1 450.7,41.3 451.4,41.2 451.7,41.6 451.2,41.7 449.0,41.6 448.1,41.7 447.5,41.5 447.5,41.1 448.2,40.6 448.7,40.8 449.4,40.6 450.0,40.5ZM450.1,34.3 449.6,34.3 450.3,33.7 450.9,33.7 450.8,34.2 450.1,34.3ZM416.8,17.9 419.5,18.0 420.9,18.2 423.1,18.3 424.9,18.4 427.9,18.6 428.3,18.7 427.3,18.8 423.4,18.9 416.4,19.0 412.4,19.2 411.1,19.2 411.6,19.5 412.5,19.5 415.6,19.2 416.8,19.1 419.0,19.2 421.8,19.3 422.9,19.2 425.0,19.3 427.4,19.2 430.2,19.0 431.0,19.4 432.0,19.8 432.9,19.7 433.6,19.8 434.2,19.9 435.0,19.9 437.4,20.0 439.1,20.2 439.7,20.3 440.2,20.6 438.9,20.9 437.6,21.2 435.8,21.3 433.7,21.4 417.8,21.8 417.3,21.9 417.2,22.3 417.9,22.4 419.7,22.2 422.7,22.1 424.9,22.1 430.1,22.2 431.7,22.5 432.5,23.1 434.3,22.9 434.7,22.9 435.2,22.5 435.6,22.3 436.3,22.1 437.3,22.1 440.1,22.0 440.7,22.0 441.2,22.3 441.3,22.8 441.0,23.3 440.3,23.8 439.7,24.0 439.0,24.2 437.7,24.6 437.3,24.7 435.9,25.2 435.5,25.6 436.2,25.5 436.6,25.2 438.6,24.9 439.1,24.9 440.4,24.5 441.3,24.4 442.0,24.2 444.4,23.4 445.5,23.2 446.6,23.2 446.8,23.6 448.1,23.6 448.7,23.6 449.7,23.7 450.1,23.8 450.8,23.8 451.5,23.9 452.1,23.8 453.0,23.5 453.8,23.3 454.6,23.0 455.2,22.8 455.6,22.8 456.8,22.7 457.7,22.7 460.4,22.7 461.9,22.8 464.0,23.0 465.5,23.1 466.1,23.2 467.1,23.4 467.9,23.6 468.3,23.7 466.0,24.1 465.4,24.4 463.5,24.8 462.6,24.9 461.7,24.9 460.6,25.0 459.9,25.0 460.3,25.2 459.9,25.6 457.8,25.8 456.8,26.0 455.6,26.0 454.7,26.0 453.4,26.2 453.9,26.4 454.4,26.4 455.7,26.6 455.1,26.9 454.2,27.1 453.1,27.2 452.7,27.2 452.2,27.2 451.8,27.2 450.8,27.3 449.8,27.3 448.1,27.2 447.1,27.1 446.7,27.1 446.0,27.1 444.8,27.4 444.3,27.6 443.9,27.9 444.1,28.3 444.5,28.5 444.9,28.5 445.8,28.5 446.2,28.5 446.2,29.0 446.0,29.4 446.4,29.6 446.8,29.7 447.2,29.9 446.5,30.2 445.2,30.4 444.8,30.8 444.3,31.0 443.9,31.0 443.3,31.0 442.7,31.1 441.3,31.5 441.8,31.7 441.1,32.3 440.8,32.8 440.2,33.4 439.6,33.9 440.1,34.3 440.6,34.2 441.3,33.8 442.0,33.6 442.9,33.6 443.6,33.7 444.5,33.9 445.2,34.0 445.9,34.1 446.4,34.4 445.9,34.5 444.6,34.3 444.0,34.2 443.2,34.3 442.6,34.4 443.2,34.9 443.8,35.1 445.0,35.2 445.6,35.3 446.0,35.4 446.4,35.5 446.9,35.5 447.5,35.3 448.4,35.3 448.8,35.4 449.2,35.7 449.2,36.1 448.9,36.5 448.3,36.8 447.9,36.8 447.3,36.6 446.8,36.6 445.8,36.5 444.8,36.3 444.3,36.3 443.1,36.3 441.8,36.4 440.0,37.0 439.6,37.0 439.1,36.8 438.4,36.7 438.0,36.7 437.3,36.9 437.7,37.2 438.1,37.2 438.9,37.3 439.6,37.8 440.1,38.1 440.5,38.2 441.2,38.1 442.0,38.0 442.9,38.2 443.7,38.2 444.2,38.3 444.8,38.6 445.0,39.2 445.7,39.5 445.9,39.9 446.1,40.3 446.2,40.8 445.8,41.2 445.3,41.3 444.7,41.1 443.9,40.8 443.1,40.8 441.9,41.2 441.4,41.3 441.0,41.3 440.5,41.5 439.9,41.6 439.3,41.6 438.2,41.3 438.6,41.5 439.2,41.7 439.7,41.8 440.4,41.7 441.3,41.5 441.7,41.5 442.1,41.7 442.2,42.2 441.7,42.5 442.1,42.7 442.7,42.4 443.3,41.7 443.8,41.6 444.5,41.7 445.0,42.1 445.7,42.7 446.4,42.9 446.6,43.3 446.2,43.7 445.4,43.7 444.3,43.7 443.7,43.7 442.6,44.1 441.3,44.1 440.1,44.0 439.0,43.8 439.4,43.5 439.6,43.1 439.0,42.9 439.0,43.4 438.4,43.5 438.0,43.6 438.3,44.0 438.1,44.4 438.5,44.5 438.9,44.5 440.8,44.5 441.6,44.6 443.4,44.9 443.2,45.4 443.0,45.9 440.8,46.0 440.1,46.0 439.2,46.2 438.4,46.5 437.9,46.5 436.1,46.3 435.5,46.1 434.0,45.7 432.9,45.1 432.4,45.4 431.8,45.5 431.2,45.5 430.8,45.4 430.3,45.2 429.6,45.0 429.1,44.9 429.8,45.2 430.8,45.6 431.2,45.7 430.5,45.9 429.7,46.0 429.3,46.1 428.7,46.4 427.6,46.5 426.6,46.4 425.7,46.3 425.1,46.2 424.2,46.0 426.1,46.4 425.4,46.8 424.8,46.7 424.3,46.7 423.7,46.8 424.5,46.9 425.7,46.9 426.6,46.7 427.2,46.7 427.7,46.7 429.4,46.5 429.8,46.2 430.4,46.1 431.7,46.0 433.0,46.1 433.6,46.1 434.1,46.3 434.8,46.5 435.4,46.7 436.1,46.7 436.5,47.0 437.6,47.3 438.3,47.3 438.8,47.5 438.8,48.0 438.7,48.9 438.1,49.0 438.2,49.4 437.5,49.6 436.9,49.4 435.5,49.1 434.2,48.9 433.7,48.7 433.1,48.6 432.3,48.1 431.8,47.4 431.1,47.1 430.6,47.2 430.1,47.3 428.2,47.6 427.6,47.8 427.2,47.8 426.0,48.0 426.5,48.1 427.2,48.1 428.6,47.8 429.6,47.7 430.6,47.5 431.1,47.5 431.5,48.4 431.0,48.7 430.2,48.8 431.0,48.9 431.5,48.8 432.2,49.0 432.7,49.1 433.9,49.4 434.5,49.6 435.3,49.8 436.2,50.0 437.3,50.2 437.9,50.6 439.0,50.7 438.0,51.2 437.6,51.3 437.6,51.7 437.7,52.1 437.9,51.7 438.4,51.5 439.0,51.4 439.6,51.4 439.7,51.8 439.8,52.2 439.8,53.0 440.1,53.9 439.9,54.3 439.0,54.3 437.8,54.3 437.8,53.9 437.7,53.2 437.3,53.6 437.2,54.2 436.3,54.3 435.6,54.3 435.2,54.3 433.9,54.0 433.4,53.8 433.0,53.4 432.7,53.0 432.6,52.6 431.8,52.2 431.2,52.0 430.5,51.8 429.8,51.7 429.3,51.5 428.7,51.3 428.1,51.2 427.2,51.1 425.9,51.2 425.0,51.0 424.5,51.1 425.7,51.4 426.5,51.4 427.6,51.4 428.2,51.4 428.6,51.8 428.5,52.3 427.7,52.5 427.3,52.6 426.2,52.9 425.8,52.9 424.8,52.9 424.1,52.9 423.1,52.8 422.5,52.8 421.4,52.8 421.9,53.0 422.3,53.2 422.0,53.6 421.1,54.0 419.3,54.3 420.5,54.2 422.2,54.4 423.3,54.4 425.7,54.2 426.1,54.3 425.6,54.7 424.8,54.8 424.4,54.8 423.4,55.2 423.9,55.6 424.6,55.2 425.7,54.9 426.6,54.9 427.3,54.9 428.8,54.6 431.3,54.7 433.2,55.0 434.3,55.2 435.6,55.2 438.1,55.2 437.7,55.6 437.2,55.7 436.6,55.8 436.0,55.8 435.5,56.1 434.6,56.3 433.9,56.3 434.1,56.7 433.7,56.8 432.6,56.7 432.5,57.1 432.1,57.2 431.3,57.4 430.9,57.5 430.2,57.6 429.8,58.1 429.0,58.2 428.6,58.6 427.9,58.8 427.4,58.9 426.8,59.2 426.4,59.2 426.0,59.2 425.5,59.3 424.8,59.4 424.3,59.5 422.6,59.7 421.9,59.8 421.2,59.9 419.8,60.1 419.2,60.2 418.8,60.3 418.3,60.3 417.5,60.2 417.0,60.2 416.5,60.4 416.1,60.6 414.7,60.4 415.0,60.8 414.3,60.9 413.9,60.9 413.4,60.9 412.7,60.8 411.8,60.5 410.7,60.0 410.2,59.9 410.6,60.3 410.1,60.5 410.7,60.9 410.3,61.3 409.9,61.5 408.6,61.9 408.2,62.0 407.5,62.5 407.1,62.8 406.9,63.2 405.9,64.0 405.3,64.7 404.8,64.9 404.4,64.9 404.0,65.4 402.6,65.9 402.0,65.9 401.6,65.9 400.9,65.7 400.4,65.4 400.7,65.8 401.0,66.3 400.5,66.5 399.9,66.7 399.2,67.0 398.5,66.6 398.2,67.2 397.7,67.3 397.2,67.1 396.6,67.3 396.1,67.6 395.4,67.7 394.6,67.7 394.9,67.2 395.0,66.7 395.9,66.1 396.4,65.8 395.6,65.7 395.0,65.6 394.3,65.6 395.1,65.9 394.5,66.3 394.2,66.7 393.3,66.7 393.8,67.1 393.9,67.5 392.7,67.7 391.4,67.7 390.5,67.8 389.0,67.9 388.4,67.9 389.8,68.4 389.1,69.0 388.2,69.3 387.0,69.1 386.4,69.2 385.9,69.2 386.2,69.8 386.6,69.8 387.1,69.7 387.7,70.4 388.1,70.6 388.1,71.0 387.6,71.3 386.9,71.3 386.2,71.6 385.6,71.4 384.5,71.4 385.6,71.7 386.0,71.9 386.6,71.8 387.2,71.9 387.1,72.4 387.3,72.9 386.7,73.3 386.0,73.6 385.7,74.0 385.6,74.4 385.0,74.8 384.4,74.8 383.8,74.8 383.3,74.6 382.8,74.4 383.5,74.9 384.3,75.1 383.6,75.7 383.2,75.8 382.5,75.8 381.3,75.8 380.7,75.8 381.5,76.0 382.0,76.1 382.9,76.2 382.8,76.7 382.4,77.4 382.9,77.7 383.0,78.2 382.6,78.4 381.9,78.5 382.4,78.7 382.2,79.1 382.0,79.5 381.5,80.4 381.3,81.2 380.4,81.9 380.0,81.9 379.6,81.9 378.9,81.7 378.4,81.7 378.0,81.7 378.7,81.9 379.1,82.0 379.7,82.1 380.1,82.4 380.2,83.2 379.7,83.5 378.8,83.5 377.9,83.3 378.5,83.6 378.0,83.8 377.5,83.8 377.0,83.6 376.6,83.5 377.1,82.8 376.5,83.1 376.1,83.3 375.5,83.2 373.9,82.8 374.0,82.3 374.4,82.3 374.8,82.2 375.4,81.9 375.7,81.5 374.8,81.9 374.2,82.1 373.8,82.0 373.4,81.9 372.4,81.7 371.8,81.2 372.2,80.6 372.6,80.3 372.6,79.9 372.2,80.3 371.4,80.5 370.6,80.7 370.2,80.8 369.8,81.1 369.1,81.1 368.4,81.1 367.8,81.0 367.2,81.0 366.6,81.3 366.2,81.2 366.9,80.7 367.3,80.6 366.3,80.6 365.6,80.5 365.5,80.1 365.1,79.9 364.1,79.8 363.9,79.4 363.3,79.0 363.4,78.6 363.0,78.5 362.8,78.1 363.5,77.8 364.4,77.6 363.9,77.5 363.3,77.5 362.9,77.7 362.2,77.8 362.0,77.4 362.4,77.1 362.0,77.0 361.3,76.9 360.9,76.8 360.3,76.5 360.4,75.9 360.5,75.5 360.9,75.3 361.7,74.9 360.9,75.1 360.2,75.5 359.7,75.2 359.0,74.9 358.6,74.5 357.8,73.8 357.0,73.2 357.1,72.5 357.6,72.1 358.6,71.9 359.2,71.8 360.2,71.7 359.8,71.6 359.1,71.6 358.1,71.8 357.4,71.9 357.0,71.9 356.4,71.8 356.9,71.3 357.2,70.9 357.7,70.7 358.6,70.6 359.2,70.3 359.7,70.3 360.1,70.3 360.7,70.9 361.1,71.0 360.9,70.6 360.5,70.1 359.7,70.1 359.3,69.9 358.9,69.3 358.5,68.9 358.7,69.4 359.0,69.8 358.6,70.3 357.7,70.5 357.9,70.0 357.3,70.3 357.0,70.7 356.5,71.2 356.0,71.6 355.6,71.5 355.3,71.1 355.3,70.6 355.2,70.0 354.9,69.3 354.3,68.9 354.1,68.5 355.1,68.2 355.6,68.0 356.3,67.6 357.6,67.4 358.1,67.4 357.2,67.3 356.3,67.4 355.8,67.7 354.6,68.2 354.0,68.2 353.4,67.8 352.8,67.9 352.4,67.8 352.1,67.3 352.5,66.7 352.1,66.7 351.7,66.5 352.7,66.2 354.1,65.7 354.7,65.5 355.1,65.4 355.7,65.0 356.1,64.9 356.5,64.8 356.9,64.6 357.6,64.3 357.2,64.3 356.5,64.6 356.0,64.7 354.4,65.4 353.7,65.7 353.3,65.8 352.7,66.1 352.3,66.2 351.6,66.2 351.1,66.2 351.0,65.7 351.2,65.2 351.6,64.9 352.2,64.7 352.7,64.4 353.9,64.3 354.4,64.3 354.0,64.1 353.0,64.1 352.1,64.1 351.7,64.1 351.2,64.0 350.3,63.5 350.5,63.0 351.3,62.5 352.2,62.3 352.9,62.0 353.7,61.8 354.1,61.8 354.5,61.8 355.8,62.0 356.5,62.1 357.1,62.0 357.8,62.1 359.2,62.5 357.9,62.0 358.6,61.7 357.4,61.7 356.2,61.8 355.7,61.8 355.3,61.7 354.6,61.6 354.0,61.6 353.1,61.7 351.6,62.3 351.1,62.4 350.7,62.4 351.0,62.0 351.2,61.6 351.8,61.2 352.2,60.8 352.7,60.5 353.1,60.5 354.3,60.7 355.4,60.9 356.2,61.0 356.7,61.0 357.1,60.8 357.4,60.4 357.8,60.2 357.1,60.0 356.6,60.4 356.1,60.4 355.0,60.5 354.5,60.5 353.6,60.4 352.3,60.3 351.7,60.3 352.2,60.0 352.7,59.4 353.1,59.3 353.9,59.1 354.7,59.2 356.2,59.6 356.6,59.6 357.0,59.6 358.0,59.4 358.5,59.2 358.9,58.9 358.3,59.0 357.9,59.1 357.9,58.5 358.0,58.1 358.9,58.0 360.0,58.0 359.6,57.6 359.2,57.7 358.7,57.8 358.1,57.8 358.6,57.2 358.9,56.7 359.1,56.3 359.8,56.2 360.2,56.0 359.7,55.7 360.2,55.6 359.4,55.5 358.9,55.5 358.4,55.4 358.0,55.4 357.2,55.6 356.7,55.5 354.8,55.4 354.0,55.1 353.4,54.9 352.7,54.7 351.8,54.6 350.6,54.5 350.0,54.4 349.6,54.3 349.0,54.0 348.6,53.7 349.0,53.4 349.5,53.3 350.4,53.3 350.9,53.3 351.4,53.4 352.5,53.4 353.3,53.5 353.8,53.5 354.4,53.6 356.2,54.2 356.9,54.3 358.5,54.5 359.2,54.5 358.5,54.3 357.9,54.1 357.4,53.9 357.4,53.5 357.2,53.0 356.2,52.8 356.9,52.7 357.6,52.8 358.0,52.9 357.3,52.4 356.1,52.4 355.4,52.4 354.9,52.4 354.4,52.3 353.4,52.3 352.8,52.3 352.5,51.9 353.0,51.6 353.5,51.4 355.6,51.1 356.2,50.9 355.8,50.9 355.3,51.0 353.7,50.9 353.0,51.1 352.3,51.3 351.6,51.2 352.1,50.8 351.8,50.4 351.6,50.0 351.2,49.7 350.5,49.2 350.9,49.0 350.3,49.1 350.5,49.5 351.0,49.9 351.4,50.1 351.2,50.5 350.8,50.7 350.3,51.0 349.9,51.0 350.1,51.5 349.7,51.6 349.1,51.7 348.1,51.8 347.7,51.7 347.1,51.6 346.3,51.6 345.6,51.2 345.5,50.7 346.0,50.1 346.3,49.7 347.3,49.3 347.7,49.0 346.3,49.4 345.6,49.5 345.6,49.1 346.2,49.1 345.6,48.8 346.0,48.6 346.9,48.6 347.4,48.4 347.8,48.2 347.9,47.8 347.6,47.3 347.0,47.2 346.4,47.4 345.9,47.3 345.5,47.2 345.6,46.8 346.0,46.8 346.4,46.5 346.3,46.1 345.4,46.1 345.0,45.9 344.5,45.7 344.1,45.6 344.5,45.1 344.9,45.1 344.6,44.7 344.1,44.3 343.6,44.0 343.1,43.9 342.6,44.0 341.8,44.1 341.1,44.1 341.8,43.9 342.5,43.8 342.6,43.4 343.2,43.1 343.7,43.0 343.0,42.7 342.2,42.6 341.7,42.3 341.1,42.0 340.7,41.8 339.4,41.6 339.0,41.4 338.6,41.1 338.2,40.9 337.3,40.7 338.1,40.4 337.8,40.0 337.0,39.7 336.4,39.6 335.9,39.5 335.4,39.4 334.9,39.3 334.1,39.2 332.9,38.9 330.9,38.6 330.0,38.5 329.5,38.4 328.8,38.4 327.5,38.2 326.4,38.2 325.7,38.2 325.0,38.0 324.2,37.9 323.8,37.9 323.3,38.1 322.7,38.3 322.3,38.3 321.8,38.2 321.4,38.0 320.7,38.2 320.3,38.3 319.7,38.4 319.2,38.5 318.6,38.5 318.2,38.5 317.5,38.4 317.0,38.2 316.3,38.3 315.7,38.5 315.1,38.5 314.2,38.3 313.7,38.3 314.3,38.8 314.8,39.0 314.4,39.0 310.7,38.7 310.2,38.6 309.6,38.5 309.0,38.4 308.0,38.1 307.3,38.0 308.7,37.3 309.3,37.3 310.4,37.2 310.8,37.1 309.0,37.0 307.6,37.0 306.5,36.8 306.1,36.7 306.2,36.3 304.9,36.5 304.3,36.6 303.9,36.6 303.5,36.5 302.7,36.2 302.3,35.9 302.9,35.7 303.9,35.6 306.5,35.5 308.4,35.6 309.0,35.3 309.5,35.2 310.7,35.1 312.7,35.0 314.1,35.1 314.7,35.2 315.6,35.3 315.8,34.8 315.4,34.4 314.7,34.2 314.0,34.2 313.5,34.3 312.5,34.6 312.0,34.7 311.2,34.7 310.7,34.6 310.3,34.6 309.6,34.5 309.1,34.5 308.7,34.6 308.1,34.8 307.4,34.8 305.6,34.6 305.2,34.5 304.7,34.2 304.1,34.2 304.8,33.9 305.3,33.8 304.4,33.8 303.9,33.9 303.5,33.9 302.8,33.9 302.0,33.9 301.4,33.7 301.0,33.6 299.8,33.5 299.3,33.4 298.4,33.1 297.8,32.9 298.4,32.6 298.0,32.3 298.7,32.0 299.9,31.8 301.0,31.6 301.7,31.5 303.0,31.6 303.5,31.5 304.4,31.3 305.6,31.2 308.4,31.0 308.8,30.6 310.1,30.5 310.9,30.4 311.5,30.4 311.9,30.3 312.6,30.2 315.0,30.2 316.0,30.2 316.5,30.2 317.2,30.1 317.9,29.8 318.3,29.6 319.1,28.9 319.5,28.5 319.7,28.1 320.5,27.7 320.9,27.6 321.7,27.5 321.3,27.4 320.7,27.4 320.2,27.5 319.5,27.5 318.8,27.5 318.3,27.6 317.9,27.6 317.2,27.7 316.7,27.7 315.9,27.6 315.4,27.6 314.3,27.6 313.7,27.4 313.3,27.2 313.8,26.7 315.0,26.3 315.6,26.2 316.3,26.0 316.8,26.0 317.2,25.9 317.7,25.9 318.5,25.6 319.3,25.5 320.3,25.1 320.8,25.0 322.5,24.8 323.0,24.8 323.4,24.9 323.8,25.0 324.8,25.3 324.3,24.8 325.0,24.4 325.9,24.4 326.9,24.5 327.6,24.5 328.2,24.6 328.8,24.7 329.3,24.6 329.7,24.5 330.1,24.2 330.2,23.5 329.9,23.1 330.5,22.8 331.0,22.6 332.1,22.4 333.1,22.4 333.6,22.4 334.3,22.5 335.3,22.5 336.2,22.7 337.7,23.1 338.7,23.3 339.5,23.4 340.3,23.5 341.4,23.8 342.1,23.9 342.7,24.0 342.1,23.7 341.2,23.5 339.3,23.2 338.3,22.9 337.3,22.6 336.6,22.4 335.4,22.3 336.9,22.0 339.7,21.8 342.8,21.6 343.9,21.6 345.7,21.5 346.3,21.4 348.0,21.2 348.5,21.2 349.2,21.3 350.0,21.4 350.4,21.6 350.9,21.8 351.1,22.9 351.6,23.1 352.0,22.9 352.4,22.8 352.9,22.3 352.5,21.9 352.5,21.5 353.4,21.3 356.2,22.0 357.4,22.2 358.6,22.5 360.1,22.5 361.5,22.5 362.1,22.5 360.0,21.9 359.1,21.6 358.5,21.2 361.0,20.9 364.3,21.1 368.5,21.7 370.5,22.0 374.2,22.7 375.3,22.8 375.8,22.8 376.3,22.6 376.0,22.2 376.3,21.5 376.9,21.4 376.9,20.9 376.2,20.7 373.5,20.2 374.0,20.1 374.8,20.0 381.5,20.2 382.7,20.2 383.2,20.3 383.7,20.3 385.1,20.3 377.1,19.8 375.7,19.8 374.9,19.8 374.2,19.8 372.6,19.8 371.8,19.8 370.9,19.6 371.8,19.3 372.5,19.3 373.8,19.4 374.7,19.2 376.0,19.1 377.2,19.0 380.0,18.7 380.5,18.7 381.2,18.7 382.6,18.8 383.2,18.9 383.8,19.0 384.2,19.1 384.7,19.1 385.3,19.2 386.2,18.9 387.0,18.7 387.9,18.5 389.2,18.6 390.0,18.7 390.8,18.9 391.9,19.0 393.7,19.4 394.2,19.4 394.6,19.0 392.9,18.7 392.4,18.5 392.9,18.3 393.9,18.3 394.6,18.2 395.2,18.1 395.9,18.1 396.9,18.1 397.8,18.2 398.2,18.0 401.5,17.9 402.3,17.9 402.9,17.9 403.7,17.9 404.4,17.9 405.2,18.0 406.0,18.0 407.2,17.8 408.4,17.8 414.7,17.8 416.8,17.9ZM0.0,295.8 1000.0,295.9 0.0,295.8ZM971.2,233.8 971.4,233.4 971.2,233.8ZM980.6,247.2 980.5,247.7 980.7,247.3ZM984.7,252.2 984.6,251.8 984.7,252.2ZM969.0,229.7 968.5,229.7 969.0,229.7ZM976.6,230.4 977.1,230.6 976.7,230.5ZM975.3,230.2 976.1,230.2 975.7,230.4 975.3,230.3ZM5.4,289.8 5.0,289.6 5.4,289.7ZM108.9,299.6 109.0,299.2 108.8,299.6ZM121.4,301.5 121.0,301.4 121.4,301.5ZM108.8,300.5 108.4,300.2 108.8,300.5ZM101.2,296.2 100.8,296.1 101.2,296.2ZM95.9,295.4 95.6,295.0 95.9,295.4ZM28.5,92.7 29.0,92.7 28.4,92.8ZM298.2,194.3 297.7,194.3 297.3,194.3 297.8,194.2 298.2,194.3ZM0.0,484.3 4.5,484.3 5.4,484.3 6.3,484.4 8.4,484.4 10.3,484.5 10.8,484.7 11.5,484.7 12.8,484.7 13.9,484.6 14.8,484.6 23.1,484.8 31.5,485.2 33.2,485.4 34.7,485.7 36.4,485.6 45.9,485.8 47.4,485.8 53.3,486.1 63.5,486.6 64.4,486.6 65.4,486.6 64.9,486.3 63.9,486.1 62.6,485.9 63.5,485.8 65.3,485.8 64.9,485.7 63.9,485.6 60.3,485.5 45.6,484.8 45.1,484.7 44.7,484.6 44.1,484.6 41.9,484.5 41.3,484.5 44.7,484.3 45.1,484.2 44.4,484.1 43.0,483.9 42.5,483.8 43.1,483.5 44.1,483.5 41.8,482.8 40.2,482.7 39.1,482.8 37.1,482.8 34.6,482.8 33.9,482.8 33.2,482.6 32.4,482.3 32.0,482.3 31.2,482.0 30.1,481.8 24.5,481.3 23.5,481.1 16.5,480.3 16.0,480.0 19.3,480.3 19.9,480.3 20.6,480.2 21.1,480.3 21.9,480.2 22.7,480.1 24.9,480.4 29.3,480.8 30.5,481.0 31.1,481.1 31.7,481.1 32.2,481.2 32.8,481.1 33.2,481.2 34.1,481.2 38.3,481.1 40.0,481.2 41.9,481.4 42.7,481.7 43.2,481.9 44.3,481.7 45.2,481.6 46.9,481.5 47.5,481.5 48.4,481.7 49.5,482.0 53.9,481.9 55.8,481.9 57.1,482.1 62.0,481.6 62.7,481.5 63.8,481.2 62.9,481.1 62.3,481.1 63.8,480.8 66.6,480.6 68.2,480.5 69.0,480.2 72.7,479.6 73.9,479.4 75.0,479.0 72.6,478.3 70.2,477.6 71.0,477.4 71.7,477.3 72.1,477.1 71.6,476.7 70.9,476.6 69.7,476.4 65.3,476.0 63.8,475.9 64.4,475.6 65.2,475.5 66.9,475.4 77.7,475.1 88.6,474.7 87.4,474.3 86.2,474.3 85.7,474.2 84.9,473.9 83.0,473.6 82.6,473.6 81.9,473.4 82.1,472.8 82.7,472.6 83.8,472.5 84.5,472.5 86.8,472.5 87.6,472.5 88.0,472.4 87.7,472.0 88.1,472.0 88.5,472.0 88.4,471.6 87.7,471.5 86.0,471.3 82.0,471.0 80.4,470.7 79.5,470.5 78.8,470.3 78.0,470.2 77.5,470.1 77.1,469.7 75.8,469.8 73.6,469.8 70.8,469.6 68.9,469.3 66.4,468.7 65.4,468.4 66.1,468.2 66.9,468.1 70.2,467.8 70.7,467.7 71.4,467.4 70.3,467.3 69.3,467.3 68.5,467.2 65.1,467.2 63.2,467.2 61.5,466.9 60.3,466.5 59.7,466.0 60.1,465.6 60.5,465.3 60.4,464.9 60.5,464.3 61.1,464.1 61.6,464.1 62.6,464.5 63.5,464.6 64.8,464.5 65.6,464.3 66.1,464.2 66.9,464.2 68.5,464.3 69.2,464.2 70.0,464.2 72.5,464.5 73.0,464.7 73.4,464.9 74.8,465.1 77.8,465.0 78.6,465.1 80.7,465.5 82.5,465.9 83.1,466.0 84.1,466.1 84.5,466.0 85.8,465.7 87.9,465.4 88.5,465.2 87.3,464.9 86.8,464.8 86.7,464.2 87.3,464.2 88.3,464.5 89.6,464.8 90.1,464.8 91.1,464.7 91.9,464.6 93.4,465.2 94.2,465.2 95.3,465.2 95.2,464.8 95.8,464.4 95.1,464.2 94.5,464.1 95.0,463.9 95.5,463.8 95.3,463.3 94.0,462.9 92.3,462.5 90.7,462.3 87.2,462.5 86.0,462.4 85.2,462.3 84.3,462.1 85.3,462.0 86.4,461.9 87.1,461.6 87.6,461.4 88.0,461.4 89.3,461.5 92.2,462.0 92.8,462.1 94.8,462.3 95.3,462.3 96.0,462.2 95.4,462.0 94.8,461.9 93.4,461.4 94.5,460.8 96.9,460.8 98.0,460.6 99.4,460.4 101.2,459.9 102.7,459.8 104.6,459.7 105.3,459.8 106.9,460.3 108.0,460.4 108.7,460.4 107.7,459.8 108.3,459.8 109.1,459.7 109.8,459.6 110.3,459.5 112.0,458.9 113.5,458.8 117.7,458.5 119.2,458.8 120.4,458.8 120.9,458.4 121.6,457.9 122.1,457.7 124.0,457.5 125.4,457.5 126.5,457.7 127.5,457.9 128.3,457.9 129.2,457.9 130.6,457.8 132.4,457.7 133.2,457.7 134.2,457.8 136.5,457.9 138.3,458.0 139.5,458.0 141.0,457.9 141.8,457.8 144.8,457.6 147.2,457.5 148.9,457.6 151.8,457.5 154.7,457.6 155.9,457.7 162.4,457.6 167.6,457.4 168.3,457.3 169.4,457.0 170.0,456.7 170.4,456.6 171.3,456.6 172.8,456.7 174.8,456.9 176.6,456.8 179.9,456.9 180.3,456.8 180.6,456.3 181.1,455.5 181.6,455.3 182.4,455.3 184.7,455.8 184.1,456.2 184.0,456.6 184.9,456.7 184.5,457.1 183.9,457.3 183.4,457.9 183.0,458.3 183.5,458.3 184.0,458.2 184.5,458.2 185.9,458.0 188.4,457.9 189.3,457.8 189.7,457.8 189.6,457.4 189.7,457.0 189.7,456.6 189.9,456.1 190.4,456.1 191.2,456.1 191.6,456.2 192.3,456.3 193.0,456.4 193.6,456.6 193.8,457.0 193.6,457.5 193.0,457.9 191.8,458.2 190.4,458.7 191.4,458.9 194.5,458.9 196.5,458.8 197.7,458.9 199.3,459.0 200.5,459.2 202.0,459.3 203.0,459.2 203.8,459.3 207.2,458.9 208.6,458.7 209.4,458.8 210.7,458.7 211.4,458.8 212.7,458.6 213.6,458.6 214.5,458.7 217.5,458.7 218.6,459.4 219.3,459.5 220.3,459.4 220.9,459.3 222.0,459.4 223.5,459.2 225.1,459.2 225.7,459.2 226.2,458.9 225.8,458.7 224.4,458.6 223.2,458.2 222.6,458.1 221.8,458.2 221.4,458.1 220.9,458.0 221.5,457.8 222.2,457.4 221.9,457.0 220.8,456.9 219.8,457.1 219.4,457.0 218.7,456.9 218.5,456.5 217.8,455.8 216.4,455.4 215.4,455.3 214.5,455.2 214.4,454.6 215.5,454.5 216.6,454.5 217.1,454.6 217.8,454.6 218.6,454.7 219.1,454.8 219.5,454.9 220.2,454.9 222.8,454.8 223.2,454.7 224.0,454.5 224.6,454.6 225.3,454.5 224.4,454.4 223.5,454.2 222.2,453.9 221.0,453.8 218.9,453.7 217.9,453.7 217.2,453.6 214.8,453.7 214.1,453.6 213.7,453.3 213.0,452.6 213.3,452.1 214.3,452.0 215.3,452.0 215.7,452.1 215.7,452.5 215.3,452.6 216.6,452.8 217.1,452.8 217.6,452.9 218.5,452.8 219.9,452.7 220.7,452.8 221.5,452.9 222.8,452.8 227.2,452.8 227.7,452.9 228.3,453.1 228.7,453.2 229.2,453.1 230.7,453.4 231.5,453.5 232.2,453.6 232.9,453.6 233.7,453.6 234.6,453.4 235.5,453.4 236.0,453.4 237.3,453.5 238.2,453.6 238.9,453.6 239.7,453.4 242.1,453.2 243.8,453.3 246.8,453.6 247.4,453.7 248.8,453.5 249.2,453.1 249.2,452.7 249.6,452.6 250.5,452.4 251.3,452.4 251.8,452.5 252.4,451.9 253.4,451.9 254.1,452.0 255.0,452.2 254.6,452.6 254.0,453.1 254.4,453.4 255.0,453.4 255.7,453.4 256.6,453.3 257.2,453.3 258.2,453.8 258.9,453.8 259.4,453.8 261.2,453.4 261.7,453.3 262.3,453.5 263.2,453.9 263.9,454.2 265.1,454.3 266.1,454.4 267.2,454.6 267.9,454.7 269.3,454.7 270.0,454.8 271.7,455.2 273.3,455.0 274.1,454.8 274.5,454.5 274.3,454.1 274.3,453.7 274.9,453.4 276.8,453.9 276.6,453.4 276.1,453.0 276.6,452.6 277.4,452.8 278.3,452.9 279.1,453.0 280.7,453.6 281.2,454.2 282.2,454.3 282.9,454.3 283.8,454.2 284.9,454.1 285.7,454.2 286.5,454.1 286.0,454.8 286.4,455.1 286.8,455.0 288.1,455.0 289.1,454.8 290.0,454.8 290.9,454.6 291.5,454.6 292.1,454.6 292.8,454.8 293.5,454.7 293.9,454.7 294.5,454.7 297.4,454.0 298.1,454.0 298.9,454.0 300.0,453.8 300.8,453.8 301.5,453.8 302.7,453.5 304.7,453.5 305.6,453.4 307.6,453.3 308.8,453.1 311.1,452.6 312.0,452.3 313.0,451.7 313.7,451.1 314.4,450.3 314.0,449.7 313.3,449.2 312.6,448.7 312.4,448.0 312.5,447.4 312.2,446.8 312.0,446.3 311.4,445.6 310.8,445.1 310.0,444.5 310.0,443.9 309.8,443.5 309.3,443.1 309.8,442.7 310.7,442.6 312.9,442.8 313.6,442.4 314.0,442.1 313.8,441.7 313.4,441.6 312.8,441.3 313.5,441.0 313.6,440.5 313.8,440.1 314.2,439.7 314.0,439.3 313.5,439.0 313.8,438.4 314.1,438.0 314.5,437.8 314.1,437.5 313.5,437.5 312.5,437.6 312.4,436.9 312.5,436.4 313.1,436.3 313.8,436.0 314.1,436.5 314.3,436.9 315.0,436.7 315.4,436.8 315.3,436.4 315.3,435.9 315.2,435.4 315.6,435.0 316.2,435.0 316.8,435.1 317.3,435.1 317.6,434.5 317.3,434.1 317.7,433.7 318.2,433.7 318.6,433.7 319.0,433.7 318.7,433.3 319.2,433.2 320.2,433.3 320.8,433.2 320.4,433.0 320.4,432.6 320.9,432.7 321.0,432.3 321.6,432.3 322.0,432.1 322.6,432.1 322.1,431.7 322.0,431.3 322.5,430.8 322.9,430.6 323.7,430.8 324.3,430.8 324.8,430.9 324.7,430.4 325.6,430.1 326.3,430.1 326.4,429.6 326.8,429.8 327.4,429.8 328.1,429.5 328.5,429.5 329.2,429.3 330.1,428.8 330.9,428.2 332.6,427.6 333.4,427.5 334.7,427.3 335.5,427.0 336.0,426.9 336.5,426.5 337.0,426.5 338.3,426.3 339.3,425.9 340.6,425.6 341.2,425.7 341.6,426.0 341.9,426.4 342.3,426.6 341.7,426.7 341.3,426.8 341.2,426.3 340.4,426.4 339.6,426.7 338.2,427.1 337.4,427.5 336.9,428.0 336.1,428.3 336.7,428.6 336.7,429.0 336.0,429.0 335.5,429.0 335.1,428.9 334.6,428.8 334.5,429.3 334.1,429.3 333.4,429.0 332.7,429.3 332.2,429.5 331.8,429.7 330.8,430.3 330.4,430.5 329.6,430.6 329.2,430.6 328.6,430.5 329.0,431.1 328.2,431.2 327.7,431.2 327.6,431.8 328.0,432.0 328.5,432.1 327.8,432.2 327.4,432.5 326.9,432.9 327.3,433.4 327.8,433.6 328.2,433.7 328.8,433.6 329.6,433.5 330.0,433.3 330.4,433.3 330.8,433.1 331.6,433.1 331.3,433.6 330.7,433.5 330.8,433.9 330.5,434.3 330.1,433.9 329.7,433.8 329.1,434.0 328.7,434.0 328.5,434.5 328.1,434.2 327.5,433.9 327.1,433.9 326.4,433.9 325.9,434.0 326.1,434.5 326.0,434.9 326.3,435.3 325.8,435.2 325.0,434.6 324.5,434.3 323.8,434.0 323.4,434.0 322.9,434.1 322.9,434.5 322.3,435.0 322.8,435.4 322.9,435.8 322.1,435.9 321.1,435.7 320.7,435.7 320.3,435.6 320.0,436.0 319.9,436.4 319.4,436.7 319.8,436.8 319.2,437.0 318.8,437.1 318.2,437.0 318.0,437.6 317.9,438.3 318.0,438.7 317.7,439.3 318.4,439.3 318.8,439.3 319.6,439.1 318.4,439.7 319.2,439.9 318.8,440.5 319.7,440.8 321.0,441.0 322.0,441.0 321.8,440.5 322.4,440.3 322.8,440.2 324.4,440.1 324.8,440.1 325.2,440.1 324.7,440.2 324.0,440.3 323.0,440.5 323.8,441.0 323.7,441.5 324.2,442.1 324.7,442.4 325.4,442.7 326.1,443.0 326.5,443.3 326.6,444.0 327.2,444.5 327.9,444.8 327.7,445.2 327.2,445.1 326.7,445.5 327.1,445.6 327.8,445.8 329.2,445.8 328.9,446.2 328.3,446.4 327.8,446.5 327.9,446.9 328.6,446.8 329.1,446.8 329.7,446.9 330.5,447.7 330.5,448.1 330.1,448.2 329.5,448.5 329.1,448.6 328.4,448.9 328.0,449.0 328.5,449.1 329.0,449.1 330.0,448.8 330.6,449.1 330.5,449.5 328.8,449.6 327.9,449.7 327.1,450.0 328.1,450.2 328.8,450.1 329.2,450.2 329.7,450.3 330.3,450.3 330.7,450.1 331.3,450.2 331.4,450.7 331.3,451.2 330.4,451.3 329.8,451.3 329.8,451.7 330.7,451.9 331.3,451.8 331.9,451.9 331.9,452.3 332.3,452.8 332.9,452.6 333.3,452.6 333.3,453.3 332.2,453.4 331.8,453.4 331.4,453.5 330.8,453.7 330.3,453.7 329.9,453.5 329.4,453.3 328.5,453.2 327.8,453.2 328.4,453.5 328.8,454.2 329.4,454.1 330.3,454.3 330.9,454.5 330.8,455.2 330.3,455.4 329.4,455.3 329.0,455.3 328.6,455.3 328.2,455.6 329.7,455.7 330.1,455.7 329.9,456.1 329.0,456.1 328.6,456.2 328.2,456.4 329.6,456.5 330.2,456.4 331.2,456.2 331.0,456.6 330.5,456.9 329.5,457.0 328.8,457.0 327.8,456.9 327.1,456.8 328.1,457.5 328.0,458.0 327.4,458.1 326.7,458.2 326.2,458.0 325.8,457.6 325.3,457.5 324.8,457.4 324.7,457.9 324.0,458.0 323.4,458.1 322.9,458.2 322.4,458.3 323.4,458.4 324.1,458.4 324.5,458.7 323.5,458.8 322.6,458.9 321.4,459.1 322.3,459.2 323.1,459.2 323.7,459.3 324.2,459.3 322.1,459.9 320.1,460.4 319.3,460.5 318.6,460.6 316.8,461.0 315.6,461.1 312.5,461.4 307.5,462.1 305.8,462.6 305.3,462.9 304.0,463.1 303.1,463.2 300.6,463.2 298.0,463.0 295.9,463.0 294.8,463.0 290.9,462.7 290.4,462.7 289.8,462.8 289.4,462.8 289.0,462.8 288.2,462.7 285.6,462.9 285.6,463.4 286.6,463.9 288.2,464.7 289.1,464.8 289.6,465.0 290.6,465.2 292.8,465.2 295.9,465.4 297.6,465.5 296.5,466.4 295.9,466.6 294.3,467.0 292.2,467.2 290.6,467.1 287.7,466.8 284.1,466.5 278.7,466.2 277.5,466.1 276.1,466.0 275.3,466.1 274.7,466.2 273.4,466.2 273.8,466.3 279.1,467.1 283.7,467.6 284.3,467.8 284.9,467.9 284.6,468.5 283.7,468.7 281.4,468.8 278.4,468.9 277.0,469.0 275.5,468.8 272.4,468.2 270.5,467.8 269.2,467.4 268.3,467.0 267.3,466.6 267.5,467.1 268.0,467.4 268.8,467.7 267.9,467.7 267.5,467.8 267.5,468.2 268.7,468.8 269.5,468.9 270.6,469.2 273.2,469.7 273.6,469.9 274.4,470.3 275.3,470.8 275.8,470.9 276.3,470.9 276.4,470.3 277.4,470.2 279.3,470.3 287.5,470.4 288.3,470.5 288.8,471.2 287.9,471.7 287.3,472.0 286.4,472.1 285.5,472.2 284.2,472.3 281.4,472.2 278.7,472.2 280.8,472.5 282.8,472.7 285.7,472.6 286.8,472.6 287.8,472.5 288.2,472.7 288.9,473.0 289.4,473.2 290.1,473.7 290.7,474.2 291.5,474.6 292.2,474.7 293.0,474.6 294.6,474.5 296.2,474.7 297.1,474.8 298.5,474.6 299.5,474.3 301.7,474.1 302.1,474.0 302.7,473.9 303.6,474.0 304.0,474.0 304.5,474.3 304.9,474.6 305.5,474.8 306.2,474.9 306.6,474.9 307.8,475.0 309.5,474.9 310.2,475.0 310.7,475.4 311.2,475.4 317.9,476.3 320.1,476.5 323.7,476.5 326.4,476.5 326.8,476.6 327.3,476.8 326.3,476.9 325.2,476.9 323.5,476.9 322.9,476.9 321.6,476.8 320.9,476.9 320.3,476.8 319.4,476.9 317.7,477.0 318.1,477.2 318.7,477.2 320.0,477.2 321.7,477.2 320.3,477.5 316.9,477.5 316.3,477.6 316.8,477.7 317.3,477.9 316.9,478.3 317.5,478.6 317.9,478.6 318.3,478.6 319.0,478.7 319.7,478.8 321.1,478.8 322.9,478.6 323.7,478.6 326.0,478.5 328.1,478.5 330.9,478.3 331.4,478.3 331.9,478.3 331.1,478.5 327.5,479.1 326.2,479.2 325.7,479.2 326.5,479.8 327.4,480.1 328.0,480.5 328.6,480.6 329.7,480.4 330.0,480.8 329.3,481.2 328.9,481.5 329.4,481.7 330.6,481.7 332.2,481.8 333.7,481.8 334.7,481.8 338.1,480.9 339.5,480.4 340.1,480.3 340.7,480.1 343.6,479.5 344.2,479.4 345.0,479.1 346.4,479.1 348.3,478.7 350.0,478.3 350.7,478.3 351.2,478.2 351.8,478.2 353.3,478.2 354.4,478.2 356.3,477.9 357.8,477.8 359.3,477.7 361.0,477.7 365.7,477.5 367.0,477.6 368.4,477.8 369.4,477.8 370.6,477.7 371.5,477.6 371.9,477.9 371.7,478.5 371.0,478.7 371.7,479.2 372.8,479.2 374.9,479.0 376.5,478.8 377.0,478.7 377.6,478.7 378.7,478.5 380.1,477.8 381.8,477.1 383.2,476.7 384.1,476.1 384.9,475.8 385.8,475.6 386.3,475.5 387.7,475.5 389.6,475.1 392.3,474.7 394.4,474.9 396.6,475.2 397.7,474.9 398.6,474.9 399.4,474.8 400.1,474.7 400.6,474.5 401.3,474.3 401.9,474.0 404.6,473.9 407.4,473.7 407.8,473.7 408.2,473.7 409.2,473.6 410.4,473.5 412.1,473.5 413.0,473.5 413.8,473.1 415.5,473.0 417.2,472.8 418.0,472.7 418.5,472.7 432.7,472.4 433.3,472.2 434.5,472.1 435.0,471.8 433.1,471.7 432.5,471.6 431.9,471.6 431.5,471.6 429.8,471.6 416.8,471.1 416.1,470.8 416.2,470.3 415.8,469.9 414.9,469.8 413.9,469.8 412.7,469.8 409.6,470.1 408.4,470.1 405.0,469.8 402.8,469.4 401.3,469.3 400.3,469.0 399.3,468.8 399.3,468.4 399.5,468.0 401.4,466.8 402.5,466.2 403.3,466.2 404.0,465.9 404.8,465.3 405.3,465.1 406.7,464.8 407.3,464.7 409.4,464.3 410.0,464.3 410.9,464.3 412.0,464.0 415.3,463.2 416.1,462.9 417.0,462.8 419.6,462.1 422.0,461.8 423.2,461.7 424.6,461.5 426.2,461.3 427.6,461.0 432.6,460.5 435.6,460.3 437.6,460.2 439.0,460.3 440.5,460.2 441.7,460.1 442.3,460.0 443.1,459.7 445.9,459.8 447.6,459.6 448.4,459.6 449.2,459.5 448.8,459.4 448.3,459.3 447.9,459.0 448.3,458.7 449.4,458.3 449.8,458.0 450.2,457.5 451.6,456.6 452.8,456.4 453.5,456.5 454.4,456.5 456.5,456.7 456.9,456.6 457.5,456.3 458.1,456.0 459.3,455.5 459.4,455.1 457.6,455.2 456.3,455.4 454.9,455.3 455.5,455.0 454.5,454.7 454.1,454.6 454.3,454.0 454.8,453.9 456.1,453.2 456.7,453.0 458.3,452.9 460.2,453.1 460.7,453.1 461.1,452.8 460.6,452.3 461.3,452.1 462.2,452.2 463.3,452.2 464.6,451.7 466.4,451.4 467.3,451.2 468.1,451.1 468.5,450.8 469.1,450.1 469.6,449.7 469.4,449.3 468.9,449.4 468.5,449.4 467.5,449.2 466.3,448.9 465.9,448.6 466.1,448.1 466.5,448.0 466.9,448.0 467.6,448.1 468.5,448.4 469.0,448.6 469.5,448.8 469.9,448.8 470.4,448.5 470.8,448.0 471.6,447.8 472.1,447.6 471.3,447.3 471.9,447.0 472.5,447.3 473.3,447.5 473.9,447.6 474.4,447.7 475.1,448.2 476.0,449.1 476.4,449.1 477.2,449.0 478.0,449.0 478.6,448.7 478.7,448.1 478.8,447.6 478.1,447.1 478.5,446.8 479.5,446.6 480.5,446.8 481.0,446.8 481.8,446.7 482.7,446.5 483.5,446.4 484.2,446.5 484.1,447.1 483.6,447.4 483.1,447.7 483.0,448.1 483.5,448.2 487.6,448.1 488.2,448.2 488.9,448.2 489.7,448.3 491.0,448.2 492.2,448.1 492.7,448.1 493.7,448.2 494.4,448.4 495.8,448.4 496.2,448.3 496.6,448.0 497.0,448.0 497.5,448.2 497.7,448.7 498.5,449.2 499.1,449.0 499.5,448.8 500.4,448.3 501.5,448.0 502.3,447.8 504.3,447.4 505.3,447.2 507.2,446.9 509.7,446.8 514.2,446.3 515.7,446.2 518.1,446.1 519.3,445.9 520.6,445.8 521.3,445.4 523.1,445.7 523.7,445.8 524.5,445.5 525.4,445.0 526.7,445.2 527.5,445.6 528.4,445.9 530.5,446.4 531.1,446.5 532.5,446.6 532.9,446.5 533.5,446.2 534.2,445.7 534.6,445.5 535.2,445.3 535.9,445.0 535.3,444.8 536.3,444.6 536.9,445.1 537.6,445.2 538.4,445.4 540.3,445.3 541.8,445.3 543.2,445.4 543.9,445.3 544.5,445.0 545.5,444.8 546.1,445.0 546.4,445.5 547.7,445.7 550.3,445.9 551.0,445.6 551.7,445.2 552.4,445.0 552.8,445.0 553.3,445.3 553.1,445.8 552.9,446.3 553.2,446.7 553.9,447.0 554.6,447.0 555.4,447.0 555.9,447.0 558.5,446.8 558.8,446.3 559.3,445.8 560.3,445.2 560.7,445.2 561.7,445.6 562.1,445.8 561.8,446.2 562.3,446.5 563.8,446.7 564.3,446.7 565.0,446.5 565.7,446.0 566.1,445.6 566.7,445.6 567.3,445.7 567.7,445.9 567.7,446.4 568.3,446.7 568.8,446.9 570.0,447.1 571.2,447.2 572.1,447.3 573.6,447.3 574.3,447.1 574.8,447.1 575.6,447.0 576.4,446.7 576.9,446.6 578.9,446.3 580.3,446.1 581.8,445.6 583.3,445.3 585.6,445.1 586.3,445.1 587.2,445.1 589.3,444.7 590.2,444.5 590.6,444.4 591.1,444.2 591.4,443.7 591.6,443.1 591.4,442.7 590.9,442.4 590.5,441.9 590.7,441.3 592.0,440.8 593.0,440.8 594.0,440.8 595.0,440.8 594.6,441.3 594.1,441.6 594.6,442.0 596.1,441.9 596.5,442.1 597.6,443.0 597.8,443.4 598.2,443.6 598.8,443.5 600.0,443.5 600.9,443.4 601.6,443.4 602.0,443.5 602.4,443.7 603.1,443.9 603.8,443.7 604.3,443.7 605.0,443.7 606.0,444.0 606.9,444.6 608.0,444.9 607.9,444.5 608.4,444.2 608.9,443.9 609.7,443.4 610.3,442.8 610.4,442.1 610.7,441.6 611.2,441.3 611.7,441.1 612.5,440.9 613.4,440.9 614.3,440.5 614.9,440.3 616.2,440.1 617.8,439.9 618.9,439.2 619.3,439.2 619.9,439.1 621.0,439.0 622.7,438.8 623.3,438.8 624.2,438.6 625.0,438.2 625.5,438.1 626.6,438.2 627.5,437.9 628.2,437.9 628.9,437.8 628.7,437.4 629.0,437.0 630.2,436.9 631.0,437.1 631.5,437.1 630.9,437.7 631.4,438.0 631.9,438.1 632.5,438.1 633.2,437.9 633.9,438.1 634.2,438.7 634.9,438.7 635.1,438.3 635.1,437.8 636.3,437.1 636.7,436.7 635.9,436.7 635.3,436.7 634.6,436.2 635.6,435.9 636.8,435.9 637.5,436.2 638.9,436.6 639.7,436.6 640.4,436.7 640.3,435.9 639.7,435.4 639.6,435.0 639.8,434.6 640.5,434.3 641.5,434.2 643.6,433.5 644.1,433.4 645.5,433.2 647.1,433.2 649.1,432.9 652.6,433.1 653.6,433.2 654.2,433.3 654.7,433.6 655.5,433.9 656.6,434.4 657.9,434.5 658.3,434.7 658.8,435.0 658.3,435.3 657.8,435.3 657.0,435.2 656.4,435.0 656.0,435.1 656.4,435.3 656.8,435.5 656.6,436.0 655.0,436.7 656.0,436.8 656.6,436.7 657.1,436.4 657.7,436.3 659.3,436.3 660.1,436.2 660.6,436.2 661.2,436.4 662.0,436.6 663.2,436.7 664.6,437.5 665.7,437.4 666.3,437.2 668.0,437.2 669.5,437.5 670.3,437.6 672.7,437.7 674.1,437.9 675.0,437.7 675.7,437.6 676.9,437.5 677.6,437.6 679.4,437.8 682.5,438.1 684.7,438.2 686.6,438.2 687.5,438.4 689.2,438.5 689.8,438.6 691.4,438.5 692.1,438.4 692.8,438.2 693.2,438.2 693.3,439.0 693.9,439.7 694.4,440.2 693.8,440.6 693.1,440.9 693.2,441.3 693.1,441.7 693.4,442.1 693.2,442.5 692.7,442.6 691.8,442.6 691.4,442.7 691.6,443.2 692.0,443.3 692.1,443.8 691.4,444.2 691.0,444.2 690.0,444.2 689.4,444.0 689.0,444.2 687.7,444.7 687.3,444.9 686.9,445.2 687.9,445.4 688.7,445.6 690.4,445.6 691.0,445.5 691.7,445.3 692.1,445.4 692.2,446.1 691.3,447.3 690.6,447.7 690.1,447.9 689.7,448.0 689.0,448.3 688.5,448.8 688.0,449.3 687.3,450.0 686.9,450.8 686.7,451.3 686.4,451.8 685.8,452.6 685.4,452.8 684.7,453.1 685.5,453.4 686.1,453.4 687.0,453.6 688.2,453.2 688.8,453.0 688.9,452.6 688.8,452.1 689.2,451.8 690.1,451.4 692.1,451.2 692.5,451.1 693.2,451.0 693.8,450.7 694.3,450.4 695.3,450.2 696.0,449.8 696.5,449.5 697.4,449.3 698.0,449.0 698.3,448.1 698.5,447.7 699.0,447.1 699.4,446.7 700.7,446.3 701.2,446.1 701.7,445.8 702.1,445.5 702.1,445.1 702.3,444.7 702.9,444.5 703.7,444.0 704.7,444.0 705.4,443.7 706.2,443.9 707.1,444.1 708.7,444.0 709.5,444.1 710.1,444.0 710.6,443.7 710.8,443.3 711.4,443.0 712.1,443.0 713.2,442.6 714.4,442.2 715.4,442.1 716.2,441.9 716.7,441.4 717.3,441.0 718.0,440.6 718.2,440.0 718.7,439.7 719.5,439.4 720.2,439.2 723.2,438.7 725.5,438.4 727.8,438.0 728.5,438.0 729.5,437.8 731.0,437.8 731.4,437.8 731.9,437.3 733.1,436.9 733.8,436.8 734.7,436.4 735.4,436.4 736.4,436.5 737.3,436.6 738.1,436.6 739.2,436.3 741.0,436.2 741.5,436.1 741.9,435.9 744.4,435.5 745.3,435.6 746.6,435.5 747.4,435.6 748.2,435.6 749.2,435.6 750.8,435.5 751.5,435.4 752.8,435.0 754.3,434.9 754.9,434.8 755.8,434.7 756.4,434.9 756.9,435.0 757.6,435.1 758.5,434.9 759.3,435.0 760.3,435.1 761.0,435.2 761.4,435.2 762.0,435.1 762.7,434.8 763.4,434.7 764.1,434.8 764.6,434.9 765.4,435.1 766.6,435.1 767.8,435.0 768.9,434.9 769.7,434.7 770.5,434.9 771.4,435.0 772.9,434.6 773.5,434.7 773.9,434.8 774.6,435.2 776.0,435.1 777.3,434.9 778.4,434.7 779.4,434.5 780.2,434.3 781.5,433.6 781.9,433.2 783.8,433.2 784.4,433.1 785.2,433.0 786.6,433.1 787.9,433.3 788.8,433.3 789.7,433.4 790.7,433.7 791.7,433.8 795.5,434.5 797.7,434.6 798.8,434.9 799.4,435.2 800.0,435.2 800.4,435.1 801.0,435.5 802.5,435.7 804.1,435.9 805.1,435.6 806.8,435.1 807.3,434.8 807.2,434.2 808.1,433.5 809.6,433.2 811.5,433.1 812.6,432.9 814.2,432.8 814.9,432.9 815.3,433.0 815.9,433.1 816.5,433.5 817.6,434.3 818.4,434.6 819.1,434.7 819.7,434.7 820.3,434.9 821.2,435.5 820.7,436.0 820.2,436.2 818.2,436.4 817.4,436.6 816.6,436.7 816.4,437.1 817.5,437.2 818.5,437.2 819.2,437.1 819.9,437.0 820.5,436.8 821.9,436.7 822.8,436.5 823.6,436.4 824.2,436.2 824.8,436.3 825.4,436.4 825.8,436.4 827.1,436.5 827.6,436.3 828.2,436.3 828.7,436.4 829.2,436.6 829.8,436.6 830.5,436.5 831.4,436.3 832.7,436.1 833.9,436.0 834.4,436.1 833.2,436.3 831.3,436.7 830.3,437.0 830.9,437.1 834.4,436.8 836.0,436.5 837.5,436.4 839.0,435.8 839.4,435.7 840.6,435.6 842.3,435.4 843.5,435.2 844.4,435.0 845.0,435.0 845.5,435.1 846.1,435.3 846.7,435.3 847.5,435.1 848.0,434.8 848.9,434.4 849.6,434.3 850.2,434.4 851.2,434.6 851.8,434.7 852.4,435.4 853.8,436.1 854.3,436.3 855.5,436.2 856.7,436.4 857.3,436.4 857.8,436.3 858.3,436.4 859.0,436.2 859.7,435.4 860.4,434.6 861.0,434.3 861.4,434.1 861.9,434.1 862.7,433.9 863.8,433.9 864.5,433.9 866.2,434.0 867.6,433.8 869.1,433.8 869.9,433.6 870.7,433.6 871.8,433.8 872.7,434.1 873.0,434.7 874.4,434.3 874.9,434.3 876.0,433.7 876.5,433.8 877.8,434.1 878.3,434.1 879.3,434.6 879.8,434.5 880.2,434.3 881.5,434.3 882.6,434.5 883.1,434.6 883.7,434.8 884.1,434.9 886.8,434.9 887.8,435.1 888.6,435.3 891.4,435.4 892.5,435.6 893.1,435.5 894.4,435.6 894.9,435.8 895.3,436.0 896.4,436.1 896.9,436.1 897.7,436.0 898.5,435.8 899.2,435.8 899.6,435.9 899.7,436.4 900.3,436.4 901.0,436.2 901.5,436.2 901.4,436.9 900.7,437.4 900.4,437.9 899.8,438.3 900.5,438.6 901.1,438.3 902.4,438.1 903.1,437.8 904.3,437.8 905.5,437.8 906.3,438.2 907.9,438.8 908.0,439.2 907.8,439.6 908.6,439.9 909.3,440.0 909.9,439.9 912.4,440.2 913.6,440.1 914.6,440.1 915.9,440.0 916.8,440.1 917.6,440.1 918.5,440.0 919.3,439.9 919.8,440.6 919.8,441.0 920.2,441.2 920.7,441.0 923.0,440.9 923.7,440.9 924.5,441.0 925.2,441.3 925.9,441.2 926.4,441.0 927.0,440.9 927.2,440.3 927.2,439.9 927.9,439.9 928.3,440.0 929.4,440.7 930.2,441.0 931.0,441.4 932.0,441.7 933.4,441.9 934.7,442.2 936.2,442.2 937.4,442.5 938.3,442.2 938.7,442.2 939.3,442.2 940.1,442.5 940.7,442.6 942.7,443.0 943.8,443.1 944.2,443.4 944.8,443.7 945.0,444.4 946.3,444.7 946.7,445.0 947.3,445.3 948.4,446.7 949.0,447.0 949.8,447.0 950.5,447.3 950.1,446.2 950.1,445.7 950.6,445.4 951.9,445.3 952.8,445.8 953.7,446.2 954.3,446.2 955.5,446.2 956.7,445.9 957.5,446.0 958.9,446.0 960.7,446.2 961.5,446.2 962.8,446.3 964.5,446.6 965.5,446.7 966.1,447.0 966.6,447.5 967.1,447.7 967.7,447.8 968.9,448.0 971.3,448.6 972.2,448.8 972.7,449.0 973.0,448.5 973.4,448.4 973.9,448.9 974.4,449.3 974.1,449.9 973.4,449.9 972.8,449.9 972.3,450.3 972.1,451.1 972.6,451.1 973.0,451.3 972.4,451.7 971.6,451.5 970.7,451.4 969.6,451.3 968.7,451.1 968.3,451.1 967.9,451.1 968.4,451.3 968.9,451.5 970.2,451.7 971.7,452.0 971.4,452.4 971.0,452.9 969.5,453.3 968.7,453.0 967.7,453.0 967.2,453.1 966.3,453.1 964.3,453.2 963.6,452.8 962.4,452.6 963.4,453.4 964.5,453.5 965.6,453.7 964.7,454.0 963.9,454.3 962.3,454.2 961.5,454.3 961.1,454.4 960.7,454.4 961.4,454.8 960.9,455.1 960.4,455.2 959.9,455.1 959.3,455.2 959.0,454.4 958.7,453.8 958.3,453.8 957.8,453.9 957.6,454.3 958.0,455.1 958.1,455.6 957.7,455.6 958.4,456.3 959.1,456.7 959.5,457.1 958.3,457.1 957.9,457.2 957.5,457.1 956.7,457.0 956.0,457.0 955.4,457.1 954.8,457.1 954.3,456.7 953.9,456.6 953.5,456.7 953.2,457.2 952.7,457.4 952.1,457.6 951.7,457.8 951.5,458.8 951.1,459.0 950.6,459.0 950.2,459.1 949.7,459.0 949.1,458.9 947.0,459.3 947.8,459.4 949.7,459.5 950.5,459.6 950.7,460.1 951.6,460.4 952.1,460.5 952.1,461.0 951.2,461.5 952.0,461.7 952.3,462.4 951.9,462.7 952.1,463.2 951.7,463.4 951.2,463.8 951.9,463.9 952.4,464.0 953.0,464.0 953.5,464.2 954.0,464.6 954.5,465.0 954.5,465.5 954.9,465.8 955.7,466.0 956.2,466.3 956.7,466.3 956.7,466.8 955.9,467.1 955.5,467.3 956.4,467.3 957.3,467.5 958.5,467.3 959.1,467.0 959.5,466.8 960.2,467.5 961.7,467.9 962.5,468.0 963.3,468.1 964.0,468.1 964.0,468.6 963.5,468.6 962.6,468.6 961.9,468.4 961.4,468.3 957.3,468.3 956.4,468.4 955.3,468.7 954.2,468.8 952.5,469.0 951.8,469.2 949.9,468.6 949.3,468.2 948.6,468.3 949.5,469.2 949.9,469.4 949.3,469.6 948.7,469.5 947.7,469.4 946.9,469.6 945.8,470.0 946.3,470.4 945.0,471.0 944.6,471.0 945.3,471.2 944.1,471.6 944.7,471.9 945.5,471.9 946.0,472.0 945.5,472.4 944.9,472.5 941.0,473.0 940.4,473.2 941.8,473.5 945.9,473.4 945.9,473.8 946.7,474.1 945.8,474.4 945.2,474.4 946.1,474.7 946.5,475.3 945.9,475.7 947.0,476.1 948.8,476.7 949.2,476.7 950.0,476.8 951.2,477.1 951.6,477.3 952.3,477.4 952.8,477.7 954.4,478.1 951.2,478.6 947.7,478.9 951.8,479.1 952.8,479.3 953.3,479.2 955.6,478.9 957.6,478.8 958.3,478.8 961.1,479.5 962.3,479.8 963.2,479.9 963.8,479.9 964.2,480.0 964.6,480.2 965.0,480.6 965.6,480.7 966.2,480.6 966.9,480.5 967.4,480.5 968.3,480.7 967.8,481.0 967.3,481.2 966.6,481.2 966.2,481.2 965.8,481.2 966.2,481.4 967.0,481.6 971.8,481.7 973.1,481.9 974.5,481.8 975.1,481.8 975.6,481.9 976.5,482.2 977.5,482.3 979.0,482.4 980.2,482.4 981.7,482.7 982.4,482.7 982.8,482.8 986.1,482.9 986.6,483.0 987.0,483.2 987.8,483.2 988.6,483.3 993.3,483.5 995.0,483.7 995.4,483.7 995.8,483.7 997.1,483.8 998.3,483.9 998.9,484.1 0.0,484.3ZM0.0,500.0 996.1,500.0 992.2,500.0 988.3,500.0 984.4,500.0 980.5,500.0 976.6,500.0 972.7,500.0 968.7,500.0 964.8,500.0 960.9,500.0 957.0,500.0 953.1,500.0 949.2,500.0 945.3,500.0 941.4,500.0 937.5,500.0 933.6,500.0 929.7,500.0 925.8,500.0 921.9,500.0 918.0,500.0 914.1,500.0 910.2,500.0 906.2,500.0 902.3,500.0 898.4,500.0 894.5,500.0 890.6,500.0 886.7,500.0 882.8,500.0 878.9,500.0 875.0,500.0 871.1,500.0 867.2,500.0 863.3,500.0 859.4,500.0 855.5,500.0 851.6,500.0 847.7,500.0 843.7,500.0 839.8,500.0 835.9,500.0 832.0,500.0 828.1,500.0 824.2,500.0 820.3,500.0 816.4,500.0 812.5,500.0 808.6,500.0 804.7,500.0 800.8,500.0 796.9,500.0 793.0,500.0 789.1,500.0 785.2,500.0 781.2,500.0 777.3,500.0 773.4,500.0 769.5,500.0 765.6,500.0 761.7,500.0 757.8,500.0 753.9,500.0 750.0,500.0 746.1,500.0 742.2,500.0 738.3,500.0 734.4,500.0 730.5,500.0 726.6,500.0 722.7,500.0 718.7,500.0 714.8,500.0 710.9,500.0 707.0,500.0 703.1,500.0 699.2,500.0 695.3,500.0 691.4,500.0 687.5,500.0 683.6,500.0 679.7,500.0 675.8,500.0 671.9,500.0 668.0,500.0 664.1,500.0 660.2,500.0 656.2,500.0 652.3,500.0 648.4,500.0 644.5,500.0 640.6,500.0 636.7,500.0 632.8,500.0 628.9,500.0 625.0,500.0 621.1,500.0 617.2,500.0 613.3,500.0 609.4,500.0 605.5,500.0 601.6,500.0 597.7,500.0 593.7,500.0 589.8,500.0 585.9,500.0 582.0,500.0 578.1,500.0 574.2,500.0 570.3,500.0 566.4,500.0 562.5,500.0 558.6,500.0 554.7,500.0 550.8,500.0 546.9,500.0 543.0,500.0 539.1,500.0 535.2,500.0 531.2,500.0 527.3,500.0 523.4,500.0 519.5,500.0 515.6,500.0 511.7,500.0 507.8,500.0 503.9,500.0 500.0,500.0 496.1,500.0 492.2,500.0 488.3,500.0 484.4,500.0 480.5,500.0 476.6,500.0 472.7,500.0 468.8,500.0 464.8,500.0 460.9,500.0 457.0,500.0 453.1,500.0 449.2,500.0 445.3,500.0 441.4,500.0 437.5,500.0 433.6,500.0 429.7,500.0 425.8,500.0 421.9,500.0 418.0,500.0 414.1,500.0 410.2,500.0 406.3,500.0 402.3,500.0 398.4,500.0 394.5,500.0 390.6,500.0 386.7,500.0 382.8,500.0 378.9,500.0 375.0,500.0 371.1,500.0 367.2,500.0 363.3,500.0 359.4,500.0 355.5,500.0 351.6,500.0 347.7,500.0 343.8,500.0 339.8,500.0 335.9,500.0 332.0,500.0 328.1,500.0 324.2,500.0 320.3,500.0 316.4,500.0 312.5,500.0 308.6,500.0 304.7,500.0 300.8,500.0 296.9,500.0 293.0,500.0 289.1,500.0 285.2,500.0 281.3,500.0 277.3,500.0 273.4,500.0 269.5,500.0 265.6,500.0 261.7,500.0 257.8,500.0 253.9,500.0 250.0,500.0 246.1,500.0 242.2,500.0 238.3,500.0 234.4,500.0 230.5,500.0 226.6,500.0 222.7,500.0 218.8,500.0 214.8,500.0 210.9,500.0 207.0,500.0 203.1,500.0 199.2,500.0 195.3,500.0 191.4,500.0 187.5,500.0 183.6,500.0 179.7,500.0 175.8,500.0 171.9,500.0 168.0,500.0 164.1,500.0 160.2,500.0 156.3,500.0 152.3,500.0 148.4,500.0 144.5,500.0 140.6,500.0 136.7,500.0 132.8,500.0 128.9,500.0 125.0,500.0 121.1,500.0 117.2,500.0 113.3,500.0 109.4,500.0 105.5,500.0 101.6,500.0 97.7,500.0 93.8,500.0 89.8,500.0 85.9,500.0 82.0,500.0 78.1,500.0 74.2,500.0 70.3,500.0 66.4,500.0 62.5,500.0 58.6,500.0 54.7,500.0 50.8,500.0 46.9,500.0 43.0,500.0 39.1,500.0 35.2,500.0 31.3,500.0 27.3,500.0 23.4,500.0 19.5,500.0 15.6,500.0 11.7,500.0 7.8,500.0 3.9,500.0 0.0,500.0ZM281.1,343.5 280.6,343.5 281.0,343.3ZM38.6,66.0 39.4,65.8 38.6,66.0ZM651.3,182.1 650.9,182.0 651.3,182.1Z";

// ════════════════════════════════════════
// 재난·BP 세계지도 — 인라인 SVG(외부 의존 0). 지진(USGS 실좌표)·뉴스 빈도(coarse)·BP 사이트
// world-atlas land-50m → equirectangular 투영(빌드타임 1회 생성, 소수1자리). 투영식은 점 변환에도 재사용.
// 110m에서 50m로 올렸다. 110m 해안선은 매립 산단을 통째로 누락해서
// 온산·여수·가시마 같은 해안 공장이 지도상 바다 위에 떠 보였다.
// ════════════════════════════════════════
const MAP_W=1000, MAP_H=500;
function mapPx(lon){ return (lon+180)/360*MAP_W; }
function mapPy(lat){ return (90-lat)/180*MAP_H; }
const MAP_VIEWS={ kr:{vb:"844.4 140.3 22.3 18.6", k:0.035}, asia:{vb:"763.9 111.1 152.8 97.2", k:0.16}, world:{vb:"0 0 1000 500", k:1} };   // kr≈lon124~132·lat32.8~39.5 · asia≈lon95~150·lat15~50, k=점크기 줌보정
// 지역 중심점 — 뉴스 빈도 버블(coarse 표시 전용). ★거리판정 입력 금지(과거 감사 확정). GLB(미상) 제외.
const REGION_CENTROIDS={TW:[23.7,121],JP:[36.2,138.2],KR:[36.5,127.8],CN:[35,105],UA:[48.5,31],RU:[60,90],IL:[31.5,34.8],IR:[32,53],ME:[25,45],US:[39,-98],EU:[50,10]};
// 세부 지역(한·중·일) — ★지도 버블 표시 전용. 판정·교차검증(corroboration 버킷)은 국가 단위 유지(신호품질 보호).
// [표시명, [lat,lon], [키워드 정규식…]] — 모호어는 lookahead로 오탐 억제(이천만원·아산병원·구미가·경주마·화성(Mars) 등). 목록 순서 = 우선순위.
const SUB_REGIONS={
KR:[
 ["이천",[37.27,127.44],["이천(?!만|억|명|원|년|여)","icheon"]],
 ["청주",[36.64,127.49],["청주","오창","오송","cheongju"]],
 ["평택",[36.99,127.09],["평택","pyeongtaek"]],
 ["용인",[37.24,127.18],["용인","yongin"]],
 ["수원",[37.26,127.03],["수원","suwon"]],
 ["화성(경기)",[37.20,126.83],["화성시","동탄","경기\\s*화성","화성\\s*(공장|산단)"]],
 ["안성",[37.01,127.27],["안성"]],
 ["성남·판교",[37.44,127.14],["성남","판교"]],
 ["안산·시흥",[37.32,126.83],["안산","시흥","반월"]],
 ["파주",[37.76,126.78],["파주"]],
 ["서울",[37.57,126.98],["서울","seoul"]],
 ["인천",[37.46,126.71],["인천","incheon"]],
 ["광주(경기)",[37.41,127.26],["경기\\s*광주","곤지암"]],
 ["천안·아산",[36.80,127.05],["천안","아산(?!병원)"]],
 ["대전·세종",[36.40,127.35],["대전","세종시","세종특별","daejeon"]],
 ["충주",[36.99,127.93],["충주"]],
 ["당진·서산",[36.84,126.55],["당진","서산","대산\\s*(석유|산단|공단)"]],
 ["군산",[35.97,126.71],["군산"]],
 ["전주·익산",[35.88,127.07],["전주","익산","완주"]],
 ["광주",[35.16,126.85],["광주","gwangju"]],
 ["여수·광양",[34.85,127.68],["여수","광양","yeosu"]],
 ["목포",[34.81,126.39],["목포"]],
 ["대구",[35.87,128.60],["대구","daegu"]],
 ["구미",[36.12,128.34],["구미(?!가)","gumi"]],
 ["포항",[36.02,129.36],["포항","pohang"]],
 ["경주",[35.86,129.22],["경주(?!마)","gyeongju"]],
 ["안동",[36.57,128.73],["안동"]],
 ["울산",[35.54,129.31],["울산","ulsan"]],
 ["부산",[35.18,129.08],["부산","busan"]],
 ["창원·김해",[35.25,128.75],["창원","김해","마산"]],
 ["거제·통영",[34.86,128.55],["거제","통영"]],
 ["강원영동",[37.75,128.90],["강릉","동해시","삼척","속초"]],
 ["원주·춘천",[37.60,127.90],["원주","춘천"]],
 ["제주",[33.50,126.53],["제주","jeju"]],
],
JP:[
 ["미야기(센다이)",[38.27,140.87],["센다이","미야기","sendai","miyagi"]],
 ["이와테",[39.70,141.15],["이와테","미야코","산리쿠","iwate","sanriku","morioka"]],
 ["후쿠시마",[37.40,140.47],["후쿠시마","fukushima"]],
 ["아오모리·아키타",[40.60,140.50],["아오모리","아키타","aomori","akita"]],
 ["야마가타",[38.25,140.34],["야마가타","yamagata"]],
 ["도쿄",[35.68,139.69],["도쿄","tokyo"]],
 ["가나가와(요코하마)",[35.45,139.64],["요코하마","가나가와","가와사키","yokohama","kawasaki","kanagawa"]],
 ["지바",[35.60,140.12],["지바","chiba"]],
 ["사이타마·북관동",[36.30,139.50],["사이타마","군마","도치기","우쓰노미야","saitama","gunma","tochigi"]],
 ["이바라키",[36.34,140.45],["이바라키","ibaraki"]],
 ["니가타",[37.90,139.02],["니가타","niigata"]],
 ["호쿠리쿠(노토)",[36.80,136.80],["도야마","이시카와","가나자와","후쿠이","노토","toyama","ishikawa","kanazawa","noto","fukui"]],
 ["아이치(나고야)",[35.18,136.91],["나고야","아이치","도요타","nagoya","aichi"]],
 ["시즈오카",[34.98,138.38],["시즈오카","하마마쓰","shizuoka","hamamatsu"]],
 ["나가노·기후",[36.00,138.00],["나가노","야마나시","기후현","기후시","nagano","gifu","yamanashi"]],
 ["오사카",[34.69,135.50],["오사카","사카이","osaka","sakai"]],
 ["교토·나라",[34.90,135.75],["교토","시가현","나라현","kyoto","nara"]],
 ["효고(고베)",[34.69,135.19],["고베","효고","히메지","kobe","hyogo","himeji"]],
 ["히로시마",[34.40,132.46],["히로시마","hiroshima"]],
 ["주고쿠 기타",[34.80,133.20],["오카야마","야마구치","시마네","돗토리","okayama","yamaguchi","shimane","tottori"]],
 ["시코쿠",[33.80,133.50],["시코쿠","마쓰야마","도쿠시마","가가와","고치현","에히메","shikoku","ehime","matsuyama"]],
 ["후쿠오카(기타큐슈)",[33.59,130.40],["후쿠오카","기타큐슈","fukuoka","kitakyushu"]],
 ["구마모토",[32.80,130.71],["구마모토","kumamoto"]],
 ["나가사키·사가",[32.90,129.90],["나가사키","사가현","nagasaki","saga"]],
 ["가고시마·미야자키",[31.60,130.90],["가고시마","미야자키","사쿠라지마","kagoshima","miyazaki","sakurajima"]],
 ["오이타",[33.24,131.61],["오이타","벳푸","oita","beppu"]],
 ["오키나와",[26.20,127.70],["오키나와","나하","okinawa","naha"]],
 ["홋카이도",[43.10,142.50],["홋카이도","삿포로","hokkaido","sapporo"]],
 ["도호쿠",[38.70,140.90],["도호쿠","tohoku"]],
 ["간토",[35.80,139.80],["간토","kanto"]],
 ["간사이",[34.80,135.50],["간사이","긴키","kansai","kinki"]],
 ["규슈",[32.80,130.70],["규슈","kyushu"]],
 ["주부",[35.20,137.00],["주부","chubu"]],
],
CN:[
 ["우시",[31.57,120.30],["우시","무석","wuxi"]],
 ["쑤저우",[31.30,120.58],["쑤저우","suzhou"]],
 ["난징",[32.06,118.80],["난징","남경","nanjing"]],
 ["상하이",[31.23,121.47],["상하이","shanghai"]],
 ["항저우",[30.27,120.15],["항저우","hangzhou"]],
 ["닝보",[29.87,121.54],["닝보","ningbo"]],
 ["다롄",[38.91,121.60],["다롄","대련","dalian"]],
 ["선양(랴오닝)",[41.80,123.40],["선양","랴오닝","요령","shenyang","liaoning"]],
 ["베이징",[39.90,116.40],["베이징","beijing"]],
 ["톈진",[39.13,117.20],["톈진","천진","tianjin"]],
 ["허베이(탕산)",[38.04,114.51],["허베이","스자좡","탕산","hebei","tangshan","shijiazhuang"]],
 ["칭다오",[36.07,120.38],["칭다오","qingdao"]],
 ["산둥 기타",[36.70,117.50],["산둥","산동","지난시","옌타이","shandong","jinan","yantai"]],
 ["선전",[22.54,114.06],["선전","심천","shenzhen"]],
 ["광저우·둥관",[23.10,113.50],["광저우","둥관","포산","guangzhou","dongguan","foshan"]],
 ["홍콩·마카오",[22.30,114.17],["홍콩","마카오","hong\\s*kong","macau"]],
 ["광둥 기타",[23.40,113.90],["광둥","광동","주하이","guangdong","zhuhai"]],
 ["푸젠(샤먼)",[26.07,119.30],["푸젠","복건","샤먼","푸저우","xiamen","fujian","fuzhou"]],
 ["우한(후베이)",[30.59,114.31],["우한","후베이","호북","wuhan","hubei"]],
 ["후난·장시",[28.20,113.50],["후난","창사","장시성","난창","hunan","changsha","jiangxi"]],
 ["청두(쓰촨)",[30.57,104.07],["청두","쓰촨","사천","chengdu","sichuan"]],
 ["충칭",[29.56,106.55],["충칭","chongqing"]],
 ["시안(산시)",[34.34,108.94],["시안","섬서","산시성","xi'an","shaanxi"]],
 ["허난(정저우)",[34.75,113.63],["허난","정저우","하남성","zhengzhou","henan"]],
 ["윈난·구이저우",[25.60,103.80],["윈난","쿤밍","구이저우","구이양","yunnan","kunming","guizhou"]],
 ["광시",[22.80,108.30],["광시","난닝","guangxi","nanning"]],
 ["동북 기타",[45.00,126.50],["지린","창춘","하얼빈","헤이룽장","harbin","jilin","changchun","heilongjiang"]],
 ["네이멍구",[41.00,111.00],["네이멍구","내몽골","후허하오터","inner\\s*mongolia","hohhot"]],
 ["신장·간쑤",[40.00,90.00],["신장","우루무치","간쑤","란저우","칭하이","xinjiang","urumqi","gansu","lanzhou","qinghai"]],
 ["하이난",[19.20,109.70],["하이난","hainan"]],
 ["장쑤 기타",[32.90,119.80],["장쑤","강소","jiangsu"]],
 ["저장 기타",[29.20,120.50],["저장","원저우","zhejiang","wenzhou"]],
],
};
const SUB_REGIONS_RX={}; for(const c in SUB_REGIONS){ SUB_REGIONS_RX[c]=SUB_REGIONS[c].map(([name,ll,kws])=>({name,ll,rx:new RegExp(kws.join("|"),"i")})); }
function detectSubRegion(code,title,desc){   // ★지도 표시 전용 — 판정·거리 계산 미사용
  const list=SUB_REGIONS_RX[code]; if(!list) return null;
  const s=title+" "+(desc||"");
  for(const e of list) if(e.rx.test(s)) return e;
  return null;
}
let mapView="asia";   // 기본: 한·중·일 확대(주 사용 지역)
// ── 연속 줌·팬 상태 — viewBox를 단일 진실로, 토글은 프리셋으로 애니메이션 이동 + 줌 레벨에 따라 자동 동기 ──
const MAP_VB={x:763.9,y:111.1,w:152.8,h:97.2};   // 현재 viewBox (기본 asia)
function mapK(){ return Math.max(0.02, Math.pow(MAP_VB.w/1000, 0.9)); }   // 점 크기 줌 보정(연속)
function applyVB(){ const svg=document.getElementById("mapSvg"); if(svg) svg.setAttribute("viewBox",`${MAP_VB.x.toFixed(2)} ${MAP_VB.y.toFixed(2)} ${MAP_VB.w.toFixed(2)} ${MAP_VB.h.toFixed(2)}`); }
function clampVB(){
  MAP_VB.w=Math.min(1000,Math.max(10,MAP_VB.w)); MAP_VB.h=Math.min(500,Math.max(5,MAP_VB.h));
  MAP_VB.x=Math.min(1000-MAP_VB.w,Math.max(0,MAP_VB.x)); MAP_VB.y=Math.min(500-MAP_VB.h,Math.max(0,MAP_VB.y));
}
// 줌 폭 → 가장 가까운 프리셋으로 토글 상태 동기(스크롤 확대 시 '자연스럽게' 한국↔한중일↔세계 전환 표시)
function syncMapToggle(){
  const v=MAP_VB.w<=60?"kr":MAP_VB.w<=420?"asia":"world";
  if(v!==mapView){ mapView=v; document.querySelectorAll("#mapToggle button").forEach(b=>b.classList.toggle("on",b.dataset.v===v)); }
}
let _mapAnim=null, _mapRerender=null;
function _queueMapRender(){ clearTimeout(_mapRerender); _mapRerender=setTimeout(()=>renderMap(),140); }   // 휠 연타 시 점 크기 재계산 디바운스
function setMapView(v){
  if(!MAP_VIEWS[v]) return;
  hideMapPop();
  mapView=v;
  document.querySelectorAll("#mapToggle button").forEach(b=>b.classList.toggle("on",b.dataset.v===v));
  const [tx,ty,tw,th]=MAP_VIEWS[v].vb.split(" ").map(Number);
  // 현재 viewBox → 프리셋으로 280ms 이징 애니메이션(자연스러운 전환)
  cancelAnimationFrame(_mapAnim);
  const s={...MAP_VB}, t0=performance.now(), D=280, ease=p=>1-Math.pow(1-p,3);
  const step=now=>{
    const p=Math.min(1,(now-t0)/D), e=ease(p);
    MAP_VB.x=s.x+(tx-s.x)*e; MAP_VB.y=s.y+(ty-s.y)*e; MAP_VB.w=s.w+(tw-s.w)*e; MAP_VB.h=s.h+(th-s.h)*e;
    applyVB();
    if(p<1) _mapAnim=requestAnimationFrame(step); else renderMap();
  };
  _mapAnim=requestAnimationFrame(step);
}
// +/- 버튼·프로그램 줌 — 지도 중심 기준. 휠 줌과 동일 경로(clamp·토글동기·점재계산) 재사용.
function zoomMapBy(f){
  hideMapPop();
  const cx=MAP_VB.x+MAP_VB.w/2, cy=MAP_VB.y+MAP_VB.h/2;
  const nw=Math.min(1000,Math.max(10,MAP_VB.w*f)), r=nw/MAP_VB.w;
  MAP_VB.w=nw; MAP_VB.h=MAP_VB.h*r; MAP_VB.x=cx-MAP_VB.w/2; MAP_VB.y=cy-MAP_VB.h/2;
  clampVB(); applyVB(); syncMapToggle(); _queueMapRender();
}
let _hintT=null;   // 모디파이어 없이 스크롤 시 안내 배지 잠깐 노출
function _mapZoomHint(){ const h=document.getElementById("mapScrollHint"); if(!h)return; h.classList.add("show"); clearTimeout(_hintT); _hintT=setTimeout(()=>h.classList.remove("show"),1100); }
// 휠 줌(Ctrl/⌘ + 스크롤, 커서 중심) + 마우스 드래그 팬. 모디파이어 없는 휠은 페이지 스크롤로 통과(스크롤 강탈 방지). 터치는 토글·버튼 사용.
function initMapInteractions(){
  const svg=document.getElementById("mapSvg"); if(!svg||svg._wired) return; svg._wired=true;
  const toVB=(cx,cy)=>{ const pt=svg.createSVGPoint(); pt.x=cx; pt.y=cy; const m=svg.getScreenCTM(); return m?pt.matrixTransform(m.inverse()):null; };
  svg.addEventListener("wheel",e=>{
    if(!(e.ctrlKey||e.metaKey)){ _mapZoomHint(); return; }   // 모디파이어 없으면 preventDefault 안 함 → 페이지 스크롤 유지
    e.preventDefault();   // Ctrl/⌘ 동반: 브라우저 페이지 줌 차단하고 지도 줌(트랙패드 핀치도 ctrlKey=true로 여기 유입)
    hideMapPop();
    const p=toVB(e.clientX,e.clientY); if(!p) return;
    const f=e.deltaY>0?1.18:1/1.18, nw=Math.min(1000,Math.max(10,MAP_VB.w*f)), r=nw/MAP_VB.w;
    MAP_VB.x=p.x-(p.x-MAP_VB.x)*r; MAP_VB.y=p.y-(p.y-MAP_VB.y)*r; MAP_VB.w=nw; MAP_VB.h=MAP_VB.h*r;
    clampVB(); applyVB(); syncMapToggle(); _queueMapRender();
  },{passive:false});
  let drag=null, _downPt=null, _moved=false;
  svg.addEventListener("pointerdown",e=>{ if(e.pointerType!=="mouse")return; drag={x:e.clientX,y:e.clientY}; _downPt={x:e.clientX,y:e.clientY}; _moved=false; svg.setPointerCapture(e.pointerId); svg.style.cursor="grabbing"; });
  svg.addEventListener("pointermove",e=>{ if(!drag)return;
    if(_downPt && (Math.abs(e.clientX-_downPt.x)+Math.abs(e.clientY-_downPt.y))>3){ if(!_moved) hideMapPop(); _moved=true; }   // 실제 팬 시작 → 팝업 닫고 클릭 취소
    const a=toVB(drag.x,drag.y), b=toVB(e.clientX,e.clientY); if(!a||!b)return;
    MAP_VB.x-=b.x-a.x; MAP_VB.y-=b.y-a.y; drag={x:e.clientX,y:e.clientY};
    clampVB(); applyVB();
  });
  // ── 터치 ────────────────────────────────────────────────────
  // 여기는 오래도록 마우스만 받았다(위 pointerdown이 mouse가 아니면 되돌아간다).
  // 아이패드에서는 권역 토글 말고는 지도를 넓혀 볼 방법이 없었다.
  // 한 손가락은 페이지 스크롤에 양보하고, 두 손가락일 때만 지도를 다룬다.
  const _d2=(t)=>Math.hypot(t[0].clientX-t[1].clientX, t[0].clientY-t[1].clientY);
  const _m2=(t)=>({x:(t[0].clientX+t[1].clientX)/2, y:(t[0].clientY+t[1].clientY)/2});
  let _pin=null;
  svg.style.touchAction="pan-y";
  svg.addEventListener("touchstart",e=>{
    if(e.touches.length!==2) return;
    _pin={ d:_d2(e.touches), c:_m2(e.touches), vb:{...MAP_VB} };
    hideMapPop();
  },{passive:true});
  svg.addEventListener("touchmove",e=>{
    if(!_pin || e.touches.length!==2) return;
    e.preventDefault();
    const a=toVB(_pin.c.x,_pin.c.y); if(!a) return;
    const nd=_d2(e.touches), c=_m2(e.touches);
    const f=_pin.d/Math.max(nd,1);
    const nw=Math.min(1000,Math.max(10,_pin.vb.w*f)), r=nw/_pin.vb.w;
    MAP_VB.x=a.x-(a.x-_pin.vb.x)*r; MAP_VB.y=a.y-(a.y-_pin.vb.y)*r;
    MAP_VB.w=nw; MAP_VB.h=_pin.vb.h*r;
    // 두 손가락 중심이 옮겨간 만큼 지도도 따라 옮긴다.
    const rect=svg.getBoundingClientRect();
    MAP_VB.x-=(c.x-_pin.c.x)/rect.width*MAP_VB.w;
    MAP_VB.y-=(c.y-_pin.c.y)/rect.height*MAP_VB.h;
    clampVB(); applyVB();
  },{passive:false});
  ["touchend","touchcancel"].forEach(ev=>svg.addEventListener(ev,e=>{
    if(_pin && e.touches.length<2){ _pin=null; syncMapToggle(); _queueMapRender(); }
  }));

  const endDrag=()=>{ drag=null; svg.style.cursor="grab"; };
  svg.addEventListener("pointerup",endDrag); svg.addEventListener("pointercancel",endDrag);
  // 클릭 → 원(data-mp) 이면 팝업, 빈 곳이면 닫기. 팬 드래그 뒤 클릭은 무시.
  svg.addEventListener("click",e=>{
    if(_moved){ _moved=false; return; }
    // 포인터 캡처가 click 타깃을 svg로 재지정하므로 e.target 대신 좌표로 실제 원을 찾음
    const el=document.elementFromPoint(e.clientX,e.clientY), t=el&&el.closest&&el.closest("[data-mp]");
    if(t){ const i=+t.getAttribute("data-mp"); if(MAP_POINTS[i]) showMapPop(i,e); }
    else hideMapPop();
  });
  svg.style.cursor="grab";
}
// ── 원 클릭 팝업 — 렌더 시 각 원에 data-mp 인덱스 부여, MAP_POINTS에 상세 보관. 클릭 시 지도 위 요약 팝업 ──
let MAP_POINTS=[];
// BP 사이트 — 본사(HQ)/제조(Plant) 구분(role·type 텍스트로 판정). 등급은 부가정보(mSIGNAL 체계: QD/Q).
function siteKind(s){ return /본사|hq|head/i.test(String((s&&(s.type||s.role))||""))?"hq":"plant"; }
// 등급 코드는 데이터 그대로 두고, 화면에 찍을 때만 우리말로 푼다(_shared/grade.html).
function bpGrade(c){ const g=String(c&&c.grade||"").trim().toUpperCase(); return g.startsWith("QD")?"QD":/^(Q|D|[ABC])/.test(g)?g[0]:""; }
function _newsForRegion(region,subName){   // 버블(지역·세부) → 최근 7일 관련 신호(최신순)
  const wk=Date.now()-7*24*3600*1000;
  return (allNews||[]).filter(n=>{
    const t=new Date(n.pubDate).getTime(); if(!isFinite(t)||t<wk) return false;
    if(detectRegion(n.title,n.desc)!==region) return false;
    const sub=detectSubRegion(region,n.title,n.desc);
    return subName?(sub&&sub.name===subName):!sub;   // 세부지역 지정이면 일치, '국가 기타'면 세부 미매칭분
  }).sort((a,b)=>new Date(b.pubDate)-new Date(a.pubDate));
}
function _popRow(n){   // 헤드라인 1행 — 원문 링크 있으면 클릭 진입
  const u=safeUrl(n.link), inner=`<div class="mp-h">${esc(n.title)}</div><div class="mp-s">${esc(n.source||"")}</div>`;
  return u?`<a class="mp-item" href="${u}" target="_blank" rel="noopener">${inner}</a>`:`<div class="mp-item">${inner}</div>`;
}
function _popHtml(p){
  const close=`<button class="mp-x" onclick="hideMapPop()" aria-label="닫기">×</button>`;
  if(p.kind==="bubble"){
    const items=_newsForRegion(p.region,p.sub).slice(0,4);
    const rows=items.map(_popRow).join("")||`<div class="mp-empty">표시할 최근 신호 없음</div>`;
    const rest=p.count-items.length;
    const more=`<button class="mp-more" onclick="mapGotoFeed()">${rest>0?"+"+rest+"건 · ":""}피드에서 전체 보기 →</button>`;
    return `${close}<div class="mp-head"><span class="mp-badge mp-b-news">뉴스</span>${esc(p.label)} · 최근 7일 ${p.count}건</div>${rows}${more}`;
  }
  if(p.kind==="quake"){
    const col=p.mag>=6?"var(--hi)":p.mag>=5?"var(--md)":"var(--accent)";
    const near=BP_SITES.length?nearestBPSites(p.lat,p.lon):[];
    let body;
    if(!BP_SITES.length) body=`<div class="mp-empty">BP 사업장 정보를 불러오지 못함 — 🔑 설정에서 붙여넣기</div>`;
    else if(!near.length) body=`<div class="mp-empty">인근 기준 반경 내 등록 BP 없음</div>`;
    else body=near.slice(0,4).map(b=>`<div class="mp-bp"><span class="mp-lvl mp-${b.lvl}">${b.lvl==="urgent"?"긴급":b.lvl==="monitor"?"주시":"참고"}</span>${esc(b.name)}${b.site?" · "+esc(b.site):""} <b>${b.d}km</b></div>`).join("");
    const link=safeUrl(p.url), src=link?`<a class="mp-more" href="${link}" target="_blank" rel="noopener">USGS 원문 →</a>`:"";
    const sub=BP_SITES.length?`인근 BP 사이트 ${near.length}곳 · 왜 크게 표기됐나`:"공급망 근접도 미판정";
    return `${close}<div class="mp-head" style="color:${col}"><span class="mp-badge" style="background:${col}">지진</span>M${esc(String(p.magLabel))} ${esc(p.place||"")}</div><div class="mp-sub">${sub}</div>${body}${src}`;
  }
  if(p.kind==="bp"){
    const ms=BP_MASTER.filter(b=>b.bp===p.name);   // 마스터에서 이 BP의 모든 소재군·등급·국가 연동
    const grade=p.grade||bpGrade(ms.find(b=>b.grade)||{});
    const country=(ms.find(b=>b.reg)||{}).reg||p.reg||"";
    const grps=[]; if(p.grp)grps.push(p.grp); ms.forEach(b=>{ if(b.grp&&!grps.includes(b.grp)) grps.push(b.grp); });
    const kindTxt=p.site_kind==="hq"?"본사":"제조";
    const gb=grade?`<span class="mp-grade" title="${esc(gradeTitle(grade))}">${esc(gradeLabel(grade))}</span>`:"";
    const line=[esc(p.site||""),kindTxt,(p.role&&!/본사|hq|plant|제조/i.test(p.role))?esc(p.role):"",country?esc(country):""].filter(Boolean).join(" · ");
    const grpLine=grps.length?`<div class="mp-sub">소재군 · ${esc(grps.join(", "))}</div>`:"";
    return `${close}<div class="mp-head"><span class="mp-badge mp-b-bp">BP</span>${esc(p.name)}${gb}</div><div class="mp-bp">${line}</div>${grpLine}`;
  }
  return close;
}
function showMapPop(i,e){
  const p=MAP_POINTS[i]; if(!p) return;
  const stage=document.querySelector(".map-stage"), pop=document.getElementById("mapPop");
  if(!stage||!pop) return;
  pop.innerHTML=_popHtml(p); pop.hidden=false;
  const r=stage.getBoundingClientRect(), x=e.clientX-r.left, y=e.clientY-r.top, pw=pop.offsetWidth, ph=pop.offsetHeight;
  let left=x+12, top=y-ph-10;
  if(top<4) top=y+14;                              // 위 공간 부족 → 점 아래로
  if(left+pw>r.width-4) left=x-pw-12;              // 우측 넘침 → 점 왼쪽
  left=Math.max(4,Math.min(left,r.width-pw-4));
  top=Math.max(4,Math.min(top,r.height-ph-4));
  pop.style.left=left+"px"; pop.style.top=top+"px";
}
function hideMapPop(){ const pop=document.getElementById("mapPop"); if(pop&&!pop.hidden){ pop.hidden=true; pop.innerHTML=""; } }
function mapGotoFeed(){ hideMapPop(); const el=document.getElementById("newsList")||document.getElementById("feedSection"); if(el) el.scrollIntoView({behavior:"smooth",block:"start"}); }
async function renderMap(){
  const svg=document.getElementById("mapSvg"); if(!svg) return;
  hideMapPop(); MAP_POINTS=[];   // 재렌더 시 팝업 닫고 포인트 재구성(인덱스 정합)
  initMapInteractions();   // 휠 줌·드래그 팬(1회 결선)
  const land=document.getElementById("mapLand"); if(land&&!land.getAttribute("d")) land.setAttribute("d",WORLD_PATH);   // 최초 1회만 대륙 path 주입
  const k=mapK();   // viewBox 폭 기반 연속 스케일 보정: 줌해도 점의 시각 크기 일정
  const gQ=document.getElementById("mapQuakes"), gB=document.getElementById("mapBubbles"),
        gM=document.getElementById("mapMonitor"), gBp=document.getElementById("mapBp"), hint=document.getElementById("mapHint");
  if(!gQ) return;

  // ── 뉴스 빈도 버블(최근 7일 · √스케일) — coarse 표시 전용. GLB 제외. detectRegion 인라인 재사용 ──
  // 한·중·일은 세부 지역(SUB_REGIONS)으로 분해 표기(이천·청주·우시·다롄·도호쿠 등). 미매칭분은 '국가 기타'로 국가 중심점에.
  const wk=Date.now()-7*24*3600*1000, cnt={};
  (allNews||[]).forEach(n=>{ const t=new Date(n.pubDate).getTime(); if(!isFinite(t)||t<wk) return;
    const r=detectRegion(n.title,n.desc); if(r==="GLB"||!REGION_CENTROIDS[r]) return;
    const sub=detectSubRegion(r,n.title,n.desc);
    const key=sub?r+"|"+sub.name:r;
    if(!cnt[key]) cnt[key]={c:0, ll:sub?sub.ll:REGION_CENTROIDS[r], label:sub?sub.name:(SUB_REGIONS_RX[r]?r+" 기타":r)};
    cnt[key].c++;
  });
  gB.innerHTML=Object.entries(cnt).map(([key,{c,ll,label}])=>{ const [la,lo]=ll;
    const rad=Math.min(Math.sqrt(c)*2.6+2.4,18)*k;   // √스케일(건수 비례 면적)·상한 18로 과대 버블 억제
    const bar=key.indexOf("|"), region=bar<0?key:key.slice(0,bar), sub=bar<0?null:key.slice(bar+1);
    const mi=MAP_POINTS.push({kind:"bubble",region,sub,label,count:c})-1;
    return `<circle data-mp="${mi}" style="cursor:pointer" cx="${mapPx(lo).toFixed(1)}" cy="${mapPy(la).toFixed(1)}" r="${rad.toFixed(2)}" fill="var(--map-bubble)" fill-opacity="0.26" stroke="var(--map-bubble)" stroke-opacity="0.5" stroke-width="${(0.6*k).toFixed(2)}"><title>${esc(label)} 뉴스 ${c}건 · 클릭</title></circle>`;
  }).join("");

  // ── BP 사이트 점 — ★렌더 전용. 절대 fetch/저장/외부 전송 금지. validSite() 가드 재사용. (네트워크 대기 없이 즉시) ──
  const bpLoaded=BP_SITES.length>0, dots=[];
  BP_SITES.forEach(c=>{ const g=bpGrade(c); (c.sites||[]).forEach(s=>{ if(!validSite(s)) return;
    const kind=siteKind(s), cx=mapPx(Number(s.lon)), cy=mapPy(Number(s.lat));
    const mi=MAP_POINTS.push({kind:"bp",name:c.name,site:s.n,role:s.role,site_kind:kind,grade:s.grade||g,grp:s.grp||c.grp,reg:c.reg,
      regionCode:s.regionCode||"",regionKo:s.regionKo||"",admin1:s.admin1||"",
      lat:Number(s.lat),lon:Number(s.lon)})-1;
    // **사건이 걸린 권역의 BP 는 4단계 색으로 선다** — 위의 `3 우리와 연관` 과
    // 같은 색이라, 숫자와 지도가 색만으로 이어진다.
    // 색만 쓰지 않는다: 걸린 것은 크기도 키우고 테두리도 두른다.
    // 색각 이상에서 색 하나로만 가르면 그 사람에게는 표식이 없는 것과 같다.
    const rgHit = (s.regionKo && RGN_HIT.has(s.regionKo)) ||
                  (s.admin1 && RGN_HIT.has(s.admin1));
    const fill = rgHit ? "var(--rk-3)" : "var(--map-bp)";
    const grow = rgHit ? 1.45 : 1;
    const ring = rgHit ? `stroke="var(--rk-4)" stroke-width="${(1.1*k).toFixed(2)}"`
                       : `stroke="var(--surface)" stroke-width="${(0.6*k).toFixed(2)}"`;
    const tt=esc(c.name)+(s.n?" · "+esc(s.n):"")+" · "+(kind==="hq"?"본사":"제조")
           +(rgHit?" · ⚠ 사건 권역":"");
    if(kind==="hq"){ const d=(3.3*k*grow);   // 본사 = 다이아몬드(rect 45° 회전)로 제조와 구분
      dots.push(`<rect data-mp="${mi}" style="cursor:pointer" x="${(cx-d).toFixed(1)}" y="${(cy-d).toFixed(1)}" width="${(d*2).toFixed(1)}" height="${(d*2).toFixed(1)}" transform="rotate(45 ${cx.toFixed(1)} ${cy.toFixed(1)})" fill="${fill}" ${ring}><title>${tt} · 클릭</title></rect>`);
    } else {   // 제조 = 원
      dots.push(`<circle data-mp="${mi}" style="cursor:pointer" cx="${cx.toFixed(1)}" cy="${cy.toFixed(1)}" r="${(2.6*k*grow).toFixed(2)}" fill="${fill}" fill-opacity="${rgHit?1:0.9}" ${ring}><title>${tt} · 클릭</title></circle>`);
    }
  }); });
  gBp.innerHTML=dots.join("");
  if(hint) hint.style.display=bpLoaded?"none":"";   // 불러오지 못함 시 힌트 노출
  try{ renderRegionBar(); applyRegionDim(); }catch(_){}

  // ── 지진(USGS 실좌표 · M4.5+ · 24h) — 기존 fetchUsgsQuakes() 10분 캐시 재사용 ──
  let quakes=[]; try{ quakes=await fetchUsgsQuakes(); }catch(_){ quakes=[]; }
  const now=Date.now();
  quakes=quakes.filter(q=>!q.time||(now-q.time)<=24*3600*1000);
  const rings=[];
  gQ.innerHTML=quakes.map(q=>{
    const mag=(typeof q.mag==="number")?q.mag:0;
    const base=mag>=6?7:mag>=5?5:3.5, col=mag>=6?"var(--hi)":mag>=5?"var(--md)":"var(--accent)";
    const cx=mapPx(q.lon).toFixed(1), cy=mapPy(q.lat).toFixed(1);
    // BP 로드 시 아시아박스 지진만 1차(50km) 모니터 반경 원(과밀 방지). 지리 반경이라 k 미적용.
    if(bpLoaded && q.lat>=18&&q.lat<=50&&q.lon>=100&&q.lon<=150){
      const km=quakeThresholds(q.lat,q.lon)[0], rr=km*(MAP_H/180)/111;
      rings.push(`<circle cx="${cx}" cy="${cy}" r="${rr.toFixed(2)}" fill="none" stroke="var(--accent)" stroke-opacity="0.55" stroke-width="${(0.5*k).toFixed(2)}" stroke-dasharray="${(1.2*k).toFixed(2)} ${(1.2*k).toFixed(2)}"/>`);
    }
    const t=q.title||("M"+(q.mag==null?"?":q.mag)+" "+(q.place||""));
    const mi=MAP_POINTS.push({kind:"quake",mag:mag,magLabel:(q.mag==null?"?":q.mag),place:q.place||"",url:q.url,lat:q.lat,lon:q.lon})-1;
    return `<circle class="map-q" data-mp="${mi}" cx="${cx}" cy="${cy}" r="${(base*k).toFixed(2)}" fill="${col}" fill-opacity="0.85" stroke="var(--surface)" stroke-width="${(0.6*k).toFixed(2)}"><title>${esc(t)} · 클릭</title></circle>`;   // 클릭 시 팝업(인근 BP·원문)
  }).join("");
  gM.innerHTML=rings.join("");
}


function renderScmIssues(issues){
  // 판정된 사건이 걸린 권역을 지도에 표시한다. 필터를 자동으로 걸지는 않는다 —
  // 어디까지 넓혀 볼지는 사람이 정한다.
  // 사건이 **난 권역**만 표시한다.
  // 예전에는 소재군이 걸린 BP 의 전 사업장 권역을 다 넣었다 — 회사 하나가 걸리면
  // 그 회사의 한국·중국·일본 사업장까지 붉어져, 사건 8건이 권역 19곳을 물들였다.
  // 색이 그만큼 흔해지면 색은 아무것도 가리키지 못한다.
  try{
    const rs=new Set();
    (arguments[0]||[]).forEach(iss=>{
      String(iss.regionText||iss.region||"").split(/[·,、\/]+/)
        .map(x=>x.trim()).filter(Boolean)
        .forEach(a=>{ const r=regionOfArea(a); if(r) rs.add(r); });
    });
    if(typeof markEventRegions==="function") markEventRegions([...rs]);
  }catch(_){}

  window._lastScmIssues=issues;
  const grid=document.getElementById("scmGrid");
  updateKpis();
  if(!issues||issues.length===0){
    const vslot=document.getElementById("verdictSlot");
    if(vslot) vslot.innerHTML=`<div class="scm-conclusion concl-ok"><div class="concl-line"><span class="concl-icon">●</span><span class="concl-label">오늘의 결론</span><span class="concl-verdict">점검 발송 후보 <b>없음</b> · 소재 공급망 직접 영향 없음</span></div><div class="concl-meta"><span class="concl-src">지역 정밀 분석</span></div></div>`;
    grid.innerHTML=`<div class="scm-empty">소재 공급망에 직접 영향을 주는 점검 대상이 없습니다.</div>`;
    return;
  }
  const order={high:0,medium:1,low:2};
  const review=issues.filter(i=>i.triage==="review")
    .sort((a,b)=>(order[a.level]??1)-(order[b.level]??1));
  const monitor=issues.filter(i=>i.triage!=="review")
    .sort((a,b)=>(order[a.level]??1)-(order[b.level]??1));

  // 카드 한 장이 300px 이었다. 8건이면 1852px — 화면의 58% 를 점검 대상이 먹고,
  // 그 아래 있던 재난 키워드까지는 아무도 내려오지 않았다.
  // 줄로 눕히고, 펴야 할 때만 편다. 제목·권역·소재군·BP 수는 접힌 채로도 보인다.
  const reviewCard=(iss,i)=>{
    const mats=(iss.affectedMaterials||[]).slice(0,3)
      .map(m=>`<span class="scm-mat">${esc(m)}</span>`).join("");
    const link=iss.newsLink?`<a href="${safeUrl(iss.newsLink)}" target="_blank" rel="noopener" class="scm-row-link">기사↗</a>`:"";
    const bpN=(iss._served&&iss._served[0]&&iss._served[0].bp)||"";
    return`<details class="scm-r lv-${esc(iss.level||"medium")}">
      <summary>
        <span class="scm-r-dot"></span>
        <span class="scm-r-title">${esc(iss.title)}</span>
        <span class="scm-r-reg">${esc(iss.region||"")}</span>
        <span class="scm-r-mats">${mats}</span>
        <span class="scm-r-bp">${esc(bpN)}</span>
      </summary>
      <div class="scm-r-body">
        ${iss.summary?`<div class="scm-review-sum">${esc(iss.summary)}</div>`:""}
        <div class="scm-review-meta">
          <span class="conf-badge conf-official">신뢰도 ${CONF_LABEL[iss.confidence]||"추정"}</span>
          ${iss.corroboration>1?`<span class="corr-badge">${iss.corroboration}개 매체</span>`:""}
          ${link}
        </div>
        ${bpListHtml(iss)}
        ${iss.why?`<div class="scm-review-why">${esc(iss.why)}</div>`:""}
        <div class="scm-r-act">
          <span>점검 발송 여부 — 엔지니어 판단${iss.action?` · ${esc(iss.action)}`:""}</span>
          <button class="btn sm pri" onclick="event.preventDefault();sendIssueToInspect(${i})" title="이 사건을 원장에 등록하고 점검 현황으로 넘깁니다">점검 현황으로 넘기기 →</button>
        </div>
      </div>
    </details>`;
  };
  const monitorRow=iss=>{
    const mats=(iss.affectedMaterials||[]).map(m=>`<span class="scm-mat">${esc(m)}</span>`).join("");
    const link=iss.newsLink?`<a href="${safeUrl(iss.newsLink)}" target="_blank" rel="noopener" class="scm-row-link">기사↗</a>`:"";
    return`<div class="scm-row level-${iss.level}">
      <div class="scm-level-dot ${iss.level}"></div>
      <span class="scm-tag-monitor">모니터링</span>
      <div class="scm-row-title" title="${esc(iss.summary||"")}">${esc(iss.title)}</div>
      <div class="scm-row-region">${esc(iss.region||"")}</div>
      <div class="scm-mats">${mats}</div>
      ${link}
    </div>`;
  };

  // 열면 결론(P1-4) + 신선도·출처 명시(리뷰 P0) — '지역 정밀 분석' 라벨 + 기준 시각·낡음
  const _at=issues.map(i=>i.analyzedAt&&new Date(i.analyzedAt).getTime()).filter(Boolean);
  const lastAt=_at.length?Math.max(..._at):0;
  const stale=lastAt&&(Date.now()-lastAt>24*60*60*1000);
  const tstr=lastAt?new Date(lastAt).toLocaleString("ko-KR",{month:"short",day:"numeric",hour:"2-digit",minute:"2-digit"}):"";
  const hi=review.filter(i=>i.level==="high").length;
  const top=review[0];   // 이미 심각도순 정렬
  let cls,icon,verdict;
  if(review.length){
    // **모양으로도 가른다.** 셋 다 원이면 색으로만 갈리고,
    // 색각 이상에서는 표식이 없는 것과 같다.
    cls=hi?"concl-hi":"concl-md"; icon=hi?"▲":"◆";
    verdict=`점검 발송 후보 <b>${review.length}건</b>${hi?` · 긴급 ${hi}건`:``} — 엔지니어 판단 필요`;
  }else{
    cls="concl-ok"; icon="●";
    verdict=`점검 발송 후보 <b>없음</b> · 모니터링 ${monitor.length}건 정상 관찰`;
  }
  const meta=`<span class="concl-src">지역 정밀 분석</span>${tstr?` · ${tstr} 기준`:``}${stale?` · <b class="concl-stale">분석 시점 경과(24시간 초과) — 재분석 권장</b>`:``}`;
  const conclusion=`<div class="scm-conclusion ${cls}">`
    +`<div class="concl-line"><span class="concl-icon">${icon}</span><span class="concl-label">오늘의 결론</span><span class="concl-verdict">${verdict}</span></div>`
    +(top?`<div class="concl-top">핵심 · <b>${esc(top.title)}</b><span class="concl-top-reg">${esc(top.region||"")}</span>${(top.affectedMaterials||[]).slice(0,3).map(esc).join(", ")}</div>`:"")
    +`<div class="concl-meta">${meta}</div>`
    +`</div>`;

  const sn=document.getElementById("scmN"); if(sn) sn.textContent=review.length?review.length+"건":"";
  const vslot=document.getElementById("verdictSlot"); if(vslot) vslot.innerHTML=conclusion;   // 결론은 최상단 슬롯(P1)
  grid.innerHTML =
    (review.length
      ? (window._reviewIssues=review, review.map(reviewCard).join(""))
      : `<div class="scm-ok">오늘 점검 발송 후보 없음 · 정상 모니터링 상태</div>`)
    + (monitor.length
      ? `<div class="scm-sub">모니터링 · 조회 전용 (발송 대상 아님) · ${monitor.length}건</div>` + monitor.map(monitorRow).join("")
      : "");
}

// ── 서버 자동판단(결정적 상황요약) — 로컬 정밀분석이 없을 때 '오늘의 결론'을 서버 스냅샷에서 읽어 표시 ──
let _srvConcl;   // undefined=미로드, null=없음, obj={c,meta}=로드됨
async function loadServerConclusion(){
  if(_srvConcl!==undefined) return _srvConcl;
  try{
    const idx=await fetch(MGSCS_DATA+"index.json",{cache:"no-cache"}).then(r=>r.ok?r.json():null);
    const date=idx&&idx.length?idx[idx.length-1]:null;
    if(!date){ _srvConcl=null; return null; }
    const snap=await fetch(`${MGSCS_DATA}${date}.json`,{cache:"no-cache"}).then(r=>r.ok?r.json():null);
    _srvConcl=(snap&&snap.conclusion)?{c:snap.conclusion, meta:{date:snap.date, generatedAt:snap.generatedAt, aiMode:snap.aiMode||snap.health?.aiMode}}:null;
  }catch{ _srvConcl=null; }
  return _srvConcl;
}
function serverConclusionBanner(c, m){
  const attention=c.level==="attention";
  const cls=attention?"concl-md":"concl-ok", icon=attention?"🟠":"🟢";
  const verdict=attention
    ? `조달권 고위험 <b>${c.sourcingHigh}건</b> · 공식경보 ${c.official} · 교차 확인 ${c.corroborated} — 상황 주시`
    : `조달권 특이 고위험 <b>없음</b> · 공식경보 ${c.official} · 교차 확인 ${c.corroborated} · 조달권 관련 ${c.sourcingHits}건 관찰`;
  const t=m.generatedAt?new Date(m.generatedAt):(m.date?new Date(m.date+"T00:00:00+09:00"):null);
  const tstr=t&&!isNaN(t)?t.toLocaleString("ko-KR",{month:"short",day:"numeric",hour:"2-digit",minute:"2-digit"}):(m.date||"");
  const staleS=t&&!isNaN(t)&&(Date.now()-t.getTime()>36*60*60*1000);   // 서버 일 1회 → 36h 초과면 낡음
  const kwOnly=(m.aiMode==="keyword-only");   // 서버 AI 검증 미가동 → 정직 표기(결함1)
  const meta=`<span class="concl-src concl-src-server">서버가 모아 둔 결론</span>${kwOnly?` · <b class="concl-stale">키워드 기반(AI 미검증)</b>`:``}${tstr?` · ${tstr} 기준`:``}${staleS?` · <b class="concl-stale">최신성 낮음 — 수집 지연</b>`:``}`;
  return `<div class="scm-conclusion ${cls}">`
    +`<div class="concl-line"><span class="concl-icon">${icon}</span><span class="concl-label">오늘의 결론</span><span class="concl-verdict">${verdict}</span></div>`
    +(c.top?`<div class="concl-top">핵심 · <b>${esc(c.top.title)}</b><span class="concl-top-reg">${esc(c.top.region||"")}</span>${esc(c.top.category||"")}</div>`:"")
    +`<div class="concl-meta">${meta}</div>`
    +`</div>`;
}
async function showServerConclusion(){
  if(window._lastScmIssues) return;   // 로컬 분석이 이미 있으면 서버 배너로 덮지 않음
  const sc=await loadServerConclusion();
  if(!sc || window._lastScmIssues) return;   // 서버 결론 없음, 또는 로딩 중 로컬 분석 생김 → 유지
  const vslot=document.getElementById("verdictSlot"); if(vslot) vslot.innerHTML=serverConclusionBanner(sc.c, sc.meta);   // 결론은 최상단 슬롯(P1). scmGrid는 정적 안내 유지
}


function detectCategory(title,desc){
  const combined=title+" "+(desc||"");
  for(const cat of CATS)
    if(cat.kw.some(k=>combined.includes(k)))return cat.id;
  const tl=combined.toLowerCase();
  const engMap=/*EM*/[[["tsunami","海啸","津波"],"쓰나미"],[["earthquake","quake","tremor","seismic","地震"],"지진"],[["volcano","volcanic eruption","eruption","lava","火山","噴火"],"화산"],[["typhoon","hurricane","cyclone","tropical storm","台风","台風"],"태풍"],[["flooding","flood","inundation","deluge","洪水","浸水"],"홍수"],[["drought","water shortage","water scarcity","water restriction","干旱","缺水","渇水"],"가뭄"],[["heatwave","heat wave","extreme heat","record heat","高温","热浪","猛暑","熱波"],"폭염"],[["cold wave","cold snap","blizzard","snowstorm","winter storm","deep freeze","寒潮","暴雪","寒波","大雪","arctic blast"],"한파"],[["wildfire","forest fire","bushfire","blaze","arson","火灾","火災","山火"],"화재"],[["explosion","blast","bomb attack","bombing","detonation","爆炸","爆発"],"폭발"],[["chemical leak","gas leak","toxic gas","ammonia leak","chlorine leak","building collapse","bridge collapse","structure collapse","radiation leak","nuclear plant","radioactive","industrial accident","泄漏","坍塌","漏れ","崩壊","放射能"],"산업 안전"],[["power outage","blackout","grid failure","power shortage","rolling blackout","grid collapse","power rationing","energy crisis","限电","停电","停電","計画停電"],"정전"],[["cyberattack","cyber attack","ransomware","data breach","it outage","system outage","网络攻击","勒索软件","サイバー攻撃","ランサムウェア"],"사이버"],[["pandemic","epidemic","outbreak","lockdown","quarantine","疫情","封城","感染症","パンデミック"],"감염병"],[["airstrike","air strike","bombardment","shelling","military offensive","空袭","空襲","战争","戦争"],"전쟁"],[["tariff","trade war","import duty","export ban","export control","export restriction","export curb","sanctions","embargo","entity list","rare earth ban","resource weaponization","semiconductor restriction","chip ban","chip export","关税","出口管制","制裁","禁运","関税","輸出規制","禁輸","trade sanction","export halt","export screening","trade restriction","trade retaliation","retaliatory tariff","strategic materials","whitelist","white list","mineral ban","gallium export","germanium export","antimony export"],"무역 규제"],[["blockade","naval blockade","strait of hormuz","hormuz","malacca strait","malacca","suez canal","suez","maritime blockade","shipping disruption","strait closure","封锁","海上封锁","封鎖","海上封鎖","ホルムズ","マラッカ","sea lane","shipping route"],"해상 봉쇄"],[["supply chain crisis","supply shock","supply disruption","supply crunch","raw material shortage","material shortage","sulfuric acid","hydrogen fluoride","photoresist","fluorinated polyimide","neon gas","krypton","xenon","helium shortage","wafer shortage","slurry supply","cmp supply","special gas","供应链","半导体材料","原材料短缺","供給危機","半導体材料","原材料不足","helium export","gallium","germanium","antimony","tungsten","palladium","nickel shortage","cobalt shortage","semiconductor material","chip material"],"공급망 위기"],[["currency crisis","exchange rate crash","forex","汇率","円安","円高"],"환율"],[["bankruptcy","insolvency","chapter 11","liquidation","files for bankruptcy","破产","倒闭","倒産","経営破綻"],"부도"],[["ukraine","kyiv","zelensky"],"우크라이나"],[["israel","gaza strip","gaza","hamas","palestine"],"이스라엘"],[["iran","tehran","persian gulf","iranian","伊朗","イラン"],"이란"],[["labor strike","general strike","walkout","port strike","dock strike","罢工","ストライキ"],"파업"]]/*EM*/;
  for(const[words,cat]of engMap)
    if(words.some(w=>tl.includes(w))){
      const found=CATS.find(c=>c.id===cat);
      return found?cat:null;
    }
  return null;
}

// 공식 경보 제목에서 심각도 추정 (GDACS 영향등급 색상 / USGS 규모)
// 제목 정규화 — 근접중복(같은 사건 다매체) 판정·교차검증 집계용
function normTitle(t){
  return String(t||"").toLowerCase()
    .replace(/^\s*\[[^\]]*\]\s*/,"")
    .replace(/\s+-\s+[^-]+$/,"")
    .replace(/[“”"'‘’`·•…|，。、！？!?,.\-–—:;()\[\]{}]/g," ")
    .replace(/[　\s]+/g," ").trim();
}
function titleKey(t){
  const toks=normTitle(t).split(" ").filter(w=>w.length>1);
  return toks.slice(0,8).sort().join(" ");
}
// 제목 토큰 집합 — 이벤트키 클러스터링 겹침 계산용 (선두 12개, 길이>1)
function titleTokens(t){ return new Set(normTitle(t).split(" ").filter(w=>w.length>1).slice(0,12)); }
// 지역 감지 — 지명 키워드만(인명 배제). ★ shared/pipeline.mjs REGION_KW와 동일(드리프트 가드 감시)
const REGION_KW=/*RK*/[["TW",["대만","타이완","타이베이","가오슝","신주","taiwan","taipei","tsmc","台湾","台灣"]],["JP",["일본","도쿄","오사카","규슈","홋카이도","도호쿠","후쿠시마","교토","나고야","고베","japan","tokyo","osaka","kyushu","fukushima","日本"]],["KR",["한국","서울","부산","대구","인천","울산","경기","포항","구미","여수","korea","seoul","busan","韓国"]],["CN",["중국","베이징","상하이","광저우","선전","우한","청두","톈진","china","beijing","shanghai","中国","中國"]],["UA",["우크라이나","키이우","키예프","ukraine","kyiv","kiev"]],["RU",["러시아","모스크바","russia","moscow","크렘린"]],["IL",["이스라엘","가자","텔아비브","팔레스타인","하마스","israel","gaza","hamas"]],["IR",["이란","테헤란","호르무즈","iran","tehran","hormuz"]],["ME",["중동","사우디","예멘","시리아","이라크","레바논","홍해","수에즈","middle east","saudi","yemen","syria","iraq","red sea","suez"]],["US",["미국","워싱턴","뉴욕","캘리포니아","텍사스","플로리다","united states","america","washington","california","texas"]],["EU",["유럽","독일","프랑스","영국","이탈리아","스페인","네덜란드","europe","germany","france","britain","london","berlin","paris"]]]/*RK*/;
function detectRegion(title,desc){
  const s=(title+" "+(desc||"")).toLowerCase();
  for(const [code,kws] of REGION_KW) if(kws.some(k=>s.includes(k.toLowerCase()))) return code;
  return "GLB";
}
// 이벤트키 교차검증 — (카테고리|지역) 버킷 내 토큰겹침≥threshold + 정확 근접중복(titleKey) 출처 합산. 결정적·비용 0
function eventCorroboration(items,threshold=4){
  const N=items.length, toks=items.map(it=>titleTokens(it.title)), keys=items.map(it=>titleKey(it.title));
  const buckets=new Map();
  for(let i=0;i<N;i++){ const b=(items[i].category||"")+"|"+detectRegion(items[i].title,items[i].desc); (buckets.get(b)||buckets.set(b,[]).get(b)).push(i); }
  const keySrc=new Map();
  for(let i=0;i<N;i++){ (keySrc.get(keys[i])||keySrc.set(keys[i],new Set()).get(keys[i])).add(items[i].source); }
  const inter=(a,b)=>{ let n=0; for(const x of a) if(b.has(x)) n++; return n; };
  const out=new Array(N);
  for(let i=0;i<N;i++){
    const sources=new Set(keySrc.get(keys[i]));
    for(const j of buckets.get((items[i].category||"")+"|"+detectRegion(items[i].title,items[i].desc))){
      if(j!==i && inter(toks[i],toks[j])>=threshold) sources.add(items[j].source);
    }
    out[i]=sources.size;
  }
  return out;
}
function officialSeverity(title){
  const t=String(title||"");
  if(/\bRed\b|red alert/i.test(t)) return "high";
  if(/\bOrange\b|orange alert/i.test(t)) return "medium";
  if(/\bGreen\b|green alert/i.test(t)) return "low";
  const m=t.match(/M[\s]?(\d+(?:\.\d+)?)/);   // USGS "M 6.1 - ..."
  if(m){ const mag=parseFloat(m[1]); return mag>=6?"high":mag>=5?"medium":"low"; }
  return "medium";
}
function formatTime(pub){
  if(!pub)return"";const d=new Date(pub);if(isNaN(d))return"";
  const diff=Math.floor((Date.now()-d)/60000);
  if(diff<1)return"방금 전";if(diff<60)return`${diff}분 전`;
  if(diff<1440)return`${Math.floor(diff/60)}시간 전`;return`${Math.floor(diff/1440)}일 전`;
}
function esc(s){return String(s).replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;");}
// RSS 제목에 섞인 HTML 엔티티(&apos; &#39; 등) 디코드 → 이후 esc로 안전 재이스케이프
function cleanTitle(s){let t=String(s||""),p;do{p=t;t=t.replace(/&#(\d+);/g,(_,n)=>String.fromCodePoint(+n)).replace(/&apos;/g,"'").replace(/&quot;/g,'"').replace(/&lt;/g,"<").replace(/&gt;/g,">").replace(/&amp;/g,"&");}while(t!==p&&t.indexOf("&")>=0);return t;}
// href 안전화: http(s) 스킴만 허용 (javascript:/data: 등 차단) 후 이스케이프
function safeUrl(u){u=String(u||"");return /^https?:\/\//i.test(u)?esc(u):"";}

// ════════════════════════════════════════
// ★ 메인 수집 함수
// ════════════════════════════════════════
async function startFetch(){
  if(isLoading)return;

  // ★ BugFix: 수집 중 카운트다운 정지
  clearInterval(cdInterval);

  // API 키 없으면 키워드 필터 모드로 자동 진행 (팝업 차단 방지)
  const hasKey = !!getApiKey();

  isLoading=true;
  setDot("loading");setStatus("신호 수집 중",false);setBtn(true);
  showLoadingState();renderSourceBar(null);

  try{
    const seen=new Set(),collected=[],srcResult={};
    let doneCount=0;

    await Promise.allSettled(SOURCES.map(async src=>{
      const txt=await fetchOneRSS(src.url);
      srcResult[src.name]=!!txt;
      if(txt){
        parseRSS(txt).forEach(it=>{
          // 검색(Google News) 제목의 " - 언론사" 접미사 제거 → 직접 피드 기사와 중복 방지
          let title=it.title;
          if(src.name.startsWith("검색")) title=title.replace(/\s+-\s+[^-]+$/,"").trim();
          // pubDate 없으면 수집 시각으로 스탬프 → 정렬/TTL 정확성 확보
          const pubDate=it.pubDate||new Date().toISOString();
          // 디둡 키에 source 포함(S5) — 교차출처 동일 헤드라인 보존(corroboration 과소집계 방지), 동일출처 중복만 제거
          const dk=title+" "+src.name;
          if(title&&!seen.has(dk)){seen.add(dk);collected.push({...it,title,pubDate,source:src.name,lang:detectLang(title)});}
        });
      }
      doneCount++;
      setProgress(Math.round(doneCount/SOURCES.length*50),`수집 중… (${doneCount}/${SOURCES.length})`);
      setStatus(`신호 수집 중 (${doneCount}/${SOURCES.length})`,false);
      renderSourceBar(srcResult);
    }));

    renderSourceBar(srcResult);
    refreshQuakePanel();   // 지진 근접 BP 갱신(독립·논블로킹)
    renderMap();           // 수집 후 지도 갱신(뉴스 버블·지진 최신화)
    if(collected.length===0){showError("수집된 신호가 없습니다. 네트워크 상태를 확인해 주십시오.");return;}

    const allKw=CATS.flatMap(c=>c.kw);
    const engKw=/*EW*/["earthquake","quake","flood","typhoon","hurricane","cyclone","wildfire","explosion","blast","bomb","war","airstrike","missile","tariff","trade war","export ban","export control","sanctions","embargo","blockade","hormuz","strait","supply chain","supply shock","semiconductor","photoresist","gallium","germanium","strike","volcano","eruption","tsunami","chemical leak","gas leak","toxic gas","building collapse","radiation leak","nuclear plant","industrial accident","drought","heatwave","power outage","blackout","grid failure","water shortage","cold wave","blizzard","winter storm","pandemic","epidemic","ransomware","cyberattack","cyber attack","bankruptcy","insolvency","fire","ukraine","kyiv","israel","gaza","hamas","walkout","terror","shooting","crash","disaster","casualties","killed","injured","dead","export halt","export restriction","export screening","trade retaliation","retaliatory tariff","entity list","whitelist","strategic materials","malacca","suez","naval blockade","shipping disruption","supply crisis","supply disruption","raw material","sulfuric acid","hydrogen fluoride","fluorinated polyimide","neon gas","krypton","xenon","helium","antimony","tungsten","palladium","rare earth","nickel shortage","cobalt shortage","semiconductor material","wafer shortage","chip ban","chip export","iran","tehran","persian gulf","port strike","dock strike","factory shutdown","plant closure","ammonia leak","chlorine leak","bridge collapse","radioactive"]/*EW*/;
    const zhKw=/*ZW*/["地震","洪水","台风","火灾","爆炸","战争","空袭","关税","封锁","制裁","出口管制","供应链","半导体","光刻胶","镓","锗","罢工","火山","海啸","干旱","缺水","停电","限电","疫情","网络攻击","勒索软件","破产","高温","寒潮","暴雪","汇率","出口禁令","出口限制","出口中断","出口审查","禁运","白名单","战略物资","硫酸","氟化氢","氟化聚酰亚胺","锑","钨","稀土","钯","半导体材料","芯片材料","晶圆","霍尔木兹","马六甲","供应危机","原材料","原材料短缺","伊朗","断水","海上封锁","贸易战","贸易制裁","贸易报复"]/*ZW*/;
    const jaKw=/*JW*/["地震","津波","台風","火災","爆発","洪水","噴火","戦争","関税","封鎖","制裁","輸出規制","供給危機","半導体","フォトレジスト","ストライキ","渇水","停電","パンデミック","感染症","サイバー攻撃","ランサムウェア","倒産","熱波","寒波","大雪","高温","円安","円高","テロ","災害","輸出禁止","輸出停止","輸出管理","輸出審査","禁輸","戦略物資","ホワイト国","包括許可","個別許可","硫酸","フッ化水素","フッ素ポリイミド","ガリウム","ゲルマニウム","アンチモン","タングステン","ネオン","クリプトン","キセノン","ヘリウム","レアアース","パラジウム","半導体材料","ウエハー","ホルムズ","マラッカ","原材料","原材料不足","イラン","断水","海上封鎖","貿易戦争","貿易報復"]/*JW*/;

    // ★ 비용 절감: 이미 DB에 있는 기사는 AI 재검증에서 제외 (id = link||title 기준)
    //   RSS에 계속 걸려 있는 기사를 매 사이클 LLM으로 재전송하던 낭비 제거
    const dbIds=new Set(loadNewsDB().map(arcKey));   // ★ arcKey로 통일 — 서버 병합분도 인식해 AI 재전송·중복 방지

    // ★ 공식 재난 경보(force) 소스 식별 — 사전검증된 재해이므로 키워드·AI 우회 후 직접 등록
    const FORCE_SRC={}; SOURCES.forEach(s=>{ if(s.force) FORCE_SRC[s.name]=s.cat||null; });
    const isForce=src=>Object.prototype.hasOwnProperty.call(FORCE_SRC,src);
    const officialNew=collected.filter(it=>isForce(it.source) && !dbIds.has(arcKey(it)));

    const kwFiltered=collected.filter(it=>{
      if(isForce(it.source)) return false;            // 공식 경보는 officialNew로 별도 처리
      if(dbIds.has(arcKey(it))) return false;
      if(EXCLUDE.test(it.title+" "+(it.desc||""))) return false;   // 비유·스포츠 오탐 제외
      const t=it.title,tl=t.toLowerCase(),d=(it.desc||"").toLowerCase();
      return allKw.some(k=>t.includes(k))
        ||engKw.some(k=>tl.includes(k))
        ||zhKw.some(k=>t.includes(k))
        ||jaKw.some(k=>t.includes(k))
        ||engKw.some(k=>d.includes(k));
    });

    const results=[];

    // 공식 경보 직접 등록 (GDACS 영향등급 / USGS 규모 → severity 매핑)
    officialNew.forEach(it=>{
      const cat = FORCE_SRC[it.source] || detectCategory(it.title,it.desc) || "기타";   // 서버(snapshot.mjs)와 폴백 통일 — 무관 경보가 공급망 위기로 오염되지 않게
      results.push({...it, titleKo:"", summary:(it.desc||"").slice(0,90), severity:officialSeverity(it.title), category:cat, id:it.link||it.title, aiVerified:false, official:true, confidence:"official"});
    });

    setProgress(55,`분석 중 · 신규 후보 ${kwFiltered.length}건 · 공식 경보 ${officialNew.length}건`);
    setStatus(`분석 중 · 후보 ${kwFiltered.length}건`,false);

    if(kwFiltered.length===0){
      if(results.length){ const m=mergeWithDB(results); allNews=m.merged; }
      else allNews = loadNewsDB();
      renderNews();
      setStatus(`수집 ${collected.length} · 공식 경보 ${officialNew.length} · 신규 후보 없음 (누적 ${allNews.length})`,false);
      setDot("ok");setUpdateTime();resetCountdown();return;
    }

    // ── 분류는 낱말로, 판정은 서버로 ────────────────────────
    // 예전에는 여기서 브라우저가 LLM 을 불러 재난 여부·번역·요약·심각도까지
    // 받아 왔다. 그러면 **판정이 두 곳에서 돈다** — 서버도 받은 신호를 다시
    // 사내 LLM 으로 판정하므로, 같은 기사에 두 개의 답이 생긴다.
    //
    // 브라우저가 하는 일은 **모으는 것까지**다(사내 서버는 뉴스에 못 닿는다).
    // 분류는 낱말로 결정적으로 하고, 뜻을 읽는 일은 전부 서버에 맡긴다.
    // 사내 LLM 은 사내망 안이라 서버가 직접 부른다 — 브라우저 키가 필요 없다.
    kwFiltered.forEach(it => {
      const cat = detectCategory(it.title, it.desc);
      if(!cat) return;
      results.push({
        ...it,
        titleKo: "", summary: (it.desc || "").slice(0, 180),
        severity: "medium",          // 등급은 서버가 게이트로 정한다
        category: cat,
        id: it.link || it.title,
        aiVerified: false,
      });
    });
    setProgress(90, `분류 ${results.length}건 — 서버가 판정합니다`);

    // 교차검증(corroboration): 이벤트키 클러스터링 — (카테고리|지역) 버킷 내 토큰겹침≥4 고유 출처 (결정적 = 비용 0)
    // 유니버스는 collected 전체(기존 DB 재확인분 포함) — 신규분만 세면 교차 확인 과소집계
    const corroItems=collected.map(it=>({title:it.title, desc:it.desc, source:it.source, category:detectCategory(it.title,it.desc)||"기타"}));
    const corroCounts=eventCorroboration(corroItems,4);
    const corroById={};
    collected.forEach((it,i)=>{ corroById[it.link||it.title]=corroCounts[i]; });
    // 근접중복 디둡: 같은 사건(titleKey)은 1건만 유지(신뢰도·교차검증 높은 쪽 우선)
    const confRank={official:0,consensus:1,estimate:2};
    const byKey={};
    results.forEach(r=>{
      r.corroboration = corroById[r.id] || 1;
      // ★ confidence 결정론화 — official(force 경보) / consensus(코드 집계 교차 확인≥2) / estimate. LLM 라벨은 신뢰도에 미반영
      if(!r.official) r.confidence = r.corroboration>=2 ? "consensus" : "estimate";
      const k=r.id&&r.official?r.id:titleKey(r.title);
      const cur=byKey[k];
      if(!cur || (confRank[r.confidence]??2)<(confRank[cur.confidence]??2)) byKey[k]=r;
    });
    const deduped=Object.values(byKey);

    const { merged, addedCount } = mergeWithDB(deduped);
    allNews = merged;

    setProgress(100,"완료");
    renderNews();

    // 모은 것을 서버로 올린다 — **여기가 공통 이력의 입구다.**
    // 정의만 해 두고 아무도 부르지 않아, 각자 브라우저에만 쌓이고 있었다.
    // 서버는 받은 것 중 재난·재해로 볼 것만 남기고(signal_filter),
    // 남긴 것을 판정해 등급을 매긴다(signal_review). 그 결과를 모두가 같이 본다.
    pushSignalsToServer(deduped).then(d=>{
      if(!d) return;
      // 서버가 재난·재해가 아니라고 뺀 것을 **화면에서도 덜어낸다.**
      // 먼저 그리고 나중에 덜어내는 순서인 이유는, 서버 왕복을 기다리면
      // 수집이 끝나도 화면이 한참 비어 있기 때문이다. 빠르게 보여 주고
      // 곧 바로잡는다. 선별 규칙은 서버 한 곳에만 둔다.
      const drop = new Set(d.droppedIds || []);
      if(drop.size){
        const before = allNews.length;
        allNews = allNews.filter(n => !drop.has(String(n.id || n.link || "")));
        if(allNews.length !== before){ saveNewsDB(allNews); renderNews(); }
      }
      // 올린 것을 서버가 곧바로 판정했을 수 있다. 결과를 다시 읽어 온다.
      setTimeout(() => { loadServerIssues(); loadJudged(); }, 1200);
      if(d.llmDown){
        showError(`판정이 멈췄습니다 — 사내 LLM 에 닿지 않습니다.\n` +
                  `신호는 서버에 쌓이고 있으니 잃지는 않습니다. 연결이 돌아오면 밀린 것부터 판정합니다.\n` +
                  `${d.llmWhy || ""}`.trim());
      }else if(d.filteredNew) setStatus(
        `서버 저장 ${d.dayTotal}건 · 재난과 무관해 뺀 것 ${d.filteredNew}건`, false);
    });

    let statusMsg=`신규 ${addedCount}건 · 누적 ${allNews.length}건 (수집 ${collected.length})`;
    // 판정 상태는 서버가 알려 준다(pushSignalsToServer 응답). 여기서 말하지 않는다.
    setStatus(statusMsg,false);
    setDot("ok");setUpdateTime();resetCountdown();

  }catch(e){
    showError(`오류: ${e.message}`);
  }finally{
    isLoading=false;setBtn(false);showProg(false);
    resetCountdown();   // ★ 모든 종료 경로에서 다음 자동 수집 타이머 보장 — 1회 실패로 무인 모니터링이 멈추지 않도록
  }
}

// ════════════════════════════════════════
// 일일 보고서는 **서버가 냅니다** — daily_report.py 가 06시에 한 장 발행하고,
// 대문이 그 숫자를 그대로 씁니다. 화면에서 따로 만들던 400여 줄을 지웁니다.
//
// 화면이 만들면 서버 보고서와 숫자가 갈립니다. 실제로 갈렸습니다 —
// 화면은 브라우저에 쌓인 것을, 서버는 자기가 판정한 것을 셌습니다.
// 무엇보다 후속 절차(점검 → 발송 → 회신)가 이미 갖춰진 지금,
// 화면에서 뽑는 HTML 보고서는 아무 데도 이어지지 않습니다.


// ════════════════════════════════════════
// 렌더링
// ════════════════════════════════════════
function openArticle(url){
  if(!url||url==="#") return;
  try {
    const w = window.open(url, "_blank");
    if(!w || w.closed || typeof w.closed==='undefined'){
      if(navigator.clipboard){
        navigator.clipboard.writeText(url).then(()=>{
          showToast("링크를 복사했습니다. 브라우저에 붙여넣으세요.");
        });
      } else {
        prompt("아래 URL을 복사하세요:", url);
      }
    }
  } catch(e){
    prompt("아래 URL을 복사하세요:", url);
  }
}

function showToast(msg){
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.style.opacity = '1';
  t.style.pointerEvents = 'auto';
  clearTimeout(t._timer);
  t._timer = setTimeout(()=>{ t.style.opacity='0'; t.style.pointerEvents='none'; }, 3000);
}

// ★ 뉴스 피드 다이어트(M3) — 최근 7일은 풀카드, 7일~90일(TTL)은 통계+컴팩트 리스트로 축약
const NEWS_RECENT_MS = 7 * 24 * 60 * 60 * 1000;
let staleOpen = false;   // "지난 신호" 접힘 상태 — 재렌더 간 유지

function renderNewsCard(item){
  const catObj=CATS.find(c=>c.id===item.category)||{};
  const clr=catObj.color||"#8794A8";
  const sevClr=item.severity==="high"?"var(--hi)":item.severity==="medium"?"var(--md)":"var(--lo)";
  const sevBg =item.severity==="high"?"var(--hi-bg)":item.severity==="medium"?"var(--md-bg)":"var(--lo-bg)";
  const sevLbl=item.severity==="high"?"🔴 긴급":item.severity==="medium"?"🟡 주의":"🟢 모니터링";
  // ★ BugFix: XSS 방지 — href를 data 속성으로 안전하게 전달
  const safeLink = safeUrl(item.link);
  // 신뢰도 뱃지 (공식/교차 확인/추정) — 공식 경보는 자동 official
  const conf=item.confidence||(item.official?"official":item.aiVerified?"consensus":"estimate");
  const CONF={official:["공식","conf-official"],consensus:["교차 확인","conf-consensus"],estimate:["추정","conf-estimate"]};
  const cf=CONF[conf]||CONF.estimate;
  const aiTag=`<span class="conf-badge ${cf[1]}">${cf[0]}</span>`
    + (item.corroboration>1?`<span class="corr-badge" title="${item.corroboration}개 매체 보도">×${item.corroboration} 매체</span>`:"");
  const langLabel=item.lang==="en"?"🇺🇸":item.lang==="zh"?"🇨🇳":item.lang==="ja"?"🇯🇵":"";
  const newBadge=item.isNew?`<span class="new-badge">신규</span>`:"";
  return`<div class="news-card${item.isNew?" is-new":""}" style="border-color:${clr};">
    <div class="news-icon" style="background:${clr}18;">${catObj.icon||"📰"}</div>
    <div class="news-body">
      <div class="news-tags">
        ${newBadge}
        <span class="tag" style="color:${clr};background:${clr}15;">${esc(item.category)}</span>
        <span class="tag" style="color:${sevClr};background:${sevBg};">${sevLbl}</span>
        ${aiTag}
        ${langLabel?`<span class="time-tag">${langLabel}</span>`:""}
        ${item.source?`<span class="src-tag">${esc(item.source)}</span>`:""}
        <span class="time-tag">${formatTime(item.pubDate)}</span>
      </div>
      <div class="news-title">${esc(cleanTitle(item.title))}</div>
      ${item.titleKo?`<div class="news-title-ko">${esc(cleanTitle(item.titleKo))}</div>`:""}
      ${item.summary?`<div class="news-summary" style="border-color:${clr};">
        <div class="sum-label">요약</div>${esc(item.summary)}</div>`:""}
    </div>
    <button class="link-btn" data-url="${safeLink}" onclick="openArticle(this.dataset.url)" title="${safeLink}">원문 보기 →</button>
  </div>`;
}

// 지난 신호(7일 초과) 구간 — 카테고리별 건수 칩(주간 키워드·카테고리 통계). arc-chip 스타일 관례 재사용
function newsStaleStats(items){
  const counts={};
  items.forEach(n=>{ const c=n.category||"기타"; counts[c]=(counts[c]||0)+1; });
  return Object.entries(counts).sort((a,b)=>b[1]-a[1]).map(([cat,cnt])=>{
    const catObj=CATS.find(c=>c.id===cat);
    return `<span class="arc-chip">${catObj?catObj.icon:"📰"} ${esc(cat)} <b>${cnt}</b></span>`;
  }).join("");
}

// 지난 신호 컴팩트 행 — 제목+링크+날짜+카테고리 칩만(요약·배지 없음, 가벼운 DOM). arc-row 스타일 관례 재사용
function newsStaleRow(item){
  const catObj=CATS.find(c=>c.id===item.category)||{};
  const clr=catObj.color||"#8794A8";
  const d=new Date(item.pubDate);
  const ds=isNaN(d)?"—":d.toLocaleDateString("ko-KR",{month:"2-digit",day:"2-digit"});
  const safeLink=safeUrl(item.link)||"#";
  return `<div class="arc-row">
    <span class="arc-date tnum">${ds}</span>
    <span class="arc-cat" style="background:${clr}14;color:${clr};border-color:${clr}40;">${catObj.icon||"📰"} ${esc(item.category||"기타")}</span>
    <a class="arc-link" href="${safeLink}" target="_blank" rel="noopener" title="${esc(cleanTitle(item.title))}">${esc(cleanTitle(item.titleKo||item.title))}</a>
    <span class="arc-src">${esc(item.source||"")}</span>
  </div>`;
}

function renderNews(){
  const list=document.getElementById("newsList"),state=document.getElementById("stateScreen");
  const staleWrap=document.getElementById("newsStaleWrap");
  const displayed=activeCategory==="전체"?allNews:allNews.filter(n=>n.category===activeCategory);
  renderTabs(); updateKpis();
  if(displayed.length===0){
    list.style.display="none";state.style.display="block";
    state.innerHTML=`<div class="state-icon">▣</div><div class="state-sub">수집된 재난·위기 신호가 없습니다.</div>`;
    if(staleWrap) staleWrap.innerHTML="";
    return;
  }
  state.style.display="none";list.style.display="grid";

  // 최근 7일 vs 7일 초과(~90일 TTL) 분리 — 오래된 신호는 풀카드 대신 통계+컴팩트 리스트
  const now=Date.now();
  const recent=[], stale=[];
  displayed.forEach(item=>{
    const t=new Date(item.pubDate).getTime();
    (!isNaN(t) && (now-t)>NEWS_RECENT_MS ? stale : recent).push(item);
  });

  list.innerHTML = recent.length
    ? recent.map(renderNewsCard).join("")
    : `<div class="arc-empty">최근 7일 이내 신호가 없습니다${stale.length?" · 아래 지난 신호를 확인해 주십시오":""}.</div>`;

  if(staleWrap){
    staleWrap.innerHTML = stale.length ? `<details id="newsStale" class="news-stale"${staleOpen?" open":""} ontoggle="staleOpen=this.open">
      <summary>지난 신호 ${stale.length}건 · 통계 보기</summary>
      <div class="news-stale-stats">${newsStaleStats(stale)}</div>
      <div class="arc-list">${stale.map(newsStaleRow).join("")}</div>
    </details>` : "";
  }
}

function renderTabs(){
  const counts={};
  allNews.forEach(n=>{counts[n.category]=(counts[n.category]||0)+1;});
  let html=`<button class="cat-tab" onclick="setCategory('전체')"
    style="${activeCategory==="전체"?"background:var(--navy);border-color:var(--navy);color:#fff;font-weight:700;":""}">
    전체${allNews.length?` (${allNews.length})`:""}</button>`;
  CATS.forEach(cat=>{
    if(!counts[cat.id])return;
    const act=activeCategory===cat.id;
    html+=`<button class="cat-tab" onclick="setCategory('${esc(cat.id)}')"
      style="${act?`background:${cat.color};border-color:${cat.color};color:#fff;font-weight:700;`:""}">
      ${cat.icon} ${esc(cat.id)} (${counts[cat.id]})</button>`;
  });
  document.getElementById("catTabs").innerHTML=html;
}

function renderSourceBar(results){
  window._lastSrcResult=results;
  const bar=document.getElementById("sourceBar");
  const total=SOURCES.length;
  let ok=0, err=0;
  if(results){ SOURCES.forEach(s=>{ const st=results[s.name]; if(st===true)ok++; else if(st===false)err++; }); }
  // 사이드바·KPI 카운트
  const setTxt=(id,v)=>{const e=document.getElementById(id);if(e)e.textContent=v;};
  setTxt("srcTotalCount",total);
  setTxt("srcOkCount",results?ok:"–");
  const navSrc=document.getElementById("navSrc"); if(navSrc) navSrc.textContent=results?`${ok}/${total}`:total;
  // 정상 소스 KPI 셀 상태색 (알림 아님 · 표시만 · 비용 0)
  const rate=total?ok/total:0;
  if(results){
    const cell=document.querySelectorAll(".kpi-strip .kpi"); const last=cell[cell.length-1];
    if(last){ last.classList.remove("hi","md","lo"); last.classList.add(rate>=0.7?"lo":rate>=0.5?"md":"hi"); }
  }
  // 패널 요약
  const sum=document.getElementById("srcSummary");
  if(sum){ sum.innerHTML = results
    ? `<span class="pill">전체 ${total}</span><span class="pill ok">정상 ${ok}</span><span class="pill err">실패 ${err}</span><span class="pill">정상률 ${Math.round(rate*100)}%</span>`
    : `<span class="pill">전체 ${total}</span><span class="pill">수집 대기</span>`; }
  // 패널 상세 그리드
  if(!bar) return;
  if(!results){ bar.innerHTML=SOURCES.map(s=>`<span class="src-badge">${esc(s.name)}</span>`).join(""); return; }
  bar.innerHTML=SOURCES.map(s=>{
    const st=results[s.name];
    return`<span class="src-badge ${st===undefined?"":st?"ok":"err"}">${st===undefined?"·":st?"✓":"✗"} ${esc(s.name)}</span>`;
  }).join("");
}

function setCategory(cat){activeCategory=cat;renderNews();}

// 키워드 모달
function openKwModal(){renderKwModal();document.getElementById("kwOverlay").classList.add("open");}
function closeKwModal(){document.getElementById("kwOverlay").classList.remove("open");}
function renderKwModal(){
  const COLORS=["#DC2626","#EA580C","#D97706","#16A34A","#059669","#2563EB","#7C3AED","#92400E","#B91C1C","#0369A1","#C2410C","#1D4ED8","#475569"];
  const body=document.getElementById("kwBody");
  body.innerHTML=`
    ${CATS.map((cat,ci)=>`
    <div class="kw-section">
      <div class="kw-section-head">
        <div class="kw-section-title" style="color:${cat.color};">
          <span style="font-size:18px;">${cat.icon}</span>
          <input value="${esc(cat.id)}" onchange="renameCat(${ci},this.value)"
            style="border:1px solid var(--line);border-radius:var(--r-sm);padding:2px 6px;font-size:12px;font-weight:700;color:${cat.color};width:90px;outline:none;font-family:var(--font-sans);">
          <input type="color" value="${cat.color}" onchange="recolorCat(${ci},this.value)"
            style="width:24px;height:24px;border:none;border-radius:var(--r-sm);cursor:pointer;padding:0;" title="색상 변경">
          <input value="${cat.icon}" onchange="reIconCat(${ci},this.value)"
            style="border:1px solid var(--line);border-radius:var(--r-sm);padding:2px 4px;font-size:16px;width:44px;text-align:center;outline:none;" title="아이콘 변경">
        </div>
        <button class="kw-del-cat" onclick="deleteCat(${ci})">카테고리 삭제</button>
      </div>
      <div class="kw-tags">
        ${cat.kw.map((k,ki)=>`
          <span class="kw-tag">${esc(k)}
            <button class="kw-tag-del" onclick="deleteKw(${ci},${ki})">×</button>
          </span>`).join("")}
      </div>
      <div class="kw-add-row">
        <input class="kw-input" id="kw_${ci}" placeholder="키워드 입력 후 Enter"
          onkeydown="if(event.key==='Enter')addKw(${ci})">
        <button class="kw-add-btn" onclick="addKw(${ci})">+ 추가</button>
      </div>
    </div>
    <hr class="kw-divider">`).join("")}
    <div class="add-cat-box">
      <h3>➕ 새 카테고리 추가</h3>
      <div class="add-cat-grid">
        <label>카테고리 이름 <input id="newCatName" placeholder="예: 핵 위협"></label>
        <label>아이콘(이모지) <input id="newCatIcon" placeholder="예: ☢️" maxlength="4"></label>
        <label>색상
          <div style="display:flex;align-items:center;gap:6px;">
            <input type="color" id="newCatColor" value="#DC2626" style="width:36px;height:32px;border:none;border-radius:var(--r-sm);cursor:pointer;padding:0;">
            <select id="newCatColorPreset" onchange="document.getElementById('newCatColor').value=this.value" style="flex:1;">
              ${COLORS.map(c=>`<option value="${c}" style="background:${c};color:#fff;">${c}</option>`).join("")}
            </select>
          </div>
        </label>
        <label>기본 키워드(쉼표 구분) <input id="newCatKw" placeholder="예: 핵, 핵무기, 핵실험"></label>
      </div>
      <button class="btn btn-dark" onclick="addCat()" style="margin-top:4px;">✓ 카테고리 추가</button>
    </div>`;
}

function renameCat(i,val){const v=val.trim();if(!v)return;CATS[i].id=v;saveCategories();}
function recolorCat(i,val){CATS[i].color=val;saveCategories();}
function reIconCat(i,val){const v=val.trim();if(!v)return;CATS[i].icon=v;saveCategories();}
function deleteCat(i){
  if(!confirm(`"${CATS[i].id}" 카테고리를 삭제하시겠습니까?`))return;
  CATS.splice(i,1);saveCategories();renderKwModal();
}
function addKw(ci){
  const input=document.getElementById(`kw_${ci}`);
  const val=input.value.trim();
  if(!val)return;
  if(CATS[ci].kw.includes(val)){alert("이미 등록된 키워드입니다.");return;}
  CATS[ci].kw.push(val);input.value="";saveCategories();renderKwModal();
}
function deleteKw(ci,ki){
  if(CATS[ci].kw.length<=1){alert("키워드는 최소 1개가 필요합니다.");return;}
  CATS[ci].kw.splice(ki,1);saveCategories();renderKwModal();
}
function addCat(){
  const name=document.getElementById("newCatName").value.trim();
  const icon=document.getElementById("newCatIcon").value.trim()||"📌";
  const color=document.getElementById("newCatColor").value;
  const kwRaw=document.getElementById("newCatKw").value;
  const kw=kwRaw.split(",").map(s=>s.trim()).filter(Boolean);
  if(!name){alert("카테고리 이름을 입력해 주세요.");return;}
  if(CATS.find(c=>c.id===name)){alert("이미 존재하는 카테고리 이름입니다.");return;}
  if(kw.length===0){alert("키워드를 최소 1개 입력해 주세요.");return;}
  CATS.push({id:name,icon,color,kw});
  saveCategories();
  document.getElementById("newCatName").value="";
  document.getElementById("newCatIcon").value="";
  document.getElementById("newCatKw").value="";
  renderKwModal();
  alert(`"${name}" 카테고리가 추가되었습니다.`);
}

const DOT_LABEL={loading:"수집 중",error:"오류",ok:"정상"};
function setDot(s){
  const d=document.getElementById("liveDot");
  if(d) d.className="live-dot"+(s==="loading"?" loading":s==="error"?" error":"");
  const l=document.getElementById("liveLabel"); if(l) l.textContent=DOT_LABEL[s]||"대기 중";
}
function setStatus(msg,isErr){const el=document.getElementById("statusText");if(el){el.textContent=msg;el.className="status-text"+(isErr?" err":"");}}
function setBtn(dis){const b=document.getElementById("refreshBtn");if(b){b.disabled=dis;b.textContent=dis?"수집 중…":"수집 실행";}}
function setUpdateTime(){const e=document.getElementById("updateTime");if(e)e.textContent=new Date().toLocaleTimeString("ko-KR")+" 수집 완료";}
function showProg(on){const w=document.getElementById("progWrap");if(w)w.hidden=!on;}
function setProgress(pct,label){
  pct=Math.max(0,Math.min(100,Math.round(pct)));
  const f=document.getElementById("progressFill"); if(f)f.style.width=pct+"%";
  const ring=document.getElementById("progRing"); if(ring)ring.style.setProperty("--p",pct);
  const pp=document.getElementById("progPct"); if(pp)pp.textContent=pct+"%";
  if(label){ ["progressLabel","progressLabelInline"].forEach(id=>{const l=document.getElementById(id);if(l)l.textContent=label;}); }
}
function showLoadingState(){
  const s=document.getElementById("stateScreen");
  const list=document.getElementById("newsList");
  showProg(true);
  if(allNews.length){ // 이미 데이터가 있으면 피드를 가린 채 진행률만 상단에 노출
    if(s)s.style.display="none"; if(list)list.style.display="grid"; setProgress(0,"준비 중"); return;
  }
  if(s)s.style.display="block";
  if(list)list.style.display="none";
  const icon=s&&s.querySelector(".state-icon");
  const sub=s&&s.querySelector(".state-sub");
  if(icon){icon.className="state-icon spin";icon.textContent="◴";}
  if(sub){sub.textContent="재난·위기 신호를 수집하고 있습니다.";}
  if(s&&!s.querySelector(".progress-wrap")){
    s.insertAdjacentHTML("beforeend",`<div class="progress-wrap">
      <div class="progress-label" id="progressLabelInline">준비 중</div>
      <div class="progress-bar"><div class="progress-fill" id="progressFill" style="width:0%"></div></div>
    </div>`);
  } else {
    setProgress(0,"준비 중");
  }
}
function showError(msg){
  setDot("error");setStatus("수집 실패 · "+String(msg).slice(0,60),true);
  // ★ 이미 누적 피드가 있으면 화면을 덮지 않고 상단 배너로만 알림 — 일시적 실패가 정상 데이터를 가리지 않도록
  if(allNews.length){ const list=document.getElementById("newsList"); const s=document.getElementById("stateScreen"); if(list)list.style.display="grid"; if(s)s.style.display="none"; isLoading=false;setBtn(false); return; }
  document.getElementById("stateScreen").style.display="block";
  document.getElementById("newsList").style.display="none";
  document.getElementById("stateScreen").innerHTML=`
    <div class="state-icon">⚠️</div>
    <div class="state-title">수집 실패</div>
    <div class="state-sub">${esc(msg)}</div>
    <div class="error-guide">
      <h4>확인 사항</h4>
      <p>1. 분석 엔진 설정에서 API 키를 입력하세요.<br/>2. 네트워크 연결 상태를 확인하세요.<br/>3. 잠시 후 다시 시도하세요.</p>
    </div>
    <button class="retry-btn" onclick="startFetch()">다시 시도</button>`;
  isLoading=false;setBtn(false);
}
let nextFetchAt=0;
function resetCountdown(){
  // ★ 벽시계 기준 — 백그라운드 탭 타이머 스로틀링으로 '30분 주기'가 시간 단위로 늘어지는 문제 방지
  nextFetchAt=Date.now()+1800*1000;
  clearInterval(cdInterval);
  cdInterval=setInterval(()=>{
    const remain=Math.max(0,Math.round((nextFetchAt-Date.now())/1000));
    const m=Math.floor(remain/60),s=String(remain%60).padStart(2,"0");
    const el=document.getElementById("updateTime");
    if(el){const base=el.textContent.split("·")[0].trim();el.textContent=`${base} · 다음 ${m}:${s}`;}
    if(remain<=0 && !isLoading) startFetch();
  },1000);
}
// 백그라운드에서 밀린 사이클을 탭 복귀 즉시 만회
document.addEventListener("visibilitychange",()=>{
  if(!document.hidden && nextFetchAt && Date.now()>=nextFetchAt && !isLoading) startFetch();
});

// ════════════════════════════════════════
// 초기화
// ════════════════════════════════════════
try {
  applyTheme();     // 다크/라이트 테마 적용(localStorage 또는 OS 자동)
  updateApiKeyBtn();
  loadBpMaster();   // BP 기준 정보(대외비, 로컬 전용) 로드 — 영향 BP 표시용
  loadBpSites();    // BP 사업장 좌표(대외비) 로드 — 지진 근접 매칭용
  bootstrapBpData();    // localStorage 없으면 커밋된 기본값(data/bp/*.json) seed — 비동기·논블로킹
  pullServerSignals();  // 서버에 쌓인 공통 이력을 끌어온다 — 아래 참고
  refreshQuakePanel();  // 지진 근접 BP 패널(비동기·논블로킹)
  renderMap();          // 재난·BP 지도 최초 렌더(대륙 path 주입 + 지진·버블·BP)
  setView("dashboard");
  // 모바일: 메뉴 항목 선택 시 드로어 닫기 });
  renderSourceBar(null);
  updateKpis();
  loadTrend();   // 추세 스파크라인 (비동기·논블로킹)

  const savedScm = loadScmIssues();
  if(savedScm.length > 0) renderScmIssues(savedScm);
  else showServerConclusion();   // 로컬 정밀분석이 없으면 서버 자동판단(상황요약)을 배너에 표시(비동기·논블로킹)

  const savedNews = loadNewsDB();
  if(savedNews.length > 0){
    savedNews.forEach(n => { delete n.isNew; });
    allNews = savedNews;
    renderMap();   // 저장 신호 복원 후 지도 재렌더 — 뉴스 빈도 버블이 첫 화면부터 표시되도록(초기 renderMap은 allNews 복원 전)
    updateDbBar(savedNews);
    renderNews();
    setStatus(`저장된 신호 ${savedNews.length}건 표시 중 · 수집 실행 시 갱신`, false);
    setDot("ok");
    document.getElementById("stateScreen").style.display = "none";
    document.getElementById("newsList").style.display = "grid";
  } else {
    updateDbBar([]);
  }

  // 서버가 판정한 사건을 먼저 그린다 — 수집을 기다리지 않는다.
  // 화면을 여는 사람이 가장 먼저 알아야 하는 것은 "지금 걸린 것이 있나" 다.
  loadServerIssues();
  loadJudged();

  // 이력도 서버에서 끌어온다. 누가 어느 자리에서 열든 같은 것을 보게 하려면
  // 사람이 단추를 눌러야 맞춰지는 구조여서는 안 된다.
  syncFromServer();
  setInterval(syncFromServer, 3 * 60 * 1000);

  startFetch();
} catch(initErr) {
  console.error("초기화 오류:", initErr);
  const st=document.getElementById("statusText"); if(st) st.textContent = "초기화 오류: " + initErr.message;
}

// ══ 권역 축 ══════════════════════════════════════════════════════
// 사건이 나면 근방을 어차피 다 조사한다. 그래서 판정은 도/현으로 하되,
// 화면에서는 권역(규슈·오키나와, 영남권 …) 단위로 넓혀 볼 수 있어야 한다.
// 여기서 하는 일은 두 가지다.
//   1) 권역별 BP·site 수를 세어 칩으로 보여준다
//   2) 칩을 누르면 그 권역만 남기고 나머지를 흐리게 한다
// 사건이 선택되면 관련 권역을 붉게 표시한다 — 눌러서 넓혀 보라는 신호다.
// 선언은 위(BP_SITES 옆)로 올렸다 — renderMap 이 이보다 먼저 읽는다.

function regionStats() {
  const by = new Map();
  (BP_SITES || []).forEach((c) => (c.sites || []).forEach((st) => {
    if (!validSite(st)) return;
    const code = st.regionCode || "";
    const name = st.regionKo || (code ? code : "권역 축 미사용(US·EU 등)");
    if (!by.has(name)) by.set(name, { name, code, sites: 0, bps: new Set() });
    const e = by.get(name);
    e.sites += 1;
    e.bps.add(c.name);
  }));
  return [...by.values()].sort((a, b) => b.sites - a.sites);
}

function renderRegionBar() {
  const bar = document.getElementById("rgnBar");
  const note = document.getElementById("rgnNote");
  if (!bar) return;
  const stats = regionStats();
  if (!stats.length) { bar.hidden = true; if (note) note.hidden = true; return; }
  bar.hidden = false;
  const total = stats.reduce((n, e) => n + e.sites, 0);
  // **상위 8개만 늘 보인다.** 20개를 펼쳐 두면 두 줄을 먹는데, 꼬리 쪽은
  // 사업장이 한두 곳뿐이라 눌릴 일이 거의 없다. 사건이 걸린 권역(hit)은
  // 순위와 무관하게 항상 앞에 세운다 — 지금 봐야 할 것이 뒤에 숨으면 안 된다.
  const chip = (e) => {
    const on = RGN_SEL.has(e.name) ? " on" : "";
    const hit = RGN_HIT.has(e.name) ? " hit" : "";
    return `<span class="rgn-chip${on}${hit}" data-r="${esc(e.name)}" title="${esc(e.name)} · BP ${e.bps.size}사">`
         + `${esc(e.name)} <span class="n">${e.sites}</span></span>`;
  };
  const TOP = 8;
  const pinned = stats.filter(e => RGN_HIT.has(e.name) || RGN_SEL.has(e.name));
  const rest = stats.filter(e => !RGN_HIT.has(e.name) && !RGN_SEL.has(e.name));
  const head = [...pinned, ...rest.slice(0, Math.max(0, TOP - pinned.length))];
  const tail = rest.slice(Math.max(0, TOP - pinned.length));
  bar.innerHTML =
    `<span class="rgn-chip${RGN_SEL.size ? "" : " on"}" data-r="">전체 <span class="n">${total}</span></span>` +
    head.map(chip).join("") +
    (tail.length
      ? `<details class="rgn-more"><summary>+${tail.length}</summary>`
        + `<div class="rgn-more-b">${tail.map(chip).join("")}</div></details>`
      : "");
  bar.querySelectorAll(".rgn-chip").forEach((el) => {
    el.onclick = () => {
      const r = el.dataset.r;
      if (!r) RGN_SEL.clear();
      else if (RGN_SEL.has(r)) RGN_SEL.delete(r);
      else RGN_SEL.add(r);
      renderRegionBar();
      applyRegionDim();
    };
  });
  if (note) {
    note.hidden = false;
    note.textContent = RGN_HIT.size
      ? `최근 사건 권역: ${[...RGN_HIT].join(" · ")} — 붉은 칩을 눌러 그 권역만 볼 수 있습니다.`
      : "권역을 누르면 그 권역만 남습니다 · 판정 축은 도/현, 권역은 근방을 넓혀 보는 축";
  }
}

function applyRegionDim() {
  // 점을 다시 그리지 않고 투명도만 바꾼다. 372개를 매번 재렌더하면 느리다.
  document.querySelectorAll("#mapBp [data-mp]").forEach((el) => {
    const mp = MAP_POINTS[+el.dataset.mp];
    if (!mp || mp.kind !== "bp") return;
    const name = mp.regionKo || (mp.regionCode || "권역 축 미사용(US·EU 등)");
    const show = !RGN_SEL.size || RGN_SEL.has(name);
    el.classList.toggle("dim", !show);
  });
}

function regionOfArea(a){
  if(!window._A2R || !window._A2R.size){
    const m=new Map();
    (BP_SITES||[]).forEach(c=>(c.sites||[]).forEach(s=>{
      if(!s.regionKo) return;
      if(s.admin1) m.set(String(s.admin1).trim(), s.regionKo);
      m.set(String(s.regionKo).trim(), s.regionKo);
    }));
    window._A2R=m;
  }
  return window._A2R.get(String(a||"").trim()) || "";
}

function markEventRegions(names) {
  // 판정이 낸 도/현·권역을 화면에 표시한다. 자동으로 필터를 걸지는 않는다 —
  // 무엇을 볼지는 사람이 정한다.
  RGN_HIT = new Set((names || []).filter(Boolean));
  renderRegionBar();
}

</script>
</body>
</html>
