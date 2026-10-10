# mbridgephp — mGSCS PHP 배포판

Flask 프로젝트 `mgscs/`(servera/mgscs)를 **PHP 8.4** 에서 돌아가게 다시 구조화한 배포판이다.
진입 파일은 `index.php` 하나다. 모든 URL 이 여기로 와서 라우팅표를 거친다.

```
mbridgephp/
├── index.php        진입 하나 — 부트스트랩 · 게이트 · 라우팅표(화면 13 · API 60) · 처리기 호출
├── config.php       배포자가 손대는 유일한 파일 (데이터 폴더 · 자격 파일 · 환경변수 이름)
├── .htaccess        모든 요청 → index.php · lib/ routes/ pages/ data/ 직접 접근 차단
├── VERSION
├── lib/             도메인 모듈 (정적 클래스 21개: Store · Auth · Rebuild · Collector · SignalReview …)
├── routes/          처리기 — pages.php(Pages: 화면) · api.php(Api: /api/*)
├── pages/           화면 템플릿 9 + hub.php(허브) + _shared/(theme · nav · foot · grade)
├── static/          정적 파일 (폰트 · 로고 · 지도 경계)
├── data/            데이터 — 웹에서 열리지 않는다. baseline/ survey/ 는 기본 명부, 나머지는 실행 중 생긴다
└── cron/collect.php 수집 사이클 (CLI 전용 — 웹 스케줄러 대신 cron 이 부른다)
```

## 1. 올리기

폴더를 통째로 웹서버에 둔다. **어디에 두어도 된다** — DocumentRoot 바로 아래(`/`)든
하위 폴더(`/mbridgephp/`)든 `index.php` 위치에서 URL 접두어를 스스로 계산한다.

```
DocumentRoot/mbridgephp/index.php   →  http://서버/mbridgephp/
DocumentRoot/index.php              →  http://서버/
```

필요한 것 셋:

| 항목 | 확인 |
|---|---|
| PHP 8.1 이상 (8.4 권장) · 확장 json · mbstring · openssl · curl · session | 허브 화면 "서버 점검" 표 |
| Apache `mod_rewrite` 켜짐 + 이 폴더에 `AllowOverride All` | 허브의 "API 연결" 칸이 **정상** |
| `data/` 폴더에 웹서버 계정 쓰기 권한 | `chmod -R u+rwX data` (소유자 = 웹서버 계정) |

Apache 예:

```apache
<Directory "/var/www/html/mbridgephp">
    AllowOverride All
    Require all granted
</Directory>
```

Nginx 는 .htaccess 를 읽지 않으므로 다음 둘을 직접 적는다:

```nginx
location /mbridgephp/ {
    try_files $uri /mbridgephp/index.php$is_args$args;
}
location ~ ^/mbridgephp/(lib|routes|pages|data|cron)/ { return 403; }
```

## 2. 첫 접속

`http://서버/mbridgephp/` → **허브**. 화면·API 목록과 서버 점검 결과가 한 장에 나온다.
관리자 비밀번호는 아직 없다 — **로그인** 을 누르면 최초 1회 설정 화면이 열린다(사번 + 8자 이상).
정하고 나면 평범한 로그인 화면이 된다. 자격 파일은 `data/auth.json`(600).

## 3. 데이터

`data/baseline/`(기준 명부) · `data/survey/`(사외 조사) 는 원본 그대로 들어 있다. 첫 요청 때
`data/build/bp_resolved.json` 을 자동 재생성한다. 그 뒤로 생기는 것들:

```
data/revision/   원장(ledger.jsonl) · 사건 · 경보 설정 · 감사로그    ← 쌓기만 한다
data/signals/    수집 신호 · 판정 결과
data/reports/    일간 보고서
data/backup/     쓰기 전 자동 백업 30개
```

운영에서 데이터를 앱 밖에 두려면 `MGSCS_DATA_DIR=/var/lib/mgscs` 환경변수 하나로 옮긴다.
(Apache: `SetEnv MGSCS_DATA_DIR /var/lib/mgscs`)

## 4. 수집(cron)

웹 요청 안에서는 수집하지 않는다. cron 이 `cron/collect.php` 를 부른다.

```
* * * * *  MGSCS_COLLECT=1 php /var/www/html/mbridgephp/cron/collect.php >> /var/log/mgscs-cron.log 2>&1
```

주기는 `MGSCS_COLLECT_MIN`(기본 60분)이 지킨다 — 매분 불러도 한 시간에 한 번만 돈다.
`MGSCS_COLLECT` 가 없으면 수집은 꺼진 채로 판정만 시도한다.

## 5. 라우팅표를 고치는 법

`index.php` 의 `$PAGES` / `$APIS` 두 표가 전부다.

```php
'/dashboard' => ['dashboard', true,  'Risk 현황', '…'],   // [Pages 메서드, 공개여부, 메뉴 이름, 설명]
'GET /api/resolved' => ['apiResolved', true, '…'],       // [Api 메서드, 공개여부, 설명]
```

**표에 없으면 잠긴다.** 새 화면을 만들며 표에 넣는 걸 잊으면 공개되는 게 아니라 잠기도록
(routes.py 의 "막을 칸을 세지 않고 열 칸만 센다" 원칙 그대로).

## 6. 문제가 생기면

- 500 → `MGSCS_DEBUG=1` 을 환경변수로 주면 화면에 원인과 위치가 나온다. 운영에서는 끈다.
  디버그가 꺼져 있어도 `data/revision/audit_log.json` 에 `api.error` / `page.error` 로 남는다.
- 화면은 뜨는데 API 가 404 → mod_rewrite 가 안 도는 것. 허브의 "API 연결" 칸으로 확인.
- 로컬 확인: `MGSCS_DEBUG=1 php -S 127.0.0.1:8080 index.php` 뒤 `http://127.0.0.1:8080/`

## 7. Flask 원본과 다른 점

| Flask | PHP |
|---|---|
| `/mgscs/` 접두어 고정 | index.php 위치에서 자동 (`Http::base()`) |
| threading 스케줄러 | `cron/collect.php` |
| `~/.mgscs_auth.json` | `data/auth.json` (`MGSCS_AUTH_FILE` 로 변경) |
| 서명 쿠키 세션 | PHP 세션 (12시간 · HttpOnly · SameSite=Lax) |
| PBKDF2-SHA256 240,000회 | 같음 — Flask 가 만든 자격 파일을 그대로 쓸 수 있다 |

이전 이식판 `php 배포용/` 은 이 구조로 대체되어 저장소에서 삭제됐다.
