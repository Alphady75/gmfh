<?php

namespace App\Service;

use App\Entity\Langue;
use App\Entity\SecteursActivite;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AlertService
{
   private $request;

   private $session;

   public function __construct(
      private EntityManagerInterface $manager,
      RequestStack $requestStack,
      private UserRepository $userRepository,
   ) {
      $this->request = $requestStack->getCurrentRequest();
   }

   public function setQuery(string $query)
   {
      $this->request->getSession()->set('query', $query);
   }

   public function getQuery()
   {
      return $this->request->getSession()->get('query');
   }

   public function setSecteur(SecteursActivite $secteursActivite)
   {
      $this->request->getSession()->set('secteur', $secteursActivite);
   }

   public function getSecteur()
   {
      return $this->request->getSession()->get('secteur');
   }

   public function setLocalisation(string $localisation)
   {
      $this->request->getSession()->set('localisation', $localisation);
   }

   public function getLocalisation()
   {
      return $this->request->getSession()->get('localisation');
   }

   public function setLieutravail(string $lieutravail)
   {
      $this->request->getSession()->set('lieutravail', $lieutravail);
   }

   public function getLieutravail()
   {
      return $this->request->getSession()->get('lieutravail');
   }

   public function setTypeContrat(string $typecontrat)
   {
      $this->request->getSession()->set('typecontrat', $typecontrat);
   }

   public function getTypeContrat()
   {
      return $this->request->getSession()->get('typecontrat');
   }

   public function setPeriodicite(string $periodicite)
   {
      $this->request->getSession()->set('periodicite', $periodicite);
   }

   public function getPeriodicite()
   {
      return $this->request->getSession()->get('periodicite');
   }

   public function setMinSalaire(string $minsalaire)
   {
      $this->request->getSession()->set('minsalaire', $minsalaire);
   }

   public function getMinSalaire()
   {
      return $this->request->getSession()->get('minsalaire');
   }

   public function setMaxSalaire(string $maxsalaire)
   {
      $this->request->getSession()->set('maxsalaire', $maxsalaire);
   }

   public function getMaxSalaire()
   {
      return $this->request->getSession()->get('maxsalaire');
   }

   public function setLangue(Langue $langue)
   {
      $this->request->getSession()->set('langue', $langue);
   }

   public function getLangue()
   {
      return $this->request->getSession()->get('langue');
   }

   /**
    * Delete value to Session
    *
    * @param string $session
    * @return void
    */
   public function remove(string $value)
   {
      $session = $this->request->getSession()->get($value);

      if ($session) {
         #dd('true');
         unset($session[$value]);
      }

      #dd('true');
   }
}
