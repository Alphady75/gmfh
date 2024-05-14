<?php

namespace App\Entity\Dto;

use App\Entity\Categorie;
use App\Entity\SousCategorie;

class Post
{
    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var string
     */
    public $query = null;

    /**
     * @var int|null
     */
    public $minPrice;

    /**
     * @var int|null
     */
    public $maxPrice;

    /**
     * @var bool|null
     */
    public $promo;

    /**
     * @var bool|null
     */
    public $livraison;

    /**
     * @var bool|null
     */
    public $negociable;

    /**
     * @var bool|null
     */
    public $online;

    /**
     * @var bool|null
     */
    public $offline;

    /**
     * @var bool|null
     */
    public $vedette;

    /**
     * @var bool|null
     */
    public $urgent;

    /**
     * @var bool|null
     */
    public $isSelled;

    /**
     * @var bool|null
     */
    public $signaler;

    /**
     * @var DateTimeInterface|null
     */
    public $minDate = null;

    /**
     * @var DateTimeInterface|null
     */
    public $maxDate = null;

    /**
     * @var Categorie
     */
    public $categorie;

    /**
     * @var SousCategorie
     */
    public $souscategorie;

    /**
     * @var Ville
     */
    public $ville;

    /**
     * @var int|null
     */
    public $telephone;

    /**
     * @var string|null
     */
    public $statut;

    /**
     * @var string|null
     */
    public $etat;

    /**
     * @var int|null
     */
    public $limit;

    /**
     * Get the value of query
     *
     * @return  string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set the value of query
     *
     * @param  string  $query
     *
     * @return  self
     */
    public function setQuery(?string $query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Get the value of minPrice
     *
     * @return  int|null
     */
    public function getMinPrice()
    {
        return $this->minPrice;
    }

    /**
     * Set the value of minPrice
     *
     * @param  int|null  $minPrice
     *
     * @return  self
     */
    public function setMinPrice(?int $minPrice)
    {
        $this->minPrice = $minPrice;

        return $this;
    }

    /**
     * Get the value of maxPrice
     *
     * @return  int|null
     */
    public function getMaxPrice()
    {
        return $this->maxPrice;
    }

    /**
     * Set the value of maxPrice
     *
     * @param  int|null  $maxPrice
     *
     * @return  self
     */
    public function setMaxPrice(?int $maxPrice)
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    /**
     * Get the value of livraison
     *
     * @return  bool|null
     */
    public function getlivraison()
    {
        return $this->livraison;
    }

    /**
     * Set the value of livraison
     *
     * @param  bool|null  $livraison
     *
     * @return  self
     */
    public function setlivraison($livraison)
    {
        $this->livraison = $livraison;

        return $this;
    }

    /**
     * Get the value of prixNegociable
     *
     * @return  bool|null
     */
    public function getNegociable()
    {
        return $this->negociable;
    }

    /**
     * Set the value of negociable
     *
     * @param  bool|null  $negociable
     *
     * @return  self
     */
    public function setNegociable($negociable)
    {
        $this->negociable = $negociable;

        return $this;
    }

    /**
     * Get the value of promo
     *
     * @return  bool|null
     */
    public function getPromo()
    {
        return $this->promo;
    }

    /**
     * Set the value of promo
     *
     * @param  bool|null  $promo
     *
     * @return  self
     */
    public function setPromo($promo)
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * Get the value of minDate
     *
     * @return  DateTimeInterface|null
     */
    public function getMinDate()
    {
        return $this->minDate;
    }

    /**
     * Set the value of minDate
     *
     * @param  DateTimeInterface|null  $minDate
     *
     * @return  self
     */
    public function setMinDate($minDate)
    {
        $this->minDate = $minDate;

        return $this;
    }

    /**
     * Get the value of maxDate
     *
     * @return  DateTimeInterface|null
     */
    public function getMaxDate()
    {
        return $this->maxDate;
    }

    /**
     * Set the value of maxDate
     *
     * @param  DateTimeInterface|null  $maxDate
     *
     * @return  self
     */
    public function setMaxDate($maxDate)
    {
        $this->maxDate = $maxDate;

        return $this;
    }

    /**
     * Get the value of vedette
     *
     * @return  bool|null
     */
    public function getVedette()
    {
        return $this->vedette;
    }

    /**
     * Set the value of vedette
     *
     * @param  bool|null  $vedette
     *
     * @return  self
     */
    public function setVedette($vedette)
    {
        $this->vedette = $vedette;

        return $this;
    }

    /**
     * Get the value of isSelled
     *
     * @return  bool|null
     */
    public function getIsSelled()
    {
        return $this->isSelled;
    }

    /**
     * Set the value of isSelled
     *
     * @param  bool|null  $isSelled
     *
     * @return  self
     */
    public function setIsSelled($isSelled)
    {
        $this->isSelled = $isSelled;

        return $this;
    }

    /**
     * Get the value of minPrice
     *
     * @return  int|null
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set the value of minPrice
     *
     * @param  int|null  $minPrice
     *
     * @return  self
     */
    public function setTelephone(?int $telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return  int|null
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param  string|null  $etat
     * @return  self
     */
    public function setEtat(?string $etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * @return  int|null
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * @param  string|null  $etat
     * @return  self
     */
    public function setStatut(?string $statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * @return  bool|null
     */
    public function getSignaler()
    {
        return $this->signaler;
    }

    /**
     * @param  bool|null  $signaler
     *
     * @return  self
     */
    public function setSignaler($signaler)
    {
        $this->signaler = $signaler;

        return $this;
    }

    /**
     * Get the value of urgent
     *
     * @return  bool|null
     */
    public function getUrgent()
    {
        return $this->urgent;
    }

    /**
     * Set the value of urgent
     *
     * @param  bool|null  $urgent
     *
     * @return  self
     */
    public function setUrgent($urgent)
    {
        $this->urgent = $urgent;

        return $this;
    }

    /**
     * Get the value of categorie
     */
    public function getCategorie() {
        return $this->categorie;
    }

    /**
     * Set the value of categorie
     */
    public function setCategorie($categorie): self {
        $this->categorie = $categorie;
        return $this;
    }

    /**
     * Get the value of souscategorie
     */
    public function getSouscategorie() {
        return $this->souscategorie;
    }

    /**
     * Set the value of souscategorie
     */
    public function setSouscategorie($souscategorie): self {
        $this->souscategorie = $souscategorie;
        return $this;
    }

    /**
     * Get the value of ville
     */
    public function getVille() {
        return $this->ville;
    }

    /**
     * Set the value of ville
     */
    public function setVille($ville): self {
        $this->ville = $ville;
        return $this;
    }

    /**
     * Get the value of limit
     */
    public function getLimit() {
        return $this->limit;
    }

    /**
     * Set the value of limit
     */
    public function setLimit($limit): self {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Get the value of online
     */
    public function getOnline() {
        return $this->online;
    }

    /**
     * Set the value of online
     */
    public function setOnline($online): self {
        $this->online = $online;
        return $this;
    }
}
