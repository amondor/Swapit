<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Adresse email'
                ]
            ])
            ->add('username', TextType::class, [
                'label' => 'nom',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom d\'utilisateur'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'les mots de passe doivent correspondre',
                'required' => true,
                'first_options' => [
                    'label' => 'mot de passe',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmer mot de passe',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Confirmer mot de passe'
                    ]
            ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
