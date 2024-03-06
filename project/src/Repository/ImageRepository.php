<?php

namespace App\Repository;

use App\Entity\Image;
use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Image>
 *
 * @method Image|null find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null findOneBy(array $criteria, array $orderBy = null)
 * @method Image[]    findAll()
 * @method Image[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }

    public function save(Image $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Image $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Image[] Returns an array of Image objects
    //     */
    //    public function findByFirstImage($value): ?array
    //    {
    //         dd($this->createQueryBuilder('i', 'b')
    //              ->select('b.id as bookName')
    //             ->join('i.id', 'b')
    //             ->groupBy('i')
    //             // ->andWhere('i.id = :val')
    //            // ->andWhere('b.id = i.id')
    //             // ->setParameter('val', $value)
    //             // ->setParameter('val2', $value2)
    //              ->orderBy('i.id', 'ASC')
    //              ->setMaxResults(1)
    //              ->getQuery()
    //              //->getOneOrNullResult()
    //              ->getSQL()
    //          )
    //         ;
    //    }

    //    public function findOneBySomeField($value): ?Image
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
