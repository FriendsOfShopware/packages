<?php

namespace App\Components;

class Encryption
{
    /**
     * @var resource
     */
    private $publicKey;

    /**
     * @var resource
     */
    private $privateKey;

    public function __construct()
    {
        $path = dirname(__DIR__, 2) . '/ssl/';
        $this->publicKey = openssl_pkey_get_public(file_get_contents($path . 'public.pem'));
        $this->privateKey = openssl_pkey_get_private(file_get_contents($path . 'private.pem'));
    }

    public function encrypt(array $data): string
    {
        $str = json_encode($data);

        openssl_public_encrypt($str, $encryptedData, $this->publicKey);

        return base64_encode($encryptedData);
    }

    public function decrypt(string $data): array
    {
        $data = base64_decode($data);

        openssl_private_decrypt($data, $decryptedData, $this->privateKey);

        return json_decode($decryptedData, true);
    }
}
