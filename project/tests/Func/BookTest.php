<?php

namespace App\Tests\Func;

use App\DataFixtures\AppFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

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
        self::bootKernel();
        // If you need a client, you must create it before loading fixtures because
        // creating the client boots the kernel, which is used by loadFixtures
        $this->databaseTool->loadFixtures([AppFixtures::class]);
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin?crudAction=index&crudControllerFqcn=App%5CController%5CAdmin%5CUserCrudController');

        // …
    }

    public function testSomething(): void
    {
        self::bootKernel();
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
