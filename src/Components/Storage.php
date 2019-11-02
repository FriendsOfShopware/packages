<?php

namespace App\Components;

use App\Components\Api\AccessToken;
use App\Entity\Package;
use App\Entity\Producer;
use App\Entity\Version;
use App\Repository\PackageRepository;
use App\Repository\ProducerRepository;
use App\Struct\ComposerPackageVersion;
use App\Struct\License\Binaries;
use Doctrine\ORM\EntityManagerInterface;
use Psr\SimpleCache\CacheInterface;

class Storage
{
    /**
     * @var Encryption
     */
    private $encryption;

    /**
     * @var CacheInterface
     */
    private $cache;

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
     * @var array<string, Package>
     */
    private $packageCache;

    /**
     * @var array<string, Producer>
     */
    private $producerCache;

    private $haveWritten = false;

    public function __construct(CacheInterface $cache, EntityManagerInterface $entityManager)
    {
        $this->encryption = new Encryption();
        $this->cache = $cache;
        $this->entityManager = $entityManager;
        $this->packageRepository = $entityManager->getRepository(Package::class);
        $this->producerRepository = $entityManager->getRepository(Producer::class);
    }

    public function __destruct()
    {
        if (!$this->haveWritten) {
            return;
        }

        $this->entityManager->flush();
    }

    /**
     * @param Binaries $binary
     */
    public function getBinaryInfo(string $pluginName, $binary, ComposerPackageVersion $packageVersion): array
    {
        $cacheKey = $this->buildCacheKey($pluginName, $binary);
        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $package = $this->getPackage($pluginName, $packageVersion->authors[0]['name']);

        foreach ($package->getVersions() as $version) {
            if ($version->getVersion() === $binary->version) {
                $data = $version->toJson();
                $this->cache->set($cacheKey, $data);

                return $data;
            }
        }
    }

    /**
     * @param Binaries $binary
     */
    public function hasBinary(string $pluginName, $binary, ComposerPackageVersion $packageVersion): bool
    {
        if ($this->cache->has($this->buildCacheKey($pluginName, $binary))) {
            return true;
        }

        $package = $this->getPackage($pluginName, $packageVersion->authors[0]['name']);

        foreach ($package->getVersions() as $version) {
            if ($version->getVersion() === $binary->version) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Binaries $binary
     */
    public function saveBinary(string $pluginName, $binary, ComposerPackageVersion $packageVersion)
    {
        $this->haveWritten = true;
        $package = $this->getPackage($pluginName, $packageVersion->authors[0]['name']);

        $version = new Version();
        $version->setVersion($binary->version);
        $version->setType($packageVersion->type);
        $version->setLicense($packageVersion->license);
        $version->setHomepage($packageVersion->homepage);
        $version->setDescription(substr($packageVersion->description, 0, 255));
        $version->setExtra($packageVersion->extra);
        $version->setRequireSection($packageVersion->require);
        $version->setAuthors($packageVersion->authors);
        $version->setPackage($package);

        $this->entityManager->persist($version);
        $package->addVersion($version);

        $this->cache->set($this->buildCacheKey($pluginName, $binary), $version->toJson());
    }

    public function generateLink(string $filePath, AccessToken $token): string
    {
        $data = [
            'filePath' => $filePath,
            'domain' => $token->getShop()->domain,
            'username' => $token->getUsername(),
            'password' => $token->getPassword(),
        ];

        return getenv('APP_URL') . '/download?token=' . urlencode($this->encryption->encrypt($data));
    }

    private function getPackage(string $pluginName, string $producerName): Package
    {
        if (!isset($this->packageCache[$pluginName])) {
            $package = $this->packageRepository->findOneBy(['name' => $pluginName]);

            if (!$package) {
                $package = new Package();
                $package->setName($pluginName);
                $package->setReleaseDate(new \DateTime());

                $producer = $this->getProducer($producerName);
                $producer->addPackage($package);
                $package->setProducer($producer);

                $this->entityManager->persist($producer);
            }
            $this->entityManager->persist($package);
            $this->packageCache[$pluginName] = $package;
        }

        /* @var Package $package */
        return $this->packageCache[$pluginName];
    }

    private function getProducer(string $producerName): Producer
    {
        if (!isset($this->producerCache[$producerName])) {
            $producer = $this->producerRepository->findOneBy(['name' => $producerName]);
            if (!$producer) {
                $producer = new Producer();
                $producer->setName($producerName);

                $this->entityManager->persist($producer);
            }

            $this->producerCache[$producerName] = $producer;
        }

        return $this->producerCache[$producerName];
    }

    /**
     * @param Binaries $binary
     */
    private function buildCacheKey(string $pluginName, $binary): string
    {
        return $pluginName . '-' . $binary->version;
    }
}
