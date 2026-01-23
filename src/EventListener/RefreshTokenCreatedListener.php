<?php

namespace App\EventListener;

use App\Entity\RefreshToken;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Event\RefreshTokenCreatedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsEventListener(event: 'gesdinet_jwt_refresh_token.on_refresh_token_created', method: 'onRefreshTokenCreated')]
class RefreshTokenCreatedListener
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TokenStorageInterface $tokenStorage
    ) {
    }

    public function onRefreshTokenCreated(RefreshTokenCreatedEvent $event): void
    {
        $refreshToken = $event->getRefreshToken();
        
        // Only process if it's our RefreshToken entity
        if (!$refreshToken instanceof RefreshToken) {
            return;
        }

        // If user is already set, nothing to do
        if ($refreshToken->getUser() !== null) {
            return;
        }

        // Try to get user from token storage (if available)
        $token = $this->tokenStorage->getToken();
        if ($token && $token->getUser() instanceof User) {
            $refreshToken->setUser($token->getUser());
            return;
        }

        // Try to find user by username (email) - this is the most reliable method
        // The username is set by the bundle from the authenticated user's identifier
        $username = $refreshToken->getUsername();
        if ($username) {
            $userRepository = $this->entityManager->getRepository(User::class);
            $user = $userRepository->findOneBy(['email' => $username]);
            
            if ($user instanceof User) {
                $refreshToken->setUser($user);
            }
        }
    }
}

