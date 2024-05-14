<?php

namespace App\Controller\Base;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Form\Base\MessageType;
use App\Repository\ConversationRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/conversations')]
class ConversationController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MessageRepository $messageRepository,
        private ConversationRepository $conversationRepository
    ) {
    }

    #[Route('/', name: 'conversations', methods: ['POST', 'GET'])]
    public function conversations()
    {
        /** @var User */
        $user = $this->getUser();
        $usersconversations = $this->conversationRepository->findByParticipation($user);

        return $this->render('conversation/index.html.twig', [
            'conversations' => $usersconversations,
            'conversation' => null,
        ]);
    }

    #[Route('/{token}', name: 'conversation_details', methods: ['POST', 'GET'])]
    public function conversation(Conversation $conversation, Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        //*verification des participants
        $participants = [$conversation->getUser1(), $conversation->getUser2()];

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
        ], ['created' => 'DESC']);

        $usersconversations = $this->conversationRepository->findByParticipation($user);

        # Send de message
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        $destinataire = null;

        if ($user->getId() == $conversation->getUser1()->getId()) {
            $destinataire = $conversation->getUser2();
        } else {
            $destinataire = $conversation->getUser1();
        }

        if ($form->isSubmitted() && $form->isValid()) {

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


            return $this->redirectToRoute('conversation_details', [
                'token' => $conversation->getToken()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conversation/show.html.twig', [
            'conversation' => $conversation,
            'conversations' => $usersconversations,
            'messages' => $messages,
            'candidature' => $conversation->getCandidature(),
            'form' => $form->createView(),
        ]);
    }
}
