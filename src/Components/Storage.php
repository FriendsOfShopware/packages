<?php


namespace App\Components;

use App\Struct\License\Binaries;
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

    public function __construct(CacheInterface $cache)
    {
        $this->encryption = new Encryption();
        $this->cache = $cache;
    }

    public function getBinaryInfo(string $pluginName, Binaries $binary): array
    {
        return $this->cache->get($this->buildCacheKey($pluginName, $binary));
    }

    public function hasBinary(string $pluginName, Binaries $binary): bool
    {
        return $this->cache->has($this->buildCacheKey($pluginName, $binary));
    }

    public function saveBinary(string $pluginName, Binaries $binary, array $info)
    {
        return $this->cache->set($this->buildCacheKey($pluginName, $binary), $info);
    }

    public function generateLink(Binaries $binary, Client $client): string
    {
        $data = [
            'filePath' => $binary->filePath,
            'domain' => $client->getShop()->domain,
            'username' => $client->getUsername(),
            'password' => $client->getPassword()
        ];

        return getenv('APP_URL') . '/download?token=' . urlencode($this->encryption->encrypt($data));
    }

    private function buildCacheKey(string $pluginName, Binaries $binary): string
    {
        return $pluginName . '-' . $binary->version;
    }
}