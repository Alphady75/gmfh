<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\LangueRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: LangueRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Langue
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 90)]
    private $name;

    #[ORM\ManyToOne(targetEntity: Offre::class, inversedBy: 'langues')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $offre;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'langues')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $user;

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

    public function getOffre(): ?Offre
    {
        return $this->offre;
    }

    public function setOffre(?Offre $offre): self
    {
        $this->offre = $offre;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
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
}
