<?php

namespace App\Controller\Base;

use App\Entity\Dto\Offre as DtoOffre;
use App\Entity\Offre;
use App\Form\Dto\VisiteOffreType;
use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recrutement')]
class OffreController extends AbstractController
{
    public function __construct(private OffreRepository $offreRepository)
    {
        
    }

    #[Route('/', name: 'offres')]
    public function liste(Request $request): Response
    {
        $search = new DtoOffre();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(VisiteOffreType::class, $search);
        $form->handleRequest($request);
        $offres = $this->offreRepository->visiteurSearch($search, $this->getUser());

        return $this->render('offre/index.html.twig', [
            'offres' => $offres,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{slug}', name: 'offre_details')]
    public function details(Offre $offre, Request $request): Response
    {
        return $this->render('offre/details.html.twig', [
            'offre' => $offre,
        ]);
    }
}
