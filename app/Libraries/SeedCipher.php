<?php

namespace App\Libraries; // 네임스페이스를 App\Libraries로 변경

use Exception;

class SeedCipher
{
    private string $key;
    private string $iv;

    /**
     * SeedCipher constructor.
     * 키와 IV는 Laravel 설정 파일(config/cipher.php)에서 로드됩니다.
     * .env 파일에 SEED_KEY와 SEED_IV가 설정되어 있어야 합니다.
     * @throws Exception 유효하지 않은 키 또는 IV일 경우
     */
    public function __construct()
    {
        // Laravel의 config 헬퍼 함수를 사용하여 설정값을 읽어옵니다.
        $keyHex = config('cipher.seed.key');
        $ivHex = config('cipher.seed.iv');

        if (empty($keyHex)) {
            throw new Exception("SEED_KEY is not configured in .env or config/cipher.php");
        }
        if (empty($ivHex)) {
            throw new Exception("SEED_IV is not configured in .env or config/cipher.php");
        }

        // 16진수 문자열을 바이너리 키로 변환
        $this->key = hex2bin($keyHex);
        if (strlen($this->key) !== 16 && strlen($this->key) !== 32) { // 16바이트(128비트) 또는 32바이트(256비트)
            throw new Exception("Invalid SEED key length. Must be 16 or 32 bytes after hex2bin.");
        }

        // 16진수 문자열을 바이너리 IV로 변환
        $this->iv = hex2bin($ivHex);
        if (strlen($this->iv) !== 16) { // 16바이트(128비트)
            throw new Exception("Invalid SEED IV length. Must be 16 bytes after hex2bin.");
        }
    }

    /**
     * KISA SEED CBC 암호화 함수
     * @param string $plaintext 암호화할 평문
     * @return string Base64 인코딩된 암호문
     * @throws Exception openssl_encrypt 실패 시
     */
    public function encrypt(string $plaintext): string
    {
        $ciphertext = openssl_encrypt(
            $plaintext,
            'seed-cbc',
            $this->key,
            OPENSSL_RAW_DATA,
            $this->iv
        );

        if ($ciphertext === false) {
            throw new Exception("SeedEncryptPlaintext failed: " . openssl_error_string());
        }

        return base64_encode($ciphertext);
    }

    /**
     * KISA SEED CBC 복호화 함수
     * @param string $encryptedtext Base64 인코딩된 암호문
     * @return string 복호화된 평문
     * @throws Exception openssl_decrypt 실패 시
     */
    public function decrypt(string $encryptedtext): string
    {
        $raw_ciphertext = base64_decode($encryptedtext);
        if ($raw_ciphertext === false) {
            throw new Exception("Base64 decoding failed for: " . $encryptedtext);
        }

        $plaintext = openssl_decrypt(
            $raw_ciphertext,
            'seed-cbc',
            $this->key,
            OPENSSL_RAW_DATA,
            $this->iv
        );

        if ($plaintext === false) {
            throw new Exception("SeedDecryptPlaintext failed: " . openssl_error_string());
        }

        return $plaintext;
    }
}