<?php

namespace App\Entity;

use App\Repository\EluStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EluStatusRepository::class)]
class EluStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'status', targetEntity: Elu::class)]
    private Collection $elus;

    public function __construct()
    {
        $this->elus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Elu>
     */
    public function getElus(): Collection
    {
        return $this->elus;
    }

    public function addElu(Elu $elu): static
    {
        if (!$this->elus->contains($elu)) {
            $this->elus->add($elu);
            $elu->setStatus($this);
        }

        return $this;
    }

    public function removeElu(Elu $elu): static
    {
        if ($this->elus->removeElement($elu)) {
            // set the owning side to null (unless already changed)
            if ($elu->getStatus() === $this) {
                $elu->setStatus(null);
            }
        }

        return $this;
    }
}
