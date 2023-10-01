<?php

namespace App\Controller\Admin;

use App\Entity\ConfigurationSite;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ConfigurationSiteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ConfigurationSite::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            MoneyField::new('cotisation')->setLabel('Cotisation annuelle TTC:')->setCurrency('EUR')->setStoredAsCents(),
            EmailField::new('emailSite')->setLabel('Adresse Email pour les contacts:'),
            NumberField::new('delayBeforeMeetingToSendEmail')->setLabel('Nbre de jours avant la réunion pour envoyer les emails:'),
            TextField::new('iban')->setLabel('IBAN:'),
            TextField::new('bic')->setLabel('BIC')
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'CONFIGURATION GENERALE DU SITE')
            ->setDefaultSort(['id' => 'ASC'])
            ;
    }
}
