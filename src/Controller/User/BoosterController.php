<?php

namespace App\Controller\User;

use App\Entity\Boost;
use App\Entity\Booster;
use App\Entity\Offre;
use App\Entity\Post;
use App\Form\Booster\OffreType;
use App\Form\Booster\PostType;
use App\Repository\BoostRepository;
use App\Repository\OffreRepository;
use App\Repository\PostRepository;
use App\Service\SessionService;
use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/booster')]
class BoosterController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
        private StripeService $stripeService,
        private SessionService $sessionService,
        private BoostRepository $boostRepository,
        private OffreRepository $offreRepository,
        private PostRepository $postRepository,
    ) {
    }

    #[Route('/offre/{slug}', name: 'booster_offre')]
    public function BoosterOffre(Offre $offre, Request $request): Response
    {
        $form = $this->createForm(OffreType::class, $offre, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            # Get Booster
            /** @var Booster */
            $booster = $form->get('booster')->getData();

            # Save offre in session
            $this->sessionService->setOffre($offre);

            return $this->PayToBoost($booster->getTarif(), 'booster/offre-booster-success');
        }

        return $this->render('user/booster/offre.html.twig', [
            'form' => $form->createView(),
            'offre' => $offre,
        ]);
    }

    #[Route('/annonce/{slug}', name: 'booster_post')]
    public function index(Post $post, Request $request): Response
    {
        $form = $this->createForm(PostType::class, $post, []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            # Get Booster
            /** @var Booster */
            $booster = $form->get('booster')->getData();

            # Save post in session
            $this->sessionService->setPost($post);

            return $this->PayToBoost($booster->getTarif(), 'booster/annonce-booster-success');
        }

        return $this->render('user/booster/post.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }
    
    public function PayToBoost($montant, string $urlToRedirect): Response
    {
        # Payer avec stripe
        $respone = $this->stripeService->booster($montant, $urlToRedirect);

        if ($this->getUser()) {

            return $this->redirect($respone);
        }

        return $this->redirect($respone);
    }

    #[Route('/offre-booster-success', name: 'offre_booster_success')]
    public function offreBossterSuccess(Request $request)
    {
        $token = $request->get('token');
        $checkBoost = $this->boostRepository->findOneByToken($token);

        /** @var Offre */
        $offre = $this->sessionService->getOffre();

        if (!$checkBoost && $offre != null) {

            $boost = new Boost();
            $boost->setElement('offre');
            $boost->setStartAt($offre->getBooster()->getStartAt());
            $boost->setEndAt($offre->getBooster()->getEndAt());
            $boost->setStatus('EN COURS');
            $boost->setToken($token);
            $boost->setOffre($this->offreRepository->find($offre->getId()));

            # Set booster true to offre
            $offre->setBoosted(true);
            # Save Boost
            $this->entityManagerInterface->persist($boost);
            $this->entityManagerInterface->flush();
        }

        return $this->render('user/booster/offre_success.html.twig', [
            'offre' => $offre
        ]);
    }

    #[Route('/annonce-booster-success', name: 'post_booster_success')]
    public function postBoosterSuccess(Request $request)
    {
        $token = $request->get('token');
        $checkBoost = $this->boostRepository->findOneByToken($token);

        /** @var Post */
        $post = $this->sessionService->getPost();

        if (!$checkBoost && $post != null) {

            $boost = new Boost();
            $boost->setElement('post');
            $boost->setStartAt($post->getBooster()->getStartAt());
            $boost->setEndAt($post->getBooster()->getEndAt());
            $boost->setStatus('EN COURS');
            $boost->setToken($token);
            $boost->setPost($this->postRepository->find($post->getId()));

            # Set booster true to post
            $post->setBoosted(true);
            # Save Boost
            $this->entityManagerInterface->persist($boost);
            $this->entityManagerInterface->flush();
        }

        return $this->render('user/booster/post_success.html.twig', [
            'post' => $post
        ]);
    }

    #[Route('/cancel', name: 'booster_canceled')]
    public function cancel()
    {
        return $this->render('user/booster/cancel.html.twig', []);
    }
}
