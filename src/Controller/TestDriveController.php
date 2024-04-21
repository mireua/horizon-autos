<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/test-drive')]
class TestDriveController extends AbstractController
{
    #[Route('/', name: 'test_drive', methods: ['GET', 'POST'])]
    public function book(Request $request): Response
    {
        // Placeholder vehicle data - you would typically fetch this from a database
        $vehicles = [
            ['model' => 'BMW 3 Series'],
            ['model' => 'Audi A4'],
            ['model' => 'Volkswagen Golf'],
            ['model' => 'Nissan Altima'],
            ['model' => 'Toyota Camry'],
            ['model' => 'Honda Accord'],
        ];

        if ($request->isMethod('POST')) {
            // Handle the booking logic here
            $model = $request->request->get('model');
            $date = $request->request->get('date');
            $time = $request->request->get('time');

            // Here you would typically save this data to a database and possibly send a confirmation email

            $this->addFlash('success', "Your test drive for the $model on $date at $time has been booked successfully.");
            return $this->redirectToRoute('book_test_drive');
        }

        return $this->render('testdrive/index.html.twig', [
            'vehicles' => $vehicles,
        ]);
    }
}
