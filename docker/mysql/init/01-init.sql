-- 위카옥션 데이터베이스 초기화 스크립트

-- 기본 문자셋 설정
SET NAMES utf8mb4;
SET character_set_client = utf8mb4;

-- 데이터베이스 생성 (필요시)
CREATE DATABASE IF NOT EXISTS `wca_auction_prod` 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

-- 테스트 데이터베이스 생성
CREATE DATABASE IF NOT EXISTS `wca_auction_test` 
    CHARACTER SET utf8mb4 
    COLLATE utf8mb4_unicode_ci;

-- 사용자 권한 설정 (환경변수 기반)
-- 운영환경에서는 .env 파일의 DB_USERNAME, DB_PASSWORD를 사용

-- 성능 관련 설정
SET GLOBAL innodb_buffer_pool_size = 268435456; -- 256MB
SET GLOBAL query_cache_size = 33554432; -- 32MB
SET GLOBAL table_open_cache = 2000;

-- 로그 설정
SET GLOBAL general_log = 'ON';
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 2;

-- 보안 설정
SET GLOBAL sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO';

-- 타임존 설정
SET GLOBAL time_zone = '+09:00';

-- 최적화
FLUSH PRIVILEGES;
FLUSH TABLES;