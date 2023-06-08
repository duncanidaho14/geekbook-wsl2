<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Book;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Order;
use App\Entity\Author;
use App\Entity\Address;
use App\Entity\Carrier;
use App\Entity\Comment;
use App\Entity\Category;
use App\Entity\OrderDetails;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
            ->setBirthday(new \DateTime("26-04-1999 04:44"))
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
                ->setBirthday($faker->dateTime())
            ;
            $password = $this->hasher->hashPassword($user, 'password');
            $user->setPassword($password);
            $manager->persist($user);
            $users[] = $user;
        }

        

        for ($addr=0; $addr < 20; $addr++) { 
            $address = new Address();
            $address->setName($faker->name())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setCompany($faker->company())
                ->setAddress($faker->streetAddress())
                ->setZip($faker->postcode())
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
                ->setDescription($faker->sentence())
                ->setPrice($faker->numberBetween(0, 25))
            ;
            $manager->persist($carrier);
        }

        

        

        $books = [];
        for ($bo=0; $bo < 50; $bo++) { 
            $book = new Book();
            $book->setTitle($title = $faker->sentence())
                ->setIntroduction($faker->sentence())
                ->setDescription($faker->paragraph(3))
                ->setPrice($faker->randomFloat(2, 1, 100))
                ->setLangue('fr')
                ->setNbPages($faker->numberBetween(10, 1200))
                ->setDimension('15x21')
                ->setIsbn($faker->isbn10())
                ->setEditor($faker->company())
                ->setIsInStock($faker->boolean(true))
                ->setSlug($this->slugger->slug(mb_strtolower($title)))
                ->setRating($faker->numberBetween(1,5))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setPublishedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setFirstCover($faker->imageUrl())
                
            ;
        
            $categories = [];
            for ($cat=0; $cat < 5; $cat++) { 
                $category = new Category();
                $category->setName($faker->name())
                    ->setImage($faker->imageUrl())
                    ->addBook($book)
                ;
                $manager->persist($category);
                $categories[] = $category;
            }

            $images = [];
            for ($im=0; $im < 4; $im++) { 
                $image = new Image();
                $image->setName($faker->name())
                    ->setUrl($faker->imageUrl())
                    ->setBook($book)
                ;
                $manager->persist($image);
                $images[] = $image;
            }
            
            $comments = [];
            for ($com=0; $com < 20; $com++) { 
                $comment = new Comment();
                $comment->setTitle($faker->sentence())
                        ->setComment($faker->paragraph(3))
                        ->setRating($faker->numberBetween(1, 5))
                        ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                        ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                        ->setUserComment($user)
                        ->setBookComment($book)
                ;
                $manager->persist($comment);
                $comments[] = $comment;
            }
            $orders = [];
            
            for ($or=0; $or < 10; $or++) { 
                $order = new Order();
                $order->setReference($faker->randomNumber())
                    ->setFullName($faker->name())
                    ->setCarrierName($faker->name())
                    ->setCarrierPrice($faker->numberBetween(0, 25))
                    ->setDeliveryAddress($faker->address())
                    ->setPrice($price = $faker->numberBetween(1, 75))
                    ->setUnitPrice($faker->numberBetween(1, 75)< $price)
                    ->setIsPaid($faker->boolean(\mt_rand(0, 1)))
                    ->setMoreInformation($faker->sentence())
                    ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                    ->setUsers($users[mt_rand(0, count($users) - 1 )])
                    ->setQuantity($quantity = $faker->numberBetween(1, 5))
                    ->setSubTotalHT($subTotalHT = $faker->numberBetween(0, 100))
                    ->setTaxe($taxe = $faker->numberBetween(0, 100))
                    ->setSubTotalTTC($subTotalTTC = $faker->numberBetween(0, 100))
                ;
                    
                    $orderDetails = [];
                    for ($ordd=0; $ordd < 10; $ordd++) { 
                        $orderDetail = new OrderDetails();
                        $orderDetail->setQuantity($quantity)
                            ->setProductPrice($faker->numberBetween(0, 100))
                            ->setSubTotalHT($subTotalHT)
                            ->setProductName($faker->name())
                            ->setSubTotalTTC($subTotalTTC)
                            ->setTaxe($taxe)
                            ->setOrders($order)
                            ->setCarrierName($order->getFullName())
                            ->setCarrierPrice($order->getCarrierPrice())
                        ;
                        
                        $manager->persist($orderDetail);
                        $orderDetails[] = $orderDetail;
                    }

                    

                $order->addOrderDetail($orderDetail);
                
                
                $manager->persist($order);
                $orders[] = $order;
            }


            $authors = [];
            for ($au=0; $au < 2; $au++) { 
                $author = new Author();
                $author->setFirstName($faker->firstName())
                    ->setLastName($faker->lastName())
                    ->setDescription($faker->paragraph(2))
                    ->setAvatar($faker->imageUrl())
                    ->addBook($book)
                ;
                $manager->persist($author);
                $authors[] = $author;
            }

           
            
            $book->setCommand($order);
            $book->addAuthor($author)
                    ->addCategory($category)
                    ->addComment($comment)
                    ->addImage($image);

            $manager->persist($book);

            $manager->persist($book);
            $books[] = $book;
        }
        
        $manager->flush();
    }
}
