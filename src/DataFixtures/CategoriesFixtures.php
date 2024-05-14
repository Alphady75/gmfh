<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $categories = [
            1 => [
                'name' => 'Vêtements & Mode',
                'slug' => 'vetements-et-mode',
                'emoji' => '👕',
                'icone' => 'fa fa-t-shirt1',
            ],
            2 => [
                'name' => 'Maison & Jardin',
                'slug' => 'maison-et-jardin',
                'emoji' => '🏡',
                'icone' => 'fa fa-home',
            ],
            3 => [
                'name' => 'Électronique',
                'slug' => 'electronique',
                'emoji' => '💻',
                'icone' => 'fa fa-desktop',
            ],
            4 => [
                'name' => 'Santé & Beauté',
                'slug' => 'sante-et-beaute',
                'emoji' => '⚕️',
                'icone' => 'fa fa-heartbeat',
            ],
            5 => [
                'name' => 'Idées de cadeau',
                'slug' => 'idees-de-cadeau',
                'emoji' => '🎁',
                'icone' => 'fa fa-gift',
            ],
            6 => [
                'name' => 'Jouets et jeux',
                'slug' => 'jouets-et-jeux',
                'emoji' => '🎮',
                'icone' => 'fa fa-gamepad1',
            ],
            7 => [
                'name' => 'Cuisson',
                'slug' => 'cuisson',
                'emoji' => '🍳',
                'icone' => 'fa fa-cook',
            ],
            8 => [
                'name' => 'Smart Phones',
                'slug' => 'smart-phones',
                'emoji' => '📱',
                'icone' => 'fa fa-mobile',
            ],
            9 => [
                'name' => 'Cameras & Photo',
                'slug' => 'cameras-photo',
                'emoji' => '📷',
                'icone' => 'fa fa-camera2',
            ],
            10 => [
                'name' => 'Accessoires',
                'slug' => 'accessoires',
                'emoji' => '⚒️',
                'icone' => 'fa fa-clock',
            ],
            11 => [
                'name' => 'Meubles',
                'slug' => 'meubles',
                'emoji' => '🗄️',
                'icone' => 'fa fa-sofa',
            ],
            12 => [
                'name' => 'Chaussures',
                'slug' => 'chaussures',
                'emoji' => '👟',
                'icone' => 'fa fa-shoes',
            ],
            13 => [
                'name' => 'Véhicules',
                'slug' => 'vehicules',
                'emoji' => '🚗',
                'icone' => 'fa fa-officebag',
            ],
            14 => [
                'name' => 'Matériel professionel',
                'slug' => 'matériel-professionel',
                'emoji' => '🧰',
                'icone' => 'fa fa-officebag',
            ],
            15 => [
                'name' => 'Autre',
                'slug' => 'autre',
                'emoji' => '➕',
                'icone' => 'fa fa-officebag',
            ]
        ];

        foreach($categories as $key => $value){
            $categorie = new Categorie();
            $categorie->setName($value['name']);
            $categorie->setSlug($value['slug']);
            $categorie->setComplet(true);
            $manager->persist($categorie);

            // Enregistre la catégorie dans une référence
            $this->addReference('categorie_' . $key, $categorie);
        }

        $manager->flush();
    }
}
