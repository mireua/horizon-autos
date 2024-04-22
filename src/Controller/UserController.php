<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\Inquiry;
use App\Entity\Car;
use App\Entity\CarService;
use App\Entity\Sale;
use App\Entity\Financing;
use App\Entity\TestDriveAppointment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route(path: '/myprofile', name: 'user_profile')]
    public function profile(): Response
    {
        // Initialize user to null
        $user = null;

        // Check if user is logged in
        if ($this->getUser()) {
            // Retrieve the current user details from the database
            $userRepository = $this->entityManager->getRepository(User::class);
            $user = $userRepository->findOneBy(['email' => $this->getUser()->getEmail()]);
        }

        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route(path: '/inbox/{inquiryId?}', name: 'user_inbox')]  // Optional inquiryId parameter
    public function inbox(Request $request, ?int $inquiryId = null): Response
    {
        $user = $this->getUser();

        // Fetch all inquiries where the user is involved
        $inquiryRepository = $this->entityManager->getRepository(Inquiry::class);
        $inquiries = $inquiryRepository->createQueryBuilder('i')
            ->andWhere('i.sender = :user OR i.receiver = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        $selectedInquiry = null;
        if ($inquiryId) {
            $selectedInquiry = $inquiryRepository->find($inquiryId);
            if (!$selectedInquiry) {
                $this->addFlash('error', 'Inquiry not found.');
                return $this->redirectToRoute('user_inbox');
            }
        }

        return $this->render('user/messages.html.twig', [
            'inquiries' => $inquiries,
            'selectedInquiry' => $selectedInquiry,
            'user' => $user,
        ]);
    }

    #[Route(path: '/orders', name: 'user_orders')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Fetch sales
        $sales = $entityManager->getRepository(Sale::class)->findAll();
    
        return $this->render('user/orders.html.twig', [
            'sales' => $sales,
        ]);
    }

}
