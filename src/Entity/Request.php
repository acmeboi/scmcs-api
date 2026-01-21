<?php

namespace App\Entity;

use App\Repository\RequestRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
#[ORM\Table(name: 'tbl_request')]
#[ApiResource]
class Request
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'balance_bf')]
    private ?float $balanceBf = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column]
    private ?float $expensive = null;

    #[ORM\Column]
    private ?int $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBalanceBf(): ?float
    {
        return $this->balanceBf;
    }

    public function setBalanceBf(float $balanceBf): static
    {
        $this->balanceBf = $balanceBf;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getExpensive(): ?float
    {
        return $this->expensive;
    }

    public function setExpensive(float $expensive): static
    {
        $this->expensive = $expensive;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): static
    {
        $this->type = $type;

        return $this;
    }
}
