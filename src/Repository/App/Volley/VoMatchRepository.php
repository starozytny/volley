<?php

namespace App\Repository\App\Volley;

use App\Entity\App\Volley\VoMatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VoMatch|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoMatch|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoMatch[]    findAll()
 * @method VoMatch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoMatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VoMatch::class);
    }

    // /**
    //  * @return VoMatch[] Returns an array of VoMatch objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VoMatch
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
