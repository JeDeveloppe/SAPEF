<?php

namespace App\Service;

use App\Entity\City;
use League\Csv\Reader;
use App\Repository\CityRepository;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CityService
{
    public function __construct(
        private EntityManagerInterface $em,
        private CityRepository $repository,
        private DepartmentRepository $departmentRepository
        ){
    }

    public function importCsvCityForDev(SymfonyStyle $io): void
    {
        $io->title('Importation des villes');

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
        $csv = Reader::createFromPath('%kernel.root.dir%/../.docs/importForDev/villes_france.csv','r');
        $csv->setHeaderOffset(0);

        return $csv;
    }

    private function createOrUpdate(array $arrayEntity): City
    {
        $entity = $this->repository->findOneBy(['id' => $arrayEntity['ville_id']]);

        if(!$entity){
            $entity = new City();
        }

        // "ville_id","ville_departement","ville_slug","ville_nom","name","ville_code_postal","lng","lat","actif"
        
        $entity
            ->setDepartment($this->departmentRepository->findOneBy(['number' => $arrayEntity['ville_departement']]))
            ->setName($arrayEntity['name'])
            ->setPostalCode($arrayEntity['ville_code_postal'])
            ->setLatitude($arrayEntity['lat'])
            ->setLongitude($arrayEntity['lng']);

        return $entity;
    }
}