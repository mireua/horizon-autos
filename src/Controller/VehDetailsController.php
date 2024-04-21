<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\User; // Import the User entity
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/vehicles')]
class VehDetailsController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/{id}', name: 'veh_details', methods: ['GET'])]
    public function details(int $id, Request $request): Response
    {
        // Retrieve the car details from the database
        $car = $this->entityManager->getRepository(Car::class)->find($id);

        // Initialize user to null
        $user = null;

        // Check if user is logged in
        if ($this->getUser()) {
            // Retrieve the current user details from the database
            $userRepository = $this->entityManager->getRepository(User::class);
            $user = $userRepository->findOneBy(['email' => $this->getUser()->getEmail()]);
        }

        // Render the details template with the car details and user details
        return $this->render('vehindex/view.html.twig', [
            'car' => $car,
            'user' => $user,
        ]);
    }
}
