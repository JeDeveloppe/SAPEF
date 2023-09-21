<?php

namespace App\Controller\Admin;

use App\Entity\Desk;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DeskCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Desk::class;
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
