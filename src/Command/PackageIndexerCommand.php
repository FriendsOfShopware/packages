<?php

namespace App\Command;

use Algolia\AlgoliaSearch\SearchClient;
use App\Entity\Package;
use App\Entity\Version;
use App\Repository\PackageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PackageIndexerCommand extends Command
{
    protected static $defaultName = 'search:package:index';

    /**
     * @var SearchClient
     */
    private $client;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(SearchClient $client, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $index = $this->client->initIndex($this->getIndexName());

        /** @var PackageRepository $repository */
        $repository = $this->entityManager->getRepository(Package::class);

        $packages = $repository->findAllPackages();

        $items = [];

        foreach ($packages as $package) {
            $items[] = [
                'objectID' => $package->getId(),
                'name' => $package->getComposerName(),
                'description' => array_values(array_filter(array_unique(array_map(static function (Version $version) {
                    return $version->getDescription();
                }, $package->getVersions()->toArray())))),
                'types' => array_values(array_unique(array_map(static function (Version $version) {
                    return $version->getType();
                }, $package->getVersions()->toArray()))),
                'producerName' => $package->getVersions()->current()->getAuthors()[0]['name'],
            ];
        }

        $index->saveObjects($items);
    }

    private function getIndexName(): string
    {
        $env = getenv('APP_ENV');

        if (!$env) {
            $env = 'dev';
        }

        return $env . '_packages';
    }
}
