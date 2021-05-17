<?php

namespace App\Controller;

use App\Components\Api\AccessToken;
use App\Components\Api\Client;
use App\Components\PackagistLoader;
use App\Components\ResolverContext;
use App\Entity\Package;
use App\Repository\PackageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Account extends AbstractController
{
    /**
     * @param PackageRepository<Package> $packageRepository
     */
    public function __construct(private Client $client, private PackagistLoader $packagistLoader, private PackageRepository $packageRepository)
    {
    }

    #[Route('/account', name: 'account')]
    public function index(): Response
    {
        if ($redirect = $this->haveShop()) {
            return $redirect;
        }

        /** @var AccessToken|null $token */
        $token = $this->getUser();

        if ($token === null || $token->getShop() === null) {
            return $this->redirectToRoute('login');
        }

        $licenses = $this->client->licenses($token);
        $context = new ResolverContext(false, $token);
        $data = $this->packagistLoader->load($licenses, $context);
        $packageNames = array_map(static fn (string $name) => str_replace('store.shopware.com/', '', $name), array_keys($data['packages']));

        /** @var Package[] $packages */
        $packages = $this->packageRepository->findPackagesByNames($packageNames);

        return $this->render('account.html.twig', [
            'packages' => $packages,
            'token' => (string) $token,
            'shop' => $token->getShop(),
            'company' => $token->getMemberShip()->company->name,
        ]);
    }

    private function haveShop(): ?RedirectResponse
    {
        if (! $this->getUser() instanceof AccessToken) {
            return null;
        }

        if (!$this->getUser()->getShop()) {
            return $this->redirectToRoute('shop-selection');
        }

        return null;
    }
}
