<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeSuccessPaymentController extends AbstractController
{
    #[Route('/stripe-success-payment', name: 'app_stripe_success_payment')]
    public function index(): Response
    {
        return $this->render('stripe_success_payment/index.html.twig', [
            'controller_name' => 'StripeSuccessPaymentController',
        ]);
    }
}
