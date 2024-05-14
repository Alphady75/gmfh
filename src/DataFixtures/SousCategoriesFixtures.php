<?php

namespace App\DataFixtures;

use App\Entity\SousCategorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\String\Slugger\SluggerInterface;

class SousCategoriesFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private SluggerInterface $slugger)
    {
        
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nbsouscategories = 1; $nbsouscategories <= 60; $nbsouscategories++) {

            $categorie = $this->getReference('categorie_'. $faker->numberBetween(1, 11));
            $name = 'Sous categorie-' . $nbsouscategories . ' (' . $categorie->getName() . ')';
            $souscategorie = new SousCategorie();
            $souscategorie->setName($name);
            $souscategorie->setSlug(strtolower($this->slugger->slug($name)));
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
