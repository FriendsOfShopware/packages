<?php

namespace App\Controller;

use App\Components\Api\AccessToken;
use App\Components\Api\Exceptions\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class Login extends AbstractController
{
    /**
     * @var \App\Components\Api\Client
     */
    private $client;

    public function __construct(\App\Components\Api\Client $client)
    {
        $this->client = $client;
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

            return $this->redirectToRoute('shop-selection');
        } catch (AccessDeniedException $e) {
            return $this->render('login/index.html.twig', [
                'loginError' => true,
            ]);
        }
    }

    /**
     * @Route(path="/login/shop-selection", name="shop-selection")
     */
    public function shopSelection(Request $request)
    {
        /** @var AccessToken $token */
        $token = $this->getUser();
        $shops = $this->client->shops($token);

        if ($selectedShop = $request->request->get('shop')) {
            foreach ($shops as $shop) {
                if ($shop->domain === $selectedShop) {
                    $token->setShop($shop);

                    return $this->redirectToRoute('account');
                }
            }
        }

        return $this->render('login/shop-selection.html.twig', [
            'shops' => $shops,
        ]);
    }
}
