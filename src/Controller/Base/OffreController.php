<?php

namespace App\Controller\Base;

use App\Entity\Dto\Offre as DtoOffre;
use App\Entity\Offre;
use App\Entity\SecteursActivite;
use App\Entity\Signaler;
use App\Form\Base\SignalerOffreType;
use App\Form\Dto\VisiteOffreType;
use App\Repository\AlertRepository;
use App\Repository\OffreRepository;
use App\Service\AlertService;
use App\Service\OffreService;
use App\Service\SessionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recrutement')]
class OffreController extends AbstractController
{
    public function __construct(
        private OffreRepository $offreRepository,
        private EntityManagerInterface $entityManager,
        private OffreService $offreService,
        private AlertRepository $alertRepository,
        private AlertService $alertService
    ) {
    }

    #[Route('/rechercher-une-offre', name: 'offres_recherche', methods: ['GET'])]
    public function rechercher(Request $request, ): Response
    {
        $search = new DtoOffre();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(VisiteOffreType::class, $search, [
            'action' => $this->generateUrl('offres'),
            'method' => 'GET',
        ]);
        $form->handleRequest($request);

        # Get Booster offre
        $offres = $this->offreRepository->findBoosted(20);

        return $this->render('offre/rechercher.html.twig', [
            'form' => $form->createView(),
            'offres' => $offres,
        ]);
    }

    #[Route('/toute-les-offres', name: 'offres')]
    public function liste(Request $request): Response
    {
        $clear = false;
        # Vérifier s'il y a des paramètres dans l'URL
        if ($request->query->count() > 0)
            $clear = true;

        $search = new DtoOffre();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(VisiteOffreType::class, $search);
        $form->handleRequest($request);
        $offres = $this->offreRepository->visiteurSearch($search);

        # Récupérer les données de recherche depuis la session
        $previousSearches = [
            'keywords' => $this->alertService->getQuery(),
            'secteur' => $this->alertService->getSecteur(),
            'localisation' => $this->alertService->getLocalisation(),
        ];

        return $this->render('offre/index.html.twig', [
            'clear' => $clear,
            'offres' => $offres,
            'previousSearches' => $previousSearches,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/secteur-activite/{slug}', name: 'offres_secteur')]
    public function secteurActivite(SecteursActivite $secteur, Request $request): Response
    {
        $clear = false;
        # Vérifier s'il y a des paramètres dans l'URL
        if ($request->query->count() > 0)
            $clear = true;

        $search = new DtoOffre();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(VisiteOffreType::class, $search);
        $form->handleRequest($request);
        $offres = $this->offreRepository->visiteurSearchBySecteurActivite($search, $secteur);

        return $this->render('offre/secteur.html.twig', [
            'clear' => $clear,
            'secteur' => $secteur,
            'offres' => $offres,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/offres/{slug}', name: 'offre_details', methods: ['POST', 'GET'])]
    public function details(Offre $offre, Request $request): Response
    {
        # Check vue
        $this->offreService->checkVueOffre($offre);

        $signaler = new Signaler();
        $signalerForm = $this->createForm(SignalerOffreType::class, $signaler, []);
        $signalerForm->handleRequest($request);

        if ($signalerForm->isSubmitted() && $signalerForm->isValid()) {
            if (!$this->getUser())
                return $this->redirectToRoute('app_login');

            $signaler->setUser($this->getUser());
            $signaler->setOffre($offre);
            $this->entityManager->persist($signaler);
            $this->entityManager->flush();
            $this->addFlash('success', "Votre requette a  bien été envoyée");
            return $this->redirectToRoute('offre_details', ['slug' => $offre->getSlug()]);
        }

        return $this->render('offre/details.html.twig', [
            'offre' => $offre,
            'signalerForm' => $signalerForm->createView(),
        ]);
    }

    #[Route('/remove/{value}', name: 'offre_remove', methods: ['POST', 'GET'])]
    public function removeToSearch(Request $request, $value): Response
    {
        $this->alertService->remove($value);

        return $this->redirect($request->headers->get('referer'));
    }
}
