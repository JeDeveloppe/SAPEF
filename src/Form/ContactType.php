<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\ContactSubject;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

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
            ->add('subject', EntityType::class, [
                'label' => 'Sujet:',
                'class' => ContactSubject::class,
                'placeholder' => 'Sujet de la demande...',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('question', TextareaType::class, [
                'label' => 'Votre question:',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => '4'
                ]
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(['message' => 'Problème de captcha. Merci de ré-essayer ou de contacter le support en précisant le/les code(s): {{ errorCodes }}']),
                'action_name' => 'homepage',
                // 'script_nonce_csp' => $nonceCSP,
                'locale' => 'fr',
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
