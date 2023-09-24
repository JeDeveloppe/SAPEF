<?php

namespace App\Service;

use App\Entity\EluStatus;
use App\Repository\EluRepository;
use App\Repository\EluStatusRepository;
use App\Repository\RegionErmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EluService
{
    public function __construct(
        private EntityManagerInterface $em,
        private EluRepository $eluRepository,
        private EluStatusRepository $repository,
        private RegionErmRepository $regionErmRepository
        ){
    }

    public function creationEluStatusForDev(SymfonyStyle $io): void
    {
        $io->title('Importation des EluStatus');

            $totals = [];
            //TODO FAIRE LISTE DES STATUS
            //! NE PAS CHANGER
            array_push($totals,'TITULAIRE');

            //?METTRE A JOUR
            array_push($totals,'SUPPLEANT(E)');
        
            $io->progressStart(count($totals));

            foreach($totals as $arrayTotal){

                $entity = $this->repository->findOneBy(['name' => $arrayTotal]);

                if(!$entity){
                    $entity = new EluStatus();
                }

                $io->progressAdvance();

                $entity->setName($arrayTotal);

                $this->em->persist($entity);
            }
            
            $this->em->flush();

            $io->progressFinish();
        

        $io->success('Importation terminée');
    }

    public function constructionOfTheMapOfFranceWithElus(){

        $donnees = []; //? toutes les réponses seront dans ce tableau final
        $elus = $this->eluRepository->findBy([],['name' => 'ASC']);
        $titulaires = [];
        $suppleants = [];

        //TODO SI Y A D'AUTRE STATUS FAIRE LA LOGIQUE
        foreach($elus as $elu){
            if($elu->getStatus() == 'TITULAIRE'){
                array_push($titulaires, $elu);
                $donnees['titulaires'] = $titulaires;
            }else{
                array_push($suppleants, $elu);
                $donnees['suppleants'] = $suppleants;
            }
        }

        $regions = $this->regionErmRepository->findAll();
        $states = [];

        foreach($regions as $region){
            $elus = $region->getElus();
            
            // $liste_users = '<ul>';
            $listeUsers = [];

            foreach($elus as $elu){

                //on rempli l'array pour faire un tri alphabetique par la suite
                $listeUsers[] = $elu->getName()->getFirstname().' '.$elu->getName()->getLastname().' ('.$elu->getName()->getJob().') '.$elu->getName()->getPhone();

                $stores[] = 
                [
                "lat" => $elu->getName()->getShop()->getLatitude(),
                "lng" => $elu->getName()->getShop()->getLongitude(),
                "name" => $elu->getName()->getShop()->getName(),
                "color" => "#000000",
                "description" => $elu->getName()->getFirstName().' '.$elu->getName()->getLastname().' ('.$elu->getName()->getJob().')'
                ];

                $departments = $region->getDepartments();

                //tri alphabetique de l'array
                asort($listeUsers);

                //composition de la liste dans l'ordre alphabetique Name 
                $liste_users = '<ul>';
                    foreach($listeUsers as $liste_user){
                        $liste_users .= '<li>'.$liste_user.'</li>';
                    }
                $liste_users .= '</ul>';
                
                foreach($departments as $department){
    
                    $states[$department->getCodeMap()] =
                    [
                        "name" => $department->getName().' ('.$department->getNumber().')',
                        "description" => $liste_users,
                        "color" => $elu->getRegionErm()->getColor(),
                        "hover_color" => $elu->getRegionErm()->getColorHover(),
                    ];
                    
                }
            }
        }

        $jsonStores = json_encode($stores, JSON_FORCE_OBJECT); 
        $jsonStates = json_encode($states, JSON_FORCE_OBJECT); 

        $donnees['stores'] = $jsonStores;
        $donnees['states'] = $jsonStates;

        return $donnees;
    }
}