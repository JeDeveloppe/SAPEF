<?php

namespace App\Controller\Admin;

use App\Entity\MeetingName;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MeetingNameCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MeetingName::class;
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
