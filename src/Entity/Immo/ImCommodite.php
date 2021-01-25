<?php

namespace App\Entity\Immo;

use App\Repository\Immo\ImCommoditeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImCommoditeRepository::class)
 */
class ImCommodite
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $hasAscenseur;

    /**
     * @ORM\Column(type="integer")
     */
    private $hasCave;

    /**
     * @ORM\Column(type="integer")
     */
    private $hasInterphone;

    /**
     * @ORM\Column(type="integer")
     */
    private $hasGardien;

    /**
     * @ORM\Column(type="integer")
     */
    private $hasTerrasse;

    /**
     * @ORM\Column(type="integer")
     */
    private $hasClim;

    /**
     * @ORM\Column(type="integer")
     */
    private $hasPiscine;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbParking;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbBox;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHasAscenseur(): ?int
    {
        return $this->hasAscenseur;
    }

    public function setHasAscenseur(int $hasAscenseur): self
    {
        $this->hasAscenseur = $hasAscenseur;

        return $this;
    }

    public function getHasCave(): ?int
    {
        return $this->hasCave;
    }

    public function setHasCave(int $hasCave): self
    {
        $this->hasCave = $hasCave;

        return $this;
    }

    public function getHasInterphone(): ?int
    {
        return $this->hasInterphone;
    }

    public function setHasInterphone(int $hasInterphone): self
    {
        $this->hasInterphone = $hasInterphone;

        return $this;
    }

    public function getHasGardien(): ?int
    {
        return $this->hasGardien;
    }

    public function setHasGardien(int $hasGardien): self
    {
        $this->hasGardien = $hasGardien;

        return $this;
    }

    public function getHasTerrasse(): ?int
    {
        return $this->hasTerrasse;
    }

    public function setHasTerrasse(int $hasTerrasse): self
    {
        $this->hasTerrasse = $hasTerrasse;

        return $this;
    }

    public function getHasClim(): ?int
    {
        return $this->hasClim;
    }

    public function setHasClim(int $hasClim): self
    {
        $this->hasClim = $hasClim;

        return $this;
    }

    public function getHasPiscine(): ?int
    {
        return $this->hasPiscine;
    }

    public function setHasPiscine(int $hasPiscine): self
    {
        $this->hasPiscine = $hasPiscine;

        return $this;
    }

    public function getNbParking(): ?int
    {
        return $this->nbParking;
    }

    public function setNbParking(?int $nbParking): self
    {
        $this->nbParking = $nbParking;

        return $this;
    }

    public function getNbBox(): ?int
    {
        return $this->nbBox;
    }

    public function setNbBox(?int $nbBox): self
    {
        $this->nbBox = $nbBox;

        return $this;
    }
}
