<?php

namespace App\Entity;

use App\Repository\EffectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EffectRepository::class)]
class Effect
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $poste = null;

    #[ORM\Column]
    private ?int $num_cin = null;

    #[ORM\ManyToOne(inversedBy: 'effects')]
    private ?RessourcesHumaines $id_ref = null;

    /**
     * @var Collection<int, Poste>
     */
    #[ORM\OneToMany(targetEntity: Poste::class, mappedBy: 'poste_e')]
    private Collection $postes;

    #[ORM\Column(length: 255)]
    private ?string $image_effect = null;

    #[ORM\Column(length: 255)]
    private ?string $localisation = null;

    // Si tu veux que latitude et longitude soient persistés en base, décommente ces lignes
    // #[ORM\Column(type: 'float', nullable: true)]
    // private ?float $latitude = null;

    // #[ORM\Column(type: 'float', nullable: true)]
    // private ?float $longitude = null;

    public function __construct()
    {
        $this->postes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): static
    {
        $this->poste = $poste;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getNumCin(): ?int
    {
        return $this->num_cin;
    }

    public function setNumCin(int $num_cin): static
    {
        $this->num_cin = $num_cin;

        return $this;
    }

    public function getIdRef(): ?RessourcesHumaines
    {
        return $this->id_ref;
    }

    public function setIdRef(?RessourcesHumaines $id_ref): static
    {
        $this->id_ref = $id_ref;

        return $this;
    }

    /**
     * @return Collection<int, Poste>
     */
    public function getPostes(): Collection
    {
        return $this->postes;
    }

    public function addPoste(Poste $poste): static
    {
        if (!$this->postes->contains($poste)) {
            $this->postes->add($poste);
            $poste->setPosteE($this);
        }

        return $this;
    }

    public function removePoste(Poste $poste): static
    {
        if ($this->postes->removeElement($poste)) {
            if ($poste->getPosteE() === $this) {
                $poste->setPosteE(null);
            }
        }

        return $this;
    }

    public function getImageEffect(): ?string
    {
        return $this->image_effect;
    }

    public function setImageEffect(string $image_effect): static
    {
        $this->image_effect = $image_effect;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    // Méthodes utiles pour extraire latitude et longitude depuis la chaîne localisation

    public function getLatitude(): ?float
    {
        if (!$this->localisation) {
            return null;
        }
        $parts = explode(',', $this->localisation);
        return isset($parts[0]) ? (float) trim($parts[0]) : null;
    }

    public function getLongitude(): ?float
    {
        if (!$this->localisation) {
            return null;
        }
        $parts = explode(',', $this->localisation);
        return isset($parts[1]) ? (float) trim($parts[1]) : null;
    }
}
