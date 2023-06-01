<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeSuccessPaymentController extends AbstractController
{
    #[Route('/payement-reussi/{stripeSessionId}', name: 'app_success_payment')]
    public function index(): Response
    {
        return $this->render('stripe_success_payment/index.html.twig', [
            'controller_name' => 'StripeSuccessPaymentController',
        ]);
    }
}
