<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\FavorisRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: FavorisRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Favoris
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 15)]
    private $element;

    #[ORM\ManyToOne(targetEntity: Offre::class, inversedBy: 'favoris')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $offre;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'favoris')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $post;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'favoris')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $user;

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

    public function getOffre(): ?Offre
    {
        return $this->offre;
    }

    public function setOffre(?Offre $offre): self
    {
        $this->offre = $offre;

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
