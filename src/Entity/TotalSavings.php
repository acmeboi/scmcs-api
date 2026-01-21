<?php

namespace App\Entity;

use App\Repository\TotalSavingsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: TotalSavingsRepository::class)]
#[ORM\Table(name: 'tbl_total_savings')]
#[ApiResource]
class TotalSavings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Member::class)]
    #[ORM\JoinColumn(name: 'memberId', referencedColumnName: 'id', nullable: false)]
    private ?Member $member = null;

    #[ORM\Column]
    private ?float $share = null;

    #[ORM\Column]
    private ?float $thrif = null;

    #[ORM\Column]
    private ?float $savings = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): static
    {
        $this->member = $member;

        return $this;
    }

    public function getShare(): ?float
    {
        return $this->share;
    }

    public function setShare(float $share): static
    {
        $this->share = $share;

        return $this;
    }

    public function getThrif(): ?float
    {
        return $this->thrif;
    }

    public function setThrif(float $thrif): static
    {
        $this->thrif = $thrif;

        return $this;
    }

    public function getSavings(): ?float
    {
        return $this->savings;
    }

    public function setSavings(float $savings): static
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
}
