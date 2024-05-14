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

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\ManyToOne(targetEntity: SecteursActivite::class, inversedBy: 'sousSecteursActivites')]
    #[ORM\JoinColumn(nullable: false)]
    private $secteursActivite;

    #[ORM\OneToMany(mappedBy: 'soussecteuractivite', targetEntity: Offre::class , cascade: ['persist'])]
    private $offres;

    #[ORM\OneToMany(mappedBy: 'soussecteuractivite', targetEntity: User::class , cascade: ['persist'])]
    private $users;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $slug;

    public function __construct()
    {
        $this->offres = new ArrayCollection();
        $this->users = new ArrayCollection();
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
            $user->setsoussecteuractivite($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getsoussecteuractivite() === $this) {
                $user->setsoussecteuractivite(null);
            }
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
