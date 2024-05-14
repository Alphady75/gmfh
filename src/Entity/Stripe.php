<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use App\Repository\StripeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StripeRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Stripe
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $name;

    #[ORM\Column(type: 'float', nullable: true)]
    private $tarif;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $stripeKey;

    #[ORM\Column(type: 'string', length: 7, nullable: true)]
    private $hexaColor;

    #[ORM\Column(type: 'text', nullable: true)]
    private $description;

    #[ORM\OneToMany(mappedBy: 'Stripe', targetEntity: Abonnement::class)]
    private $abonnements;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private $typeTarification;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $recommanded;

    #[ORM\OneToMany(mappedBy: 'stripe', targetEntity: Composants::class)]
    private $composants;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $complet;

    #[ORM\Column(type: 'string', length: 10)]
    private $devise;

    public function __construct()
    {
        $this->abonnements = new ArrayCollection();
        $this->composants = new ArrayCollection();
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

    public function getTarif(): ?float
    {
        return $this->tarif;
    }

    public function setTarif(?float $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getStripeKey(): ?string
    {
        return $this->stripeKey;
    }

    public function setStripeKey(?string $stripeKey): self
    {
        $this->stripeKey = $stripeKey;

        return $this;
    }

    public function getHexaColor(): ?string
    {
        return $this->hexaColor;
    }

    public function setHexaColor(?string $hexaColor): self
    {
        $this->hexaColor = $hexaColor;

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

    /**
     * @return Collection|Abonnement[]
     */
    public function getAbonnements(): Collection
    {
        return $this->abonnements;
    }

    public function addAbonnement(Abonnement $abonnement): self
    {
        if (!$this->abonnements->contains($abonnement)) {
            $this->abonnements[] = $abonnement;
            $abonnement->setStripe($this);
        }

        return $this;
    }

    public function removeAbonnement(Abonnement $abonnement): self
    {
        if ($this->abonnements->removeElement($abonnement)) {
            // set the owning side to null (unless already changed)
            if ($abonnement->getStripe() === $this) {
                $abonnement->setStripe(null);
            }
        }

        return $this;
    }

    public function getTypeTarification(): ?string
    {
        return $this->typeTarification;
    }

    public function setTypeTarification(?string $typeTarification): self
    {
        $this->typeTarification = $typeTarification;

        return $this;
    }

    public function getRecommanded(): ?bool
    {
        return $this->recommanded;
    }

    public function setRecommanded(?bool $recommanded): self
    {
        $this->recommanded = $recommanded;

        return $this;
    }

    /**
     * @return Collection|Composants[]
     */
    public function getComposants(): Collection
    {
        return $this->composants;
    }

    public function addComposant(Composants $composant): self
    {
        if (!$this->composants->contains($composant)) {
            $this->composants[] = $composant;
            $composant->setStripe($this);
        }

        return $this;
    }

    public function removeComposant(Composants $composant): self
    {
        if ($this->composants->removeElement($composant)) {
            // set the owning side to null (unless already changed)
            if ($composant->getStripe() === $this) {
                $composant->setStripe(null);
            }
        }

        return $this;
    }

    public function getComplet(): ?bool
    {
        return $this->complet;
    }

    public function setComplet(?bool $complet): self
    {
        $this->complet = $complet;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
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
}
