<?php

namespace App\Controller\Base;

use App\Entity\Avis;
use App\Entity\Candidature;
use App\Entity\Message;
use App\Entity\Offre;
use App\Entity\User;
use App\Form\Base\AvisType;
use App\Form\Candidature\PostulerType;
use App\Form\Candidature\EntretienType;
use App\Form\Candidature\EvaluationType;
use App\Form\Candidature\RejetType;
use App\Form\Candidature\SelectionType;
use App\Form\Candidature\TrieType;
use App\Form\Base\MessageType;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use App\Service\ConversationService;
use App\Service\GenererTokenConversation;
use App\Service\GenererTokenService;
use App\Service\MailerService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/candidature')]
class CandidatureController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $manager,
        private GenererTokenService $tokenService,
        private MailerService $mailerService,
        private EntityManagerInterface $entityManager,
        private ConversationRepository $conversationRepository,
        private GenererTokenConversation $genererTokenConversation,
        private ConversationService $conversationService,
        private MessageRepository $messageRepository
    ) {
    }

    #[Route('/postuler/{slug}', name: 'post_candidature')]
    public function postuler(Offre $offre, Request $request): Response
    {
        $user = $this->getUser();
        $user2 = $offre->getUser();

        if ($user == $offre->getUser()) {
            return $this->redirectToRoute('offres', [], 301);
        }

        $candidature = new Candidature();
        $form = $this->createForm(PostulerType::class, $candidature, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($offre->isCandidateByUser($user)) {
                $this->addFlash('warning', "Vous avez Appliquer à cette offre d'emplois");
                return $this->redirectToRoute('candidature_echec', ['slug' => $offre->getSlug()]);
            }

            # Save candidature
            $candidature->setUser($this->getUser());
            $candidature->setToken($this->tokenService->generateUniqueToken());
            $candidature->setOffre($offre);
            $candidature->setStatus('En attente');
            $candidature->setStatusColor('warning');
            $this->manager->persist($candidature);
            $this->manager->flush();

            # Send mail to candidat
            $this->mailerService->sendCandidatEmail($candidature);
            # Send mail to emploiyer
            $this->mailerService->sendCandidatureEmployerEmail($candidature);

            # Check if conversation
            $checkCandidatureConversation = $this->conversationService->checkCandidatureConversation($user, $user2, $candidature);

            # Check Chat
            if ($checkCandidatureConversation) {
                return $this->redirectToRoute('suivis_candidature', ['token' => $checkCandidatureConversation->getToken()]);
            }

            # Open chat
            $this->conversationService->createConversation(
                $user,
                $user2,
                $user,
                $form->get('candidatPresentation')->getData(),
                $candidature,
                $user2
            );

            return $this->redirectToRoute('candidature_success', ['token' => $candidature->getToken()]);
        }

        return $this->render('candidature/postuler.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/suivis-candidature/{token}', name: 'suivis_candidature', methods: ['GET', 'POST'])]
    public function suivisCondidature(Candidature $candidature, Request $request): Response
    {
        /** @var User */
        $user = $this->getUser();

        $conversation = $this->conversationRepository->findOneBy(['candidature' => $candidature]);

        # verification des participants
        $participants = [$conversation->getUser1(), $conversation->getUser2()];

        $candidature = $conversation->getCandidature();
        $candidature->setEntretien($candidature->getEntretien());

        if (!in_array($user, $participants)) {

            return $this->redirectToRoute('conversations', [], Response::HTTP_SEE_OTHER);
        }

        if ($conversation->getLastMessage()->getDestinataire()->getId() == $user->getId() && $conversation->getLastMessage()->getLu() == 0) {
            $message = $this->messageRepository->find($conversation->getLastMessage()->getId());
            $message->setLu(true);
            $this->entityManager->flush();
        }

        $messages = $this->messageRepository->findBy([
            'conversation' => $conversation
        ], ['created' => 'ASC']);

        # Test de message
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $destinataire = null;

            if ($user->getId() == $conversation->getUser1()->getId()) {
                $destinataire = $conversation->getUser2();
            } else {
                $destinataire = $conversation->getUser1();
            }

            $message->setConversation($conversation);
            $message->setAuteur($user);
            $message->setDestinataire($destinataire);
            $message->setLu(false);

            $this->entityManager->persist($message);
            $this->entityManager->flush();

            $conversation->setSender($this->getUser());
            $conversation->setCreated(new \DateTimeImmutable());
            $conversation->setLastMessage($message);

            $this->entityManager->flush();


            return $this->redirectToRoute('suivis_candidature', [
                'token' => $candidature->getToken()
            ], Response::HTTP_SEE_OTHER);
        }

        # Confirme trie candidature
        $trieForm = $this->createForm(TrieType::class, $candidature);
        $trieForm->handleRequest($request);
        if ($trieForm->isSubmitted() && $trieForm->isValid()) {
            $candidature->setDateTrie(new DateTime());
            $candidature->setAcceptationDate(new DateTime());
            $candidature->setStatus('Accepter');
            $candidature->setStatusColor('blue');
            $this->entityManager->flush();
            # Send Mail
            $this->mailerService->sendCandidatEmailCallBack(
                $candidature,
                $trieForm->get('acceptationMessage')->getData()
            );
            $this->addFlash('success', "La candidature a été acceptée");
            return $this->redirectToRoute('suivis_candidature', ['token' => $candidature->getToken()]);
        }

        # Evaluation competence
        $evaluationForm = $this->createForm(EvaluationType::class, $candidature);
        $evaluationForm->handleRequest($request);
        if ($evaluationForm->isSubmitted() && $evaluationForm->isValid()) {
            $candidature->setDateEvaluation(new DateTime());
            $candidature->setStatus("En cours d'évaluation");
            $this->entityManager->flush();
            # Send Mail
            $this->mailerService->sendCandidatEmailCallBack(
                $candidature,
                $evaluationForm->get('evaluationMessage')->getData()
            );
            $this->addFlash('success', "Les compétences du candidat ont été évaluées");
            return $this->redirectToRoute('suivis_candidature', ['token' => $candidature->getToken()]);
        }

        # Entretien
        $entretienForm = $this->createForm(EntretienType::class, $candidature);
        $entretienForm->handleRequest($request);
        if ($entretienForm->isSubmitted() && $entretienForm->isValid()) {
            $candidature->setDateEntretien(new DateTime());
            $candidature->setStatus('Convier en entretien');
            $candidature->setStatusColor('info');
            $conversation->setTerminee(true);
            $this->entityManager->flush();
            # Send Mail
            $this->mailerService->sendCandidatEmailCallBack(
                $candidature,
                $entretienForm->get('entretienMessage')->getData()
            );
            $this->addFlash('success', "Votre demande d'entretien a bien été envoyée au candidat");
            return $this->redirectToRoute('suivis_candidature', ['token' => $candidature->getToken()]);
        }

        # Sélection
        $selectionForm = $this->createForm(SelectionType::class, $candidature);
        $selectionForm->handleRequest($request);
        if ($selectionForm->isSubmitted() && $selectionForm->isValid()) {
            $candidature->setDateSelection(new DateTime());
            $candidature->setStatus('Sélectionner');
            $candidature->setStatusColor('success');
            $conversation->setTerminee(true);
            $this->entityManager->flush();
            # Send Mail
            $this->mailerService->sendCandidatEmailCallBack(
                $candidature,
                $selectionForm->get('selectionMessage')->getData()
            );
            $this->addFlash('success', "La sélectection du candidat a bien été éffectuée");
            return $this->redirectToRoute('suivis_candidature', ['token' => $candidature->getToken()]);
        }

        # Rejet
        $rejetForm = $this->createForm(RejetType::class, $candidature);
        $rejetForm->handleRequest($request);
        if ($rejetForm->isSubmitted() && $rejetForm->isValid()) {
            $candidature->setDateRejet(new DateTime());
            $conversation->setTerminee(true);
            $candidature->setStatus('Rejeter');
            $candidature->setStatusColor('danger');
            $this->entityManager->flush();
            # Send Mail
            $this->mailerService->sendCandidatEmailCallBack(
                $candidature,
                $rejetForm->get('rejetMessage')->getData()
            );
            $this->addFlash('success', "La candidature à bien été rejetée!");
            return $this->redirectToRoute('suivis_candidature', ['token' => $candidature->getToken()]);
        }

        # Send avis
        $destinataire = null;

        if ($user->getId() == $conversation->getUser1()->getId()) {
            $destinataire = $conversation->getUser2();
        } else {
            $destinataire = $conversation->getUser1();
        }

        $avis = new Avis();
        $avisForm = $this->createForm(AvisType::class, $avis);
        $avisForm->handleRequest($request);
        if ($avisForm->isSubmitted() && $avisForm->isValid()) {
            $avis->setOffre($candidature->getOffre());
            $avis->setAuteur($user);
            $avis->setUser($destinataire);
            $this->entityManager->persist($avis);
            $this->entityManager->flush();

            $this->addFlash('success', "Votre avis a bien été enregistrer");
        }

        return $this->render('candidature/suivis.html.twig', [
            'conversation' => $conversation,
            'messages' => $messages,
            'candidature' => $candidature,
            'form' => $form->createView(),
            'trieForm' => $trieForm->createView(),
            'evaluationForm' => $evaluationForm->createView(),
            'entretienForm' => $entretienForm->createView(),
            'selectionForm' => $selectionForm->createView(),
            'rejetForm' => $rejetForm->createView(),
            'avisForm' => $avisForm->createView(),
        ]);
    }

    #[Route('/success/{token}', name: 'candidature_success', methods: ['GET'])]
    public function success(Candidature $candidature)
    {
        return $this->render('candidature/success.html.twig', [
            'candidature' => $candidature,
            'offre' => $candidature->getOffre()
        ]);
    }

    #[Route('/echec/{slug}', name: 'candidature_echec', methods: ['GET'])]
    public function cancel(Offre $offre)
    {
        return $this->render('candidature/success.html.twig', [
            'offre' => $offre,
        ]);
    }
}
