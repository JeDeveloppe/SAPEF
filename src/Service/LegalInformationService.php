<?php

namespace App\Service;

use App\Entity\LegalInformation;
use App\Repository\LegalInformationRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LegalInformationService
{
    public function __construct(
        private LegalInformationRepository $legalInformationRepository,
        private EntityManagerInterface $manager,
        private UserRepository $userRepository
        ){
    }

    public function addLegalInformationForDev(SymfonyStyle $io): void
    {        
        
        $legal = $this->legalInformationRepository->findOneBy(['companyName' => 'LE SAPEF']);

        if(!$legal){
            $legal = new LegalInformation();
        }

            $io->title('Création / mise à jour des informations légales');

            //TODO => METTRE A JOUR EN REUNION
            //! ADRESSE EMAIL SITE DANS .ENV a verifier
            $legal
                ->setCompanyName('LE SAPEF')
                ->setStreetCompany('24 rue froide')
                ->setPostalCodeCompany(14980)
                ->setCityCompany('ROTS')
                ->setPublicationManagerFirstName('Antoine')
                ->setPublicationManagerLastName('GALLÉE')
                ->setSiretCompany('88847646200013')
                ->setFullUrlCompany('http://www.refaitesvosjeux.fr')
                //!NE PAS CHANGER LA SUITE
                ->setEmailCompany($_ENV['ADRESSE_EMAIL_SITE']) 
                ->setHostName('IONOS SARL')
                ->setHostStreet('7 place de la gare')
                ->setHostPostalCode(57200)
                ->setHostCity('SARREGUEMINES')
                ->setHostPhone('09.70.80.89.11')
                ->setWebmasterCompanyName('Je-Développe')
                ->setWebmasterFistName('René')
                ->setWebmasterLastName('WETTA')
                ->setIsOnline(true)
                ->setUpdatedBy($this->userRepository->findOneBy(['email' => $_ENV['ADMIN_EMAIL']]))
                ->setUpdatedAt(new DateTimeImmutable('now'));
    
            $this->manager->persist($legal);
            $this->manager->flush();

            $io->success('Importation terminée');

    }

}