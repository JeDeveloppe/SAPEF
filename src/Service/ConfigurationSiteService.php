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
        private ConfigurationSiteRepository $repository
        ){
    }

    public function importConfigurationSite(SymfonyStyle $io): void
    {
        $io->title('Importation des configurations');

            $totals = $this->readCsvFile();
        
            $io->progressStart(count($totals));

            foreach($totals as $arrayTotal){

                $io->progressAdvance();
                $entity = $this->createOrUpdate($arrayTotal);
                $this->em->persist($entity);
            }
            
            $this->em->flush();

            $io->progressFinish();
        

        $io->success('Importation terminée');
    }

    private function readCsvFile(): Reader
    {
        //TODO METTRE A JOUR LES DONNEES DANS LE CSV CONFIG
        $csv = Reader::createFromPath('%kernel.root.dir%/../.docs/import/configuration_site.csv','r');
        $csv->setHeaderOffset(0);

        return $csv;
    }

    private function createOrUpdate(array $arrayEntity): ConfigurationSite
    {
        $entity = $this->repository->findOneBy(['id' => $arrayEntity['id']]);

        if(!$entity){
            $entity = new ConfigurationSite();
        }

        //"id","cotisation","email_site","delay_before_meeting_to_send_email","iban","bic"
        
        $entity
            ->setCotisation($arrayEntity['cotisation'])
            ->setEmailSite($_ENV['ADRESSE_EMAIL_SITE'])
            ->setDelayBeforeMeetingToSendEmail($arrayEntity['delay_before_meeting_to_send_email'])
            ->setIban($arrayEntity['iban'])
            ->setBic($arrayEntity['bic']);

        return $entity;
    }

}