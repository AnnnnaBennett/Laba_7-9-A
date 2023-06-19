<?php

namespace App\Controller\Admin;

use App\Entity\Answers;
use App\Entity\Questions;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $hasAccess = $this->isGranted('ROLE_ADMIN');

        if (!$hasAccess)
            return $this->redirect('/');

        $routeBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routeBuilder->setController(UserCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Laba');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Назад', 'fa fa-home', 'app_main');
        yield MenuItem::linkToCrud('Вопросы', 'fas fa-question', Questions::class);
        yield MenuItem::linkToCrud('Ответы', 'fas fa-comments', Answers::class);
        yield MenuItem::linkToCrud('Пользователи', 'fas fa-user', User::class);
    }
}
