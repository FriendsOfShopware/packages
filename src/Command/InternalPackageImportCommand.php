<?php

namespace App\Command;

use App\Components\Api\Client;
use App\Components\ExtensionReader;
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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class InternalPackageImportCommand extends Command
{
    protected static $defaultName = 'internal:package:import';

    private HttpClientInterface $client;

    /**
     * @var PackageRepository<Package>
     */
    private PackageRepository $packageRepository;

    /**
     * @var ProducerRepository<Producer>
     */
    private ProducerRepository $producerRepository;

    private VersionParser $versionParser;

    public function __construct(private EntityManagerInterface $entityManager, private ExtensionReader $reader)
    {
        parent::__construct();
        $this->packageRepository = $entityManager->getRepository(Package::class);
        $this->producerRepository = $entityManager->getRepository(Producer::class);
        $this->versionParser = new VersionParser();
    }

    public function configure(): void
    {
        $this
            ->setDescription('This command can be only used by an Shopware employee')
            ->addOption('offset', 'o', InputOption::VALUE_OPTIONAL, 'Offset', 0);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->login();

        $current = 0;

        if (\is_string($input->getOption('offset'))) {
            $current = (int) $input->getOption('offset');
        }

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
            $current += \count($plugins);
            $plugins = $this->loadPlugins($current);
            $this->login();
        }

        $progressBar->finish();

        $output->writeln('');

        $this->rebuildRequireStructure();

        return 0;
    }

    private function login(): void
    {
        $client = HttpClient::create();

        if (\getenv('SBP_LOGIN') === false) {
            throw new \RuntimeException('Please specify SBP_LOGIN env variable');
        }

        $response = $client->request('POST', \getenv('SBP_LOGIN'), [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'name' => $_SERVER['SBP_USER'],
                'password' => $_SERVER['SBP_PASSWORD'],
            ],
        ])->toArray();

        $this->client = HttpClient::create([
            'headers' => [
                'X-Shopware-Token' => $response['token'],
                'User-Agent' => 'packages.friendsofshopware.de',
            ],
        ]);
    }

    /**
     * @return Plugin[]
     */
    private function loadPlugins(int $offset): array
    {
        if (\getenv('SBP_PLUGIN_LIST') === false) {
            throw new \RuntimeException('Env variable SBP_PLUGIN_LIST is not defined');
        }

        return \json_decode($this->client->request('GET', \getenv('SBP_PLUGIN_LIST'), [
            'query' => [
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
        // Skip apps
        if ($plugin->generation->name === 'apps') {
            return;
        }

        // Don't trigger an error on api server, cause this is an fake plugin
        if ($plugin->name === 'SwagCorePlatform') {
            return;
        }

        // Broken plugin
        if ($plugin->id === 2_394) {
            return;
        }

        // For some reason the producer name is empty, skip it
        if (empty($plugin->producer->name)) {
            return;
        }

        $package = $this->packageRepository->findOneBy([
            'name' => $plugin->name,
        ]);

        if (!$package) {
            if ($plugin->activationStatus->name !== 'activated') {
                $createDate = new \DateTime($plugin->creationDate);
                $diff = $createDate->diff(new \DateTime());

                // New packages needs to be activated first. Consider but only within one month
                if ($diff->m + $diff->y === 0) {
                    return;
                }
            }

            $package = new Package();
            $package->setName($plugin->name);

            $producer = $this->producerRepository->findOneBy(['prefix' => $plugin->producer->prefix]);

            // Search by producer name. Sometimes prefix does not match.
            if (!$producer) {
                $producer = $this->producerRepository->findOneBy(['name' => $plugin->producer->name]);
            }

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

        $package->setLabel($this->getLabel($plugin));
        $package->setReleaseDate(new \DateTime($plugin->creationDate));
        $package->setStoreLink('https://store.shopware.com/en/search?sSearch=' . $plugin->code);

        foreach ($plugin->infos as $info) {
            if ('en_GB' === $info->locale->name) {
                $package->setDescription($info->description);
                $package->setDescription($package->getSafeDescription());
                $package->setShortDescription($info->shortDescription);
            }
        }

        $this->entityManager->flush();

        if (!\is_iterable($plugin->binaries)) {
            return;
        }

        /** @var Binaries $binary */
        foreach ($plugin->binaries as $binary) {
            try {
                $this->versionParser->normalize((string) $binary->version);
            } catch (\UnexpectedValueException) {
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
                if ($version->getVersion() === $binary->version && isset($binary->status)) {
                    if ('codereviewsucceeded' !== $binary->status->name) {
                        $this->entityManager->remove($version);
                        $this->entityManager->flush();
                    }
                    $foundVersion = $version;
                }
            }

            if ($foundVersion) {
                $foundVersion->setReleaseDate(new \DateTime($binary->creationDate ?? 'now'));
                $foundVersion->setBinaryId((int) $binary->id);

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
            $version->setBinaryId($binary->id);
            $version->setVersion((string) $binary->version);
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
            } catch (\Throwable) {
                continue;
            }

            try {
                $this->reader->readFromZip($pluginZip, $version);
            } catch (\InvalidArgumentException) {
                continue;
            }

            $version->setDescription(\mb_substr((string) $version->getDescription(), 0, 255));
            $version->setPackage($package);
            $version->setReleaseDate(new \DateTime($binary->creationDate ?? 'now'));
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

    /**
     * @param Plugin $plugin
     */
    private function getLabel(object $plugin): string
    {
        foreach ($plugin->infos as $info) {
            if ($info->locale->name === 'en_GB' && !empty($info->name)) {
                return $info->name;
            }
        }

        foreach ($plugin->infos as $info) {
            if (!empty($info->name)) {
                return $info->name;
            }
        }

        return $plugin->name;
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

        $zipPackageNameToStoreName = $connection->fetchAllKeyValue($sql);

        $validVersions = $connection->fetchAllAssociative('SELECT id, require_section FROM version
WHERE JSON_LENGTH(require_section) > 1 AND type = \'shopware-platform-plugin\'');

        foreach ($validVersions as $validVersion) {
            $validVersion['require_section'] = \json_decode($validVersion['require_section'], true);
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
                $connection->update('version', ['require_section' => \json_encode($updatedRequire)], ['id' => $validVersion['id']]);
            }
        }
    }
}
