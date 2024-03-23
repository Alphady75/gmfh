<?php

namespace App\Controller\User;

use App\Entity\Experiences;
use App\Repository\ExperiencesRepository;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ExperienceController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private ExperiencesRepository $experienceRepository, private OffreRepository $offreRepository)
    {
    }

    #[Route('/experience/ajax/add/{label}/{offreID}', name: 'user_experience_add', methods: ['POST'])]
    public function add(string $label, $offreID): JsonResponse
    {
        $check = $this->experienceRepository->findOneBy([
            'description' => $label,
        ]);

        if ($check == null) {
            $experience = new Experiences();
            $experience->setOffre($this->offreRepository->find($offreID));
            $experience->setName(strtolower($label));
            $this->entityManager->persist($experience);
            $this->entityManager->flush();
            return new JsonResponse([
                'id' => $experience->getId()
            ]);
        } 
    }
}
