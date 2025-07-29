#!/bin/bash

# ===========================================
# 자체 서명 SSL 인증서 생성 스크립트
# 개발 및 테스트용
# ===========================================

set -e

# 설정
CERT_DIR="$(dirname "$0")"
DAYS=365
COUNTRY="KR"
STATE="Busan"
CITY="Busan"
ORG="WeCarMobility"
UNIT="IT"
DOMAIN="${1:-localhost}"

echo "SSL 인증서 생성 중..."
echo "도메인: $DOMAIN"
echo "유효기간: $DAYS 일"

# 개인키 생성
openssl genrsa -out "$CERT_DIR/key.pem" 2048

# CSR(Certificate Signing Request) 생성
openssl req -new -key "$CERT_DIR/key.pem" -out "$CERT_DIR/cert.csr" -subj "/C=$COUNTRY/ST=$STATE/L=$CITY/O=$ORG/OU=$UNIT/CN=$DOMAIN"

# 자체 서명 인증서 생성
openssl x509 -req -in "$CERT_DIR/cert.csr" -signkey "$CERT_DIR/key.pem" -out "$CERT_DIR/cert.pem" -days $DAYS

# CSR 파일 제거
rm "$CERT_DIR/cert.csr"

# 권한 설정
chmod 600 "$CERT_DIR/key.pem"
chmod 644 "$CERT_DIR/cert.pem"

echo "SSL 인증서가 생성되었습니다:"
echo "  - 인증서: $CERT_DIR/cert.pem"
echo "  - 개인키: $CERT_DIR/key.pem"
echo ""
echo "운영환경에서는 신뢰할 수 있는 CA에서 발급받은 인증서를 사용하세요."