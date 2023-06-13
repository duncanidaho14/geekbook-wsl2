<?php

namespace App\Controller;

use App\Entity\Order;
use Knp\Snappy\Pdf;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PdfController extends AbstractController
{
    #[Route("/pdf/{stripeSessionId}", name:"app_pdf")]
    public function pdfAction(Pdf $knpSnappyPdf, ?Order $order)
    {
        
        //$pageUrl = $this->generateUrl('app_success_payment', array(), true); // use absolute path!


        $html = $this->renderView('stripe_success_payment/index.html.twig', array(
            'order'  => $order
        ));

        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html),
            'invoice.pdf'
        );
       
        // return new PdfResponse(
        //     $knpSnappyPdf->getOutput($pageUrl),
        //     'invoice.pdf'
        // );
    }
}