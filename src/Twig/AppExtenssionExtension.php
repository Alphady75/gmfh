<?php

namespace App\Twig;

use App\Entity\User;
use App\Repository\AlertRepository;
use App\Repository\ArticleCategorieRepository;
use App\Repository\ArticleRepository;
use App\Repository\AvisRepository;
use App\Repository\ConversationRepository;
use App\Repository\FavorisRepository;
use App\Repository\HorairesRepository;
use App\Repository\OffreRepository;
use App\Repository\PostRepository;
use App\Repository\SecteursActiviteRepository;
use App\Repository\SignalerRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtenssionExtension extends AbstractExtension
{
    public function __construct(
        private OffreRepository $offreRepository,
        private SecteursActiviteRepository $secteursActivite,
        private HorairesRepository $horairesRepository,
        private ArticleCategorieRepository $articleCategorieRepository,
        private TagRepository $tagRepository,
        private ConversationRepository $conversationRepository,
        private FavorisRepository $favorisRepository,
        private ArticleRepository $articleRepository,
        private UserRepository $userRepository,
        private AvisRepository $avisRepository,
        private SignalerRepository $signalerRepository,
        private AlertRepository $alertRepository,
        private VilleRepository $villeRepository,
        private PostRepository $postRepository,
    ) {
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('useroffres', [$this, 'getUserOffre']),
            new TwigFunction('secteurs', [$this, 'getSecteurs']),
            new TwigFunction('offrehoraire', [$this, 'getOffreOneHoraire']),
            new TwigFunction('articlecategories', [$this, 'getArticleCategories']),
            new TwigFunction('tags', [$this, 'getArticleTags']),
            new TwigFunction('messageNonLu', [$this, 'getMessageNonLu']),
            new TwigFunction('userfavoris', [$this, 'getUserFavoris']),
            new TwigFunction('lastarticles', [$this, 'getLastArticle']),
            new TwigFunction('bestsocietes', [$this, 'getBestSociete']),
            new TwigFunction('checkuseravis', [$this, 'checkIfPostedAvis']),
            new TwigFunction('entreprises', [$this, 'getEntreprise']),
            new TwigFunction('signalers', [$this, 'getSignaler']),
            new TwigFunction('lastsignalers', [$this, 'getLastSignaler']),
            new TwigFunction('alerts', [$this, 'getUserLastedAlerts']),
            new TwigFunction('villes', [$this, 'getVilles']),
            new TwigFunction('nbUsers', [$this, 'countUsers']),
            new TwigFunction('nbPosts', [$this, 'countPosts']),
            new TwigFunction('nbOffres', [$this, 'countOffres']),
        ];
    }

    /**
     * Get User offre
     *
     * @param  $user
     * @param integer $limit
     * @return array
     */
    public function getUserOffre($user, int $limit)
    {
        return $this->offreRepository->findBy(['user' => $user], ['created' => 'DESC'], $limit);
    }

    public function getSecteurs(int $limit)
    {
        return $this->secteursActivite->findBy([], ['created' => 'DESC'], $limit);
    }

    /**
     * Get Offre One Horaire
     *
     * @param $offre
     * @return array
     */
    public function getOffreOneHoraire($offre)
    {
        return $this->horairesRepository->findOneBy(['offre' => $offre]);
    }

    /**
     * Get all article catégories
     *
     * @return array
     */
    public function getArticleCategories()
    {
        return $this->articleCategorieRepository->findBy([], ['name' => 'ASC']);
    }

    /**
     * Get all article tags
     *
     * @return array
     */
    public function getArticleTags()
    {
        return $this->tagRepository->findBy([], ['name' => 'ASC']);
    }

    /**
     * Get User Message Not Read
     *
     * @param $user
     * @return User
     */
    public function getMessageNonLu($user)
    {
        return $this->conversationRepository->findByParticipationNonLu($user);
    }

    /**
     * Get User offre
     *
     * @param $user
     * @return Favoris
     */
    public function getUserFavoris($user)
    {
        return $this->favorisRepository->findBy(['user' => $user]);
    }

    /**
     * Get Last Article
     *
     * @param integer $limit
     * @return Article
     */
    public function getLastArticle(int $limit)
    {
        return $this->articleRepository->findBy(['online' => true], ['created' => 'DESC'], $limit);
    }

    /**
     * Get Best Entreprise
     *
     * @param integer $limit
     * @return User
     */
    public function getBestSociete(int $limit)
    {
        return $this->userRepository->findBy([
            'compte' => 'ENTREPRISE', 'annuaire' => 1
        ], ['created' => 'DESC'], $limit);
    }

    /**
     * Check if user has posted avis
     *
     * @param [type] $user
     * @param [type] $offre
     * @return Avis
     */
    public function checkIfPostedAvis($user, $offre)
    {
        return $this->avisRepository->findBy(['auteur' => $user, 'offre' => $offre]);
    }

    /**
     * Get Entreprise
     *
     * @param integer $limit
     * @return User
     */
    public function getEntreprise(int $limit)
    {
        return $this->userRepository->findBy([
            'annuaire' => 1, 'compte' => 'ENTREPRISE',
        ], ['created' => 'DESC'], $limit);
    }

    /**
     * Get élément signaler
     *
     * @return Siganler
     */
    public function getSignaler()
    {
        return $this->signalerRepository->findAll();
    }
    
    /**
     * Get laste Signaler
     *
     * @param integer $limit
     * @return Siganler
     */
    public function getLastSignaler(int $limit)
    {
        return $this->signalerRepository->findBy([], ['created' => 'DESC'], $limit);
    }

    /**
     * Get User Alerts
     *
     * @param User $user
     * @param integer $limit
     * @return Alert
     */
    public function getUserLastedAlerts(User $user, int $limit)
    {
        return $this->alertRepository->findBy(['user' => $user], ['created' => 'DESC'], $limit);
    }

    /**
     * Get location
     *
     * @param integer $limit
     * @return Ville
     */
    public function getVilles(int $limit)
    {
        return $this->villeRepository->findBy([], ['created' => 'DESC'], $limit);
    }

    /**
     * Count Nomber Of users
     *
     * @return int
     */
    public function countUsers()
    {
        return count($this->userRepository->findAll());
    }

    /**
     * Count Nomber Of posts
     *
     * @return int
     */
    public function countPosts()
    {
        return count($this->postRepository->findAll());
    }

    /**
     * Count Nomber Of Offre
     *
     * @return int
     */
    public function countOffres()
    {
        return count($this->offreRepository->findAll());
    }
}
