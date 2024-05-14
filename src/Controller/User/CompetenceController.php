<?php

namespace App\Controller\User;

use App\Entity\Competence;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CompetenceController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private CompetenceRepository $competenceRepository)
    {
    }

    #[Route('/competence/ajax/add/{label}', name: 'user_competence_add', methods: ['POST'])]
    public function add(string $label): JsonResponse
    {
        $check = $this->competenceRepository->findOneBy([
            'name' => $label,
        ]);

        if ($check == null && strlen($label) >= 5) {
            $competence = new Competence();
            $competence->setUser($this->getUser());
            $competence->setName(strtolower($label));
            $this->entityManager->persist($competence);
            $this->entityManager->flush();
            return new JsonResponse([
                'id' => $competence->getId()
            ]);
        } 
        
        return new JsonResponse([
            'message' => "Contenu trop court, 5 caractères en maximum"
        ]);
    }
}
