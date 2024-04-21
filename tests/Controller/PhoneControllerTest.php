<?php

namespace App\Test\Controller;

use App\Entity\Phone;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PhoneControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/phone/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Phone::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Phone index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'phone[model]' => 'Testing',
            'phone[memory]' => 'Testing',
            'phone[manufacturer]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Phone();
        $fixture->setModel('My Title');
        $fixture->setMemory('My Title');
        $fixture->setManufacturer('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Phone');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Phone();
        $fixture->setModel('Value');
        $fixture->setMemory('Value');
        $fixture->setManufacturer('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'phone[model]' => 'Something New',
            'phone[memory]' => 'Something New',
            'phone[manufacturer]' => 'Something New',
        ]);

        self::assertResponseRedirects('/phone/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getModel());
        self::assertSame('Something New', $fixture[0]->getMemory());
        self::assertSame('Something New', $fixture[0]->getManufacturer());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Phone();
        $fixture->setModel('Value');
        $fixture->setMemory('Value');
        $fixture->setManufacturer('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/phone/');
        self::assertSame(0, $this->repository->count([]));
    }
}
