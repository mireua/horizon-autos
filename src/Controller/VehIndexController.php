<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/vehicles')]
class VehIndexController extends AbstractController
{
    #[Route('/', name: 'veh_index', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        // Placeholder vehicle data - you would typically fetch this from a database
        $vehicles = [
            ['model' => 'BMW 3 Series', 'description' => 'The BMW 3 Series is a compact executive car with a blend of performance and luxury.', 'price' => 41000, 'image' => 'placeholder-bmw3.jpg'],
            ['model' => 'Audi A4', 'description' => 'The Audi A4 offers cutting-edge technology and a comfortable ride.', 'price' => 39000, 'image' => 'placeholder-audi-a4.jpg'],
            ['model' => 'Volkswagen Golf', 'description' => 'The VW Golf presents a perfect balance of comfort and style.', 'price' => 23000, 'image' => 'placeholder-vwgolf.jpg'],
            ['model' => 'Nissan Altima', 'description' => 'A reliable sedan with a focus on comfort and efficiency.', 'price' => 24000, 'image' => 'placeholder-nissan-altima.jpg'],
            ['model' => 'Toyota Camry', 'description' => 'The Toyota Camry offers reliability and value with advanced safety features.', 'price' => 25000, 'image' => 'placeholder-toyota-camry.jpg'],
            ['model' => 'Honda Accord', 'description' => 'The Honda Accord is known for its efficiency and longevity.', 'price' => 26000, 'image' => 'placeholder-honda-accord.jpg'],
        ];

        // Fetch sorting and filtering parameters from request
        $sort = $request->query->get('sort', 'asc');
        $filterModel = $request->query->get('model', 'all');

        // Filter and sort logic - could be optimized and handled by a database query in a real scenario
        if ($filterModel !== 'all') {
            $vehicles = array_filter($vehicles, function ($vehicle) use ($filterModel) {
                return $vehicle['model'] === $filterModel;
            });
        }

        if ($sort === 'asc') {
            usort($vehicles, function ($a, $b) {
                return $a['price'] <=> $b['price'];
            });
        } elseif ($sort === 'desc') {
            usort($vehicles, function ($a, $b) {
                return $b['price'] <=> $a['price'];
            });
        }

        return $this->render('vehindex/index.html.twig', [
            'vehicles' => $vehicles,
        ]);
    }
}
