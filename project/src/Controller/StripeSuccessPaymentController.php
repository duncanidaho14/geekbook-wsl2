<?php

namespace App\Controller;

use App\Classes\Basket;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeSuccessPaymentController extends AbstractController
{
    #[Route('/payement-reussi/{stripeSessionId}', name: 'app_success_payment')]
    public function index(?Order $order, Basket $cart, EntityManagerInterface $manager): Response
    {
        if (!$order || $order->getUsers() !== $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        

        if (!$order->getIsPaid()) {
            $order->setIsPaid(true);
            $manager->flush();
            
            $cart->remove();
        }

        return $this->render('stripe_success_payment/index.html.twig', [
            'order' => $order,
        ]);
    }
}
