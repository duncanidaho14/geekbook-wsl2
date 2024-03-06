<?php

namespace App\Controller;

use App\Classes\PdfGen;
use App\Entity\Order;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PdfController extends AbstractController
{
    #[Route("/pdf/{stripeSessionId}", name:"app_account_pdf_order")]
    public function pdfAction(Order $order, PdfGen $pdf)
    {
        // $pageUrl = $this->generateUrl('app_success_payment', array(), true);
        $html = $this->render('/stripe_success_payment/index.html.twig', [
            'order' => $order
        ]);

        $pdf->showPdfFile($html);
    }
}
