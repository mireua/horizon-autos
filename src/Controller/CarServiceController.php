<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\CarService;
use App\Entity\Sale;
use App\Entity\User;
use App\Factory\CarServiceFactory;
use App\Repository\CarRepository;
use App\Repository\SaleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/')]
class CarServiceController extends AbstractController{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/service', name: 'car_service')]
    public function index(SaleRepository $saleRepository): Response
    {
        $sales = $this->entityManager->getRepository(Sale::class)->findAll();
        $vehicles = $this->entityManager->getRepository(Car::class)->findAll();


        return $this->render('user/service.html.twig', [
            'sales' => $sales,
            'vehicles' => $vehicles,
        ]);
    }
}