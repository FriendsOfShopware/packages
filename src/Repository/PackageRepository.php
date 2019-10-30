<?php

namespace App\Repository;

use App\Entity\Package;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Package|null find($id, $lockMode = null, $lockVersion = null)
 * @method Package|null findOneBy(array $criteria, array $orderBy = null)
 * @method Package[]    findAll()
 * @method Package[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PackageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Package::class);
    }

    public function findOne(string $name): ?Package
    {
        $qb = $this->createQueryBuilder('package');
        $qb->innerJoin('package.versions', 'versions')
            ->innerJoin('package.producer', 'producer')
            ->addSelect('versions')
            ->addSelect('producer');

        $qb->addOrderBy('versions.version', 'DESC');
        $qb->where('package.name = :name')
            ->setParameter('name', rtrim(str_replace('store.shopware.com/', '', $name), '/'));

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findPackagesByNames(array $names): array
    {
        $qb = $this->createQueryBuilder('package');
        $qb->innerJoin('package.versions', 'versions')
            ->addSelect('versions');

        $qb->where('package.name IN (:names)');
        $qb->setParameter('names', $names, Connection::PARAM_STR_ARRAY);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Package[]
     */
    public function findAllPackages(): array
    {
        $qb = $this->createQueryBuilder('package');
        $qb->innerJoin('package.versions', 'versions')
            ->addSelect('versions');

        return $qb->getQuery()->getResult();
    }

    /**
     * @return Package[]
     */
    public function findNewPackages(): \ArrayIterator
    {
        $qb = $this->createQueryBuilder('package');
        $qb->innerJoin('package.versions', 'versions')
            ->addSelect('versions')
            ->addOrderBy('package.id', 'DESC');


        $paginator = new Paginator($qb->getQuery());
        $paginator->getQuery()->setMaxResults(10);

        return $paginator->getIterator();
    }

}
