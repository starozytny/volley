<?php

namespace App\Entity\Immo;

use App\Repository\Immo\ImCoproRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImCoproRepository::class)
 */
class ImCopro
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbLot;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $chargesAnnuelle;

    /**
     * @ORM\Column(type="integer")
     */
    private $hasProced;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $detailsProced;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbLot(): ?int
    {
        return $this->nbLot;
    }

    public function setNbLot(?int $nbLot): self
    {
        $this->nbLot = $nbLot;

        return $this;
    }

    public function getChargesAnnuelle(): ?int
    {
        return $this->chargesAnnuelle;
    }

    public function setChargesAnnuelle(?int $chargesAnnuelle): self
    {
        $this->chargesAnnuelle = $chargesAnnuelle;

        return $this;
    }

    public function getHasProced(): ?int
    {
        return $this->hasProced;
    }

    public function setHasProced(int $hasProced): self
    {
        $this->hasProced = $hasProced;

        return $this;
    }

    public function getDetailsProced(): ?string
    {
        return $this->detailsProced;
    }

    public function setDetailsProced(?string $detailsProced): self
    {
        $this->detailsProced = $detailsProced;

        return $this;
    }
}
