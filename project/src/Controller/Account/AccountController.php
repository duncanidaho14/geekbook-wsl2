<?php

namespace App\Controller\Account;


use App\Entity\Order;
use App\Repository\CarrierRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AccountController extends AbstractController
{
    

    #[Route('/compte/mes-commandes/{reference}', name: 'app_order_account')]
    #[IsGranted('ROLE_USER')]
    public function myOrderAccount(EntityManagerInterface $manager, string $reference)
    {
        $orders = $manager->getRepository(Order::class)->findOrdersSuccess($this->getUser());

        return $this->render('account/myorder.html.twig', [
            'orders' => $orders,
            'reference' => $reference
        ]);
    }

    #[Route('/compte/mes-commandes', name: 'app_orders_account')]
    #[IsGranted('ROLE_USER')]
    public function myOrdersAccount(EntityManagerInterface $manager)
    {
        $orders = $manager->getRepository(Order::class)->findOrdersSuccess($this->getUser());
       
        return $this->render('account/myorders.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/compte', name: 'app_account')]
    #[IsGranted('ROLE_USER')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('account/index.html.twig', [
            'user' => $userRepository->findOneBy(['id' => $this->getUser()]),
        ]);
    }
}
