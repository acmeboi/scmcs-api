<?php

namespace App\EventListener;

use App\Entity\RefreshToken;
use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;

class RefreshTokenPrePersistSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [Events::prePersist];
    }

    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        // Only process RefreshToken entities
        if (!$entity instanceof RefreshToken) {
            return;
        }

        // If user is already set, nothing to do
        if ($entity->getUser() !== null) {
            return;
        }

        // CRITICAL: user_id is NOT NULL, so we MUST set it before persist
        // Try to find user by username (email)
        $username = $entity->getUsername();
        
        if (!$username) {
            // Username is required - this should never happen, but if it does, we can't proceed
            throw new \RuntimeException('Cannot create refresh token: username is not set');
        }

        $entityManager = $args->getObjectManager();
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email' => $username]);
        
        if (!$user instanceof User) {
            // User must exist - if not found, this is a critical error
            throw new \RuntimeException(sprintf(
                'Cannot create refresh token: User with email "%s" not found',
                $username
            ));
        }

        // Set the user - this is mandatory
        $entity->setUser($user);
    }
}

