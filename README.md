## 위카옥션

### 개요

-   2024-3-11
    현재 테스트 모델로, 대략 어떤 방식인지 공유 하기위해 베타 상태를 우선 공유함.
    추후 변경해 다시 전달 예정
-   2024-4-2
    개발상태로 변경 (새 리포지토리)

### 준비

git, docker(윈도우 WSL2), 옵션 (php8~, composer)

### 설치

#### GIT 다운로드

```bash
git clone https://github.com/evolsraet/wcauction_test.git
```

### .env.example -> .env 로 복사

#### php 의존성 업데이트

##### 방법 1. php 8 로 바로설치 (php8.x, composer 필요)

```bash
composer install
```

##### 방법 2. 필요파일 위치 후 설치 (php, compose 불필요) - 에러가능성 (24.3.18)

https://drive.google.com/file/d/1JMak5vJLC6F3kHxrnGV-AmQYBoHUWnRw/view?usp=drive_link

-   폴더/vendoer/laravel 안에서 압축풀기
-   docker-composer up -d
-   도커내부에서 [laravel.test] composer install

#### 도커 서버 실행

./vendor/bin/sail 을 Alias 하여 sail 명령으로 진행 추천
(linux, mac 예: [alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'])

```bash
./vendor/bin/sail up -d ## 서버 실행 (localhost)
./vendor/bin/sail php artisan migrate:fresh --seed ## 디비 새로 마이그레이션 및 테스트 데이터 설치 (개발중 언제든지)
# ./vendor/bin/sail php artisan migrate ## 디비 마이그레이션
# ./vendor/bin/sail php artisan db:seed ## 임시 데이터 삽입
./vendor/bin/sail  artisan storage:link ## 파일 링크
```

```bash
./vendor/bin/sail stop ## 정지
```

#### npm 서버 실행 (별도 터미널 추천)

```bash
./vendor/bin/sail npm install ## 의존성 설치 (처름 1회)
./vendor/bin/sail npm run dev ## 실행
```

### 설명

-   vue 진입 포인트 : /resources/views/main-view.blade.php
-   vue 단일 js : /resources/sass/app.scss
-   sass 단일 scss : /resources/sass/app.scss
-   vue 관련 모든 소소 : /resources/j

작업환경 vscode 추천

### 작업주의

-   개발 플로우
    -   작업전 (당겨오기)
        -   git pull // 저장소 내용으로 로컬 업데이트
    -   작업 후
        -   git add . // 추가 파일 적용
        -   git commit -am '메세지' // 로컬에만 저장됨
        -   git push // 저장소에 업로드

# 테스트2