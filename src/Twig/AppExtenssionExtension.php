<?php

namespace App\Twig;

use App\Repository\ArticleCategorieRepository;
use App\Repository\HorairesRepository;
use App\Repository\OffreRepository;
use App\Repository\SecteursActiviteRepository;
use App\Repository\TagRepository;
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
        private TagRepository $tagRepository
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
    public function getOffreOneHoraire($offre){
        return $this->horairesRepository->findOneBy(['offre' => $offre]);
    }

    /**
     * Get all article catégories
     *
     * @return array
     */
    public function getArticleCategories(){
        return $this->articleCategorieRepository->findBy(['name' => 'ASC']);
    }

    /**
     * Get all article tags
     *
     * @return array
     */
    public function getArticleTags(){
        return $this->tagRepository->findBy(['name' => 'ASC']);
    }
}
