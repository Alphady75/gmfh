<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'Cette annonce existe déjà')]
#[ORM\HasLifecycleCallbacks]
/**
 * @Vich\Uploadable
 */
class Post
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $slug;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $tarif;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'boolean')]
    private $online;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[JoinColumn(onDelete: 'CASCADE')]
    private $user;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Avis::class, cascade: ['persist'])]
    private Collection $avis;

    #[ORM\ManyToOne(targetEntity: SousCategorie::class, inversedBy: 'posts')]
    private $souscategorie;

    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'posts')]
    private $categorie;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Media::class, orphanRemoval: true, cascade: ['persist'])]
    private $medias;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private $etat;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $negociable;

    #[ORM\Column(type: 'boolean')]
    private $livraison;

    #[ORM\Column(type: 'boolean')]
    private $promo;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $vedette;

    #[ORM\Column(type: 'string', length: 90, nullable: true)]
    private $statut;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $tarifPromo;

    #[ORM\Column(type: 'boolean')]
    private $boosted;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private $devise;

    #[ORM\ManyToOne(targetEntity: Booster::class, inversedBy: 'posts')]
    private $booster;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Boost::class, cascade: ['persist'])]
    private Collection $boosts;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $urgent;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isSelled;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $sellPlateform;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Favoris::class, cascade: ['persist'])]
    private $favoris;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Signaler::class, cascade: ['persist'])]
    private $signalers;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $bloquer;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $bloquerAt;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Conversation::class, cascade: ['persist'])]
    private $conversations;

    #[ORM\OneToMany(mappedBy: 'posts', targetEntity: Realisation::class, cascade: ['persist'])]
    private Collection $realisations;

    /**
     * @Vich\UploadableField(mapping="annonces", fileNameProperty="image")
     * @var File|null
     * @Assert\Image(maxSize="10M", maxSizeMessage="Image trop volumineuse maximum 10Mb")
     * @Assert\Image(mimeTypes = {"image/jpeg", "image/jpg", "image/png"}, mimeTypesMessage = "Mauvais format d'image (jpeg, jpg et png)")
    **/
    private $imageFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $image;

    /**
     * @Vich\UploadableField(mapping="annonces", fileNameProperty="imageDeux")
     * @var File|null
     * @Assert\Image(maxSize="10M", maxSizeMessage="Image trop volumineuse maximum 10Mb")
     * @Assert\Image(mimeTypes = {"image/jpeg", "image/jpg", "image/png"}, mimeTypesMessage = "Mauvais format d'image (jpeg, jpg et png)")
    **/
    private $imageDeuxFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imageDeux;

    /**
     * @Vich\UploadableField(mapping="annonces", fileNameProperty="imageTrois")
     * @var File|null
     * @Assert\Image(maxSize="10M", maxSizeMessage="Image trop volumineuse maximum 10Mb")
     * @Assert\Image(mimeTypes = {"image/jpeg", "image/jpg", "image/png"}, mimeTypesMessage = "Mauvais format d'image (jpeg, jpg et png)")
    **/
    private $imageTroisFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imageTrois;

    public function __construct()
    {
        $this->avis = new ArrayCollection();
        $this->medias = new ArrayCollection();
        $this->boosts = new ArrayCollection();
        $this->favoris = new ArrayCollection();
        $this->signalers = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->realisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTarif(): ?int
    {
        return $this->tarif;
    }

    public function setTarif(?int $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(?bool $online): self
    {
        $this->online = $online;

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
            $avi->setPost($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getPost() === $this) {
                $avi->setPost(null);
            }
        }

        return $this;
    }

    public function getSouscategorie(): ?SousCategorie
    {
        return $this->souscategorie;
    }

    public function setSouscategorie(?SousCategorie $souscategorie): self
    {
        $this->souscategorie = $souscategorie;

        return $this;
    }

    public function __toString()
    {
        return $this->getName() ?? '';
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setUpdated(new \DateTimeImmutable());
        }
    }

    public function getImageDeuxFile(): ?File
    {
        return $this->imageDeuxFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageDeuxFile
     */
    public function setImageDeuxFile(?File $imageDeuxFile = null): void
    {
        $this->imageDeuxFile = $imageDeuxFile;

        if (null !== $imageDeuxFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setUpdated(new \DateTimeImmutable());
        }
    }

    public function getImageTroisFile(): ?File
    {
        return $this->imageTroisFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageTroisFile
     */
    public function setImageTroisFile(?File $imageTroisFile = null): void
    {
        $this->imageTroisFile = $imageTroisFile;

        if (null !== $imageTroisFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setUpdated(new \DateTimeImmutable());
        }
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Media[]
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addMedia(Media $media): self
    {
        if (!$this->medias->contains($media)) {
            $this->medias[] = $media;
            $media->setPost($this);
        }

        return $this;
    }

    public function removeMedia(Media $media): self
    {
        if ($this->medias->removeElement($media)) {
            // set the owning side to null (unless already changed)
            if ($media->getPost() === $this) {
                $media->setPost(null);
            }
        }

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

    public function getNegociable(): ?bool
    {
        return $this->negociable;
    }

    public function setNegociable(?bool $negociable): self
    {
        $this->negociable = $negociable;

        return $this;
    }

    public function getlivraison(): ?bool
    {
        return $this->livraison;
    }

    public function setlivraison(bool $livraison): self
    {
        $this->livraison = $livraison;

        return $this;
    }

    public function getPromo(): ?bool
    {
        return $this->promo;
    }

    public function setPromo(bool $promo): self
    {
        $this->promo = $promo;

        return $this;
    }

    public function getVedette(): ?bool
    {
        return $this->vedette;
    }

    public function setVedette(?bool $vedette): self
    {
        $this->vedette = $vedette;

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

    public function getTarifPromo(): ?int
    {
        return $this->tarifPromo;
    }

    public function setTarifPromo(?int $tarifPromo): self
    {
        $this->tarifPromo = $tarifPromo;

        return $this;
    }

    public function getBoosted(): ?bool
    {
        return $this->boosted;
    }

    public function setBoosted(bool $boosted): self
    {
        $this->boosted = $boosted;

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

    public function getBooster(): ?Booster
    {
        return $this->booster;
    }

    public function setBooster(?Booster $booster): self
    {
        $this->booster = $booster;

        return $this;
    }

    /**
     * @return Collection|Boost[]
     */
    public function getBoosts(): Collection
    {
        return $this->boosts;
    }

    public function addBoost(Boost $boost): self
    {
        if (!$this->boosts->contains($boost)) {
            $this->boosts[] = $boost;
            $boost->setPost($this);
        }

        return $this;
    }

    public function removeBoost(Boost $boost): self
    {
        if ($this->boosts->removeElement($boost)) {
            // set the owning side to null (unless already changed)
            if ($boost->getPost() === $this) {
                $boost->setPost(null);
            }
        }

        return $this;
    }

    public function getUrgent(): ?bool
    {
        return $this->urgent;
    }

    public function setUrgent(?bool $urgent): self
    {
        $this->urgent = $urgent;

        return $this;
    }

    public function getIsSelled(): ?bool
    {
        return $this->isSelled;
    }

    public function setIsSelled(?bool $isSelled): self
    {
        $this->isSelled = $isSelled;

        return $this;
    }

    public function getSellPlateform(): ?string
    {
        return $this->sellPlateform;
    }

    public function setSellPlateform(?string $sellPlateform): self
    {
        $this->sellPlateform = $sellPlateform;

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
            $favori->setPost($this);
        }

        return $this;
    }

    public function removeFavori(Favoris $favori): self
    {
        if ($this->favoris->removeElement($favori)) {
            // set the owning side to null (unless already changed)
            if ($favori->getPost() === $this) {
                $favori->setPost(null);
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
            $signaler->setPost($this);
        }

        return $this;
    }

    public function removeSignaler(Signaler $signaler): self
    {
        if ($this->signalers->removeElement($signaler)) {
            // set the owning side to null (unless already changed)
            if ($signaler->getPost() === $this) {
                $signaler->setPost(null);
            }
        }

        return $this;
    }

    /**
     * Verifi si cette a déjà ajouter en favoris par un utilisateur
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

    public function getBloquer(): ?bool
    {
        return $this->bloquer;
    }

    public function setBloquer(?bool $bloquer): self
    {
        $this->bloquer = $bloquer;

        return $this;
    }

    public function getBloquerAt(): ?\DateTimeInterface
    {
        return $this->bloquerAt;
    }

    public function setBloquerAt(?\DateTimeInterface $bloquerAt): self
    {
        $this->bloquerAt = $bloquerAt;

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
            $conversation->setPost($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getPost() === $this) {
                $conversation->setPost(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImageDeux(): ?string
    {
        return $this->imageDeux;
    }

    public function setImageDeux(?string $imageDeux): self
    {
        $this->imageDeux = $imageDeux;

        return $this;
    }

    public function getImageTrois(): ?string
    {
        return $this->imageTrois;
    }

    public function setImageTrois(?string $imageTrois): self
    {
        $this->imageTrois = $imageTrois;

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
            $realisation->setPosts($this);
        }

        return $this;
    }

    public function removeRealisation(Realisation $realisation): self
    {
        if ($this->realisations->removeElement($realisation)) {
            // set the owning side to null (unless already changed)
            if ($realisation->getPosts() === $this) {
                $realisation->setPosts(null);
            }
        }

        return $this;
    }
}
