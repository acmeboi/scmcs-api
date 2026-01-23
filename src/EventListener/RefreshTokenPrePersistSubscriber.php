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

        // Try to find user by username (email)
        $username = $entity->getUsername();
        if ($username) {
            $entityManager = $args->getObjectManager();
            $userRepository = $entityManager->getRepository(User::class);
            $user = $userRepository->findOneBy(['email' => $username]);
            
            if ($user instanceof User) {
                $entity->setUser($user);
            }
        }
    }
}

