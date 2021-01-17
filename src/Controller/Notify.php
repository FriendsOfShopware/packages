<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Package;
use App\Repository\PackageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Notify
{
    private const USER_AGENT_REGEX = '/Composer\/(?<composerVersion>\d+\.\d+\.\d+).*; PHP\s(?<phpVersion>\d+\.\d+\.\d+)/m';

    /**
     * @param PackageRepository<Package> $packageRepository
     */
    public function __construct(private PackageRepository $packageRepository, private EntityManagerInterface $entityManager)
    {
    }

    #[Route('/downloads/', name: 'notify')]
    public function notify(Request $request): JsonResponse
    {
        $json = \json_decode($request->getContent(), true, 512, \JSON_THROW_ON_ERROR);

        [$composerVersion, $phpVersion] = $this->getComposerAndPhpVersionFromRequest($request);

        if (!isset($json['downloads'])) {
            // Invalid request
            return new JsonResponse();
        }

        foreach ($json['downloads'] as $download) {
            /** @var \App\Entity\Package|null $package */
            $package = $this->packageRepository->findOneBy(['name' => \str_replace('store.shopware.com/', '', $download['name'])]);
            if ($package === null) {
                continue;
            }

            $downloadObj = new \App\Entity\Download();
            $downloadObj->setInstalledAt(new \DateTime());
            $downloadObj->setPackage($package);
            $downloadObj->setVersion($download['version']);
            $downloadObj->setComposerVersion($composerVersion);
            $downloadObj->setPhpVersion($phpVersion);
            $this->entityManager->persist($downloadObj);
        }

        $this->entityManager->flush();
        return new JsonResponse();
    }

    /**
     * @return array{0: string, 1: string}|null[]
     */
    private function getComposerAndPhpVersionFromRequest(Request $request): array
    {
        $userAgent = $request->headers->get('User-Agent');

        if (empty($userAgent)) {
            return [null, null];
        }

        if (!\str_contains($userAgent, 'Composer')) {
            return [null, null];
        }

        \preg_match_all(self::USER_AGENT_REGEX, $userAgent, $matches, \PREG_SET_ORDER, 0);

        return [$matches[0]['composerVersion'], $matches[0]['phpVersion']];
    }
}
