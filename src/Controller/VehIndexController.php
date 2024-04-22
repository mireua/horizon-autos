<?php

namespace App\Controller;

use App\Entity\Car;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/vehicleindex')]
class VehIndexController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'veh_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        // Retrieve vehicles from the database
        $vehicles = $this->entityManager->getRepository(Car::class)->findAll();

        // Fetch sorting, filtering, and search parameters from request
        $sortPrice = $request->query->get('sortPrice', 'asc');
        $sortYear = $request->query->get('sortYear', 'asc');
        $sortEngine = $request->query->get('sortEngine', 'asc');
        $search = $request->query->get('search');

        // Apply filtering and sorting logic
        // Note: Implement the logic for filtering and sorting based on year and engine size
        // For demonstration purpose, assuming all vehicles are filtered and sorted already

        // Modify engine size for comparison
        foreach ($vehicles as $vehicle) {
            $engineSize = $vehicle->getEngine();
            // Remove 'L' from engine size and convert to float for comparison
            $engineSizeForComparison = (float) str_replace('L', '', $engineSize);
            $vehicle->engineSizeForComparison = $engineSizeForComparison;
        }

        // Rendering the template with vehicles data
        return $this->render('vehindex/index.html.twig', [
            'vehicles' => $vehicles,
        ]);
    }
}
