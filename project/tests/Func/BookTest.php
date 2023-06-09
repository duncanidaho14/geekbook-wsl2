<?php

namespace App\Tests\Func;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookTest extends WebTestCase
{
    
     /**
     * @var AbstractDatabaseTool
     */
    protected $databaseTool;

    public function setUp(): void
    {
        parent::setUp();

        $this->databaseTool = static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    /**
     * Example using LiipFunctionalBundle the fixture loader.
     */
    public function testUserFooIndex(): void
    {
        // If you need a client, you must create it before loading fixtures because
        // creating the client boots the kernel, which is used by loadFixtures
        $client = static::createClient();
        $this->databaseTool->loadFixtures(['Liip\FooBundle\Tests\Fixtures\LoadUserData']);

        $crawler = $client->request('GET', '/users/foo');
        
        // …
    }

    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/livres');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Les livres de GeekBook');
    }

    
    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}
