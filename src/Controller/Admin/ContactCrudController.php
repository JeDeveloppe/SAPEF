<?php

namespace App\Controller\Admin;

use DateTimeImmutable;
use App\Entity\Contact;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use phpDocumentor\Reflection\Types\Boolean;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
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
            DateTimeField::new('createdAt')->setFormat('dd-MM-yyyy')->setLabel('Demande le:')->setDisabled(true),
            TextField::new('email')->setDisabled(true),
            TelephoneField::new('phone')->setDisabled(true),
            AssociationField::new('subject')->setLabel('Sujet:')->setDisabled(true),
            TextareaField::new('question')->setDisabled(true)->setLabel('Question:'),
            TextareaField::new('answer')->onlyOnForms()->setLabel('Réponse:'),
            BooleanField::new('answer')->onlyOnIndex()->setLabel('Réponse faite:')->setDisabled(true),
            AssociationField::new('answeredBy')->setLabel('Répondu par:')->setDisabled(true)->onlyOnForms(),
            DateTimeField::new('answeredAt')->setFormat('dd-MM-yyyy')->setLabel('Rédondu le:')->setDisabled(true)->onlyOnForms(),
            
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setPageTitle('index', 'Liste des demandes')
            ->setPageTitle('new', 'Nouvelle demande')
            ->setPageTitle('edit', 'Édition d\'une demande')
            ->setDefaultSort(['id' => 'DESC'])
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_EDIT, Action::SAVE_AND_CONTINUE);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Contact) {
            $user = $this->security->getUser();

            $now = new DateTimeImmutable ('now');

            if(!empty($entityInstance->getAnswer())){

                $entityInstance->setAnsweredAt($now)->setAnsweredBy($user);
                
                $entityManager->persist($entityInstance);
                $entityManager->flush();
                
                $donnees = [
                    'answer' => $entityInstance->getAnswer()
                ];
                
                $this->mailService->sendMail($entityInstance->getEmail(), 'Réponse à votre question sur le site LE SAPEF', 'contact_answer', $donnees);
            }
        }
    }
}
