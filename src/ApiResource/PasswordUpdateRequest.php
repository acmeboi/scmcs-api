<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\PasswordUpdateController;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/password/update',
            controller: PasswordUpdateController::class,
            security: 'is_granted("PUBLIC_ACCESS")',
            name: 'password_update'
        ),
    ]
)]
class PasswordUpdateRequest
{
    #[ApiProperty]
    #[Assert\NotBlank]
    public ?string $token = null;

    #[ApiProperty]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8, minMessage: 'Password must be at least 8 characters long')]
    public ?string $newPassword = null;
}

