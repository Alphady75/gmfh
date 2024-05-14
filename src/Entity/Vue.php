<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\VueRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: VueRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Vue
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'vues')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $user;

    #[ORM\Column(type: 'string', length: 60)]
    private $ip;

    #[ORM\ManyToOne(targetEntity: Offre::class, inversedBy: 'vues')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $offre;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

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
}
