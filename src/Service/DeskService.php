<?php

namespace App\Service;

use App\Entity\DeskRole;
use App\Repository\DeskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DeskService
{
    public function __construct(
        private EntityManagerInterface $em,
        private DeskRepository $repository
        ){
    }

    public function addMeetingNameForDev(SymfonyStyle $io): void
    {
        $entities = [];

        //TODO METTRE A JOUR
        $entities = ['PRÉSIDENT','VICE- PRÉSIDENT', 'SECRETAIRE', 'SECRETAIRE ADJOINT','TRESORIER','TRESORIER ADJOINT','DÉLÉGUÉ AUX RELATIONS EXTÉRIEURES'];

        $io->title('Création des rôles du bureau');

        foreach($entities as $entitiesArray){

            $entity = $this->repository->findOneBy(['name' => $entitiesArray]);

            if(!$entity){
                $entity = new DeskRole();
            }

            $entity->setName($entitiesArray);
            $this->em->persist($entity);

        }

        $this->em->flush();
        $io->success('Création terminée');
    }

}