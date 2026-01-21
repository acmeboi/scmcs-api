<?php

namespace App\Entity;

use App\Repository\GainRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: GainRepository::class)]
#[ORM\Table(name: 'tbl_gain')]
#[ApiResource]
class Gain
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $requisition = null;

    #[ORM\Column(name: 'fixedAsset')]
    private ?float $fixedAsset = null;

    #[ORM\Column]
    private ?float $watanda = null;

    #[ORM\Column]
    private ?float $layya = null;

    #[ORM\Column(name: 'formFee')]
    private ?float $formFee = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequisition(): ?float
    {
        return $this->requisition;
    }

    public function setRequisition(float $requisition): static
    {
        $this->requisition = $requisition;

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

    public function getWatanda(): ?float
    {
        return $this->watanda;
    }

    public function setWatanda(float $watanda): static
    {
        $this->watanda = $watanda;

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

    public function getFormFee(): ?float
    {
        return $this->formFee;
    }

    public function setFormFee(float $formFee): static
    {
        $this->formFee = $formFee;

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
