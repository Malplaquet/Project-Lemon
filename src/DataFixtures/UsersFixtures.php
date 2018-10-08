<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Users;

class UsersFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for ($i = 1; $i <= 10; $i++) {
          $users = new Users();
          $users->setPrenom("John $i")
                ->setNom("Doe $i")
                ->setDateDeNaissance(new \DateTime())
                ->setEmail("john.doe$i@lemonproject.com")
                ->setSexe("Non défini")
                ->setPays("France")
                ->setMetier("employé");
          $manager->persist($users);
        }
        $manager->flush();
    }
}
