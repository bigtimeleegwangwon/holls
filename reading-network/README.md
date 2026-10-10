# 독서 개념 네트워크 시각화 v2 (슐 분석 기반)

**슐 분석(Sholl Analysis)** 개념을 문학비평에 도입한 대화형 네트워크 그래프 시각화.

책을 읽으면서 줄 친 **인물·사건·개념**을 네트워크로 그립니다.
- **x축** = 작중 시간(`timeOrder`) — 이야기가 진행되는 흐름
- **y축** = 동심원 반경(`radius`) — 핵심 주제로부터의 심리적·서사적 거리
- **선의 곡률·굵기·색** = 관계의 종류·강도·방향

## 특징

- 📊 **x축(시간) + y축(반경) 결합 레이아웃**: D3.js forceX + forceY로 타임라인 × 동심원 배치
- 🎨 **고정 4색 팔레트**: Scarlet / Amber / Emerald / Turquoise
- ⚡ **슐 지표 패널**: 반경별 교차점 수, 시간별 밀도, 가지 증식 시각화 (미구현)
- 🔗 **4가지 관계 유형(layer)**: 서사전개 / 철학기초 / 인물관계 / 은유확장
- 🔍 **검색 & 필터링**: 색상별 + layer별 토글
- 📋 **목록 뷰**: timeOrder + radius 정렬
- 📌 **상세 패널**: 각 노드의 원문·메모·출처
- 🌙 **다크/라이트 테마**: localStorage 저장

## 데이터 입력: `data.json` 구조

**v2는 노드/엣지 분리 스키마** — 노드가 더 이상 `links`를 내장하지 않음:

```json
{
  "nodes": [
    {
      "id": "n1",
      "label": "근대성",
      "category": "scarlet",
      "timeOrder": 1,
      "radius": 0,
      "chapter": "1",
      "page": 15,
      "quote": "근대성이란 단순한 시간의 개념이 아니라 정신적 태도의 문제다.",
      "note": "작가가 보는 근대의 본질적 정의"
    }
  ],
  "edges": [
    {
      "id": "e1",
      "source": "n1",
      "target": "n2",
      "relation": "철학적 기초",
      "layer": "서사전개",
      "weight": 3,
      "directed": true,
      "evidencePage": 23
    }
  ]
}
```

### nodes 배열

| 필드 | 타입 | 필수 | 설명 |
|------|------|------|------|
| `id` | 문자열 | ✅ | 유일한 노드 ID (예: `n1`, `n2`) |
| `label` | 문자열 | ✅ | 사건/인물/개념의 이름 |
| `category` | 문자열 | ✅ | `scarlet` \| `amber` \| `emerald` \| `turquoise` |
| `timeOrder` | 숫자 | ✅ | **작중 시간순 사건 번호**(1부터 시작, 중복 허용) |
| `radius` | 숫자 | ✅ | **슐 분석 동심원**(0~3; 0=중심/핵심, 3=말초/상징) |
| `chapter` | 문자열 | ❌ | 책의 장 (예: `1장`) |
| `page` | 숫자 | ❌ | 책에서 읽은 페이지 번호 |
| `quote` | 문자열 | ❌ | 책에서 줄 친 원문 |
| `note` | 문자열 | ❌ | 개인적인 메모/해석 |

### edges 배열

| 필드 | 타입 | 필수 | 설명 |
|------|------|------|------|
| `id` | 문자열 | ✅ | 유일한 엣지 ID (예: `e1`) |
| `source` | 문자열 | ✅ | 출발 노드의 ID |
| `target` | 문자열 | ✅ | 도착 노드의 ID |
| `relation` | 문자열 | ❌ | 관계 설명 (호버 시 tooltip) |
| `layer` | 문자열 | ✅ | 관계 종류: `서사전개` \| `철학기초` \| `인물관계` \| `은유확장` |
| `weight` | 숫자 | ❌ | 관계의 강도(1~3) — stroke-width에 반영 |
| `directed` | 불린 | ❌ | `true`(방향 있음) \| `false`(양방향) |
| `evidencePage` | 숫자 | ❌ | 관계가 명시된 페이지 |

## 슐 분석 동심원(radius)의 의미

| radius | 개념 | 설명 |
|--------|------|------|
| **0** | 세포체(Soma) | 핵심 모티프·주제·중심 사건 |
| **1** | 가지돌기(Dendrites) | 중심에서 직접 파생되는 개념 |
| **2** | 중간층 | 이론적 심화·역사적 배경 |
| **3** | 말초(Periphery) | 상징·구체적 사례·낯설게 하기 |

## 관계 유형(layer)

| layer | 의미 | 예시 |
|-------|------|------|
| **서사전개** | 플롯 진행·시간 흐름 | 사건→사건, 이야기의 흐름 |
| **철학기초** | 사상적·개념적 기초 | 개념→개념, "에 기초한" |
| **인물관계** | 캐릭터 간 관계·감정 | 인물→인물, 심리 상태 |
| **은유확장** | 상징·이미지의 변주 | 핵심 상징→하위 이미지, 낯설게 하기 |

## 시각화 요소

- **노드 위치**
  - x = `xScale(timeOrder)` — 좌→우 시간 진행
  - y = `centerY ± rScale(radius)` — 중심선 기준 상하 대칭, 반경 밴드
  - 반경별 수평 점선 + 라벨(우측)

- **엣지(링크)**
  - 곡선 경로(3차 베지어 곡선)
  - 색상: 호버 시 direction 강조 (outgoing=Scarlet, incoming=Turquoise)
  - `stroke-width` = `1.5 + weight × 0.3`
  - `directed:false` 엣지는 화살표 없음

- **상호작용**
  - 노드 호버 → 나가는/들어오는 엣지 강조, 레이블 명도 조정
  - 필터(`범주`, `관계 유형`) → layer별 엣지 toggle
  - 테마 토글 → dark/light CSS 변수
  - 검색 → 노드 이름 포함 필터

## 개입 없는 배포 (FTP)

### 환경
- 호스팅: cafe24 (공유 호스팅, PHP 8.4)
- URL: `https://getupearly.mycafe24.com/sholl/`
- 기술 스택: 순수 HTML/CSS/D3.js (프레임워크 없음)

### 배포 단계
1. 로컬 수정 후 FTP로 `/www/sholl/` 업로드 (index.html, style.css, app.js, data.json)
2. 캐시 버스팅 쿼리스트링 업데이트 (v3, v4, ... as needed)
3. 확인: curl로 최신 파일 서빙 여부 검증

```bash
curl -i https://getupearly.mycafe24.com/sholl/style.css?v=3
```

## 다크 테마 기술

- **FOUC 방지**: `<head>`의 inline `<script>`로 CSS 로드 전 테마 설정
- **CSS 변수**: `:root` (다크 기본) + `[data-theme="light"]` (오버라이드)
- **localStorage**: 테마 선택 저장

## 라이선스 & 출처

- **데이터**: 사용자 입력
- **라이브러리**: [D3.js v7](https://d3js.org/) (ISC License)
- **초기 스키마 영감**: [Mike Bostock — Hierarchical Edge Bundling](https://observablehq.com/@d3/hierarchical-edge-bundling/2)
- **문학비평 개념**: 슐 분석(Sholl Analysis) — 신경과학 → 문학비평 확장 개념화

---

**v2 완료일**: 2026년 10월

**v3 로드맵** (미구현):
- 회원가입 + OAuth 소셜 로그인 (Google/Kakao/Naver/GitHub)
- 사용자별 분석 저장·공유 (1분석/사용자)
- 슐 지표 패널 (반경별 교차점, 시간별 밀도)
