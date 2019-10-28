<?php

namespace App\Components;

use App\Struct\License\License;
use App\Struct\Shop\Shop;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\NullAdapter;

class Client
{
    private const BASE_URL = 'https://api.shopware.com';

    /**
     * @var string
     */
    private $token;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var Shop
     */
    private $shop;

    /**
     * @var Shop[]
     */
    private $customerShops = [];

    /**
     * @var License[]
     */
    private $licenses;

    /**
     * @var CacheInterface
     */
    private $readCache;

    /**
     * @var CacheInterface
     */
    private $writeCache;


    public function __construct(CacheInterface $cache)
    {
        $this->readCache = $cache;
        $this->writeCache = $cache;
    }

    public function login(string $username, string $password, ?string $domain = null, bool $loadLicenses = true)
    {
        $this->username = $username;
        $this->password = $password;

        $response = $this->apiRequest('/accesstokens', 'POST', [
            'shopwareId' => $this->username,
            'password' => $this->password,
        ]);

        if (isset($response['success']) && $response['success'] === false) {
            throw new \Exception(sprintf('Login to Account failed with code %s', $response['code']));
        }

        $this->token = $response['token'];
        $this->userId = $response['userId'];

        $this->loadLicenses($domain, $loadLicenses);
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getShop(): Shop
    {
        return $this->shop;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return License[]
     */
    public function getLicenses(): array
    {
        return $this->licenses;
    }

    public function getShops(): array
    {
        return $this->customerShops;
    }

    public function getBinaryLink(string $binaryLink): ?string
    {
        $json = $this->apiRequest($binaryLink, 'GET', [
            'json' => true,
            'shopId' => $this->shop->id
        ]);

        if (!array_key_exists('url', $json)) {
            return null;
        }

        return $json['url'];
    }

    public function disableCache(): void
    {
        $this->readCache = new NullAdapter();
    }

    private function loadLicenses(?string $domain = null, bool $loadLicenses = true): void
    {
        $partnerAccount = $this->apiRequest('/partners/' . $this->userId, 'GET');

        if ($partnerAccount && !empty($partnerAccount['partnerId'])) {
            $clientShops = Shop::mapList($this->apiRequest('/partners/' . $this->userId . '/clientshops', 'GET', [], false));
        } else {
            $clientShops = [];
        }

        $shops = Shop::mapList($this->apiRequest('/shops', 'GET', [
            'userId' => $this->userId,
        ], false));

        $this->customerShops = array_merge($shops, $clientShops);

        if (!$loadLicenses) {
            return;
        }

        $this->shop = array_filter($this->customerShops, function (Shop $shop) use ($domain) {
            return $shop->domain === $domain || ($shop->domain[0] === '.' && strpos($shop->domain, $domain) !== false);
        });

        if (!$loadLicenses) {
            return;
        }

        if (count($this->shop) === 0) {
            throw new \RuntimeException(sprintf('Shop with given domain "%s" does not exist!', $domain));
        }

        $this->shop = array_values($this->shop)[0];

        $licenseParams = [
            'domain' => $this->shop->domain,
        ];

        if (!empty($partnerAccount['partnerId'])) {
            $licenseParams['partnerId'] = $this->userId;
        }

        $licenses = $this->apiRequest('/licenses', 'GET', $licenseParams, false);

        if (isset($licenses->success) && !$licenses->success) {
            throw new \RuntimeException(sprintf('[Installer] Fetching shop licenses failed with code "%s"!', $licenses->code));
        }

        $this->licenses = License::mapList($licenses);
    }

    /**
     * @return array
     */
    private function apiRequest(string $path, string $method, array $params = [], bool $assoc = true): array
    {
        $cacheKey = md5($path . $method . json_encode($params) . $this->userId . json_encode($this->shop));

        if ($this->readCache->has($cacheKey)) {
            return $this->readCache->get($cacheKey);
        }

        if ($method === 'GET' && !empty($params)) {
            $path .= '?' . http_build_query($params);
        }

        $ch = curl_init(self::BASE_URL . $path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }

        if ($this->token) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'X-Shopware-Token: ' . $this->token,
                'Useragent: Packagist Server',
            ]);
        }

        $response = curl_exec($ch);
        $response = json_decode($response, $assoc);

        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) === 200) {
            $this->writeCache->set($cacheKey, $response, 300);
        }

        curl_close($ch);

        return $response;
    }
}
