<?php

namespace App\DataFixtures;

use App\Entity\Offre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class OffresFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nboffres = 1; $nboffres <= 40; $nboffres++) {

            $user = $this->getReference('user_' . $faker->numberBetween(1, 30));
            $secteur = $this->getReference('secteur_' . $faker->numberBetween(1, 11));

            $offre = new Offre();
            $offre->setIntitulePost($faker->randomElement([$faker->titleMale, $faker->titleFemale]));
            $offre->setTypeContrat($faker->randomElements(['Temps plein', 'Temps partiel', 'Contrat à durée déterminée (CDD)', 'Contrat à durée indéterminée (CDI)']));
            $offre->setLangues($faker->randomElements([
                'Anglais',
                'Français'
            ]));
            $offre->setInfosContrat($faker->realText(200));
            $offre->setDateDebut($faker->dateTime);
            $offre->setDateFin($faker->dateTime);
            $offre->setName($faker->realText(150));
            $offre->setDescription($faker->realtext(300));
            $offre->setSlug('offre-'.$nboffres);
            $offre->setDescription($faker->realtext(250));
            $offre->setUser($user);
            $offre->setSecteuractivite($secteur);
            $offre->setStatus($faker->randomElement(['Actif', 'Inactif']));
            $offre->setPeriodicite($faker->randomElement(['Jour', 'Heure', 'Mois', 'Année']));
            $offre->setQualification($faker->randomElement(['License', 'Bachelor', 'Doctorat']));
            $offre->setBoosted(false);
            $offre->setComplet(true);
            $offre->setSalaire($faker->numberBetween(1000, 9000));
            $offre->setAnneeExperience($faker->numberBetween(1, 20));
            $offre->setEffectifRecrutement($faker->numberBetween(1, 50));
            $offre->setDevise($faker->randomElement(['$', 'FCFA', '€']));
            $offre->setLocalisation($faker->country . ' ' . $faker->streetAddress);
            $offre->setLieuTravail($faker->randomElement([
                'En personne', 
                'Emplacement général',
                'Télétravail Le poste s\'exerce à distance',
                'Télétravail hybride',
                'Déplacements fréquents',
            ]));

            $manager->persist($offre);

            // Enregistre l'utilisateur dans une référence
            $this->addReference('offre_' . $nboffres, $offre);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UsersFixtures::class,
            SecteursFixtures::class,
        ];
    }
}
