<?php

namespace App\Controller\User;

use App\Entity\Langue;
use App\Repository\LangueRepository;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LangueController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private LangueRepository $langueRepository, private OffreRepository $offreRepository)
    {
    }

    #[Route('/langues/ajax/add/{label}/{offreID}', name: 'user_langue_add', methods: ['POST'])]
    public function add(string $label, $offreID): JsonResponse
    {
        $check = $this->langueRepository->findOneBy([
            'name' => $label,
        ]);

        if ($check == null && strlen($label) >= 5) {
            $langue = new Langue();
            $langue->setOffre($this->offreRepository->find($offreID));
            $langue->setName(strtolower($label));
            $this->entityManager->persist($langue);
            $this->entityManager->flush();
            return new JsonResponse([
                'id' => $langue->getId()
            ]);
        } 
        
        return new JsonResponse([
            'message' => "Contenu trop court, 5 caractères en maximum"
        ]);
    }
}
