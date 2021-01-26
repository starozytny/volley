<?php

namespace App\Entity\Immo;

use App\Repository\Immo\ImBienRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImBienRepository::class)
 */
class ImBien
{

    const NATURE_LOCATION = 0;
    const NATURE_VENTE = 1;

    const TYPE_MAISON = 0;
    const TYPE_APPARTEMENT = 1;
    const TYPE_PARKING = 2;
    const TYPE_BUREAUX = 3;
    const TYPE_LOCAL = 4;
    const TYPE_IMMEUBLE = 5;
    const TYPE_TERRAIN = 6;
    const TYPE_FOND_COMMERCE = 7;

    const ALL_TYPE = array(0,1,2,3,4,5,6,7);

    const SLUG_LOCATION = 'locations';
    const SLUG_VENTE = 'ventes';

    const SLUG_MAISON = 'maison';
    const SLUG_APPARTEMENT = 'appartement';
    const SLUG_PARKING = 'parking';
    const SLUG_BUREAUX = 'bureaux';
    const SLUG_LOCAL = 'local';
    const SLUG_IMMEUBLE = 'immeuble';
    const SLUG_TERRAIN = 'terrain';
    const SLUG_FOND_COMMERCE = 'fond-commerce';
    const SLUG_AUTRE = 'autre';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ref;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $realRef;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nature;

    /**
     * @ORM\Column(type="integer")
     */
    private $natureCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     */
    private $typeCode;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $typeT;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descriptif;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateDispo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $identifiant;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstImage;

    /**
     * @ORM\Column(type="integer")
     */
    private $isCopro;

    /**
     * @ORM\ManyToOne(targetEntity=ImAgence::class, inversedBy="biens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agence;

    /**
     * @ORM\OneToOne(targetEntity=ImFinancier::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $financier;

    /**
     * @ORM\OneToOne(targetEntity=ImCopro::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $copro;

    /**
     * @ORM\ManyToOne(targetEntity=ImResponsable::class, inversedBy="biens")
     */
    private $responsable;

    /**
     * @ORM\OneToOne(targetEntity=ImDiagnostic::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $diagnostic;

    /**
     * @ORM\OneToOne(targetEntity=ImCaracteristique::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $caracteristique;

    /**
     * @ORM\OneToOne(targetEntity=ImAdresse::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $adresse;

    /**
     * @ORM\OneToMany(targetEntity=ImImage::class, mappedBy="bien", orphanRemoval=true)
     */
    private $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getRealRef(): ?string
    {
        return $this->realRef;
    }

    public function setRealRef(string $realRef): self
    {
        $this->realRef = $realRef;

        return $this;
    }

    public function getNature(): ?string
    {
        return $this->nature;
    }

    public function setNature(?string $nature): self
    {
        $this->nature = $nature;

        return $this;
    }

    public function getNatureCode(): ?int
    {
        return $this->natureCode;
    }

    public function setNatureCode(int $natureCode): self
    {
        $this->natureCode = $natureCode;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTypeCode(): ?int
    {
        return $this->typeCode;
    }

    public function setTypeCode(int $typeCode): self
    {
        $this->typeCode = $typeCode;

        return $this;
    }

    public function getTypeT(): ?string
    {
        return $this->typeT;
    }

    public function setTypeT(?string $typeT): self
    {
        $this->typeT = $typeT;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(?string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    public function getDateDispo(): ?\DateTimeInterface
    {
        return $this->dateDispo;
    }

    public function setDateDispo(?\DateTimeInterface $dateDispo): self
    {
        $this->dateDispo = $dateDispo;

        return $this;
    }

    public function getIdentifiant(): ?string
    {
        return $this->identifiant;
    }

    public function setIdentifiant(string $identifiant): self
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    public function getFirstImage(): ?string
    {
        return $this->firstImage;
    }

    public function setFirstImage(?string $firstImage): self
    {
        $this->firstImage = $firstImage;

        return $this;
    }

    public function getIsCopro(): ?int
    {
        return $this->isCopro;
    }

    public function setIsCopro(int $isCopro): self
    {
        $this->isCopro = $isCopro;

        return $this;
    }


    public function getAgence(): ?ImAgence
    {
        return $this->agence;
    }

    public function setAgence(?ImAgence $agence): self
    {
        $this->agence = $agence;

        return $this;
    }

    public function getFinancier(): ?ImFinancier
    {
        return $this->financier;
    }

    public function setFinancier(ImFinancier $financier): self
    {
        $this->financier = $financier;

        return $this;
    }

    public function getCopro(): ?ImCopro
    {
        return $this->copro;
    }

    public function setCopro(ImCopro $copro): self
    {
        $this->copro = $copro;

        return $this;
    }

    public function getResponsable(): ?ImResponsable
    {
        return $this->responsable;
    }

    public function setResponsable(?ImResponsable $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function getDiagnostic(): ?ImDiagnostic
    {
        return $this->diagnostic;
    }

    public function setDiagnostic(ImDiagnostic $diagnostic): self
    {
        $this->diagnostic = $diagnostic;

        return $this;
    }

    public function getCaracteristique(): ?ImCaracteristique
    {
        return $this->caracteristique;
    }

    public function setCaracteristique(ImCaracteristique $caracteristique): self
    {
        $this->caracteristique = $caracteristique;

        return $this;
    }

    public function getAdresse(): ?ImAdresse
    {
        return $this->adresse;
    }

    public function setAdresse(ImAdresse $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection|ImImage[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(ImImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setBien($this);
        }

        return $this;
    }

    public function removeImage(ImImage $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getBien() === $this) {
                $image->setBien(null);
            }
        }

        return $this;
    }

    public function getSlugNature()
    {
        switch ($this->getNatureCode()) {
            case self::NATURE_LOCATION:
                return self::SLUG_LOCATION;
            default:
                return self::SLUG_VENTE;
        }
    }

    public function getSlugType()
    {
        switch ($this->getTypeCode()) {
            case self::TYPE_MAISON:
                return self::SLUG_MAISON;
            case self::TYPE_APPARTEMENT:
                return self::SLUG_APPARTEMENT;
            case self::TYPE_PARKING:
                return self::SLUG_PARKING;
            case self::TYPE_BUREAUX:
                return self::SLUG_BUREAUX;
            case self::TYPE_LOCAL:
                return self::SLUG_LOCAL;
            case self::TYPE_IMMEUBLE:
                return self::SLUG_IMMEUBLE;
            case self::TYPE_TERRAIN:
                return self::SLUG_TERRAIN;
            case self::TYPE_FOND_COMMERCE:
                return self::SLUG_FOND_COMMERCE;
            default:
                return self::SLUG_AUTRE;
        }
    }

    public function getSlugRef(){
        $ref = $this->getRef();
        $pos = strpos($ref,'|');
        $ref = substr($ref, $pos+1, strlen($ref));
        $ref = str_replace('/','-',$ref);

        return $ref;
    }

    public function __toString(): string
    {
        return $this->getRef() . ' - ' . $this->getLibelle();
    }
}
