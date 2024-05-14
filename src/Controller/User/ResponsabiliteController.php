<?php

namespace App\Controller\User;

use App\Entity\Responsabilite;
use App\Repository\OffreRepository;
use App\Repository\ResponsabiliteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ResponsabiliteController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private ResponsabiliteRepository $responsabiliteRepository, private OffreRepository $offreRepository)
    {
    }

    #[Route('/responsabilites/ajax/add/{label}/{offreID}', name: 'user_responsabilite_add', methods: ['POST'])]
    public function add(string $label, $offreID): JsonResponse
    {
        $check = $this->responsabiliteRepository->findOneBy([
            'name' => $label,
        ]);

        if ($check == null && strlen($label) >= 5) {
            $responsabilite = new Responsabilite();
            $responsabilite->setOffre($this->offreRepository->find($offreID));
            $responsabilite->setName(strtolower($label));
            $this->entityManager->persist($responsabilite);
            $this->entityManager->flush();
            return new JsonResponse([
                'id' => $responsabilite->getId()
            ]);
        }
        
        return new JsonResponse([
            'message' => "Contenu trop court, 5 caractères en maximum"
        ]);
    }
}
