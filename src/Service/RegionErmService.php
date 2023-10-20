<?php

namespace App\Service;

use App\Entity\RegionErm;
use App\Repository\DepartmentRepository;
use League\Csv\Reader;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RegionErmRepository;
use Doctrine\ORM\Cache\Region;
use Symfony\Component\Console\Style\SymfonyStyle;

class RegionErmService
{
    public function __construct(
        private EntityManagerInterface $em,
        private RegionErmRepository $regionErmRepository,
        private DepartmentRepository $departmentRepository
        ){
    }

    public function importRegionsErmForDev(SymfonyStyle $io): void
    {
        $io->title('Importation des régions Erm');

            $regions = $this->readCsvFileTotalRegionsForDev();
        
            $io->progressStart(count($regions));

            foreach($regions as $arrayRegion){

                $io->progressAdvance();
                $region = $this->createOrUpdateRegionForDev($arrayRegion);
                $this->em->persist($region);
            }
            
            $this->em->flush();

            $io->progressFinish();
        

        $io->success('Importation terminée');
    }

    private function readCsvFileTotalRegionsForDev(): Reader
    {
        $csv = Reader::createFromPath('%kernel.root.dir%/../.docs/importForDev/sapef_region.csv','r');
        $csv->setHeaderOffset(0);

        return $csv;
    }

    private function createOrUpdateRegionForDev(array $arrayRegion): RegionErm
    {
        $region = $this->regionErmRepository->findOneBy(['id' => $arrayRegion['id']]);

        if(!$region){
            $region = new RegionErm();
        }
        $region->setName($arrayRegion['name'])->setColor($arrayRegion['color'])->setColorHover($arrayRegion['hover_color']);

        return $region;
    }

    public function addDepartmentsToRegion(SymfonyStyle $io): void
    {
        $io->title('Importation des départements dans les régions Erm');

            $relations = $this->readCsvFileRegion_Departments();
        
            $io->progressStart(count($relations));

            foreach($relations as $arrayRelation){

                $io->progressAdvance();
                $region = $this->createOrUpdateRelationRegionDepartments($arrayRelation);

                $this->em->persist($region);

            }
            
            $this->em->flush();

            $io->progressFinish();
        

        $io->success('Importation terminée');
    }

    private function readCsvFileRegion_Departments(): Reader
    {
        $csv = Reader::createFromPath('%kernel.root.dir%/../.docs/importForDev/region_erm_department.csv','r');
        $csv->setHeaderOffset(0);

        return $csv;
    }

    private function createOrUpdateRelationRegionDepartments(array $arrayRelation): RegionErm
    {
        $region = $this->regionErmRepository->findOneBy(['id' => $arrayRelation['region_erm_id']]);
        $department = $this->departmentRepository->findOneBy(['id' => $arrayRelation['department_id']]);

        $region->addDepartment($department);

        return $region;
    }
}