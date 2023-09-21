<?php

namespace App\Service\InitForProd;

use App\Entity\MeanOfPaiement;
use App\Repository\MeanOfPaiementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreationMoyenDePaiementService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MeanOfPaiementRepository $meanOfPaiementRepository
        ){
    }

    public function addMoyens(SymfonyStyle $io){

        $moyens = ['CB','ESPÈCES','CHQ','VIR'];

        foreach($moyens as $moyenArray){

            $moyen = $this->meanOfPaiementRepository->findOneBy(['name' => $moyenArray]);

            if(!$moyen){
                $moyen = new MeanOfPaiement();
            }

            $moyen->setName($moyenArray);
            $this->em->persist($moyen);

        }
        $this->em->flush();
        $io->success('Créations terminée');
    }
}