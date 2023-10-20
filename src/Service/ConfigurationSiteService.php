<?php

namespace App\Service;

use App\Entity\ConfigurationSite;
use League\Csv\Reader;
use App\Entity\Department;
use App\Repository\ConfigurationSiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConfigurationSiteService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ConfigurationSiteRepository $configurationSiteRepository
        ){
    }

    public function addConfigurationSite(SymfonyStyle $io)
    {

        $config[] = [
            'cotisation' => 2500, //cotisation in cents
            'delay' => 15,  //delay before meeting to send email
            'iban' => "FR76 1470 7000 3932 5212 0434 040", //iban
            'bic' => "&&&&&" //bic from iban
        ];

        $io->title('Création de la configuration');

        foreach($config as $configArray){

            $entity = $this->configurationSiteRepository->findOneBy(['Iban' => $configArray['iban']]);

            if(!$entity){
                $entity = new ConfigurationSite();
            }
            
            $entity
                ->setCotisation($configArray['cotisation'])
                ->setEmailSite($_ENV['ADRESSE_EMAIL_SITE'])
                ->setDelayBeforeMeetingToSendEmail($configArray['delay'])
                ->setIban($configArray['iban'])
                ->setBic($configArray['bic']);

            $this->em->persist($entity);
        }

        $this->em->flush();
        $io->success('Créations terminée');

    }
}