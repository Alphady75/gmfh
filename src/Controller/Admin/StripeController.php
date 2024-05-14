<?php

namespace App\Controller\Admin;

use App\Entity\Stripe;
use App\Form\Admin\StripeType;
use App\Repository\StripeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/stripe')]
class StripeController extends AbstractController
{
    public function __construct(
        private StripeRepository $stripeRepository,
        private PaginatorInterface $paginator,
        private EntityManagerInterface $entityManager,
    ) {
        
    }

    #[Route('/', name: 'admin_stripe_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $stripes = $this->paginator->paginate(
            $this->stripeRepository->findBy(['complet' => true], ['created' => 'DESC']),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('admin/stripe/index.html.twig', [
            'stripes' => $stripes,
        ]);
    }

    #[Route('/new', name: 'admin_stripe_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        # Créate secteur if does not exist
        $stripe = $this->createIfNotExist();
        $form = $this->createForm(StripeType::class, $stripe, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $stripe->setComplet(true);
            $this->entityManager->persist($stripe);
            $this->entityManager->flush();
            $this->addFlash('success', "Le contenu a bien été enregistré");
            return $this->redirectToRoute('admin_stripe_index');
        }

        return $this->render('admin/stripe/new.html.twig', [
            'stripe' => $stripe,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_stripe_show', methods: ['GET'])]
    public function show(Stripe $stripe): Response
    {
        return $this->render('admin/stripe/show.html.twig', [
            'stripe' => $stripe,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_stripe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stripe $stripe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StripeType::class, $stripe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', "Le contenu a bien été enregistré");
            return $this->redirectToRoute('admin_stripe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/stripe/edit.html.twig', [
            'stripe' => $stripe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_stripe_delete', methods: ['POST'])]
    public function delete(Request $request, Stripe $stripe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $stripe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($stripe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_stripe_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * Create if does not exist
     * @return Stripe
     */
    public function createIfNotExist()
    {
        $stripe = $this->stripeRepository->findOneBy(['complet' => 0]);

        if (!$stripe) {
            $stripe = new Stripe();
            $stripe->setDevise('FCFA');
            $stripe->setComplet(false);
            $this->entityManager->persist($stripe);
            $this->entityManager->flush();
        }

        return $stripe;
    }
}
