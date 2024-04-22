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
        $vehicles = $this->entityManager->getRepository(Car::class)->findAll();

        $search = $request->query->get('search');

        if ($search) {
            $vehicles = $this->entityManager->getRepository(Car::class)->findByMakeOrModel($search);
        }

        return $this->render('vehindex/index.html.twig', [
            'vehicles' => $vehicles,
        ]);
    }

    #[Route('/search', name: 'veh_index_search', methods: ['GET'])]
    public function search(Request $request): Response
    {
        $searchTerm = $request->query->get('search');

        return $this->redirectToRoute('veh_index', ['search' => $searchTerm]);
    }

    #[Route('/reset', name: '_reset')]
    public function resetSearch(): Response
    {
        return $this->redirectToRoute('veh_index');
    }
}
