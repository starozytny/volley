<?php

namespace App\Repository\Blog;

use App\Entity\Blog\BoAuthor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BoAuthor|null find($id, $lockMode = null, $lockVersion = null)
 * @method BoAuthor|null findOneBy(array $criteria, array $orderBy = null)
 * @method BoAuthor[]    findAll()
 * @method BoAuthor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoAuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BoAuthor::class);
    }

    // /**
    //  * @return BoAuthor[] Returns an array of BoAuthor objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BoAuthor
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
