<?php

namespace App\Controller\Admin;

use App\Entity\DeskRole;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DeskRoleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DeskRole::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
