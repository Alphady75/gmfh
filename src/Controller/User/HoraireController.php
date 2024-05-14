<?php

namespace App\Controller\User;

use App\Entity\Horaires;
use App\Repository\HorairesRepository;
use App\Repository\OffreRepository;
use App\Repository\horairesActiviteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class HoraireController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private HorairesRepository $horairesRepository, private OffreRepository $offreRepository)
    {
    }

    #[Route('/horaires/ajax/add/{label}/{offreID}', name: 'user_horare_add', methods: ['POST'])]
    public function add(string $label, $offreID): JsonResponse
    {
        $check = $this->horairesRepository->findOneBy([
            'name' => $label,
        ]);

        if ($check == null&& strlen($label) >= 5) {
            $horare = new Horaires();
            $horare->setOffre($this->offreRepository->find($offreID));
            $horare->setName(strtolower($label));
            $this->entityManager->persist($horare);
            $this->entityManager->flush();
            return new JsonResponse([
                'id' => $horare->getId()
            ]);
        } 
        
        return new JsonResponse([
            'message' => "Contenu trop court, 5 caractères en maximum"
        ]);
    }
}
