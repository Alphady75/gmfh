<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\SecteursActiviteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SecteursActiviteRepository::class)]
#[ORM\HasLifecycleCallbacks]
class SecteursActivite
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'secteursActivite', targetEntity: SousSecteursActivite::class, orphanRemoval: true)]
    private $sousSecteursActivites;

    #[ORM\OneToMany(mappedBy: 'secteuractivite', targetEntity: Offre::class)]
    private $offres;

    public function __construct()
    {
        $this->sousSecteursActivites = new ArrayCollection();
        $this->offres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
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
        return $this->getName();
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
}
