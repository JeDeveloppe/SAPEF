<?php

namespace App\Entity;

use App\Repository\MeetingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeetingRepository::class)]
class Meeting
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'meetings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MeetingName $name = null;

    #[ORM\ManyToOne(inversedBy: 'meetings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MeetingPlace $place = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?MeetingName
    {
        return $this->name;
    }

    public function setName(?MeetingName $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPlace(): ?MeetingPlace
    {
        return $this->place;
    }

    public function setPlace(?MeetingPlace $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }
}
