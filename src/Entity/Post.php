<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

    /**
     * @Vich\UploadableField(mapping="annonces", fileNameProperty="image")
     * @var File|null
     * @Assert\Image(maxSize="10M", maxSizeMessage="Image trop volumineuse maximum 10Mb")
     * @Assert\Image(mimeTypes = {"image/jpeg", "image/jpg", "image/png"}, mimeTypesMessage = "Mauvais format d'image (jpeg, jpg et png)")
    **/
    private $imageFile;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $image;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $tarif;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\Column(type: 'boolean')]
    private $online;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
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

    #[ORM\Column(type: 'string', length: 30)]
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

    public function __construct()
    {
        $this->avis = new ArrayCollection();
        $this->medias = new ArrayCollection();
        $this->boosts = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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
        return $this->getName();
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

    public function getImageFile(): ?File
    {
        return $this->imageFile;
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

    public function setDevise(string $devise): self
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
}
