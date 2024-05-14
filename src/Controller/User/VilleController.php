<?php

namespace App\Controller\User;

use App\Entity\Ville;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class VilleController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private VilleRepository $villeRepository)
    {
    }

    #[Route('/ville/ajax/add/{label}', name: 'user_ville_add', methods: ['POST'])]
    public function add(string $label): JsonResponse
    {
        $check = $this->villeRepository->findOneBy([
            'name' => $label,
        ]);

        if ($check == null && strlen($label) >= 5) {
            $ville = new ville();
            $ville->setUser($this->getUser());
            $ville->setName(strtolower($label));
            $this->entityManager->persist($ville);
            $this->entityManager->flush();
            return new JsonResponse([
                'id' => $ville->getId()
            ]);
        } 
        
        return new JsonResponse([
            'message' => "Contenu trop court, 5 caractères en maximum"
        ]);
    }
}
