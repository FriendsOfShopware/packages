<?php

namespace App\Controller;

use App\Entity\Package;
use App\Repository\PackageRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Browse extends AbstractController
{
    public function __construct(private Connection $connection)
    {
    }

    #[Route('/browse', name: 'browse')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        /** @var PackageRepository $repository */
        $repository = $entityManager->getRepository(Package::class);

        return $this->render('browse.html.twig', [
            'newPackages' => $repository->findNewPackages(),
            'popularPackages' => $this->getPopularPackages(),
        ]);
    }

    private function getPopularPackages(): array
    {
        $sql = <<<SQL
SELECT CONCAT('store.shopware.com/', LOWER(package.name)) as name, COUNT(*) as downloads
FROM download
INNER JOIN package ON package.id = package_id
GROUP BY download.package_id
ORDER BY COUNT(*) DESC
LIMIT 10
SQL;

        return $this->connection->fetchAll($sql);
    }
}
