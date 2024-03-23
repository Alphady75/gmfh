<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\OffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OffreRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Offre
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $slug;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $intitulePost;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $localisation;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dateDebut;

    #[ORM\Column(type: 'text', nullable: true)]
    private $infosContrat;

    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: Horaires::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $horaires;

    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: Experiences::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $experiences;

    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: Candidature::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $candidatures;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'offres')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\ManyToOne(targetEntity: SecteursActivite::class, inversedBy: 'offres')]
    private $secteuractivite;

    #[ORM\ManyToOne(targetEntity: SousSecteursActivite::class, inversedBy: 'offres')]
    private $soussecteuractivite;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $effectifRecrutement;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $lieuTravail;

    #[ORM\Column(type: 'array', nullable: true)]
    private $langues = [];

    #[ORM\Column(type: 'array', nullable: true)]
    private $typeContrat = [];

    #[ORM\ManyToOne(targetEntity: Booster::class, inversedBy: 'offres')]
    private $booster;

    #[ORM\Column(type: 'boolean')]
    private $complet;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $boosted;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private $status;

    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: Boost::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $boosts;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $anneeExperience;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $salaire;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $qualification;

    #[ORM\Column(type: 'string', length: 90, nullable: true)]
    private $periodicite;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dateFin;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private $devise;

    public function __construct()
    {
        $this->horaires = new ArrayCollection();
        $this->experiences = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
        $this->boosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getIntitulePost(): ?string
    {
        return $this->intitulePost;
    }

    public function setIntitulePost(string $intitulePost): self
    {
        $this->intitulePost = $intitulePost;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getInfosContrat(): ?string
    {
        return $this->infosContrat;
    }

    public function setInfosContrat(?string $infosContrat): self
    {
        $this->infosContrat = $infosContrat;

        return $this;
    }

    /**
     * @return Collection|Horaires[]
     */
    public function getHoraires(): Collection
    {
        return $this->horaires;
    }

    public function addHoraire(Horaires $horaire): self
    {
        if (!$this->horaires->contains($horaire)) {
            $this->horaires[] = $horaire;
            $horaire->setOffre($this);
        }

        return $this;
    }

    public function removeHoraire(Horaires $horaire): self
    {
        if ($this->horaires->removeElement($horaire)) {
            // set the owning side to null (unless already changed)
            if ($horaire->getOffre() === $this) {
                $horaire->setOffre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Experiences[]
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experiences $experience): self
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences[] = $experience;
            $experience->setOffre($this);
        }

        return $this;
    }

    public function removeExperience(Experiences $experience): self
    {
        if ($this->experiences->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getOffre() === $this) {
                $experience->setOffre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Candidature[]
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): self
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures[] = $candidature;
            $candidature->setOffre($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getOffre() === $this) {
                $candidature->setOffre(null);
            }
        }

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

    public function getSecteuractivite(): ?SecteursActivite
    {
        return $this->secteuractivite;
    }

    public function setSecteuractivite(?SecteursActivite $secteuractivite): self
    {
        $this->secteuractivite = $secteuractivite;

        return $this;
    }

    public function getSoussecteuractivite(): ?SousSecteursActivite
    {
        return $this->soussecteuractivite;
    }

    public function setSoussecteuractivite(?SousSecteursActivite $soussecteuractivite): self
    {
        $this->soussecteuractivite = $soussecteuractivite;

        return $this;
    }

    public function getEffectifRecrutement(): ?int
    {
        return $this->effectifRecrutement;
    }

    public function setEffectifRecrutement(?int $effectifRecrutement): self
    {
        $this->effectifRecrutement = $effectifRecrutement;

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

    public function getLangues(): ?array
    {
        return $this->langues;
    }

    public function setLangues(?array $langues): self
    {
        $this->langues = $langues;

        return $this;
    }

    public function getTypeContrat(): ?array
    {
        return $this->typeContrat;
    }

    public function setTypeContrat(?array $typeContrat): self
    {
        $this->typeContrat = $typeContrat;

        return $this;
    }

    public function getBooster(): ?Booster
    {
        return $this->booster;
    }

    public function setBooster(?Booster $booster): self
    {
        $this->booster = $booster;

        return $this;
    }

    public function getComplet(): ?bool
    {
        return $this->complet;
    }

    public function setComplet(bool $complet): self
    {
        $this->complet = $complet;

        return $this;
    }

    public function getBoosted(): ?bool
    {
        return $this->boosted;
    }

    public function setBoosted(?bool $boosted): self
    {
        $this->boosted = $boosted;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Boost[]
     */
    public function getBoosts(): Collection
    {
        return $this->boosts;
    }

    public function addBoost(Boost $boost): self
    {
        if (!$this->boosts->contains($boost)) {
            $this->boosts[] = $boost;
            $boost->setOffre($this);
        }

        return $this;
    }

    public function removeBoost(Boost $boost): self
    {
        if ($this->boosts->removeElement($boost)) {
            // set the owning side to null (unless already changed)
            if ($boost->getOffre() === $this) {
                $boost->setOffre(null);
            }
        }

        return $this;
    }

    public function getAnneeExperience(): ?string
    {
        return $this->anneeExperience;
    }

    public function setAnneeExperience(?string $anneeExperience): self
    {
        $this->anneeExperience = $anneeExperience;

        return $this;
    }

    public function getSalaire(): ?int
    {
        return $this->salaire;
    }

    public function setSalaire(?int $salaire): self
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getQualification(): ?string
    {
        return $this->qualification;
    }

    public function setQualification(?string $qualification): self
    {
        $this->qualification = $qualification;

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

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getDevise(): ?string
    {
        return $this->devise;
    }

    public function setDevise(?string $devise): self
    {
        $this->devise = $devise;

        return $this;
    }
}
