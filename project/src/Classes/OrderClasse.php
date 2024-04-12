<?php

namespace App\Classes;

use App\Entity\User;
use App\Entity\Order;
use App\Classes\Basket;
use App\Entity\Book;
use App\Entity\OrderDetails;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class OrderClasse
{
    private RequestStack $session;
    private EntityManagerInterface $entityManager;

    public function __construct(RequestStack $session, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function createOrder(Basket $cart)
    {
        dd($cart->getCart());
        $order = new Order();
           
        $order->setReference($cart->getReference())
            ->setCarrierName($carrier->getName())
            ->setCarrierPrice($carrier->getPrice())
            ->setName($address->getName())
            ->setAddress($address->getName())
            ->setMoreInformation($informations)
            ->setQuantity($cart->getQuantity())
            ->setSubTotalHT($cart->getSubTotalHT())
            ->setTaxe($cart->getTaxe())
            ->setSubTotalTTC($cart->getSubTotalTTC())
            ->setUser($cart->getUser())
            ->setCreatedAt($cart->getCreatedAt())
            ->setPrice($cart->getPrice())
            ->setUnitPrice($cart->getUnitPrice())
        ;

        $this->entityManager->persist($order);

        $products = $cart->getAllBasket()->getValues();
        
        foreach ($products as $cartProduct) {
            $orderDetails = new OrderDetails();

            $orderDetails->setOrders($order)
                        ->setProductName($cartProduct->getName())
                        ->setProductPrice($cartProduct->getPrice())
                        ->setQuantity($cartProduct->getQuantity())
                        ->setSubTotalHT($cartProduct->getSubTotalHT())
                        ->setTaxe($cartProduct->getTaxe())
                        ->setSubTotalTTC($cartProduct->getSubTotalTTC())
                        ->setCarrierName($order->getCarrierName())
                        ->setCarrierPrice($order->getCarrierPrice())
            ;

            $this->entityManager->persist($orderDetails);

        }
        $this->entityManager->flush();

        return $order;
    }

    public function getLineItems(Order $cart)
    {
        $orderDetails = $cart->getOrderDetails();
        $line_items = [];
        foreach ($orderDetails as $details) {
            $product = $this->entityManager->getRepository(Book::class)->findOneByTitle($details->getProductName());

            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => round($product->getPrice()) * 100,
                    'product_data' => [
                        'name' => 'prix du livre',
                        'images' => [],
                    ],
                ],
                'quantity' => $details->getQuantity(),
            ];

        }

        //carrier
        $line_items[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => round($details->getCarrierPrice() * 100),
                'product_data' => [
                    'name' => 'Transporteur',
                    'images' => [],
                ],
            ],
            'quantity' => 1,
        ];

        //taxe
        $line_items[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => round($details->getTaxe()) * 100,
                'product_data' => [
                    'name' => 'tva (20%)',
                    'images' => [],
                ],
            ],
            'quantity' => 1,
        ];

        return $line_items;

    }

    public function saveOrder($data, User $user)
    {
        $price = 0;
        $unitPrice = 0;

        $cart = new Order();
        $reference = $this->generateUuid();
        $address = $data['checkout']['address'];
        $carrier = $data['checkout']['carrier'];
        $informations = $data['checkout']['moreInformation'];
        $book = $data['products'][0];
        foreach ($book['book'] as $myBook) {
            $price = $myBook['price'];
            return $price;
        }

        foreach ($book['book'] as $myBook) {
            $price = $myBook['unitPrice'];
            return $price;
        }

        $cart->setReference($reference)
            ->setCarrierName($carrier->getName())
            ->setCarrierPrice($carrier->getPrice())
            ->setFullName($address->getName())
            ->setDeliveryAddress($address->getAddress())
            ->setMoreInformation($informations)
            ->setQuantity($data['data']['quantityCart'])
            ->setSubTotalHT($data['data']['subTotalHT'])
            ->setTaxe($data['data']['taxe'])
            ->setSubTotalTTC($data['data']['subTotalTTC'])
            ->setUsers($user)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setPrice($price)
            ->setUnitPrice($unitPrice)
        ;

        $this->entityManager->persist($cart);

        $cartDetailsArray = [];

        foreach ($data['products'] as $products) {
            $cartDetails = new OrderDetails();

            $subTotal = $products['quantity'] * $products['book']->getPrice();
            $cartDetails->setOrders($cart)
                        ->setProductName($products['book']->getTitle())
                        ->setProductPrice($products['book']->getPrice())
                        ->setQuantity($products['quantity'])
                        ->setSubTotalHT($subTotal)
                        ->setTaxe($subTotal * 0.2)
                        ->setSubTotalTTC($subTotal * 1.2)
                        ->setCarrierName($data['checkout']['carrier']->getName())
                        ->setCarrierPrice($data['checkout']['carrier']->getPrice())
            ;
            $this->entityManager->persist($cartDetails);
            $cartDetailsArray[] = $cartDetails;
        }
        $this->entityManager->flush();

        return $reference;
    }

    /**
     * retourne un identifiant unique pour la référence du panier
     */
    public function generateUuid()
    {
        mt_srand((float)microtime() * 1000000);

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
