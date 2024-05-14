<?php

namespace App\Controller\Client;

use App\Entity\Activite;
use App\Form\ChangePasswordFormType;
use App\Form\User\BecomeEntrepriseType;
use App\Form\User\BecomeParticulierOrEntrepriseType;
use App\Form\User\ProfilPersonnelType;
use App\Repository\AlertRepository;
use App\Repository\CandidatureRepository;
use App\Repository\FavorisRepository;
use App\Repository\SignalerRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client-espace')]
class EspaceClientController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MailerService $mailer,
        private FavorisRepository $favorisRepository,
        private CandidatureRepository $candidatureRepository,
        private AlertRepository $alertRepository,
        private SignalerRepository $signalerRepository
    ) {
    }

    #[Route('/tableau-de-bord', name: 'client_dashboard')]
    public function espaceMembre(): Response
    {   
        /** @var User */
        $user = $this->getUser();
        $favoris = $this->favorisRepository->findBy(['user' => $user]);
        $candidatures = $this->candidatureRepository->findBy(['user' => $user], ['created' => 'DESC'], 6);
        $alerts = $this->alertRepository->findBy(['user' => $user], ['created' => 'DESC'], 6);
        $signalers = $this->signalerRepository->findBy(['user' => $user], ['created' => 'DESC'], 6);

        return $this->render('client/dashboard/index.html.twig', [
            'favoris' => $favoris,
            'candidatures' => $candidatures,
            'alerts' => $alerts,
            'signalers' => $signalers,
        ]);
    }

    #[Route('/completer-mon-compte', name: 'client_complete_compte')]
    public function completeCompte(Request $request): Response
    {
        /** @var User */
        $user = $this->getUser();

        $form = $this->createForm(ProfilPersonnelType::class, $user, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setCompleted(true);
            $this->entityManager->flush();

            $this->addFlash('success', "Votre compte à bien été mis à jour");
            return $this->redirectToRoute('client_dashboard', [], 301);
        }

        return $this->render('client/espace/complete_compte.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/changer-statut-compte', name: 'client_change_compte', methods: ['POST', 'GET'])]
    public function changeCompteStatut(Request $request): Response
    {
        /** @var User */
        $user = $this->getUser();

        $formType = BecomeParticulierOrEntrepriseType::class;
        $currentStatut = $user->getCompte();

        # Check user compte
        if ($currentStatut == "PARTICULIER")
            $formType = BecomeEntrepriseType::class;

        $form = $this->createForm($formType, $user, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newStatut = $form->get('compte')->getData();

            if ($newStatut == "PARTICULIER") {
                $user->setRoles(['ROLE_PARTICULIER']);
            }

            if ($newStatut == "ENTREPRISE") {
                $user->setRoles(['ROLE_ENTREPRISE']);
            }

            # Reset completed
            $user->setCompleted(false);
            $this->entityManager->flush();

            # Save activity
            $activite = new Activite();
            $activite->setObjet('Changement de statut de votre compte');
            $activite->setUser($user);
            $activite->setRaison($form->get('raisons')->getData());
            $this->entityManager->persist($activite);
            $this->entityManager->flush();

            # Send mail to User
            $this->mailer->sendStatutCompteChanged($user->getEmail(), $currentStatut, $newStatut, $activite);

            # Redirect to login to config compte
            return $this->redirectToRoute('app_logout');
        }

        return $this->render('client/espace/change_compte.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/mettre-a-jour-mes-acces', name: 'client_update_acces')]
    public function updateProfile(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordFormType::class, $user, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode(hash) the plain password, and set it.
            $encodedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $this->entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a bien été réinitialiser');
            return $this->redirectToRoute('app_logout');
        }

        return $this->render('client/espace/access.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }
}
