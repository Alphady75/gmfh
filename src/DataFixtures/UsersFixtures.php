<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;
use Symfony\Component\String\Slugger\SluggerInterface;

class UsersFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder, private SluggerInterface $slugger)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nbUsers = 1; $nbUsers <= 30; $nbUsers++) {
            
            $soussecteur = $this->getReference('soussecteur_' . $faker->numberBetween(1, 30));

            $user = new User();
            if ($nbUsers == 1) {

                $user->setEmail('admin@gmail.com');
                $user->setRoles(['ROLE_ADMINISTRATEUR']);
                $user->setCompte('ADMINISTRATEUR');

            } else {

                $user->setCompte($faker->randomElement(['PERSONNEL', 'ENTREPRISE', 'PARTICULIER']));

                if ($user->getCompte() == 'PERSONNEL') {
                    $user->setEmail('client' . $nbUsers . '@gmail.com');
                    $user->setRoles(['ROLE_PERSONNEL']);
                    $user->setAnnuaire(false);
                    $user->setPeriodicite($faker->randomElement(['Jour', 'Heure', 'Mois', 'Année']));

                } elseif ($user->getCompte() == 'ENTREPRISE') {

                    $user->setRoles(['ROLE_ENTREPRISE']);
                    $user->setEmail('entreprise' . $nbUsers . '@gmail.com');
                    $user->setSociete($faker->company);
                    $user->setNiu($faker->companySuffix);
                    $user->setSiteWeb($faker->domainName);
                    $user->setAnnuaire(true);
                    $user->setsoussecteuractivite($soussecteur);
                    $user->setSecteuractivite($soussecteur->getSecteursActivite());

                } elseif ($user->getCompte() == 'PARTICULIER') {

                    $user->setRoles(['ROLE_PARTICULIER']);
                    $user->setEmail('particulier' . $nbUsers . '@gmail.com');
                    $user->setSalaire($faker->numberBetween(10, 9000));
                    $user->setDevise($faker->randomElement(['$', 'FCFA', '€']));
                    $user->setAnnuaire(true);
                    $user->setSoussecteuractivite($soussecteur);
                    $user->setSecteuractivite($soussecteur->getSecteursActivite());
                    $user->setPeriodicite($faker->randomElement(['Jour', 'Heure', 'Mois', 'Année']));
                    $user->setQualification($faker->randomElement(['License', 'Bachelor', 'Doctorat']));

                }
            }

            $user->setIsVerified(true);
            $user->setPrenom($faker->firstName());
            $user->setQualification($faker->company);
            $user->setNom($faker->lastName());
            $user->setNameSlug($this->slugger->slug(strtolower($faker->firstName() . '-' . $faker->lastName())));
            $user->setTelephone($faker->phoneNumber);
            $user->setlocalisation($faker->city);
            $user->setApropo($faker->realText(300));
            $user->setResetPasswordCode($faker->numberBetween(100000, 900000));
            $user->setCodeIsVerified($faker->numberBetween(100000, 900000));
            $user->setCompleted(true);
            $user->setPassword($this->encoder->hashPassword($user, 'azerty'));
            $manager->persist($user);

            // Enregistre l'utilisateur dans une référence
            $this->addReference('user_' . $nbUsers, $user);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            SousSecteursFixtures::class,
        ];
    }
}
