<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function __construct(
        private Security $security,
    )
    {
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title')->setLabel('Titre:'),
            // TextareaField::new('content')->hideOnIndex(),
            TextEditorField::new('content', 'Contenu:')
                ->setFormType(CKEditorType::class),
            BooleanField::new('isOnline')
                ->setLabel('Afficher sur le site'),
            DateTimeField::new('createdAt')
                ->setFormat('dd-MM-yyyy')
                ->onlyOnDetail(),
            AssociationField::new('createdBy')
                ->setLabel('Créé par:')
                ->onlyOnDetail(),
            AssociationField::new('updatedBy')
                ->setLabel('Mise à jour par:')
                ->onlyOnDetail(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setPageTitle('index', 'Liste des actualités')
            ->setPageTitle('new', 'Nouvelle actualité')
            ->setPageTitle('detail', 'Détails d\'une actualité')
            ->setDefaultSort(['id' => 'DESC'])
            ->setFormThemes(['@EasyAdmin/crud/form_theme.html.twig', '@FOSCKEditor/Form/ckeditor_widget.html.twig']);
            // ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Post) {
            $user = $this->security->getUser();

            $entityInstance->setCreatedAt(new DateTimeImmutable('now'))->setCreatedBy($user)->setUpdatedBy($user);

            $entityManager->persist($entityInstance);
            $entityManager->flush();
        }
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Post) {
            $user = $this->security->getUser();

            $entityInstance->setUpdatedBy($user);

            $entityManager->persist($entityInstance);
            $entityManager->flush();
        }
    }
}
