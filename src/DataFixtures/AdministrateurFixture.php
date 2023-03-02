<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AdministrateurFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //on inject l'administrateur
        $password = "$2y$12\$Bn1rYoKUgw54wz2VjCZ4tOrqxlYrSZ1NMYZDyw4X/wZl.zwmji1na";
        $admin = new User();
        $admin->setCreatedAt(new DateTimeImmutable('now'))
            ->setEmail("jedeveloppe.contact@gmail.com")
            ->setPassword($password)
            ->setRoles(["ROLE_SUPER_ADMIN"])
            ->setPhone(0000000000);
            
        $manager->persist($admin);
        $manager->flush();

    }
}
