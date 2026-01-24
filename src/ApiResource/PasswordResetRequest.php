<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\PasswordResetRequestController;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/password/reset-request',
            controller: PasswordResetRequestController::class,
            security: 'is_granted("PUBLIC_ACCESS")',
            name: 'password_reset_request'
        ),
    ]
)]
class PasswordResetRequest
{
    #[ApiProperty]
    #[Assert\NotBlank(message: 'Email is required')]
    #[Assert\Email(message: 'Please enter a valid email address')]
    public ?string $email = null;
}

