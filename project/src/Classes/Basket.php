<?php

namespace App\Classes;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Basket
{
    private RequestStack $session;
    private EntityManagerInterface $entityManager;
    private User $user;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $session)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function add($id)
    {
        $basket = $this->session->getSession()->get('basket', []);
        
        if (!empty($basket[$id])) {
            $basket[$id]++;
        } else {
            $basket[$id] = 1;
        }
        

        $this->get()->set('basket', $basket);
    }

    public function get(): SessionInterface
    {
        return $this->session->getSession();
    }

    public function remove()
    {
        return $this->session->getSession()->remove('basket');
    }

    public function delete($id)
    {
        $basket = $this->session->getSession()->get('basket', []);
        unset($basket[$id]);

        return $this->get()->set('basket', $basket);
    }

    public function decrease($id)
    {
        $basket = $this->session->getSession()->get('basket', []);

        if ($basket[$id] > 1) {
            $basket[$id]--;
        } else {
            unset($basket[$id]);
        }

        return $this->get()->set('basket', $basket);
    }

    public function getAllBasket(User $user): array
    {
        $basketOver = [];
        if ($this->get()) {
            /* This code is iterating over the items in the basket, retrieving the corresponding `Book`
            entity from the database using the `EntityManager` and adding it to an array called
            `` along with the quantity of that book in the basket. If a book cannot be
            found in the database, it is removed from the basket and the iteration continues to the
            next item. The resulting `` array contains all the books in the basket along
            with their quantities. */
            foreach ($this->get() as $id => $quantity) {
                /* This line of code is retrieving a `Book` entity from the database using the `EntityManager` based on
                the given `id`. It is used in the `getAllBasket()` method of the `Basket` class to retrieve all the
                books in the basket along with their quantities. If a book with the given `id` cannot be found in
                the database, it is removed from the basket. */
                $book = $this->entityManager->getRepository(Book::class)->findOneById(['id' => $user->getId()]);
                if (!$book) {
                    $this->delete($id);
                    continue;
                }
                $basketOver[] = [
                    'book' => $book,
                    'quantity' => $quantity
                ];
            }
        }
        return $basketOver;
    }
}