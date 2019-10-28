<?php

namespace App\Controller;

use App\Components\Encryption;
use App\Components\PackagistLoader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PackagesJson
{
    /**
     * @Route(path="/packages.json", name="index")
     */
    public function index(Request $request, PackagistLoader $packagistLoader, Encryption $encryption)
    {
        if (!$request->headers->has('Token')) {
            return new JsonResponse(['message' => 'Invalid headers'], Response::HTTP_FORBIDDEN);
        }

        $token = $encryption->decrypt($request->headers->get('Token'));

        if (empty($token)) {
            return new JsonResponse(['message' => 'Invalid headers'], Response::HTTP_FORBIDDEN);
        }

        $data = $packagistLoader->load($token['domain'], $token['username'], $token['password']);

        return new JsonResponse($data);
    }
}