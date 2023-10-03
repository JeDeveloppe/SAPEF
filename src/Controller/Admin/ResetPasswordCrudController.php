<?php

namespace App\Controller\Admin;

use DateTimeImmutable;
use App\Entity\ResetPassword;
use Symfony\Component\Uid\Uuid;
use App\Repository\UserRepository;
use App\Service\MailService;
use App\Service\ResetPasswordService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ResetPasswordCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ResetPassword::class;
    }

    public function __construct(
        private UserRepository $userRepository,
        private MailService $mailService,
        private ResetPasswordService $resetPasswordService
    )
    {
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('email')->setLabel('Email:'),
            TextField::new('uuid')->hideWhenCreating()->setLabel('Token:')->setDisabled(true),
            DateTimeField::new('createdAt')->setFormat('dd.MM.yyyy à HH:mm')->setLabel('Créé / envoyé:')->hideWhenCreating()->setDisabled(true),
            BooleanField::new('isUsed')->hideWhenCreating()->setLabel('Utilisé:')->setDisabled(true),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setPageTitle('index', 'Liste des demandes')
            ->setPageTitle('new', 'Nouvelle demande de mot de passe')
            ->setPageTitle('edit', 'Édition d\'une demande de mot de passe')
            ->setDefaultSort(['id' => 'DESC'])
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->setPermission(Action::DELETE, 'ROLE_SUPER_ADMIN');
        
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if($entityInstance instanceof ResetPassword) {

            $user = $this->userRepository->findOneBy(['email' => $entityInstance->getEmail()]);

            if(!$user){

                $this->addFlash('Utilisateur inconnu pour l\'adresse email: '.$entityInstance->getEmail(),'warning');

                //TODO faire redirection sur le crud
                dd('STOP');
                $this->redirectToRoute('app_user');

            }else{

                $this->resetPasswordService->saveResetPasswordInDatabaseAndSendEmail($entityInstance);

            }
        }
    }
}
