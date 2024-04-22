<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testProfilePage()
    {
        $client = static::createClient();
        $client->request('GET', '/myprofile');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'User Profile');
    }

    public function testInboxPage()
    {
        $client = static::createClient();
        $client->request('GET', '/inbox');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Inbox');
    }

    public function testOrdersPage()
    {
        $client = static::createClient();
        $client->request('GET', '/orders');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Orders');
    }
}
