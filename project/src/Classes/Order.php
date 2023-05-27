<?php

namespace App\Classes;

use App\Classes\Basket;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Order
{
    private RequestStack $session;
    private EntityManagerInterface $entityManager;
    
    public function __construct(RequestStack $session, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function createOrder(Basket $basket)
    {

    }

    public function saveOrder(Basket $basket, User $user)
    {
        
    }

    /**
     * retourne un identifiant unique pour la référence du panier
     */
    public function generateUuid()
    {
        mt_srand((double)microtime()*1000000);

        $charid = strtoupper(md5(uniqid(rand(), true)));

        $hyphen = chr(45);

        $uuid = ""
        .substr($charid, 0, 8).$hyphen
        .substr($charid, 8, 4).$hyphen
        .substr($charid, 12, 4).$hyphen
        .substr($charid, 16, 4).$hyphen
        .substr($charid, 20, 12)
        ;
        return $uuid;
    }
}