<?php

namespace App\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/page')]
class PageController extends AbstractController
{
    #[Route('/faqs', name: 'faqs')]
    public function faqs(): Response
    {
        return $this->render('page/faqs.html.twig', [

        ]);
    }

    #[Route('/cgv', name: 'cgv')]
    public function cgv(): Response
    {
        return $this->render('page/cgv.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/mentions-legales', name: 'mentions')]
    public function mentions(): Response
    {
        return $this->render('page/mentions.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/cgu', name: 'cgu')]
    public function cgu(): Response
    {
        return $this->render('page/cgu.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    #[Route('/politiques-de-confidentialite', name: 'politiques')]
    public function politiques(): Response
    {
        return $this->render('page/politiques.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }
}
