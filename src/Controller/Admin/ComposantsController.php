<?php

namespace App\Controller\Admin;

use App\Entity\Composants;
use App\Repository\ComposantsRepository;
use App\Repository\StripeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ComposantsController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private StripeRepository $stripeRepository,
        private ComposantsRepository $composantsRepository
    ) {
    }

    #[Route('/composants/ajax/add/{label}/{stripeID}', name: 'admin_composant_add', methods: ['POST'])]
    public function add(string $label, $stripeID): JsonResponse
    {
        $check = $this->composantsRepository->findOneBy([
            'name' => $label,
        ]);

        if ($check == null) {
            $composant = new Composants();
            $composant->setStripe($this->stripeRepository->find($stripeID));
            $composant->setName(strtolower($label));
            $this->entityManager->persist($composant);
            $this->entityManager->flush();
            return new JsonResponse([
                'id' => $composant->getId()
            ]);
        }
    }
}
