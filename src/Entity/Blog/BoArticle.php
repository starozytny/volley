<?php

namespace App\Entity\Blog;

use App\Repository\Blog\BoArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BoArticleRepository::class)
 */
class BoArticle
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"admin:read", "admin:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin:read", "admin:write"})
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"admin:write"})
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"admin:read", "admin:write"})
     */
    private $introduction;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"admin:read", "admin:write"})
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=BoAuthor::class, inversedBy="articles")
     */
    private $author;

    public function __construct()
    {
        $createAt = new \DateTime();
        $createAt->setTimezone(new \DateTimeZone("Europe/Paris"));
        $this->createdAt = $createAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(?string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?BoAuthor
    {
        return $this->author;
    }

    public function setAuthor(?BoAuthor $author): self
    {
        $this->author = $author;

        return $this;
    }
}
