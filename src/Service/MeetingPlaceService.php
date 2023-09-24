<?php

namespace App\Service;

use App\Entity\MeetingPlace;
use App\Repository\MeetingPlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MeetingPlaceService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MeetingPlaceRepository $repository
        ){
    }

    public function addMeetingPlace(SymfonyStyle $io): void
    {
        $entities = [];

        //TODO FAIRE LISTE DES ENTITIES
        //! NE PAS CHANGER
        array_push($entities,'Siège Euromaster');

        //?METTRE A JOUR
        array_push($entities,'Non définie');

        $io->title('Création place de réunion');

        foreach($entities as $entitiesArray){

            $entity = $this->repository->findOneBy(['name' => $entitiesArray]);

            if(!$entity){
                $entity = new MeetingPlace();
            }

            $entity->setName($entitiesArray);
            $this->em->persist($entity);

        }

        $this->em->flush();
        $io->success('Créations terminée');
    }

}