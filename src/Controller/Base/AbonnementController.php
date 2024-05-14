<?php

namespace App\Controller\Base;

use App\Entity\Abonnement;
use App\Entity\Stripe;
use App\Repository\AbonnementRepository;
use App\Repository\StripeRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/offres-abonnements')]
class AbonnementController extends AbstractController
{
    private $privateKey;

    private UrlHelper $urlHelper;

    public function __construct(
        private StripeRepository $stripeRepository,
        UrlHelper $urlHelper,
        private EntityManagerInterface $entityManager,
        private AbonnementRepository $abonnementRepo
    ) {
        // Vérification de l'environnement

        if ($_ENV['APP_ENV'] === 'dev') {

            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
        } else {

            $this->privateKey = $_ENV['STRIPE_SECRET_KEY_LIVE'];
        }

        $this->urlHelper = $urlHelper;
    }

    #[Route('/', name: 'abonnements')]
    public function index(): Response
    {
        return $this->render('abonnement/index.html.twig', [
            'services' => $this->stripeRepository->findBy(['complet' => true]),
        ]);
    }

    #[Route('/checkout/{stripeKey}', name: 'stripe_abonnement_checkout', methods: ['POST'])]
    public function Checkout($stripeKey, StripeRepository $abonnementRepo): Response
    {
        /** @var User */
        $user = $this->getUser();
        if(!$user)
            return $this->redirectToRoute('app_login');

        $stripeAbonnement = $abonnementRepo->findOneBy(['stripeKey' => $stripeKey]);

        \Stripe\Stripe::setApiKey($this->privateKey);

        $websiteDomaine = $this->urlHelper->getAbsoluteUrl('/offres-abonnements');

        $checkout_session = \Stripe\Checkout\Session::create([
            'success_url' => $websiteDomaine . '/success/' . $stripeAbonnement->getStripeKey(),
            'cancel_url' => $websiteDomaine . '/cancel',
            'allow_promotion_codes' => true,
            'payment_method_types' => ['card'],
            'mode' => 'subscription',
            'line_items' => [[
                'price' => $stripeKey,
                // For metered billing, do not pass quantity
                'quantity' => 1,
            ]],
            "customer_email" => $user->getEmail()
        ]);

        //dd($checkout_session);

        return $this->render('abonnement/checkout.html.twig', [
            'checkout_session_id' => $checkout_session['id'],
        ]);
    }

    #[Route('/success/{stripeKey}', name: 'stripe_abonnement_success', methods: ['GET', 'POST'])]
    public function success(Stripe $stripe): Response 
    {
        /** @var User $user */
        $user = $this->getUser();
        $abonnement = $user->getAbonnement();

        if ($abonnement == null) {
            $abonnement = new Abonnement();
            $abonnement->setActive(true);
            $abonnement->setAnnuler(false);
            $abonnement->setUser($user);
            $abonnement->setStripe($stripe);
            $abonnement->setActive(true);
            $abonnement->setAnnuler(false);
            $abonnement->setStartAt(new DateTime());
            $dateFin = new \DateTime();
            $dateFin->modify('+1 month');
            $abonnement->setEndAt($dateFin);
            $this->entityManager->persist($abonnement);
            $this->entityManager->flush();
        }

        if ($abonnement) {
            $abonnement->setStartAt(new DateTime());
            $dateFin = new \DateTime();
            $dateFin->modify('+1 month');
            $abonnement->setEndAt($dateFin);
            $abonnement->setActive(true);
            $abonnement->setAnnuler(false);
            $abonnement->setStripe($stripe);
            $this->entityManager->flush();
        }

        return $this->render('abonnement/success.html.twig', [
            'stripe' => $stripe,
        ]);
    }

    #[Route('/cancel', name: 'stripe_abonnement_cancel')]
    public function cancel(): Response
    {
        return $this->render('abonnement/cancel.html.twig', []);
    }

    #[Route('/create-portal-session', name: 'stripe_abonnement_create_portal_session')]
    public function createPortalSession()
    {

        //require 'vendor/autoload.php';
        // This is a sample test API key. Sign in to see examples pre-filled with your key.
        \Stripe\Stripe::setApiKey('sk_test_VePHdqKTYQjKNInc7u56JBrQ');

        header('Content-Type: application/json');

        $YOUR_DOMAIN = 'http://localhost:4242/public/success.html';

        try {
            $checkout_session = \Stripe\Checkout\Session::retrieve($_POST['session_id']);
            $return_url = $YOUR_DOMAIN;

            // Authenticate your user.
            $session = \Stripe\BillingPortal\Session::create([
                'customer' => $checkout_session->customer,
                'return_url' => $return_url,
            ]);
            header("HTTP/1.1 303 See Other");
            header("Location: " . $session->url);
        } catch (Error $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    #[Route('/webhooks', name: 'stripe_abonnement_webhooks')]
    public function webhooks()
    {

        // This is a public sample test API key.
        // Don’t submit any personally identifiable information in requests made with this key.
        // Sign in to see your own test API key embedded in code samples.
        \Stripe\Stripe::setApiKey($this->privateKey);

        // Replace this endpoint secret with your endpoint's unique secret
        // If you are testing with the CLI, find the secret by running 'stripe listen'
        // If you are using an endpoint defined with the API or dashboard, look in your webhook settings
        // at https://dashboard.stripe.com/webhooks
        $endpoint_secret = 'whsec_12345';

        $payload = @file_get_contents('php://input');
        $event = null;
        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            echo '⚠️  Webhook error while parsing basic request.';
            http_response_code(400);
            exit();
        }
        // Handle the event
        switch ($event->type) {
            case 'customer.subscription.trial_will_end':
                $subscription = $event->data->object; // contains a \Stripe\Subscription
                // Then define and call a method to handle the trial ending.
                // handleTrialWillEnd($subscription);
                break;
            case 'customer.subscription.created':
                $subscription = $event->data->object; // contains a \Stripe\Subscription
                // Then define and call a method to handle the subscription being created.
                // handleSubscriptionCreated($subscription);
                break;
            case 'customer.subscription.deleted':
                $subscription = $event->data->object; // contains a \Stripe\Subscription
                // Then define and call a method to handle the subscription being deleted.
                // handleSubscriptionDeleted($subscription);
                break;
            case 'customer.subscription.updated':
                $subscription = $event->data->object; // contains a \Stripe\Subscription
                // Then define and call a method to handle the subscription being updated.
                // handleSubscriptionUpdated($subscription);
                break;
            default:
                // Unexpected event type
                echo 'Received unknown event type';
        }
    }

    #[Route('/create-checkout-session', name: 'stripe_abonnement_create_checkout_session')]
    public function createCheckoutSession()
    {

        // This is a public sample test API key.
        // Don’t submit any personally identifiable information in requests made with this key.
        // Sign in to see your own test API key embedded in code samples.
        $stripe = new \Stripe\StripeClient('sk_test_VePHdqKTYQjKNInc7u56JBrQ');

        header('Content-Type: application/json');

        $YOUR_DOMAIN = 'http://localhost:4242/public';

        try {
            $prices = \Stripe\Price::all([
                // retrieve lookup_key from form data POST body
                'lookup_keys' => [$_POST['lookup_key']],
                'expand' => ['data.product']
            ]);

            $checkout_session = \Stripe\Checkout\Session::create([
                'line_items' => [[
                    'price' => $prices->data[0]->id,
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => $YOUR_DOMAIN . '/success.html?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
            ]);

            header("HTTP/1.1 303 See Other");
            header("Location: " . $checkout_session->url);
        } catch (Error $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
