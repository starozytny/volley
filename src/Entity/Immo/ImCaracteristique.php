<?php

namespace App\Entity\Immo;

use App\Repository\Immo\ImCaracteristiqueRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImCaracteristiqueRepository::class)
 */
class ImCaracteristique
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasCommodite;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $surface;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $surfaceTerrain;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $surfaceSejour;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbPiece;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbChambre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbSdb;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbSe;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbWc;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $isWcSepare;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbBalcon;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbEtage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $etage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $anneeConstruction;

    /**
     * @ORM\Column(type="integer")
     */
    private $isRefaitneuf;

    /**
     * @ORM\Column(type="integer")
     */
    private $isMeuble;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typeChauffage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typeCuisine;

    /**
     * @ORM\Column(type="integer")
     */
    private $isSud;

    /**
     * @ORM\Column(type="integer")
     */
    private $isNord;

    /**
     * @ORM\Column(type="integer")
     */
    private $isEst;

    /**
     * @ORM\Column(type="integer")
     */
    private $isOuest;

    /**
     * @ORM\OneToOne(targetEntity=ImCommodite::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $commodite;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHasCommodite(): ?bool
    {
        return $this->hasCommodite;
    }

    public function setHasCommodite(bool $hasCommodite): self
    {
        $this->hasCommodite = $hasCommodite;

        return $this;
    }

    public function getSurface(): ?float
    {
        return $this->surface;
    }

    public function setSurface(?float $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getSurfaceTerrain(): ?float
    {
        return $this->surfaceTerrain;
    }

    public function setSurfaceTerrain(?float $surfaceTerrain): self
    {
        $this->surfaceTerrain = $surfaceTerrain;

        return $this;
    }

    public function getSurfaceSejour(): ?float
    {
        return $this->surfaceSejour;
    }

    public function setSurfaceSejour(?float $surfaceSejour): self
    {
        $this->surfaceSejour = $surfaceSejour;

        return $this;
    }

    public function getNbPiece(): ?int
    {
        return $this->nbPiece;
    }

    public function setNbPiece(?int $nbPiece): self
    {
        $this->nbPiece = $nbPiece;

        return $this;
    }

    public function getNbChambre(): ?int
    {
        return $this->nbChambre;
    }

    public function setNbChambre(?int $nbChambre): self
    {
        $this->nbChambre = $nbChambre;

        return $this;
    }

    public function getNbSdb(): ?int
    {
        return $this->nbSdb;
    }

    public function setNbSdb(?int $nbSdb): self
    {
        $this->nbSdb = $nbSdb;

        return $this;
    }

    public function getNbSe(): ?int
    {
        return $this->nbSe;
    }

    public function setNbSe(?int $nbSe): self
    {
        $this->nbSe = $nbSe;

        return $this;
    }

    public function getNbWc(): ?int
    {
        return $this->nbWc;
    }

    public function setNbWc(?int $nbWc): self
    {
        $this->nbWc = $nbWc;

        return $this;
    }

    public function getIsWcSepare(): ?int
    {
        return $this->isWcSepare;
    }

    public function setIsWcSepare(?int $isWcSepare): self
    {
        $this->isWcSepare = $isWcSepare;

        return $this;
    }

    public function getNbBalcon(): ?int
    {
        return $this->nbBalcon;
    }

    public function setNbBalcon(?int $nbBalcon): self
    {
        $this->nbBalcon = $nbBalcon;

        return $this;
    }

    public function getNbEtage(): ?int
    {
        return $this->nbEtage;
    }

    public function setNbEtage(?int $nbEtage): self
    {
        $this->nbEtage = $nbEtage;

        return $this;
    }

    public function getEtage(): ?int
    {
        return $this->etage;
    }

    public function setEtage(?int $etage): self
    {
        $this->etage = $etage;

        return $this;
    }

    public function getAnneeConstruction(): ?string
    {
        return $this->anneeConstruction;
    }

    public function setAnneeConstruction(?string $anneeConstruction): self
    {
        $this->anneeConstruction = $anneeConstruction;

        return $this;
    }

    public function getIsRefaitneuf(): ?int
    {
        return $this->isRefaitneuf;
    }

    public function setIsRefaitneuf(int $isRefaitneuf): self
    {
        $this->isRefaitneuf = $isRefaitneuf;

        return $this;
    }

    public function getIsMeuble(): ?int
    {
        return $this->isMeuble;
    }

    public function setIsMeuble(int $isMeuble): self
    {
        $this->isMeuble = $isMeuble;

        return $this;
    }

    public function getTypeChauffage(): ?string
    {
        return $this->typeChauffage;
    }

    public function setTypeChauffage(?string $typeChauffage): self
    {
        $this->typeChauffage = $typeChauffage;

        return $this;
    }

    public function getTypeCuisine(): ?string
    {
        return $this->typeCuisine;
    }

    public function setTypeCuisine(?string $typeCuisine): self
    {
        $this->typeCuisine = $typeCuisine;

        return $this;
    }

    public function getIsSud(): ?int
    {
        return $this->isSud;
    }

    public function setIsSud(int $isSud): self
    {
        $this->isSud = $isSud;

        return $this;
    }

    public function getIsNord(): ?int
    {
        return $this->isNord;
    }

    public function setIsNord(int $isNord): self
    {
        $this->isNord = $isNord;

        return $this;
    }

    public function getIsEst(): ?int
    {
        return $this->isEst;
    }

    public function setIsEst(int $isEst): self
    {
        $this->isEst = $isEst;

        return $this;
    }

    public function getIsOuest(): ?int
    {
        return $this->isOuest;
    }

    public function setIsOuest(int $isOuest): self
    {
        $this->isOuest = $isOuest;

        return $this;
    }

    public function getCommodite(): ?ImCommodite
    {
        return $this->commodite;
    }

    public function setCommodite(ImCommodite $commodite): self
    {
        $this->commodite = $commodite;

        return $this;
    }
}
