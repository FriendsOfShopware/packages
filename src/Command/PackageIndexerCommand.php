<?php

namespace App\Command;

use App\Entity\Package;
use App\Entity\Version;
use App\Repository\PackageRepository;
use Doctrine\ORM\EntityManagerInterface;
use MeiliSearch\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PackageIndexerCommand extends Command
{
    public const INDEX_NAME = 'packages';
    private const ES_TYPE = 'packages';

    protected static $defaultName = 'search:package:index';

    private Client $client;

    private EntityManagerInterface $entityManager;

    public function __construct(Client $client, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->client->deleteAllIndexes();
        $index = $this->client->createIndex(self::INDEX_NAME);
        $index->updateSettings([
            'attributesForFaceting' => [
                'types',
                'producerName',
            ],
        ]);

        /** @var PackageRepository $repository */
        $repository = $this->entityManager->getRepository(Package::class);

        $total = $repository->findAllPackagesTotal();
        $progressBar = new ProgressBar($output);
        $progressBar->start($total);

        for ($i = 0; $i <= $total; $i += 100) {
            $packages = $repository->findAllPackages($i);

            $items = [];

            foreach ($packages as $package) {
                $unifiedTypes = \array_values(\array_unique(\array_map(static fn (Version $version) => $version->getType(), $package->getVersions()->toArray())));

                $items[] = [
                    'id' => $package->getId(),
                    'name' => $package->getName(),
                    'composerName' => $package->getComposerName(),
                    'label' => $package->getLabel(),
                    'shortDescription' => $package->getShortDescription() ?? $package->getSafeDescription() ?? $package->getName(),
                    'types' => $unifiedTypes,
                    'producerName' => $package->getProducer()->getName(),
                ];
            }

            $index->addDocuments($items);

            $progressBar->advance(100);
            unset($items, $packages);
            \gc_collect_cycles();
        }

        $output->writeln('');

        return 0;
    }
}
