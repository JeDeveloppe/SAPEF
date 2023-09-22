<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('email'),
            TextField::new('nickname')->onlyOnForms(),
            TextField::new('phone'),
            DateTimeField::new('createdAt')->setFormat('dd.MM.yyyy à HH:mm')->setLabel('Date inscription:')->setDisabled(true),
            DateTimeField::new('lastVisiteAt')->setFormat('dd.MM.yyyy à HH:mm')->setLabel('Dernière visite:')->setDisabled(true),
        ];
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'LISTE DES MEMBRES')
            ->setDefaultSort(['id' => 'DESC'])
            ;
    }
}
