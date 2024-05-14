<?php

namespace App\Service;

use App\Entity\Commande;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeService
{
   private $stripeSecretKey;
   private $domaine;

   public function __construct(
      private EntityManagerInterface $entityManager,
   ) {
      /**
       * Vérification de l'environnement
       */
      if ($_ENV['APP_ENV'] === 'dev') {
         $this->stripeSecretKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
      } else {
         $this->stripeSecretKey = $_ENV['STRIPE_SECRET_KEY_LIVE'];
      }

      $this->domaine = $_ENV['SITE_DOMAINE'];
   }

   public function booster(int $amount, string $urlToRedirect)
   {
      Stripe::setApiKey($this->stripeSecretKey);
      $amountConverted = $this->concertAmount($amount);
      $token = 'GAMFAH-' . uniqid(md5('token'));

      $checkout_session = Session::create([
         'line_items' => [[
            'quantity' => 1,
            'price_data' => [
               #'currency' => Commande::DEVISE,
               'currency' => 'EUR',
               'unit_amount' => $amount * 100,
               'product_data' => [
                  'name' => "Reservation",
               ],
            ],
         ]],
         'mode' => 'payment',
         'success_url' => $this->domaine . $urlToRedirect .'?token='.$token,
         'cancel_url' => $this->domaine . 'booster/cancel',
      ]);

      return $checkout_session->url;
   }

   public function concertAmount(int $amount)
   {
      $base = 600;
      $montant = $amount / $base;
      $roundedMontant = round($montant, 2);

      return $roundedMontant;

   }
}
