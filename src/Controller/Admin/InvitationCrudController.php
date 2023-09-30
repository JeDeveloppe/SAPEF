<?php

namespace App\Controller\Admin;

use DateTimeImmutable;
use App\Entity\Invitation;
use App\Service\MailService;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


//TODO expliquer system d'invitation / inscription sur le site
//TODO penser au template nav s'il faut changer
//TODO modifier RegistrationController au cas ou
class InvitationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Invitation::class;
    }

    public function __construct(
        private Security $security,
        private MailService $mailService
    )
    {
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('email')->setLabel('Email:'),
            TextField::new('uuid')->hideWhenCreating()->setLabel('Token:'),
            DateTimeField::new('sendAt')->setFormat('dd.MM.yyyy à HH:mm')->setLabel('Créé / envoyé:')->hideWhenCreating(),
            AssociationField::new('user')->hideWhenCreating()->setLabel('Inscrit:'),
            AssociationField::new('createdBy')->hideOnIndex()->setDisabled(true)->hideWhenCreating()->renderAsEmbeddedForm()
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setPageTitle('index', 'Liste des invitations')
            ->setPageTitle('new', 'Nouvelle invitation')
            ->setPageTitle('detail', 'Détails d\'une invitation')
            ->setDefaultSort(['id' => 'DESC'])
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Invitation) {
            $user = $this->security->getUser();
            $entityInstance->setSendAt(new DateTimeImmutable('now'))->setCreatedBy($user)->setUuid(Uuid::v4());

            $donnees = [
                'recipient' => $entityInstance->getEmail(),
                'host' => $user->getEmail(),
                'uuid' => $entityInstance->getUuid()
            ];

            $entityManager->persist($entityInstance);
            $entityManager->flush();

            $this->mailService->sendMail($entityInstance->getEmail(),'Invitation au site LE SAPEF', 'invitation_to_registration', $donnees);
        }
    }
}
