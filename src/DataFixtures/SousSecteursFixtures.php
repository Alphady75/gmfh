<?php

namespace App\DataFixtures;

use App\Entity\SousSecteursActivite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\String\Slugger\SluggerInterface;

class SousSecteursFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private SluggerInterface $slugger)
    {
        
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nbsoussecteurs = 1; $nbsoussecteurs <= 30; $nbsoussecteurs++) {

            $secteur = $this->getReference('secteur_'. $faker->numberBetween(1, 11));
            $name = 'Sous secteur-activité-' . $nbsoussecteurs . ' (' . $secteur->getName() . ')';

            $soussecteur = new SousSecteursActivite();
            $soussecteur->setName($name);
            $soussecteur->setSlug(strtolower($this->slugger->slug($name)));
            $soussecteur->setSecteursActivite($secteur);
            $manager->persist($soussecteur);

            // Enregistre l'utilisateur dans une référence
            $this->addReference('soussecteur_' . $nbsoussecteurs, $soussecteur);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SecteursFixtures::class,
        ];
    }
}
