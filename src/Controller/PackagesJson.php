<?php

namespace App\Controller;

use App\Components\Api\Client;
use App\Components\Encryption;
use App\Components\PackagistLoader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PackagesJson
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var PackagistLoader
     */
    private $packagistLoader;

    /**
     * @var Encryption
     */
    private $encryption;

    public function __construct(Client $client, PackagistLoader $packagistLoader, Encryption $encryption)
    {
        $this->client = $client;
        $this->packagistLoader = $packagistLoader;
        $this->encryption = $encryption;
    }

    /**
     * @Route(path="/packages.json", name="index")
     */
    public function index(Request $request)
    {
        if (!$request->headers->has('Token')) {
            return new JsonResponse(['message' => 'Invalid headers'], Response::HTTP_FORBIDDEN);
        }

        $credentials = $this->encryption->decrypt($request->headers->get('Token'));

        if (empty($credentials)) {
            return new JsonResponse(['message' => 'Invalid headers'], Response::HTTP_FORBIDDEN);
        }

        $token = $this->client->login($credentials['username'], $credentials['password']);
        $shops = $this->client->shops($token);
        $foundShop = null;

        foreach ($shops as $shop) {
            if ($shop->domain === $credentials['domain']) {
                $foundShop = $shop;
                break;
            }
        }

        if (!$foundShop) {
            throw new \RuntimeException('Cannot find shop');
        }

        $token->setShop($foundShop);
        $this->client->useToken($token);

        return new JsonResponse($this->packagistLoader->load($this->client->licenses($token)));
    }
}