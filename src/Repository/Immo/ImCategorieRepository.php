<?php

namespace App\Repository\Immo;

use App\Entity\Immo\ImCategorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImCategorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImCategorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImCategorie[]    findAll()
 * @method ImCategorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImCategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImCategorie::class);
    }

    // /**
    //  * @return ImCategorie[] Returns an array of ImCategorie objects
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
    public function findOneBySomeField($value): ?ImCategorie
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
