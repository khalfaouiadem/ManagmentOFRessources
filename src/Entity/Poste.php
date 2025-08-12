<?php

namespace App\Entity;

use App\Repository\PosteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PosteRepository::class)]
class Poste
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_P = null;

    #[ORM\Column(length: 255)]
    private ?string $Code_P = null;

    #[ORM\ManyToOne(inversedBy: 'postes')]
    private ?Effect $poste_e = null;

    #[ORM\Column(length: 255)]
    private ?string $image_poste = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomP(): ?string
    {
        return $this->nom_P;
    }

    public function setNomP(string $nom_P): static
    {
        $this->nom_P = $nom_P;

        return $this;
    }

    public function getCodeP(): ?string
    {
        return $this->Code_P;
    }

    public function setCodeP(string $Code_P): static
    {
        $this->Code_P = $Code_P;

        return $this;
    }

    public function getPosteE(): ?Effect
    {
        return $this->poste_e;
    }

    public function setPosteE(?Effect $poste_e): static
    {
        $this->poste_e = $poste_e;

        return $this;
    }

    public function getImagePoste(): ?string
    {
        return $this->image_poste;
    }

    public function setImagePoste(string $image_poste): static
    {
        $this->image_poste = $image_poste;

        return $this;
    }
}
