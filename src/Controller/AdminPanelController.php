<?php

namespace App\Controller;

use App\Entity\TestDriveAppointment;
use App\Entity\User;
use App\Entity\Inquiry;
use App\Entity\Financing;
use App\Entity\Car;
use App\Entity\CarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminPanelController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/admin', name: 'admin_panel')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/admin/car-list', name: 'admin_car_list')]
    public function adminCarList(EntityManagerInterface $entityManager): Response
    {
        $vehicles = $entityManager->getRepository(Car::class)->findAll();
    
        return $this->render('admin/car_listings.html.twig', [
            'vehicles' => $vehicles,
        ]);
    }
    
    #[Route('/admin/car-list/new', name: 'admin_car_create', methods: ['GET', 'POST'])]
    public function adminCarCreate(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Handle form submission for creating a new car
        if ($request->isMethod('POST')) {
            $data = $request->request->all();
    
            // Create a new car entity
            $car = new Car();
            $car->setMake($data['make']);
            $car->setModel($data['model']);
            $car->setYear($data['year']);
            $car->setEngine($data['engine']);
            $car->setPrice((float)$data['price']);
            $car->setVin($data['vin']);
            $car->setDescription($data['description']);
            $car->setStatus('Available');
            $car->setImage($data['image']);
            // Set other fields accordingly
    
            // Persist the new car entity
            $entityManager->persist($car);
            $entityManager->flush();
    
            $this->addFlash('success', 'Car created successfully.');
            return $this->redirectToRoute('admin_car_list');
        }
    
        return $this->render('admin/admin_car_create.html.twig');
    }
    
    #[Route('/admin/car-list/{id}/edit', name: 'admin_car_edit', methods: ['GET', 'POST'])]
    public function adminCarEdit(Car $car, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Handle form submission for editing an existing car
        if ($request->isMethod('POST')) {
            $data = $request->request->all();
    
            // Update car entity with new data
            $car->setMake($data['make']);
            $car->setModel($data['model']);
            $car->setYear($data['year']);
            $car->setEngine($data['engine']);
            $car->setPrice($data['price']);
            $car->setVin($data['vin']);
            $car->setDescription($data['description']);
            $car->setImage($data['image']);
            // Update other fields accordingly
    
            // Persist the updated car entity
            $entityManager->flush();
    
            $this->addFlash('success', 'Car updated successfully.');
            return $this->redirectToRoute('admin_car_list');
        }
    
        return $this->render('admin/admin_car_edit.html.twig', [
            'car' => $car,
        ]);
    }
    
    #[Route('/admin/car-list/{id}', name: 'admin_car_delete', methods: ['DELETE'])]
    public function adminCarDelete(Car $car, EntityManagerInterface $entityManager): Response
    {
        // Delete the car entity
        $entityManager->remove($car);
        $entityManager->flush();
    
        $this->addFlash('success', 'Car deleted successfully.');
        return $this->redirectToRoute('admin_car_list');
    }

    
    #[Route('/admin/test-drives', name: 'admin_test_drives')]
    public function viewTestDrives(): Response
    {
        $testDrives = $this->entityManager->getRepository(TestDriveAppointment::class)->findAll();
        
        return $this->render('admin/test_drives.html.twig', [
            'testDrives' => $testDrives,
        ]);
    }

    #[Route('/admin/test-drives/approve/{id}', name: 'approve_test_drive', methods: ['GET'])]
    public function showApproveServiceModal(TestDriveAppointment $testDrive): Response
    {
        return $this->render('admin/approve_test_drive_modal.html.twig', [
            'testDrive' => $testDrive,
        ]);
    }

    #[Route('/admin/test-drives/allocate/{id}', name: 'allocate_time_slot', methods: ['POST'])]
    public function allocateTimeSlot(Request $request, TestDriveAppointment $testDrive): Response
    {
        $timeSlot = $request->request->get('timeSlot');
        
        $testDrive->setScheduledTime(new \DateTime($timeSlot));
        $testDrive->setStatus('approved');
        $this->entityManager->flush();

        $this->addFlash('success', 'Time slot allocated successfully!');
        return $this->redirectToRoute('admin_test_drives');
    }

    #[Route('/admin/test-drives/deny/{id}', name: 'deny_test_drive', methods: ['POST'])]
    public function denyTestDrive(TestDriveAppointment $testDrive): Response
    {
        $this->entityManager->remove($testDrive);
        $this->entityManager->flush();
        
        $this->addFlash('success', 'Test drive application denied and deleted successfully!');
        return $this->redirectToRoute('admin_test_drives');
    }

    #[Route('/admin/service-requests', name: 'admin_service_requests')]
    public function viewServiceRequests(): Response
    {
        $serviceRequests = $this->entityManager->getRepository(CarService::class)->findAll();
        
        return $this->render('admin/service_requests.html.twig', [
            'serviceRequests' => $serviceRequests,
        ]);
    }

    #[Route('/admin/service-requests/approve/{id}', name: 'approve_service_request', methods: ['GET'])]
    public function showApproveModal(CarService $serviceRequest): Response
    {
        return $this->render('admin/approve_service_request_modal.html.twig', [
            'serviceRequest' => $serviceRequest,
        ]);
    }

    #[Route('/admin/service-requests/allocate/{id}', name: 'allocate_time_slot', methods: ['POST'])]
    public function allocateServiceTimeSlot(Request $request, CarService $serviceRequest): Response
    {
        $timeSlot = $request->request->get('timeSlot');
        
        $serviceRequest->setServiceDate(new \DateTime($timeSlot));
        $serviceRequest->setStatus('approved');
        $this->entityManager->flush();

        $this->addFlash('success', 'Time slot allocated successfully!');
        return $this->redirectToRoute('admin_service_requests');
    }

    #[Route('/admin/service-requests/deny/{id}', name: 'deny_service_request', methods: ['POST'])]
    public function denyServiceRequest(CarService $serviceRequest): Response
    {
        $this->entityManager->remove($serviceRequest);
        $this->entityManager->flush();
        
        $this->addFlash('success', 'Service request denied and deleted successfully!');
        return $this->redirectToRoute('admin_service_requests');
    }

    

    #[Route('/admin/accounts', name: 'admin_accounts', methods: ['GET'])]
    public function manageAccounts(): Response
    {
        $users = $this->entityManager->getRepository(User::class)->findAll();
        return $this->render('admin/accounts.html.twig', ['users' => $users]);
    }

    #[Route('/admin/accounts/edit/{id}', name: 'admin_edit_account', methods: ['GET', 'POST'])]
    public function editAccount(Request $request, User $user): Response
    {
        if ($request->isMethod('POST')) {
            // Update user details only if form fields are not empty
            $name = $request->request->get('name');
            $email = $request->request->get('email');
            $phoneNumber = $request->request->get('phoneNumber');

            if (!empty($name)) {
                $user->setName($name);
            }
            if (!empty($email)) {
                $user->setEmail($email);
            }
            if (!empty($phoneNumber)) {
                $user->setPhone($phoneNumber);
            }

            $this->entityManager->flush();

            $this->addFlash('success', 'User updated successfully!');
            return $this->redirectToRoute('admin_accounts');
        }

        return $this->render('admin/edit_account.html.twig', ['user' => $user]);
    }

    #[Route('/admin/accounts/data/{id}', name: 'admin_fetch_user', methods: ['GET'])]
    public function fetchUserData(User $user): Response
    {
        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'phone' => $user->getPhone()
        ]);
    }

    #[Route('/admin/accounts/delete/{id}', name: 'admin_delete_account', methods: ['POST'])]
    public function deleteAccount(User $user): Response
    {
        foreach ($user->getSentInquiries() as $inquiry) {
            $user->removeSentInquiry($inquiry);
            $this->entityManager->remove($inquiry);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'User and all related inquiries deleted successfully!');
        return $this->redirectToRoute('admin_accounts');
    }

    #[Route('/admin/accounts/promote/{id}', name: 'admin_promote_account', methods: ['POST'])]
    public function promoteAccount(User $user): Response
    {
        if (!in_array('ROLE_MANAGER', $user->getRoles())) {
            $user->setRoles(array_merge($user->getRoles(), ['ROLE_SALESPERSON']));
            $this->entityManager->flush();
            $this->addFlash('success', 'User promoted to Salesperson!');
        }
        return $this->redirectToRoute('admin_accounts');
    }

    #[Route('/admin/accounts/demote/{id}', name: 'admin_demote_account', methods: ['POST'])]
    public function demoteAccount(User $user): Response
    {
        $user->setRoles(['ROLE_CUSTOMER']);
        $this->entityManager->flush();
        $this->addFlash('success', 'User demoted to Customer.');
        return $this->redirectToRoute('admin_accounts');
    }

    #[Route('/admin/financing', name: 'admin_financing', methods: ['GET'])]
    public function manageFinance(): Response
    {
        $finance = $this->entityManager->getRepository(Financing::class)->findAll();
        return $this->render('admin/financing.html.twig', ['financings' => $finance]);
    }

    #[Route('/admin/financing/approve/{id}', name: 'approve_financing', methods: ['POST'])]
    public function approveFinancing(Financing $financing): Response
    {
        $financing->setStatus('approved');
        $this->entityManager->flush();

        $this->addFlash('success', 'Financing request approved successfully!');
        return $this->redirectToRoute('admin_financing');
    }

    #[Route('/admin/financing/deny/{id}', name: 'deny_financing', methods: ['POST'])]
    public function denyFinancing(Financing $financing): Response
    {
        $financing->setStatus('denied');
        $this->entityManager->flush();

        $this->addFlash('success', 'Financing request denied successfully!');
        return $this->redirectToRoute('admin_financing');
    }
}
