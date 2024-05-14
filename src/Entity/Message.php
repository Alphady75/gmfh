<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\HasLifecycleCallbacks]
/**
 * @Vich\Uploadable
 */
class Message
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $contenu;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'messages')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $auteur;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'messages')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $destinataire;

    #[ORM\Column(type: 'boolean')]
    private $lu;

    /**
     * @Vich\UploadableField(mapping="chatfichiers", fileNameProperty="fichier")
     * @var File|null
     * @Assert\File(maxSize="10M", maxSizeMessage="Fichier trop volumineux maximum 10Mb")
    **/
    private $fichierFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $fichier;

    #[ORM\ManyToOne(targetEntity: Candidature::class, inversedBy: 'messages')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $candidature;

    #[ORM\OneToMany(mappedBy: 'lastMessage', targetEntity: Conversation::class, cascade: ['persist'])]
    private $conversations;

    #[ORM\ManyToOne(targetEntity: Conversation::class, inversedBy: 'messages')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $conversation;

    public function __construct()
    {
        $this->conversations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

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

    public function getDestinataire(): ?User
    {
        return $this->destinataire;
    }

    public function setDestinataire(?User $destinataire): self
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    public function getLu(): ?bool
    {
        return $this->lu;
    }

    public function setLu(bool $lu): self
    {
        $this->lu = $lu;

        return $this;
    }

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setFichier(?string $fichier): self
    {
        $this->fichier = $fichier;

        return $this;
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

    /**
     * @return Collection|Conversation[]
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
            $conversation->setLastMessage($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getLastMessage() === $this) {
                $conversation->setLastMessage(null);
            }
        }

        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }/**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $fichierFile
     */
    public function setFichierFile(?File $fichierFile = null): void
    {
        $this->fichierFile = $fichierFile;

        if (null !== $fichierFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setUpdated(new \DateTimeImmutable());
        }
    }

    public function getFichierFile(): ?File
    {
        return $this->fichierFile;
    }
}
