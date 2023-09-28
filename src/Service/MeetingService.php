<?php

namespace App\Service;

use DateInterval;
use DateTimeImmutable;
use App\Entity\Meeting;
use App\Repository\MeetingRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MeetingNameRepository;
use App\Repository\MeetingPlaceRepository;
use App\Repository\ConfigurationSiteRepository;
use Symfony\Component\Console\Style\SymfonyStyle;

class MeetingService
{
    public function __construct(
        private MeetingRepository $meetingRepository,
        private ConfigurationSiteRepository $configurationSiteRepository,
        private EntityManagerInterface $em,
        private MeetingPlaceRepository $meetingPlaceRepository,
        private MeetingNameRepository $meetingNameRepository
        )
    {
    }

    public function addFakeMeetingIn2100ForDev(SymfonyStyle $io){

        $io->title('Création Fake Meeting en 2100');

        $date = new DateTimeImmutable('2100-3-31');

        //! ANNEE MOIS JOUR A METTRE A JOUR DANS AUTRE METHODE DE CE SERVICE
        $meetingIn2100 = $date->setTime(14,15,00);

        $meeting = $this->meetingRepository->findOneBy(['date' => $meetingIn2100]);

        if(!$meeting){
            $meeting = new Meeting();
        }

        $meeting
            ->setName($this->meetingNameRepository->findOneBy(['name' => 'Réunion CSE']))
            ->setPlace($this->meetingPlaceRepository->findOneBy(['name' => 'Siège Euromaster']))
            ->setDate($meetingIn2100);
        $this->em->persist($meeting);
        $this->em->flush();

        $io->success('Création terminée');
    }

    public function nextMeetingCalc(){

        $configuration = $this->configurationSiteRepository->findOneBy([]);

        $meetingValues = [];

        $now = new DateTimeImmutable('now');
        $add = $configuration->getDelayBeforeMeetingToSendEmail()+1;
        $newMeetingDate = $now->add(new DateInterval('P'.$add.'D'));

        $nextMeeting = $this->meetingRepository->findNextMeeting($newMeetingDate);

        //? DATE EN 2100 ENJECTEE A L'INITIALISATION POUR QUE LE SITE FONCTIONNE TOUJOURS...
        if(!$nextMeeting){
            //! DOIT ETRE PAREIL QUE METHODE AU DESSUS
            $date = new DateTimeImmutable('2100-3-31');
            $date100 = $date->setTime(14,15,00);

            $nextMeeting = $this->meetingRepository->findOneBy(['date' => $date100]);
        }

        $meetingValues['nextMeeting'] = $nextMeeting;

        $nextMeetingDate = $nextMeeting->getDate();
        // $meetingValues['nextMeetingDate'] = $nextMeetingDate;


        $nextMeetingDateEndQuestions = $nextMeetingDate->sub(new DateInterval('P'.$configuration->getDelayBeforeMeetingToSendEmail().'D'));

  
        $meetingValues['nextMeetingDateEndQuestions'] = $nextMeetingDateEndQuestions;

        $nextMeetingTimestamp = $nextMeetingDateEndQuestions->getTimestamp();
        $meetingValues['nextMeetingTimestamp'] = $nextMeetingTimestamp;

        $date = date('M d, Y H:i:s', $nextMeetingTimestamp);

        return $meetingValues;
    }
}