<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\ServiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Service
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'services')]
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?User
    {
        return $this->name;
    }

    public function setName(?User $name): self
    {
        $this->name = $name;

        return $this;
    }
}
