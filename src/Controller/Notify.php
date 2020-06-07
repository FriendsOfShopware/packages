<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Downloads;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Notify
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(path="/downloads/", name="notify")
     */
    public function notify(Request $request): JsonResponse
    {
        $json = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (!isset($json['downloads'])) {
            // Invalid request
            return new JsonResponse();
        }

        $packageRepository = $this->entityManager->getRepository(\App\Entity\Package::class);

        foreach ($json['downloads'] as $download) {
            /** @var \App\Entity\Package|null $package */
            $package = $packageRepository->findOneBy(['name' => str_replace('store.shopware.com/', '', $download['name'])]);
            if ($package === null) {
                continue;
            }

            $downloadObj = new \App\Entity\Download();
            $downloadObj->setInstalledAt(new \DateTime());
            $downloadObj->setPackage($package);
            $downloadObj->setVersion($download['version']);
            $this->entityManager->persist($downloadObj);
        }

        $this->entityManager->flush();

        return new JsonResponse();
    }
}
