<?php

namespace App\Repository;

use App\Entity\Dto\Offre as DtoOffre;
use App\Entity\Offre;
use App\Entity\SecteursActivite;
use App\Entity\User;
use App\Service\AlertService;
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
    public function __construct(
        ManagerRegistry $registry,
        private PaginatorInterface $paginator,
        private AlertService $alertService
    ) {
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
     * Get autheur offre filter
     * @return PaginationInterface
     */
    public function adminFilter(DtoOffre $search): PaginationInterface
    {
        $query = $this->createQueryBuilder('o')
            ->select('u', 'o')
            ->select('sect', 'o')
            ->leftjoin('o.user', 'u')
            ->leftjoin('o.secteuractivite', 'sect')
            ->andWhere('o.complet = 1')
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

    /**
     * Get visiteur offre filter
     * @return PaginationInterface
     */
    public function visiteurSearch(DtoOffre $search): PaginationInterface
    {
        $limit = 12;

        $query = $this->createQueryBuilder('o')
            ->select('u', 'o')
            ->select('sect', 'o')
            ->select('h', 'o')
            ->select('e', 'o')
            ->select('l', 'o')
            ->leftjoin('o.user', 'u')
            ->leftjoin('o.secteuractivite', 'sect')
            ->leftjoin('o.horaires', 'h')
            ->leftjoin('o.experiences', 'e')
            ->leftjoin('o.langues', 'l')
            ->andWhere('u.isVerified = 1')
            ->andWhere('u.annuaire = 1')
            ->andWhere('o.complet = 1')
            ->andWhere('o.status = :status')
            ->andWhere('o.bloquer = 0')
            ->setParameter('status', 'Actif')
            ->orderBy('o.created', 'DESC');

        if (!empty($search->getQuery())) {
            $query = $query
                ->andWhere('o.name LIKE :query')
                ->orWhere('o.intitulePoste LIKE :intitule')
                ->orWhere('sect.name LIKE :sectname')
                ->setParameters([
                    'query' => "%{$search->getQuery()}%",
                    'sectname' => "%{$search->getQuery()}%",
                    'intitule' => "%{$search->getQuery()}%",
                    'status' => "Actif",
                ]);

            # Set in session for alert
            $this->alertService->setQuery($search->getQuery());
        }

        if (!empty($search->getSecteur())) {
            $query = $query
                ->andWhere('sect.id = :cat')
                ->setParameter('cat', $search->getSecteur());

            # Set in session for alert
            $this->alertService->setSecteur($search->getSecteur());
        }

        if (!empty($search->getLocalisation())) {
            $query = $query
                ->andWhere('o.localisation = :localisation')
                ->setParameter('localisation', $search->getLocalisation());

            # Set in session for alert
            $this->alertService->setLocalisation($search->getLocalisation());
        }

        if (!empty($search->getLieuTravail())) {
            $query = $query
                ->andWhere('o.lieuTravail = :lieuTravail')
                ->setParameter('lieuTravail', $search->getLieuTravail());

            # Set in session for alert
            $this->alertService->setLieutravail($search->getLieuTravail());
        }

        if (!empty($search->getTypeContrat())) {
            $query = $query
                ->andWhere('o.typeContrat = :typeContrat')
                ->setParameter('typeContrat', $search->getTypeContrat());

            # Set in session for alert
            $this->alertService->setTypeContrat($search->getTypeContrat());
        }

        if (!empty($search->getPeriodicite())) {
            $query = $query
                ->andWhere('o.periodicite = :periodicite')
                ->setParameter('periodicite', $search->getPeriodicite());

            # Set in session for alert
            $this->alertService->setPeriodicite($search->getPeriodicite());
        }

        if (!empty($search->getMinSalaire())) {
            $query = $query
                ->where('o.salaire >= :from')
                ->setParameter('from', $search->getMinSalaire());

            # Set in session for alert
            $this->alertService->setMinSalaire($search->getMinSalaire());
        }

        if (!empty($search->getMaxSalaire())) {
            $query = $query
                ->andWhere('o.salaire <= :to')
                ->setParameter('to', $search->getMaxSalaire());

            # Set in session for alert
            $this->alertService->setMinSalaire($search->getMaxSalaire());
        }

        if (!empty($search->getLimit())) {
            $limit = $search->getLimit();
        }

        if ($search->getHoraires()->count() > 0) {
            $query = $query
                ->andWhere('h.id IN (:h)')
                ->setParameter('h', $search->getHoraires());
        }

        if ($search->getLangues()->count() > 0) {
            $query = $query
                ->andWhere('l.id IN (:l)')
                ->setParameter('l', $search->getLangues());

            # Set in session for alert
            #$this->alertService->setLangue($search->getLangues());
        }

        if ($search->getExperiences()->count() > 0) {
            $query = $query
                ->andWhere('e.id IN (:e)')
                ->setParameter('e', $search->getExperiences());
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
            $limit
        );
    }

    /**
     * Get visiteur offre filter
     * @return PaginationInterface
     */
    public function visiteurSearchBySecteurActivite(DtoOffre $search, SecteursActivite $secteursActivite): PaginationInterface
    {
        $limit = 12;

        $query = $this->createQueryBuilder('o')
            ->select('u', 'o')
            ->select('sect', 'o')
            ->select('h', 'o')
            ->select('e', 'o')
            ->select('l', 'o')
            ->leftjoin('o.user', 'u')
            ->leftjoin('o.secteuractivite', 'sect')
            ->leftjoin('o.horaires', 'h')
            ->leftjoin('o.experiences', 'e')
            ->leftjoin('o.langues', 'l')
            ->andWhere('o.secteuractivite = :secteur')
            ->andWhere('u.isVerified = 1')
            ->andWhere('o.complet = 1')
            ->andWhere('o.bloquer = 0')
            ->setParameter('secteur', $secteursActivite)
            ->orderBy('o.created', 'DESC');

        if (!empty($search->getQuery())) {
            $query = $query
                ->andWhere('o.name LIKE :query')
                ->orWhere('o.intitulePoste LIKE :intitule')
                ->orWhere('sect.name LIKE :sectname')
                ->setParameters([
                    'query' => "%{$search->getQuery()}%",
                    'sectname' => "%{$search->getQuery()}%",
                    'intitule' => "%{$search->getQuery()}%",
                    'secteur' => $secteursActivite,
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

        if (!empty($search->getLieuTravail())) {
            $query = $query
                ->andWhere('o.lieuTravail = :lieuTravail')
                ->setParameter('lieuTravail', $search->getLieuTravail());
        }

        if (!empty($search->getTypeContrat())) {
            $query = $query
                ->andWhere('o.typeContrat = :typeContrat')
                ->setParameter('typeContrat', $search->getTypeContrat());
        }

        if (!empty($search->getPeriodicite())) {
            $query = $query
                ->andWhere('o.periodicite = :periodicite')
                ->setParameter('periodicite', $search->getPeriodicite());
        }

        if (!empty($search->getLimit())) {
            $limit = $search->getLimit();
        }

        if ($search->getHoraires()->count() > 0) {
            $query = $query
                ->andWhere('h.id IN (:h)')
                ->setParameter('h', $search->getHoraires());
        }

        if ($search->getLangues()->count() > 0) {
            $query = $query
                ->andWhere('l.id IN (:l)')
                ->setParameter('l', $search->getLangues());
        }

        if ($search->getExperiences()->count() > 0) {
            $query = $query
                ->andWhere('e.id IN (:e)')
                ->setParameter('e', $search->getExperiences());
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

        if (!empty($search->getMinSalaire())) {
            $query = $query
                ->where('o.salaire >= :from')
                ->setParameter('from', $search->getMinSalaire());
        }

        if (!empty($search->getMaxSalaire())) {
            $query = $query
                ->andWhere('o.salaire <= :to')
                ->setParameter('to', $search->getMaxSalaire());
        }

        return $this->paginator->paginate(
            $query,
            $search->page,
            $limit
        );
    }

    /**
     * Get Boosted Offre
     *
     * @param integer $limit
     * @return Offre
     */
    public function findBoosted(int $limit)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.boosted = :boosted')
            ->andWhere('o.status = :status')
            ->setParameters([
                'boosted' => 1,
                'status' => 'Actif'
            ])
            ->orderBy('o.created', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
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
