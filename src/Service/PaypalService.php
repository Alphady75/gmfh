<?php

namespace App\Service;

use App\Entity\Annonce;
use App\Entity\Modalite;

class PaypalService
{

	private $paypalkey;

   public function __construct()
   {
      if ($_ENV['APP_ENV'] === 'dev') {
         $this->paypalkey = $_ENV['PAYPAL_SECRET_KEY_TEST'];
      } else {
         $this->paypalkey = $_ENV['PAYPAL_SECRET_KEY_LIVE'];
      }
   }

   public function getPaypalKey()
   {
      return $this->paypalkey;
   }

	/*public function getUserInterface(Annonce $annonce, Modalite $modalite): string
   {
      $clientId = $this->paypalkey;

      $prix = $modalite->getPrix();

      $order = json_encode([
         'purchase_units' => [[
            "description" => "Offre " . $modalite->getOffres(),
            'amount' => [
               'currency_code' => Annonce::DEVISE,
               'value' => $prix,
               'breackdown' => [
                  'item_total' => [
                     'currency_code' => Annonce::DEVISE, $prix,
                     'value' => $prix
                  ]
               ],
            ]
         ]]
      ]);

      return <<<HTML
   <!-- Replace "test" with your own sandbox Business account app client ID -->
	<script src="https://www.paypal.com/sdk/js?client-id={$clientId}&currency=EUR&intent=authorize"></script>
	<!-- Set up a container element for the button -->
	<div id="paypal-button-container" class="mt-3"></div>
	<script>
		paypal.Buttons({
			// Sets up the transaction when a payment button is clicked
			createOrder: (data, actions) => {
				return actions.order.create({$order});
			},
			// Finalize the transaction after payer approval
			onApprove(data) {
          actions.order.authorize().then(function(authorization){
				console.log({authorization, data});
				const authorizationId = authorization.purchase_units[0].payments.authorization[0].id
			 })
        },
		  onCancel(data) {
			alert('Annuler');
		}
		}).render('#paypal-button-container');
	</script>
HTML;
   }*/
}
