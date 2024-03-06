<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class GenererCodeService
{
   private $request;

   public function __construct(private EntityManagerInterface $manager, RequestStack $requestStack)
   {
      $this->request = $requestStack->getCurrentRequest();
   }

   public function generateUniqueCode(): string
   {
      // Génération d'un code initial
      $code = $this->generateRandomCode();

      // Vérification de l'unicité du code
      while ($this->codeExists($code)) {
         $code = $this->generateRandomCode();
      }

      return $code;
   }

   private function generateRandomCode(): string
   {
      return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
   }

   /**
    * Check if code Existe
    *
    * @param String $code
    * @return boolean
    */
   private function codeExists($code): bool
   {
      $existingCode = $this->manager->getRepository(User::class)->findOneBy(['resetPasswordCode' => $code]);

      return $existingCode !== null;
   }
   
   /**
    * Permet de stocker le code de réinitialisation du mot de passe en session
    *
    * @param Int $code
    * @return void
    */
   public function setCodeInSession(string $code)
   {
      if ($code) {
         // Enregistrement de la valeur en session
         $this->request->getSession()->set('code', $code);

         // Autre façon d'accéder à la session
         return $this->request->getSession()->get('code');
      }
   }
   
   /**
    * Permet de recuperer le code de réinitialisation du mot de passe en session
    *
    * @return void
    */
   public function getCodeInSession()
   {
      return $this->request->getSession()->get('code');
   }
}
