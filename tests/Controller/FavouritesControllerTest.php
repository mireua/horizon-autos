<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FavoritesControllerTest extends WebTestCase
{
    public function testViewFavorites()
    {
        $client = static::createClient();
        $client->loginUser(); // Log in as a user

        $client->request('GET', '/favorites');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Your Favorites');
    }

    public function testAddToFavorites()
    {
        $client = static::createClient();
        $client->loginUser(); // Log in as a user

        $carId = 1; // Provide an existing car id
        $client->request('GET', '/favorites/add/' . $carId);

        $this->assertResponseRedirects('/favorites');
    }

    public function testRemoveFromFavorites()
    {
        $client = static::createClient();
        $client->loginUser(); // Log in as a user

        $favoriteId = 1; // Provide an existing favorite id
        $client->request('GET', '/favorites/remove/' . $favoriteId);

        $this->assertResponseRedirects('/favorites');
    }
}
