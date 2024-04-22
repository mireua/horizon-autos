<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Sale;
use App\Entity\User;
use App\Entity\Financing;
use App\Factory\FinancingFactory;
use App\Repository\SaleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

#[Route('/cars')]
class CheckoutController extends AbstractController
{
    private $entityManager;
    private $saleRepository;

    public function __construct(EntityManagerInterface $entityManager, SaleRepository $saleRepository)
    {
        $this->entityManager = $entityManager;
        $this->saleRepository = $saleRepository;
    }

    #[Route('/checkout/{id}', name: 'checkout', methods: ['GET', 'POST'])]
    public function checkout(int $id, Request $request): Response
    {
        // Retrieve the car details from the database
        $car = $this->entityManager->getRepository(Car::class)->find($id);

        // Render the checkout template with the car details
        return $this->render('checkout/index.html.twig', [
            'car' => $car,
        ]);
    }

    #[Route('/checkout/{id}/cash', name: 'checkout_cash', methods: ['POST', 'GET'])]
    public function checkoutWithCash(int $id, Request $request): Response
    {
        // Retrieve the car details from the database
        $car = $this->entityManager->getRepository(Car::class)->find($id);

        // Retrieve the current user (assuming user is authenticated)
        $user = $this->getUser();

        // Create a new Sale entity
        $sale = new Sale();
        $sale->setCar($car);
        $sale->setUser($user);
        $sale->setDate(new \DateTime());
        $sale->setTotalCost($car->getPrice());

        // Persist the Sale entity
        $this->entityManager->persist($sale);
        $this->entityManager->flush();

        // Redirect or render success page
        return $this->redirectToRoute('checkout_success', ['id' => $sale->getId()]);
    }

    #[Route('/checkout/{id}/finance', name: 'checkout_finance', methods: ['GET', 'POST'])]
    public function checkoutWithFinance(int $id, Request $request): Response
    {
        $car = $this->entityManager->getRepository(Car::class)->find($id);
        if (!$car) {
            throw $this->createNotFoundException('Car not found');
        }

        $user = $this->getUser();

        // Check if a financing request already exists for this user and car
        $existingFinancing = $this->entityManager->getRepository(Financing::class)
            ->findOneBy(['car' => $car, 'user' => $user]);

        if ($existingFinancing) {
            return $this->json(['message' => 'You already have a financing request for this car!']);
        }

        if ($request->isMethod('POST')) {
            $amount = $request->request->get('amount');
            $interestRate = $request->request->get('interestRate');
            $term = $request->request->get('term');
        
            $financing = FinancingFactory::create($car, $user, (float) $amount, (float) $interestRate, (int) $term);
            $financing->setStatus('pending');
            $this->entityManager->persist($financing);
            $this->entityManager->flush();
        
            return $this->json(['message' => 'Finance application submitted successfully!']);
        }

        // Render the financing form
        return $this->render('checkout/finance.html.twig', [
            'car' => $car,
        ]);
    }

    #[Route('/checkout/{id}/success', name: 'checkout_success', methods: ['GET'])]
    public function checkoutSuccess(int $id): Response
    {
        // Retrieve the sale details from the database
        $sale = $this->saleRepository->find($id);

        // Render the success template with the sale details
        return $this->render('checkout/success.html.twig', [
            'sale' => $sale,
        ]);
    }
}
