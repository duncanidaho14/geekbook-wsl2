<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(AuthorRepository $authorRepository): Response
    {
        return $this->render('author/index.html.twig', [
            'authors' => $authorRepository->findAll(),
        ]);
    }

    #[Route('/author/{id}', name: 'app_author_show')]
    public function show(AuthorRepository $authorRepository, string $id): Response
    {
        return $this->render('author/show.html.twig', [
            'author' => $authorRepository->findOneById(['id' => $id]),
        ]);
    }
}
