<?php

namespace App\Repository;

use App\Entity\Dto\User as DtoUser;
use App\Entity\User;
use App\Entity\Ville;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Get visiteur offre filter
     * @return PaginationInterface
     */
    public function visiteurSearch(DtoUser $search): PaginationInterface
    {
        $limit = 12;

        $query = $this->createQueryBuilder('u')
            ->select('c', 'u')
            ->select('v', 'u')
            ->select('sect', 'u')
            ->select('s', 'u')
            ->select('l', 'u')
            ->leftjoin('u.competences', 'c')
            ->leftjoin('u.villes', 'v')
            ->leftjoin('u.secteuractivite', 'sect')
            ->leftjoin('u.services', 's')
            ->leftjoin('u.langues', 'l')
            ->andWhere('u.isVerified = 1')
            ->andWhere('u.annuaire = 1')
            ->andWhere('u.completed = 1')
            ->orderBy('u.created', 'DESC');

        if (!empty($search->getQuery())) {
            $query = $query
                ->andWhere('u.nom LIKE :query')
                ->orWhere('u.prenom LIKE :prenom')
                ->orWhere('sect.name LIKE :sectname')
                ->orWhere('u.societe LIKE :societe')
                ->setParameters([
                    'query' => "%{$search->getQuery()}%",
                    'sectname' => "%{$search->getQuery()}%",
                    'prenom' => "%{$search->getQuery()}%",
                    'societe' => "%{$search->getQuery()}%",
                ]);
        }

        if (!empty($search->getSecteur())) {
            $query = $query
                ->andWhere('sect.id = :cat')
                ->setParameter('cat', $search->getSecteur());
        }

        if (!empty($search->getLocalisation())) {
            $query = $query
                ->andWhere('u.localisation = :localisation')
                ->setParameter('localisation', $search->getLocalisation());
        }

        if (!empty($search->getPeriodicite())) {
            $query = $query
                ->andWhere('u.periodicite = :periodicite')
                ->setParameter('periodicite', $search->getPeriodicite());
        }

        if (!empty($search->getLimit())) {
            $limit = $search->getLimit();
        }

        if ($search->getLangues()->count() > 0) {
            $query = $query
                ->andWhere('l.id IN (:l)')
                ->setParameter('l', $search->getLangues());
        }

        if ($search->getVilles()->count() > 0) {
            $query = $query
                ->andWhere('v.id IN (:v)')
                ->setParameter('v', $search->getVilles());
        }

        if ($search->getServices()->count() > 0) {
            $query = $query
                ->andWhere('s.id IN (:s)')
                ->setParameter('s', $search->getServices());
        }

        if (!empty($search->getMinDate())) {
            $query = $query
                ->where('u.created >= :from')
                ->setParameter('from', $search->getMinDate());
        }

        if (!empty($search->getMaxDate())) {
            $query = $query
                ->andWhere('u.created <= :to')
                ->setParameter('to', $search->getMaxDate());
        }

        if (!empty($search->getMinSalaire())) {
            $query = $query
                ->where('u.salaire >= :from')
                ->setParameter('from', $search->getMinSalaire());
        }

        if (!empty($search->getMaxSalaire())) {
            $query = $query
                ->andWhere('u.salaire <= :to')
                ->setParameter('to', $search->getMaxSalaire());
        }

        return $this->paginator->paginate(
            $query,
            $search->page,
            $limit
        );
    }

    /**
     * Get visiteur offre filter by Location
     * @param DtoUser $search
     * @param Ville $ville
     * @return PaginationInterface
     */
    public function visiteurSearchByLocation(DtoUser $search, Ville $ville): PaginationInterface
    {
        $limit = 12;

        $query = $this->createQueryBuilder('u')
            ->select('c', 'u')
            ->select('v', 'u')
            ->select('sect', 'u')
            ->select('s', 'u')
            ->select('l', 'u')
            ->leftjoin('u.competences', 'c')
            ->leftjoin('u.villes', 'v')
            ->leftjoin('u.secteuractivite', 'sect')
            ->leftjoin('u.services', 's')
            ->leftjoin('u.langues', 'l')
            ->andWhere('u.isVerified = 1')
            ->andWhere('u.annuaire = 1')
            ->andWhere('u.completed = 1')
            ->andWhere('v.id = :id')
            ->setParameter('id', $ville->getId())
            ->orderBy('u.created', 'DESC');

        if (!empty($search->getQuery())) {
            $query = $query
                ->andWhere('u.nom LIKE :query')
                ->orWhere('u.prenom LIKE :prenom')
                ->orWhere('sect.name LIKE :sectname')
                ->orWhere('u.societe LIKE :societe')
                ->andWhere('v.id = :id')
                ->setParameters([
                    'query' => "%{$search->getQuery()}%",
                    'sectname' => "%{$search->getQuery()}%",
                    'prenom' => "%{$search->getQuery()}%",
                    'societe' => "%{$search->getQuery()}%",
                    'id' => $ville->getId(),
                ]);
        }

        if (!empty($search->getSecteur())) {
            $query = $query
                ->andWhere('sect.id = :cat')
                ->setParameter('cat', $search->getSecteur());
        }

        if (!empty($search->getLocalisation())) {
            $query = $query
                ->andWhere('u.localisation = :localisation')
                ->setParameter('localisation', $search->getLocalisation());
        }

        if (!empty($search->getPeriodicite())) {
            $query = $query
                ->andWhere('u.periodicite = :periodicite')
                ->setParameter('periodicite', $search->getPeriodicite());
        }

        if (!empty($search->getLimit())) {
            $limit = $search->getLimit();
        }

        if ($search->getLangues()->count() > 0) {
            $query = $query
                ->andWhere('l.id IN (:l)')
                ->setParameter('l', $search->getLangues());
        }

        if ($search->getVilles()->count() > 0) {
            $query = $query
                ->andWhere('v.id IN (:v)')
                ->setParameter('v', $search->getVilles());
        }

        if ($search->getServices()->count() > 0) {
            $query = $query
                ->andWhere('s.id IN (:s)')
                ->setParameter('s', $search->getServices());
        }

        if (!empty($search->getMinDate())) {
            $query = $query
                ->where('u.created >= :from')
                ->setParameter('from', $search->getMinDate());
        }

        if (!empty($search->getMaxDate())) {
            $query = $query
                ->andWhere('u.created <= :to')
                ->setParameter('to', $search->getMaxDate());
        }

        if (!empty($search->getMinSalaire())) {
            $query = $query
                ->where('u.salaire >= :from')
                ->setParameter('from', $search->getMinSalaire());
        }

        if (!empty($search->getMaxSalaire())) {
            $query = $query
                ->andWhere('u.salaire <= :to')
                ->setParameter('to', $search->getMaxSalaire());
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
    public function adminSearch(DtoUser $search): PaginationInterface
    {
        $limit = 25;

        $query = $this->createQueryBuilder('u')
            ->select('c', 'u')
            ->select('v', 'u')
            ->select('sect', 'u')
            ->select('s', 'u')
            ->select('l', 'u')
            ->leftjoin('u.competences', 'c')
            ->leftjoin('u.villes', 'v')
            ->leftjoin('u.secteuractivite', 'sect')
            ->leftjoin('u.services', 's')
            ->leftjoin('u.langues', 'l')
            ->orderBy('u.created', 'DESC');

        if (!empty($search->getQuery())) {
            $query = $query
                ->andWhere('u.nom LIKE :query')
                ->orWhere('u.prenom LIKE :prenom')
                ->orWhere('sect.name LIKE :sectname')
                ->orWhere('u.societe LIKE :societe')
                ->setParameters([
                    'query' => "%{$search->getQuery()}%",
                    'sectname' => "%{$search->getQuery()}%",
                    'prenom' => "%{$search->getQuery()}%",
                    'societe' => "%{$search->getQuery()}%",
                ]);
        }

        if (!empty($search->getSecteur())) {
            $query = $query
                ->andWhere('sect.id = :cat')
                ->setParameter('cat', $search->getSecteur());
        }

        if (!empty($search->getLocalisation())) {
            $query = $query
                ->andWhere('u.localisation = :localisation')
                ->setParameter('localisation', $search->getLocalisation());
        }

        if (!empty($search->getIsVerified())) {
            $query = $query
                ->andWhere('u.isVerified = :isVerified')
                ->setParameter('isVerified', $search->getIsVerified());
        }

        if (!empty($search->getPeriodicite())) {
            $query = $query
                ->andWhere('u.periodicite = :periodicite')
                ->setParameter('periodicite', $search->getPeriodicite());
        }

        if (!empty($search->getCompte())) {
            $query = $query
                ->andWhere('u.compte = :compte')
                ->setParameter('compte', $search->getCompte());
        }

        if (!empty($search->getLimit())) {
            $limit = $search->getLimit();
        }

        if ($search->getLangues()->count() > 0) {
            $query = $query
                ->andWhere('l.id IN (:l)')
                ->setParameter('l', $search->getLangues());
        }

        if ($search->getVilles()->count() > 0) {
            $query = $query
                ->andWhere('v.id IN (:v)')
                ->setParameter('v', $search->getVilles());
        }

        if ($search->getServices()->count() > 0) {
            $query = $query
                ->andWhere('s.id IN (:s)')
                ->setParameter('s', $search->getServices());
        }

        if (!empty($search->getMinDate())) {
            $query = $query
                ->where('u.created >= :from')
                ->setParameter('from', $search->getMinDate());
        }

        if (!empty($search->getMaxDate())) {
            $query = $query
                ->andWhere('u.created <= :to')
                ->setParameter('to', $search->getMaxDate());
        }

        if (!empty($search->getMinSalaire())) {
            $query = $query
                ->where('u.salaire >= :from')
                ->setParameter('from', $search->getMinSalaire());
        }

        if (!empty($search->getMaxSalaire())) {
            $query = $query
                ->andWhere('u.salaire <= :to')
                ->setParameter('to', $search->getMaxSalaire());
        }

        return $this->paginator->paginate(
            $query,
            $search->page,
            $limit
        );
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
