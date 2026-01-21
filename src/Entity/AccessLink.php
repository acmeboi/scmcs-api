<?php

namespace App\Entity;

use App\Repository\AccessLinkRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: AccessLinkRepository::class)]
#[ORM\Table(name: 'tbl_access_links')]
#[ApiResource]
class AccessLink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'link_name', length: 100)]
    private ?string $linkName = null;

    #[ORM\Column]
    private ?int $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLinkName(): ?string
    {
        return $this->linkName;
    }

    public function setLinkName(string $linkName): static
    {
        $this->linkName = $linkName;

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
