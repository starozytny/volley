<?php

namespace App\Entity\Immo;

use App\Repository\Immo\ImResponsableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImResponsableRepository::class)
 */
class ImResponsable
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
    private $contact;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $codeNego;

    /**
     * @ORM\OneToMany(targetEntity=ImBien::class, mappedBy="responsable")
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

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCodeNego(): ?string
    {
        return $this->codeNego;
    }

    public function setCodeNego(?string $codeNego): self
    {
        $this->codeNego = $codeNego;

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
            $bien->setResponsable($this);
        }

        return $this;
    }

    public function removeBien(ImBien $bien): self
    {
        if ($this->biens->removeElement($bien)) {
            // set the owning side to null (unless already changed)
            if ($bien->getResponsable() === $this) {
                $bien->setResponsable(null);
            }
        }

        return $this;
    }
}
