<?php

namespace App\Controller;

use App\Entity\Version;
use App\Repository\PackageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/feeds")
 */
class Feed extends AbstractController
{
    private EntityManagerInterface $entityManager;

    private RouterInterface $router;

    public function __construct(EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    /**
     * @Route(
     *     "/package.{packageName}.{format}",
     *     name="feed_package",
     *     requirements={"format"="(rss|atom)", "packageName"="[A-Za-z0-9_.-]+/[A-Za-z0-9_.-]+"},
     *     methods={"GET"}
     * )
     */
    public function packageAction(string $packageName, string $format): Response
    {
        /** @var PackageRepository $repository */
        $repository = $this->entityManager->getRepository(\App\Entity\Package::class);

        $package = $repository->findOne($packageName);

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
                'description' => strip_tags($version->getChangelog(), '<br>'),
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
