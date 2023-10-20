<?php

namespace App\Service;

use League\Csv\Reader;
use App\Entity\Shop;
use App\Repository\DepartmentRepository;
use App\Repository\ShopRepository;
use App\Service\Utilities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ShopService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ShopRepository $repository,
        private DepartmentRepository $departmentRepository,
        private Utilities $utilities
        ){
    }

    public function importCsvShopForDev(SymfonyStyle $io): void
    {
        $io->title('Importation des Centres');

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
        $csv = Reader::createFromPath('%kernel.root.dir%/../.docs/importForDev/shops.csv','r');
        $csv->setHeaderOffset(0);

        return $csv;
    }

    private function createOrUpdate(array $arrayEntity): Shop
    {
        $entity = $this->repository->findOneBy(['id' => $arrayEntity['id']]);

        if(!$entity){
            $entity = new Shop();
        }

        //"id","name","cm","phone","address","postal_code","city","longitude","latitude"

        $entity
        ->setName($arrayEntity['name'])
        ->setCounterMark($arrayEntity['cm'])
        ->setAddress($arrayEntity['address'])
        ->setPhone($arrayEntity['phone'])
        ->setLatitude($this->utilities->stringToNull($arrayEntity['latitude'])) 
        ->setLongitude($this->utilities->stringToNull($arrayEntity['longitude']))
        ->setDepartment($this->departmentRepository->findOneBy(['number' => substr($arrayEntity['postal_code'],0,2)]));

        return $entity;
    }

}