<?php

namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Form\Admin\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/categorie')]
class CategorieController extends AbstractController
{
    public function __construct(
        private PaginatorInterface $paginator,
        private CategorieRepository $categorieRepository,
        private EntityManagerInterface $entityManager,
        private SluggerInterface $slugger
    ) {
    }

    #[Route('/', name: 'admin_categorie_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $categories = $this->paginator->paginate(
            $this->categorieRepository->findBy(['complet' => true], ['created' => 'DESC']),
            $request->query->getInt('page', 1),
            12
        );

        $categorie = $this->createIfNotExist();
        $form = $this->createForm(CategorieType::class, $categorie, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $categorie->setComplet(true);
            $categorie->setSlug($this->slugger->slug(strtolower($form->get('name')->getData())));
            $this->entityManager->persist($categorie);
            $this->entityManager->flush();
            $this->addFlash('success', "Le contenu a bien été enregistré");
            return $this->redirectToRoute('admin_categorie_index');
        }

        return $this->render('admin/categorie/index.html.twig', [
            'categories' => $categories,
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'admin_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorie = $this->createIfNotExist();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $categorie->setComplet(true);
            $categorie->setSlug($this->slugger->slug(strtolower($form->get('name')->getData())));
            $this->entityManager->persist($categorie);
            $this->entityManager->flush();
            $this->addFlash('success', "Le contenu a bien été enregistré");

            return $this->redirectToRoute('admin_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_categorie_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        return $this->render('admin/categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorie->setSlug($form->get('name')->getData());
            $entityManager->flush();
            $categorie->setSlug($categorie->getSlug() . '-' . $categorie->getId());
            return $this->redirectToRoute('admin_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_categorie_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Create if does not exist
     * @return SecteursActivite
     */
    public function createIfNotExist()
    {
        $categorie = $this->categorieRepository->findOneBy(['complet' => 0]);

        if (!$categorie) {

            $categorie = new categorie();
            $categorie->setComplet(0);
            $this->entityManager->persist($categorie);
            $this->entityManager->flush();
        }

        return $categorie;
    }
}
