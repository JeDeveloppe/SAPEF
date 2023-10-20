<?php

namespace App\Service;

use App\Entity\Desk;
use DateTimeImmutable;
use League\Csv\Reader;
use App\Entity\DeskRole;
use App\Repository\DeskRepository;
use App\Repository\DeskRoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DeskService
{
    public function __construct(
        private EntityManagerInterface $em,
        private DeskRepository $deskRepository,
        private UserRepository $userRepository,
        private DeskRoleRepository $deskRoleRepository
        ){
    }

    public function addDeskStatusForDev(SymfonyStyle $io): void
    {
        $entities = [];

        $entities = ['PRÉSIDENT','VICE- PRÉSIDENT', 'SECRETAIRE', 'SECRETAIRE ADJOINT','TRESORIER','TRESORIER ADJOINT','DÉLÉGUÉ AUX RELATIONS EXTÉRIEURES'];

        $io->title('Création des rôles du bureau');

        foreach($entities as $entitiesArray){

            $entity = $this->deskRepository->findOneBy(['name' => $entitiesArray]);

            if(!$entity){
                $entity = new DeskRole();
            }

            $entity->setName($entitiesArray);
            $this->em->persist($entity);

        }

        $this->em->flush();
        $io->success('Création terminée');
    }

    public function importDeskForProd(SymfonyStyle $io): void
    {
        $io->title('Importation du bureau');

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
        $csv = Reader::createFromPath('%kernel.root.dir%/../.docs/importForProd/desk.csv','r');
        $csv->setHeaderOffset(0);

        return $csv;
    }

    private function createOrUpdate(array $arrayEntity): Desk
    {

        $entity = $this->deskRepository->find($arrayEntity['id']);

        if(!$entity){
            $entity = new Desk();
        }

        //"id","name_id","role_id","updated_by_id","updated_at"


        $entity
            ->setUpdatedAt(new DateTimeImmutable('now'))
            ->setRole($this->deskRoleRepository->find($arrayEntity['role_id']))
            ->setName($this->userRepository->find($arrayEntity['name_id']))
            ->setUpdatedBy($this->userRepository->find($arrayEntity['name_id']));

        return $entity;
    }
}