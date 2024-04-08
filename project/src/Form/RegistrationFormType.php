<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('birthday', BirthdayType::class, [
                'widget' => 'single_text',
                'label' => "Date de naissance *",
                'required' => true,
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
                //'format' => 'dd-MM-YYYY',
                'years' => range((int) date('Y') - 120, date('Y')),
                'invalid_message' => 'Entrer une date d\'anniversaire correcte'
            ])
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
                        'class' => 'block mb-2 text-sm font-medium text-gray-900 dark:text-white'
                    ],
                    'attr' => [
                        'class' => 'bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500',
                        'placeholder' => 'Vôtre mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => 'Repéter mot de passe *',
                    'label_attr' => [
                        'class' => 'block mb-2 text-sm font-medium text-gray-900 dark:text-white'
                    ],
                    'attr' => [
                        'class' => 'bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500',
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
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
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
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'app_register',
                'locale' => 'fr',
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
