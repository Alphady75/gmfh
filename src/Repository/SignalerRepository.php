<?php

namespace App\Repository;

use App\Entity\Signaler;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Signaler|null find($id, $lockMode = null, $lockVersion = null)
 * @method Signaler|null findOneBy(array $criteria, array $orderBy = null)
 * @method Signaler[]    findAll()
 * @method Signaler[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SignalerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Signaler::class);
    }

    // /**
    //  * @return Signaler[] Returns an array of Signaler objects
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
    public function findOneBySomeField($value): ?Signaler
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
