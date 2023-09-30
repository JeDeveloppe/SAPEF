<?php

namespace App\Service;

use App\Entity\MeetingName;
use App\Repository\MeetingNameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MeetingNameService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MeetingNameRepository $repository
        ){
    }

    public function addMeetingNameForDev(SymfonyStyle $io): void
    {
        $entities = [];

        //! NE PAS CHANGER
        array_push($entities,'CSE');

        //?METTRE A JOUR
        array_push($entities,'Non définie');

        $io->title('Création type de réunion');

        foreach($entities as $entitiesArray){

            $entity = $this->repository->findOneBy(['name' => $entitiesArray]);

            if(!$entity){
                $entity = new MeetingName();
            }

            $entity->setName($entitiesArray);
            $this->em->persist($entity);

        }

        $this->em->flush();
        $io->success('Création terminée');
    }

}