<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\SignalerRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: SignalerRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Signaler
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'signalers')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private $user;

    #[ORM\ManyToOne(targetEntity: Offre::class, inversedBy: 'signalers')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $offre;

    #[ORM\Column(type: 'string', length: 255)]
    private $abus;

    #[ORM\Column(type: 'text')]
    private $message;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'signalers')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $post;

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

    public function getOffre(): ?Offre
    {
        return $this->offre;
    }

    public function setOffre(?Offre $offre): self
    {
        $this->offre = $offre;

        return $this;
    }

    public function getAbus(): ?string
    {
        return $this->abus;
    }

    public function setAbus(string $abus): self
    {
        $this->abus = $abus;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

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
}
