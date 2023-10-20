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


            //! ADRESSE EMAIL SITE DANS .ENV a verifier
            $legal
                ->setCompanyName('LE SAPEF')
                ->setStreetCompany('39 boulevard Paul Langevin')
                ->setPostalCodeCompany(38600)
                ->setCityCompany('FONTAINE')
                ->setPublicationManagerFirstName('Philippe')
                ->setPublicationManagerLastName('CHAMBAT')
                ->setSiretCompany('000') //TODO a revoir
                ->setFullUrlCompany($_ENV['URL_SITE'])
                ->setEmailCompany($_ENV['ADRESSE_EMAIL_SITE']) 
                //!NE PAS CHANGER LA SUITE
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