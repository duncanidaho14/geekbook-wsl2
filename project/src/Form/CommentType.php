<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du commentaire *',
                'required' => true
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'commentaire *',
                'required' => true,
                'attr' => [
                    'class' => 'px-0 w-full h-60 text-sm bg-pink-300 text-gray-800 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800',
                    'placeholder' => 'Ecrire un commentaire'
                ]
            ])
            ->add('rating', IntegerType::class, [
                'label' => 'Note sur 5 *',
                'attr' => [
                    'min' => 0,
                    'max' => 5,
                    'step' => 1
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
