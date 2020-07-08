<?php

namespace App\Components\Api;

use App\Components\Api\Exceptions\AccessDeniedException;
use App\Components\Api\Exceptions\TokenMissingException;
use App\Struct\CompanyMemberShip\CompanyMemberShip;
use App\Struct\License\Binaries;
use App\Struct\License\License;
use App\Struct\License\VariantType;
use App\Struct\Shop\Shop;
use Psr\Cache\CacheItemInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Client
{
    public const ENDPOINT = 'https://api.shopware.com/';

    /**
     * @var HttpClientInterface
     */
    protected $client;

    /**
     * @var AccessToken|null
     */
    protected $currentToken;

    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->client = HttpClient::create();
        $this->cache = $cache;
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
            if (Response::HTTP_FORBIDDEN === $exception->getCode()) {
                throw new AccessDeniedException('Access denied');
            }

            throw $exception;
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
            $content = $this->client->request('GET', self::ENDPOINT . 'account/' . $token->getUserAccountId() . '/memberships')->getContent();
        } catch (\Throwable $e) {
            return [];
        }

        return CompanyMemberShip::mapList(json_decode($content));
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

                $clientShops = Shop::mapList(json_decode($content));
            } catch (ClientException $e) {
                // We need more requests to determine that the user is an partner. Let the api check it for us.
            }
        }

        // wildcard shops
        $wildcardShops = [];

        if ($token->getMemberShip()->can(CompanyMemberShip::WILDCARD_SHOP_PERMISSION)) {
            try {
                $content = $this->client->request('GET', self::ENDPOINT . 'wildcardlicenses?companyId=' . $token->getUserId() . '&type=partner')->getContent();
                $content = json_decode($content);
                $content = array_shift($content);
                $instances = $content->instances ?? [];

                $wildcardShops = Shop::mapList($instances);
                foreach ($wildcardShops as $shop) {
                    $shop->companyId = $content->company->id;
                    $shop->companyName = $content->company->name;
                    $shop->type = $content->type->name;
                    $shop->staging = false;
                    $shop->domain_idn = idn_to_ascii($shop->domain);
                }
            } catch (ClientException $e) {
                // We need more requests to determine that the user is an partner. Let the api check it for us.
            }
        }

        $shops = [];

        if ($token->getMemberShip()->can(CompanyMemberShip::COMPANY_SHOPS_PERMISSION)) {
            $shopsContent = $this->client->request('GET', self::ENDPOINT . 'shops', [
                'query' => [
                    'userId' => $token->getUserId(),
                ],
            ])->getContent();

            $shops = Shop::mapList(json_decode($shopsContent));
        }

        return array_merge($shops, $clientShops, $wildcardShops);
    }

    /**
     * @return License[]
     */
    public function licenses(AccessToken $token): array
    {
        $this->useToken($token);

        $cacheKey = md5('license' . $token->getUsername() . $token->getShop()->domain . $token->getUserId());

        return $this->cache->get($cacheKey, function (CacheItemInterface $item) use ($token) {
            $item->expiresAfter(3600);

            if ($token->getShop()->type === Shop::TYPE_PARTNER) {
                $content = json_decode($this->client->request('GET', self::ENDPOINT . 'wildcardlicensesinstances/' . $token->getShop()->id)->getContent());

                $licenses = [];
                foreach ($content->plugins as $pluginData) {
                    $license = new License();
                    $license->archived = false;
                    $license->plugin = $pluginData;
                    $license->variantType = new VariantType();
                    $license->variantType->name = 'buy'; // this is not really true but it's okay for our purposes

                    // for wildcard licenses there is only one available binary
                    // it is always the newest binary for the configured shopware version
                    $license->plugin->binaries = $this->getAvailableWildcardBinary($license);

                    $licenses[] = $license;
                }

                return $licenses;
            }

            $content = json_decode($this->client->request('GET', self::ENDPOINT . $this->getLicensesListPath($token), [
                'query' => [
                    'variantTypes' => 'buy,free,rent,support,test',
                    'limit' => 1000,
                ],
            ])->getContent());

            try {
                $enterprisePlugins = json_decode($this->client->request('GET', self::ENDPOINT . 'shops/' . $token->getShop()->id . '/productacceleratorlicenses')->getContent());
            } catch (\Exception $e) {
                $enterprisePlugins = [];
            }

            foreach ($content as &$plugin) {
                $plugin = json_decode($this->client->request('GET', self::ENDPOINT . $this->getPluginInfoPath($token, $plugin->id))->getContent());
            }
            unset($plugin);

            foreach ($enterprisePlugins as $enterprisePlugin) {
                $enterpriseExtension = json_decode($this->client->request('GET', self::ENDPOINT . 'shops/' . $token->getShop()->id . '/productacceleratorlicenses/' . $enterprisePlugin->id)->getContent());
                $enterpriseExtension->licenseModule->archived = false;
                $enterpriseExtension->licenseModule->variantType = new \stdClass();
                $enterpriseExtension->licenseModule->variantType->name = 'buy';
                $enterpriseExtension->licenseModule->plugin->isPremiumPlugin = false;
                $enterpriseExtension->licenseModule->plugin->isAdvancedFeature = true;
                $content[] = $enterpriseExtension->licenseModule;
            }

            return $content;
        });
    }

    public function fetchDownloadJson(string $binaryLink): ?array
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
            return null;
        }

        return $json;
    }

    public function fetchDownloadLink(string $binaryLink): ?string
    {
        $json = $this->fetchDownloadJson($binaryLink);
        if (!array_key_exists('url', $json)) {
            return null;
        }

        return $json['url'];
    }

    public function fetchDownloadVersion(string $binaryLink): ?string
    {
        $json = $this->fetchDownloadJson($binaryLink);
        if (!array_key_exists('binary', $json) || !is_array($json['binary'])) {
            return null;
        }

        return $json['binary']['version'] ?? null;
    }

    public function currentToken(): ?AccessToken
    {
        return $this->currentToken;
    }

    /**
     * @param License       $license
     * @param Binaries|null $binary  Not neccassary for wildcard licenses
     *
     * @return string
     */
    public function getBinaryFilePath($license, $binary = null)
    {
        $shop = $this->currentToken->getShop();
        if ($shop->type === Shop::TYPE_PARTNER) {
            $filePath = "wildcardlicenses/{$shop->baseId}/instances/{$shop->id}/downloads/{$license->plugin->code}/{$shop->shopwareVersion->name}";
        } else {
            $filePath = 'plugins/' . $license->plugin->id . '/binaries/' . $binary->id . '/file';
        }

        return $filePath;
    }

    private function getLicensesListPath(AccessToken $token): string
    {
        if ($token->getShop()->ownerId === $token->getUserId()) {
            return 'shops/' . $token->getShop()->id . '/pluginlicenses';
        }

        return 'partners/' . $token->getUserId() . '/customers/' . $token->getShop()->ownerId . '/shops/' . $token->getShop()->id . '/pluginlicenses';
    }

    private function getPluginInfoPath(AccessToken $token, int $licenseId): string
    {
        if ($token->getShop()->ownerId === $token->getUserId()) {
            return 'shops/' . $token->getShop()->id . '/pluginlicenses/' . $licenseId;
        }

        return 'partners/' . $token->getUserId() . '/customers/' . $token->getShop()->ownerId . '/shops/' . $token->getShop()->id . '/pluginlicenses/' . $licenseId;
    }

    private function getAvailableWildcardBinary(License $license): array
    {
        $version = $this->fetchDownloadVersion($this->getBinaryFilePath($license));

        $binary = new Binaries();
        $binary->version = $version;

        return [$binary];
    }
}
