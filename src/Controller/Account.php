<?php

namespace App\Controller;

use App\Components\Api\AccessToken;
use App\Components\Api\Client;
use App\Components\PackagistLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Account extends AbstractController
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var PackagistLoader
     */
    private $packagistLoader;

    public function __construct(Client $client, PackagistLoader $packagistLoader)
    {
        $this->client = $client;
        $this->packagistLoader = $packagistLoader;
    }

    /**
     * @Route(path="/account", name="account")
     */
    public function index(Request $request): Response
    {
        if ($redirect = $this->haveShop()) {
            return $redirect;
        }

        /** @var AccessToken $token */
        $token = $this->getUser();

        $licenses = $this->client->licenses($token);

        $data = $this->packagistLoader->load($licenses);

        return $this->render('token.html.twig', [
            'packages' => $data,
            'token' => (string)$token,
            'shop' => $token->getShop()
        ]);
    }

    private function haveShop(): ?RedirectResponse
    {
        if (!$this->getUser()->getShop()) {
            return $this->redirectToRoute('shop-selection');
        }

        return null;
    }
}