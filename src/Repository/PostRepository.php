<?php

namespace App\Repository;

use App\Entity\Dto\Post as DtoPost;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Post::class);
        $this->paginator = $paginator;
    }


    /**
     * Recupère les annonces en lien avec une recherche
     * @return PaginationInterface
     */
    public function auteurFilter(DtoPost $search, User $user): PaginationInterface
    {
        $query = $this->createQueryBuilder('p')
            ->select('u', 'p')
            ->select('scat', 'p')
            #->select('v', 'p')
            ->leftjoin('p.user', 'u')
            ->leftjoin('p.souscategorie', 'scat')
            #->leftjoin('p.ville', 'v')
            ->andWhere('p.user = :user')
            ->setParameter('user', $user)
            ->orderBy('p.created', 'DESC');

        if (!empty($search->getQuery())) {
            $query = $query
                ->andWhere('p.name LIKE :query')
                ->orWhere('scat.name LIKE :scatname')
                #->orWhere('v.name LIKE :vilname')
                ->setParameters([
                    'query' => "%{$search->getQuery()}%",
                    'user' => $user,
                    'scatname' => "%{$search->getQuery()}%",
                    #'vilname' => "%{$search->getQuery()}%",
                ]);
        }

        if (!empty($search->getMinPrice())) {
            $query = $query
                ->andWhere('p.tarif >= :minPrice')
                ->setParameter('minPrice', $search->getMinPrice());
        }

        if (!empty($search->getMaxPrice())) {
            $query = $query
                ->andWhere('p.tarif <= :maxPrice')
                ->setParameter('maxPrice', $search->getMaxPrice());
        }

        if (!empty($search->getSouscategorie())) {
            $query = $query
                ->andWhere('scat.id = :cat')
                ->setParameter('cat', $search->getSouscategorie());
        }

        if (!empty($search->getVille())) {
            $query = $query
                ->andWhere('v.id = :vil')
                ->setParameter('vil', $search->getVille());
        }

        if (!empty($search->getOnline())) {
            $query = $query
                ->andWhere('p.online = :online')
                ->setParameter('online', $search->getOnline());
        }

        if (!empty($search->getEtat())) {
            $query = $query
                ->andWhere('p.etat = :etat')
                ->setParameter('etat', $search->getEtat());
        }

        if (!empty($search->getPromo())) {
            $query = $query
                ->andWhere('p.promo = 1');
        }

        if (!empty($search->getlivraison())) {
            $query = $query
                ->andWhere('p.livraison = 1');
        }

        if (!empty($search->getIsSelled())) {
            $query = $query
                ->andWhere('p.isSelled = 1');
        }

        if (!empty($search->getVedette())) {
            $query = $query
                ->andWhere('p.vedette = 1');
        }

        if (!empty($search->getUrgent())) {
            $query = $query
                ->andWhere('p.urgent = 1');
        }

        if (!empty($search->getNegociable())) {
            $query = $query
                ->andWhere('p.negociable = 1');
        }

        if (!empty($search->getMinDate())) {
            $query = $query
                ->where('p.created >= :from')
                ->setParameter('from', $search->getMinDate());
        }

        if (!empty($search->getMaxDate())) {
            $query = $query
                ->andWhere('p.created <= :to')
                ->setParameter('to', $search->getMaxDate());
        }

        return $this->paginator->paginate(
            $query,
            $search->page,
            12
        );
    }


    /**
     * Recupère les annonces en lien avec une recherche
     * @return PaginationInterface
     */
    public function adminFilter(DtoPost $search): PaginationInterface
    {
        $query = $this->createQueryBuilder('p')
            ->select('u', 'p')
            ->select('scat', 'p')
            #->select('v', 'p')
            ->leftjoin('p.souscategorie', 'scat')
            #->leftjoin('p.ville', 'v')
            ->orderBy('p.created', 'DESC');

        if (!empty($search->getQuery())) {
            $query = $query
                ->andWhere('p.name LIKE :query')
                ->orWhere('scat.name LIKE :scatname')
                #->orWhere('v.name LIKE :vilname')
                ->setParameters([
                    'query' => "%{$search->getQuery()}%",
                    'scatname' => "%{$search->getQuery()}%",
                    #'vilname' => "%{$search->getQuery()}%",
                ]);
        }

        if (!empty($search->getMinPrice())) {
            $query = $query
                ->andWhere('p.tarif >= :minPrice')
                ->setParameter('minPrice', $search->getMinPrice());
        }

        if (!empty($search->getMaxPrice())) {
            $query = $query
                ->andWhere('p.tarif <= :maxPrice')
                ->setParameter('maxPrice', $search->getMaxPrice());
        }

        if (!empty($search->getSouscategorie())) {
            $query = $query
                ->andWhere('scat.id = :cat')
                ->setParameter('cat', $search->getSouscategorie());
        }

        if (!empty($search->getVille())) {
            $query = $query
                ->andWhere('v.id = :vil')
                ->setParameter('vil', $search->getVille());
        }

        if (!empty($search->getOnline())) {
            $query = $query
                ->andWhere('p.online = :online')
                ->setParameter('online', $search->getOnline());
        }

        if (!empty($search->getEtat())) {
            $query = $query
                ->andWhere('p.etat = :etat')
                ->setParameter('etat', $search->getEtat());
        }

        if (!empty($search->getPromo())) {
            $query = $query
                ->andWhere('p.promo = 1');
        }

        if (!empty($search->getlivraison())) {
            $query = $query
                ->andWhere('p.livraison = 1');
        }

        if (!empty($search->getIsSelled())) {
            $query = $query
                ->andWhere('p.isSelled = 1');
        }

        if (!empty($search->getVedette())) {
            $query = $query
                ->andWhere('p.vedette = 1');
        }

        if (!empty($search->getUrgent())) {
            $query = $query
                ->andWhere('p.urgent = 1');
        }

        if (!empty($search->getNegociable())) {
            $query = $query
                ->andWhere('p.negociable = 1');
        }

        if (!empty($search->getMinDate())) {
            $query = $query
                ->where('p.created >= :from')
                ->setParameter('from', $search->getMinDate());
        }

        if (!empty($search->getMaxDate())) {
            $query = $query
                ->andWhere('p.created <= :to')
                ->setParameter('to', $search->getMaxDate());
        }

        return $this->paginator->paginate(
            $query,
            $search->page,
            12
        );
    }


    /**
     * Recupère les annonces en lien avec une recherche
     * @return PaginationInterface
     */
    public function visiteurFilter(DtoPost $search): PaginationInterface
    {
        $limit = 12;

        $query = $this->createQueryBuilder('p')
            ->select('u', 'p')
            ->select('cat', 'p')
            ->leftjoin('p.user', 'u')
            ->leftjoin('p.categorie', 'cat')
            ->andWhere('p.online = 1')
            ->andWhere('p.bloquer = 0')
            ->orderBy('p.created', 'DESC');

        if (!empty($search->getQuery())) {
            $query = $query
                ->andWhere('p.name LIKE :query')
                ->orWhere('cat.name LIKE :catname')
                ->setParameters([
                    'query' => "%{$search->getQuery()}%",
                    'catname' => "%{$search->getQuery()}%",
                ]);
        }

        if (!empty($search->getLimit())) {
            $limit = $search->getLimit();
        }

        if (!empty($search->getMinPrice())) {
            $query = $query
                ->andWhere('p.tarif >= :minPrice')
                ->setParameter('minPrice', $search->getMinPrice());
        }

        if (!empty($search->getMaxPrice())) {
            $query = $query
                ->andWhere('p.tarif <= :maxPrice')
                ->setParameter('maxPrice', $search->getMaxPrice());
        }

        if (!empty($search->getCategorie())) {
            $query = $query
                ->andWhere('cat.id = :cat')
                ->setParameter('cat', $search->getCategorie());
        }

        if (!empty($search->getEtat())) {
            $query = $query
                ->andWhere('p.etat = :etat')
                ->setParameter('etat', $search->getEtat());
        }

        if (!empty($search->getPromo())) {
            $query = $query
                ->andWhere('p.promo = 1');
        }

        if (!empty($search->getlivraison())) {
            $query = $query
                ->andWhere('p.livraison = 1');
        }

        if (!empty($search->getIsSelled())) {
            $query = $query
                ->andWhere('p.isSelled = 1');
        }

        if (!empty($search->getVedette())) {
            $query = $query
                ->andWhere('p.vedette = 1');
        }

        if (!empty($search->getUrgent())) {
            $query = $query
                ->andWhere('p.urgent = 1');
        }

        if (!empty($search->getNegociable())) {
            $query = $query
                ->andWhere('p.negociable = 1');
        }

        if (!empty($search->getMinDate())) {
            $query = $query
                ->where('p.created >= :from')
                ->setParameter('from', $search->getMinDate());
        }

        if (!empty($search->getMaxDate())) {
            $query = $query
                ->andWhere('p.created <= :to')
                ->setParameter('to', $search->getMaxDate());
        }

        return $this->paginator->paginate(
            $query,
            $search->page,
            $limit
        );
    }

    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
