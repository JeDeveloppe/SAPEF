<?php

namespace App\Command;

use App\Service\EluService;
use App\Service\JobService;
use App\Service\CityService;
use App\Service\ConfigurationSiteService;
use App\Service\ContactSubjectService;
use App\Service\ShopService;
use App\Service\UserService;
use App\Service\GenderService;
use App\Service\RegionErmService;
use App\Service\DepartmentService;
use App\Service\DeskService;
use App\Service\MeansOfPaiementService;
use App\Service\LegalInformationService;
use App\Service\MeetingNameService;
use App\Service\MeetingPlaceService;
use App\Service\MeetingService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:initfordev')]

class InitForDev extends Command
{
    public function __construct(
            private UserService $userService,
            private MeansOfPaiementService $meansOfPaiementService,
            private DepartmentService $departmentService,
            private CityService $cityService,
            private GenderService $genderService,
            private JobService $jobService,
            private ShopService $shopService,
            private RegionErmService $regionErmService,
            private EluService $eluService,
            private LegalInformationService $legalInformationService,
            private ConfigurationSiteService $configurationSiteService,
            private MeetingNameService $meetingNameService,
            private MeetingPlaceService $meetingPlaceService,
            private MeetingService $meetingService,
            private DeskService $deskService,
            private ContactSubjectService $contactSubjectService
        )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        ini_set('memory_limit', '2048M');

        $io = new SymfonyStyle($input,$output);
        
        // $this->configurationSiteService->addConfigurationSite($io);
        // $this->meetingNameService->addMeetingNameForDev($io);
        // $this->deskService->addDeskStatusForDev($io);
        // $this->meetingPlaceService->addMeetingPlaceForDev($io);
        // $this->meetingService->addFakeMeetingIn2100ForDev($io);
        // $this->eluService->creationEluStatusForDev($io);
        // $this->jobService->addJobsForDev($io);
        // $this->genderService->addGendersForDev($io);
        // $this->meansOfPaiementService->addMeansOfPaiementsForDev($io);
        // $this->regionErmService->importRegionsErmForDev($io);
        // $this->departmentService->importDepartmentsForDev($io);
        // $this->cityService->importCsvCityForDev($io);
        // //TODO IN SHOP.CSV => CM NUMBER FOR SIEGE AND UPDATE IT IN ...AllAdminsUsersForDev
        // $this->shopService->importCsvShopForDev($io);
        $this->userService->initAdminUserForDev($io);
        // $this->legalInformationService->addLegalInformationForDev($io);
        // $this->meetingService->addFakeMeetingIn2100ForDev($io);
        // $this->contactSubjectService->addContactSubjectInDatabase($io);

        $this->userService->initAllAdminsUsersForDev($io);

        return Command::SUCCESS;
    }

}