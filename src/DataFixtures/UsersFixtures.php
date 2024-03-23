<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class UsersFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($nbUsers = 1; $nbUsers <= 30; $nbUsers++) {
            $user = new User();
            if ($nbUsers == 1) {

                $user->setEmail('admin@gmail.com');
                $user->setRoles(['ROLE_ADMIN']);
                $user->setCompte('Administrateur');

            } else {

                $user->setCompte($faker->randomElement(['PERSONNEL', 'ENTREPRISE']));

                if ($user->getCompte() == 'PERSONNEL') {
                    $user->setEmail('client' . $nbUsers . '@gmail.com');
                    $user->setRoles(['ROLE_PERSONNEL']);
                } elseif ($user->getCompte() == 'ENTREPRISE') {

                    $user->setRoles(['ROLE_ENTREPRISE']);
                    $user->setEmail('entreprise' . $nbUsers . '@gmail.com');
                    $user->setSociete($faker->company);
                    $user->setNiu($faker->companySuffix);
                    $user->setSiteWeb($faker->domainName);
                }
            }

            $user->setIsVerified(true);
            $user->setPrenom($faker->firstName());
            $user->setNom($faker->lastName());
            $user->setTelephone($faker->phoneNumber);
            $user->setVilleResidence($faker->city);
            $user->setApropo($faker->realText(300));
            $user->setNameSlug($faker->firstName() . '-' . $faker->lastName());
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
}
