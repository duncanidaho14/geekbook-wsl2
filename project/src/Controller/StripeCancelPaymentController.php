<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeCancelPaymentController extends AbstractController
{
    #[Route('/stripe-cancel-payment', name: 'app_stripe_cancel_payment')]
    public function index(): Response
    {
        return $this->render('stripe_cancel_payment/index.html.twig', [
            'controller_name' => 'StripeCancelPaymentController',
        ]);
    }
}
