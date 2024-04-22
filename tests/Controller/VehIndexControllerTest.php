<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VehIndexControllerTest extends WebTestCase
{
    public function testIndexPage()
    {
        $client = static::createClient();
        $client->request('GET', '/vehicleindex/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Vehicle Index');
    }

    public function testSearch()
    {
        $client = static::createClient();
        $client->request('GET', '/vehicleindex/?search=Audi');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Vehicle Index');
        // Add additional assertions for search results
    }
}
