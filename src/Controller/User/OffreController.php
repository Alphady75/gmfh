<?php

namespace App\Controller\User;

use App\Entity\Dto\Offre as DtoOffre;
use App\Entity\Offre;
use App\Entity\User;
use App\Form\Dto\OffreType as DtoOffreType;
use App\Form\User\OffreType;
use App\Repository\AlertRepository;
use App\Repository\OffreRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/partenaire/offres')]
class OffreController extends AbstractController
{
    public function __construct(
        private SluggerInterface $slugger,
        private OffreRepository $offreRepository,
        private EntityManagerInterface $entityManager,
        private AlertRepository $alertRepository,
        private MailerService $mailerService
    ) {
    }

    #[Route('/liste', name: 'user_offre', methods: ['GET'])]
    public function index(OffreRepository $offreRepository, Request $request): Response
    {
        /** @var User */
        $user = $this->getUser();

        $search = new DtoOffre();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(DtoOffreType::class, $search);
        $form->handleRequest($request);
        $offres = $offreRepository->auteurFilter($search, $this->getUser());
        if ($user->getCompte() == 'ADMINISTRATEUR') {
            $offres = $offreRepository->adminFilter($search);
        }

        return $this->render('user/offre/index.html.twig', [
            'offres' => $offres,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/nouvelle-offre', name: 'user_offre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $offre = $this->createIfNotExist($user);

        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $offre->setSlug(strtolower($this->slugger->slug($form->get('name')->getData())));
            $offre->setUser($this->getUser());
            $offre->setBloquer(false);
            $offre->setComplet(true);
            $offre->setStatus('Inactif');
            $entityManager->persist($offre);
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été enregistrer');

            # Get Alerte emploi
            $alerts = $this->alertRepository->findBySecteur(
                $form->get('secteuractivite')->getData()->getId(),
                $form->get('intitulePoste')->getData()
            );
            # Send Alerte Notification to user
            foreach ($alerts as $alert) {
                $this->mailerService->sendAlertEmail($offre, $alert->getUser());
            }

            return $this->redirectToRoute('user_offre_show', ['slug' => $offre->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/appercu/{slug}', name: 'user_offre_show', methods: ['GET'])]
    public function show(Offre $offre): Response
    {
        return $this->render('user/offre/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    #[Route('/{slug}/edit', name: 'user_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Le contenu a bien été mise à jour');
            return $this->redirectToRoute('user_offre_show', ['slug' => $offre->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'user_offre_delete', methods: ['POST'])]
    public function delete(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $offre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($offre);
            $entityManager->flush();
            $this->addFlash('success', 'Le contenu a bien été supprimer');
        }

        return $this->redirectToRoute('user_offre', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/masquer/{id}', name: 'user_offre_masquer', methods: ['POST'])]
    public function masquer(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('masquer' . $offre->getId(), $request->request->get('_token'))) {
            $offre->setStatus('Inactif');
            $entityManager->flush();
            $this->addFlash('success', 'Le contenu a bien été masquer');
        }

        return $this->redirectToRoute('user_offre_show', ['slug' => $offre->getSlug()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/publier/{id}', name: 'user_offre_publier', methods: ['POST'])]
    public function publier(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('publier' . $offre->getId(), $request->request->get('_token'))) {
            $offre->setStatus('Actif');
            $entityManager->flush();
            $this->addFlash('success', 'Le contenu a bien été publier');
        }

        return $this->redirectToRoute('user_offre_show', ['slug' => $offre->getSlug()], Response::HTTP_SEE_OTHER);
    }

    /**
     * Create if does not exist
     *
     * @param Offre $offre
     * @param User $user
     * @return Offre
     */
    public function createIfNotExist(User $user)
    {
        $offre = $this->offreRepository->findOneBy(['user' => $user, 'complet' => 0]);

        if (!$offre) {

            $offre = new Offre();
            $offre->setUser($user);
            $offre->setSecteuractivite($user->getSecteuractivite());
            $offre->setSoussecteuractivite($user->getsoussecteuractivite());
            $offre->setComplet(false);
            $offre->setBoosted(false);
            $this->entityManager->persist($offre);
            $this->entityManager->flush();
        }

        return $offre;
    }
}
