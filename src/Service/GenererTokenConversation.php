<?php

namespace App\Service;

use App\Entity\Conversation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class GenererTokenConversation
{
   private $request;

   public function __construct(private EntityManagerInterface $manager, RequestStack $requestStack)
   {
      $this->request = $requestStack->getCurrentRequest();
   }

   public function generateUniqueToken(): string
   {
      // Génération d'un token initial
      $token = $this->generateRandomtoken();

      // Vérification de l'unicité du token
      while ($this->tokenExists($token)) {
         $token = $this->generateRandomToken();
      }

      return $token;
   }

   private function generateRandomToken(): string
   {
      return md5(uniqid('GAMFAH')) . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
   }

   /**
    * Check if token Existe
    *
    * @param String $token
    * @return boolean
    */
   private function tokenExists($token): bool
   {
      $existingtoken = $this->manager->getRepository(Conversation::class)->findOneBy(['token' => $token]);

      return $existingtoken !== null;
   }
   
   /**
    * Permet de stocker le token de réinitialisation du mot de passe en session
    *
    * @param Int $token
    * @return void
    */
   public function setTokenInSession(string $token)
   {
      if ($token) {
         // Enregistrement de la valeur en session
         $this->request->getSession()->set('token', $token);

         // Autre façon d'accéder à la session
         return $this->request->getSession()->get('token');
      }
   }
   
   /**
    * Permet de recuperer le token de réinitialisation du mot de passe en session
    *
    * @return void
    */
   public function getTokenInSession()
   {
      return $this->request->getSession()->get('token');
   }
}
