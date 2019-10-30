<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Search extends AbstractController
{
    /**
     * @Route(path="/search", name="search")
     */
    public function search(): Response
    {
        return $this->render('base.html.twig');
    }
}