<?php

namespace App\Controller;

use App\Classes\Basket;
use App\Form\OrderType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Cache\CacheInterface;

class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande', name: 'app_order')]
    #[Security("is_granted('ROLE_USER')")]
    public function index(Basket $basket, Request $request): Response
    {

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        foreach($basket->getAllBasket($this->getUser()) as $key => $value){
            $value;
        }


        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'basket' => $basket->getAllBasket($this->getUser())
        ]);
    }


    #[Route("/commande/recapitulatif", name:"app_order_recap", methods:["POST"])]
    #[Security("is_granted('ROLE_USER')")]
    public function add(CacheInterface $cache, Basket $basket, Request $request): Response
    {
        
        foreach ($basket->getAllBasket($this->getUser()) as $key => $value) {
            $value;
        }

        $value =  $cache->get($value['book'], function() use ($value) {
            return $value;
        });

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $date = new DateTime();

        }
        return $this->redirectToRoute('app_basket');
    }
}
