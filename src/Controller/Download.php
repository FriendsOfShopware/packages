<?php

namespace App\Controller;

use App\Components\Api\Client;
use App\Components\Encryption;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Download
{
    /**
     * @var Encryption
     */
    private $encryption;

    /**
     * @var Client
     */
    private $client;

    public function __construct(Encryption $encryption, Client $client)
    {
        $this->encryption = $encryption;
        $this->client = $client;
    }

    /**
     * @Route(path="/download")
     */
    public function download(Request $request): Response
    {
        $token = $request->query->get('token');

        if (empty($token)) {
            return new JsonResponse([
                'Invalid token',
            ], Response::HTTP_FORBIDDEN);
        }

        $data = $this->encryption->decrypt($token);

        $token = $this->client->login($data['username'], $data['password']);

        $shops = $this->client->shops($token);
        $foundShop = null;

        foreach ($shops as $shop) {
            if ($shop->domain === $data['domain']) {
                $foundShop = $shop;
                break;
            }
        }

        if (!$foundShop) {
            throw new \RuntimeException('Cannot find shop');
        }

        $token->setShop($foundShop);
        $this->client->useToken($token);

        $downloadLink = $this->client->fetchDownloadLink($data['filePath']);

        return new RedirectResponse($downloadLink);
    }
}
