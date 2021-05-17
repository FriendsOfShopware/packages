<?php

namespace App\Controller;

use App\Entity\Package;
use App\Entity\Version;
use App\Repository\PackageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

#[Route('/feeds')]
class Feed extends AbstractController
{
    /**
     * @param PackageRepository<Package> $packageRepository
     */
    public function __construct(private PackageRepository $packageRepository, private RouterInterface $router)
    {
    }

    #[Route('/package.{packageName}.{format}', name: 'feed_package', requirements: ['format' => '(rss|atom)', 'packageName' => '[A-Za-z0-9_.-]+/[A-Za-z0-9_.-]+'], methods: ['GET'])]
    public function packageAction(string $packageName, string $format): Response
    {
        $package = $this->packageRepository->findOne($packageName);

        if (!$package) {
            throw new NotFoundHttpException(sprintf('Cannot find package by name %s', $packageName));
        }

        /** @var Version $latestVersion */
        $latestVersion = $package->getVersions()->get(0);

        $packageLink = $this->router->generate('package', ['name' => $packageName . '/'], RouterInterface::ABSOLUTE_URL);

        $feed = [
            'title' => $packageName . ' releases',
            'description' => 'Latest releases on Packages of ' . $packageName,
            'link' => $packageLink,
            'date' => $latestVersion->getReleaseDate(),
            'items' => array_map(static fn (Version $version) => [
                'title' => sprintf('%s (%s)', $packageName, $version->getVersion()),
                'link' => $packageLink . '?version=' . $version->getVersion(),
                'description' => strip_tags((string) $version->getChangelog(), '<br>'),
                'date' => $version->getReleaseDate(),
            ], $package->getVersions()->toArray()),
        ];

        if ('rss' === $format) {
            $response = $this->render('feeds/rss.html.twig', [
                'feed' => $feed,
            ]);
        } else {
            $response = $this->render('feeds/atom.html.twig', [
                'feed' => $feed,
            ]);
        }

        $response->setSharedMaxAge(3_600);
        $response->headers->set('Content-Type', 'text/xml; charset=UTF-8');

        return $response;
    }
}
