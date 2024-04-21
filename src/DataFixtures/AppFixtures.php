<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Factory\UserFactory;
use App\Factory\CarFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userFactory;
    private $carFactory;
    private $passwordHasher;

    public function __construct(UserFactory $userFactory, CarFactory $carFactory, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userFactory = $userFactory;
        $this->carFactory = $carFactory;
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Create users
        $admin = $this->userFactory->create('admin@example.com', 'password', ['ROLE_ADMIN'], 'Admin User', '123 Admin St', '123-456-7890');
        $manager->persist($admin);

        $salesperson = $this->userFactory->create('sales@example.com', 'password', ['ROLE_SALESPERSON'], 'Sales Person', '456 Sales Ave', '987-654-3210');
        $manager->persist($salesperson);

        $customer = $this->userFactory->create('customer@example.com', 'password', ['ROLE_CUSTOMER'], 'Customer User', '789 Customer Rd', '321-654-9870');
        $manager->persist($customer);

        // Create cars
        $car1 = $this->carFactory->create('Toyota', 'Corolla', 2020, '1.8L', 20000.00, 'JTDBU4EE5A9116887', 'Available', 'A reliable city car.');
        $manager->persist($car1);

        $car2 = $this->carFactory->create('Honda', 'Civic', 2019, '2.0L', 22000.00, '2HGFC2F69KH570123', 'Sold', 'Sporty and fun.');
        $manager->persist($car2);

        // Flush all changes to the database
        $manager->flush();
    }
}
