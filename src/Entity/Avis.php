<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\AvisRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: AvisRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Avis
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'avis')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $user;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'avis')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $post;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'auteuravis')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private $auteur;

    #[ORM\ManyToOne(targetEntity: Offre::class, inversedBy: 'avis')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $offre;

    #[ORM\Column(type: 'string', length: 90)]
    private $type;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $note;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): self
    {
        $this->auteur = $auteur;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
    }
}
