<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\ComposantsRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: ComposantsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Composants
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToOne(targetEntity: Stripe::class, inversedBy: 'composants')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $stripe;

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

    public function getStripe(): ?Stripe
    {
        return $this->stripe;
    }

    public function setStripe(?Stripe $stripe): self
    {
        $this->stripe = $stripe;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
