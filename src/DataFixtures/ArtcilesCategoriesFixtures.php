<?php

namespace App\DataFixtures;

use App\Entity\ArticleCategorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArtcilesCategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            1 => [
                'name' => 'Education',
                'slug' => 'education',
            ],
            2 => [
                'name' => 'Information',
                'slug' => 'information',
            ],
            3 => [
                'name' => 'Emplois',
                'slug' => 'emplois',
            ],
            4 => [
                'name' => 'Technologie',
                'slug' => 'technologie',
            ],
            5 => [
                'name' => 'Science',
                'slug' => 'science',
            ],
            6 => [
                'name' => 'Fitness',
                'slug' => 'fitness',
            ]
        ];

        foreach($categories as $key => $value){
            $categorie = new ArticleCategorie();
            $categorie->setName($value['name']);
            $categorie->setSlug($value['slug']);
            $manager->persist($categorie);

            // Enregistre la catégorie dans une référence
            $this->addReference('articlecategorie_' . $key, $categorie);
        }

        $manager->flush();
    }
}
