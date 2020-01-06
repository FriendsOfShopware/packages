<?php

namespace App\Controller;

use App\Repository\PackageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class Export
{
    /**
     * @Route(path="/export.json")
     */
    public function export(EntityManagerInterface $entityManager, CacheInterface $cache): JsonResponse
    {
        /** @var PackageRepository $repository */
        $repository = $entityManager->getRepository(\App\Entity\Package::class);

        if (!$cache->has('export')) {
            $export = $repository->findJoinedAll();
            $cache->set('export', $export, 3600);

            return new JsonResponse($export);
        }

        return new JsonResponse($cache->get('export'));
    }
}
