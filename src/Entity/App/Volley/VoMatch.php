<?php

namespace App\Entity\App\Volley;

use App\Repository\App\Volley\VoMatchRepository;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=VoMatchRepository::class)
 */
class VoMatch
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"visitor:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"visitor:read"})
     */
    private $startAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"visitor:read"})
     */
    private $team;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"visitor:read"})
     */
    private $versus;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"visitor:read"})
     */
    private $localisation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    /**
     * Return date format for parse in js
     * @Groups({"visitor:read"})
     */
    public function getStartAtJavascript(): ?string
    {
        date_default_timezone_set('Europe/Paris');
        return $this->getStartAt() != null ? date_format($this->getStartAt(), 'F d, Y H:i:s') : null;
    }

    /**
     * Return created at time in string format 5 janv. 2017
     * @Groups({"visitor:read"})
     */
    public function getStartAtStringLong(): ?string
    {
        if($this->startAt == null){
            return null;
        }
        Carbon::setLocale('fr');
        return Carbon::instance($this->getStartAt())->isoFormat('ll');
    }

    public function getTeam(): ?string
    {
        return $this->team;
    }

    public function setTeam(string $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getVersus(): ?string
    {
        return $this->versus;
    }

    public function setVersus(string $versus): self
    {
        $this->versus = $versus;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }
}
