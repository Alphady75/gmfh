<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\AlertRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: AlertRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Alert
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $query;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $localisation;

    #[ORM\ManyToMany(targetEntity: SecteursActivite::class, inversedBy: 'alerts', cascade: ['persist'])]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $secteurs;

    #[ORM\Column(type: 'boolean')]
    private $active;

    #[ORM\Column(type: 'boolean')]
    private $lu;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $lieuTravail;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $typeContrat;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $periodicite;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $minSalaire;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $maxSalaire;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'alerts')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private $user;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $url;

    public function __construct()
    {
        $this->secteurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function setQuery(?string $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    /**
     * @return Collection|SecteursActivite[]
     */
    public function getSecteurs(): Collection
    {
        return $this->secteurs;
    }

    public function addSecteur(SecteursActivite $secteur): self
    {
        if (!$this->secteurs->contains($secteur)) {
            $this->secteurs[] = $secteur;
        }

        return $this;
    }

    public function removeSecteur(SecteursActivite $secteur): self
    {
        $this->secteurs->removeElement($secteur);

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getLu(): ?bool
    {
        return $this->lu;
    }

    public function setLu(bool $lu): self
    {
        $this->lu = $lu;

        return $this;
    }

    public function getLieuTravail(): ?string
    {
        return $this->lieuTravail;
    }

    public function setLieuTravail(?string $lieuTravail): self
    {
        $this->lieuTravail = $lieuTravail;

        return $this;
    }

    public function getTypeContrat(): ?string
    {
        return $this->typeContrat;
    }

    public function setTypeContrat(?string $typeContrat): self
    {
        $this->typeContrat = $typeContrat;

        return $this;
    }

    public function getPeriodicite(): ?string
    {
        return $this->periodicite;
    }

    public function setPeriodicite(?string $periodicite): self
    {
        $this->periodicite = $periodicite;

        return $this;
    }

    public function getMinSalaire(): ?int
    {
        return $this->minSalaire;
    }

    public function setMinSalaire(?int $minSalaire): self
    {
        $this->minSalaire = $minSalaire;

        return $this;
    }

    public function getMaxSalaire(): ?int
    {
        return $this->maxSalaire;
    }

    public function setMaxSalaire(?int $maxSalaire): self
    {
        $this->maxSalaire = $maxSalaire;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
