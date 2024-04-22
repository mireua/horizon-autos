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
        $car = $this->entityManager->getRepository(Car::class)->find($id);

        $user = null;

        if ($this->getUser()) {
            $userRepository = $this->entityManager->getRepository(User::class);
            $user = $userRepository->findOneBy(['email' => $this->getUser()->getEmail()]);
        }

        return $this->render('vehindex/view.html.twig', [
            'car' => $car,
            'user' => $user,
        ]);
    }
}
