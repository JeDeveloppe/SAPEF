<?php

namespace App\Command;

use App\Service\InitForProd\CreationJobsEuromasterService;
use App\Service\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use App\Service\InitForProd\ImportSapefRegionService;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\InitForProd\CreationMoyenDePaiementService;
use App\Service\InitForProd\ImportCityService;
use App\Service\InitForProd\ImportDepartmentsService;
use App\Service\InitForProd\ImportShopService;

#[AsCommand(name: 'app:initforprod')]

class InitForProd extends Command
{
    public function __construct(
            private UserService $userService,
            private CreationMoyenDePaiementService $creationMoyenDePaiementService,
            private ImportSapefRegionService $importSapefRegionService,
            private ImportDepartmentsService $importDepartmentsService,
            private ImportCityService $importCityService,
            private CreationJobsEuromasterService $creationJobsEuromasterService,
            private ImportShopService $importShopService
            // private ImportDepartementsService $importDepartementsService,
            // private ImportVillesFrancaisesService $importVillesFrancaiseService,
            // private ImportPaiementService $importPaiementService,
        )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        ini_set('memory_limit', '2048M');

        $io = new SymfonyStyle($input,$output);
        
        $this->importShopService->import($io);
        $this->creationJobsEuromasterService->addJob($io);
        // $this->creationMoyenDePaiementService->addMoyens($io);
        // $this->importSapefRegionService->importRegionsErm($io);
        // $this->importDepartmentsService->import($io);
        // $this->importCityService->import($io);

        //ON CREE OU ON MET A JOUR L'ADMIN
        $this->userService->initForProd_adminUser($io);

        //on importe les departementss
        // $this->importDepartementsService->importDepartements($io);

        //on importe les villes francaises
        // $this->importVillesFrancaiseService->importVilles1_5($io);
        // $this->importVillesBelgesService->importVilles1_5($io);


        //on cree les MOYENS DE PAIEMENT
        // $this->creationMoyenDePaiementService->addMoyens($io);

        //on crer les information legale et la tax
        // $this->creationLegalInformationService->creationLegalInformation($io);

        return Command::SUCCESS;
    }

}