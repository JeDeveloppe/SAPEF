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
            ->setFirstname('Je')
            ->setLastname('Développe')
            ->setSex($this->sexStatusRepository->findOneBy(['name' => 'HOMME']))
            ->setPhone($_ENV['ADMIN_PHONE'])
            ->setShop($this->shopRepository->findOneBy(['counterMark' => 3428]))
            ->setJob($this->jobRepository->findOneBy(['name' => 'RCGO VI']))
            ->setIsAgreeTerms(true)
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

    public function initAllAdminsUsersForDev(SymfonyStyle $io)
    {

        $now = new DateTimeImmutable('now');
        $role = ['ROLE_ADMIN'];
        $password = 'Bienvenue@uSapef';
        $users = [];

        $users['René'] = [
            'email' => 'rene.wetta@euromaster.com',
            'role' => $role,
            'nickname' => 'René',
            'firstname' => 'René',
            'lastname' => 'WETTA',
            'sex' => 'HOMME',
            'phone' => $_ENV['ADMIN_PHONE'], 
            'shop' => 3428, //? Vérifier dans table SHOP
            'job' => 'RCGO VI'  //? Vérifier dans table JOB
        ];

        $users['JeanLuc'] = [
            'email' => 'jean-luc.bord@euromaster.com',
            'role' => $role,
            'nickname' => 'Jean-Luc',
            'firstname' => 'Jean-Luc',
            'lastname' => 'BORD',
            'sex' => 'HOMME',
            'phone' => '06.44.30.56.10', 
            'shop' => 3203, //? Vérifier dans table SHOP
            'job' => 'AO'  //? Vérifier dans table JOB
        ];
        $users['MarieDelphine'] = [
            'email' => 'marie-delphine.carneiro@euromaster.com',
            'role' => $role,
            'nickname' => 'M-D',
            'firstname' => 'Marie-Delphine',
            'lastname' => 'CARNEIRO',
            'sex' => 'FEMME',
            'phone' => '04.76.61.28.54', 
            'shop' => 3075, 
            'job' => 'SERVICE SUPPORT' 
        ];
        $users['Anne'] = [
            'email' => 'anne.belrepayreo@euromaster.com',
            'role' => $role,
            'nickname' => 'Anne',
            'firstname' => 'Anne',
            'lastname' => 'BELREPAYRE',
            'sex' => 'FEMME',
            'phone' => '06.12.87.76.02', 
            'shop' => 3189, 
            'job' => 'SERVICE SUPPORT' 
        ];
        $users['Thierry'] = [
            'email' => 'thierry.dussault@euromaster.com',
            'role' => $role,
            'nickname' => 'Thierry',
            'firstname' => 'Thierry',
            'lastname' => 'DUSSAULT',
            'sex' => 'HOMME',
            'phone' => '06.73.02.10.03', 
            'shop' => 3351, 
            'job' => 'SERVICE SUPPORT' 
        ];
        $users['Stephane'] = [
            'email' => 'stephane.lelievre@euromaster.com',
            'role' => $role,
            'nickname' => 'Stephane',
            'firstname' => 'Stephane',
            'lastname' => 'LELIEVRE',
            'sex' => 'HOMME',
            'phone' => '06.47.25.01.52', 
            'shop' => 3406, 
            'job' => 'RCGO VI' 
        ];
        $users['Martine'] = [
            'email' => 'martine.tessa@euromaster.com',
            'role' => $role,
            'nickname' => 'Martine',
            'firstname' => 'Martine',
            'lastname' => 'TESSA',
            'sex' => 'FEMME',
            'phone' => '06.42.89.32.06', 
            'shop' => 3075, 
            'job' => 'SERVICE SUPPORT' 
        ];
        $users['JeanPierre'] = [
            'email' => 'jean-pierre.soares@euromaster.com',
            'role' => $role,
            'nickname' => 'JP',
            'firstname' => 'Jean-Pierre',
            'lastname' => 'SOARES',
            'sex' => 'HOMME',
            'phone' => '06.60.50.74.94',
            'shop' => 3204, 
            'job' => 'CT' 
        ];
        $users['Laurent'] = [
            'email' => 'laurent.holtzer@euromaster.com',
            'role' => $role,
            'nickname' => 'Laurent',
            'firstname' => 'Laurent',
            'lastname' => 'HOLTZER',
            'sex' => 'HOMME',
            'phone' => '06.67.12.73.18',
            'shop' => 3214, 
            'job' => 'CT' 
        ];
        $users['Pascal'] = [
            'email' => 'pascal.marchant@euromaster.com',
            'role' => $role,
            'nickname' => 'Pascal',
            'firstname' => 'Pascal',
            'lastname' => 'MARCHANT',
            'sex' => 'HOMME',
            'phone' => '06.27.72.55.36',
            'shop' => 3142, 
            'job' => 'CT' 
        ];
        $users['Emilien'] = [
            'email' => 'emilien.djedi@euromaster.com',
            'role' => $role,
            'nickname' => 'Emilien',
            'firstname' => 'Emilien',
            'lastname' => 'DJEDI',
            'sex' => 'HOMME',
            'phone' => '03.83.50.19.11',
            'shop' => 3204, 
            'job' => 'TECHNICIEN EAD' 
        ];
        $users['Kévin'] = [
            'email' => 'kevin.formet@euromaster.com',
            'role' => $role,
            'nickname' => 'Kévin',
            'firstname' => 'Kévin',
            'lastname' => 'FORMET',
            'sex' => 'HOMME',
            'phone' => '06.47.25.03.08', 
            'shop' => 3203, 
            'job' => 'TECHNICIEN VI' 
        ];
        $users['Alain'] = [
            'email' => 'alain.donel@euromaster.com',
            'role' => $role,
            'nickname' => 'Alain',
            'firstname' => 'Alain',
            'lastname' => 'DONEL',
            'sex' => 'HOMME',
            'phone' => '06.31.21.53.88',
            'shop' => 3204, 
            'job' => 'TECHNICIEN EAD' 
        ];
        $users['Nicole'] = [
            'email' => 'nicole.lafarge@euromaster.com',
            'role' => $role,
            'nickname' => 'Nicole',
            'firstname' => 'Nicole',
            'lastname' => 'LAFARGE',
            'sex' => 'HOMME',
            'phone' => '04.76.29.56.30', 
            'shop' => 3075,
            'job' => 'SERVICE SUPPORT' 
        ];    
        $users['Simon'] = [
            'email' => 'simon.lambot@euromaster.com',
            'role' => $role,
            'nickname' => 'Simon',
            'firstname' => 'Simon',
            'lastname' => 'LAMBOT',
            'sex' => 'HOMME',
            'phone' => '06.67.93.13.74',
            'shop' => 3336, 
            'job' => 'TECHNICIEN VI' 
        ];
        $users['Alicia'] = [
            'email' => 'alicia.legoff@euromaster.com',
            'role' => $role,
            'nickname' => 'Alicia',
            'firstname' => 'Alicia',
            'lastname' => 'LE GOFF',
            'sex' => 'FEMME',
            'phone' => '06.41.92.24.16',
            'shop' => 3406, 
            'job' => 'ACS CGO VI' 
        ];
        $users['Quentin'] = [
            'email' => 'quentin.leloup@euromaster.com',
            'role' => $role,
            'nickname' => 'Quentin',
            'firstname' => 'Quentin',
            'lastname' => 'LELOUP',
            'sex' => 'HOMME',
            'phone' => '06.75.09.87.00', 
            'shop' => 3203,
            'job' => 'TECHNICIEN VI' 
        ];
        $users['Vincent'] = [
            'email' => 'vincent.ortega@euromaster.com',
            'role' => $role,
            'nickname' => 'Vincent',
            'firstname' => 'Vincent',
            'lastname' => 'ORTEGA',
            'sex' => 'HOMME',
            'phone' => '06.46.53.14.41', 
            'shop' => 3265,
            'job' => 'CT' 
        ];
        $users['Julien'] = [
            'email' => 'julien.rundstadler@euromaster.com',
            'role' => $role,
            'nickname' => 'Julien',
            'firstname' => 'Julien',
            'lastname' => 'RUNDSTADLER',
            'sex' => 'HOMME',
            'phone' => '06.18.12.42.08', 
            'shop' => 3203,
            'job' => 'TECHNICIEN VI' 
        ];
        $users['Benjamin'] = [
            'email' => 'benjamin.valliere@euromaster.com',
            'role' => $role,
            'nickname' => 'Benjamin',
            'firstname' => 'Benjamin',
            'lastname' => 'VALLIERE',
            'sex' => 'HOMME',
            'phone' => '07.61.51.77.16', 
            'shop' => 3078,
            'job' => 'TECHNICIEN VL' 
        ];
        $users['Thierry'] = [
            'email' => 'thierry.vivien@euromaster.com',
            'role' => $role,
            'nickname' => 'Thierry',
            'firstname' => 'Thierry',
            'lastname' => 'VIVIEN',
            'sex' => 'HOMME',
            'phone' => '00.00.00.00.00', 
            'shop' => 3204, 
            'job' => 'ACS ITINERANT' 
        ];
        $users['Philippe'] = [
            'email' => 'philippe.chambat@euromaster.com',
            'role' => $role,
            'nickname' => 'Philippe',
            'firstname' => 'Philippe',
            'lastname' => 'CHAMBAT',
            'sex' => 'HOMME',
            'phone' => '06.33.24.68.04', 
            'shop' => 3075,
            'job' => 'SERVICE SUPPORT' 
        ];

        $io->title('Création des elus');
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
            ->setSex($this->sexStatusRepository->findOneBy(['name' => $array['sex']]))
            ->setPhone($array['phone'])
            ->setShop($this->shopRepository->findOneBy(['counterMark' => $array['shop']]))
            ->setJob($this->jobRepository->findOneBy(['name' => $array['job']]))
            ->setPassword($this->userPasswordHasher->hashPassword($user, $password))
            ->setIsAgreeTerms(true);

            $this->em->persist($user);

        }

        $this->em->flush();
        $io->progressFinish();

        $io->success('Créations terminées');

    }

}