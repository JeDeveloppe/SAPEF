<?php

namespace App\Service;

use App\Entity\SexStatus;
use App\Repository\SexStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenderService
{
    public function __construct(
        private EntityManagerInterface $em,
        private SexStatusRepository $sexStatusRepository
        ){
    }

    public function addGendersForDev(SymfonyStyle $io){

        $sexs = [];

        //TODO => METTRE A JOUR EN REUNION
        //! NE PAS CHANGER
        array_push($sexs,'HOMME', 'FEMME');

        //?METTRE A JOUR
        array_push($sexs,'JE NE VEUX PAS LE RENSEIGNER');

        foreach($sexs as $sexArray){

            $sex = $this->sexStatusRepository->findOneBy(['name' => $sexArray]);

            if(!$sex){
                $sex = new SexStatus();
            }

            $sex->setName($sexArray);
            $this->em->persist($sex);

        }
        $this->em->flush();
        $io->success('Créations terminée');
    }
}