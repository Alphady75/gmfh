<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cette adresse email')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
/**
 * @Vich\Uploadable
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Serializable
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private $telephone;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\Column(type: 'integer', length: 9, nullable: true)]
    private $codeIsVerified;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $resetPasswordCode;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private $compte;

    #[ORM\Column(type: 'string', length: 90, nullable: true)]
    private $nom;

    #[ORM\Column(type: 'string', length: 90, nullable: true)]
    private $prenom;

    /**
     * @Vich\UploadableField(mapping="userslogos", fileNameProperty="logo")
     * @var File|null
     * @Assert\Image(maxSize="10M", maxSizeMessage="Image trop volumineuse maximum 10Mb")
     * @Assert\Image(mimeTypes = {"image/jpeg", "image/jpg", "image/png"}, mimeTypesMessage = "Mauvais format d'image (jpeg, jpg et png)")
    **/
    private $logoFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $logo;

    /**
     * @Vich\UploadableField(mapping="usersphotos", fileNameProperty="photo")
     * @var File|null
     * @Assert\Image(maxSize="10M", maxSizeMessage="Image trop volumineuse maximum 10Mb")
     * @Assert\Image(mimeTypes = {"image/jpeg", "image/jpg", "image/png"}, mimeTypesMessage = "Mauvais format d'image (jpeg, jpg et png)")
    **/
    private $photoFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $photo;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $niu;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $localisation;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $nomResponsable;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $siteWeb;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Competence::class, cascade: ['persist'])]
    private Collection $competences;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Service::class, cascade: ['persist'])]
    private Collection $services;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Post::class, cascade: ['persist'])]
    private Collection $posts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Avis::class, cascade: ['persist'])]
    private Collection $avis;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Ville::class, cascade: ['persist'])]
    private Collection $villes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Realisation::class, cascade: ['persist'])]
    private Collection $realisations;

    /**
     * @Vich\UploadableField(mapping="userscv", fileNameProperty="cv")
     * @var File|null
    **/
    private $cvFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $cv;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Candidature::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $candidatures;

    #[ORM\Column(type: 'boolean')]
    private $completed;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Activite::class, orphanRemoval: true , cascade: ['persist'])]
    private $activites;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Offre::class, orphanRemoval: true , cascade: ['persist'])]
    private $offres;

    #[ORM\Column(type: 'text', nullable: true)]
    private $apropo;

    #[ORM\Column(type: 'string', length: 255)]
    private $nameSlug;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $societe;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Article::class, orphanRemoval: true , cascade: ['persist'])]
    private $articles;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Comment::class , cascade: ['persist'])]
    private $comments;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private $genre;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $qualification;

    #[ORM\Column(type: 'integer', length: 9, nullable: true)]
    private $anneeExperience;

    #[ORM\ManyToOne(targetEntity: SecteursActivite::class, inversedBy: 'users')]
    private $secteuractivite;

    #[ORM\ManyToOne(targetEntity: SousSecteursActivite::class, inversedBy: 'users')]
    private $soussecteuractivite;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $salaire;

    #[ORM\Column(type: 'string', length: 90, nullable: true)]
    private $periodicite;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private $devise;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $annuaire;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Langue::class)]
    private $langues;

    #[ORM\OneToMany(mappedBy: 'auteur', targetEntity: Message::class , cascade: ['persist'])]
    private $messages;

    #[ORM\OneToMany(mappedBy: 'user1', targetEntity: Conversation::class, orphanRemoval: true , cascade: ['persist'])]
    private $conversations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Favoris::class , cascade: ['persist'])]
    private $favoris;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Signaler::class, orphanRemoval: true , cascade: ['persist'])]
    private $signalers;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Abonnement::class, cascade: ['persist', 'remove'])]
    private $abonnement;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Experience::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private $experiences;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Etude::class, orphanRemoval: true, cascade: ['persist', 'remove'])]
    private $etudes;

    #[ORM\OneToMany(mappedBy: 'auteur', targetEntity: Avis::class, orphanRemoval: true, cascade: ['persist'])]
    private $auteuravis;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $facebook;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $twitter;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $linkedin;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $instagram;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $youtube;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $pinterest;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $tumblr;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $whatsapp;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Vue::class, cascade: ['persist'])]
    private $vues;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Alert::class, cascade: ['persist'])]
    private $alerts;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $siege;

    public function __construct()
    {
        $this->competences = new ArrayCollection();
        $this->services = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->avis = new ArrayCollection();
        $this->villes = new ArrayCollection();
        $this->realisations = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
        $this->activites = new ArrayCollection();
        $this->offres = new ArrayCollection();
        $this->articles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->langues = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->favoris = new ArrayCollection();
        $this->signalers = new ArrayCollection();
        $this->auteuravis = new ArrayCollection();
        $this->experiences = new ArrayCollection();
        $this->etudes = new ArrayCollection();
        $this->vues = new ArrayCollection();
        $this->alerts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function serialize() {

        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
        ));

    }

    public function unserialize($serialized) {

        list (
            $this->id,
            $this->email,
            $this->password,
        ) = unserialize($serialized);
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getCodeIsVerified(): ?int
    {
        return $this->codeIsVerified;
    }

    public function setCodeIsVerified(?int $codeIsVerified): self
    {
        $this->codeIsVerified = $codeIsVerified;

        return $this;
    }

    public function getResetPasswordCode(): ?int
    {
        return $this->resetPasswordCode;
    }

    public function setResetPasswordCode(?int $resetPasswordCode): self
    {
        $this->resetPasswordCode = $resetPasswordCode;

        return $this;
    }

    public function getCompte(): ?string
    {
        return $this->compte;
    }

    public function setCompte(?string $compte): self
    {
        $this->compte = $compte;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $logoFile
     */
    public function setLogoFile(?File $logoFile = null): void
    {
        $this->logoFile = $logoFile;

        if (null !== $logoFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setUpdated(new \DateTimeImmutable());
        }
    }

    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $photoFile
     */
    public function setPhotoFile(?File $photoFile = null): void
    {
        $this->photoFile = $photoFile;

        if (null !== $photoFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setUpdated(new \DateTimeImmutable());
        }
    }

    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }

    public function getNiu(): ?string
    {
        return $this->niu;
    }

    public function setNiu(?string $niu): self
    {
        $this->niu = $niu;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getNomResponsable(): ?string
    {
        return $this->nomResponsable;
    }

    public function setNomResponsable(?string $nomResponsable): self
    {
        $this->nomResponsable = $nomResponsable;

        return $this;
    }

    public function getSiteWeb(): ?string
    {
        return $this->siteWeb;
    }

    public function setSiteWeb(?string $siteWeb): self
    {
        $this->siteWeb = $siteWeb;

        return $this;
    }

    /**
     * @return Collection|Competence[]
     */
    public function getCompetences(): Collection
    {
        return $this->competences;
    }

    public function addCompetence(Competence $competence): self
    {
        if (!$this->competences->contains($competence)) {
            $this->competences[] = $competence;
            $competence->setUser($this);
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): self
    {
        if ($this->competences->removeElement($competence)) {
            // set the owning side to null (unless already changed)
            if ($competence->getUser() === $this) {
                $competence->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Service[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setUser($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getUser() === $this) {
                $service->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Avis[]
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis[] = $avi;
            $avi->setUser($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getUser() === $this) {
                $avi->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Ville[]
     */
    public function getVilles(): Collection
    {
        return $this->villes;
    }

    public function addVille(Ville $ville): self
    {
        if (!$this->villes->contains($ville)) {
            $this->villes[] = $ville;
            $ville->setUser($this);
        }

        return $this;
    }

    public function removeVille(Ville $ville): self
    {
        if ($this->villes->removeElement($ville)) {
            // set the owning side to null (unless already changed)
            if ($ville->getUser() === $this) {
                $ville->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Realisation[]
     */
    public function getRealisations(): Collection
    {
        return $this->realisations;
    }

    public function addRealisation(Realisation $realisation): self
    {
        if (!$this->realisations->contains($realisation)) {
            $this->realisations[] = $realisation;
            $realisation->setUser($this);
        }

        return $this;
    }

    public function removeRealisation(Realisation $realisation): self
    {
        if ($this->realisations->removeElement($realisation)) {
            // set the owning side to null (unless already changed)
            if ($realisation->getUser() === $this) {
                $realisation->setUser(null);
            }
        }

        return $this;
    }

    public function getCompleted(): ?bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): self
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * @return Collection|Candidature[]
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): self
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures[] = $candidature;
            $candidature->setUser($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getUser() === $this) {
                $candidature->setUser(null);
            }
        }

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
        return $this->photoFile;
    }

    public function __toString()
    {
        return $this->getNom() . ' ' . $this->getPrenom();
    }

    /**
     * @return Collection|Activite[]
     */
    public function getActivites(): Collection
    {
        return $this->activites;
    }

    public function addActivite(Activite $activite): self
    {
        if (!$this->activites->contains($activite)) {
            $this->activites[] = $activite;
            $activite->setUser($this);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): self
    {
        if ($this->activites->removeElement($activite)) {
            // set the owning side to null (unless already changed)
            if ($activite->getUser() === $this) {
                $activite->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Offre[]
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): self
    {
        if (!$this->offres->contains($offre)) {
            $this->offres[] = $offre;
            $offre->setUser($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getUser() === $this) {
                $offre->setUser(null);
            }
        }

        return $this;
    }

    public function getApropo(): ?string
    {
        return $this->apropo;
    }

    public function setApropo(?string $apropo): self
    {
        $this->apropo = $apropo;

        return $this;
    }

    public function getNameSlug(): ?string
    {
        return $this->nameSlug;
    }

    public function setNameSlug(string $nameSlug): self
    {
        $this->nameSlug = $nameSlug;

        return $this;
    }

    public function getSociete(): ?string
    {
        return $this->societe;
    }

    public function setSociete(?string $societe): self
    {
        $this->societe = $societe;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setUser($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getUser() === $this) {
                $article->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getQualification(): ?string
    {
        return $this->qualification;
    }

    public function setQualification(?string $qualification): self
    {
        $this->qualification = $qualification;

        return $this;
    }

    public function getAnneeExperience(): ?int
    {
        return $this->anneeExperience;
    }

    public function setAnneeExperience(?int $anneeExperience): self
    {
        $this->anneeExperience = $anneeExperience;

        return $this;
    }

    public function getSecteuractivite(): ?SecteursActivite
    {
        return $this->secteuractivite;
    }

    public function setSecteuractivite(?SecteursActivite $secteuractivite): self
    {
        $this->secteuractivite = $secteuractivite;

        return $this;
    }

    public function getSoussecteuractivite(): ?SousSecteursActivite
    {
        return $this->soussecteuractivite;
    }

    public function setSoussecteuractivite(?SousSecteursActivite $soussecteuractivite): self
    {
        $this->soussecteuractivite = $soussecteuractivite;

        return $this;
    }

    public function getSalaire(): ?int
    {
        return $this->salaire;
    }

    public function setSalaire(?int $salaire): self
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getPeriodicite(): ?string
    {
        return $this->periodicite;
    }

    public function setPeriodicite(?string $periodicite): self
    {
        $this->periodicite = $periodicite;

        return $this;
    }

    public function getDevise(): ?string
    {
        return $this->devise;
    }

    public function setDevise(?string $devise): self
    {
        $this->devise = $devise;

        return $this;
    }

    public function getAnnuaire(): ?bool
    {
        return $this->annuaire;
    }

    public function setAnnuaire(?bool $annuaire): self
    {
        $this->annuaire = $annuaire;

        return $this;
    }

    /**
     * @return Collection|Langue[]
     */
    public function getLangues(): Collection
    {
        return $this->langues;
    }

    public function addLangue(Langue $langue): self
    {
        if (!$this->langues->contains($langue)) {
            $this->langues[] = $langue;
            $langue->setUser($this);
        }

        return $this;
    }

    public function removeLangue(Langue $langue): self
    {
        if ($this->langues->removeElement($langue)) {
            // set the owning side to null (unless already changed)
            if ($langue->getUser() === $this) {
                $langue->setUser(null);
            }
        }

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
            $message->setAuteur($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getAuteur() === $this) {
                $message->setAuteur(null);
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
            $conversation->setUser1($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getUser1() === $this) {
                $conversation->setUser1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Favoris[]
     */
    public function getFavoris(): Collection
    {
        return $this->favoris;
    }

    public function addFavori(Favoris $favori): self
    {
        if (!$this->favoris->contains($favori)) {
            $this->favoris[] = $favori;
            $favori->setUser($this);
        }

        return $this;
    }

    public function removeFavori(Favoris $favori): self
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getUser() === $this) {
                $favori->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Signaler[]
     */
    public function getSignalers(): Collection
    {
        return $this->signalers;
    }

    public function addSignaler(Signaler $signaler): self
    {
        if (!$this->signalers->contains($signaler)) {
            $this->signalers[] = $signaler;
            $signaler->setUser($this);
        }

        return $this;
    }

    public function removeSignaler(Signaler $signaler): self
    {
        if ($this->signalers->removeElement($signaler)) {
            // set the owning side to null (unless already changed)
            if ($signaler->getUser() === $this) {
                $signaler->setUser(null);
            }
        }

        return $this;
    }

    public function getAbonnement(): ?Abonnement
    {
        return $this->abonnement;
    }

    public function setAbonnement(Abonnement $abonnement): self
    {
        // set the owning side of the relation if necessary
        if ($abonnement->getUser() !== $this) {
            $abonnement->setUser($this);
        }

        $this->abonnement = $abonnement;

        return $this;
    }

    /**
     * @return Collection|Avis[]
     */
    public function getAuteuravis(): Collection
    {
        return $this->auteuravis;
    }

    public function addAuteuravi(Avis $auteuravi): self
    {
        if (!$this->auteuravis->contains($auteuravi)) {
            $this->auteuravis[] = $auteuravi;
            $auteuravi->setAuteur($this);
        }

        return $this;
    }

    public function removeAuteuravi(Avis $auteuravi): self
    {
        if ($this->auteuravis->removeElement($auteuravi)) {
            // set the owning side to null (unless already changed)
            if ($auteuravi->getAuteur() === $this) {
                $auteuravi->setAuteur(null);
            }
        }

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(?string $linkedin): self
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function getYoutube(): ?string
    {
        return $this->youtube;
    }

    public function setYoutube(?string $youtube): self
    {
        $this->youtube = $youtube;

        return $this;
    }

    public function getPinterest(): ?string
    {
        return $this->pinterest;
    }

    public function setPinterest(?string $pinterest): self
    {
        $this->pinterest = $pinterest;

        return $this;
    }

    public function getTumblr(): ?string
    {
        return $this->tumblr;
    }

    public function setTumblr(?string $tumblr): self
    {
        $this->tumblr = $tumblr;

        return $this;
    }

    public function getWhatsapp(): ?string
    {
        return $this->whatsapp;
    }

    public function setWhatsapp(?string $whatsapp): self
    {
        $this->whatsapp = $whatsapp;

        return $this;
    }

    /**
     * @return Collection|Experience[]
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): self
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences[] = $experience;
            $experience->setUser($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): self
    {
        if ($this->experiences->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getUser() === $this) {
                $experience->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Etude[]
     */
    public function getEtudes(): Collection
    {
        return $this->etudes;
    }

    public function addEtude(Etude $etude): self
    {
        if (!$this->etudes->contains($etude)) {
            $this->etudes[] = $etude;
            $etude->setUser($this);
        }

        return $this;
    }

    public function removeEtude(Etude $etude): self
    {
        if ($this->etudes->removeElement($etude)) {
            // set the owning side to null (unless already changed)
            if ($etude->getUser() === $this) {
                $etude->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Vue[]
     */
    public function getVues(): Collection
    {
        return $this->vues;
    }

    public function addVue(Vue $vue): self
    {
        if (!$this->vues->contains($vue)) {
            $this->vues[] = $vue;
            $vue->setUser($this);
        }

        return $this;
    }

    public function removeVue(Vue $vue): self
    {
        if ($this->vues->removeElement($vue)) {
            // set the owning side to null (unless already changed)
            if ($vue->getUser() === $this) {
                $vue->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Alert[]
     */
    public function getAlerts(): Collection
    {
        return $this->alerts;
    }

    public function addAlert(Alert $alert): self
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts[] = $alert;
            $alert->setUser($this);
        }

        return $this;
    }

    public function removeAlert(Alert $alert): self
    {
        if ($this->alerts->removeElement($alert)) {
            // set the owning side to null (unless already changed)
            if ($alert->getUser() === $this) {
                $alert->setUser(null);
            }
        }

        return $this;
    }

    public function getSiege(): ?string
    {
        return $this->siege;
    }

    public function setSiege(?string $siege): self
    {
        $this->siege = $siege;

        return $this;
    }

    /**
     * Verifi si ce microcervice a déjà ajouter en favoris par un utilisateur
     *
     * @param User $user
     * @return boolean
     */
    public function isAddedByUser(User $user){
        foreach($this->favoris as $favori){
            if($favori->getUser() === $user) return true;
        }

        return false;
    }
}
