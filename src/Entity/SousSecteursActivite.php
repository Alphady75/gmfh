<?php

namespace App\Entity;

use App\Repository\SousSecteursActiviteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SousSecteursActiviteRepository::class)]
#[ORM\HasLifecycleCallbacks]
class SousSecteursActivite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: SecteursActivite::class, inversedBy: 'sousSecteursActivites')]
    #[ORM\JoinColumn(nullable: false)]
    private $secteursActivite;

    #[ORM\OneToMany(mappedBy: 'soussecteuractivite', targetEntity: Offre::class)]
    private $offres;

    public function __construct()
    {
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

    public function getSecteursActivite(): ?SecteursActivite
    {
        return $this->secteursActivite;
    }

    public function setSecteursActivite(?SecteursActivite $secteursActivite): self
    {
        $this->secteursActivite = $secteursActivite;

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
            $offre->setSoussecteuractivite($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getSoussecteuractivite() === $this) {
                $offre->setSoussecteuractivite(null);
            }
        }

        return $this;
    }
}
