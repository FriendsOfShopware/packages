<?php

namespace App\Repository;

use App\Entity\DependencyPackage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
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

    // /**
    //  * @return DependencyPackage[] Returns an array of DependencyPackage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DependencyPackage
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
