<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterControllerTest extends WebTestCase
{
    public function testRegisterPage()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Register');
    }

    public function testRegisterUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $buttonCrawlerNode = $crawler->selectButton('Register');

        $form = $buttonCrawlerNode->form([
            'registration_form[email]' => 'test@example.com',
            'registration_form[plainPassword]' => 'password123',
            // Add other form fields here as needed
        ]);

        $client->submit($form);

        $this->assertResponseRedirects('/login'); // Assuming it redirects to login after successful registration
    }
}
