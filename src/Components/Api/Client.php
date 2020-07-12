<?php

namespace App\Components\Api;

use App\Components\Api\Exceptions\AccessDeniedException;
use App\Components\Api\Exceptions\TokenMissingException;
use App\Struct\CompanyMemberShip\CompanyMemberShip;
use App\Struct\License\Binaries;
use App\Struct\License\License;
use App\Struct\Shop\Shop;
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

        $shopsContent = $this->client->request('GET', self::ENDPOINT . 'packages/' . $token->getUserId() . '/shops')->getContent();

        return Shop::mapList(json_decode($shopsContent));
    }

    /**
     * @return License[]
     */
    public function licenses(AccessToken $token): array
    {
        $this->useToken($token);


        $url = sprintf(self::ENDPOINT . 'packages/%d/shops/%s/%d', $token->getUserId(), $token->getShop()->type, $token->getShop()->id);

        return License::mapList(json_decode($this->client->request('GET', $url)->getContent()));
    }

    public function fetchDownloadJson(string $binaryLink): ?array
    {
        if (!$this->currentToken) {
            throw new TokenMissingException();
        }

        try {
            $query = ['json' => true];
            $headers = [];
            if ($this->currentToken->getShop()->type === Shop::TYPE_WILDCARD) {
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
        if ($shop->type === Shop::TYPE_WILDCARD) {
            $filePath = "wildcardlicenses/{$shop->baseId}/instances/{$shop->id}/downloads/{$license->code}/{$shop->shopwareVersion}";
        } else {
            $filePath = 'plugins/' . $license->id . '/binaries/' . $binary->id . '/file';
        }

        return $filePath;
    }
}
