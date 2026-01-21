<?php

namespace App\Entity;

use App\Repository\AccountBalanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: AccountBalanceRepository::class)]
#[ORM\Table(name: 'tbl_account_balance')]
#[ApiResource]
class AccountBalance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $share = null;

    #[ORM\Column(length: 50)]
    private ?string $thrif = null;

    #[ORM\Column(length: 50)]
    private ?string $savings = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?int $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getShare(): ?string
    {
        return $this->share;
    }

    public function setShare(string $share): static
    {
        $this->share = $share;

        return $this;
    }

    public function getThrif(): ?string
    {
        return $this->thrif;
    }

    public function setThrif(string $thrif): static
    {
        $this->thrif = $thrif;

        return $this;
    }

    public function getSavings(): ?string
    {
        return $this->savings;
    }

    public function setSavings(string $savings): static
    {
        $this->savings = $savings;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }
}
