<?php

namespace App\Controller;

use App\Components\Api\AccessToken;
use App\Components\Api\Client;
use App\Components\Api\Exceptions\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Contracts\Cache\CacheInterface;

class Login extends AbstractController
{
    private Client $client;

    private CacheInterface $cache;

    public function __construct(Client $client, CacheInterface $cache)
    {
        $this->client = $client;
        $this->cache = $cache;
    }

    /**
     * @Route(path="/login", name="login", methods={"GET"})
     */
    public function loginForm(): Response
    {
        if ($this->getUser() instanceof AccessToken) {
            return $this->redirectToRoute('account');
        }

        return $this->render('login/index.html.twig');
    }

    /**
     * @Route(path="/login", methods={"POST"})
     */
    public function loginSubmit(Request $request, EventDispatcherInterface $dispatcher): Response
    {
        $username = $request->request->get('shopwareId');
        $password = $request->request->get('password');

        try {
            $accessToken = $this->client->login($username, $password);

            $loginToken = new UsernamePasswordToken($accessToken, null, 'main', ['ROLE_USER']);
            $this->get('security.token_storage')->setToken($loginToken);

            $event = new InteractiveLoginEvent($request, $loginToken);
            $dispatcher->dispatch($event);
            $memberShips = $this->client->memberShips($accessToken);

            if (\count($memberShips) > 1) {
                return $this->redirectToRoute('company-selection');
            }

            if (isset($memberShips[0])) {
                $accessToken->setMemberShip($memberShips[0]);
                $accessToken->setUserId($memberShips[0]->company->id);
            }

            return $this->redirectToRoute('shop-selection');
        } catch (AccessDeniedException $e) {
            return $this->render('login/index.html.twig', [
                'loginError' => $e,
            ]);
        }
    }

    /**
     * @Route(path="/account/company-selection", name="company-selection")
     */
    public function companySelection(Request $request)
    {
        /** @var AccessToken $token */
        $token = $this->getUser();

        if (!$token) {
            return $this->redirectToRoute('login');
        }

        $memberShips = $this->client->memberShips($token);

        if ($selectedCompany = $request->request->get('membership')) {
            foreach ($memberShips as $memberShip) {
                if ($memberShip->id === (int) $selectedCompany) {
                    $token->setUserId($memberShip->company->id);
                    $token->setMemberShip($memberShip);

                    return $this->redirectToRoute('shop-selection');
                }
            }
        }

        return $this->render('login/company-selection.html.twig', [
            'memberships' => $memberShips,
        ]);
    }

    /**
     * @Route(path="/account/shop-selection", name="shop-selection")
     */
    public function shopSelection(Request $request)
    {
        /** @var AccessToken $token */
        $token = $this->getUser();

        if (!$token) {
            return $this->redirectToRoute('login');
        }

        $shops = $this->client->shops($token);

        if ($selectedShop = $request->request->get('shop')) {
            foreach ($shops as $shop) {
                if ($shop->domain === $selectedShop) {
                    $token->setShop($shop);
                    $this->cache->delete($this->client->getLicenseCacheKey($token));

                    return $this->redirectToRoute('account');
                }
            }
        }

        return $this->render('login/shop-selection.html.twig', [
            'shops' => $shops,
        ]);
    }
}
