<?php

namespace App\Repository;

use App\Entity\Alert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Alert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Alert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Alert[]    findAll()
 * @method Alert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlertRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alert::class);
    }
    
    /**
     * Get emploi alerte
     *
     * @param integer $secteurId
     * @param string $query
     * @return Alert[]
     */
    public function findBySecteur(int $secteurId, string $query)
    {
        return $this->createQueryBuilder('a')
            ->select('u', 'a')
            ->select('s', 'a')
            ->leftjoin('a.user', 'u')
            ->leftjoin('a.secteurs', 's')
            ->orWhere('s.name LIKE :query')
            ->orWhere('s.id = :sectId')
            ->setParameters([
                'query' => "%{$query}%",
                'sectId' => $secteurId,
            ])
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Get emploi alerte
     *
     * @param integer $secteurId
     * @return Alert[]
     */
    public function findBySecteurId(int $secteurId)
    {
        return $this->createQueryBuilder('a')
            ->select('u', 'a')
            ->select('s', 'a')
            ->leftjoin('a.user', 'u')
            ->leftjoin('a.secteurs', 's')
            ->orWhere('s.id = :sectId')
            ->setParameter( 'sectId', $secteurId)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /*
    public function findOneBySomeField($value): ?Alert
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
