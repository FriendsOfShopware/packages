<?php

namespace App\Controller;

use App\Components\Api\Client;
use App\Components\Encryption;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Download
{
    /**
     * @var Encryption
     */
    private $encryption;

    /**
     * @var Client
     */
    private $client;

    public function __construct(Encryption $encryption, Client $client)
    {
        $this->encryption = $encryption;
        $this->client = $client;
    }

    /**
     * @Route(path="/download")
     */
    public function download(Request $request): Response
    {
        $token = $request->query->get('token');

        if (empty($token)) {
            return new JsonResponse([
                'Invalid token',
            ], Response::HTTP_FORBIDDEN);
        }

        try {
            $data = $this->encryption->decrypt($token);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'message' => 'Invalid encryption',
            ], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $token = $this->client->login($data['username'], $data['password']);
        } catch (\Throwable $e) {
            return new JsonResponse([
                'message' => 'Invalid token',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $shops = $this->client->shops($token);
        $foundShop = null;

        foreach ($shops as $shop) {
            if ($shop->domain === $data['domain']) {
                $foundShop = $shop;
                break;
            }
        }

        if (!$foundShop) {
            throw new \RuntimeException('Cannot find shop');
        }

        $token->setShop($foundShop);
        $this->client->useToken($token);

        $downloadLink = $this->client->fetchDownloadLink($data['filePath']);

        if (isset($data['needsRepack'])) {
            return $this->repackZip($downloadLink);
        }

        return new RedirectResponse($downloadLink);
    }

    private function repackZip(string $url): Response
    {
        $downloadCurl = curl_init($url);
        curl_setopt($downloadCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($downloadCurl, CURLOPT_FOLLOWLOCATION, true);
        $zipContent = curl_exec($downloadCurl);

        if (curl_getinfo($downloadCurl, CURLINFO_RESPONSE_CODE) !== 200) {
            return new Response($zipContent, 403);
        }

        curl_close($downloadCurl);

        $tmpFile = tempnam(sys_get_temp_dir(), 'plugin');
        file_put_contents($tmpFile, $zipContent);

        $extractLocation = sys_get_temp_dir() . '/' . uniqid('location', true);
        if (!mkdir($extractLocation) && !is_dir($extractLocation)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $extractLocation));
        }

        $zip = new \ZipArchive();
        $zip->open($tmpFile);

        for ($i = 0; $i < $zip->numFiles; ++$i) {
            $filename = $zip->getNameIndex($i);

            if (strpos($filename, 'Backend/') === 0) {
                $filename = substr($filename, 8);
            }

            if (strpos($filename, 'Core/') === 0) {
                $filename = substr($filename, 5);
            }

            if (strpos($filename, 'Frontend/') === 0) {
                $filename = substr($filename, 9);
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
