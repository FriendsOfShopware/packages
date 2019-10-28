<?php


namespace App\Controller;


use App\Components\Client;
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

    public function __construct(Encryption $encryption)
    {
        $this->encryption = $encryption;
    }

    /**
     * @Route(path="/download")
     */
    public function download(Request $request, Client $client): Response
    {
        $token = $request->query->get('token');

        if (empty($token)) {
            return new JsonResponse([
                'Invalid token'
            ], Response::HTTP_FORBIDDEN);
        }

        $data = $this->encryption->decrypt($token);

        $client->login($data['username'], $data['password'], $data['domain'], true);
        $downloadLink = $client->getBinaryLink($data['filePath']);

        return new RedirectResponse($downloadLink);
    }
}