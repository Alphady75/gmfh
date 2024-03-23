<?php

namespace App\DataFixtures;

use App\Entity\Booster;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class BoostersFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $booters = [
            1 => [
                'name' => 'Boost Standard',
                'description' => "Le boost standard permet à votre annonce d'apparaître en haut des résultats de recherche pendant une période définie.",
                'tarif' => 5,
                'duree' => "24 heures",
            ],
            2 => [
                'name' => 'Boost Premium',
                'description' => "Le boost premium offre une visibilité maximale en plaçant votre annonce en haut des résultats de recherche et en la mettant en évidence pour attirer davantage l'attention des utilisateurs.",
                'tarif' => 10,
                'duree' => "24 heures",
            ],
            3 => [
                'name' => 'Boost Urgent',
                'description' => " Le boost urgent attire l'attention sur le caractère urgent de votre annonce en la plaçant en haut des résultats de recherche avec un badge \"Urgent\" et en la mettant en évidence.",
                'tarif' => 8,
                'duree' => "24 heures",
            ]
        ];

        foreach ($booters as $key => $value) {
            $booter = new Booster();
            $booter->setName($value['name']);
            $booter->setDuree($value['duree']);
            $booter->setDescription($value['description']);
            $booter->setTarif($value['tarif']);
            $booter->setStartAt(new DateTime());
            $booter->setEndAt(new DateTime());
            $manager->persist($booter);

            // Enregistre la catégorie dans une référence
            $this->addReference('booter_' . $key, $booter);
        }

        $manager->flush();
    }
}
