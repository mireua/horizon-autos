<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: "string")]
    private ?string $password = null;

    #[ORM\Column(type: "json")]
    private array $roles = [];

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $phone = null;

    #[ORM\OneToMany(mappedBy: 'sender', targetEntity: Inquiry::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private $sentInquiries;

    public function __construct()
    {
        $this->sentInquiries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // Guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getSalt(): ?string
    {
        return null; // Not needed for modern password hashing
    }

    public function eraseCredentials(): void
    {
        // No-op
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return Collection|Inquiry[]
     */
    public function getSentInquiries(): Collection
    {
        return $this->sentInquiries;
    }

    public function addSentInquiry(Inquiry $inquiry): self
    {
        if (!$this->sentInquiries->contains($inquiry)) {
            $this->sentInquiries[] = $inquiry;
            $inquiry->setSender($this);
        }

        return $this;
    }

    public function removeSentInquiry(Inquiry $inquiry): self
    {
        if ($this->sentInquiries->removeElement($inquiry)) {
            // Set the owning side to null (unless already changed)
            if ($inquiry->getSender() === $this) {
                $inquiry->setSender(null);
            }
        }

        return $this;
    }
}
