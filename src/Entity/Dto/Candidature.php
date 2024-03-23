<?php

namespace App\Entity\Dto;

class Candidature
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
     * @var int
     */
    public $limit = null;

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
     * @var string
     */
    public $localisation;

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
    public function getSecteur()
    {
        return $this->secteur;
    }

    /**
     * Set the value of secteur
     */
    public function setSecteur($secteur): self
    {
        $this->secteur = $secteur;
        return $this;
    }

    /**
     * Get the value of localisation
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }

    /**
     * Set the value of localisation
     */
    public function setLocalisation($localisation): self
    {
        $this->localisation = $localisation;
        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus($status): self
    {
        $this->status = $status;
        return $this;
    }
    
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getLimit()
    {
        return $this->limit;
    }
    
    /**
     * Undocumented function
     *
     * @param string|null $limit
     * @return void
     */
    public function setLimit(?string $limit)
    {
        $this->limit = $limit;

        return $this;
    }
}
