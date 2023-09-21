<?php

namespace App\Service\InitForProd;

use App\Entity\Job;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreationJobsEuromasterService
{
    public function __construct(
        private EntityManagerInterface $em,
        private JobRepository $jobRepository
        ){
    }

    public function addJob(SymfonyStyle $io){

        $jobs = ['RCGO VI', 'RCGO VL', 'RCGO VI/VL','RCS VI','RCS VL','RCS MULTI SITE', 'ACS VI', 'ACS VL'];

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