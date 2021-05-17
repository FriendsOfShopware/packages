<?php

namespace App\Components\Api;

use App\Components\Api\Exceptions\AccessDeniedException;
use App\Components\Api\Exceptions\ApiException;
use App\Components\Api\Exceptions\TokenMissingException;
use App\Entity\Version;
use App\Struct\CompanyMemberShip\CompanyMemberShip;
use App\Struct\GeneralStatus;
use App\Struct\License\License;
use App\Struct\License\Plugin;
use App\Struct\Shop\Shop;
use App\Struct\Shop\SubscriptionModules;
use Psr\Cache\CacheItemInterface;
use Symfony\Component\HttpClient\Exception\ClientException;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

class Client
{
    private const ERROR_MAPPING = [
        'UsersException-3' => 'Invalid User. Please contact shopware AG',
        'UsersException-4' => 'Wrong credentials',
        'UsersException-16' => 'Email is actual unverified for the account. Please contact shopware AG',
        'UsersException-18' => 'User is locked. Please contact shopware AG',
    ];

    public const ENDPOINT = 'https://api.shopware.com/';

    protected ?AccessToken $currentToken = null;

    public function __construct(private CacheInterface $cache, private HttpClientInterface $client)
    {
    }

    public function login(string $username, string $password): AccessToken
    {
        try {
            /** @var array{'userId': string|null, 'userAccountId': int, 'token': string, 'locale': string, 'expire': array{'date': string}} $response */
            $response = $this->client->request('POST', self::ENDPOINT . 'accesstokens', [
                'json' => [
                    'shopwareId' => $username,
                    'password' => $password,
                ],
            ])->toArray();
        } catch (ClientException $exception) {
            $error = $exception->getResponse()->toArray(false);

            throw new AccessDeniedException(self::ERROR_MAPPING[$error['code']] ?? $error['code']);
        }

        if ($response['userId'] === null) {
            throw new AccessDeniedException('The shop does not have any company');
        }

        if (empty($response['userAccountId'])) {
            throw new AccessDeniedException('Please login with an Shopware User and not with an Shopware ID');
        }

        return AccessToken::create($response + ['username' => $username, 'password' => $password]);
    }

    public function useToken(AccessToken $token, bool $force = false): void
    {
        if ($this->currentToken && !$force) {
            return;
        }

        $this->currentToken = $token;
    }

    /**
     * @param array{'headers'?: array{'X-Shopware-Token'?: string, 'User-Agent'?: string}, 'query'?: array{'json'?: bool, 'userId'?: int, 'limit'?: int, 'variantTypes'?: string, 'shopId'?: int}} $options
     */
    private function sendRequest(string $method, string $url, array $options = []): ResponseInterface
    {
        if ($this->currentToken === null) {
            return $this->client->request($method, $url, $options);
        }

        $defaultOptions = [
            'headers' => [
                'X-Shopware-Token' => $this->currentToken->getToken(),
                'User-Agent' => 'packages.friendsofshopware.de',
            ],
        ];

        return $this->client->request($method, $url, array_replace_recursive($defaultOptions, $options));
    }

    /**
     * @return CompanyMemberShip[]
     */
    public function memberShips(AccessToken $token): array
    {
        $this->useToken($token);

        try {
            $content = json_decode(
                $this->sendRequest('GET', self::ENDPOINT . 'account/' . $token->getUserAccountId() . '/memberships')->getContent(),
                false
            );
        } catch (Throwable) {
            return [];
        }

        usort($content, static function ($a, $b) {
            return $a->company->name <=> $b->company->name;
        });

        return CompanyMemberShip::mapList($content);
    }

