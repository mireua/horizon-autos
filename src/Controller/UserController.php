<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
class UserController extends AbstractController
{

    #[Route(path: '/profile', name: 'user_profile')]
    public function profile(): Response
    {
        return $this->render('user/index.html.twig');
    }

    #[Route(path: '/inbox', name: 'user_inbox')]
    public function inbox(): Response
    {
        return $this->render('user/messages.html.twig');
    }

    #[Route(path: '/orders', name: 'user_orders')]
    public function orders(): Response
    {
        return $this->render('user/orders.html.twig');
    }

    #[Route(path: '/favourites', name: 'user_favourites')]
    public function favourites(): Response
    {
        return $this->render('user/favourites.html.twig');
    }
}