<?php

namespace App\Repository\Immo;

use App\Entity\Immo\ImFinancier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImFinancier|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImFinancier|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImFinancier[]    findAll()
 * @method ImFinancier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImFinancierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImFinancier::class);
    }

    // /**
    //  * @return ImFinancier[] Returns an array of ImFinancier objects
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
    public function findOneBySomeField($value): ?ImFinancier
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
