-- 차량 DB 통합 INSERT 실행 스크립트
-- 실행 순서: FK 의존성을 고려한 순서로 실행
-- 생성일: 2025-08-05 16:26:10

-- 1. car_makers (제조사) - 최상위 테이블
SOURCE car_makers_insert.sql;

-- 2. car_models (대표 모델) - car_makers 참조
SOURCE car_models_insert.sql;

-- 3. car_details (세부 모델) - car_models 참조  
SOURCE car_details_insert.sql;

-- 4. car_bps (제원) - car_details 참조
SOURCE car_bps_insert.sql;

-- 5. car_grades (등급) - car_bps 참조
SOURCE car_grades_insert.sql;

-- 완료 메시지
SELECT '차량 DB 데이터 삽입 완료!' as message;