<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PostsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nbposts = 1; $nbposts <= 80; $nbposts++) {

            $user = $this->getReference('user_' . $faker->numberBetween(1, 30));
            $categorie = $this->getReference('categorie_' . $faker->numberBetween(1, 15));

            $post = new Post();
            $post->setName($faker->realtext(60));
            $post->setDescription($faker->realtext(250));
            $post->setEtat($faker->randomElement(['Neuf', 'Occasion']));
            $post->setStatut($faker->randomElement(['A vendre', 'A louer', 'Reservation']));
            $post->setUser($user);
            $post->setCategorie($categorie);
            #$post->setSouscategorie($souscategorie);
            $post->setSlug('annonce-'.$nbposts);
            $post->setTarif($faker->numberBetween(0, 100));
            $post->setTarifPromo($faker->numberBetween(0, 50));
            $post->setPromo($faker->numberBetween(0, 1));
            $post->setOnline(1);
            $post->setVedette($faker->numberBetween(0, 1));
            $post->setUrgent($faker->numberBetween(0, 1));
            $post->setIsSelled($faker->numberBetween(0, 1));
            $post->setlivraison($faker->numberBetween(0, 1));
            $post->setBoosted(false);
            $post->setBloquer(false);
            $post->setDevise($faker->randomElement(['$', 'FCFA', '€']));
            if ($post->getIsSelled() == true) {
                $post->setSellPlateform($faker->randomElement(['Gamfah', 'Ailleur']));
            }
            $manager->persist($post);

            // Enregistre l'utilisateur dans une référence
            $this->addReference('post_' . $nbposts, $post);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UsersFixtures::class,
            CategoriesFixtures::class,
        ];
    }
}
