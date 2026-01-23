<?php

namespace App\Entity;

use App\Repository\RefreshTokenRepository;
use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Model\AbstractRefreshToken;
use Symfony\Component\Security\Core\User\UserInterface;

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

    /**
     * Override the parent method to ensure the user is set when creating a refresh token.
     * This is called by the bundle's RefreshTokenGenerator.
     * 
     * The parent method only sets the username, not the user relationship.
     * We override it to also set the user relationship, which is required since user_id is NOT NULL.
     */
    public static function createForUserWithTtl(string $token, UserInterface $user, int $ttl): static
    {
        $refreshToken = parent::createForUserWithTtl($token, $user, $ttl);
        
        // CRITICAL: Set the user relationship if the user is our User entity
        // The parent method only sets username, but we need the user relationship
        if ($user instanceof User && $refreshToken instanceof self) {
            $refreshToken->user = $user; // Direct property assignment to avoid setUser() recursion
        }
        
        return $refreshToken;
    }

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

