<?php

namespace App\Controller\Admin;

use App\Entity\Department;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class DepartmentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Department::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('number'),
            TextField::new('codeMap')->setDisabled(true),
            AssociationField::new('regionErms')->setDisabled(true)->setLabel('Rattaché à la région:')->onlyOnForms(),
            AssociationField::new('regionErms')->setDisabled(true)->setLabel('Qté région:')->onlyOnIndex(),
        ];
    }
}
