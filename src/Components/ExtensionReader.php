<?php

namespace App\Components;

use App\Components\XmlReader\XmlPluginReader;
use App\Entity\DependencyPackage;
use App\Entity\Version;
use App\Repository\DependencyPackageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;

class ExtensionReader
{
    /**
     * @var string[]
     */
    private array $packages = [];

    /**
     * @param DependencyPackageRepository<DependencyPackage> $dependencyPackageRepository
     */
    public function __construct(private DependencyPackageRepository $dependencyPackageRepository, private EntityManagerInterface $entityManager)
    {
        $this->dependencyPackageRepository = $dependencyPackageRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return string[]
     */
    private function getPublicPackageNames(): array
    {
        if (\count($this->packages) !== 0) {
            return $this->packages;
        }

        $packageList = file_get_contents('https://packagist.org/packages/list.json');

        if ($packageList === false) {
            throw new \RuntimeException('Cannot download package list from packages.org');
        }

        $packages = json_decode($packageList, true, \JSON_THROW_ON_ERROR);

        return $this->packages = $packages['packageNames'];
    }

    public function readFromZip(string $content, Version $version): void
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'plugin');

        if ($tmpFile === false) {
            throw new \RuntimeException('Cannot generate tmp file');
        }

        file_put_contents($tmpFile, $content);

        $zip = new \ZipArchive();
        if ($zip->open($tmpFile) !== true) {
            throw new \InvalidArgumentException('Invalid zip file');
        }

        $zipIndex = $zip->statIndex(0);

        if ($zipIndex === false) {
            throw new \InvalidArgumentException('Invalid zip file');
        }

        $folderPath = str_replace('\\', '/', $zipIndex['name']);
        $pos = strpos($folderPath, '/');

        if ($pos === false) {
            throw new \InvalidArgumentException('Zip is wrong packed');
        }

        $path = substr($folderPath, 0, $pos);

