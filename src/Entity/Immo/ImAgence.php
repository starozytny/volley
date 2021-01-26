<?php

namespace App\Entity\Immo;

use App\Repository\Immo\ImAgenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImAgenceRepository::class)
 */
class ImAgence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dirname;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $legales;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tarif;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emailStandard;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emailLocation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emailVente;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $phoneStandard;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $phoneLocation;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $phoneVente;

    /**
     * @ORM\OneToOne(targetEntity=ImAdresse::class, cascade={"persist", "remove"})
     */
    private $adresse;

    /**
     * @ORM\OneToMany(targetEntity=ImBien::class, mappedBy="agence", orphanRemoval=true)
     */
    private $biens;

    public function __construct()
    {
        $this->biens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDirname(): ?string
    {
        return $this->dirname;
    }

    public function setDirname(string $dirname): self
    {
        $this->dirname = $dirname;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLegales(): ?string
    {
        return $this->legales;
    }

    public function setLegales(?string $legales): self
    {
        $this->legales = $legales;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getTarif(): ?string
    {
        return $this->tarif;
    }

    public function setTarif(?string $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getEmailStandard(): ?string
    {
        return $this->emailStandard;
    }

    public function setEmailStandard(?string $emailStandard): self
    {
        $this->emailStandard = $emailStandard;

        return $this;
    }

    public function getEmailLocation(): ?string
    {
        return $this->emailLocation;
    }

    public function setEmailLocation(?string $emailLocation): self
    {
        $this->emailLocation = $emailLocation;

        return $this;
    }

    public function getEmailVente(): ?string
    {
        return $this->emailVente;
    }

    public function setEmailVente(?string $emailVente): self
    {
        $this->emailVente = $emailVente;

        return $this;
    }

    public function getPhoneStandard(): ?string
    {
        return $this->phoneStandard;
    }

    public function setPhoneStandard(?string $phoneStandard): self
    {
        $this->phoneStandard = $phoneStandard;

        return $this;
    }

    public function getPhoneLocation(): ?string
    {
        return $this->phoneLocation;
    }

    public function setPhoneLocation(?string $phoneLocation): self
    {
        $this->phoneLocation = $phoneLocation;

        return $this;
    }

    public function getPhoneVente(): ?string
    {
        return $this->phoneVente;
    }

    public function setPhoneVente(?string $phoneVente): self
    {
        $this->phoneVente = $phoneVente;

        return $this;
    }

    public function getAdresse(): ?ImAdresse
    {
        return $this->adresse;
    }

    public function setAdresse(?ImAdresse $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection|ImBien[]
     */
    public function getBiens(): Collection
    {
        return $this->biens;
    }

    public function addBien(ImBien $bien): self
    {
        if (!$this->biens->contains($bien)) {
            $this->biens[] = $bien;
            $bien->setAgence($this);
        }

        return $this;
    }

    public function removeBien(ImBien $bien): self
    {
        if ($this->biens->removeElement($bien)) {
            // set the owning side to null (unless already changed)
            if ($bien->getAgence() === $this) {
                $bien->setAgence(null);
            }
        }

        return $this;
    }
}
