<?php

namespace App\Controller\Admin;

use App\Entity\SousSecteursActivite;
use App\Repository\SecteursActiviteRepository;
use App\Repository\SousSecteursActiviteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class SousSecteursActivitesController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private SecteursActiviteRepository $secteursActiviteRepository,
        private SousSecteursActiviteRepository $sousSecteursActiviteRepository,
        private SluggerInterface $slugger
    ) {
    }

    #[Route('/soussecteurs/ajax/add/{label}/{secteurID}', name: 'admin_soussecteur_add', methods: ['POST'])]
    public function add(string $label, $secteurID): JsonResponse
    {
        $check = $this->sousSecteursActiviteRepository->findOneBy([
            'name' => $label,
        ]);

        if ($check == null) {
            $soussecteuractivite = new SousSecteursActivite();
            $soussecteuractivite->setSecteursActivite($this->secteursActiviteRepository->find($secteurID));
            $soussecteuractivite->setName(strtolower($label));
            $this->entityManager->persist($soussecteuractivite);
            $this->entityManager->flush();
            return new JsonResponse([
                'id' => $soussecteuractivite->getId()
            ]);
        }
    }
}
