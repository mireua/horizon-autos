<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route(path: '/myprofile', name: 'user_profile')]
    public function profile(): Response
    {

        // Initialize user to null
        $user = null;

        // Check if user is logged in
        if ($this->getUser()) {
            // Retrieve the current user details from the database
            $userRepository = $this->entityManager->getRepository(User::class);
            $user = $userRepository->findOneBy(['email' => $this->getUser()->getEmail()]);
        }

        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
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