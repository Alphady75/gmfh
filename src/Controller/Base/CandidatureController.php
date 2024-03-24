<?php

namespace App\Controller\Base;

use App\Entity\Offre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/candidature')]
class CandidatureController extends AbstractController
{
    #[Route('/postuler/{slug}', name: 'base_candidature')]
    public function postuler(Offre $offre): Response
    {
        return $this->render('candidature/postuler.html.twig', [
            'offre' => $offre,
        ]);
    }
}
