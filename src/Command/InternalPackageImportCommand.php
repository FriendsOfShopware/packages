<?php

namespace App\Command;

use App\Components\Api\Client;
use App\Components\PluginReader;
use App\Entity\Package;
use App\Entity\Producer;
use App\Entity\Version;
use App\Repository\PackageRepository;
use App\Repository\ProducerRepository;
use App\Struct\License\Binaries;
use App\Struct\License\Plugin;
use Composer\Semver\VersionParser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class InternalPackageImportCommand extends Command
{
    protected static $defaultName = 'internal:package:import';

    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PackageRepository
     */
    private $packageRepository;

    /**
     * @var ProducerRepository
     */
    private $producerRepository;

    /**
     * @var VersionParser
     */
    private $versionParser;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->packageRepository = $entityManager->getRepository(Package::class);
        $this->producerRepository = $entityManager->getRepository(Producer::class);
        $this->versionParser = new VersionParser();
    }

    public function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED)
            ->addArgument('password', InputArgument::REQUIRED)
            ->addOption('offset', 'o', InputOption::VALUE_OPTIONAL, 'Offset', 0);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->login($input);

        $current = (int) $input->getOption('offset');
        $plugins = $this->loadPlugins($current);

        $progressBar = new ProgressBar($output);
        $progressBar->start();

        while (!empty($plugins)) {
            /** @var Plugin $plugin */
            foreach ($plugins as $plugin) {
                $this->processPlugin($plugin);
                $progressBar->advance();
            }

            $this->entityManager->flush();
            $this->entityManager->clear();
            $current += count($plugins);
            $plugins = $this->loadPlugins($current);
            $this->login($input);
        }

        $progressBar->finish();

        $output->writeln('');

        $this->rebuildRequireStructure();

        return 0;
    }

    private function login(InputInterface $input): void
    {
        $client = HttpClient::create();

        $response = $client->request('POST', getenv('SBP_LOGIN'), [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'name' => $input->getArgument('username'),
                'password' => $input->getArgument('password'),
            ],
        ])->toArray();

        $this->client = HttpClient::create([
            'headers' => [
                'X-Shopware-Token' => $response['token'],
                'User-Agent' => 'packages.friendsofshopware.de',
            ],
        ]);
    }

    private function loadPlugins(int $offset): array
    {
        return json_decode($this->client->request('GET', getenv('SBP_PLUGIN_LIST'), [
            'query' => [
                'filter' => '[{"property":"lifecycleStatus","value":"readyforstore","operator":"="},{"property":"certificationNotSet","value":false,"operator":"="},{"property":"hasNoPluginSuccessor","value":false,"operator":"="}]',
                'limit' => 100,
                'offset' => $offset,
                'orderby' => 'id',
                'ordersequence' => 'desc',
            ],
        ])->getContent());
    }

    /**
     * @param Plugin $plugin
     */
    private function processPlugin($plugin): void
    {
        // Don't trigger an error on api server, cause this is an fake plugin
        if ($plugin->name === 'SwagCorePlatform') {
            return;
        }

        // Invalid broken plugin
        if ($plugin->id === 2394) {
            return;
        }

        $package = $this->packageRepository->findOneBy([
            'name' => $plugin->name,
        ]);

        if (!$package) {
            $package = new Package();
            $package->setName($plugin->name);

            $producer = $this->producerRepository->findOneBy(['prefix' => $plugin->producer->prefix]);

            if (!$producer) {
                $producer = new Producer();
                $producer->setName($plugin->producer->name);
                $producer->setPrefix($plugin->producer->prefix);
                $this->entityManager->persist($producer);
            }

            $producer->setName($plugin->producer->name);
            $this->entityManager->flush();
            $package->setProducer($producer);

            $this->entityManager->persist($package);
        }

        $package->setReleaseDate(new \DateTime($plugin->creationDate));
        $package->setStoreLink('https://store.shopware.com/search?sSearch=' . $plugin->code);

        foreach ($plugin->infos as $info) {
            if ('en_GB' === $info->locale->name) {
                $package->setDescription($info->description);
                $package->setDescription($package->getSafeDescription());
                $package->setShortDescription($info->shortDescription);
            }
        }

        $this->entityManager->flush();

        if (!is_iterable($plugin->binaries)) {
            return;
        }

        /** @var Binaries $binary */
        foreach ($plugin->binaries as $binary) {
            try {
                $this->versionParser->normalize($binary->version);
            } catch (\UnexpectedValueException $e) {
                // Very old version
                if ($binary->creationDate === null) {
                    continue;
                }

                // Plugin developer does not understand semver
                $createDate = new \DateTime($binary->creationDate);
                $binary->version = $createDate->format('Y.m.d-Hi');
            }

            $foundVersion = false;
            foreach ($package->getVersions() as $version) {
                if ($version->getVersion() === $binary->version) {
                    if ('codereviewsucceeded' !== $binary->status->name) {
                        $this->entityManager->remove($version);
                        $this->entityManager->flush();
                    }
                    $foundVersion = $version;
                }
            }

            if ($foundVersion) {
                $foundVersion->setReleaseDate(new \DateTime($binary->creationDate));

                foreach ($binary->changelogs as $changelog) {
                    if ('en_GB' === $changelog->locale->name) {
                        $foundVersion->setChangelog($changelog->text);
                    }
                }

                continue;
            }

            if ('codereviewsucceeded' !== $binary->status->name) {
                continue;
            }

            if (empty($binary->compatibleSoftwareVersions)) {
                continue;
            }

            $version = new Version();
            $version->setVersion($binary->version);
            $version->setType('shopware-plugin');
            $version->setExtra([
                'installer-name' => $plugin->name,
            ]);
            $version->setRequireSection([
                'composer/installers' => '~1.0',
            ]);
            $version->setAuthors([
                [
                    'name' => $plugin->producer->name,
                ],
            ]);

            if ('classic' === $plugin->generation->name) {
                $version->addRequire('shopware/shopware', '>=' . $binary->compatibleSoftwareVersions[0]->name);
            }

            try {
                $pluginZip = $this->client->request('GET', Client::ENDPOINT . 'plugins/' . $plugin->id . '/binaries/' . $binary->id . '/file', [
                    'query' => [
                        'unencrypted' => 'true',
                    ],
                ])->getContent();
            } catch (\Throwable $e) {
                continue;
            }

            try {
                PluginReader::readFromZip($pluginZip, $version);
            } catch (\InvalidArgumentException $e) {
                continue;
            }

            $version->setDescription(mb_substr($version->getDescription(), 0, 255));
            $version->setPackage($package);
            $version->setReleaseDate(new \DateTime($binary->creationDate));
            $package->addVersion($version);

            foreach ($binary->changelogs as $changelog) {
                if ('en_GB' === $changelog->locale->name) {
                    $version->setChangelog($changelog->text);
                }
            }

            $this->entityManager->persist($version);
        }

        if ($package->getVersions()->count() === 0) {
            $this->entityManager->remove($package);
        }

        $this->entityManager->flush();
    }

    private function rebuildRequireStructure(): void
    {
        $connection = $this->entityManager->getConnection();

        $sql = <<<SQL
SELECT
JSON_UNQUOTE(composer_json->'$.name'),
CONCAT('store.shopware.com/',LOWER(package.name))
FROM version
INNER JOIN package ON(package.id = version.package_id)
WHERE JSON_UNQUOTE(composer_json->'$.name') IS NOT NULL
GROUP BY JSON_UNQUOTE(composer_json->'$.name'), package.name
SQL;

        $zipPackageNameToStoreName = $connection->executeQuery($sql)->fetchAll(\PDO::FETCH_KEY_PAIR);

        $validVersions = $connection->fetchAll('SELECT id, require_section FROM version
WHERE JSON_LENGTH(require_section) > 1 AND type = \'shopware-platform-plugin\'');

        foreach ($validVersions as $validVersion) {
            $validVersion['require_section'] = json_decode($validVersion['require_section'], true);
            $updatedRequire = [];
            $gotUpdated = false;

            foreach ($validVersion['require_section'] as $key => $val) {
                if (isset($zipPackageNameToStoreName[$key])) {
                    $updatedRequire[$zipPackageNameToStoreName[$key]] = $val;
                    $gotUpdated = true;
                } else {
                    $updatedRequire[$key] = $val;
                }
            }

            if ($gotUpdated) {
                $connection->update('version', ['require_section' => json_encode($updatedRequire)], ['id' => $validVersion['id']]);
            }
        }
    }
}
