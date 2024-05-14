<?php

namespace App\DataFixtures;

use App\Entity\secteur;
use App\Entity\SecteursActivite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class SecteursFixtures extends Fixture
{
    private $sluger;

    public function __construct(SluggerInterface $sluger)
    {
        $this->sluger = $sluger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $secteurs = [
            1 => [
                'name' => 'Agriculture, Sylviculture et Pêche',
                'icon' => 'icone',
            ],
            2 => [
                'name' => 'Industrie manufacturière',
                'icon' => 'icone',
            ],
            3 => [
                'name' => "Technologies de l'information et de la communication (TIC)",
                'icon' => 'icone',
            ],
            4 => [
                'name' => 'Industrie manufacturière',
                'icon' => 'icone',
            ],
            5 => [
                'name' => 'Construction',
                'icon' => 'icone',
            ],
            6 => [
                'name' => 'Hôtellerie et Restauration',
                'icon' => 'icone',
            ],
            7 => [
                'name' => 'Services financiers et Assurance',
                'icon' => 'icone',
            ],
            8 => [
                'name' => 'Santé et Services sociaux',
                'icon' => 'icone',
            ],
            9 => [
                'name' => 'Éducation et Formation',
                'icon' => 'icone',
            ],
            10 => [
                'name' => 'Administration publique',
                'icon' => 'icone',
            ],
            11 => [
                'name' => 'Services professionnels (conseil, juridique, comptabilité, etc.)',
                'icon' => 'icone',
            ]
        ];

        foreach($secteurs as $key => $value){
            $secteur = new SecteursActivite();
            $secteur->setName($value['name']);
            $secteur->setSlug(strtolower($this->sluger->slug($value['name'])));
            $secteur->setComplet(true);
            $manager->persist($secteur);

            // Enregistre la catégorie dans une référence
            $this->addReference('secteur_' . $key, $secteur);
        }

        $manager->flush();
    }
}
