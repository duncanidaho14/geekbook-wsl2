<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('introduction')
            ->add('description')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('publishedAt')
            ->add('slug')
            ->add('price')
            ->add('langue')
            ->add('nbPages')
            ->add('dimension')
            ->add('isbn')
            ->add('editor')
            ->add('isInStock')
            ->add('categories')
            ->add('authors')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
