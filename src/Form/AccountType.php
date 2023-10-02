<?php

namespace App\Form;

use App\Entity\Job;
use App\Entity\Shop;
use App\Entity\User;
use App\Entity\SexStatus;
use Doctrine\ORM\QueryBuilder;
use App\Repository\JobRepository;
use App\Repository\ShopRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse email:',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            // ->add('password', PasswordType::class, [
            //     'label' => 'Mot de passe:',
            //     'attr' => [
            //         'class' => 'form-control',
            //     ]
            // ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone:',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom:',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom:',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('job', EntityType::class, [
                'label' => 'Poste:',
                'class' => Job::class,
                'query_builder' => function (JobRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('j')
                        ->orderBy('j.name', 'ASC');
                },
                'placeholder' => 'Faire un choix...',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('shop', EntityType::class, [
                'label' => 'Lieu de rattachement:',
                'class' => Shop::class,
                'query_builder' => function (ShopRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('s')
                        ->orderBy('s.counterMark', 'ASC');
                },
                'choice_label' => function ($shop) {
                    return $shop->getCounterMark().' - '.$shop->getName();
                },
                'placeholder' => 'Faire un choix...',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('sex', EntityType::class, [
                'label' => 'Genre:',
                'class' => SexStatus::class,
                'placeholder' => 'Faire un choix...',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
