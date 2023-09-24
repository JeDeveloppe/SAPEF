<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\CountryRepository;
use App\Repository\JobRepository;
use App\Repository\SexStatusRepository;
use App\Repository\ShopRepository;
use DateTimeImmutable;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $userPasswordHasher,
        private UserRepository $userRepository,
        private JobRepository $jobRepository,
        private ShopRepository $shopRepository,
        private SexStatusRepository $sexStatusRepository
        ){
    }

    public function initAdminUserForDev($io): void
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
            ->setFirstname('René')
            ->setLastname('WETTA')
            ->setSex($this->sexStatusRepository->findOneBy(['name' => 'HOMME']))
            ->setPhone($_ENV['ADMIN_PHONE'])
            ->setShop($this->shopRepository->findOneBy(['counterMark' => 3428]))
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

    public function initAllAdminsUsersForDev(SymfonyStyle $io){

        $now = new DateTimeImmutable('now');
        $password = 'Bienvenue@uSapef';
        $users = [];

        $users['JeanLuc'] = [
            'email' => 'jean-luc.bord@euromaster.com',
            'role' => ['ROLE_ADMIN'],
            'nickname' => 'Jean-Luc',
            'firstname' => 'Jean-Luc',
            'lastname' => 'BORD',
            'sex' => $this->sexStatusRepository->findOneBy(['name' => 'HOMME']), //? HOMME FEMME
            'phone' => '00.00.00.00.00',
            'shop' => $this->shopRepository->findOneBy(['counterMark' => 3203]), //? Vérifier dans table SHOP
            'job' => $this->jobRepository->findOneBy(['name' => 'AO'])  //? Vérifier dans table JOB
        ];
        $users['Philippe'] = [
            'email' => 'philippe.chambat@euromaster.com',
            'role' => ['ROLE_ADMIN'],
            'nickname' => 'Philippe',
            'firstname' => 'Philippe',
            'lastname' => 'CHAMBAT',
            'sex' => $this->sexStatusRepository->findOneBy(['name' => 'HOMME']), //? HOMME FEMME
            'phone' => '00.00.00.00.00',
            'shop' => $this->shopRepository->findOneBy(['counterMark' => 0000]), //? Vérifier dans table SHOP
            'job' => $this->jobRepository->findOneBy(['name' => 'AO']) //? Vérifier dans table JOB
        ];
        $users['Thierry'] = [
            'email' => 'thierry.vivien@euromaster.com',
            'role' => ['ROLE_ADMIN'],
            'nickname' => 'Thierry',
            'firstname' => 'Thierry',
            'lastname' => 'VIVIEN',
            'sex' => $this->sexStatusRepository->findOneBy(['name' => 'HOMME']), //? HOMME FEMME
            'phone' => '00.00.00.00.00',
            'shop' => $this->shopRepository->findOneBy(['counterMark' => 0000]), //? Vérifier dans table SHOP
            'job' => $this->jobRepository->findOneBy(['name' => 'ACS ITINERANT']) //? Vérifier dans table JOB
        ];
        $users['Thierry'] = [
            'email' => 'marie-delphine.carneiro@euromaster.com',
            'role' => ['ROLE_ADMIN'],
            'nickname' => 'M-D',
            'firstname' => 'Marie-Delphine',
            'lastname' => 'CARNEIRO',
            'sex' => $this->sexStatusRepository->findOneBy(['name' => 'FEMME']), //? HOMME FEMME
            'phone' => '00.00.00.00.00',
            'shop' => $this->shopRepository->findOneBy(['counterMark' => 0000]), //? Vérifier dans table SHOP
            'job' => $this->jobRepository->findOneBy(['name' => 'ACS ITINERANT']) //? Vérifier dans table JOB
        ];
        

        $io->title('Création des rôles du bureau');
        $io->progressStart(count($users));

        foreach($users as $array){
            $io->progressAdvance();

            $user = $this->userRepository->findOneBy(['email' => $array['email']]);

            if(!$user){
                $user = new User();
            }

            $user->setCreatedAt($now)
            ->setLastVisiteAt($now)
            ->setEmail($array['email'])
            ->setRoles($array['role'])
            ->setNickname($array['nickname'])
            ->setFirstname($array['firstname'])
            ->setLastname($array['lastname'])
            ->setSex($array['sex'])
            ->setPhone($array['phone'])
            ->setShop($array['shop'])
            ->setJob($array['job'])
            ->setPassword(
                $this->userPasswordHasher->hashPassword(
                        $user,
                        $password
                    )
                );

            $this->em->persist($user);
        }

        $this->em->flush();
        $io->progressFinish();

        $io->success('Créations terminées');
    }

}