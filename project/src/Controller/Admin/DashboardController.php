<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Entity\Address;
use App\Entity\Author;
use App\Entity\Carrier;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(BookCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('bundles/EasyAdminBundle/welcome.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('GeekBook');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Commentaires', 'fas fa-comment', Comment::class);

        yield MenuItem::section('Livres');
        yield MenuItem::linkToCrud('Livres', 'fas fa-book', Book::class);
        yield MenuItem::subMenu('Commande', 'fas fa-shop', Order::class);
        yield MenuItem::subMenu('Détails de la commande', 'fas fa-cart-shopping', OrderDetails::class);
        yield MenuItem::linkToCrud('Author', 'fas fa-list', Author::class);
        yield MenuItem::linkToCrud('Image', 'fas fa-list', Image::class);
        yield MenuItem::linkToCrud('Categorie', 'fas fa-category', Category::class);
        yield MenuItem::linkToCrud('Transporteur', 'fas fa-list', Carrier::class);
        yield MenuItem::linkToCrud('Adresse', 'fas fa-list', Address::class);
        
        return [
            yield MenuItem::section('commerce'),
            yield MenuItem::subMenu('Commandes', 'fa fa-article')->setSubItems([
                MenuItem::linkToCrud('Commande', 'fa fa-tags', Order::class),
                MenuItem::linkToCrud('Détails de la commande', 'fa fa-file-text', OrderDetails::class),
                MenuItem::linkToCrud('Transporteur', 'fa fa-truck', Carrier::class),
            ]),
        ];
    }
}
