<?php

namespace App\Controller\Admin;

use App\Entity\SecteursActivite;
use App\Form\Admin\SecteursActiviteType;
use App\Repository\SecteursActiviteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/secteurs-activites')]
class SecteursActiviteController extends AbstractController
{
    public function __construct(
        private PaginatorInterface $paginator,
        private EntityManagerInterface $entityManager,
        private SluggerInterface $slugger,
        private SecteursActiviteRepository $secteursActiviteRepository,
    ) {
    }

    #[Route('/', name: 'admin_secteurs_activite_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $secteursactivites = $this->paginator->paginate(
            $this->secteursActiviteRepository->findBy(['complet' => 1], ['created' => 'DESC']),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('admin/secteurs_activite/index.html.twig', [
            'secteurs_activites' => $secteursactivites,
        ]);
    }

    #[Route('/new', name: 'admin_secteurs_activite_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        # Créate secteur if does not exist
        $secteuractivite = $this->createIfNotExist();
        $form = $this->createForm(SecteursActiviteType::class, $secteuractivite, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $secteuractivite->setComplet(true);
            $secteuractivite->setSlug(strtolower($this->slugger->slug($form->get('name')->getData())));
            $this->entityManager->persist($secteuractivite);
            $this->entityManager->flush();
            
            $secteuractivite->setSlug($secteuractivite->getSlug() . '-' . $secteuractivite->getId());
            $this->entityManager->flush();
            $this->addFlash('success', "Le contenu a bien été enregistré");
            return $this->redirectToRoute('admin_secteurs_activite_index');
        }

        return $this->render('admin/secteurs_activite/new.html.twig', [
            'secteuractivite' => $secteuractivite,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_secteurs_activite_show', methods: ['GET'])]
    public function show(SecteursActivite $secteursActivite): Response
    {
        return $this->render('admin/secteurs_activite/show.html.twig', [
            'secteurs_activite' => $secteursActivite,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_secteurs_activite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SecteursActivite $secteuractivite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SecteursActiviteType::class, $secteuractivite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $secteuractivite->setSlug(strtolower($form->get('name')->getData() . '-' . $secteuractivite->getId()));
            $entityManager->flush();
            $this->addFlash('success', "Le contenu à bien été enregistrer");
            return $this->redirectToRoute('admin_secteurs_activite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/secteurs_activite/edit.html.twig', [
            'secteuractivite' => $secteuractivite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_secteurs_activite_delete', methods: ['POST'])]
    public function delete(Request $request, SecteursActivite $secteursActivite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$secteursActivite->getId(), $request->request->get('_token'))) {
            $entityManager->remove($secteursActivite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_secteurs_activite_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Create if does not exist
     * @return SecteursActivite
     */
    public function createIfNotExist()
    {
        $secteursactivite = $this->secteursActiviteRepository->findOneBy(['complet' => 0]);

        if (!$secteursactivite){

            $secteursactivite = new SecteursActivite();
            $secteursactivite->setComplet(false);
            $this->entityManager->persist($secteursactivite);
            $this->entityManager->flush();
        }

        return $secteursactivite;
    }
}
