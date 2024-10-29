<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Author;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mot de passe ne peut être vide',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre titre doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096
                    ])
                ]
            ])
            ->add('introduction', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mot de passe ne peut être vide',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre introduction doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096
                    ])
                ]
            ])
            ->add('description', TextareaType::class)
            ->add('publishedAt', DateTimeType::class, [
                'input' => 'datetime_immutable',
            ])
            ->add('price', MoneyType::class)
            ->add('langue', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mot de passe ne peut être vide',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre choix du langage doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096
                    ])
                ]
            ])
            ->add('nbPages', NumberType::class)
            ->add('dimension', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mot de passe ne peut être vide',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre dimension doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096
                    ])
                ]
            ])
            ->add('isbn', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mot de passe ne peut être vide',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre isbn doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096
                    ])
                ]
            ])
            ->add('editor', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le mot de passe ne peut être vide',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre editeur doit comporter au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096
                    ])
                ]
            ])
            ->add('isInStock', CheckboxType::class)
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'mapped' => false
            ])
            ->add('authors', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'fullName',
                'mapped' => false
            ])
            // ->add('submit', SubmitType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Create Task',
                'attr' => [
                    'value' => 'create-task'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
