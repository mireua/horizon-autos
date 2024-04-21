<?php

namespace App\Entity;

use App\Repository\TestDriveAppointmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestDriveAppointmentRepository::class)]
class TestDriveAppointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Car::class)]
    private ?Car $car = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user = null; // Changed from Customer to User

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $scheduledTime = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null; // e.g., scheduled, completed, canceled

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
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

    public function getScheduledTime(): ?\DateTimeInterface
    {
        return $this->scheduledTime;
    }

    public function setScheduledTime(\DateTimeInterface $scheduledTime): self
    {
        $this->scheduledTime = $scheduledTime;
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
}
