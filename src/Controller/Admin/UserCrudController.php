<?php

namespace App\Controller\Admin;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

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
            AssociationField::new('job')->setLabel('Métier ERM:')->setDisabled(true),
            AssociationField::new('sex')->setLabel('Genre:')->setDisabled(true)->onlyOnForms(),
            AssociationField::new('shop')->setLabel('Centre:')->setDisabled(true),
            ImageField::new('image')->setLabel('Image:')->setBasePath($this->getParameter('app.path.images_users'))->onlyOnIndex(),
            TextField::new('imageFile')->setFormType(VichImageType::class)->setFormTypeOptions([
                //TODO vérifier les options
                'required' => false,
                'allow_delete' => false,
                'delete_label' => 'Supprimer du serveur ?',
                'download_label' => '...',
                'download_uri' => true,
                'image_uri' => true,
                // 'imagine_pattern' => '...',
                'asset_helper' => true,
            ])->setLabel('Image:')->onlyOnForms(),
            TextField::new('phone'),
            DateTimeField::new('createdAt')->setFormat('dd.MM.yyyy à HH:mm')->setLabel('Date inscription:')->setDisabled(true),
            DateTimeField::new('lastVisiteAt')->setFormat('dd.MM.yyyy à HH:mm')->setLabel('Dernière visite:')->setDisabled(true),
        ];
    }
    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setPageTitle('index', 'Liste des membres')
            ->setPageTitle('new', 'Nouveau membre')
            ->setPageTitle('edit', 'Édition du membre')
            ->setDefaultSort(['lastVisiteAt' => 'DESC'])
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
        if ($entityInstance instanceof User) {
            $now = new DateTimeImmutable ('now');
            $entityInstance->setCreatedAt($now)->setLastVisiteAt($now);

            $entityManager->persist($entityInstance);
            $entityManager->flush();
        }
    }
}
