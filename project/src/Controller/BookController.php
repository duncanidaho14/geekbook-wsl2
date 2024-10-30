<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\UX\Turbo\TurboBundle;
use App\Repository\BookRepository;
use App\Repository\ImageRepository;
use App\Repository\CommentRepository;
use Symfony\Component\Mercure\Update;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookController extends AbstractController
{
    #[Route('/livres', name: 'app_book')]
    public function index(EntityManagerInterface $manager, BookRepository $bookRepository): Response
    {
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

    #[IsGranted('ROLE_USER')]
    #[Route('/livre/{slug}', name: 'app_show_book', requirements:['slug' => Requirement::ASCII_SLUG], methods:['GET', 'POST'])]
    public function show(
        HubInterface $default,
        Book $bookCount,
        Request $request,
        EntityManagerInterface $manager,
        BookRepository $bookRepository,
        ImageRepository $imageRepository,
        CommentRepository $commentRepository,
        string $slug
    ): Response {
        $response = new JsonResponse(['message' => 'Données reçues avec succès']);
        $response->headers->set('Access-Control-Allow-Origin', 'https://%env(MERCURE_URL)%:2019');
        $response->headers->set('Access-Control-Allow-Methods', ['GET', 'POST', 'PUT', 'DELETE'], 'OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
        $response->headers->set('CORS_ALLOWED_ORIGINS', 'https://%env(MERCURE_URL)%:2019');
        
        headers_sent($response);
        $url = $default->getUrl();
        $acceptHeader = AcceptHeader::fromString($request->headers->get('Accept'))->all();
        $acceptHeader2 = AcceptHeader::fromString($request->headers->get('Content-Type'))->all();
        $books = $bookRepository->findOneBySlug($slug);
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $emptyForm = clone $form;
        $form->handleRequest($request);
        $comments = $bookCount->getComments();

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUserComment($this->getUser())
                    ->setBookComment($books);
            $manager->persist($comment);
            $manager->flush();
            // 🔥 The magic happens here! 🔥
            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
                $default->publish(new Update(
                    'post_comments',
                    $this->renderView('book/success.stream.html.twig', [
                        'book' => $books,
                        'comment' => $comment,
                        'commentsCount' => $comments->count() + 1,
                        'form' => $emptyForm
                    ])
                ));
                return $this->render('book/success.stream.html.twig', [
                    'book' => $books,
                    'comment' => $comment,
                    'commentsCount' => $comments->count() + 1,
                    'form' => $emptyForm
                ]);
            }
            $this->addFlash('success', 'Le commentaire a bien été posté !');
            return $this->redirectToRoute('app_show_book', ['slug' => $books->getSlug()], Response::HTTP_SEE_OTHER);
        }


        return $this->render('book/show.html.twig', [
            'book' => $books,
            'images' => $imageRepository->findOneByUrl($slug),
            'comments' => $commentRepository->findByBookComment($books),
            'form' => $form->createview(),
            'headers' => $acceptHeader,
            'headers2' => $acceptHeader2,
            'url' => $url
        ]);
    }

    #[Route('/add/livre', name:'app_add_book')]
    #[Security("is_granted('ROLE_ADMIN')")]
    public function addBook(Request $request, EntityManagerInterface $manager)
    {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book, [
            'action' => $this->generateUrl('app_add_book')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($book);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre livre a bien été enregistré !'
            );

            return $this->redirectToRoute('app_book');
        }
        $response = new Response(null, $form->isSubmitted() ? 422 : 200);
        return $this->render('book/add.html.twig', [
            'form' => $form->createView()
        ], $response);
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
