<?php

namespace App\Controller;

use Error;
use Stripe\Event;
use Stripe\Price;
use Stripe\Stripe;
use App\Entity\Order;
use App\Classes\OrderClasse;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    public function StripeCheckoutAction(): Response 
    {

        $user = $this->getUser();
        Stripe::setApiKey('sk_test_CfH6H673P3jKvGLZJJl48ApX');
        header('Content-Type: application/json');
        try {
            $prices = \Stripe\Price::all([
                // retrieve lookup_key from form data POST body
                'lookup_keys' => [$_POST['lookup_key']],
                'expand' => ['data.product']
            ]);

            $checkout_session = \Stripe\Checkout\Session::create([
                'line_items' => [[
                'price' => $prices->data[0]->id,
                'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => $_ENV['YOUR_DOMAIN'] . '/subscription_success.html?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => $_ENV['YOUR_DOMAIN'] . '/subscription_failed.html.twig',
            ]);

            header("HTTP/1.1 303 See Other");
            header("Location: " . $checkout_session->url);
            } catch (Error $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }

        
    }

    public function createPortalSession(): JsonResponse {
        
        $user = $this->getUser();
        Stripe::setApiKey('sk_test_CfH6H673P3jKvGLZJJl48ApX');

        

        header('Content-Type: application/json');

        

        try {
        $checkout_session = \Stripe\Checkout\Session::retrieve($_POST['session_id']);
        $return_url = $_ENV['YOUR_DOMAIN'];

        // Authenticate your user.
        $session = \Stripe\BillingPortal\Session::create([
            'customer' => $checkout_session->customer,
            'return_url' => $return_url,
        ]);
            header("HTTP/1.1 303 See Other");
            header("Location: " . $session->url);
        } catch (Error $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function webhooks() {
      
       
        Stripe::setApiKey('sk_test_CfH6H673P3jKvGLZJJl48ApX');


// Replace this endpoint secret with your endpoint's unique secret
// If you are testing with the CLI, find the secret by running 'stripe listen'
// If you are using an endpoint defined with the API or dashboard, look in your webhook settings
// at https://dashboard.stripe.com/webhooks
$endpoint_secret = 'whsec_12345';

$payload = @file_get_contents('php://input');
$event = null;
try {
  $event = \Stripe\Event::constructFrom(
    json_decode($payload, true)
  );
} catch(\UnexpectedValueException $e) {
  // Invalid payload
  echo '⚠️  Webhook error while parsing basic request.';
  http_response_code(400);
  exit();
}
// Handle the event
switch ($event->type) {
  case 'customer.subscription.trial_will_end':
    $subscription = $event->data->object; // contains a \Stripe\Subscription
    // Then define and call a method to handle the trial ending.
    // handleTrialWillEnd($subscription);
    break;
  case 'customer.subscription.created':
    $subscription = $event->data->object; // contains a \Stripe\Subscription
    // Then define and call a method to handle the subscription being created.
    // handleSubscriptionCreated($subscription);
    break;
  case 'customer.subscription.deleted':
    $subscription = $event->data->object; // contains a \Stripe\Subscription
    // Then define and call a method to handle the subscription being deleted.
    // handleSubscriptionDeleted($subscription);
    break;
  case 'customer.subscription.updated':
    $subscription = $event->data->object; // contains a \Stripe\Subscription
    // Then define and call a method to handle the subscription being updated.
    // handleSubscriptionUpdated($subscription);
    break;
  case 'entitlements.active_entitlement_summary.updated':
    $subscription = $event->data->object; // contains a \Stripe\Subscription
    // Then define and call a method to handle active entitlement summary updated.
    // handleEntitlementUpdated($subscription);
    break;
  default:
    // Unexpected event type
    echo 'Received unknown event type';
}
    }
}
