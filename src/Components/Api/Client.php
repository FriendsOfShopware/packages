<?php

namespace App\Components\Api;

use App\Components\Api\Exceptions\AccessDeniedException;
use App\Components\Api\Exceptions\ApiException;
use App\Components\Api\Exceptions\TokenMissingException;
use App\Entity\Version;
use App\Struct\CompanyMemberShip\CompanyMemberShip;
use App\Struct\GeneralStatus;
use App\Struct\License\License;
use App\Struct\Shop\Shop;
use App\Struct\Shop\SubscriptionModules;
use Psr\Cache\CacheItemInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
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

    protected HttpClientInterface $client;

    protected ?AccessToken $currentToken = null;

    public function __construct(private CacheInterface $cache)
    {
        $this->client = HttpClient::create();
    }

    public function login(string $username, string $password): AccessToken
    {
        try {
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

        return AccessToken::create($response + ['username' => $username, 'password' => $password]);
    }

    public function useToken(AccessToken $token, bool $force = false): void
    {
        if ($this->currentToken && !$force) {
            return;
        }

        $this->currentToken = $token;
        $this->client = HttpClient::create([
            'headers' => [
                'X-Shopware-Token' => $token->getToken(),
                'User-Agent' => 'packages.friendsofshopware.de',
            ],
        ]);
    }

    /**
     * @return CompanyMemberShip[]
     */
    public function memberShips(AccessToken $token): array
    {
        $this->useToken($token);

        try {
            $content = \json_decode(
                $this->client->request('GET', self::ENDPOINT . 'account/' . $token->getUserAccountId() . '/memberships')->getContent(),
                false
            );
        } catch (\Throwable) {
            return [];
        }

        \usort($content, static function ($a, $b) {
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
                $content = $this->client->request('GET', self::ENDPOINT . 'partners/' . $token->getUserId() . '/clientshops')->getContent();

                $clientShops = Shop::mapList(\json_decode($content, false));
            } catch (ClientException) {
                // We need more requests to determine that the user is an partner. Let the api check it for us.
            }
        }

        // wildcard shops
        $wildcardShops = [];

        if ($token->getMemberShip()->can(CompanyMemberShip::WILDCARD_SHOP_PERMISSION)) {
            try {
                $content = $this->client->request('GET', self::ENDPOINT . 'wildcardlicenses?companyId=' . $token->getUserId() . '&type=partner')->getContent();
                $content = \json_decode($content);
                $content = \array_shift($content);
                $instances = $content->instances ?? [];

                $wildcardShops = Shop::mapList($instances);
                foreach ($wildcardShops as $shop) {
                    $shop->companyId = $content->company->id;
                    $shop->companyName = $content->company->name;
                    $shop->type = $content->type->name;
                    $shop->staging = false;
                    $shop->domain_idn = \idn_to_ascii($shop->domain);
                    $shop->subscriptionModules = [SubscriptionModules::make(['expirationDate' => \date('Y-m-d H:i:s', \strtotime('+1 year'))])];
                }
            } catch (ClientException) {
                // We need more requests to determine that the user is an partner. Let the api check it for us.
            }
        }

        $shops = [];

        if ($token->getMemberShip()->can(CompanyMemberShip::COMPANY_SHOPS_PERMISSION)) {
            try {
                $shopsContent = $this->client->request('GET', self::ENDPOINT . 'shops', [
                    'query' => [
                        'userId' => $token->getUserId(),
                    ],
                ])->getContent();

                $shops = Shop::mapList(\json_decode($shopsContent));
            } catch (ClientException) {
                // Partner without own domains
            }
        }

        $allShops = \array_merge($shops, $clientShops, $wildcardShops);

        \usort($allShops, static function ($a, $b) {
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

        $cacheKey = $this->getLicenseCacheKey($token);

        return $this->cache->get($cacheKey, function (CacheItemInterface $item) use ($token) {
            $item->expiresAfter(300);

            if ($token->getShop()->type === Shop::TYPE_PARTNER) {
                $content = \json_decode($this->client->request('GET', self::ENDPOINT . 'wildcardlicensesinstances/' . $token->getShop()->id)->getContent());

                $licenses = [];
                foreach ($content->plugins as $pluginData) {
                    $license = new License();
                    $license->archived = false;
                    $license->plugin = $pluginData;
                    $license->variantType = new GeneralStatus();
                    $license->variantType->name = 'buy'; // this is not really true but it's okay for our purposes

                    $licenses[] = $license;
                }

                return $licenses;
            }

            try {
                $content = \json_decode($this->client->request('GET', self::ENDPOINT . $this->getLicensesListPath($token), [
                    'query' => [
                        'variantTypes' => 'buy,free,rent,support,test',
                        'limit' => 1_000,
                    ],
                ])->getContent());
            } catch (ClientException) {
                $content = [];
            }

            try {
                $enterprisePlugins = \json_decode($this->client->request('GET', self::ENDPOINT . 'shops/' . $token->getShop()->id . '/productacceleratorlicenses')->getContent());
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
            return $this->client->request('GET', self::ENDPOINT . 'companies/' . $this->currentToken()->getMemberShip()->company->id . '/allocations')->toArray();
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * @return array{'url': string, 'binary': array{'version': string}|null}
     */
    public function fetchDownloadJson(string $binaryLink): array
    {
        if (!$this->currentToken) {
            throw new TokenMissingException();
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

            $json = $this->client->request('GET', self::ENDPOINT . $binaryLink, [
                'query' => $query,
                'headers' => $headers,
            ])->toArray();
        } catch (ClientException $e) {
            $response = $e->getResponse()->toArray(false);

            if (\array_key_exists('code', $response)) {
                throw new ApiException($response['code']);
            }

            throw new ApiException(\json_encode($response));
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

    public function fetchDownloadVersion(string $binaryLink): ?string
    {
        $json = $this->fetchDownloadJson($binaryLink);
        if (!\array_key_exists('binary', $json) || !\is_array($json['binary'])) {
            return null;
        }

        return $json['binary']['version'] ?? null;
    }

    public function currentToken(): ?AccessToken
    {
        return $this->currentToken;
    }

    /**
     * @param License $license
     *
     * @return string
     */
    public function getBinaryFilePath($license, Version $binary = null)
    {
        $shop = $this->currentToken->getShop();
        if ($shop->type === Shop::TYPE_PARTNER) {
            $filePath = "wildcardlicenses/{$shop->baseId}/instances/{$shop->id}/downloads/{$license->plugin->code}/{$shop->shopwareVersion->name}";
        } else {
            $filePath = 'plugins/' . $license->plugin->id . '/binaries/' . $binary->getBinaryId() . '/file';
        }

        return $filePath;
    }

    public function getLicenseCacheKey(AccessToken $token): string
    {
        return \md5('license' . $token->getUsername() . $token->getShop()->domain . $token->getUserId());
    }

    private function getLicensesListPath(AccessToken $token): string
    {
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
