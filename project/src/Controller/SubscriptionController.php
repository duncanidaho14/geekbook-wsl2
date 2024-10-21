<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SubscriptionController extends AbstractController
{ 
    #[Route('/abonnement', name:'subscription_app')]
    public function premium(): Response
    {
        return $this->render('stripe_success_payment/subscription.html.twig', []);
    }
}
