# Gemini CLI 프로젝트 점검 프롬프트

## 상황 설명
위카옥션(wca_v2) 프로젝트의 프론트엔드/백엔드 개발자가 인수인계 없이 동시에 퇴사했습니다. 
gemini.md 파일을 참고하여 프로젝트의 현재 상태를 점검하고 오류를 찾아주세요.

## 작업 지시사항

### 1단계: 환경 점검 (최우선)
```bash
# Docker/Sail 환경 확인
./vendor/bin/sail ps
./vendor/bin/sail up -d

# Laravel 상태 확인
./vendor/bin/sail artisan --version
./vendor/bin/sail artisan migrate:status
./vendor/bin/sail artisan config:cache
./vendor/bin/sail artisan route:cache
```

### 2단계: 오류 수집
1. **에러 로그 분석**
   - `storage/logs/laravel.log` 파일에서 최근 24시간 내 ERROR, CRITICAL 레벨 로그 수집
   - 반복되는 오류 패턴 파악

2. **프론트엔드 점검** (v2 디렉토리만)
   - 모든 주요 페이지 접속 테스트:
     - `/v2` (홈)
     - `/v2/login` (로그인)
     - `/v2/register` (회원가입)
     - `/v2/auction` (경매 목록)
     - `/v2/sell` (판매 등록)
   - 각 페이지의 JavaScript 콘솔 에러 수집
   - 404 에러 발생 리소스 확인

3. **API 엔드포인트 테스트**
   - `./vendor/bin/sail artisan route:list --path=api` 결과에서 주요 API 추출
   - 각 API의 응답 상태 확인 (200, 401, 404, 500 등)

### 3단계: 의존성 점검
1. **환경 변수 확인**
   - `.env` 파일에서 누락된 필수 변수 확인
   - 외부 API 키 유효성 검증 (테스트 가능한 것만)

2. **외부 서비스 연동 상태**
   - 결제 시스템
   - 차량 진단 API
   - SMS 발송 (Aligo)
   - 소셜 로그인

### 4단계: 결과 정리

다음 형식으로 정리해주세요:

```markdown
# 위카옥션 프로젝트 점검 결과

## 1. 치명적 오류 (즉시 수정 필요)
- [ ] 오류 내용
- [ ] 영향 범위
- [ ] 예상 원인

## 2. 주요 기능 오류
- [ ] 오류 내용
- [ ] 재현 방법
- [ ] 관련 파일/코드

## 3. UI/UX 이슈
- [ ] 문제 내용
- [ ] 발생 페이지
- [ ] 스크린샷 (가능한 경우)

## 4. 성능/최적화 이슈
- [ ] 이슈 내용
- [ ] 측정 지표

## 5. 보안 취약점
- [ ] 취약점 내용
- [ ] 위험도

## 6. 권장 조치사항
### 즉시 조치
1. 
2. 

### 단기 조치 (1주일 내)
1. 
2. 

### 중장기 개선사항
1. 
2. 
```

## 주의사항
- v1 디렉토리는 무시하고 v2만 점검
- 모든 명령어는 `./vendor/bin/sail`을 통해 실행
- 프로덕션 환경을 변경하지 말고 개발 환경에서만 테스트
- 민감한 정보(API 키, 비밀번호 등)는 결과에 포함하지 말 것

## 추가 요청사항
오류를 발견하면 다음 정보도 함께 제공해주세요:
- 오류 발생 시간
- 오류 스택 트레이스
- 관련 코드 위치 (파일명:줄번호)
- 재현 가능 여부
- 임시 해결 방법 (있다면) 
