<?php

namespace App\Controller\User;

use App\Entity\SecteursActivite;
use App\Repository\OffreRepository;
use App\Repository\SecteursActiviteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SecteursActiviteController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private SecteursActiviteRepository $secteursActiviteRepository, private OffreRepository $offreRepository)
    {
    }

    #[Route('/secteur/ajax/add/{label}/{offreId}', name: 'user_secteur_add', methods: ['POST'])]
    public function add(string $label, $offreId): JsonResponse
    {
        $check = $this->secteursActiviteRepository->findOneBy([
            'name' => $label,
        ]);

        if ($check == null && strlen($label) >= 5) {
            $offre = $this->offreRepository->find($offreId);
            $secteur = new SecteursActivite();
            $secteur->addOffre($offre);
            $secteur->setName(strtolower($label));
            $this->entityManager->persist($secteur);
            $this->entityManager->flush();
            return new JsonResponse([
                'id' => $secteur->getId()
            ]);
        } 
        
        return new JsonResponse([
            'message' => "Contenu trop court, 5 caractères en maximum"
        ]);
    }
}
