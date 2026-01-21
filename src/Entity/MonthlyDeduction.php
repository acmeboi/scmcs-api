<?php

namespace App\Entity;

use App\Repository\MonthlyDeductionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: MonthlyDeductionRepository::class)]
#[ORM\Table(name: 'tbl_monthly_deduction')]
#[ApiResource]
class MonthlyDeduction
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

    #[ORM\Column]
    private ?float $softloan = null;

    #[ORM\Column(name: 'fixedAsset')]
    private ?float $fixedAsset = null;

    #[ORM\Column]
    private ?float $essential = null;

    #[ORM\Column]
    private ?float $layya = null;

    #[ORM\Column]
    private ?int $watanda = null;

    #[ORM\Column]
    private ?float $refund = null;

    #[ORM\Column]
    private ?float $other = null;

    #[ORM\Column(name: 'form_fee')]
    private ?float $formFee = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?int $status = null;

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

    public function getSoftloan(): ?float
    {
        return $this->softloan;
    }

    public function setSoftloan(float $softloan): static
    {
        $this->softloan = $softloan;

        return $this;
    }

    public function getFixedAsset(): ?float
    {
        return $this->fixedAsset;
    }

    public function setFixedAsset(float $fixedAsset): static
    {
        $this->fixedAsset = $fixedAsset;

        return $this;
    }

    public function getEssential(): ?float
    {
        return $this->essential;
    }

    public function setEssential(float $essential): static
    {
        $this->essential = $essential;

        return $this;
    }

    public function getLayya(): ?float
    {
        return $this->layya;
    }

    public function setLayya(float $layya): static
    {
        $this->layya = $layya;

        return $this;
    }

    public function getWatanda(): ?int
    {
        return $this->watanda;
    }

    public function setWatanda(int $watanda): static
    {
        $this->watanda = $watanda;

        return $this;
    }

    public function getRefund(): ?float
    {
        return $this->refund;
    }

    public function setRefund(float $refund): static
    {
        $this->refund = $refund;

        return $this;
    }

    public function getOther(): ?float
    {
        return $this->other;
    }

    public function setOther(float $other): static
    {
        $this->other = $other;

        return $this;
    }

    public function getFormFee(): ?float
    {
        return $this->formFee;
    }

    public function setFormFee(float $formFee): static
    {
        $this->formFee = $formFee;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

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
