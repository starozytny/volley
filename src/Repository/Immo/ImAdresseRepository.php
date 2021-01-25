<?php

namespace App\Repository\Immo;

use App\Entity\Immo\ImAdresse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImAdresse|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImAdresse|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImAdresse[]    findAll()
 * @method ImAdresse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImAdresseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImAdresse::class);
    }

    // /**
    //  * @return ImAdresse[] Returns an array of ImAdresse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImAdresse
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
