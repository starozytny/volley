<?php

namespace App\Entity\Immo;

use App\Repository\Immo\ImAdresseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImAdresseRepository::class)
 */
class ImAdresse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adr;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $quartier;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $ardt;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $lat;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $lon;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdr(): ?string
    {
        return $this->adr;
    }

    public function setAdr(?string $adr): self
    {
        $this->adr = $adr;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getQuartier(): ?string
    {
        return $this->quartier;
    }

    public function setQuartier(?string $quartier): self
    {
        $this->quartier = $quartier;

        return $this;
    }

    public function getArdt(): ?string
    {
        return $this->ardt;
    }

    public function setArdt(?string $ardt): self
    {
        $this->ardt = $ardt;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLon(): ?float
    {
        return $this->lon;
    }

    public function setLon(?float $lon): self
    {
        $this->lon = $lon;

        return $this;
    }

    public function getSlugVille(){
        $ville = $this->getVille();
        $ville = strtolower($ville);
        $ville = trim($ville);
        $ville = str_replace(' ','-',$ville);
        $ville = str_replace('/','-',$ville);

        return $ville;
    }

    public function __toString(): string
    {
        return $this->getCp() . ' ' . $this->getVille() . ' ' . $this->getAdr();
    }
}
