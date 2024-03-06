<?php

namespace App\Tests\Unit;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BooksTest extends KernelTestCase
{
    public function getEntity(): Book
    {
        return (new Book())
            ->setTitle('Mon titre')
            ->setPrice(12)
            ->setDescription('Ma description')

        ;
    }

    public function testEntityIsValid(): void
    {

        $entity = $this->getEntity();

        $kernel = self::bootKernel();

        $error = $this->getContainer()->get('validator')->validate($entity);

        $this->assertCount(0, $error);


        $this->assertSame('test', $kernel->getEnvironment());
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }
}
