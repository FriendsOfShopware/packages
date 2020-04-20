<?php

namespace App\Command;

use App\Entity\Package;
use App\Entity\Version;
use App\Repository\PackageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Elasticsearch\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PackageIndexerCommand extends Command
{
    public const INDEX_NAME = 'packages';
    private const ES_TYPE = 'packages';

    protected static $defaultName = 'search:package:index';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(Client $client, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $indexName = self::INDEX_NAME . '_' . time();

        $this->createIndex($indexName);

        /** @var PackageRepository $repository */
        $repository = $this->entityManager->getRepository(Package::class);

        $total = $repository->findAllPackagesTotal();
        $progressBar = new ProgressBar($output);
        $progressBar->start($total);

        for ($i = 0; $i <= $total; $i += 100) {
            $packages = $repository->findAllPackages($i);

            $items = [];

            foreach ($packages as $package) {
                $unifiedTypes = array_values(array_unique(array_map(static function (Version $version) {
                    return $version->getType();
                }, $package->getVersions()->toArray())));

                $items[] = ['index' => ['_id' => $package->getId()]];
                $items[] = [
                    'id' => $package->getId(),
                    'name' => $package->getComposerName(),
                    'shortDescription' => $package->getShortDescription() ?? $package->getSafeDescription() ?? $package->getName(),
                    'types' => array_map(static function ($type) {
                        return ['name' => $type];
                    }, $unifiedTypes),
                    'producerName' => $package->getProducer()->getName(),
                ];
            }

            $this->client->bulk([
                'index' => $indexName,
                'type' => self::ES_TYPE,
                'body' => $items,
            ]);

            $progressBar->advance(100);
            unset($items, $packages);
            gc_collect_cycles();
        }

        $this->createAlias($indexName);
        $this->cleanup($indexName);

        return 0;
    }

    private function createIndex(string $indexName): void
    {
        $this->client->indices()->create([
            'index' => $indexName,
        ]);

        $this->client->indices()->putMapping([
            'index' => $indexName,
            'type' => self::ES_TYPE,
            'body' => [
                'dynamic' => false,
                'properties' => [
                    'id' => ['type' => 'long'],
                    'name' => $this->getLanguageField(),
                    'shortDescription' => $this->getLanguageField(),
                    'types' => [
                        'type' => 'nested',
                        'properties' => [
                            'name' => ['type' => 'keyword'],
                        ],
                    ],
                    'producerName' => $this->getLanguageField(),
                ],
            ],
            'include_type_name' => true,
        ]);
    }

    private function getLanguageField(): array
    {
        return [
            'type' => 'text',
            'fielddata' => true,
            'analyzer' => 'english',
            'fields' => [
                'raw' => ['type' => 'keyword'],
            ],
        ];
    }

    private function createAlias($newIndex): void
    {
        $aliasExists = $this->client->indices()->existsAlias(['name' => self::INDEX_NAME]);
        if ($aliasExists) {
            $this->switchAlias($newIndex);

            return;
        }

        $this->client->indices()->putAlias([
            'index' => $newIndex,
            'name' => self::INDEX_NAME,
        ]);
    }

    private function switchAlias(string $newIndex): void
    {
        $actions = [
            ['add' => ['index' => $newIndex, 'alias' => self::INDEX_NAME]],
        ];

        $current = $this->client->indices()->getAlias(['name' => self::INDEX_NAME]);
        $current = array_keys($current);
        foreach ($current as $value) {
            $actions[] = ['remove' => ['index' => $value, 'alias' => self::INDEX_NAME]];
        }

        $this->client->indices()->updateAliases(['body' => ['actions' => $actions]]);
    }

    private function cleanup(string $indexName): void
    {
        $aliases = $this->client->indices()->getAlias([]);

        foreach ($aliases as $index => $indexAliases) {
            if ($index === $indexName) {
                continue;
            }

            if (empty($indexAliases['aliases'])) {
                $this->client->indices()->delete(['index' => $index]);
            }
        }
    }
}
