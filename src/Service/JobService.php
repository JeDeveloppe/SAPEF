<?php

namespace App\Service;

use App\Entity\Job;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class JobService
{
    public function __construct(
        private EntityManagerInterface $em,
        private JobRepository $jobRepository
        ){
    }

    public function addJobsForDev(SymfonyStyle $io){


        //TODO => METTRE A JOUR EN REUNION => et au féminin ?
        $jobs = [];

        //! NE PAS CHANGER
        array_push($jobs,'RCGO VI');

        //? METTRE A JOUR
        //?LISTE AUX OPERATIONS
        array_push($jobs,'DG');
        array_push($jobs,'DO','DOA');
        array_push($jobs,'AO', 'DR', 'RAVL');
        array_push($jobs,'RCGO VL', 'RCGO VI / VL','RCS VI','RCS VL','RCS MULTI SITE', 'RZ VI');
        array_push($jobs,'ACS VI', 'ACS VL', 'ACS ITINERANT');
        array_push($jobs,'TECHNICIEN VI', 'TECHNICIEN VL', 'TECHNICIEN EAD');

        //?LISTE AU COMMERCE
        array_push($jobs,'ATC PL', 'ATC AGR', 'ATC GC');

        //?LISTE AU SIEGE ?
        // array_push($jobs,'ATC PL', 'ATC AGR', 'ATC GC');

        foreach($jobs as $jobArray){

            $job = $this->jobRepository->findOneBy(['name' => $jobArray]);

            if(!$job){
                $job = new Job();
            }

            $job->setName($jobArray);
            $this->em->persist($job);

        }
        $this->em->flush();
        $io->success('Créations terminée');
    }
}