<?php

namespace App\Controller;

use App\Classes\Basket;
use App\Entity\Address;
use App\Form\AddressesType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddressController extends AbstractController
{
    private RequestStack $session;

    public function __construct(RequestStack $session)
    {
        $this->session = $session;
    }

    #[Route('/adresse', name: 'app_address')]
    public function index(): Response
    {
        return $this->render('address/index.html.twig', [
            'controller_name' => 'AddressController',
        ]);
    }

    #[Route('/adresse/voir/{id}', name: 'app_show_address')]
    public function showAddress(AddressRepository $addressRepository, string $id): Response
    {
        return $this->render('address/show.html.twig', [
            'address' => $addressRepository->findOneById($id),
        ]);
    }

    #[Route("/adresse/ajouter", name:"app_add_address")]
    public function addAddress(EntityManagerInterface $entityManager, Request $request, Basket $basket): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressesType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setOwner($this->getUser());

            $entityManager->persist($address);
            $entityManager->flush();

            if ($basket->getAllBasket()) {
                return $this->redirectToRoute('app_order');
            }

            sleep(2);
            $this->addFlash(
                'success',
                'Vôtre adresse a bien été enregistré !'
            );

            return $this->redirectToRoute('app_address');
        }
        
        return $this->render("address/add.html.twig", [
            'form' => $form->createView()
        ]);
    }

    #[Route("/adresse/editer/{id<\d+>}", name:"app_edit_address")]
    public function editAddress(EntityManagerInterface $entityManager, Request $request, string $id): Response
    {
        $address = $entityManager->getRepository(Address::class)->findOneById($id);

        if (!$address || $address->getOwner() != $this->getUser()) {
            return $this->redirectToRoute('app_address');
        }

        $form = $this->createForm(AddressesType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            if ($this->session->getSession()->get('checkout_data')) {
                $data = $this->session->getSession()->get('checkout_data');
                $data['address'] = $address;
                $this->session->getSession()->set('checkout_data', $data);
                return $this->redirectToRoute('app_order_recap');
            }
            $this->addFlash(
                'success',
                'Vôtre adresse a bien été enregistré !'
            );

            return $this->redirectToRoute('app_address');
        }
        
        return $this->render("address/edit.html.twig", [
            'form' => $form->createView()
        ]);
    }

    #[Route("/address/delete/{id}", name:"app_delete_address")]
    public function deleteAddress(string $id)
    {
        $address = $entityManager->getRepository(Address::class)->findOneById($id);

        if (!$address || $address->getOwner() != $this->getUser()) {
            $entityManager->remove($address);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_address');
    }
}
