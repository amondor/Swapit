<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\Exchange;
use App\Entity\Offer;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Swapit - Bienvenue sur votre Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        //yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Vos tables');
        yield MenuItem::linkToCrud('Echanges', 'fas fa-exchange-alt ', Exchange::class);
        yield MenuItem::linkToCrud('Commentaires', 'fas fa-comment', Comment::class);
        yield MenuItem::linkToCrud('Offres', 'fas fa-gift', Offer::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
        
        
    }
}
