<?php

namespace App\Service;

use App\Entity\Candidature;
use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\ConversationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ConversationService
{
   private $request;

   public function __construct(
      private EntityManagerInterface $manager,
      RequestStack $requestStack,
      private EntityManagerInterface $entityManager,
      private GenererTokenConversation $genererTokenConversation,
      private ConversationRepository $conversationRepository
   ) {
      $this->request = $requestStack->getCurrentRequest();
   }

   /**
    * Open Chat for candidature
    *
    * @param User $user1
    * @param User $user2
    * @param User $auteur
    * @param string $message
    * @param Candidature $candidature
    * @param User $destinataire
    * @return Conversation
    */
   public function createConversation(
      User $user1,
      User $user2,
      User $auteur,
      string $contenu,
      Candidature $candidature = null,
      User $destinataire,
   ) {
      $conversation = new Conversation();
      $message = new Message();
      $conversation->setUser1($user1);
      $conversation->setUser2($user2);
      $conversation->setTerminee(false);
      $conversation->setCandidature($candidature);
      # Générate Token For conversation
      $conversation->setToken($this->genererTokenConversation->generateUniqueToken());
      $this->entityManager->persist($conversation);
      $this->entityManager->flush();

      $message->setConversation($conversation);
      $message->setAuteur($auteur);
      $message->setDestinataire($destinataire);
      $message->setContenu($contenu);
      $message->setLu(false);
      $this->entityManager->persist($message);

      $conversation->setLastMessage($message);

      $this->entityManager->flush();

      return $conversation;
   }

   public function checkCandidatureConversation(User $user1, User $user2, Candidature $candidature = null)
   {
      $conversation = $this->conversationRepository->findOneBy([
         'user1' => $user1,
         'user2' => $user2,
         'candidature' => $candidature,
      ]);

      return $conversation;
   }

   /**
    * Open Chat for candidature
    *
    * @param User $user1
    * @param User $user2
    * @param User $auteur
    * @param string $message
    * @param Post $post
    * @param User $destinataire
    * @return Conversation
    */
   public function createPostConversation(
      User $user1,
      User $user2,
      User $auteur,
      string $contenu,
      Post $post = null,
      User $destinataire,
   ) {
      $conversation = new Conversation();
      $message = new Message();
      $conversation->setUser1($user1);
      $conversation->setUser2($user2);
      $conversation->setTerminee(false);
      $conversation->setPost($post);
      # Générate Token For conversation
      $conversation->setToken($this->genererTokenConversation->generateUniqueToken());
      $this->entityManager->persist($conversation);
      $this->entityManager->flush();

      $message->setConversation($conversation);
      $message->setAuteur($auteur);
      $message->setDestinataire($destinataire);
      $message->setContenu($contenu);
      $message->setLu(false);
      $this->entityManager->persist($message);

      $conversation->setLastMessage($message);

      $this->entityManager->flush();

      return $conversation;
   }

   public function checkPostConversation(User $user1, User $user2, Post $post = null)
   {
      $conversation = $this->conversationRepository->findOneBy([
         'user1' => $user1,
         'user2' => $user2,
         'post' => $post,
      ]);

      return $conversation;
   }

   public function checkConversation(User $user1, User $user2)
   {
      $conversation = $this->conversationRepository->findOneBy([
         'user1' => $user1,
         'user2' => $user2,
      ]);

      return $conversation;
   }
}
