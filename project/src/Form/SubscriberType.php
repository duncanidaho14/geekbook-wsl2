<?php

namespace App\Form;

use App\Entity\Subscription;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('choiceSubscriber', ChoiceType::class, [
            //     'choices'  => [
            //         "Free" => [
            //             'abo' => "Gratuit",
            //             'price' => "0",
            //             'premium' => false,
            //         ],
            //         "Premium" => [
            //             'abo' => "Premium",
            //             'price' => "2",
            //             'premium' => true,
            //         ],
            //         "Legendary" => [
            //             'abo' => "Légendaire",
            //             'price' => "5",
            //             'premium' => true,
            //         ]
            //     ],
            //     'choice_label' => function (?Subscription $subscription): string {
            //         return $subscription ? strtoupper($subscription->getName()) : '';
            //     },
            //     'choice_value' => 'free',
                
            // ])
            ->add('choiceSubscription', ChoiceType::class, [
                // 'choice_label' => function(?Subscription $subscription) {
                //     return $subscription ? strtoupper($subscription->getName()) : '';
                // },
                'choices' => [
                    "Free" => [
                        'abo' => "Gratuit",
                        'price' => "0",
                        'premium' => false,
                    ],
                    "Premium" => [
                        'abo' => "Premium",
                        'price' => "2",
                        'premium' => true,
                    ],
                    "Legendary" => [
                        'abo' => "Légendaire",
                        'price' => "5",
                        'premium' => true,
                    ]] 
            ])       
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subscription::class,
        ]);
    }
}
