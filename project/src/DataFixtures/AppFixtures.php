<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Carrier;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Order;
use App\Entity\OrderDetail;
use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    private SluggerInterface $slugger;

    public function __construct(UserPasswordHasherInterface $hasher, SluggerInterface $slugger)
    {
        $this->hasher = $hasher;
        $this->slugger = $slugger;
    }
    
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $admin = new User();
        $admin->setFirstName('kirua')
            ->setLastName('zoldyk')
            ->setAvatar($faker->imageUrl())
            ->setEmail('admin@geekbook.com')
            ->setIsVerified(true)
            ->setRoles(['ROLE_ADMIN'])
            ->setAgreeTerms(true)
        ;

        $password = $this->hasher->hashPassword($admin, 'password');
        $admin->setPassword($password);

        $manager->persist($admin);
        
        $users = [];
        for ($us=0; $us < 15; $us++) { 
            $user = new User();
            $user->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEmail($faker->email())
                ->setAvatar($faker->imageUrl())
                ->setIsVerified($faker->randomElement())
                ->setAgreeTerms(true)
                ->setRoles(['ROLE_USER'])
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
            ;
            $password = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($password);
            $manager->persist($user);
            $users[] = $user;
        }

        $books = [];
        for ($bo=0; $bo < 50; $bo++) { 
            $book = new Book();
            $book->setTitle($title = $faker->name())
                ->setIntroduction($faker->sentence())
                ->setDescription($faker->paragraph(3))
                ->setPrice($faker->randomNumber())
                ->setLangue('fr')
                ->setNbPages($faker->randomNumber())
                ->setDimension($faker->randomNumber())
                ->setIsbn($faker->isbn10())
                ->setEditor($faker->company())
                ->setIsInStock($faker->boolean())
                ->setSlug($this->slugger->slug(mb_strtolower($title)))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setPublishedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
            ;
            $manager->persist($book);
            $books[] = $book;
        }

        for ($au=0; $au < 10; $au++) { 
            $author = new Author();
            $author->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setDescription($faker->paragraph(2))
            ;
            $manager->persist($author);
        }

        for ($cat=0; $cat < 5; $cat++) { 
            $category = new Category();
            $category->setName($faker->name())
                ->setImage($faker->imageUrl())
            ;
            $manager->persist($category);
        }

        for ($addr=0; $addr < 10; $addr++) { 
            $address = new Address();
            $address->setName($faker->name())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setCompany($faker->company())
                ->setAddress($faker->streetAddress())
                ->setZip($faker->countryCode())
                ->setCity($faker->city())
                ->setCountry($faker->country())
                ->setPhone($faker->phoneNumber())
                ->setOwner($user)
            ;
            $manager->persist($address);
        }

        for ($car=0; $car < 4; $car++) { 
            $carrier = new Carrier();
            $carrier->setName($faker->name())
                ->setDescription($faker->paragraph(2))
                ->setPrice($faker->numberBetween(0, 25))
            ;
            $manager->persist($carrier);
        }

        for ($com=0; $com < 20; $com++) { 
            $comment = new Comment();
            $comment->setTitle($faker->title())
                    ->setComment($faker->paragraph(3))
                    ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                    ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                    ->setUserComment($users[\mt_rand(0, count($users) - 1)])
                    ->setBookComment($books[\mt_rand(0, count($books) - 1)])
            ;
            $manager->persist($comment);
        }

        for ($im=0; $im < 100; $im++) { 
            $image = new Image();
            $image->setName($faker->name())
                ->setUrl($faker->imageUrl())
                ->setBook($books[\mt_rand(0, count($books) - 1)])
            ;
            $manager->persist($image);
        }

        for ($or=0; $or < 100; $or++) { 
            $order = new Order();
            $order->setCarrierName($faker->name())
                ->setCarrierPrice($faker->numberBetween(0, 25))
                ->setDelivery($faker->paragraph(5))
                ->setIsPaid($faker->boolean(\mt_rand(0, 1)))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setReference($faker->randomNumber())
                ->setStripeSessionId($faker->randomNumber())
            ;
            $manager->persist($order);
        }
        
        for ($ordd=0; $ordd < 100; $ordd++) { 
            $orderDetail = new OrderDetail();
            $orderDetail->setQuantity($faker->numberBetween(1, 5))
                ->setPrice($faker->numberBetween(0, 100))
                ->setTotal($faker->numberBetween(0, 100))
                ->setBook($books[mt_rand(0, count($books) - 1)])
                ->addProduct($order)
            ;
            $manager->persist($orderDetail);
        }

        $manager->flush();
    }
}