    /**
     * @return Shop[]
     */
    public function shops(AccessToken $token): array
    {
        $this->useToken($token);

        // client shops
        $clientShops = [];

        if ($token->getMemberShip()->can(CompanyMemberShip::PARTNER_SHOPS_PERMISSION)) {
            try {
                $content = $this->sendRequest('GET', self::ENDPOINT . 'partners/' . $token->getUserId() . '/clientshops')->getContent();

                $clientShops = Shop::mapList(json_decode($content, false));
            } catch (ClientException) {
                // We need more requests to determine that the user is an partner. Let the api check it for us.
            }
        }

        // wildcard shops
        $wildcardShops = [];

        if ($token->getMemberShip()->can(CompanyMemberShip::WILDCARD_SHOP_PERMISSION)) {
            try {
                $content = $this->sendRequest('GET', self::ENDPOINT . 'wildcardlicenses?companyId=' . $token->getUserId() . '&type=partner')->getContent();
                $content = json_decode($content);

                foreach ($content as $wildcardInstance) {
                    $instances = $wildcardInstance->instances ?? [];

                    $list = Shop::mapList($instances);
                    foreach ($list as $shop) {
                        $shop->companyId = $wildcardInstance->company->id;
                        $shop->companyName = $wildcardInstance->company->name;
                        $shop->type = $wildcardInstance->type->name;
                        $shop->staging = false;

                        $idn = idn_to_ascii($shop->domain);

                        if ($idn) {
                            $shop->domain_idn = $idn;
                        }

                        $shop->subscriptionModules = [SubscriptionModules::make(['expirationDate' => date('Y-m-d H:i:s', strtotime('+1 year'))])];
                        $wildcardShops[] = $shop;
                    }
                }
            } catch (ClientException) {
                // We need more requests to determine that the user is an partner. Let the api check it for us.
            }
        }

        $shops = [];

        if ($token->getMemberShip()->can(CompanyMemberShip::COMPANY_SHOPS_PERMISSION)) {
            try {
                $shopsContent = $this->sendRequest('GET', self::ENDPOINT . 'shops', [
                    'query' => [
                        'userId' => $token->getUserId(),
                    ],
                ])->getContent();

                $shops = Shop::mapList(json_decode($shopsContent));
            } catch (ClientException) {
                // Partner without own domains
            }
        }

        $allShops = array_merge($shops, $clientShops, $wildcardShops);

        usort($allShops, static function ($a, $b) {
            return $a->domain <=> $b->domain;
        });

        return $allShops;
    }

    /**
     * @return License[]
     */
    public function licenses(AccessToken $token): array
    {
        $this->useToken($token);

        if ($token->getShop() === null) {
            throw new \InvalidArgumentException('Token needs a Shop');
        }

        $cacheKey = $this->getLicenseCacheKey($token);

        return $this->cache->get($cacheKey, function (CacheItemInterface $item) use ($token) {
            $item->expiresAfter(300);

            if ($token->getShop()->type === Shop::TYPE_PARTNER) {
                $content = json_decode($this->sendRequest('GET', self::ENDPOINT . 'wildcardlicensesinstances/' . $token->getShop()->id)->getContent(), true);

                $licenses = [];
                foreach ($content['plugins'] as $pluginData) {
                    $license = new License();
                    $license->archived = false;
                    $license->plugin = Plugin::make($pluginData);
                    $license->variantType = new GeneralStatus();
                    $license->variantType->name = 'buy'; // this is not really true but it's okay for our purposes

                    $licenses[] = $license;
                }

                return $licenses;
            }

            try {
                $content = json_decode($this->sendRequest('GET', self::ENDPOINT . $this->getLicensesListPath($token), [
                    'query' => [
                        'variantTypes' => 'buy,free,rent,support,test',
                        'limit' => 1_000,
                    ],
                ])->getContent());

                // Some weird licenses doesnt have a plugin.
                foreach ($content as $key => $license) {
                    if (!isset($license->plugin)) {
                        unset($content[$key]);
                    }
                }
            } catch (ClientException) {
                $content = [];
            }

            try {
                $enterprisePlugins = json_decode($this->sendRequest('GET', self::ENDPOINT . 'shops/' . $token->getShop()->id . '/productacceleratorlicenses')->getContent());
            } catch (ClientException) {
                $enterprisePlugins = [];
            }

            foreach ($enterprisePlugins as $enterprisePlugin) {
                if (!isset($enterprisePlugin->licenseModule->plugin)) {
                    continue;
                }

                /**
                 * @var \stdClass $licenseModule
                 */
                $licenseModule = $enterprisePlugin->licenseModule;

                $licenseModule->archived = false;
                $licenseModule->variantType = new \stdClass();
                $licenseModule->variantType->name = 'buy';
                $licenseModule->plugin->isPremiumPlugin = false;
                $licenseModule->plugin->isAdvancedFeature = true;
                $content[] = $licenseModule;
            }

            return $content;
        });
    }

