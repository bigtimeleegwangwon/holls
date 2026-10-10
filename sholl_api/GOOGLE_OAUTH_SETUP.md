# Google OAuth 설정 가이드

## 1단계: Google Cloud 프로젝트 생성

1. [Google Cloud Console](https://console.cloud.google.com/) 접속
2. 상단 프로젝트 선택 드롭다운 → **새 프로젝트**
3. 프로젝트명: `sholl-reader` (또는 원하는 이름)
4. **만들기** 클릭

## 2단계: OAuth 2.0 동의 화면 구성

1. 왼쪽 메뉴 → **APIs & Services** → **OAuth consent screen**
2. **User Type**: 외부(External) 선택 → **만들기**
3. 동의 화면 설정:
   - **앱 이름**: "독서 개념 네트워크"
   - **사용자 지원 이메일**: 본인 이메일
   - **개발자 연락처 정보**: 본인 이메일
4. **저장 및 계속**
5. 범위 설정: **범위 추가** → `openid`, `email`, `profile` 선택 → **업데이트**
6. 테스트 사용자: **사용자 추가** → 본인 이메일 추가 (테스트 중)
7. **저장 및 계속**

## 3단계: OAuth 클라이언트 ID 생성

1. 왼쪽 메뉴 → **APIs & Services** → **Credentials**
2. **+ 사용자 인증정보 만들기** → **OAuth 클라이언트 ID**
3. **애플리케이션 유형**: 웹 애플리케이션
4. **이름**: `sholl-web-client`
5. **리다이렉트URI** 추가:
   ```
   https://getupearly.mycafe24.com/sholl_api/auth/google/callback
   ```
6. **만들기**

## 4단계: 클라이언트 ID & 시크릿 복사

1. 생성된 OAuth 2.0 클라이언트 ID 클릭
2. **클라이언트 ID** 복사
3. **클라이언트 보안 비밀번호** 복사

## 5단계: 로컬 설정 파일에 입력

`sholl_api/config.local.php` 파일 생성 (config.local.php.example 참고):

```php
<?php
define('GOOGLE_CLIENT_ID', '[복사한 클라이언트 ID]');
define('GOOGLE_CLIENT_SECRET', '[복사한 클라이언트 보안 비밀번호]');
```

## 6단계: 배포

### 로컬 테스트 (필수)
```bash
# 로컬 PHP 서버 시작
cd sholl_api
php -S localhost:8001

# 브라우저에서 테스트
curl http://localhost:8001/auth/google/start
# Google 인증 페이지로 리다이렉트되면 성공
```

### café24 배포
1. `config.local.php` 파일 생성 (위와 동일)
2. 전체 `sholl_api/` 디렉토리를 FTP로 `/www/sholl_api/` 업로드
3. 권한 설정: `data/` 디렉토리는 744, 파일은 644

## 주의사항

⚠️ **보안**:
- `config.local.php`는 절대 git에 커밋하지 마세요
- `.gitignore`에 추가:
  ```
  config.local.php
  data/
  ```
- 클라이언트 보안 비밀번호는 절대 프론트엔드에 노출되면 안 됩니다

⚠️ **리다이렉트 URI**:
- 정확히 `https://getupearly.mycafe24.com/sholl_api/auth/google/callback` 이어야 함
- 프로토콜 (https), 도메인, 경로 모두 정확해야 함

## 테스트 사용자 추가

Google OAuth 동의 화면을 "외부" 타입으로 설정하면 테스트 사용자만 로그인 가능합니다. 프로덕션 배포 전에 "내부"로 변경하거나 앱 검증을 받아야 합니다.

현재는 테스트 모드이므로:
1. 위에서 추가한 테스트 사용자 이메일로만 로그인 테스트 가능
2. 다른 사용자는 "승인되지 않은 앱" 에러 표시

## 다음 단계

Google OAuth 동작 확인 후:
- Stage 3: Kakao, Naver, GitHub OAuth 추가
- Stage 4: 프론트엔드 로그인 UI 구현
