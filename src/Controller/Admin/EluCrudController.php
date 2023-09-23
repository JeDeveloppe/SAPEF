<?php

namespace App\Controller\Admin;

use App\Entity\Elu;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EluCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Elu::class;
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
             AssociationField::new('status')
                ->setLabel('Status:')
                ->setFormTypeOptions(['placeholder' => 'Sélectionner un status...']),
            AssociationField::new('regionErm')
                ->setLabel('Région:')
                ->setFormTypeOptions(['placeholder' => 'Sélectionner une région...']),
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
            ->setPageTitle('index', 'Liste des élus')
            ->setPageTitle('new', 'Nouvel élu')
            ->setPageTitle('edit', 'Édition d\'un élu')
            ->setDefaultSort(['name' => 'DESC'])
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->setPermission(Action::DELETE, 'ROLE_SUPER_ADMIN');
        
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Elu) {
            $user = $this->security->getUser();
            $entityInstance->setUpdatedAt(new DateTimeImmutable('now'))->setUpdatedBy($user);

            $entityManager->persist($entityInstance);
            $entityManager->flush();
        }
    }
}
