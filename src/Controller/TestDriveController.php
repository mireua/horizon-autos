<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\User;
use App\Entity\Inquiry;
use App\Entity\TestDriveAppointment;
use App\Factory\TestDriveAppointmentFactory;
use App\Factory\InquiryFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/vehicles')]
class TestDriveController extends AbstractController
{
    private $entityManager;
    private $testDriveAppointmentFactory;

    public function __construct(EntityManagerInterface $entityManager, TestDriveAppointmentFactory $testDriveAppointmentFactory)
    {
        $this->entityManager = $entityManager;
        $this->testDriveAppointmentFactory = $testDriveAppointmentFactory;
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

    #[Route('/book-test-drive/{carId}', name: 'book_test_drive', methods: ['POST'])]
    public function bookTestDrive(int $carId, Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->json(['message' => 'Authentication required'], Response::HTTP_UNAUTHORIZED);
        }

        $car = $this->entityManager->getRepository(Car::class)->find($carId);
        if (!$car) {
            return $this->json(['message' => 'Car not found'], Response::HTTP_NOT_FOUND);
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $this->getUser()->getEmail()]);

        // Check if the user already has a booking for this car
        $existingBooking = $this->entityManager->getRepository(TestDriveAppointment::class)
                            ->findOneBy(['car' => $car, 'user' => $user]);

        if ($existingBooking) {
            return $this->json([
                'message' => 'You have already booked a test drive for this car.'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $scheduledTime = new \DateTime($request->request->get('scheduledTime'));
        } catch (\Exception $e) {
            return $this->json(['message' => 'Invalid date format'], Response::HTTP_BAD_REQUEST);
        }

        $status = 'pending'; // Assuming the default status is 'pending'
        $appointment = $this->testDriveAppointmentFactory->create($car, $user, $scheduledTime, $status);
        $this->entityManager->persist($appointment);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Test drive booked, it will be reviewed shortly.',
            'appointmentId' => $appointment->getId()
        ], Response::HTTP_CREATED);
    }

    #[Route('/submit-inquiry/{carId}', name: 'submit_inquiry', methods: ['POST'])]
    public function submitInquiry(Request $request, int $carId): Response
    {
        // Get the user from the request (adjust this as necessary based on how you manage user sessions)
        $userId = $this->getUser()->getId();  // Assuming you are using Symfony's default security
        $user = $this->entityManager->getRepository(User::class)->find($userId);
        if (!$user) {
            return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        // Find the car associated with the inquiry
        $car = $this->entityManager->getRepository(Car::class)->find($carId);
        if (!$car) {
            return $this->json(['message' => 'Car not found'], Response::HTTP_NOT_FOUND);
        }

        // Find an appropriate receiver based on roles
        $eligibleRoles = ['ROLE_MANAGER', 'ROLE_SALESPERSON', 'ROLE_TECHNICIAN'];
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('u')
            ->from(User::class, 'u')
            ->where('u.roles LIKE :managerRole')
            ->orWhere('u.roles LIKE :salesRole')
            ->orWhere('u.roles LIKE :techRole')
            ->setParameter('managerRole', '%"ROLE_MANAGER"%')
            ->setParameter('salesRole', '%"ROLE_SALESPERSON"%')
            ->setParameter('techRole', '%"ROLE_TECHNICIAN"%')
            ->setMaxResults(1);

        $receiver = $queryBuilder->getQuery()->getOneOrNullResult();
        if (!$receiver) {
            return $this->json(['message' => 'No eligible receiver found'], Response::HTTP_BAD_REQUEST);
        }

        // Create the inquiry
        $message = $request->request->get('message');
        $inquiry = InquiryFactory::create($user, $receiver, $message, $car);

        // Persist the inquiry
        $this->entityManager->persist($inquiry);
        $this->entityManager->flush();

        // Return success response
        return $this->json(['message' => 'Inquiry submitted successfully']);
    }
}
