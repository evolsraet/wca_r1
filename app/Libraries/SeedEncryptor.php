<?php

namespace App\Libraries;

use Illuminate\Support\Str;
use App\Libraries\KISA_SEED_CBC;

class SeedEncryptor
{
    protected array $key;
    protected array $iv;

    public function __construct(string $keyHex = null, string $ivHex = null)
    {
        $this->key = $this->hexToByteArray($keyHex ?? '88,E3,4F,8F,08,17,79,F1,E9,F3,94,37,0A,D4,05,89');
        $this->iv = $this->hexToByteArray($ivHex ?? '26,8D,66,A7,35,A8,1A,81,6F,BA,D9,FA,36,16,25,01');
    }

    public function encrypt(string $hexPlain): string
    {
        $plainBytes = $this->hexToByteArray($hexPlain);

        if (count($plainBytes) === 0) {
            return $hexPlain;
        }

        $cipherBytes = KISA_SEED_CBC::SEED_CBC_Encrypt(
            $this->key, $this->iv, $plainBytes, 0, count($plainBytes)
        );

        return $this->byteArrayToHex($cipherBytes);
    }

    public function decrypt(string $hexCipher): string
    {
        $cipherBytes = $this->hexToByteArray($hexCipher);

        if (count($cipherBytes) === 0) {
            return $hexCipher;
        }

        $plainBytes = KISA_SEED_CBC::SEED_CBC_Decrypt(
            $this->key, $this->iv, $cipherBytes, 0, count($cipherBytes)
        );

        return $this->byteArrayToHex($plainBytes);
    }

    protected function hexToByteArray(string $hex): array
    {
        return array_map(fn($v) => hexdec(trim($v)), explode(',', $hex));
    }

    protected function byteArrayToHex(array $bytes): string
    {
        return rtrim(implode(',', array_map(fn($b) => strtoupper(sprintf("%02X", $b)), $bytes)), ',');
    }
}
