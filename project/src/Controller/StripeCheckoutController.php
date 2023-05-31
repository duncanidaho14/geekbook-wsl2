<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Order;
use App\Classes\Basket;
use App\Classes\OrderClasse;
use Stripe\Checkout\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class StripeCheckoutController extends AbstractController
{
    #[Route('/checkout/{reference}', name: 'app_stripe_checkout')]
    public function index(?Basket $basket, OrderClasse $orderService): Response
    {
        if (!$basket) {
            return $this->redirectToRoute('app_home');
        }
        // foreach ($cart['products'] as $book) {
        //     foreach($book['book'] as $images) {
        //         dd($images);
        //         foreach ($images as $img) { 
        //             dd($img);
        //         }
        //     }
        // }
        Stripe::setApiKey('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');
        header('Content-Type: application/json');
        $line_items = $orderService->getLineItems($basket);
        $line_items = [];
        foreach ($cart['products'] as $dataProduct) {
            $product = $dataProduct;
            
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => round($product['book']->getPrice())*100,
                    'taxes' => dd($product),
                    'product_data' => [
                        'name' => $product['book']->getTitle(),
                        // 'images' => [
                        //     $_ENV['YOUR_DOMAIN']. $product['book']['images']->getUrl()
                        // ],
                    ],
                ],
                'quantity' => $dataProduct['quantity'],
            ];
        }
        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment',
            'success_url' => $_ENV['YOUR_DOMAIN'] . '/stripe-success-payment',
            'cancel_url' => $_ENV['YOUR_DOMAIN'] . '/stripe-cancel-payment',
        ]);
        
        

        header("HTTP/1.1 303 See Other");
        header("Location: " . $checkout_session->url);

        //$order->setStripeSessionId($checkout_session->id);
        //$this->entityManager->flush();

        $response = new JsonResponse(['location' => $checkout_session->url, 'id' => $checkout_session->id]);
        return $this->redirect($checkout_session->url);
    }
}
