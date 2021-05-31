<?php

namespace App\Controller;

use App\Components\Api\Client;
use App\Components\Encryption;
use App\Components\RequestContextResolver;
use App\Entity\DependencyPackage;
use App\Exception\AccessDeniedToDownloadPluginHttpException;
use App\Exception\InvalidTokenHttpException;
use App\Repository\DependencyPackageRepository;
use RuntimeException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;
use ZipArchive;

class Download
{
    /**
     * @param DependencyPackageRepository<DependencyPackage> $dependencyPackageRepository
     */
    public function __construct(private Encryption $encryption, private Client $client, private RequestContextResolver $resolver, private DependencyPackageRepository $dependencyPackageRepository)
    {
    }

    #[Route('/download')]
    public function download(Request $request): Response
    {
        $this->resolver->resolve($request);

        $downloadToken = $request->query->get('token');

        if (empty($downloadToken) || !is_string($downloadToken)) {
            throw new InvalidTokenHttpException();
        }

        try {
            $downloadInformation = $this->encryption->decrypt($downloadToken);
        } catch (Throwable) {
            throw new InvalidTokenHttpException();
        }

        $downloadLink = $this->client->fetchDownloadLink($downloadInformation['filePath']);

        if ($downloadLink === null) {
            throw new AccessDeniedToDownloadPluginHttpException();
        }

        if (isset($downloadInformation['needsRepack'])) {
            return $this->repackZip($downloadLink);
        }

        return new RedirectResponse($downloadLink);
    }

    #[Route('/download/dependency')]
    public function dependencyDownload(Request $request): Response
    {
        $tokenValue = $request->query->get('token');

        if (empty($tokenValue) || !is_string($tokenValue)) {
            throw new InvalidTokenHttpException();
        }

        try {
            $credentials = $this->encryption->decrypt($tokenValue);
        } catch (Throwable) {
            throw new InvalidTokenHttpException();
        }

        if (!isset($credentials['dependencyId'])) {
            throw new InvalidTokenHttpException();
        }

        $package = $this->dependencyPackageRepository->find($credentials['dependencyId']);

        if ($package === null) {
            throw new NotFoundHttpException('Cannot find package');
        }

        return new BinaryFileResponse($package->getPath());
    }

    private function repackZip(string $url): Response
    {
        /** @var \CurlHandle $downloadCurl */
        $downloadCurl = curl_init($url);

        curl_setopt($downloadCurl, \CURLOPT_RETURNTRANSFER, true);
        curl_setopt($downloadCurl, \CURLOPT_FOLLOWLOCATION, true);
        $zipContent = curl_exec($downloadCurl);

        if (curl_getinfo($downloadCurl, \CURLINFO_RESPONSE_CODE) !== 200) {
            return new Response((string) $zipContent, 403);
        }

        curl_close($downloadCurl);

        $tmpFile = tempnam(sys_get_temp_dir(), 'plugin');

        if ($tmpFile == false) {
            throw new RuntimeException('Cannot create temp file');
        }

        file_put_contents($tmpFile, $zipContent);

        $extractLocation = sys_get_temp_dir() . '/' . uniqid('location', true);
        if (!mkdir($extractLocation) && !is_dir($extractLocation)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $extractLocation));
        }

        $zip = new ZipArchive();
        $zip->open($tmpFile);

        for ($i = 0; $i < $zip->numFiles; ++$i) {
            $filename = $zip->getNameIndex($i);

            if ($filename === false) {
                continue;
            }

            if (str_starts_with($filename, 'Backend/')) {
                $filename = substr($filename, 8);
            }

            if (str_starts_with($filename, 'Core/')) {
                $filename = substr($filename, 5);
            }

            if (str_starts_with($filename, 'Frontend/')) {
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
