<?php

namespace App\Controller\Admin;

use App\Entity\Elu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EluCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Elu::class;
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
