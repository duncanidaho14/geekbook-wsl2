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


class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('introduction', TextType::class)
            ->add('description', TextareaType::class)
            ->add('publishedAt', DateTimeType::class, [
                'input' => 'datetime_immutable',
            ])
            ->add('price', MoneyType::class)
            ->add('langue', TextType::class)
            ->add('nbPages', NumberType::class)
            ->add('dimension', TextType::class)
            ->add('isbn', TextType::class)
            ->add('editor', TextType::class)
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
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
