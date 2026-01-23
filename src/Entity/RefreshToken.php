<?php

namespace App\Entity;

use App\Repository\RefreshTokenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Model\AbstractRefreshToken;

#[ORM\Entity(repositoryClass: RefreshTokenRepository::class)]
#[ORM\Table(name: 'refresh_tokens')]
class RefreshToken extends AbstractRefreshToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected string|int|null $id = null;

    #[ORM\Column(name: 'refresh_token', type: Types::STRING, length: 128, unique: true)]
    protected ?string $refreshToken = null;

    #[ORM\Column(name: 'username', type: Types::STRING, length: 255)]
    protected ?string $username = null;

    #[ORM\Column(name: 'valid', type: Types::DATETIME_MUTABLE)]
    protected ?\DateTimeInterface $valid = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'refreshTokens')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    protected ?User $user = null;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        if ($user) {
            $this->setUsername($user->getUserIdentifier());
        }

        return $this;
    }
}

