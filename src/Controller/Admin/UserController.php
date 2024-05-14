<?php

namespace App\Controller\Admin;

use App\Entity\Dto\User as DtoUser;
use App\Entity\User;
use App\Form\Admin\EditUserType;
use App\Form\Dto\UserType as DtoUserType;
use App\Form\Admin\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $userPasswordHasher,
        private SluggerInterface $slugger
    ) {
    }

    #[Route('/', name: 'admin_user_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $search = new DtoUser();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(DtoUserType::class, $search);
        $form->handleRequest($request);
        $users = $this->userRepository->adminSearch($search);

        $user = new User();
        $userForm = $this->createForm(UserType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

            $compte = $userForm->get('compte')->getData();
            $name = $userForm->get('nom')->getData() . ' ' . $userForm->get('prenom')->getData();
            if ($compte == 'ENTREPRISE') {
                $user->setNomResponsable($name);
            }
            $user->setCompleted(true);
            $user->setAnnuaire(true);
            $user->setIsVerified(true);
            $user->setNameSlug($this->slugger->slug($name));
            $user->setRoles(['ROLE_' . $compte]);
            if ($compte == 'PARTICULIER' or $compte == 'ENTREPRISE') {
                $user->setAnnuaire(true);
            }
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $userForm->get('plainPassword')->getData()
                )
            );
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $user->setNameSlug($user->getNameSlug() . '-' . $user->getId());
            $this->entityManager->flush();

            $this->addFlash('success', 'Le compte a bien été cré');
            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
            'user' => $user,
            'form' => $form->createView(),
            'userForm' => $userForm->createView(),
        ]);
    }

    #[Route('/new', name: 'admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{nameSlug}/edit', name: 'admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $compte = $form->get('compte')->getData();
            $name = $form->get('nom')->getData() . ' ' . $form->get('prenom')->getData();
            if ($compte == 'ENTREPRISE') {
                $user->setNomResponsable($name);
            }
            $user->setCompleted(true);
            $user->setAnnuaire(true);
            $user->setIsVerified(true);
            $user->setNameSlug($this->slugger->slug($name));
            $user->setRoles(['ROLE_' . $compte]);
            if ($compte == 'PARTICULIER' or $compte == 'ENTREPRISE') {
                $user->setAnnuaire(true);
            }

            $password = $form->get('plainPassword')->getData();

            if (!empty($password)) {
                $user->setPassword(
                    $this->userPasswordHasher->hashPassword(
                        $user,
                        $password
                    )
                );
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $user->setNameSlug($user->getNameSlug() . '-' . $user->getId());
            $this->entityManager->flush();

            $this->addFlash('success', 'Le compte a bien été mise à jour');
            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/user/edit.html.twig', [
            'user' => $user,
            'userForm' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/annuaire/{id}', name: 'admin_user_annuaire', methods: ['POST'])]
    public function activeAnnuaire(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('annuaire' . $user->getId(), $request->request->get('_token'))) {

            $statut = $user->getAnnuaire() == 1 ? 'rendu visible' : "masquer";

            if ($user->getAnnuaire() == 1) {
                $user->setAnnuaire(0);
            }else{
                $user->setAnnuaire(1);
            }
            $this->entityManager->flush();
            $this->addFlash('success', "Le profil a bien été $statut sur l'annuaire");
        }

        return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
