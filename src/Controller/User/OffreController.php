<?php

namespace App\Controller\User;

use App\Entity\Dto\Offre as DtoOffre;
use App\Entity\Offre;
use App\Entity\User;
use App\Form\Dto\OffreType as DtoOffreType;
use App\Form\User\OffreType;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/partenaire/offres')]
class OffreController extends AbstractController
{
    public function __construct(private SluggerInterface $slugger, private OffreRepository $offreRepository, private EntityManagerInterface $entityManager)
    {
        
    }

    #[Route('/liste', name: 'user_offre', methods: ['GET'])]
    public function index(OffreRepository $offreRepository, Request $request): Response
    {
        $search = new DtoOffre();
        $search->page = $request->get('page', 1);
        $form = $this->createForm(DtoOffreType::class, $search);
        $form->handleRequest($request);
        $offres = $offreRepository->auteurFilter($search, $this->getUser());

        return $this->render('user/offre/index.html.twig', [
            'offres' => $offres,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/nouvelle-offre', name: 'user_offre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $offre = $this->createIfNotExist($user);

        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $offre->setSlug(strtolower($this->slugger->slug($form->get('name')->getData())));
            $offre->setUser($this->getUser());
            $offre->setComplet(true);
            $entityManager->persist($offre);
            $entityManager->flush();

            $this->addFlash('success', 'Le contenu a bien été enregistrer');

            # Booster if true
            $boosted = $form->get('boosted')->getData();
            if ($boosted)
                return $this->redirectToRoute('booster_offre', ['slug' => $offre->getSlug()], 301);

            return $this->redirectToRoute('user_offre', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'user_offre_show', methods: ['GET'])]
    public function show(Offre $offre): Response
    {
        return $this->render('user/offre/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    #[Route('/{slug}/edit', name: 'user_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('user_offre', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'user_offre_delete', methods: ['POST'])]
    public function delete(Request $request, Offre $offre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($offre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_offre', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Create if does not exist
     *
     * @param Offre $offre
     * @param User $user
     * @return Offre
     */
    public function createIfNotExist(User $user)
    {
        $offre = $this->offreRepository->findOneBy(['user' => $user, 'complet' => 0]);

        if (!$offre){

            $offre = new Offre();
            $offre->setUser($user);
            $offre->setComplet(false);
            $offre->setBoosted(false);
            $this->entityManager->persist($offre);
            $this->entityManager->flush();
        }

        return $offre;
    }
}
