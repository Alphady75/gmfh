<?php

namespace App\Controller\Client;

use App\Entity\Alert;
use App\Form\Base\AlertType;
use App\Repository\AlertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/client-espace/alertes')]
class AlertController extends AbstractController
{
    public function __construct(
        private AlertRepository $alertRepository,
        private EntityManagerInterface $entityManager,
        private PaginatorInterface $paginator,
    ) {
    }

    #[Route('/', name: 'client_alert_index', methods: ['GET'])]
    public function liste(Request $request): Response
    {
        $user = $this->getUser();

        $alerts = $this->paginator->paginate(
            $this->alertRepository->findBy(['user' => $user], ['created' => 'DESC']),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('client/alert/index.html.twig', [
            'alerts' => $alerts,
        ]);
    }

    #[Route('/new', name: 'client_alert_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $alert = new Alert();
        $form = $this->createForm(AlertType::class, $alert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $alert->setActive(true);
            $alert->setLu(false);
            $alert->setUser($this->getUser());

            $this->entityManager->persist($alert);
            $this->entityManager->flush();

            $this->addFlash('success', "Cette alerte a été sauvegardée avec succès. Vous serez avisé par courriel lorsqu’une nouvelle offre d’emploi correspondant à ces critères sera publiée.");

            return $this->redirectToRoute('client_alert_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/alert/new.html.twig', [
            'alert' => $alert,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'client_alert_show', methods: ['GET'])]
    public function show(Alert $alert): Response
    {
        return $this->render('client/alert/show.html.twig', [
            'alert' => $alert,
        ]);
    }

    #[Route('/{id}/edit', name: 'client_alert_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Alert $alert, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AlertType::class, $alert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('client_alert_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('client/alert/edit.html.twig', [
            'alert' => $alert,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'client_alert_delete', methods: ['POST'])]
    public function delete(Request $request, Alert $alert, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $alert->getId(), $request->request->get('_token'))) {
            $entityManager->remove($alert);
            $entityManager->flush();
            $this->addFlash('success', "Votre alerte a bien été supprimée");
        }

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/remove/{id}', name: 'client_alert_remove', methods: ['GET'])]
    public function remove(Request $request, Alert $alert): Response
    {
        $this->entityManager->remove($alert);
        $this->entityManager->flush();
        $this->addFlash('success', "Votre alerte a bien été supprimée");

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/desactive/{id}', name: 'client_alert_desactive', methods: ['POST'])]
    public function desactive(Request $request, Alert $alert, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('desactive' . $alert->getId(), $request->request->get('_token'))) {
            $alert->setActive(false);
            $entityManager->flush();
            $this->addFlash('success', "Votre alerte a bien été désactivée");
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
