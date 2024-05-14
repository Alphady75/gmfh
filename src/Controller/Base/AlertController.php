<?php

namespace App\Controller\Base;

use App\Entity\Alert;
use App\Repository\AlertRepository;
use App\Repository\OffreRepository;
use App\Service\AlertService;
use App\Service\OffreService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/alertes')]
class AlertController extends AbstractController
{
    public function __construct(
        private OffreRepository $offreRepository,
        private EntityManagerInterface $entityManager,
        private OffreService $offreService,
        private AlertRepository $alertRepository,
        private AlertService $alertService
    ) {
    }

    #[Route('/active', name: 'active_alert', methods: ['GET', 'POST'])]
    public function activeAlert(Request $request): Response
    {
        $user = $this->getUser();
        $alert = $this->alertRepository->findBySecteurId($this->alertService->getSecteur()->getId());

        if (!$alert) {
            $alert = new Alert();
            $alert->setUser($user);
            $alert->setActive(true);
            $alert->setLu(true);
            $this->entityManager->persist($alert);
            $this->entityManager->flush();
        }

        $alert->setQuery($this->alertService->getQuery());
        $alert->addSecteur($this->alertService->getSecteur());
        $alert->setLocalisation($this->alertService->getLocalisation());
        $alert->setLieuTravail($this->alertService->getLieutravail());
        $alert->setTypeContrat($this->alertService->getTypeContrat());
        $alert->setPeriodicite($this->alertService->getPeriodicite());
        $alert->setMinSalaire($this->alertService->getMinSalaire());
        $alert->setMaxSalaire($this->alertService->getMaxSalaire());
        $alert->setActive(true);
        $alert->setLu(true);
        $this->entityManager->flush();

        $this->addFlash('success', "Alerte crée avec succès !");

        return $this->redirect($request->headers->get('referer'));
    }
}
