<?php

namespace App\Service;

use App\Entity\RegionErm;
use League\Csv\Reader;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RegionErmRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

class RegionErmService
{
    public function __construct(
        private EntityManagerInterface $em,
        private RegionErmRepository $regionErmRepository
        ){
    }

    public function importRegionsErm(SymfonyStyle $io): void
    {
        $io->title('Importation des régions Erm');

            $regions = $this->readCsvFileTotalRegions();
        
            $io->progressStart(count($regions));

            foreach($regions as $arrayRegion){

                $io->progressAdvance();
                $region = $this->createOrUpdateRegion($arrayRegion);
                $this->em->persist($region);
            }
            
            $this->em->flush();

            $io->progressFinish();
        

        $io->success('Importation terminée');
    }

    private function readCsvFileTotalRegions(): Reader
    {
        $csv = Reader::createFromPath('%kernel.root.dir%/../.docs/import/sapef_region.csv','r');
        $csv->setHeaderOffset(0);

        return $csv;
    }

    private function createOrUpdateRegion(array $arrayRegion): RegionErm
    {
        $region = $this->regionErmRepository->findOneBy(['id' => $arrayRegion['id']]);

        if(!$region){
            $region = new RegionErm();
        }
        $region->setName($arrayRegion['name'])->setColor($arrayRegion['color'])->setColorHover($arrayRegion['hover_color']);

        return $region;
    }

}