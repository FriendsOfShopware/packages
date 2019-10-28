<?php


namespace App\Components;


class Encryption
{
    private const METHOD = 'aes256';

    /**
     * @var string
     */
    private $publicKey;

    /**
     * @var string
     */
    private $privateKey;

    public function __construct()
    {
        $path = dirname(__DIR__, 2) . '/ssl/';
        $this->publicKey = file_get_contents($path . 'public.pem');
        $this->privateKey = file_get_contents($path . 'private.pem');
    }

    public function encrypt(array $data): string
    {
        $str = json_encode($data);

        $key = openssl_pkey_get_public($this->publicKey);

        openssl_public_encrypt($str, $encryptedData, $key);

        return base64_encode($encryptedData);
    }

    public function decrypt(string $data): array
    {
        $data = base64_decode($data);

        $key = openssl_pkey_get_private($this->privateKey);

        openssl_private_decrypt($data, $decryptedData, $key);

        return json_decode($decryptedData, true);
    }
}