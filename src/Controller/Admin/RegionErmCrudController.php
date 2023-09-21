<?php

namespace App\Controller\Admin;

use App\Entity\RegionErm;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
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
            TextField::new('name'),
            TextField::new('color'),
            TextField::new('colorHover'),
            AssociationField::new('departments')

        ];
    }
}
