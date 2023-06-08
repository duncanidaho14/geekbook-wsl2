<?php

namespace App\Controller;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeCancelPaymentController extends AbstractController
{
    #[Route('/payement-echec/{stripeSessionId}', name: 'app_cancel_payment')]
    public function index(?Order $order): Response
    {
        if(!$order || $order->getUsers() !== $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('stripe_cancel_payment/index.html.twig', [
            'order' => $order,
        ]);
    }
}
