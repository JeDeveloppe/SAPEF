<?php

namespace App\Command;

use App\Service\EluService;
use App\Service\JobService;
use App\Service\CityService;
use App\Service\ConfigurationSiteService;
use App\Service\ShopService;
use App\Service\UserService;
use App\Service\GenderService;
use App\Service\RegionErmService;
use App\Service\DepartmentService;
use App\Service\MeansOfPaiementService;
use App\Service\LegalInformationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:initbeforeprod')]

class InitBeforeProd extends Command
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
            private ConfigurationSiteService $configurationSiteService
        )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        ini_set('memory_limit', '2048M');

        $io = new SymfonyStyle($input,$output);
        
        $this->configurationSiteService->importConfigurationSite($io);
        // $this->eluService->creationEluStatus($io);
        // $this->jobService->addJobs($io);
        // $this->genderService->addGenders($io);
        // $this->meansOfPaiementService->addMeansOfPaiements($io);
        // $this->regionErmService->importRegionsErm($io);
        // $this->departmentService->importDepartments($io);
        // $this->cityService->importCsv($io);
        // $this->shopService->importCsv($io);
        // $this->userService->initForProd_adminUser($io);
        // $this->legalInformationService->addLegalInformation($io);

        return Command::SUCCESS;
    }

}