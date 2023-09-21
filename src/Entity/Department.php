<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartmentRepository::class)]
class Department
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $codeMap = null;

    #[ORM\ManyToOne(inversedBy: 'departments')]
    #[ORM\JoinColumn(nullable: true)]
    private ?RegionErm $region = null;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: City::class)]
    private Collection $cities;

    #[ORM\Column(length: 3)]
    private ?string $number = null;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: Shop::class)]
    private Collection $shops;

    public function __construct()
    {
        $this->cities = new ArrayCollection();
        $this->shops = new ArrayCollection();
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

    public function getCodeMap(): ?string
    {
        return $this->codeMap;
    }

    public function setCodeMap(string $codeMap): static
    {
        $this->codeMap = $codeMap;

        return $this;
    }

    public function getRegion(): ?RegionErm
    {
        return $this->region;
    }

    public function setRegion(?RegionErm $region): static
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Collection<int, City>
     */
    public function getCities(): Collection
    {
        return $this->cities;
    }

    public function addCity(City $city): static
    {
        if (!$this->cities->contains($city)) {
            $this->cities->add($city);
            $city->setDepartment($this);
        }

        return $this;
    }

    public function removeCity(City $city): static
    {
        if ($this->cities->removeElement($city)) {
            // set the owning side to null (unless already changed)
            if ($city->getDepartment() === $this) {
                $city->setDepartment(null);
            }
        }

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection<int, Shop>
     */
    public function getShops(): Collection
    {
        return $this->shops;
    }

    public function addShop(Shop $shop): static
    {
        if (!$this->shops->contains($shop)) {
            $this->shops->add($shop);
            $shop->setDepartment($this);
        }

        return $this;
    }

    public function removeShop(Shop $shop): static
    {
        if ($this->shops->removeElement($shop)) {
            // set the owning side to null (unless already changed)
            if ($shop->getDepartment() === $this) {
                $shop->setDepartment(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->number.' - '.$this->name;
    }
}
