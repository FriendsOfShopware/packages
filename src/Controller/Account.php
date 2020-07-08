<?php

namespace App\Controller;

use App\Components\Api\AccessToken;
use App\Components\Api\Client;
use App\Components\PackagistLoader;
use App\Entity\Package;
use App\Repository\PackageRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(Client $client, PackagistLoader $packagistLoader, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->packagistLoader = $packagistLoader;
        $this->entityManager = $entityManager;
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

        $data = $this->packagistLoader->load($licenses, $token->getShop());
        $packageNames = array_map(static function (string $name) {
            return str_replace('store.shopware.com/', '', $name);
        }, array_keys($data['packages']));

        /** @var PackageRepository $repository */
        $repository = $this->entityManager->getRepository(Package::class);

        /** @var \App\Entity\Package[] $packages */
        $packages = $repository->findPackagesByNames($packageNames);

        return $this->render('account.html.twig', [
            'packages' => $packages,
            'token' => (string) $token,
            'shop' => $token->getShop(),
            'company' => $token->getMemberShip()->company->name,
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
