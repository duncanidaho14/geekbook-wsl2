<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'attr' => ['class' => 'tinymce'],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer votre prénom',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Vôtre prénom doit comporter au moins {{ limit }} charactères',
                        'max' => 45,
                    ]),
                ],
            ])
            ->add('lastName', TextType::class, [
                'attr' => ['class' => 'tinymce'],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer votre nom',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Vôtre nom doit comporter au moins {{ limit }} charactères',
                        'max' => 45,
                    ]),
                ],
                ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'tinymce'],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer votre Email',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Vôtre email doit comporter au moins {{ limit }} charactères',
                        'max' => 62,
                    ]),
                ],
            ])
            ->add('avatar', TextType::class, [
                'attr' => ['class' => 'tinymce'],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer votre avatar',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Vôtre avatar doit comporter au moins {{ limit }} charactères',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe doit être le même ! ',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => [
                    'label' => 'Mot de passe *',
                    'label_attr' => [
                        'class' => 'text-lg'
                    ],
                    'attr' => [
                        'class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:shadow-outline',
                        'placeholder' => 'Vôtre mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => 'Repéter mot de passe *',
                    'label_attr' => [
                        'class' => 'text-lg'
                    ],
                    'attr' => [
                        'class' => 'shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:shadow-outline',
                        'placeholder' => 'Répéter le même mot de passe'
                    ]
                ],
                'mapped' => true,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mot de passe ne peut être vide',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => true,
                'required' => true,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions d\'utilisations.',
                    ]),
                ],
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
