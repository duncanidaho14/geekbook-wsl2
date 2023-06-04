<?php

namespace App\Controller\Admin;

use App\Entity\Book;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Order;
use App\Entity\Author;
use App\Entity\Address;
use App\Entity\Carrier;
use App\Entity\Comment;
use App\Entity\Category;
use App\Entity\OrderDetails;
use App\Entity\ResetPasswordRequest;
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
        
        return [
            yield MenuItem::section('Commerces'),
            yield MenuItem::subMenu('Commerce', 'fa fa-shop')->setSubItems([
                MenuItem::linkToCrud('Commandes', 'fa fa-shop', Order::class),
                MenuItem::linkToCrud('Détails de la commande', 'fa fa-file-text', OrderDetails::class),
                MenuItem::linkToCrud('Transporteurs', 'fa fa-truck', Carrier::class),
            ]),
            yield MenuItem::section('Produits'),
            yield MenuItem::subMenu('Livres', 'fa fa-book')->setSubItems([
                MenuItem::linkToCrud('Livres', 'fa fa-book', Book::class),
                MenuItem::linkToCrud('Images', 'fas fa-image', Image::class),
                MenuItem::linkToCrud('Categories', 'fa fa-file-text', Category::class),
                MenuItem::linkToCrud('Auteurs', 'fa fa-user-secret', Author::class),
            ]),
            yield MenuItem::section('Utilisateurs'),
            yield MenuItem::subMenu('Utilisateurs', 'fa fa-users')->setSubItems([
                MenuItem::linkToCrud('Utilisateurs', 'fa fa-user-secret', User::class),
                MenuItem::linkToCrud('Commentaires', 'fas fa-comment', Comment::class),
                MenuItem::linkToCrud('Adresse', 'fa fa-house', Address::class),
                MenuItem::linkToCrud('Mot de passe oublié', 'fa fa-lock', ResetPasswordRequest::class),
            ]),
        ];
    }
}
