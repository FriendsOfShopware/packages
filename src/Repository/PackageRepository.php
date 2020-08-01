<?php

namespace App\Repository;

use App\Entity\Package;
use App\Entity\Version;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\QueryBuilder;
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

    public function findJoinedAll(): array
    {
        $qb = $this->createQueryBuilder('package');
        $qb->innerJoin('package.versions', 'versions')
            ->innerJoin('package.producer', 'producer')
            ->addSelect('versions')
            ->addSelect('producer');

        return $qb->getQuery()->getArrayResult();
    }

    public function findOne(string $name): ?Package
    {
        $qb = $this->createQueryBuilder('package');
        $qb->innerJoin('package.versions', 'versions')
            ->innerJoin('package.producer', 'producer')
            ->addSelect('versions')
            ->addSelect('producer');

        $qb->where('package.name = :name')
            ->setParameter('name', rtrim(str_replace('store.shopware.com/', '', $name), '/'));

        /** @var Package|null $package */
        $package = $qb->getQuery()->getOneOrNullResult();

        if (null === $package) {
            return null;
        }

        $this->getEntityManager()->detach($package);

        $versions = $package->getVersions()->toArray();
        uasort($versions, static fn (Version $a, Version $b) => version_compare($a->getVersion(), $b->getVersion()));

        $package->setVersions(new ArrayCollection(array_reverse($versions)));

        return $package;
    }

    /**
     * @return Package[]
     */
    public function findPackagesForLicenses(array $names): array
    {
        $qb = $this->createQueryBuilder('package');
        $qb->innerJoin('package.versions', 'versions')
            ->addSelect('versions');

        $qb->where('package.name IN (:names)')
            ->setParameter('names', $names);

        $result = [];

        /** @var Package $package */
        foreach ($qb->getQuery()->getResult() as $package) {
            $result[$package->getComposerName()] = $package;
        }

        return $result;
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

    public function findAllPackagesTotal(): int
    {
        $paginator = new Paginator($this->findAllPackagesQuery());

        return $paginator->count();
    }

    /**
     * @return Package[]
     */
    public function findAllPackages(int $offset = 0, int $limit = 100): iterable
    {
        $paginator = new Paginator($this->findAllPackagesQuery());
        $paginator->getQuery()->setMaxResults($limit);
        $paginator->getQuery()->setFirstResult($offset);

        return $paginator->getIterator();
    }

    /**
     * @return Package[]
     */
    public function findNewPackages(): iterable
    {
        $qb = $this->createQueryBuilder('package');
        $qb->innerJoin('package.versions', 'versions')
            ->addSelect('versions')
            ->addOrderBy('package.releaseDate', 'DESC');

        $paginator = new Paginator($qb->getQuery());
        $paginator->getQuery()->setMaxResults(10);

        return $paginator->getIterator();
    }

    private function findAllPackagesQuery(): QueryBuilder
    {
        $qb = $this->createQueryBuilder('package');
        $qb
            ->innerJoin('package.versions', 'versions')
            ->innerJoin('package.producer', 'producer')
            ->addSelect('versions')
            ->addSelect('producer');

        return $qb;
    }
}
