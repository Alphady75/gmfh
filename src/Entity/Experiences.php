<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\ExperiencesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExperiencesRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Experiences
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $name;

    #[ORM\ManyToOne(targetEntity: Offre::class, inversedBy: 'experiences')]
    #[ORM\JoinColumn(nullable: false)]
    private $offre;

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
        return $this->getname();
    }
}
