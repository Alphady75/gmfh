<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\HorairesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HorairesRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Horaires
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Offre::class, inversedBy: 'horaires')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private $offre;

    #[ORM\Column(type: 'string', length: 100)]
    private $name;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
