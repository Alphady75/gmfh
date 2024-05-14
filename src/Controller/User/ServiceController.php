<?php

namespace App\Controller\User;

use App\Entity\Service;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private ServiceRepository $serviceRepository)
    {
    }

    #[Route('/service/ajax/add/{label}', name: 'user_service_add', methods: ['POST'])]
    public function add(string $label): JsonResponse
    {
        $check = $this->serviceRepository->findOneBy([
            'name' => $label,
        ]);

        if ($check == null && strlen($label) >= 5) {
            $service = new Service();
            $service->setUser($this->getUser());
            $service->setName(strtolower($label));
            $this->entityManager->persist($service);
            $this->entityManager->flush();
            return new JsonResponse([
                'id' => $service->getId()
            ]);
        } 
        
        return new JsonResponse([
            'message' => "Contenu trop court, 5 caractères en maximum"
        ]);
    }
}
