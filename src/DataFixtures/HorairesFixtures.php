<?php

namespace App\DataFixtures;

use App\Entity\Horaires;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class HorairesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $horaires = [
            1 => [
                'name' => 'Temps plein',
            ],
            2 => [
                'name' => 'Temps partiel',
            ],
            3 => [
                'name' => 'Horaires flexibles',
            ],
            4 => [
                'name' => 'Travail par quarts',
            ],
            5 => [
                'name' => "Horaires de travail compressés",
            ],
            6 => [
                'name' => 'Travail à distance',
            ],
            7 => [
                'name' => 'Travail saisonnier',
            ]
        ];

        foreach($horaires as $key => $value){
            $offre = $this->getReference('offre_' . $faker->numberBetween(1, 40));
            $horaire = new Horaires();
            $horaire->setName($value['name']);
            $horaire->setOffre($offre);
            $manager->persist($horaire);

            // Enregistre la catégorie dans une référence
            $this->addReference('horaire_' . $key, $horaire);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            OffresFixtures::class,
        ];
    }
}
