<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Home extends AbstractController
{
    /**
     * @Route(path="/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home.html.twig');
    }
}
