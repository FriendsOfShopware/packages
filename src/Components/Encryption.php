<?php

namespace App\Components;

use OpenSSLAsymmetricKey;

class Encryption
{
    private OpenSSLAsymmetricKey $publicKey;

    private OpenSSLAsymmetricKey $privateKey;

    public function __construct()
    {
        $path = \dirname(__DIR__, 2) . '/ssl/';
        $publicBody = \file_get_contents($path . 'public.pem');
        $privateBody = \file_get_contents($path . 'private.pem');

        if ($publicBody === false || $privateBody === false) {
            throw new \RuntimeException('Cannot read encryption files');
        }

        if ($publicKey = \openssl_pkey_get_public($publicBody)) {
            $this->publicKey = $publicKey;
        } else {
            throw new \RuntimeException('Cannot read public key');
        }

        if ($privateKey = \openssl_pkey_get_private($privateBody)) {
            $this->privateKey = $privateKey;
        } else {
            throw new \RuntimeException('Cannot read private key');
        }
    }

    /**
     * @param array{'domain'?: string, 'username'?: string, 'password'?: string, 'userId'?: int, 'filePath'?: string, 'dependencyId'?: int} $data
     */
    public function encrypt(array $data): string
    {
        $str = \json_encode($data, \JSON_THROW_ON_ERROR);

        \openssl_public_encrypt($str, $encryptedData, $this->publicKey);

        return \base64_encode($encryptedData);
    }

    /**
     * @return array{'domain': string, 'username': string, 'password': string, 'userId': int, 'filePath': string, 'dependencyId'?: int}
     */
    public function decrypt(string $data): array
    {
        $data = \base64_decode($data);

        \openssl_private_decrypt($data, $decryptedData, $this->privateKey);

        return \json_decode($decryptedData, true);
    }
}
