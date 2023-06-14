<?php

namespace App\Controller;

use App\Entity\Order;
use Knp\Snappy\Pdf;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PdfController extends AbstractController
{
    #[Route("/account/order/pdf", name:"app_account_pdf_order")]
    public function pdfAction(Pdf $knpSnappyPdf)
    {
        $pageUrl = $this->generateUrl('app_success_payment', array(), true);

        return new PdfResponse(
            $knpSnappyPdf->getOutput($pageUrl),
            'file.pdf'
        );
    }
}
