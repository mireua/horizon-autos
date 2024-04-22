<?php

namespace App\Controller;

use App\Entity\CarService;
use App\Entity\Sale;
use App\Entity\Car;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/')]
class CarServiceController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/service', name: 'car_service')]
    public function index(): Response
    {
        $sales = $this->entityManager->getRepository(Sale::class)->findAll();
        $vehicles = $this->entityManager->getRepository(Car::class)->findAll();

        return $this->render('carservice/service.html.twig', [
            'sales' => $sales,
            'vehicles' => $vehicles,
        ]);
    }

    #[Route('/service/book', name: 'book_service', methods: ['POST'])]
    public function bookService(Request $request): Response
    {
        $carId = $request->request->get('car');
        $userId = $this->getUser()->getId();
        $scheduledTime = new \DateTime($request->request->get('scheduledTime'));

        $car = $this->entityManager->getRepository(Car::class)->find($carId);
        $user = $this->entityManager->getRepository(User::class)->find($userId);

        $carService = new CarService();
        $carService->setCar($car);
        $carService->setUser($user);
        $carService->setStatus('pending');
        $carService->setServiceDate($scheduledTime);

        $this->entityManager->persist($carService);
        $this->entityManager->flush();

        return $this->redirectToRoute('homepage');
    }
}
