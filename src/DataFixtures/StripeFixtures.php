<?php

namespace App\DataFixtures;

use App\Entity\Stripe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class StripeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $stripes = [
            1 => [
                'name' => 'Static',
                'tarif' => 50,
                'stripeKey' => 'price_1P1WtFKh8lE9zjqNOQpNzNCR',
            ],
            2 => [
                'name' => 'Standard',
                'tarif' => 90,
                'stripeKey' => 'price_1P1WnaKh8lE9zjqN5UYq085W',
            ],
            3 => [
                'name' => 'Premium',
                'tarif' => 100,
                'stripeKey' => 'price_1P1Ws0Kh8lE9zjqNPfMTB04B',
            ],
        ];

        foreach($stripes as $key => $value){
            $stripe = new Stripe();
            $stripe->setName($value['name']);
            $stripe->setTarif($value['tarif']);
            $stripe->setTypeTarification('mois');
            $stripe->setDevise($faker->randomElement(['$', 'FCFA', '€']));
            $stripe->setStripeKey($value['stripeKey']);
            $stripe->setComplet(true);
            $manager->persist($stripe);
            # Enregistre la catégorie dans une référence
            $this->addReference('stripe_' . $key, $stripe);
        }

        $manager->flush();
    }
}
