<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\SecteursActiviteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(fields: ['name'], message: "Ce secteur d'activité existe déjà")]
#[ORM\Entity(repositoryClass: SecteursActiviteRepository::class)]
#[ORM\HasLifecycleCallbacks]
class SecteursActivite
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'secteursActivite', targetEntity: SousSecteursActivite::class, orphanRemoval: true, cascade: ['persist'])]
    private $sousSecteursActivites;

    #[ORM\OneToMany(mappedBy: 'secteuractivite', targetEntity: Offre::class, cascade: ['persist'])]
    private $offres;

    #[ORM\OneToMany(mappedBy: 'secteuractivite', targetEntity: User::class, cascade: ['persist'])]
    private $users;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $complet;

    #[ORM\ManyToMany(targetEntity: Alert::class, mappedBy: 'secteurs', cascade: ['persist'])]
    private $alerts;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $slug;

    public function __construct()
    {
        $this->sousSecteursActivites = new ArrayCollection();
        $this->offres = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->alerts = new ArrayCollection();
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

    /**
     * @return Collection|SousSecteursActivite[]
     */
    public function getSousSecteursActivites(): Collection
    {
        return $this->sousSecteursActivites;
    }

    public function addSousSecteursActivite(SousSecteursActivite $sousSecteursActivite): self
    {
        if (!$this->sousSecteursActivites->contains($sousSecteursActivite)) {
            $this->sousSecteursActivites[] = $sousSecteursActivite;
            $sousSecteursActivite->setSecteursActivite($this);
        }

        return $this;
    }

    public function removeSousSecteursActivite(SousSecteursActivite $sousSecteursActivite): self
    {
        if ($this->sousSecteursActivites->removeElement($sousSecteursActivite)) {
            // set the owning side to null (unless already changed)
            if ($sousSecteursActivite->getSecteursActivite() === $this) {
                $sousSecteursActivite->setSecteursActivite(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName() ?? '';
    }

    /**
     * @return Collection|Offre[]
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): self
    {
        if (!$this->offres->contains($offre)) {
            $this->offres[] = $offre;
            $offre->setSecteuractivite($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getSecteuractivite() === $this) {
                $offre->setSecteuractivite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setSecteuractivite($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSecteuractivite() === $this) {
                $user->setSecteuractivite(null);
            }
        }

        return $this;
    }

    public function getComplet(): ?bool
    {
        return $this->complet;
    }

    public function setComplet(?bool $complet): self
    {
        $this->complet = $complet;

        return $this;
    }

    /**
     * @return Collection|Alert[]
     */
    public function getAlerts(): Collection
    {
        return $this->alerts;
    }

    public function addAlert(Alert $alert): self
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts[] = $alert;
            $alert->addSecteur($this);
        }

        return $this;
    }

    public function removeAlert(Alert $alert): self
    {
        if ($this->alerts->removeElement($alert)) {
            $alert->removeSecteur($this);
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
