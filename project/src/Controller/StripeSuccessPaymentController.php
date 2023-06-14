<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use App\Entity\Order;
use App\Classes\Basket;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class StripeSuccessPaymentController extends AbstractController
{
    #[Route('/payement-reussi/{stripeSessionId}', name: 'app_success_payment')]
    public function index(Pdf $knpSnappyPdf, RequestStack $requestStack, ?Order $order, Basket $cart, EntityManagerInterface $manager, string $stripeSessionId): Response
    {
        if(!$order || $order->getUsers() !== $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        
        $currentUrl = $requestStack->getCurrentRequest()->getUri();

        if(!$order->getIsPaid()) {
            $order->setIsPaid(true);
            putenv('FONTCONFIG_PATH=/tmp');
            $knpSnappyPdf->setOption('enable-local-file-access', true);
            $knpSnappyPdf->generate($currentUrl, '/var/www/project/assets/pdf/invoice.pdf');
            $manager->flush();
            
            $cart->remove();
        }
        
        

        return $this->render('stripe_success_payment/index.html.twig', [
            'order' => $order,
        ]);
    }
}
