<?php

namespace App\Service;

use App\Entity\Categorie;
use App\Entity\Offre;
use App\Entity\Post;
use App\Entity\SousCategorie;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class SessionService
{
   private $request;

   public function __construct(
      private EntityManagerInterface $manager,
      RequestStack $requestStack,
      private UserRepository $userRepository
   ) {
      $this->request = $requestStack->getCurrentRequest();
   }
   
   public function setCategorie(Categorie $categorie)
   {
      if ($categorie) {
         // Enregistrement de la valeur en session
         $this->request->getSession()->set('categorie', $categorie);

         // Autre façon d'accéder à la session
         return $this->request->getSession()->get('categorie');
      }
   }
   
   public function getCategorie()
   {
      return $this->request->getSession()->get('categorie');
   }
   
   public function setSousCategorie(SousCategorie $souscategorie)
   {
      if ($souscategorie) {
         // Enregistrement de la valeur en session
         $this->request->getSession()->set('souscategorie', $souscategorie);

         // Autre façon d'accéder à la session
         return $this->request->getSession()->get('souscategorie');
      }
   }
   
   public function getSouscategorie()
   {
      return $this->request->getSession()->get('souscategorie');
   }
   
   public function setOffre(Offre $offre)
   {
      if ($offre) {
         // Enregistrement de la valeur en session
         $this->request->getSession()->set('offre', $offre);

         // Autre façon d'accéder à la session
         return $this->request->getSession()->get('offre');
      }
   }
   
   public function getOffre()
   {
      return $this->request->getSession()->get('offre');
   }
   
   public function setPost(Post $post)
   {
      if ($post) {
         // Enregistrement de la valeur en session
         $this->request->getSession()->set('post', $post);

         // Autre façon d'accéder à la session
         return $this->request->getSession()->get('post');
      }
   }
   
   public function getPost()
   {
      return $this->request->getSession()->get('post');
   }
}
