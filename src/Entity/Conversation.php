<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\ConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

#[ORM\Entity(repositoryClass: ConversationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Conversation
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Candidature::class, inversedBy: 'conversations')]
    #[JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private $candidature;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'conversations')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private $user1;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'conversations')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $user2;

    #[ORM\ManyToOne(targetEntity: Message::class, inversedBy: 'conversations')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $lastMessage;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'conversations')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $sender;

    #[ORM\Column(type: 'boolean')]
    private $terminee;

    #[ORM\OneToMany(mappedBy: 'conversation', targetEntity: Message::class, cascade: ['persist'])]
    private $messages;

    #[ORM\Column(type: 'string', length: 255)]
    private $token;

    #[ORM\ManyToOne(targetEntity: Post::class, inversedBy: 'conversations')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $post;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidature(): ?Candidature
    {
        return $this->candidature;
    }

    public function setCandidature(?Candidature $candidature): self
    {
        $this->candidature = $candidature;

        return $this;
    }

    public function getUser1(): ?User
    {
        return $this->user1;
    }

    public function setUser1(?User $user1): self
    {
        $this->user1 = $user1;

        return $this;
    }

    public function getUser2(): ?User
    {
        return $this->user2;
    }

    public function setUser2(?User $user2): self
    {
        $this->user2 = $user2;

        return $this;
    }

    public function getLastMessage(): ?Message
    {
        return $this->lastMessage;
    }

    public function setLastMessage(?Message $lastMessage): self
    {
        $this->lastMessage = $lastMessage;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getTerminee(): ?bool
    {
        return $this->terminee;
    }

    public function setTerminee(bool $terminee): self
    {
        $this->terminee = $terminee;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setConversation($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getConversation() === $this) {
                $message->setConversation(null);
            }
        }

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
