<?php

namespace App\Controller;

use App\Entity\Package;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Browse extends AbstractController
{
    /**
     * @Route(path="/browse", name="browse")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $packages = $entityManager->getRepository(Package::class)->findAllPackages();

        return $this->render('browse.html.twig', [
            'packages' => $packages
        ]);
    }
}