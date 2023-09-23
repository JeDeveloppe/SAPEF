<?php

namespace App\Entity;

use App\Repository\ConfigurationSiteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfigurationSiteRepository::class)]
class ConfigurationSite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    private ?string $cotisation = null;

    #[ORM\Column(length: 255)]
    private ?string $emailSite = null;

    #[ORM\Column]
    private ?int $DelayBeforeMeetingToSendEmail = null;

    #[ORM\Column(length: 33)]
    private ?string $Iban = null;

    #[ORM\Column(length: 11)]
    private ?string $Bic = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCotisation(): ?string
    {
        return $this->cotisation;
    }

    public function setCotisation(string $cotisation): self
    {
        $this->cotisation = $cotisation;

        return $this;
    }

    public function getEmailSite(): ?string
    {
        return $this->emailSite;
    }

    public function setEmailSite(string $emailSite): self
    {
        $this->emailSite = $emailSite;

        return $this;
    }

    public function getDelayBeforeMeetingToSendEmail(): ?int
    {
        return $this->DelayBeforeMeetingToSendEmail;
    }

    public function setDelayBeforeMeetingToSendEmail(int $DelayBeforeMeetingToSendEmail): self
    {
        $this->DelayBeforeMeetingToSendEmail = $DelayBeforeMeetingToSendEmail;

        return $this;
    }

    public function getIban(): ?string
    {
        return $this->Iban;
    }

    public function setIban(string $Iban): self
    {
        $this->Iban = $Iban;

        return $this;
    }

    public function getBic(): ?string
    {
        return $this->Bic;
    }

    public function setBic(string $Bic): self
    {
        $this->Bic = $Bic;

        return $this;
    }
}
