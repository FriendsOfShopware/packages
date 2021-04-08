<?php

namespace App\Components;

use App\Components\Api\Client;
use App\Exception\InvalidShopGivenHttpException;
use App\Exception\InvalidTokenHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class RequestContextResolver
{
    public function __construct(private Client $client, private Encryption $encryption, private CacheInterface $cache)
    {
    }

    public function resolve(Request $request): ResolverContext
    {
        $tokenValue = $request->headers->get('token', $request->headers->get('authorization'));

        if ($tokenValue === null) {
            throw new InvalidTokenHttpException();
        }

        if (\str_starts_with($tokenValue, 'Bearer ')) {
            $tokenValue = \substr($tokenValue, 7);
        }

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

        return new ResolverContext(
            $request->headers->has('token'),
            $token
        );
    }
}
