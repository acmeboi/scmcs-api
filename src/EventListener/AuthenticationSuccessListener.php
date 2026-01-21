<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'lexik_jwt_authentication.on_authentication_success', method: 'onAuthenticationSuccessResponse')]
class AuthenticationSuccessListener
{
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

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

