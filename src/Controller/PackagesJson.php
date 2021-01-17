<?php

namespace App\Controller;

use App\Components\Api\Client;
use App\Components\Encryption;
use App\Components\PackagistLoader;
use App\Exception\InvalidShopGivenHttpException;
use App\Exception\InvalidTokenHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class PackagesJson
{
    public function __construct(private Client $client, private PackagistLoader $packagistLoader, private Encryption $encryption, private CacheInterface $cache)
    {
    }

    #[Route('/packages.json', name: 'index')]
    public function index(Request $request): JsonResponse
    {
        if (!$request->headers->has('Token')) {
            throw new InvalidTokenHttpException();
        }
        $tokenValue = $request->headers->get('Token');
        try {
            $credentials = $this->encryption->decrypt((string) $tokenValue);
        } catch (\Throwable) {
            throw new InvalidTokenHttpException();
        }
        $cacheKey = \md5($credentials['username'] . $credentials['password'] . $credentials['domain'] . ($credentials['userId'] ?? ''));
        $token = $this->cache->get(\md5($cacheKey), function (ItemInterface $item) use ($tokenValue) {
            $credentials = $this->encryption->decrypt((string) $tokenValue);

            if (empty($credentials)) {
                throw new InvalidTokenHttpException();
            }

            $token = $this->client->login($credentials['username'], $credentials['password']);
            $item->expiresAt($token->getExpire());

            if (isset($credentials['userId'])) {
                $token->setUserId($credentials['userId']);
            }

            foreach ($this->client->memberShips($token) as $memberShip) {
                if ($memberShip->company->id === $token->getUserId()) {
                    $token->setMemberShip($memberShip);
                }
            }

            $shops = $this->client->shops($token);
            $foundShop = null;

            foreach ($shops as $shop) {
                if ($shop->domain === $credentials['domain']) {
                    $foundShop = $shop;
                    break;
                }
            }

            if (!$foundShop) {
                throw new InvalidShopGivenHttpException($credentials['domain']);
            }

            $token->setShop($foundShop);

            return $token;
        });

        $this->client->useToken($token);

        return new JsonResponse($this->packagistLoader->load($this->client->licenses($token), $token->getShop()));
    }
}
