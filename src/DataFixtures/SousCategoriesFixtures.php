<?php

namespace App\DataFixtures;

use App\Entity\SousCategorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class SousCategoriesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nbsouscategories = 1; $nbsouscategories <= 60; $nbsouscategories++) {

            $categorie = $this->getReference('categorie_'. $faker->numberBetween(1, 11));
            $souscategorie = new SousCategorie();
            $souscategorie->setName('Sous categorie-' . $nbsouscategories . ' (' . $categorie->getName() . ')');
            $souscategorie->setCategorie($categorie);
            $manager->persist($souscategorie);

            // Enregistre l'utilisateur dans une référence
            $this->addReference('souscategorie_' . $nbsouscategories, $souscategorie);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoriesFixtures::class,
        ];
    }
}
