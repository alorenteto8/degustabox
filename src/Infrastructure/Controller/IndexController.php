<?php

namespace App\Infrastructure\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/app', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('app/index.html.twig');
    }
}