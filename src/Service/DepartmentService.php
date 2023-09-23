<?php

namespace App\Service;

use League\Csv\Reader;
use App\Entity\Department;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DepartmentService
{
    public function __construct(
        private EntityManagerInterface $em,
        private DepartmentRepository $repository
        ){
    }

    public function importDepartments(SymfonyStyle $io): void
    {
        $io->title('Importation des départements');

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
        $csv = Reader::createFromPath('%kernel.root.dir%/../.docs/import/department.csv','r');
        $csv->setHeaderOffset(0);

        return $csv;
    }

    private function createOrUpdate(array $arrayEntity): Department
    {
        $entity = $this->repository->findOneBy(['id' => $arrayEntity['id']]);

        if(!$entity){
            $entity = new Department();
        }

        // "id","region_code_id","code_departement","name","slug","simplemap_code"
        
        $entity
            ->setCodeMap($arrayEntity['simplemap_code'])
            ->setName($arrayEntity['name'])
            ->setNumber($arrayEntity['code_departement']);

        return $entity;
    }

}