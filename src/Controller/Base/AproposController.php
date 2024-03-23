<?php

namespace App\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/apropos')]
class AproposController extends AbstractController
{
    #[Route('/', name: 'apropos')]
    public function index(): Response
    {
        return $this->render('apropos/index.html.twig', [
            'controller_name' => 'AproposController',
        ]);
    }
}
