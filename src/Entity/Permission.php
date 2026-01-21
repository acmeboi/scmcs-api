<?php

namespace App\Entity;

use App\Repository\PermissionRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
#[ORM\Table(name: 'tbl_permissions')]
#[ApiResource]
class Permission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Admin::class)]
    #[ORM\JoinColumn(name: 'userId', referencedColumnName: 'id', nullable: false)]
    private ?Admin $admin = null;

    #[ORM\ManyToOne(targetEntity: AccessLink::class)]
    #[ORM\JoinColumn(name: 'linkId', referencedColumnName: 'id', nullable: false)]
    private ?AccessLink $accessLink = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    public function setAdmin(?Admin $admin): static
    {
        $this->admin = $admin;

        return $this;
    }

    public function getAccessLink(): ?AccessLink
    {
        return $this->accessLink;
    }

    public function setAccessLink(?AccessLink $accessLink): static
    {
        $this->accessLink = $accessLink;

        return $this;
    }
}
