<?php

$key = openssl_pkey_new([
    'digest_alg' => 'aes256',
    'private_key_type' => \OPENSSL_KEYTYPE_RSA,
    'encrypt_key' => 'packagist',
    'encrypt_key_cipher' => \OPENSSL_CIPHER_AES_256_CBC,
]);

if (!mkdir($concurrentDirectory = __DIR__ . '/ssl') && !is_dir($concurrentDirectory)) {
    throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
}

openssl_pkey_export_to_file($key, __DIR__ . '/ssl/private.pem');

$keyData = openssl_pkey_get_details($key);
file_put_contents(__DIR__ . '/ssl/public.pem', $keyData['key']);

chmod(__DIR__ . '/ssl/private.pem', 0660);
chmod(__DIR__ . '/ssl/public.pem', 0660);
