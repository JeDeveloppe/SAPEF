<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\CountryRepository;
use App\Repository\JobRepository;
use App\Repository\ShopRepository;
use DateTimeImmutable;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $userPasswordHasher,
        private UserRepository $userRepository,
        private JobRepository $jobRepository,
        private ShopRepository $shopRepository
        ){
    }

    public function initForProd_adminUser($io): void
    {
        $io->title('Création / mise à jour de l\'user ADMIN');

        //on vérifié si on a déjà créé l'admin
        $user = $this->userRepository->findOneBy(['email' => $_ENV['ADMIN_EMAIL']]);

        if(!$user){

            $user = new User();
        }

        $user->setCreatedAt(new DateTimeImmutable('now'))
            ->setLastVisiteAt(new DateTimeImmutable('now'))
            ->setEmail($_ENV['ADMIN_EMAIL'])
            ->setRoles(['ROLE_SUPER_ADMIN'])
            ->setNickname('JeDéveloppe')
            ->setPhone($_ENV['ADMIN_PHONE'])
            ->setShop($this->shopRepository->findOneBy(['counterMark' => '3428']))
            ->setJob($this->jobRepository->findOneBy(['name' => 'RCGO VI']))
            ->setPassword(
                $this->userPasswordHasher->hashPassword(
                        $user,
                        $_ENV['ADMIN_PASSWORD']
                    )
                );

        $this->em->persist($user);
        $this->em->flush();

        $io->success('Admin créé / mise à jour!');

    }
}