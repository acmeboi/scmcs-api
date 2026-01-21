<?php

namespace App\Entity;

use App\Repository\OutstandingRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: OutstandingRepository::class)]
#[ORM\Table(name: 'tbl_outstanding')]
#[ApiResource]
class Outstanding
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Member::class)]
    #[ORM\JoinColumn(name: 'memberId', referencedColumnName: 'id', nullable: false)]
    private ?Member $member = null;

    #[ORM\Column]
    private ?float $contribution = null;

    #[ORM\Column]
    private ?float $outstanding = null;

    #[ORM\Column]
    private ?float $inward = null;

    #[ORM\Column]
    private ?float $outward = null;

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

    public function getContribution(): ?float
    {
        return $this->contribution;
    }

    public function setContribution(float $contribution): static
    {
        $this->contribution = $contribution;

        return $this;
    }

    public function getOutstanding(): ?float
    {
        return $this->outstanding;
    }

    public function setOutstanding(float $outstanding): static
    {
        $this->outstanding = $outstanding;

        return $this;
    }

    public function getInward(): ?float
    {
        return $this->inward;
    }

    public function setInward(float $inward): static
    {
        $this->inward = $inward;

        return $this;
    }

    public function getOutward(): ?float
    {
        return $this->outward;
    }

    public function setOutward(float $outward): static
    {
        $this->outward = $outward;

        return $this;
    }
}
