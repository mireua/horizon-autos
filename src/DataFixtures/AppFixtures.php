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
        $admin = $this->userFactory->create('manager@example.com', 'password', ['ROLE_MANAGER'], 'John Doe', '123 Admin St', '123-456-7890');
        $manager->persist($admin);

        $salesperson = $this->userFactory->create('sales@example.com', 'password', ['ROLE_SALESPERSON'], 'Jane Smith', '456 Sales Ave', '987-654-3210');
        $manager->persist($salesperson);

        $customer = $this->userFactory->create('customer@example.com', 'password', ['ROLE_CUSTOMER'], 'Alice Johnson', '789 Customer Rd', '321-654-9870');
        $manager->persist($customer);

        $technician = $this->userFactory->create('technician@example.com', 'password', ['ROLE_TECHNICIAN'], 'Bob Technician', '456 Technician Rd', '789-654-3210');
        $manager->persist($technician);

        // Create cars
        $vehicles = [
            // BMW
            ['make' => 'BMW', 'model' => '3 Series', 'description' => 'The BMW 3 Series is a compact executive car with a blend of performance and luxury.', 'price' => 41000, 'image' => '3series.jpg', 'engineSize' => '2.0L'],
            ['make' => 'BMW', 'model' => '5 Series', 'description' => 'The BMW 5 Series combines elegance, comfort, and advanced technology.', 'price' => 53000, 'image' => '5series.jpg', 'engineSize' => '3.0L'],

            // Audi
            ['make' => 'Audi', 'model' => 'A4', 'description' => 'The Audi A4 offers cutting-edge technology and a comfortable ride.', 'price' => 39000, 'image' => 'a4.jpg', 'engineSize' => '2.0L'],
            ['make' => 'Audi', 'model' => 'A6', 'description' => 'The Audi A6 is a mid-size luxury sedan with exceptional performance and refinement.', 'price' => 55000, 'image' => 'a6.jpg', 'engineSize' => '3.5L'],

            // Volkswagen
            ['make' => 'Volkswagen', 'model' => 'Golf', 'description' => 'The VW Golf presents a perfect balance of comfort and style.', 'price' => 23000, 'image' => 'golf.jpg', 'engineSize' => '1.8L'],
            ['make' => 'Volkswagen', 'model' => 'Passat', 'description' => 'The Volkswagen Passat is a spacious and practical family sedan.', 'price' => 35000, 'image' => 'passat.jpg', 'engineSize' => '2.0L'],

            // Nissan
            ['make' => 'Nissan', 'model' => 'Altima', 'description' => 'A reliable sedan with a focus on comfort and efficiency.', 'price' => 24000, 'image' => 'altima.jpg', 'engineSize' => '2.5L'],
            ['make' => 'Nissan', 'model' => 'Maxima', 'description' => 'The Nissan Maxima is a stylish and sporty full-size sedan with premium features.', 'price' => 36000, 'image' => 'maxima.jpg', 'engineSize' => '3.5L'],

            // Toyota
            ['make' => 'Toyota', 'model' => 'Camry', 'description' => 'The Toyota Camry offers reliability and value with advanced safety features.', 'price' => 25000, 'image' => 'camry.jpg', 'engineSize' => '2.5L'],
            ['make' => 'Toyota', 'model' => 'Corolla', 'description' => 'The Toyota Corolla is a compact sedan known for its fuel efficiency and practicality.', 'price' => 22000, 'image' => 'corolla.jpg', 'engineSize' => '1.8L'],

            // Honda
            ['make' => 'Honda', 'model' => 'Accord', 'description' => 'The Honda Accord is known for its efficiency and longevity.', 'price' => 26000, 'image' => 'accord.jpg', 'engineSize' => '2.0L'],
            ['make' => 'Honda', 'model' => 'Civic', 'description' => 'The Honda Civic is a versatile and reliable compact car with a comfortable ride.', 'price' => 23000, 'image' => 'civic.jpg', 'engineSize' => '1.5L'],
        ];

        foreach ($vehicles as $vehicle) {
            $make = $vehicle['make'];
            $model = $vehicle['model'];
            $description = $vehicle['description'];
            $price = $vehicle['price'];
            $image = $vehicle['image'];
            $engineSize = $vehicle['engineSize'];

            $year = random_int(2013, 2021); // Random year between 2013 and 2021
            $county = ['C', 'CNW', 'CE', 'CWW', 'CN', 'CW', 'D', 'DL', 'D', 'G', 'KE', 'KY', 'KK', 'L', 'LD', 'LH', 'LK', 'LM', 'LS', 'MH', 'MN', 'MO', 'OY', 'RN', 'SO', 'T', 'W', 'WD', 'WH', 'WX', 'WW'];
            $countyCode = $county[array_rand($county)]; // Random county code
            $sequenceNumber = str_pad(random_int(1, 999), 3, '0', STR_PAD_LEFT);

            $vin = sprintf('%02d-%s-%s', $year, $countyCode, $sequenceNumber);

            $car = $this->carFactory->create($make, $model, $year, $engineSize, $price, $vin, 'Available', $description, $image);
            $manager->persist($car);
        }

        // Flush all changes to the database
        $manager->flush();
    }

}
