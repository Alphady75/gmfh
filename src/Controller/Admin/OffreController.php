<?php

namespace App\Controller\Admin;

use App\Entity\Dto\Offre as DtoOffre;
use App\Entity\Offre;
use App\Form\Dto\OffreType;
use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/offre')]
class OffreController extends AbstractController
{
    public function __construct(private OffreRepository $offreRepository)
    {
        
    }
    #[Route('/', name: 'admin_offres', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $search = new DtoOffre();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(OffreType::class, $search);
        $form->handleRequest($request);
        $offres = $this->offreRepository->adminFilter($search, $this->getUser());

        return $this->render('user/offre/index.html.twig', [
            'offres' => $offres,
            'form' => $form->createView(),
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
