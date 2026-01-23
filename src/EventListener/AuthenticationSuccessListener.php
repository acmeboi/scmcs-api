<?php

namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[AsEventListener(event: 'lexik_jwt_authentication.on_authentication_success', method: 'onAuthenticationSuccessResponse')]
class AuthenticationSuccessListener
{
    public function __construct(
        private TokenStorageInterface $tokenStorage
    ) {
    }

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

        // Ensure the user is stored in token storage for refresh token creation
        // This ensures RefreshTokenCreatedListener can access the user
        if ($user instanceof User) {
            $token = $this->tokenStorage->getToken();
            if ($token) {
                $token->setUser($user);
            }
        }

        // Get the Member profile if it exists
        $member = null;
        if (method_exists($user, 'getMember') && $user->getMember()) {
            $member = $user->getMember();
            $data['profile'] = [
                'id' => $member->getId(),
                'name' => $member->getName(),
                'email' => $member->getEmail(),
                'phone' => $member->getPhone(),
                'gender' => $member->getGender(),
                'department' => $member->getDepartment(),
                'fileNumber' => $member->getFileNumber(),
                'transactionId' => $member->getTransactionId(),
                'thrif' => $member->getThrif(),
                'specialSavings' => $member->getSpecialSavings(),
                'status' => $member->getStatus(),
                'date' => $member->getDate()?->format('Y-m-d H:i:s'),
            ];
        }

        // Add user information
        $data['user'] = [
            'id' => method_exists($user, 'getId') ? $user->getId() : null,
            'email' => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
            'enabled' => method_exists($user, 'isEnabled') ? $user->isEnabled() : true,
        ];

        $event->setData($data);
    }
}

