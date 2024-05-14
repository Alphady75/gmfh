<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\CandidatureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CandidatureRepository::class)]
#[ORM\HasLifecycleCallbacks]
/**
 * @Vich\Uploadable
 */
class Candidature
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private $user;

    /**
     * @Vich\UploadableField(mapping="candidaturescv", fileNameProperty="cv")
     * @var File|null
     * @Assert\File(mimeTypes = {"application/msword", "application/pdf"}, mimeTypesMessage = "Mauvais format de document (doc et pdf)")
     * @Assert\File(maxSize="10M", maxSizeMessage="Fichier trop volumineux maximum 10Mb")
     **/
    private $cvFile;

    #[ORM\Column(type: 'string', length: 255)]
    private $cv;

    #[ORM\ManyToOne(targetEntity: Offre::class, inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private $offre;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private $statut;

    #[ORM\Column(type: 'string', length: 15, nullable: true)]
    private $etat;

    #[ORM\Column(type: 'string', length: 255)]
    private $token;

    #[ORM\Column(type: 'text')]
    private $candidatPresentation;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private $status;

    #[ORM\OneToMany(mappedBy: 'candidature', targetEntity: Message::class, cascade: ['persist'])]
    private $messages;

    #[ORM\OneToMany(mappedBy: 'candidature', targetEntity: Conversation::class, cascade: ['persist'])]
    private $conversations;

    #[ORM\Column(type: 'string', length: 90, nullable: true)]
    private $entretien;

    /**
     * @Vich\UploadableField(mapping="candidatureslettres", fileNameProperty="lettreMotivation")
     * @var File|null
     * @Assert\File(mimeTypes = {"application/msword", "application/pdf"}, mimeTypesMessage = "Mauvais format de document (doc et pdf)")
     * @Assert\File(maxSize="10M", maxSizeMessage="Fichier trop volumineux maximum 10Mb")
     **/
    private $lettreMotivationFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $lettreMotivation;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dateEntretien;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dateSelection;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dateTrie;

    #[ORM\Column(type: 'text', nullable: true)]
    private $entretienMessage;

    #[ORM\Column(type: 'text', nullable: true)]
    private $evaluationMessage;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dateEvaluation;

    #[ORM\Column(type: 'text', nullable: true)]
    private $selectionMessage;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $dateRejet;

    #[ORM\Column(type: 'text', nullable: true)]
    private $rejetMessage;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $acceptationDate;

    #[ORM\Column(type: 'text', nullable: true)]
    private $acceptationMessage;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private $statusColor;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->conversations = new ArrayCollection();
    }

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

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): self
    {
        $this->cv = $cv;

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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

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

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $cvFile
     */
    public function setCvFile(?File $cvFile = null): void
    {
        $this->cvFile = $cvFile;

        if (null !== $cvFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setUpdated(new \DateTimeImmutable());
        }
    }

    public function getCvFile(): ?File
    {
        return $this->cvFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $lettreMotivationFile
     */
    public function setLettreMotivationFile(?File $lettreMotivationFile = null): void
    {
        $this->lettreMotivationFile = $lettreMotivationFile;

        if (null !== $lettreMotivationFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setUpdated(new \DateTimeImmutable());
        }
    }

    public function getLettreMotivationFile(): ?File
    {
        return $this->lettreMotivationFile;
    }

    public function getCandidatPresentation(): ?string
    {
        return $this->candidatPresentation;
    }

    public function setCandidatPresentation(string $candidatPresentation): self
    {
        $this->candidatPresentation = $candidatPresentation;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

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
            $message->setCandidature($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getCandidature() === $this) {
                $message->setCandidature(null);
            }
        }

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
            $conversation->setCandidature($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getCandidature() === $this) {
                $conversation->setCandidature(null);
            }
        }

        return $this;
    }

    public function getEntretien(): ?string
    {
        return $this->entretien;
    }

    public function setEntretien(?string $entretien): self
    {
        $this->entretien = $entretien;

        return $this;
    }

    public function getLettreMotivation(): ?string
    {
        return $this->lettreMotivation;
    }

    public function setLettreMotivation(?string $lettreMotivation): self
    {
        $this->lettreMotivation = $lettreMotivation;

        return $this;
    }

    public function getDateEntretien(): ?\DateTimeInterface
    {
        return $this->dateEntretien;
    }

    public function setDateEntretien(?\DateTimeInterface $dateEntretien): self
    {
        $this->dateEntretien = $dateEntretien;

        return $this;
    }

    public function getDateSelection(): ?\DateTimeInterface
    {
        return $this->dateSelection;
    }

    public function setDateSelection(?\DateTimeInterface $dateSelection): self
    {
        $this->dateSelection = $dateSelection;

        return $this;
    }

    public function getDateTrie(): ?\DateTimeInterface
    {
        return $this->dateTrie;
    }

    public function setDateTrie(?\DateTimeInterface $dateTrie): self
    {
        $this->dateTrie = $dateTrie;

        return $this;
    }

    public function getEntretienMessage(): ?string
    {
        return $this->entretienMessage;
    }

    public function setEntretienMessage(?string $entretienMessage): self
    {
        $this->entretienMessage = $entretienMessage;

        return $this;
    }

    public function getEvaluationMessage(): ?string
    {
        return $this->evaluationMessage;
    }

    public function setEvaluationMessage(?string $evaluationMessage): self
    {
        $this->evaluationMessage = $evaluationMessage;

        return $this;
    }

    public function getDateEvaluation(): ?\DateTimeInterface
    {
        return $this->dateEvaluation;
    }

    public function setDateEvaluation(?\DateTimeInterface $dateEvaluation): self
    {
        $this->dateEvaluation = $dateEvaluation;

        return $this;
    }

    public function getSelectionMessage(): ?string
    {
        return $this->selectionMessage;
    }

    public function setSelectionMessage(?string $selectionMessage): self
    {
        $this->selectionMessage = $selectionMessage;

        return $this;
    }

    public function getDateRejet(): ?\DateTimeInterface
    {
        return $this->dateRejet;
    }

    public function setDateRejet(?\DateTimeInterface $dateRejet): self
    {
        $this->dateRejet = $dateRejet;

        return $this;
    }

    public function getRejetMessage(): ?string
    {
        return $this->rejetMessage;
    }

    public function setRejetMessage(?string $rejetMessage): self
    {
        $this->rejetMessage = $rejetMessage;

        return $this;
    }

    public function getAcceptationDate(): ?\DateTimeInterface
    {
        return $this->acceptationDate;
    }

    public function setAcceptationDate(?\DateTimeInterface $acceptationDate): self
    {
        $this->acceptationDate = $acceptationDate;

        return $this;
    }

    public function getAcceptationMessage(): ?string
    {
        return $this->acceptationMessage;
    }

    public function setAcceptationMessage(?string $acceptationMessage): self
    {
        $this->acceptationMessage = $acceptationMessage;

        return $this;
    }

    public function getStatusColor(): ?string
    {
        return $this->statusColor;
    }

    public function setStatusColor(?string $statusColor): self
    {
        $this->statusColor = $statusColor;

        return $this;
    }
}
