<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\BoostRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: BoostRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Boost
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 20)]
    private $element;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'boosts')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $post;

    #[ORM\Column(type: 'datetime')]
    private $startAt;

    #[ORM\Column(type: 'datetime')]
    private $endAt;

    #[ORM\Column(type: 'string', length: 15)]
    private $status;

    #[ORM\ManyToOne(targetEntity: Offre::class, inversedBy: 'boosts')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $offre;

    #[ORM\Column(type: 'string', length: 255)]
    private $token;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getElement(): ?string
    {
        return $this->element;
    }

    public function setElement(string $element): self
    {
        $this->element = $element;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

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
