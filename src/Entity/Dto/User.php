<?php

namespace App\Entity\Dto;

use Doctrine\Common\Collections\ArrayCollection;

class User
{
    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var int
     */
    public $limit;

    /**
     * @var string
     */
    public $compte;

    /**
     * @var string
     */
    public $query = null;

    /**
     * @var DateTimeInterface|null
     */
    public $minDate = null;

    /**
     * @var DateTimeInterface|null
     */
    public $maxDate = null;

    /**
     * @var int
     */
    public $maxSalaire = null;

    /**
     * @var int
     */
    public $minSalaire = null;

    /**
     * @var SecteurActivite
     */
    public $secteur;

    /**
     * @var ArrayCollection
     */
    public $competences;

    /**
     * @var ArrayCollection
     */
    public $langues;

    /**
     * @var ArrayCollection
     */
    public $villes;

    /**
     * @var ArrayCollection
     */
    public $services;

    /**
     * @var string
     */
    public $localisation;

    /**
     * @var string
     */
    public $periodicite;

    /**
     * @var string
     */
    public $lieuTravail;

    /**
     * @var bool
     */
    public $isVerified;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->langues = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->villes = new ArrayCollection();
    }

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
     * Get the value of secteur
     */
    public function getSecteur() {
        return $this->secteur;
    }

    /**
     * Set the value of secteur
     */
    public function setSecteur($secteur): self {
        $this->secteur = $secteur;
        return $this;
    }

    /**
     * Get the value of localisation
     */
    public function getLocalisation() {
        return $this->localisation;
    }

    /**
     * Set the value of localisation
     */
    public function setLocalisation($localisation): self {
        $this->localisation = $localisation;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCompetences(): ArrayCollection
    {
        return $this->competences;
    }

    /**
     * @param ArrayCollection $competences
     */
    public function setCompetences(ArrayCollection $competences):void
    {
        $this->competences = $competences;
    }

    /**
     * Get the value of services
     *
     * @return  ArrayCollection
     */ 
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Set the value of services
     *
     * @param  ArrayCollection  $services
     *
     * @return  self
     */ 
    public function setServices(ArrayCollection $services)
    {
        $this->services = $services;

        return $this;
    }

    /**
     * Get the value of langues
     *
     * @return  ArrayCollection
     */ 
    public function getLangues()
    {
        return $this->langues;
    }

    /**
     * Set the value of langues
     *
     * @param  ArrayCollection  $langues
     *
     * @return  self
     */ 
    public function setLangues(ArrayCollection $langues)
    {
        $this->langues = $langues;

        return $this;
    }

    /**
     * Get the value of minSalaire
     *
     * @return  int
     */ 
    public function getMinSalaire()
    {
        return $this->minSalaire;
    }

    /**
     * Set the value of minSalaire
     *
     * @param  int  $minSalaire
     *
     * @return  self
     */ 
    public function setMinSalaire(string $minSalaire)
    {
        $this->minSalaire = $minSalaire;

        return $this;
    }

    /**
     * Get the value of maxSalaire
     *
     * @return  string
     */ 
    public function getMaxSalaire()
    {
        return $this->maxSalaire;
    }

    /**
     * Set the value of maxSalaire
     *
     * @param  string  $maxSalaire
     *
     * @return  self
     */ 
    public function setMaxSalaire(string $maxSalaire)
    {
        $this->maxSalaire = $maxSalaire;

        return $this;
    }

    /**
     * Get the value of limit
     *
     * @return  int
     */ 
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Set the value of limit
     *
     * @param  int  $limit
     *
     * @return  self
     */ 
    public function setLimit(int $limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get the value of periodicite
     *
     * @return  string
     */ 
    public function getPeriodicite()
    {
        return $this->periodicite;
    }

    /**
     * Set the value of periodicite
     *
     * @param  string  $periodicite
     *
     * @return  self
     */ 
    public function setPeriodicite(string $periodicite)
    {
        $this->periodicite = $periodicite;

        return $this;
    }

    /**
     * Get the value of lieuTravail
     *
     * @return  string
     */ 
    public function getLieuTravail()
    {
        return $this->lieuTravail;
    }

    /**
     * Set the value of lieuTravail
     *
     * @param  string  $lieuTravail
     *
     * @return  self
     */ 
    public function setLieuTravail(string $lieuTravail)
    {
        $this->lieuTravail = $lieuTravail;

        return $this;
    }

    /**
     * Get the value of villes
     */
    public function getVilles() {
        return $this->villes;
    }

    /**
     * Set the value of villes
     */
    public function setVilles($villes): self {
        $this->villes = $villes;
        return $this;
    }

    /**
     * Get the value of isVerified
     *
     * @return  bool
     */ 
    public function getIsVerified()
    {
        return $this->isVerified;
    }

    /**
     * Set the value of isVerified
     *
     * @param  bool  $isVerified
     *
     * @return  self
     */ 
    public function setIsVerified(bool $isVerified)
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * Get the value of compte
     *
     * @return  string
     */ 
    public function getCompte()
    {
        return $this->compte;
    }

    /**
     * Set the value of compte
     *
     * @param  string  $compte
     *
     * @return  self
     */ 
    public function setCompte(string $compte)
    {
        $this->compte = $compte;

        return $this;
    }
}
