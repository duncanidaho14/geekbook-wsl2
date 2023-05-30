<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Comment;
use App\Form\BookType;
use App\Form\CommentType;
use App\Repository\BookRepository;
use App\Repository\ImageRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookController extends AbstractController
{
    #[Route('/livres', name: 'app_book')]
    public function index(EntityManagerInterface $manager, BookRepository $bookRepository): Response
    {

        /* This code is creating a query to retrieve data from the database using Doctrine's
        EntityManager. The query selects specific fields from the Image, Book, and Author entities
        and joins them together based on their relationships. It also filters the results to only
        include records where the book ID matches the image's book ID. Finally, it groups the
        results by specific fields and limits the number of results to 1. The result of the query is
        stored in the `books` variable. */
        $books = $manager->createQuery("SELECT i.id, i.url, i.name, b.slug, b.id, b.title, b.introduction, b.description, u.firstName, u.lastName
                                            FROM App\Entity\Image i 
                                            JOIN i.book b
                                            JOIN b.authors u
                                            WHERE b.id =  i.book
                                            GROUP BY i.id, b.slug, b.id, u.firstName, u.lastName
                                            ")->setMaxResults(1)->getResult();
        
        return $this->render('book/index.html.twig', [
            'books' => $books,
            'booksRepo' => $bookRepository->findAll()
        ]);
    }

    #[Route('/livre/{slug}', name: 'app_show_book')]
    #[Security("is_granted('ROLE_USER')")]
    public function show(Request $request, EntityManagerInterface $manager, BookRepository $bookRepository, ImageRepository $imageRepository, CommentRepository $commentRepository, string $slug): Response
    {
        $books = $bookRepository->findOneBySlug($slug);
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUserComment($this->getUser())
                    ->setBookComment($books);
           
                    
            $manager->persist($comment);
            $manager->flush();
        }

        return $this->render('book/show.html.twig', [
            'book' => $books,
            'images' => $imageRepository->findOneByUrl($slug),
            'comments' => $commentRepository->findByBookComment($books),
            'form' => $form->createview()
        ]);
    }

    #[Route('/add/livre', name:'app_add_book')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function addBook(Request $request, EntityManagerInterface $manager)
    {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($book);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre livre a bien été enregistré !'
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->render('book/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/livre/{slug}', name:'app_edit_book')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function editBook(Request $request, EntityManagerInterface $manager, Book $book, string $slug)
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre livre a bien été édité !'
            );

            return $this->redirectToRoute('app_show_book');
        }

        return $this->render('book/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/supprimer/livre/{slug}', name:'app_delete_book')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function deleteBook(Book $book, EntityManagerInterface $manager)
    {
        $manager->remove($book);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre livre ' . $book->getTitle() . ' a bien été supprimé ! '
        );

        return $this->redirectToRoute('app_book');
    }
}
