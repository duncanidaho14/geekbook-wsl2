<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AddressesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'tinymce'],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer le nom de votre adresse',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Vôtre nom d\'adresse doit comporter au moins {{ limit }} charactères',
                        'max' => 45,
                    ]),
                ],
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
                        'min' => 3,
                        'minMessage' => 'Vôtre nom doit comporter au moins {{ limit }} charactères',
                        'max' => 45,
                    ]),
                ],
            ])
            ->add('phone', TelType::class, [
                'attr' => ['class' => 'tinymce'],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer votre nom',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Vôtre nom doit comporter au moins {{ limit }} charactères',
                        'max' => 45,
                    ]),
                ],
            ])
            ->add('company', TextType::class, [
                'attr' => ['class' => 'tinymce'],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer votre  nom d\'entreprise ',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Vôtre nom d\'entreprise doit comporter au moins {{ limit }} charactères',
                        'max' => 45,
                    ]),
                ],
            ])
            ->add('address', TextType::class, [
                'attr' => ['class' => 'tinymce'],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer votre adresse',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Vôtre adresse doit comporter au moins {{ limit }} charactères',
                        'max' => 45,
                    ]),
                ],
            ])
            ->add('zip', TextType::class, [
                'attr' => ['class' => 'tinymce'],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer votre nom',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Vôtre nom doit comporter au moins {{ limit }} charactères',
                        'max' => 45,
                    ]),
                ],
            ])
            ->add('city', TextType::class, [
                'attr' => ['class' => 'tinymce'],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer votre nom',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Vôtre nom doit comporter au moins {{ limit }} charactères',
                        'max' => 45,
                    ]),
                ],
            ])
            ->add('country', CountryType::class, [
                'attr' => ['class' => 'tinymce'],
                'required' => true
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => "text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
