<?php

namespace App\Entity;

use App\Repository\RessourcesHumainesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RessourcesHumainesRepository::class)]
class RessourcesHumaines
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\ManyToOne(inversedBy: 'ressourcesHumaines')]
    private ?Admin $id_ref = null;

    /**
     * @var Collection<int, Effect>
     */
    #[ORM\OneToMany(targetEntity: Effect::class, mappedBy: 'id_ref')]
    private Collection $effects;

    /**
     * @var Collection<int, Materiel>
     */
    #[ORM\OneToMany(targetEntity: Materiel::class, mappedBy: 'ressource')]
    private Collection $materiels;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    public function __construct()
    {
        $this->effects = new ArrayCollection();
        $this->materiels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;
        return $this;
    }

    public function getIdRef(): ?Admin
    {
        return $this->id_ref;
    }

    public function setIdRef(?Admin $id_ref): static
    {
        $this->id_ref = $id_ref;
        return $this;
    }

    public function getEffects(): Collection
    {
        return $this->effects;
    }

    public function addEffect(Effect $effect): static
    {
        if (!$this->effects->contains($effect)) {
            $this->effects->add($effect);
            $effect->setIdRef($this);
        }
        return $this;
    }

    public function removeEffect(Effect $effect): static
    {
        if ($this->effects->removeElement($effect) && $effect->getIdRef() === $this) {
            $effect->setIdRef(null);
        }
        return $this;
    }

    public function getMateriels(): Collection
    {
        return $this->materiels;
    }

    public function addMateriel(Materiel $materiel): static
    {
        if (!$this->materiels->contains($materiel)) {
            $this->materiels->add($materiel);
            $materiel->setRessource($this);
        }
        return $this;
    }

    public function removeMateriel(Materiel $materiel): static
    {
        if ($this->materiels->removeElement($materiel) && $materiel->getRessource() === $this) {
            $materiel->setRessource(null);
        }
        return $this;
    }

    public function __toString(): string
    {
        return $this->code ?? '';
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
