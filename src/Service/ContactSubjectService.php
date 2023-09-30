<?php

namespace App\Service;

use App\Entity\ContactSubject;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ContactSubjectRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

class ContactSubjectService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ContactSubjectRepository $repository
        ){
    }

    public function addContactSubjectInDatabase(SymfonyStyle $io): void
    {
        $entities = [];

        //TODO => METTRE A JOUR EN REUNION
        //! NE PAS CHANGER
        array_push($entities,'Question pour la prochaine réunion');

        //?METTRE A JOUR
        array_push($entities,'Données personnelles');
        array_push($entities,'Demande d\'inscription','Autre demande');

        $io->title('Création sujets de contact');

        foreach($entities as $entitiesArray){

            $entity = $this->repository->findOneBy(['name' => $entitiesArray]);

            if(!$entity){
                $entity = new ContactSubject();
            }

            $entity->setName($entitiesArray);
            $this->em->persist($entity);

        }

        $this->em->flush();
        $io->success('Créations terminée');
    }

}