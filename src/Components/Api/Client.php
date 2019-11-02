<?php

namespace App\Components\Api;

use App\Components\Api\Exceptions\AccessDeniedException;
use App\Components\Api\Exceptions\TokenMissingException;
use App\Struct\License\License;
use App\Struct\Shop\Shop;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
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
     * @return Shop[]
     */
    public function shops(AccessToken $token): array
    {
        $this->useToken($token);

        $clientShops = [];

        try {
            $content = $this->client->request('GET', self::ENDPOINT . 'partners/' . $token->getUserId() . '/clientshops')->getContent();

            $clientShops = Shop::mapList(json_decode($content));
        } catch (ClientException $e) {
            // no partner account
        }

        $shopsContent = $this->client->request('GET', self::ENDPOINT . 'shops', [
            'query' => [
                'userId' => $token->getUserId(),
            ],
        ])->getContent();

        $shops = Shop::mapList(json_decode($shopsContent));

        return array_merge($shops, $clientShops);
    }

    /**
     * @return License[]
     */
    public function licenses(AccessToken $token): array
    {
        $this->useToken($token);

        $content = $this->cachedRequest('GET', self::ENDPOINT . $this->getLicensesListPath($token), [
            'query' => [
                'variantTypes' => 'buy,free,rent,support,test',
                'limit' => 1000,
            ],
        ]);

        foreach ($content as &$plugin) {
            $plugin = $this->cachedRequest('GET', self::ENDPOINT . $this->getPluginInfoPath($token, $plugin->id));
        }
        unset($plugin);

        return $content;
    }

    public function fetchDownloadLink(string $binaryLink): ?string
    {
        if (!$this->currentToken) {
            throw new TokenMissingException();
        }

        try {
            $json = $this->client->request('GET', self::ENDPOINT . $binaryLink, [
                'query' => [
                    'json' => true,
                    'shopId' => $this->currentToken->getShop()->id,
                ],
            ])->toArray();
        } catch (ClientException $e) {
            return null;
        }

        if (!array_key_exists('url', $json)) {
            return null;
        }

        return $json['url'];
    }

    public function currentToken(): ?AccessToken
    {
        return $this->currentToken;
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

    private function cachedRequest(string $method, string $uri, array $options = [])
    {
        $cacheKey = md5(json_encode($this->currentToken) . $method . $uri . json_encode($options));

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $response = json_decode($this->client->request($method, $uri, $options)->getContent());
        $this->cache->set($cacheKey, $response, 3600);

        return $response;
    }
}
