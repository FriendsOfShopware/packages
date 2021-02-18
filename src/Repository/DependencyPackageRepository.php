<?php

namespace App\Repository;

use App\Entity\DependencyPackage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template DependencyPackage
 * @extends ServiceEntityRepository<DependencyPackage>
 *
 * @method DependencyPackage|null find($id, $lockMode = null, $lockVersion = null)
 * @method DependencyPackage|null findOneBy(array $criteria, array $orderBy = null)
 * @method DependencyPackage[]    findAll()
 * @method DependencyPackage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DependencyPackageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DependencyPackage::class);
    }
}
