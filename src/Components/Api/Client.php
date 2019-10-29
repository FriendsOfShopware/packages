<?php

namespace App\Components\Api;

use App\Components\Api\Exceptions\AccessDeniedException;
use App\Components\Api\Exceptions\TokenMissingException;
use App\Struct\License\License;
use App\Struct\Shop\Shop;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Client
{
    private const ENDPOINT = 'https://api.shopware.com/';

    /**
     * @var HttpClientInterface
     */
    protected $client;

    /**
     * @var AccessToken|null
     */
    protected $currentToken;

    public function __construct()
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
                ]
            ])->toArray();
        } catch (ClientException $exception) {
            if ($exception->getCode() === Response::HTTP_FORBIDDEN) {
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
                'User-Agent' => 'packages.friendsofshopware.de'
            ]
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
                'userId' => $token->getUserId()
            ]
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
        $content = $this->client->request('GET', self::ENDPOINT . 'licenses', [
            'query' => [
                'domain' => $token->getShop()->domain,
                'partnerId' => $token->getUserId()
            ]
        ])->getContent();

        return License::mapList(json_decode($content));
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
                    'shopId' => $this->currentToken->getShop()->id
                ]
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
}