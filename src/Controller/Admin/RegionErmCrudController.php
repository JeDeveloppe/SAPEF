<?php

namespace App\Controller\Admin;

use App\Entity\RegionErm;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RegionErmCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RegionErm::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Nom:'),
            ColorField::new('color')->setLabel('Couleur de la région:'),
            ColorField::new('colorHover')->setLabel('Couleur de la région (hover)'),
            AssociationField::new('departments')->setLabel('Qté de départements rattachés:')->onlyOnIndex(),
            AssociationField::new('departments')->setLabel('Les départements rattachés:')->onlyOnForms(),
            AssociationField::new('elus')->setLabel('Qté élus:')->onlyOnIndex(),
            AssociationField::new('elus')->setLabel('Les élus:')->onlyOnForms()->setDisabled(true),

        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setPageTitle('index', 'Liste des régions SAPEF')
            ->setPageTitle('new', 'Nouvelle région SAPEF')
            ->setPageTitle('edit', 'Édition d\'une région SAPEF')
            ->setDefaultSort(['name' => 'ASC'])
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->setPermission(Action::DELETE, 'ROLE_SUPER_ADMIN');
        
    }
}
