<?php

namespace App\Tests\Func;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/livres');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Les livres de GeekBook');
    }
}
