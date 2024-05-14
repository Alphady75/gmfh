<?php

namespace App\Controller\User;

use App\Entity\SousSecteursActivite;
use App\Repository\OffreRepository;
use App\Repository\SousSecteursActiviteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SousSecteursActiviteController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private SousSecteursActiviteRepository $soussecteurActiviteRepository, private OffreRepository $offreRepository)
    {
    }

    #[Route('/soussecteur/ajax/add/{label}/{offreId}', name: 'user_soussecteur_add', methods: ['POST'])]
    public function add(string $label, $offreId): JsonResponse
    {
        $check = $this->soussecteurActiviteRepository->findOneBy([
            'name' => $label,
        ]);

        if ($check == null && strlen($label) >= 5) {
            $offre = $this->offreRepository->find($offreId);
            $soussecteur = new SousSecteursActivite();
            $soussecteur->addOffre($offre);
            $soussecteur->setName(strtolower($label));
            $this->entityManager->persist($soussecteur);
            $this->entityManager->flush();
            return new JsonResponse([
                'id' => $soussecteur->getId()
            ]);
        } 
        
        return new JsonResponse([
            'message' => "Contenu trop court, 5 caractères en maximum"
        ]);
    }
}
