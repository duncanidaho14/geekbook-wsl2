<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddressController extends AbstractController
{
    #[Route('/adresse', name: 'app_address')]
    public function index(): Response
    {
        return $this->render('address/index.html.twig', [
            'controller_name' => 'AddressController',
        ]);
    }

    #[Route("/adresse/ajouter", name:"app_add_address")]
    public function addAddress(EntityManagerInterface $entityManager, Request $request): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressesType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setOwner($this->getUser());

            $entityManager->persist($address);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Vôtre adresse a bien été enregistré !'
            );

            return $this->redirectToRoute('app_home');
        }
        
        return $this->render("address/add.html.twig", [
            'form' => $form->createView()
        ]);
    }

    #[Route("/adresse/editer/{id<\d+>}", name:"app_add_address")]
    public function editAddress(EntityManagerInterface $entityManager, Request $request, string $id): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressesType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setOwner($this->getUser());

            $entityManager->persist($address);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Vôtre adresse a bien été enregistré !'
            );

            return $this->redirectToRoute('app_home');
        }
        
        return $this->render("address/edit.html.twig", [
            'form' => $form->createView()
        ]);
    }
}
