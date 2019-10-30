<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class Package extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(path="/packages/{name}", name="package", requirements={"name"="[A-Za-z0-9_.-]+(/[A-Za-z0-9_.-]+?)?/"})
     */
    public function package(string $name, Request $request): Response
    {
        $package = $this->entityManager->getRepository(\App\Entity\Package::class)->findOne($name);

        if (!$package) {
            throw new NotFoundHttpException(sprintf('Cannot find package by name %s', $name));
        }

        $selectedVersion = $request->query->get('version', $package->getNewestVersion());
        $foundVersion = $version = $package->getVersions()->current();

        if ($selectedVersion) {
            foreach ($package->getVersions() as $version) {
                if ($version->getVersion() === $selectedVersion) {
                    $foundVersion = $version;
                }
            }
        }

        return $this->render('package.html.twig', [
            'package' => $package,
            'version' => $foundVersion
        ]);
    }
}