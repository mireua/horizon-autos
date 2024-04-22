<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VehDetailsControllerTest extends WebTestCase
{
    public function testDetailsPage()
    {
        $client = static::createClient();
        $client->request('GET', '/vehicles/1');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Vehicle Details');
    }
}
