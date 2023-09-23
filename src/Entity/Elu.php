<?php

namespace App\Entity;

use App\Repository\EluRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EluRepository::class)]
class Elu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'elus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $name = null;

    #[ORM\ManyToOne(inversedBy: 'elus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?EluStatus $status = null;

    #[ORM\ManyToOne(inversedBy: 'elus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RegionErm $regionErm = null;

    #[ORM\ManyToOne(inversedBy: 'updatedElus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $updatedBy = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

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

    public function getStatus(): ?EluStatus
    {
        return $this->status;
    }

    public function setStatus(?EluStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getRegionErm(): ?RegionErm
    {
        return $this->regionErm;
    }

    public function setRegionErm(?RegionErm $regionErm): static
    {
        $this->regionErm = $regionErm;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
