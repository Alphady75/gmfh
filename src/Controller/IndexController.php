<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/redirect-to', name: 'user_redirect')]
    public function redirectUserAfterLogin()
    {
        /** @var User */
        $user = $this->getUser();
        $root = null;

        if ($user->getCompte() === 'PERSONNEL') {
            $root = 'client_dashboard';
        } elseif ($user->getCompte() === 'ADMINISTRATEUR') {
            $root = 'admin_dashboard';
        }else{
            $root = 'user_dashboard';
        }
        
        return $this->redirectToRoute($root, [], 301);
    }
}
