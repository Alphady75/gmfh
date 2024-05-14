<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ArticlesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nbarticles = 1; $nbarticles <= 30; $nbarticles++) {

            $user = $this->getReference('user_' . $faker->numberBetween(1, 30));
            $categorie = $this->getReference('articlecategorie_' . $faker->numberBetween(1, 6));

            $article = new Article();
            $article->setName($faker->realtext(150));
            $article->setDescription($faker->realtext(400));
            $article->setResume($faker->realtext(250));
            $article->setUser($user);
            $article->setCategorie($categorie);
            $article->setSlug('article-'.$nbarticles);
            $article->setOnline($faker->numberBetween(0, 1));
            $article->setComplet(1);
            $manager->persist($article);

            // Enregistre l'utilisateur dans une référence
            $this->addReference('article_' . $nbarticles, $article);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UsersFixtures::class,
            ArtcilesCategoriesFixtures::class,
        ];
    }
}
