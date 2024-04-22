<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CheckoutControllerTest extends WebTestCase
{
    public function testCheckoutWithCash()
    {
        $client = static::createClient();

        // Simulate a POST request to checkout with cash
        $crawler = $client->request('POST', '/cars/checkout/1/cash');

        // Assert that the response is a redirect to the checkout success page
        $this->assertTrue($client->getResponse()->isRedirect('/cars/checkout/success/1'));

        // Alternatively, you can also assert that the success message is displayed
        // $this->assertSelectorTextContains('div.alert-success', 'Sale completed successfully!');
    }

    public function testCheckoutWithFinance()
    {
        $client = static::createClient();

        // Simulate a POST request to checkout with financing
        $crawler = $client->request('POST', '/cars/checkout/1/finance', [], [], [], json_encode([
            'amount' => '20000',
            'interestRate' => '5',
            'term' => '48',
        ]));

        // Assert that the response contains the success message
        $this->assertJsonStringEqualsJsonString('{"message":"Finance application submitted successfully!"}', $client->getResponse()->getContent());
    }

    // Add more test methods for other controller actions as needed...
}
