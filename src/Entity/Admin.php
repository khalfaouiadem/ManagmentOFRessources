<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
class Admin
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
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $id_ref = null;

    /**
     * @var Collection<int, RessourcesHumaines>
     */
    #[ORM\OneToMany(targetEntity: RessourcesHumaines::class, mappedBy: 'id_ref')]
    private Collection $ressourcesHumaines;

    public function __construct()
    {
        $this->ressourcesHumaines = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getIdRef(): ?string
    {
        return $this->id_ref;
    }

    public function setIdRef(?string $id_ref): static
    {
        $this->id_ref = $id_ref;
        return $this;
    }

    public function getRessourcesHumaines(): Collection
    {
        return $this->ressourcesHumaines;
    }

    public function addRessourcesHumaine(RessourcesHumaines $ressourcesHumaine): static
    {
        if (!$this->ressourcesHumaines->contains($ressourcesHumaine)) {
            $this->ressourcesHumaines->add($ressourcesHumaine);
            $ressourcesHumaine->setIdRef($this);
        }
        return $this;
    }

    public function removeRessourcesHumaine(RessourcesHumaines $ressourcesHumaine): static
    {
        if ($this->ressourcesHumaines->removeElement($ressourcesHumaine)) {
            if ($ressourcesHumaine->getIdRef() === $this) {
                $ressourcesHumaine->setIdRef(null);
            }
        }
        return $this;
    }

    public function __toString(): string
    {
        return $this->nom . ' ' . $this->prenom;
    }
}
