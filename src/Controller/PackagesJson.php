<?php

namespace App\Controller;

use App\Components\Api\Client;
use App\Components\PackagistLoader;
use App\Components\RequestContextResolver;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PackagesJson
{
    public function __construct(private Client $client, private PackagistLoader $packagistLoader, private RequestContextResolver $resolver)
    {
    }

    #[Route('/packages.json', name: 'index')]
    public function index(Request $request): JsonResponse
    {
        $context = $this->resolver->resolve($request);

        $packagesJson = $this->packagistLoader->load($this->client->licenses($context->token), $context);

        return new JsonResponse($packagesJson);
    }
}
