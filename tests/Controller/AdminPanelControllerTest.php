<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminPanelControllerTest extends WebTestCase
{
    public function testAdminCarList()
    {
        $client = static::createClient();

        // Simulate a request to the admin car list page
        $client->request('GET', '/admin/car-list');

        // Assert that the response is successful (HTTP status code 200)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Assert that the page contains expected content
        $this->assertSelectorTextContains('h1', 'Car Listings');
    }

    public function testAdminCarCreate()
    {
        $client = static::createClient();

        // Simulate a POST request to create a new car
        $client->request('POST', '/admin/car-list/new', [], [], [], json_encode([
            'make' => 'Toyota',
            'model' => 'Camry',
            'year' => '2022',
            'engine' => 'V6',
            'price' => '25000',
            'vin' => 'ABC123',
            'description' => 'A reliable sedan.',
            'image' => 'camry.jpg', // Assuming you have an image file named 'camry.jpg'
        ]));

        // Assert that the response is a redirect to the admin car list page
        $this->assertTrue($client->getResponse()->isRedirect('/admin/car-list'));
    }

    // Add more test methods for other controller actions as needed...
}
