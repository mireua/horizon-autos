<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestDriveControllerTest extends WebTestCase
{
    public function testBookPage()
    {
        $client = static::createClient();
        $client->request('GET', '/vehicles');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Book a Test Drive');
    }

    public function testBookTestDrive()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/vehicles/book-test-drive/1', [], [], ['CONTENT_TYPE' => 'application/json'], '{"scheduledTime": "2024-04-25 10:00:00"}');

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            'message' => 'Test drive booked, it will be reviewed shortly.'
        ]);
    }

    public function testSubmitInquiry()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/vehicles/submit-inquiry/1', [], [], ['CONTENT_TYPE' => 'application/json'], '{"message": "Test inquiry message"}');

        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
            'message' => 'Inquiry submitted successfully'
        ]);
    }
}
