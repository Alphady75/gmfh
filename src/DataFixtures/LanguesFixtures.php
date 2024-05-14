<?php

namespace App\DataFixtures;

use App\Entity\Langue;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LanguesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $langues = [
            1 => [
                'name' => 'Français',
            ],
            2 => [
                'name' => 'Anglais',
            ],
            3 => [
                'name' => "Espaniole",
            ],
            4 => [
                'name' => 'Italien',
            ],
        ];

        foreach($langues as $key => $value){
            $langue = new Langue();
            $langue->setName($value['name']);
            $manager->persist($langue);

            // Enregistre la catégorie dans une référence
            $this->addReference('langue_' . $key, $langue);
        }

        $manager->flush();
    }
}
