<?php

namespace App\Entity\Dto;

class Offre
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
     * @var SecteurActivite
     */
    public $secteur;

    /**
     * @var Horaires
     */
    public $horaires;

    /**
     * @var string
     */
    public $localisation;

    /**
     * @var array
     */
    public $typeContrat;

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
     * Get the value of typeContrat
     */
    public function getTypeContrat() {
        return $this->typeContrat;
    }

    /**
     * Set the value of typeContrat
     */
    public function setTypeContrat($typeContrat): self {
        $this->typeContrat = $typeContrat;
        return $this;
    }

    /**
     * Get the value of horaires
     */
    public function getHoraires() {
        return $this->horaires;
    }

    /**
     * Set the value of horaires
     */
    public function setHoraires($horaires): self {
        $this->horaires = $horaires;
        return $this;
    }
}
