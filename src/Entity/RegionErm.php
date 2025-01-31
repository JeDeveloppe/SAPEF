<?php

namespace App\Entity;

use App\Repository\RegionErmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegionErmRepository::class)]
class RegionErm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 10)]
    private ?string $color = null;

    #[ORM\Column(length: 10)]
    private ?string $colorHover = null;

    #[ORM\OneToMany(mappedBy: 'regionErm', targetEntity: Elu::class)]
    private Collection $elus;

    #[ORM\ManyToMany(targetEntity: Department::class, inversedBy: 'regionErms')]
    private Collection $departments;

    public function __construct()
    {
        $this->elus = new ArrayCollection();
        $this->departments = new ArrayCollection();
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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getColorHover(): ?string
    {
        return $this->colorHover;
    }

    public function setColorHover(string $colorHover): static
    {
        $this->colorHover = $colorHover;

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
            $elu->setRegionErm($this);
        }

        return $this;
    }

    public function removeElu(Elu $elu): static
    {
        if ($this->elus->removeElement($elu)) {
            // set the owning side to null (unless already changed)
            if ($elu->getRegionErm() === $this) {
                $elu->setRegionErm(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection<int, Department>
     */
    public function getDepartments(): Collection
    {
        return $this->departments;
    }

    public function addDepartment(Department $department): static
    {
        if (!$this->departments->contains($department)) {
            $this->departments->add($department);
        }

        return $this;
    }

    public function removeDepartment(Department $department): static
    {
        $this->departments->removeElement($department);

        return $this;
    }
}
