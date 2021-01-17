<?php

namespace App\Controller;

use App\Components\Api\Client;
use App\Components\Encryption;
use App\Exception\AccessDeniedToDownloadPluginHttpException;
use App\Exception\InvalidShopGivenHttpException;
use App\Exception\InvalidTokenHttpException;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Throwable;
use ZipArchive;

class Download
{
    public function __construct(private Encryption $encryption, private Client $client, private CacheInterface $cache)
    {
    }

    #[\Symfony\Component\Routing\Annotation\Route('/download')]
    public function download(Request $request): Response
    {
        $tokenValue = $request->query->get('token');

        if (empty($tokenValue)) {
            throw new InvalidTokenHttpException();
        }

        try {
            $credentials = $this->encryption->decrypt($tokenValue);
        } catch (Throwable) {
            throw new InvalidTokenHttpException();
        }

        $cacheKey = \md5($credentials['username'] . $credentials['password'] . $credentials['domain'] . ($credentials['userId'] ?? ''));
        $token = $this->cache->get(\md5($cacheKey), function (ItemInterface $item) use ($credentials) {
            if (empty($credentials)) {
                throw new InvalidTokenHttpException();
            }

            $token = $this->client->login($credentials['username'], $credentials['password']);
            $item->expiresAt($token->getExpire());

            if (isset($credentials['userId'])) {
                $token->setUserId($credentials['userId']);
            }

            foreach ($this->client->memberShips($token) as $memberShip) {
                if ($memberShip->company->id === $token->getUserId()) {
                    $token->setMemberShip($memberShip);
                }
            }

            $shops = $this->client->shops($token);
            $foundShop = null;

            foreach ($shops as $shop) {
                if ($shop->domain === $credentials['domain']) {
                    $foundShop = $shop;
                    break;
                }
            }

            if (!$foundShop) {
                throw new InvalidShopGivenHttpException($credentials['domain']);
            }

            $token->setShop($foundShop);

            return $token;
        });

        $this->client->useToken($token);

        $downloadLink = $this->client->fetchDownloadLink($credentials['filePath']);

        if ($downloadLink === null) {
            throw new AccessDeniedToDownloadPluginHttpException();
        }

        if (isset($credentials['needsRepack'])) {
            return $this->repackZip($downloadLink);
        }

        return new RedirectResponse($downloadLink);
    }

    private function repackZip(string $url): Response
    {
        /** @var \CurlHandle $downloadCurl */
        $downloadCurl = \curl_init($url);

        \curl_setopt($downloadCurl, \CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($downloadCurl, \CURLOPT_FOLLOWLOCATION, true);
        $zipContent = \curl_exec($downloadCurl);

        if (\curl_getinfo($downloadCurl, \CURLINFO_RESPONSE_CODE) !== 200) {
            return new Response((string) $zipContent, 403);
        }

        \curl_close($downloadCurl);

        $tmpFile = \tempnam(\sys_get_temp_dir(), 'plugin');

        if ($tmpFile == false) {
            throw new RuntimeException('Cannot create temp file');
        }

        \file_put_contents($tmpFile, $zipContent);

        $extractLocation = \sys_get_temp_dir() . '/' . \uniqid('location', true);
        if (!\mkdir($extractLocation) && !\is_dir($extractLocation)) {
            throw new RuntimeException(\sprintf('Directory "%s" was not created', $extractLocation));
        }

        $zip = new ZipArchive();
        $zip->open($tmpFile);

        for ($i = 0; $i < $zip->numFiles; ++$i) {
            $filename = $zip->getNameIndex($i);

            if ($filename === false) {
                continue;
            }

            if (\str_starts_with($filename, 'Backend/')) {
                $filename = \substr($filename, 8);
            }

            if (\str_starts_with($filename, 'Core/')) {
                $filename = \substr($filename, 5);
            }

            if (\str_starts_with($filename, 'Frontend/')) {
                $filename = \substr($filename, 9);
            }

            if ($filename === '') {
                $zip->deleteIndex($i);
                continue;
            }

            $zip->renameIndex($i, $filename);
        }

        $zip->close();

        return new BinaryFileResponse($tmpFile);
    }
}
