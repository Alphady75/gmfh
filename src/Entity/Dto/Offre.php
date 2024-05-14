<?php

namespace App\Entity\Dto;

use App\Entity\SecteursActivite;
use Doctrine\Common\Collections\ArrayCollection;

class Offre
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
    public $query = null;

    /**
     * @var bool|null
     */
    public $booster;

    /**
     * @var bool|null
     */
    public $urgent;

    /**
     * @var bool|null
     */
    public $status;

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
    public $minSalaire = null;

    /**
     * @var int
     */
    public $maxSalaire = null;

    /**
     * @var SecteursActivite
     */
    public $secteur;

    /**
     * @var ArrayCollection
     */
    public $horaires;

    /**
     * @var ArrayCollection
     */
    public $langues;

    /**
     * @var ArrayCollection
     */
    public $experiences;

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
    public $typeContrat;

    /**
     * @var string
     */
    public $lieuTravail;

    public function __construct()
    {
        $this->horaires = new ArrayCollection();
        $this->langues = new ArrayCollection();
        $this->experiences = new ArrayCollection();
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
     * Get the value of secteur
     */
    public function getSecteur() {
        return $this->secteur;
    }

    /**
     * Set the value of secteur
     */
    public function setSecteur(SecteursActivite $secteur): self {
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
     * Get the value of status
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus($status): self {
        $this->status = $status;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getHoraires(): ArrayCollection
    {
        return $this->horaires;
    }

    /**
     * @param ArrayCollection $horaires
     */
    public function setHoraires(ArrayCollection $horaires):void
    {
        $this->horaires = $horaires;
    }

    /**
     * Get the value of experiences
     *
     * @return  ArrayCollection
     */ 
    public function getExperiences()
    {
        return $this->experiences;
    }

    /**
     * Set the value of experiences
     *
     * @param  ArrayCollection  $experiences
     *
     * @return  self
     */ 
    public function setExperiences(ArrayCollection $experiences)
    {
        $this->experiences = $experiences;

        return $this;
    }

    /**
     * Get the value of typeContrat
     *
     * @return  string
     */ 
    public function getTypeContrat()
    {
        return $this->typeContrat;
    }

    /**
     * Set the value of typeContrat
     *
     * @param  string  $typeContrat
     *
     * @return  self
     */ 
    public function setTypeContrat(string $typeContrat)
    {
        $this->typeContrat = $typeContrat;

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
}
