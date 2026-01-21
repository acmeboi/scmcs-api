<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\SignUpController;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/sign-up',
            controller: SignUpController::class,
            security: 'is_granted("PUBLIC_ACCESS")',
            name: 'sign_up'
        ),
    ]
)]
class SignUpRequest
{
    #[ApiProperty]
    #[Assert\NotBlank]
    #[Assert\Email]
    public ?string $email = null;
}

