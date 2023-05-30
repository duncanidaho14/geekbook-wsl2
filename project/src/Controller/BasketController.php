<?php

namespace App\Controller;

use App\Entity\Book;
use App\Classes\Basket;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class BasketController extends AbstractController
{

    private Basket $basket;

    public function __construct(Basket $basket)
    {
        $this->basket = $basket;
    }

    /**
     * Undocumented function
     *
     * @param Basket $basket
     * @return Response
     */
    #[Route('/mon-panier', name: 'app_basket')]
    #[Security("is_granted('ROLE_USER')")]
    public function index(): Response
    {
        
        
        $cart = $this->basket->getAllBasket();
        
        if (empty($cart['data'])) {
            return $this->redirectToRoute("app_home");
        }
        //$update = $basket->updateCart($this->getUser(), $cart['data'] );
        return $this->render('basket/index.html.twig', [
            'basket' => $cart,
            // 'update' => $update
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Basket $basket
     * @param [type] $id
     * @return Response
     */
    #[Route('/mon-panier/add/{id<\d+>}', name:"app_add_basket")]
    #[Security("is_granted('ROLE_USER')")]
    public function addBasket(Book $book,int $id): Response
    {
        $this->basket->add($id);

        return $this->redirectToRoute('app_basket');
    }

    /**
     * Undocumented function
     *
     * @param Basket $basket
     * @return Response
     */
    #[Route("/mon-panier/remove", name:"app_remove_basket")]
    #[Security("is_granted('ROLE_USER')")]
    public function remove(): Response
    {
        $this->basket->remove();

        return $this->redirectToRoute('app_home');
    }

    /**
     * Undocumented function
     *
     * @param Basket $basket
     * @param [type] $id
     * @return Response
     */
    #[Route("/mon-panier/delete/{id<\d+>}", name:"app_delete_basket")]
    #[Security("is_granted('ROLE_USER')")]
    public function delete($id): Response
    {
        $this->basket->delete($id);

        return $this->redirectToRoute('app_basket');
    }

   
    
    /**
     * This PHP function decreases the quantity of a product in a user's basket and redirects them to
     * the basket page.
     * 
     * @param Basket basket The  parameter is an instance of the Basket class, which is likely a
     * representation of a user's shopping basket or cart.
     * @param id The  parameter is a variable that represents the ID of the product that needs to be
     * decreased in the basket. It is passed as a parameter in the URL when the user clicks on the
     * "decrease" button for a specific product in their basket.
     * 
     * @return Response a Response object, which is a Symfony class used to represent an HTTP response.
     * In this case, the response is a redirection to the 'app_basket' route.
     *
     * @param Basket $basket
     * @param [type] $id
     * @return Response
     */ 
    #[Route("/mon-panier/decrease/{id<\d+>}", name:"app_decrease_basket")]
    #[Security("is_granted('ROLE_USER')")]
    public function decrease($id): Response
    {
        $this->basket->decrease($id);

        return $this->redirectToRoute('app_basket');
    }


}
