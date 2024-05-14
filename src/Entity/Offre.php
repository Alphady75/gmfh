<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\OffreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: OffreRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Il existe déjà une offre avec ce titre')]
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
    private $intitulePoste;

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

    #[ORM\Column(type: 'string', length: 200, nullable: true)]
    private $typeContrat;

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

    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: Favoris::class)]
    private $favoris;

    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: Responsabilite::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $responsabilites;

    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: Langue::class, cascade: ['persist'])]
    private Collection $langues;

    #[ORM\Column(type: 'string', length: 90, nullable: true)]
    private $genre;

    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: Signaler::class, cascade: ['persist'])]
    private $signalers;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $bloquer;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $bloquerAt;

    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: Avis::class, cascade: ['persist'])]
    private $avis;

    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: Vue::class, cascade: ['persist'])]
    private $vues;

    public function __construct()
    {
        $this->horaires = new ArrayCollection();
        $this->experiences = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
        $this->boosts = new ArrayCollection();
        $this->favoris = new ArrayCollection();
        $this->responsabilites = new ArrayCollection();
        $this->langues = new ArrayCollection();
        $this->signalers = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->vues = new ArrayCollection();
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

    public function getIntitulePoste(): ?string
    {
        return $this->intitulePoste;
    }

    public function setIntitulePoste(?string $intitulePoste): self
    {
        $this->intitulePoste = $intitulePoste;

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

    public function setLocalisation(?string $localisation): self
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

    public function getTypeContrat(): ?string
    {
        return $this->typeContrat;
    }

    public function setTypeContrat(?string $typeContrat): self
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

    /**
     * @return Collection|Favoris[]
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Favoris $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
            $favori->setOffre($this);
        }

        return $this;
    }

    public function removeFavori(Favoris $favori): self
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getOffre() === $this) {
                $favori->setOffre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Responsabilite[]
     */
    public function getResponsabilites(): Collection
    {
        return $this->responsabilites;
    }

    public function addResponsabilite(Responsabilite $responsabilite): self
    {
        if (!$this->responsabilites->contains($responsabilite)) {
            $this->responsabilites[] = $responsabilite;
            $responsabilite->setOffre($this);
        }

        return $this;
    }

    public function removeResponsabilite(Responsabilite $responsabilite): self
    {
        if ($this->responsabilites->removeElement($responsabilite)) {
            // set the owning side to null (unless already changed)
            if ($responsabilite->getOffre() === $this) {
                $responsabilite->setOffre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Langue[]
     */
    public function getLangues(): Collection
    {
        return $this->langues;
    }

    public function addLangue(Langue $langue): self
    {
        if (!$this->langues->contains($langue)) {
            $this->langues[] = $langue;
            $langue->setOffre($this);
        }

        return $this;
    }

    public function removeLangue(Langue $langue): self
    {
        if ($this->langues->removeElement($langue)) {
            // set the owning side to null (unless already changed)
            if ($langue->getOffre() === $this) {
                $langue->setOffre(null);
            }
        }

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * @return Collection|Signaler[]
     */
    public function getSignalers(): Collection
    {
        return $this->signalers;
    }

    public function addSignaler(Signaler $signaler): self
    {
        if (!$this->signalers->contains($signaler)) {
            $this->signalers[] = $signaler;
            $signaler->setOffre($this);
        }

        return $this;
    }

    public function removeSignaler(Signaler $signaler): self
    {
        if ($this->signalers->removeElement($signaler)) {
            // set the owning side to null (unless already changed)
            if ($signaler->getOffre() === $this) {
                $signaler->setOffre(null);
            }
        }

        return $this;
    }

    /**
     * Verifi si ce microcervice a déjà ajouter en favoris par un utilisateur
     *
     * @param User $user
     * @return boolean
     */
    public function isAddedByUser(User $user){
        foreach($this->favoris as $favori){
            if($favori->getUser() === $user) return true;
        }

        return false;
    }

    /**
     * Verifi si ce microcervice a déjà ajouter en favoris par un utilisateur
     *
     * @param User $user
     * @return boolean
     */
    public function isCandidateByUser(User $user){
        foreach($this->candidatures as $candidature){
            if($candidature->getUser() === $user) return true;
        }

        return false;
    }

    public function getBloquer(): ?bool
    {
        return $this->bloquer;
    }

    public function setBloquer(?bool $bloquer): self
    {
        $this->bloquer = $bloquer;

        return $this;
    }

    public function getBloquerAt(): ?\DateTimeInterface
    {
        return $this->bloquerAt;
    }

    public function setBloquerAt(?\DateTimeInterface $bloquerAt): self
    {
        $this->bloquerAt = $bloquerAt;

        return $this;
    }

    /**
     * @return Collection|Avis[]
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis[] = $avi;
            $avi->setOffre($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getOffre() === $this) {
                $avi->setOffre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Vue[]
     */
    public function getVues(): Collection
    {
        return $this->vues;
    }

    public function addVue(Vue $vue): self
    {
        if (!$this->vues->contains($vue)) {
            $this->vues[] = $vue;
            $vue->setOffre($this);
        }

        return $this;
    }

    public function removeVue(Vue $vue): self
    {
        if ($this->vues->removeElement($vue)) {
            // set the owning side to null (unless already changed)
            if ($vue->getOffre() === $this) {
                $vue->setOffre(null);
            }
        }

        return $this;
    }
}
