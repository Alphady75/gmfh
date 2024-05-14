<?php

namespace App\Repository;

use App\Entity\SousSecteursActivite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SousSecteursActivite|null find($id, $lockMode = null, $lockVersion = null)
 * @method SousSecteursActivite|null findOneBy(array $criteria, array $orderBy = null)
 * @method SousSecteursActivite[]    findAll()
 * @method SousSecteursActivite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SousSecteursActiviteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SousSecteursActivite::class);
    }

    // /**
    //  * @return SousSecteursActivite[] Returns an array of SousSecteursActivite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SousSecteursActivite
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
