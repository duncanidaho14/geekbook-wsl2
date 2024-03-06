<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Order;
use App\Classes\OrderClasse;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class StripeCheckoutController extends AbstractController
{
    #[Route('/checkout/{reference}', name: 'app_stripe_checkout')]
    public function index(?Order $cart, OrderClasse $orderService, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if (!$cart) {
            return $this->redirectToRoute('app_book');
        }

        Stripe::setApiKey('sk_test_CfH6H673P3jKvGLZJJl48ApX');

        $line_items = $orderService->getLineItems($cart);

        $checkout_session = Session::create([
            'customer_email' => $user->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [[$line_items]],
            'mode' => 'payment',
            'success_url' => $_ENV['YOUR_DOMAIN'].'/payement-reussi/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $_ENV['YOUR_DOMAIN'].'/payement-echec/{CHECKOUT_SESSION_ID}',
        ]);


        $cart->setStripeSessionId($checkout_session->id);
        $manager->flush();

        return $this->redirect($checkout_session->url);
    }
}
