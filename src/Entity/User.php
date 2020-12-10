<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use OpenApi\Annotations as OA;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"})
 * @UniqueEntity(fields={"email"})
 */
class User implements UserInterface
{
    const ADMIN_READ = ['admin:read'];

    const CODE_ROLE_USER = 0;
    const CODE_ROLE_SUPER_ADMIN = 1;
    const CODE_ROLE_ADMIN = 2;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"admin:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     * @Groups({"admin:read", "admin:write"})
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Groups({"admin:read", "admin:write"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"admin:write"})
     * @OA\Property(type="array", @OA\Items(type="string"))
     */
    private $roles = ['ROLE_USER'];

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"admin:write"})
     */
    private $password;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getHighRole(): string
    {
        $rolesSortedByImportance = ['ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_USER'];
        $rolesLabel = ['Super admin', 'Administrateur', 'Utilisateur'];
        $i = 0;
        foreach ($rolesSortedByImportance as $role)
        {
            if (in_array($role, $this->roles))
            {
                return $rolesLabel[$i];
            }
            $i++;
        }

        return "Utilisateur";
    }

    public function getHighRoleCode(): int
    {
        switch($this->getHighRole()){
            case 'Super admin':
                return self::CODE_ROLE_SUPER_ADMIN;
            case 'Administrateur':
                return self::CODE_ROLE_ADMIN;
            default:
                return self::CODE_ROLE_USER;
        }
    }


    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * How long ago an user was added.
     *
     * @return string
     * @Groups({"admin:read"})
     */
    public function getCreatedAtAgo(): string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }
}
