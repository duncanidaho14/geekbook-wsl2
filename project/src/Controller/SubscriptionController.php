<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Form\SubscriberType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{ 
    #[Route('/abonnement', name:'subscription_app')]
    public function premium(Request $request, Subscription $subscription): Response
    {
        
        if (!$this->getUser()) {
            
        }

        return $this->render('stripe_success_payment/subscription.html.twig', [
            
        ]);
    }
}
