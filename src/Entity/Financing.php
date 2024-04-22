<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FinancingRepository;

#[ORM\Entity(repositoryClass: FinancingRepository::class)]
class Financing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private ?float $amount = null;

    #[ORM\Column(type: "decimal", precision: 4, scale: 2)]
    private ?float $interestRate = null;

    #[ORM\Column(type: "integer")]
    private ?int $term = null; // term in months

    #[ORM\Column(type: "string", length: 255)]
    private ?string $status = 'pending'; // Default status

    #[ORM\ManyToOne(targetEntity: Car::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Car $car = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function getInterestRate(): ?float
    {
        return $this->interestRate;
    }

    public function setInterestRate(float $interestRate): self
    {
        $this->interestRate = $interestRate;
        return $this;
    }

    public function getTerm(): ?int
    {
        return $this->term;
    }

    public function setTerm(int $term): self
    {
        $this->term = $term;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): self
    {
        $this->car = $car;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
