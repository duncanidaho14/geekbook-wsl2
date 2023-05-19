<?php

namespace App\Controller;

use App\Classes\Basket;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class BasketController extends AbstractController
{
    /**
     * Undocumented function
     *
     * @param Basket $basket
     * @return Response
     */
    #[Route('/mon-panier', name: 'app_basket')]
    #[Security("is_granted('ROLE_USER')")]
    public function index(Basket $basket): Response
    {
        return $this->render('basket/index.html.twig', [
            'basket' => $basket->getAllBasket($this->getUser()),
            'cart' => $basket->get()
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
    public function addBasket(Basket $basket, int $id): Response
    {
        $basket->add($id);

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
    public function remove(Basket $basket): Response
    {
        $basket->remove();

        return $this->redirectToRoute('app_home');
    }

    /**
     * Undocumented function
     *
     * @param Basket $basket
     * @param [type] $id
     * @return Response
     */
    #[Route("/mon-panier/delete/{id}", name:"app_delete_basket")]
    #[Security("is_granted('ROLE_USER')")]
    public function delete(Basket $basket, $id): Response
    {
        $basket->delete($id);

        return $this->redirectToRoute('app_basket');
    }

   
    /**
     * Undocumented function
     *
     * @param Basket $basket
     * @param [type] $id
     * @return Response
     */ 
    #[Route("/mon-panier/decrease/{id}", name:"app_decrease_basket")]
    #[Security("is_granted('ROLE_USER')")]
    public function decrease(Basket $basket, $id): Response
    {
        $basket->decrease($id);

        return $this->redirectToRoute('app_basket');
    }


}
