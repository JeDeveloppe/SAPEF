<?php

namespace App\Controller\Admin;

use App\Entity\Meeting;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;

class MeetingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Meeting::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('date')->setFormat('dd.MM.yyyy à HH:mm')->setLabel('Date/ heure de la réunion:'),
            AssociationField::new('name')->setLabel('Type de réunion'),
            AssociationField::new('place')->setLabel('Lieu de réunion:'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setPageTitle('index', 'Liste des réunions')
            ->setPageTitle('new', 'Nouvelle réunion')
            ->setPageTitle('edit', 'Édition d\'une réunion')
            ->setDefaultSort(['date' => 'ASC'])
            ;
    }
}
