<?php

namespace App\Controller;

use App\Entity\TestDriveAppointment;
use App\Entity\User;
use App\Entity\Inquiry;
use App\Entity\Financing;
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
    #[Route('/admin/test-drives', name: 'admin_test_drives')]
    public function viewTestDrives(): Response
    {
        $testDrives = $this->entityManager->getRepository(TestDriveAppointment::class)->findAll();
        
        return $this->render('admin/test_drives.html.twig', [
            'testDrives' => $testDrives,
        ]);
    }

    #[Route('/admin/test-drives/approve/{id}', name: 'approve_test_drive', methods: ['GET'])]
    public function showApproveModal(TestDriveAppointment $testDrive): Response
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
        return $this->render('admin/service_requests.html.twig');
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

    #[Route('/admin/car-listings', name: 'admin_car_listings')]
    public function manageCarListings(): Response
    {
        return $this->render('admin/car_listings.html.twig');
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
