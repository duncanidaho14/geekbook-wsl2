<?php

namespace App\Classes;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Basket
{
    private RequestStack $session;
    private EntityManagerInterface $entityManager;
    private const TVA = 0.2;

    public function __construct(RequestStack $session, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function add($id)
    {
        $basket = $this->getCart()->get('basket', []);

        if (!empty($basket[$id])) {
            $basket[$id]++;
        } else {
            $basket[$id] = 1;
        }

        $this->updateCart($basket);
    }

    public function getCart(): SessionInterface
    {
        return $this->session->getSession();
    }

    public function remove()
    {
        $this->updateCart([]);
    }

    public function delete($id)
    {
        $basket = $this->getCart()->get('basket', []);

        if (isset($basket[$id])) {
            unset($basket[$id]);
            $this->updateCart($basket);
        }
    }

    public function decrease($id)
    {
        $basket = $this->getCart()->get('basket', []);

        if (isset($basket[$id])) {
            if ($basket[$id] > 1) {
                $basket[$id]--;
            } else {
                unset($basket[$id]);
            }
            $this->updateCart($basket);
        }
    }

    public function updateCart($basket)
    {
        $this->getCart()->set('basket', $basket);
        $this->getCart()->set('cartData', $this->getAllBasket());
    }

    public function getAllBasket(): array
    {
        $basket = $this->getCart()->get('basket', []);
        $basketOver = [];
        $quantityCart = 0;
        $subTotal = 0;
        //dd($this->getCart()->get('basket'));
        if (is_array($basket)) {
            /* This code is iterating over the items in the basket, retrieving the corresponding `Book`
            entity from the database using the `EntityManager` and adding it to an array called
            `` along with the quantity of that book in the basket. If a book cannot be
            found in the database, it is removed from the basket and the iteration continues to the
            next item. The resulting `` array contains all the books in the basket along
            with their quantities. */

            foreach ($basket as $id => $quantity) {
                //dd($this->entityManager->getRepository(Book::class)->findOneBy(['id' => $user->getId()]));
                /* This line of code is retrieving a `Book` entity from the database using the `EntityManager` based on
                the given `id`. It is used in the `getAllBasket()` method of the `Basket` class to retrieve all the
                books in the basket along with their quantities. If a book with the given `id` cannot be found in
                the database, it is removed from the basket. */
                $book = $this->entityManager->getRepository(Book::class)->find($id);

                if (!$book) {
                    $this->delete($id);
                    continue;
                }
                $basketOver["products"][] = [
                    'book' => $book,
                    'quantity' => $quantity
                ];
                (int)$quantityCart += $quantity;
                (float)$subTotal += (float)$book->getPrice() * $quantity;
            }
        }

        $basketOver['data'] = [
            'quantityCart' => $quantityCart,
            'subTotalHT' => $subTotal,
            'taxe' => $subTotal * self::TVA,
            'subTotalTTC' => $subTotal + ($subTotal * self::TVA) + 2
        ];

        return $basketOver;
    }
}
