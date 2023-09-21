<?php

namespace App\Entity;

use App\Repository\DeskRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeskRepository::class)]
class Desk
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'desks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $name = null;

    #[ORM\ManyToOne(inversedBy: 'desks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DeskRole $role = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'desks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $updatedBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?User
    {
        return $this->name;
    }

    public function setName(?User $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getRole(): ?DeskRole
    {
        return $this->role;
    }

    public function setRole(?DeskRole $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?User $updatedBy): static
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }
}
