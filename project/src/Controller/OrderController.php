<?php

namespace App\Controller;

use DateTime;
use App\Classes\OrderClasse;
use App\Classes\Basket;
use App\Form\CheckoutType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private RequestStack $session;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $session)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    #[Route('/commande', name: 'app_order')]
    #[Security("is_granted('ROLE_USER')")]
    public function index(Basket $basket, Request $request): Response
    {
        $user = $this->getUser();
        $cart = $basket->getAllBasket();

        if (!isset($cart['products'])) {
            return $this->redirectToRoute('app_home');
        }

        if (!$user->getAddresses()->getValues()) {
            return $this->redirectToRoute('app_add_address');
        }

        $form = $this->createForm(CheckoutType::class, null, [
            'user' => $user
        ]);

        $form->handleRequest($request);

        foreach($basket->getAllBasket() as $key => $value){
            $value;
        }


        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'basket' => $cart
        ]);
    }


    #[Route("/commande/recapitulatif", name:"app_order_recap")]
    #[Security("is_granted('ROLE_USER')")]
    public function add(CacheInterface $cache, Basket $basket, Request $request, OrderClasse $orderClasse): Response
    {
        
        $user = $this->getUser();
        $cart = $basket->getAllBasket();

        if (!isset($cart['products'])) {
            return $this->redirectToRoute('app_home');
        }

        if (!$user->getAddresses()->getValues()) {
            return $this->redirectToRoute('app_add_address');
        }
        // foreach($basket->getAllBasket() as $key => $value){
        //     $cache->get($value['data'], function() use ($value) {
        //         dd($value);
        //         return $value;
        //     });
        // };

        $form = $this->createForm(CheckoutType::class, null, [
            'user' => $user
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() || $this->session->getSession()->get('checkout_data')) {
            if ($this->session->getSession()->get('checkout_data')) {
                $data =  $this->session->getSession()->get('checkout_data');
            }else {
                $data = $form->getData();
                $this->session->getSession()->set('checkout_data', $data);
            }
            $address = $data['address'];
            $carrier = $data['carrier'];
            $information = $data['moreInformation'];
            
            // Save cart
            $cart['checkout'] = $data;
            $reference = $orderClasse->saveOrder($cart, $user);

            return $this->render('order/add.html.twig', [
                'basket' => $cart,
                'address' => $address,
                'carrier' => $carrier,
                'informations' => $information,
                'form' => $form->createView()
            ]);
        }
        return $this->redirectToRoute('app_basket');
    }
}
