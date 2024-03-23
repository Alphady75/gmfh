<?php

namespace App\Repository;

use App\Entity\Dto\Offre as DtoOffre;
use App\Entity\Dto\Post;
use App\Entity\Offre;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Offre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offre[]    findAll()
 * @method Offre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Offre::class);
        $this->paginator = $paginator;
    }

    /**
     * Get autheur offre filter
     * @return PaginationInterface
     */
    public function auteurFilter(DtoOffre $search, User $user): PaginationInterface
    {
        $query = $this->createQueryBuilder('o')
            ->select('u', 'o')
            ->select('sect', 'o')
            ->leftjoin('o.user', 'u')
            ->leftjoin('o.secteuractivite', 'sect')
            ->andWhere('o.user = :user')
            ->andWhere('o.complet = 1')
            ->setParameter('user', $user)
            ->orderBy('o.created', 'DESC');

        if (!empty($search->getQuery())) {
            $query = $query
                ->andWhere('o.name LIKE :query')
                ->orWhere('sect.name LIKE :sectname')
                ->setParameters([
                    'query' => "%{$search->getQuery()}%",
                    'user' => $user,
                    'sectname' => "%{$search->getQuery()}%",
                ]);
        }

        if (!empty($search->getSecteur())) {
            $query = $query
                ->andWhere('sect.id = :cat')
                ->setParameter('cat', $search->getSecteur());
        }

        if (!empty($search->getLocalisation())) {
            $query = $query
                ->andWhere('o.localisation = :localisation')
                ->setParameter('localisation', $search->getLocalisation());
        }

        if (!empty($search->getStatus())) {
            $query = $query
                ->andWhere('o.status = :status')
                ->setParameter('status', $search->getStatus());
        }

        if (!empty($search->getUrgent())) {
            $query = $query
                ->andWhere('o.urgent = 1');
        }

        if (!empty($search->getMinDate())) {
            $query = $query
                ->where('o.created >= :from')
                ->setParameter('from', $search->getMinDate());
        }

        if (!empty($search->getMaxDate())) {
            $query = $query
                ->andWhere('o.created <= :to')
                ->setParameter('to', $search->getMaxDate());
        }

        return $this->paginator->paginate(
            $query,
            $search->page,
            12
        );
    }

    /**
     * Get visiteur offre filter
     * @return PaginationInterface
     */
    public function visiteurSearch(DtoOffre $search): PaginationInterface
    {
        $query = $this->createQueryBuilder('o')
            ->select('u', 'o')
            ->select('sect', 'o')
            ->leftjoin('o.user', 'u')
            ->leftjoin('o.secteuractivite', 'sect')
            #->andWhere('o.user = :user')
            ->andWhere('o.complet = 1')
            #->setParameter('user', $user)
            ->orderBy('o.created', 'DESC');

        if (!empty($search->getQuery())) {
            $query = $query
                ->andWhere('o.name LIKE :query')
                ->orWhere('sect.name LIKE :sectname')
                ->setParameters([
                    'query' => "%{$search->getQuery()}%",
                    'sectname' => "%{$search->getQuery()}%",
                ]);
        }

        if (!empty($search->getSecteur())) {
            $query = $query
                ->andWhere('sect.id = :cat')
                ->setParameter('cat', $search->getSecteur());
        }

        if (!empty($search->getLocalisation())) {
            $query = $query
                ->andWhere('o.localisation = :localisation')
                ->setParameter('localisation', $search->getLocalisation());
        }

        if (!empty($search->getStatus())) {
            $query = $query
                ->andWhere('o.status = :status')
                ->setParameter('status', $search->getStatus());
        }

        if (!empty($search->getUrgent())) {
            $query = $query
                ->andWhere('o.urgent = 1');
        }

        if (!empty($search->getMinDate())) {
            $query = $query
                ->where('o.created >= :from')
                ->setParameter('from', $search->getMinDate());
        }

        if (!empty($search->getMaxDate())) {
            $query = $query
                ->andWhere('o.created <= :to')
                ->setParameter('to', $search->getMaxDate());
        }

        return $this->paginator->paginate(
            $query,
            $search->page,
            12
        );
    }

    // /**
    //  * @return Offre[] Returns an array of Offre objects
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
    public function findOneBySomeField($value): ?Offre
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
