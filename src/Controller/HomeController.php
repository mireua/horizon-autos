<?php

// src/Controller/HomeController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')] // This sets the prefix for all routes in this controller, here just the root
class HomeController extends AbstractController
{
    #[Route('/', name: 'homepage', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            // You can pass additional variables to the template if needed
        ]);
    }
}
