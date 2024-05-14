<?php

namespace App\Service;

use App\Entity\Offre;
use App\Entity\Vue;
use App\Repository\VueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class OffreService
{
   private $request;

   public function __construct(
      private EntityManagerInterface $manager,
      RequestStack $requestStack,
      private VueRepository $vueRepository
   ) {
      $this->request = $requestStack->getCurrentRequest();
   }

   /**
    * Check vue Offre
    *
    * @param Offre $offre
    * @return Vue
    */
   public function checkVueOffre(Offre $offre)
   {
      $ip = $this->request->getClientIp();

      $vue = $this->vueRepository->findOneBy(['ip' => $ip, 'offre' => $offre]);
      
      if (!$vue) {
         $vue = new Vue();
         $vue->setIp($ip);
         $vue->setOffre($offre);
         $this->manager->persist($vue);
         $this->manager->flush();

         $vue = $vue;
      }

      return $vue;
   }
}
