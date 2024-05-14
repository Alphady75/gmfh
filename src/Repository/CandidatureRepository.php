<?php

namespace App\Repository;

use App\Entity\Candidature;
use App\Entity\Dto\Candidature as DtoCandidature;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Candidature|null find($id, $lockMode = null, $lockVersion = null)
 * @method Candidature|null findOneBy(array $criteria, array $orderBy = null)
 * @method Candidature[]    findAll()
 * @method Candidature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidatureRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Candidature::class);
        $this->paginator = $paginator;
    }

    /**
     * @return Candidature[] Returns an array of Candidature objects
     */
    public function findUserCondidats($user, $limit)
    {
        return $this->createQueryBuilder('c')
            ->select('u', 'c')
            ->select('o', 'c')
            ->leftjoin('c.user', 'u')
            ->leftjoin('c.offre', 'o')
            ->orderBy('c.created', 'DESC')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function auteurFilter(DtoCandidature $search, User $user): PaginationInterface
    {
        $limit = 12;

        $query = $this->createQueryBuilder('c')
            ->select('u', 'c')
            ->select('o', 'c')
            ->leftjoin('c.user', 'u')
            ->leftjoin('c.offre', 'o')
            ->orderBy('c.created', 'DESC')
            ->andWhere('o.user = :user')
            ->setParameter('user', $user);

        if (!empty($search->getQuery())) {
            $query = $query
                ->andWhere('u.nom LIKE :query')
                ->orWhere('u.prenom LIKE :prenom')
                ->setParameters([
                    'query' => "%{$search->getQuery()}%",
                    'user' => $user,
                    'prenom' => "%{$search->getQuery()}%",
                ]);
        }

        if (!empty($search->getSecteur())) {
            $query = $query
                ->andWhere('o.secteuractivite = :sec')
                ->setParameter('sec', $search->getSecteur());
        }

        if (!empty($search->getLocalisation())) {
            $query = $query
                ->andWhere('c.localisation = :localisation')
                ->setParameter('localisation', $search->getLocalisation());
        }

        if (!empty($search->getLimit())) {
            $limit = $search->getLimit();
        }

        if (!empty($search->getStatus())) {
            $query = $query
                ->andWhere('c.status = :status')
                ->setParameter('status', $search->getStatus());
        }

        if (!empty($search->getStatus())) {
            $query = $query
                ->andWhere('c.status = :status')
                ->setParameter('status', $search->getStatus());
        }

        if (!empty($search->getMinDate())) {
            $query = $query
                ->where('c.created >= :from')
                ->setParameter('from', $search->getMinDate());
        }

        if (!empty($search->getMaxDate())) {
            $query = $query
                ->andWhere('c.created <= :to')
                ->setParameter('to', $search->getMaxDate());
        }

        return $this->paginator->paginate(
            $query,
            $search->page,
            $limit
        );
    }

    public function aappliquerFilter(DtoCandidature $search, User $user): PaginationInterface
    {
        $limit = 12;

        $query = $this->createQueryBuilder('c')
            ->select('u', 'c')
            ->select('o', 'c')
            ->leftjoin('c.user', 'u')
            ->leftjoin('c.offre', 'o')
            ->orderBy('c.created', 'DESC')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user);

        if (!empty($search->getQuery())) {
            $query = $query
                ->andWhere('u.nom LIKE :query')
                ->orWhere('u.prenom LIKE :prenom')
                ->setParameters([
                    'query' => "%{$search->getQuery()}%",
                    'user' => $user,
                    'prenom' => "%{$search->getQuery()}%",
                ]);
        }

        if (!empty($search->getSecteur())) {
            $query = $query
                ->andWhere('o.secteuractivite = :sec')
                ->setParameter('sec', $search->getSecteur());
        }

        if (!empty($search->getLocalisation())) {
            $query = $query
                ->andWhere('c.localisation = :localisation')
                ->setParameter('localisation', $search->getLocalisation());
        }

        if (!empty($search->getLimit())) {
            $limit = $search->getLimit();
        }

        if (!empty($search->getStatus())) {
            $query = $query
                ->andWhere('c.status = :status')
                ->setParameter('status', $search->getStatus());
        }

        if (!empty($search->getStatus())) {
            $query = $query
                ->andWhere('c.status = :status')
                ->setParameter('status', $search->getStatus());
        }

        if (!empty($search->getMinDate())) {
            $query = $query
                ->where('c.created >= :from')
                ->setParameter('from', $search->getMinDate());
        }

        if (!empty($search->getMaxDate())) {
            $query = $query
                ->andWhere('c.created <= :to')
                ->setParameter('to', $search->getMaxDate());
        }

        return $this->paginator->paginate(
            $query,
            $search->page,
            $limit
        );
    }

    public function adminFilter(DtoCandidature $search): PaginationInterface
    {
        $limit = 12;

        $query = $this->createQueryBuilder('c')
            ->select('u', 'c')
            ->select('o', 'c')
            ->leftjoin('c.user', 'u')
            ->leftjoin('c.offre', 'o')
            ->orderBy('c.created', 'DESC');

        if (!empty($search->getQuery())) {
            $query = $query
                ->andWhere('u.nom LIKE :query')
                ->orWhere('u.prenom LIKE :prenom')
                ->setParameters([
                    'query' => "%{$search->getQuery()}%",
                    'prenom' => "%{$search->getQuery()}%",
                ]);
        }

        if (!empty($search->getSecteur())) {
            $query = $query
                ->andWhere('o.secteuractivite = :sec')
                ->setParameter('sec', $search->getSecteur());
        }

        if (!empty($search->getLocalisation())) {
            $query = $query
                ->andWhere('c.localisation = :localisation')
                ->setParameter('localisation', $search->getLocalisation());
        }

        if (!empty($search->getLimit())) {
            $limit = $search->getLimit();
        }

        if (!empty($search->getStatus())) {
            $query = $query
                ->andWhere('c.status = :status')
                ->setParameter('status', $search->getStatus());
        }

        if (!empty($search->getStatus())) {
            $query = $query
                ->andWhere('c.status = :status')
                ->setParameter('status', $search->getStatus());
        }

        if (!empty($search->getMinDate())) {
            $query = $query
                ->where('c.created >= :from')
                ->setParameter('from', $search->getMinDate());
        }

        if (!empty($search->getMaxDate())) {
            $query = $query
                ->andWhere('c.created <= :to')
                ->setParameter('to', $search->getMaxDate());
        }

        return $this->paginator->paginate(
            $query,
            $search->page,
            $limit
        );
    }

    /*
    public function findOneBySomeField($value): ?Candidature
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
