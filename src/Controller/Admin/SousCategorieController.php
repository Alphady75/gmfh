<?php

namespace App\Controller\Admin;

use App\Entity\SousCategorie;
use App\Repository\CategorieRepository;
use App\Repository\SousCategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SousCategorieController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CategorieRepository $categorieRepository,
        private SousCategorieRepository $sousCategorieRepository
    ) {
    }

    #[Route('/souscategories/ajax/add/{label}/{categorieID}', name: 'admin_souscategorie_add', methods: ['POST'])]
    public function add(string $label, $categorieID): JsonResponse
    {
        $check = $this->sousCategorieRepository->findOneBy([
            'name' => $label,
        ]);

        if ($check == null) {
            $souscategorie = new SousCategorie();
            $souscategorie->setCategorie($this->categorieRepository->find($categorieID));
            $souscategorie->setName(strtolower($label));
            $souscategorie->setSlug(strtolower($label));
            $this->entityManager->persist($souscategorie);
            $this->entityManager->flush();
            return new JsonResponse([
                'id' => $souscategorie->getId()
            ]);
        }
    }
}
