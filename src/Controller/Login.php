<?php

namespace App\Controller;

use App\Components\Client;
use App\Components\Encryption;
use App\Components\PackagistLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Login extends AbstractController
{
    /**
     * @Route(path="/login", methods={"GET"})
     */
    public function loginForm(): Response
    {
        return $this->render('login.html.twig');
    }

    /**
     * @Route(path="/login", methods={"POST"})
     */
    public function domainSelection(Request $request, Client $client): Response
    {
        $username = $request->request->get('shopwareId');
        $password = $request->request->get('password');

        try {
            $client->login($username, $password, null, false);

            $session = $request->getSession();
            $session->set('credentials', ['username' => $username, 'password' => $password]);

            return $this->render('login.html.twig', ['domains' => $client->getShops()]);
        } catch (\Exception $e) {
            return $this->render('login.html.twig', [
                'loginError' => true
            ]);
        }
    }

    /**
     * @Route(path="/token", name="token", methods={"POST"})
     */
    public function tokenPage(Request $request, PackagistLoader $packagistLoader, Encryption $encryption): Response
    {
        $domain = $request->request->get('domain');
        $credentials = $request->getSession()->get('credentials');

        $data = $packagistLoader->load($domain, $credentials['username'], $credentials['password']);

        $token = $encryption->encrypt([
            'username' => $credentials['username'],
            'password' => $credentials['password'],
            'domain' => $domain
        ]);

        return $this->render('token.html.twig', [
            'packages' => $data,
            'token' => $token,
            'domain' => $domain,
            'shop' => $packagistLoader->getClient()->getShop()
        ]);
    }


}