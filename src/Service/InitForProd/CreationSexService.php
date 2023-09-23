<?php

namespace App\Service\InitForProd;

use App\Entity\sex;
use App\Entity\SexStatus;
use App\Repository\sexRepository;
use App\Repository\SexStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreationSexService
{
    public function __construct(
        private EntityManagerInterface $em,
        private SexStatusRepository $sexStatusRepository
        ){
    }

    public function addsex(SymfonyStyle $io){


        $sexs = [];

        //TODO FAIRE LISTE DES SEXES
        //! NE PAS CHANGER
        array_push($sexs,'HOMME', 'FEMME');

        //?METTRE A JOUR
        array_push($sexs,'AUTRE', 'JE NE VEUX PAS LE RENSEIGNER');

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