<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Carrier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        
        $builder
            ->add('address', EntityType::class, [
                'class' => Address::class,
                'required' => true,
                'choices' => $user->getAddresses(),
                'multiple' => false,
                'expanded' => true
            ])
            ->add('carrier', EntityType::class, [
                'class' => Carrier::class,
                'required' => true,
                'multiple' => false,
                'expanded' => true
            ])
            ->add('moreInformation', TextareaType::class, [
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Payer',
                'attr' => [
                    'class' => 'Payer mt-4 mb-8 w-full block text-center rounded-md bg-gray-900 px-6 py-3 font-medium text-white'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'user' => array(),
            'checkout' => "name"
        ]);
    }
    
    public function getBlockPrefix()
    {
        return 'checkout';
    }
}
