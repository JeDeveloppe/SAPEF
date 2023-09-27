<?php

namespace App\Controller\Admin;

use App\Entity\Desk;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DeskCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Desk::class;
    }

    public function __construct(
        private Security $security
    )
    {
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name.nickname')
                ->setLabel('Surnom:')
                ->onlyOnIndex(),
            AssociationField::new('name')
                ->setLabel('Membre:')
                ->setFormTypeOptions(['placeholder' => 'Sélectionner un membre...']),
            AssociationField::new('role')
                ->setLabel('Rôle:')
                ->setFormTypeOptions(['placeholder' => 'Sélectionner un rôle...']),
            ChoiceField::new('orderOfAppearance')
                ->setLabel('Ordre d\'affichage')
                ->setChoices([
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                    '6' => 6,
                    '7' => 7,
                    '8' => 8,
                    '9' => 9,
                    '10' => 10,
                ]),
            AssociationField::new('updatedBy')
                ->setLabel('Créé par:')
                ->setDisabled(true)
                ->setFormTypeOptions(['placeholder' => 'Administrateur en cours...'])
                ->onlyOnForms(),
            DateTimeField::new('updatedAt')
                ->setFormat('dd.MM.yyy à HH:mm')
                ->setLabel('Date de création:')
                ->setDisabled(true)
                ->onlyOnForms(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setPageTitle('index', 'Liste des membres du bureau')
            ->setPageTitle('new', 'Nouveau membre du bureau')
            ->setPageTitle('edit', 'Édition d\'un membre du bureau')
            ->setDefaultSort(['name' => 'DESC'])
            ;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Desk) {
            $user = $this->security->getUser();
            $entityInstance->setUpdatedAt(new DateTimeImmutable('now'))->setUpdatedBy($user);

            $entityManager->persist($entityInstance);
            $entityManager->flush();
        }
    }
}
