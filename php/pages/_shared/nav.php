<style>
  body{padding-left:var(--rail-w);}
  body.rail-off{padding-left:var(--rail-w-min);}
  @media(max-width:900px){ body,body.rail-off{padding-left:0;} }
</style>
<aside class="rail">
  <a href="<?= url('/home') ?>" class="rail-wm">
    <img class="rail-ci"
         src="<?= url('/static/260611_junghoon/mgscs/skhynix-logo.png') ?>"
         alt="SK hynix">
    <b>mGSCS</b>
    <span class="full">material Global SCM Control System</span>
    <span>소재 공급망 리스크 모니터링</span></a>
  <?php if ($data_profile == 'mock'): ?>
  <div class="rail-mock" title="<?= htmlspecialchars($data_profile_meta['swap'] ?? '') ?>">모사 데이터 · BP <?= htmlspecialchars($data_profile_meta['bps'] ?? '') ?> · 사업장 <?= htmlspecialchars($data_profile_meta['sites'] ?? '') ?><br><span>구조는 실제, 값은 생성</span></div>
  <?php endif; ?>

  <nav class="rail-nav">
    <a href="<?= url('/home') ?>" class="<?= $here == 'home' ? 'on' : '' ?>">
      <b>홈</b><i>홈</i></a>
    <a href="<?= url('/dashboard') ?>" class="<?= $here == 'dashboard' ? 'on' : '' ?>">
      <b>Risk 현황</b><i>Risk</i></a>
    <a href="<?= url('/inspect') ?>" class="<?= $here == 'inspect' ? 'on' : '' ?>">
      <b>점검 현황</b><i>점검</i></a>

    <a href="<?= url('/system') ?>" class="<?= $here == 'system' ? 'on' : '' ?>">
      <b>판정 기준</b><i>기준</i></a>

    <div class="rail-grp">관리자 메뉴<?php if (!$is_admin): ?><span class="lk">잠김</span><?php endif; ?></div>

    <a href="<?= url('/bp') ?>" class="<?= $here == 'bp' ? 'on' : '' ?>">
      <b>BP 기준 정보</b><i>BP</i></a>

    <a href="<?= url('/revision') ?>" class="<?= $here == 'revision' ? 'on' : '' ?>">
      <b>등록·수정</b><i>등록</i></a>

    <a href="<?= url('/review') ?>" class="<?= $here == 'review' ? 'on' : '' ?>">
      <b>감지 검토</b><i>검토</i></a>

    <a href="<?= url('/settings') ?>" class="<?= $here == 'settings' ? 'on' : '' ?>">
      <b>시스템 설정</b><i>설정</i></a>
  </nav>

  <button class="rail-fold" type="button" onclick="mgscsToggleRail()"
          title="메뉴 접기 · 펴기" aria-label="메뉴 접기"></button>

  <div class="rail-foot">
    <span class="rail-live" id="railLive" title="수집이 돌고 있나"><i class="live-dot" id="liveDot"></i><span id="liveLabel">확인 중</span></span>
    <?php if ($logged_in): ?>
      <span class="rail-who"><?= htmlspecialchars($actor_now ?? '') ?></span>
      <a href="<?= url('/logout') ?>" class="rail-act">나가기</a>
    <?php else: ?>
      <a href="<?= url('/login?next=' . urlencode($_SERVER['REQUEST_URI'])) ?>" class="rail-act rail-in">관리자 로그인</a>
    <?php endif; ?>
    <button class="rail-theme" type="button" onclick="mgscsToggleTheme()"
            title="라이트/다크 전환">◐</button>
    <span class="rail-own">기획·개발<br>
      SK hynix 소재전략 이정훈 TL</span>
  </div>
</aside>
<script>
// 신호등 — 초록 이상 없음 · 파랑 볼 것 남음 · 빨강 파이프 끊김.
// 색만으로 가르지 않는다. 글자가 같이 간다.
(function(){
  var dot = document.getElementById("liveDot");
  var lab = document.getElementById("liveLabel");
  var box = document.getElementById("railLive");
  if(!dot || !lab) return;
  function set(cls, txt, why){
    dot.className = "live-dot " + cls;
    lab.textContent = txt;
    if(box) box.title = why || "";
  }
  function read(){
    Promise.all([
      fetch("<?= url('/api/signals/funnel') ?>").then(function(r){return r.ok?r.json():null;}).catch(function(){return null;}),
      fetch("<?= url('/api/alerts') ?>").then(function(r){return r.ok?r.json():null;}).catch(function(){return null;})
    ]).then(function(a){
      var f = a[0] || {}, al = a[1] || {};
      var pend = (al.pending || al.items || []).length || 0;
      if(!f.ok){ set("error", "서버 못 읽음", "감지 상태를 못 읽었습니다 [9010]"); return; }
      if(!f.collected){ set("error", "수집 끊김", "오늘 올라온 신호가 없습니다 [1001]"); return; }
      if(f.waiting || pend){
        set("loading", "확인 필요",
            "점검 대상 " + (f.waiting||0) + "건" + (pend ? " · 미확인 " + pend + "건" : ""));
        return;
      }
      set("ok", "정상", "수집 " + f.collected + "건 · 점검 대상 없음");
    });
  }
  read();
  setInterval(read, 3 * 60 * 1000);
})();
</script>