        switch ($path) {
            case 'Frontend':
            case 'Backend':
            case 'Core':
                $zip->close();
                unlink($tmpFile);
                $version->setType('shopware-' . strtolower($path) . '-plugin');
                break;
            default:
                $this->readNewPluginSystem($zip, $tmpFile, $path, $version);
        }
    }

    private function readNewPluginSystem(\ZipArchive $archive, string $tmpFile, string $pluginName, Version $version): void
    {
        $version->setType('shopware-plugin');

        $reader = new XmlPluginReader();

        $extractLocation = sys_get_temp_dir() . '/' . uniqid('location', true);
        if (!mkdir($extractLocation) && !is_dir($extractLocation)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $extractLocation));
        }

        $archive->extractTo($extractLocation);
        $archive->close();

        if (file_exists($extractLocation . '/' . $pluginName . '/plugin.xml')) {
            $xml = $reader->read($extractLocation . '/' . $pluginName . '/plugin.xml');

            if (isset($xml['requiredPlugins'])) {
                foreach ($xml['requiredPlugins'] as $requiredPlugin) {
                    $requiredPluginName = strtolower($requiredPlugin['pluginName']);

                    if ($requiredPluginName === 'cron') {
                        continue;
                    }

                    if (isset($requiredPlugin['minVersion'])) {
                        $version->addRequire('store.shopware.com/' . $requiredPluginName, '>=' . $requiredPlugin['minVersion']);
                    } else {
                        $version->addRequire('store.shopware.com/' . $requiredPluginName, '*');
                    }
                }
            }

            if (isset($xml['label']['en'])) {
                $version->setDescription($xml['label']['en']);
            }

            if (isset($xml['license'])) {
                $version->setLicense($xml['license']);
            }

            if (isset($xml['link'])) {
                $version->setHomepage($xml['link']);
            }
        } elseif (file_exists($extractLocation . '/' . $pluginName . '/composer.json')) {
            $composerJsonBody = file_get_contents($extractLocation . '/' . $pluginName . '/composer.json');

            if ($composerJsonBody === false) {
                throw new \RuntimeException('Cannot find composer.json in plugin');
            }

            $composerJson = json_decode($composerJsonBody, true, 512, \JSON_THROW_ON_ERROR);

            if (isset($composerJson['type'])) {
                $version->setType($composerJson['type']);
            }

            if (isset($composerJson['description'])) {
                $version->setDescription($composerJson['description']);
            }

            if (isset($composerJson['extra'])) {
                $version->setExtra($composerJson['extra']);
            }

            if (isset($composerJson['homepage'])) {
                $version->setHomepage($composerJson['homepage']);
            }

            if (isset($composerJson['authors'])) {
                $version->setAuthors($composerJson['authors']);
            }

            if (isset($composerJson['require'])) {
                $version->setRequireSection($composerJson['require']);

                $this->checkForPrivateDependencies($composerJson['require'], $version, $extractLocation . '/' . $pluginName);
            } elseif ($version->getType() === 'shopware-platform-plugin') {
                $version->setRequireSection([]);
            }

            if (isset($composerJson['autoload'])) {
                $version->setAutoload($composerJson['autoload']);
            }

            if (isset($composerJson['autoload-dev'])) {
                $version->setAutoloadDev($composerJson['autoload-dev']);
            }

            $version->setComposerJson($composerJson);
        }

        $fs = new Filesystem();
        $fs->remove($extractLocation);
        unlink($tmpFile);
    }

    /**
     * @param array<string, string> $require
     */
    private function checkForPrivateDependencies(array $require, Version $version, string $extractLocation): void
    {
        // Has no vendor directory
        if (!file_exists($extractLocation . '/vendor/composer/installed.json')) {
            return;
        }

        $installedDeps = $this->parseInstalledPackages($extractLocation . '/vendor/composer/installed.json');

        $publicPackages = $this->getPublicPackageNames();

        foreach ($require as $key => $_) {
            // Runtime deps
            if (str_starts_with($key, 'ext') || $key === 'php') {
                continue;
            }

            // Is listed on packagist.org
            if (\in_array($key, $publicPackages)) {
                continue;
            }

            // Is not included in vendor
            if (!file_exists($extractLocation . '/vendor/' . $key) || !file_exists($extractLocation . '/vendor/' . $key . '/composer.json')) {
                continue;
            }

            $dependencyComposerJsonContent = file_get_contents($extractLocation . '/vendor/' . $key . '/composer.json');
            if ($dependencyComposerJsonContent === false) {
                continue;
            }

            $vendorLibraryComposerJson = json_decode($dependencyComposerJsonContent, true);

            // The package needs an name
            if (!isset($vendorLibraryComposerJson['name'])) {
                continue;
            }

            // The package is not listed in composer installed
            if (!isset($installedDeps[$vendorLibraryComposerJson['name']])) {
                continue;
            }

            if (!empty($vendorLibraryComposerJson['require'])) {
                $this->checkForPrivateDependencies($vendorLibraryComposerJson['require'], $version, $extractLocation);
            }

            $dependencyPackage = $this->dependencyPackageRepository->findOneBy([
                'name' => $vendorLibraryComposerJson['name'],
                'version' => $installedDeps[$vendorLibraryComposerJson['name']]
            ]);

            if (!$dependencyPackage) {
                $dependencyPackage = new DependencyPackage();
                $dependencyPackage->setName($vendorLibraryComposerJson['name']);
                $dependencyPackage->setVersion($installedDeps[$vendorLibraryComposerJson['name']]);

                // Ensure the package has an version
                $vendorLibraryComposerJson['version'] = $installedDeps[$vendorLibraryComposerJson['name']];
                $dependencyPackage->setComposerJson($vendorLibraryComposerJson);

                $this->entityManager->persist($dependencyPackage);

                $zip = new \ZipArchive();
                $folder = \dirname($dependencyPackage->getPath());

                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                $zip->open($dependencyPackage->getPath(), \ZipArchive::CREATE);
                $dependencyPath = $extractLocation . '/vendor/' . $key;

                $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dependencyPath));

                /** @var \SplFileInfo $file */
                foreach ($files as $file) {
                    if ($file->isDir()) {
                        continue;
                    }

                    $filePath = (string) $file->getRealPath();
                    $relativePath = substr($filePath, \strlen($dependencyPath) + 1);

                    $zip->addFile($filePath, $relativePath);
                }

                $zip->close();
            }

            $version->addDependencyPackage($dependencyPackage);
        }
    }

    /**
     * @return array<string, string>
     */
    private function parseInstalledPackages(string $path): array
    {
        $content = file_get_contents($path);

        if ($content === false) {
            throw new \RuntimeException('Cannot read from path' . $path);
        }

        $deps = json_decode($content, true, \JSON_THROW_ON_ERROR);
        $indexedDeps = [];

        if (isset($deps['packages'])) {
            $deps = $deps['packages'];
        }

        foreach ($deps as $dep) {
            if (!isset($dep['name'])) {
                continue;
            }

            $name = (string) $dep['name'];
            $indexedDeps[$name] = $dep['version'];
        }

        return $indexedDeps;
    }
}
