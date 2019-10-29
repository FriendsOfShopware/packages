<?php


namespace App\Components;

use App\Components\Api\AccessToken;
use App\Entity\Package;
use App\Entity\Version;
use App\Repository\PackageRepository;
use App\Repository\VersionRepository;
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
     * @var array<string, Package>
     */
    private $packageCache;

    public function __construct(CacheInterface $cache, EntityManagerInterface $entityManager)
    {
        $this->encryption = new Encryption();
        $this->cache = $cache;
        $this->entityManager = $entityManager;
        $this->packageRepository = $entityManager->getRepository(Package::class);
    }

    public function getBinaryInfo(string $pluginName, Binaries $binary): array
    {
        $cacheKey = $this->buildCacheKey($pluginName, $binary);
        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $package = $this->getPackage($pluginName);

        foreach ($package->getVersions() as $version) {
            if ($version->getVersion() === $binary->version) {
                $data = $version->toJson();
                $this->cache->set($cacheKey, $data);
                return $data;
            }
        }
    }

    public function hasBinary(string $pluginName, Binaries $binary): bool
    {
        if ($this->cache->has($this->buildCacheKey($pluginName, $binary))) {
            return true;
        }

        $package = $this->getPackage($pluginName);

        foreach ($package->getVersions() as $version) {
            if ($version->getVersion() === $binary->version) {
                return true;
            }
        }

        return false;
    }

    public function saveBinary(string $pluginName, Binaries $binary, array $info)
    {
        $package = $this->getPackage($pluginName);

        $version = new Version();
        $version->setVersion($binary->version);
        $version->setType($info['type']);
        $version->setDescription(isset($info['description']) ? substr($info['description'], 0 ,255) : null);
        $version->setExtra($info['extra'] ?? []);
        $version->setRequireSection($info['require'] ?? []);
        $version->setPackage($package);

        $this->entityManager->persist($version);
        $package->addVersion($version);

        $this->entityManager->flush();
        $this->cache->set($this->buildCacheKey($pluginName, $binary), $version->toJson());
    }

    public function generateLink(Binaries $binary, AccessToken $token): string
    {
        $data = [
            'filePath' => substr($binary->filePath, 1),
            'domain' => $token->getShop()->domain,
            'username' => $token->getUsername(),
            'password' => $token->getPassword()
        ];

        return getenv('APP_URL') . '/download?token=' . urlencode($this->encryption->encrypt($data));
    }

    private function getPackage(string $pluginName): Package
    {
        if (!isset($this->packageCache[$pluginName])) {
            $package = $this->packageRepository->findOneBy(['name' => $pluginName]);

            if (!$package) {
                $package = new Package();
                $package->setName($pluginName);
            }
            $this->entityManager->persist($package);
            $this->packageCache[$pluginName] = $package;
        }

        /** @var Package $package */
        return $this->packageCache[$pluginName];
    }

    private function buildCacheKey(string $pluginName, Binaries $binary): string
    {
        return $pluginName . '-' . $binary->version;
    }

    public function __destruct()
    {
        $this->entityManager->flush();
    }
}