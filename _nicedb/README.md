# 신차 DB SQL 변환 도구

Excel 파일(`nice_db_20250424.xlsx`)을 위카옥션 데이터베이스 테이블 구조에 맞는 SQL INSERT 문으로 변환하는 도구입니다.

## 📊 변환 결과

| 테이블 | 레코드 수 | 파일명 | 설명 |
|--------|----------|---------|------|
| car_makers | 91개 | `car_makers_insert.sql` | 제조사 정보 |
| car_models | 942개 | `car_models_insert.sql` | 대표 모델 정보 |
| car_details | 1,813개 | `car_details_insert.sql` | 세부 모델 정보 |
| car_bps | 8,071개 | `car_bps_insert.sql` | 제원 정보 |
| car_grades | 25,288개 | `car_grades_insert.sql` | 등급 정보 |

**총 35,205개의 INSERT 문이 생성되었습니다.**

## 📋 테이블 구조 매핑

### car_makers (제조사)
- `id` ← 제조사코드
- `name` ← 제조사명  
- `name_en` ← 제조사영문명
- `country` ← 제조국가
- `import_yn` ← 국산/수입구분
- `logo_url` ← 이미지URL

### car_models (대표 모델)
- `id` ← 대표모델코드
- `name` ← 대표모델명
- `maker_id` ← 제조사코드 (FK)

### car_details (세부 모델)
- `id` ← 모델명코드
- `name` ← 모델명
- `model_id` ← 대표모델코드 (FK)
- `short_name` ← 짧은모델명
- `generation_name` ← 세대코드
- `start_date` ← 모델출시일
- `end_date` ← 모델단종일
- `image_url` ← 이미지URL

### car_bps (제원)
- `id` ← 제원코드
- `name` ← 제원명
- `detail_id` ← 모델명코드 (FK)

### car_grades (등급)
- `id` ← 등급ID
- `name` ← 등급명
- `bp_id` ← 제원코드 (FK)
- 기타 상세 정보 포함

## 🚀 사용 방법

### 1. Laravel Seeder 사용 (권장)
```bash
# 차량 DB만 단독 실행
./vendor/bin/sail artisan db:seed --class=CarOnlySeeder

# 전체 시드 실행 (환경별 자동 판단)
./vendor/bin/sail artisan db:seed

# 프로덕션 환경에서 강제 실행
./vendor/bin/sail artisan db:seed --force
```

### 2. 개별 테이블 실행 (SQL 직접)
```sql
-- 제조사만 삽입
SOURCE car_makers_insert.sql;

-- 모델만 삽입  
SOURCE car_models_insert.sql;
```

### 3. 전체 테이블 일괄 실행 (SQL 직접)
```sql
-- FK 의존성 순서로 모든 테이블 실행
SOURCE execute_all_car_inserts.sql;
```

### 4. MySQL 직접 실행
```bash
# MySQL 컨테이너에서 실행
./vendor/bin/sail mysql < _nicedb/execute_all_car_inserts.sql
```

## ⚠️ 주의사항

### 실행 순서
외래키 제약조건으로 인해 **반드시 다음 순서**로 실행해야 합니다:
1. car_makers (제조사)
2. car_models (대표 모델)  
3. car_details (세부 모델)
4. car_bps (제원)
5. car_grades (등급)

### 데이터 검증
- 헤더 행(`makerId` 등)은 자동으로 제거됨
- NULL 값은 적절히 처리됨
- 숫자형 ID는 유효성 검사 후 삽입
- 문자열의 따옴표는 이스케이프 처리됨

### 중복 데이터
- 각 테이블의 기본키 기준으로 중복 제거 완료
- 동일한 ID로 재실행 시 PRIMARY KEY 오류 발생 가능

## 🛠️ 개발 도구

### Laravel Seeder
- `Common/CarDatabaseSeeder.php`: 차량 DB 전용 시더
- `CarOnlySeeder.php`: 차량 DB만 단독 실행하는 시더
- `DatabaseSeeder.php`: 환경별 시더 (차량 DB 포함)

### 파이썬 스크립트
- `excel_to_sql_converter.py`: car_makers 테이블만 생성
- `comprehensive_car_db_converter.py`: 모든 테이블 생성

### 실행 환경
```bash
# 가상환경 설정
python3 -m venv venv
source venv/bin/activate
pip install pandas openpyxl

# 스크립트 실행
python comprehensive_car_db_converter.py
```

## 📈 데이터 통계

### 제조사별 분포
- 국산: 약 30개 제조사
- 수입: 약 61개 제조사

### 차종별 분포  
- 승용차, SUV, 트럭, 버스 등 다양한 차종 포함
- 전기차, 하이브리드 포함

### 가격대별 분포
- 신차 가격: 1,000만원 ~ 5억원대
- 다양한 등급별 세분화

## 🔧 문제 해결

### 인코딩 오류
- 모든 파일은 UTF-8로 저장됨
- MySQL에서 `charset utf8mb4` 사용 권장

### 메모리 부족
- car_grades 테이블이 가장 큼 (25,288개 레코드)
- 필요시 배치 단위로 분할 실행

### FK 제약조건 오류
- 실행 순서 확인
- 참조하는 상위 테이블 데이터 존재 여부 확인

---

## 📝 생성 정보
- **생성일**: 2025-08-05 16:26:10
- **원본 파일**: nice_db_20250424.xlsx (25,289행)
- **도구**: Python pandas + openpyxl
- **테이블**: Laravel Migration 기준