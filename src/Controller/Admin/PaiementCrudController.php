<?php

namespace App\Controller\Admin;

use DateTimeImmutable;
use App\Entity\Paiement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class PaiementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Paiement::class;
    }

    public function __construct(
        private Security $security
    )
    {
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('paymentAt')
                ->setFormat('dd.MM.yyy à HH:mm')
                ->setLabel('Date du paiement:'),
            MoneyField::new('amount')->setLabel('Montant reçu:')->setCurrency('EUR')->setStoredAsCents(),
            AssociationField::new('name')
                ->setLabel('Membre:')
                ->setFormTypeOptions(['placeholder' => 'Sélectionner un membre...']),
            AssociationField::new('meansOfPaiement')
                ->setLabel('Paiement par:')
                ->setFormTypeOptions(['placeholder' => 'Sélectionner un moyen de paiement...']),
            TextField::new('comment')
                ->setLabel('Commentaire:'),
            AssociationField::new('createdBy')
                ->setLabel('Créé par:')
                ->setDisabled(true)
                ->setFormTypeOptions(['placeholder' => 'Administrateur en cours...'])
                ->hideOnIndex(),
            DateTimeField::new('createdAt')
                ->setFormat('dd.MM.yyy à HH:mm')
                ->setLabel('Date de création:')
                ->setDisabled(true)
                ->hideOnIndex(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setPageTitle('index', 'Liste des paiements')
            ->setPageTitle('new', 'Nouveau paiement')
            ->setPageTitle('edit', 'Édition d\'un paiement')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->setPermission(Action::EDIT, 'ROLE_SUPER_ADMIN');
        
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Paiement) {
            $user = $this->security->getUser();
            $entityInstance->setCreatedAt(new DateTimeImmutable('now'))->setCreatedBy($user);

            $entityManager->persist($entityInstance);
            $entityManager->flush();
        }
    }
}