    /**
     * @return array{'partnerId': string}|null
     */
    public function getPartnerAllocation(): ?array
    {
        try {
            /** @var array{'partnerId': string} $response */
            $response = $this->sendRequest('GET', self::ENDPOINT . 'companies/' . $this->currentToken()->getMemberShip()->company->id . '/allocations')->toArray();
            return $response;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * @return array{'url': string, 'binary'?: array{'version': string}}
     */
    public function fetchDownloadJson(string $binaryLink): array
    {
        if (!$this->currentToken) {
            throw new TokenMissingException();
        }

        if ($this->currentToken->getShop() === null) {
            throw new \InvalidArgumentException('Token needs a Shop');
        }

        try {
            $query = ['json' => true];
            $headers = [];
            if ($this->currentToken->getShop()->type === Shop::TYPE_PARTNER) {
                $headers = [
                    'X-Shopware-Token' => $this->currentToken()->getToken(),
                ];
            } else {
                $query['shopId'] = $this->currentToken->getShop()->id;
            }

            /** @var array{'url': string, 'binary'?: array{'version': string}} $json */
            $json = $this->sendRequest('GET', self::ENDPOINT . $binaryLink, [
                'query' => $query,
                'headers' => $headers,
            ])->toArray();
        } catch (ClientException $e) {
            $response = $e->getResponse()->toArray(false);

            if (\array_key_exists('code', $response)) {
                throw new ApiException($response['code']);
            }

            throw new ApiException((string) json_encode($response));
        }

        return $json;
    }

    public function fetchDownloadLink(string $binaryLink): ?string
    {
        $json = $this->fetchDownloadJson($binaryLink);
        if (!\array_key_exists('url', $json)) {
            return null;
        }

        return $json['url'];
    }

    public function currentToken(): AccessToken
    {
        if ($this->currentToken === null) {
            throw new \RuntimeException('Current Token is not set');
        }

        return $this->currentToken;
    }

    /**
     * @param License $license
     *
     * @return string
     */
    public function getBinaryFilePath($license, Version $binary = null)
    {
        $shop = $this->currentToken()->getShop();

        if ($shop === null) {
            throw new \InvalidArgumentException('Token needs a Shop');
        }

        if ($shop->type === Shop::TYPE_PARTNER) {
            $filePath = "wildcardlicenses/{$shop->baseId}/instances/{$shop->id}/downloads/{$license->plugin->code}/{$shop->shopwareVersion->name}?json=true";
        } elseif ($binary) {
            $filePath = 'plugins/' . $license->plugin->id . '/binaries/' . $binary->getBinaryId() . '/file';
        } else {
            throw new \RuntimeException('Cannot determine filePath');
        }

        return $filePath;
    }

    public function getLicenseCacheKey(AccessToken $token): string
    {
        if ($token->getShop() === null) {
            throw new \InvalidArgumentException('Token needs a Shop');
        }

        return md5('license' . $token->getUsername() . $token->getShop()->domain . $token->getUserId());
    }

    private function getLicensesListPath(AccessToken $token): string
    {
        if ($token->getShop() === null) {
            throw new \InvalidArgumentException('Token needs a Shop');
        }

        if ($token->getShop()->ownerId === $token->getUserId()) {
            return 'shops/' . $token->getShop()->id . '/pluginlicenses';
        }

        $partnerAllocation = $this->getPartnerAllocation();
        if ($partnerAllocation === null || !isset($partnerAllocation['partnerId'])) {
            return 'partners/' . $token->getUserId() . '/customers/' . $token->getShop()->ownerId . '/shops/' . $token->getShop()->id . '/pluginlicenses';
        }

        return 'partners/' . $partnerAllocation['partnerId'] . '/customers/' . $token->getShop()->ownerId . '/shops/' . $token->getShop()->id . '/pluginlicenses';
    }
}
