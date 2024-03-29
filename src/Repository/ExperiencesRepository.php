<?php

namespace App\Repository;

use App\Entity\Experiences;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Experiences|null find($id, $lockMode = null, $lockVersion = null)
 * @method Experiences|null findOneBy(array $criteria, array $orderBy = null)
 * @method Experiences[]    findAll()
 * @method Experiences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExperiencesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Experiences::class);
    }

    // /**
    //  * @return Experiences[] Returns an array of Experiences objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Experiences
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
