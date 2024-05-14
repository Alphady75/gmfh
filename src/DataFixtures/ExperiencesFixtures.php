<?php

namespace App\DataFixtures;

use App\Entity\Experiences;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ExperiencesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $experiences = [
            1 => [
                'name' => 'Maîtrise de logiciels',
            ],
            2 => [
                'name' => 'Maîtrise de Microsoft Office',
            ],
            3 => [
                'name' => 'Connaissance avancée de Python',
            ],
            4 => [
                'name' => 'Capacités de communication',
            ],
            5 => [
                'name' => "Aptitudes au travail d'équipe",
            ],
            6 => [
                'name' => 'Diplôme universitaire en marketing',
            ],
            7 => [
                'name' => 'Compétences avancées en tableurs',
            ],
            8 => [
                'name' => 'Bonne communication en équipe',
            ],
            9 => [
                'name' => 'Maitrise du traitement de texte',
            ],
            10 => [
                'name' => 'Capacité à utiliser Internet',
            ],
        ];

        foreach($experiences as $key => $value){
            $offre = $this->getReference('offre_' . $faker->numberBetween(1, 40));
            $experience = new Experiences();
            $experience->setName($value['name']);
            $experience->setOffre($offre);
            $manager->persist($experience);

            // Enregistre la catégorie dans une référence
            $this->addReference('experience_' . $key, $experience);
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
