<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function __construct(
        private Security $security
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        if(!$user){
            $builder
                ->add('email', EmailType::class, [
                    'label' => 'Adresse email:',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ])
                ->add('phone', TelType::class, [
                    'label' => 'Téléphone:',
                    'attr' => [
                        'class' => 'form-control'
                    ]
                ]);
        }
        
        $builder
            ->add('question', TextareaType::class, [
                'label' => 'Votre question:',
                'attr' => [
                    'class' => 'form-control'
                ]
            ]);
            // ->add('answer')
            // ->add('createdAt')

            // ->add('user')
            // ->add('answeredBy')
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
