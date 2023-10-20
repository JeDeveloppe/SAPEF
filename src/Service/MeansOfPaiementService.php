<?php

namespace App\Service;

use App\Entity\MeanOfPaiement;
use App\Repository\MeanOfPaiementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MeansOfPaiementService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MeanOfPaiementRepository $meanOfPaiementRepository
        ){
    }

    public function addMeansOfPaiementsForDev(SymfonyStyle $io){

        $moyens = ['CB','CHQ','VIR','PAYPAL'];

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