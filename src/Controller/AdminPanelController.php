<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPanelController extends AbstractController
{
    #[Route('/admin', name: 'admin_panel')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/admin/test-drives', name: 'admin_test_drives')]
    public function viewTestDrives(): Response
    {
        // Implement logic to fetch and show test drives
        return $this->render('admin/test_drives.html.twig');
    }

    #[Route('/admin/service-requests', name: 'admin_service_requests')]
    public function viewServiceRequests(): Response
    {
        // Implement logic to fetch and show service requests
        return $this->render('admin/service_requests.html.twig');
    }

    #[Route('/admin/accounts', name: 'admin_accounts')]
    public function manageAccounts(): Response
    {
        // Implement logic for CRUD on accounts
        return $this->render('admin/accounts.html.twig');
    }

    #[Route('/admin/crud', name: 'admin_crud')]
    public function crudAccounts(): Response
    {
        // Implement logic for CRUD on accounts
        return $this->render('admin/crud.html.twig');
    }

    #[Route('/admin/create', name: 'admin_create')]
    public function createAccounts(): Response
    {
        // Implement logic for CRUD on accounts
        return $this->render('admin/create.html.twig');
    }

    #[Route('/admin/car-listings', name: 'admin_car_listings')]
    public function manageCarListings(): Response
    {
        // Implement logic for CRUD on car listings
        return $this->render('admin/car_listings.html.twig');
    }

    #[Route('/admin/financing', name: 'admin_financing')]
    public function viewFinancingRequests(): Response
    {
        // Implement logic to fetch and show financing requests
        return $this->render('admin/financing.html.twig');
    }
}