<?php

namespace App\Entity;

use App\Repository\UpgradeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: UpgradeRepository::class)]
#[ORM\Table(name: 'tbl_upgrade')]
#[ApiResource]
class Upgrade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Member::class)]
    #[ORM\JoinColumn(name: 'memberId', referencedColumnName: 'id', nullable: false)]
    private ?Member $member = null;

    #[ORM\Column(name: 'upgradeDate', type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $upgradeDate = null;

    #[ORM\Column(options: ['default' => 1])]
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

    public function getUpgradeDate(): ?\DateTimeInterface
    {
        return $this->upgradeDate;
    }

    public function setUpgradeDate(\DateTimeInterface $upgradeDate): static
    {
        $this->upgradeDate = $upgradeDate;

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
