<?php

namespace App\Entity;

use App\Repository\MaterielRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterielRepository::class)]
class Materiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_m = null;

    #[ORM\Column(length: 255)]
    private ?string $code_m = null;

    #[ORM\ManyToOne(inversedBy: 'materiels')]
    private ?RessourcesHumaines $ressource = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomM(): ?string
    {
        return $this->nom_m;
    }

    public function setNomM(string $nom_m): static
    {
        $this->nom_m = $nom_m;
        return $this;
    }

    public function getCodeM(): ?string
    {
        return $this->code_m;
    }

    public function setCodeM(string $code_m): static
    {
        $this->code_m = $code_m;
        return $this;
    }

    public function getRessource(): ?RessourcesHumaines
    {
        return $this->ressource;
    }

    public function setRessource(?RessourcesHumaines $ressource): static
    {
        $this->ressource = $ressource;
        return $this;
    }

    public function __toString(): string
    {
        return $this->nom_m ?? '';
    }
}
