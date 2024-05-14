<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Vue;
use App\Repository\UserRepository;
use App\Repository\VueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class UserService
{
   private $request;

   public function __construct(
      private EntityManagerInterface $manager,
      RequestStack $requestStack,
      private UserRepository $userRepository,
      private VueRepository $vueRepository
   ) {
      $this->request = $requestStack->getCurrentRequest();
   }

   /**
    * Permet d'enregistrer email utilisateur dans session
    *
    * @param string $email
    * @return void
    */
   public function setUserEmailInSession(string $email)
   {
      if ($email) {
         // Enregistrement de la valeur en session
         $this->request->getSession()->set('email', $email);

         // Autre façon d'accéder à la session
         return $this->request->getSession()->get('email');
      }
   }

   /**
    * permet d'enregistrer l'email utilisateur en session
    *
    * @return void
    */
   public function getUserEmailInSession()
   {
      return $this->request->getSession()->get('email');
   }

   /**
    * Recupère le compte utilisateur selon le code de confirmation & email en session
    *
    * @param string $code
    * @return User
    */
   public function getUserByCodeAndEmailInSession($code)
   {
      return $this->userRepository->findOneBy([
         'email' => $this->getUserEmailInSession(),
         'codeIsVerified' => $code,
      ]);
   }

   /**
    * Recupère le compte utilisateur selon le code de reinitialisation & email en session
    *
    * @param string $code
    * @return User
    */
   public function getUserByResetPassCodeAndEmailInSession($code)
   {
      return $this->userRepository->findOneBy([
         'email' => $this->getUserEmailInSession(),
         'resetPasswordCode' => $code,
      ]);
   }

   /**
    * Check vue profile
    *
    * @param User $user
    * @return Vue
    */
   public function checkVueProfile(User $user)
   {
      $ip = $this->request->getClientIp();

      $checkVue = $this->vueRepository->findOneBy(['ip' => $ip]);
      
      if (!$checkVue) {
         $vue = new Vue();
         $vue->setIp($ip);
         $vue->setUser($user);
         $this->manager->persist($vue);
         $this->manager->flush();

         $checkVue = $vue;
      }
      return $checkVue;
   }
}
