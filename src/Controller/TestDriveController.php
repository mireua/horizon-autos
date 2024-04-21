<?php

namespace App\Controller;

use App\Entity\Car;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/test-drive')]
class TestDriveController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'test_drive', methods: ['GET', 'POST'])]
    public function book(Request $request): Response
    {
        // Retrieve vehicles from the database
        $vehicles = $this->entityManager->getRepository(Car::class)->findAll();

        // Check if a make filter is applied
        $make = $request->query->get('make');
        if ($make) {
            $vehicles = $this->entityManager->getRepository(Car::class)->findBy(['make' => $make]);
        }

        if ($request->isMethod('POST')) {
            // Handle the booking logic here
            $model = $request->request->get('model');
            $date = $request->request->get('date');
            $time = $request->request->get('time');

            // Here you would typically save this data to a database and possibly send a confirmation email

            $this->addFlash('success', "Your test drive for the $model on $date at $time has been booked successfully.");
            return $this->redirectToRoute('test_drive');
        }

        return $this->render('testdrive/index.html.twig', [
            'vehicles' => $vehicles,
        ]);
    }
}

