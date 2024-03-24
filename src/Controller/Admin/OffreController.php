<?php

namespace App\Controller\Admin;

use App\Entity\Offre;
use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/offre')]
class OffreController extends AbstractController
{
    #[Route('/', name: 'admin_offres', methods: ['GET'])]
    public function index(OffreRepository $offreRepository): Response
    {
        return $this->render('admin/offre/index.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'admin_offre_show', methods: ['GET'])]
    public function show(Offre $offre): Response
    {
        return $this->render('admin/offre/show.html.twig', [
            'offre' => $offre,
        ]);
    }
}
