<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Controller\MemberContributionsController;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/member/contributions',
            controller: MemberContributionsController::class,
            security: 'is_granted("IS_AUTHENTICATED_FULLY")',
            name: 'member_contributions'
        ),
    ],
    normalizationContext: ['groups' => ['contributions:read']]
)]
class MemberContributionsResponse
{
    #[ApiProperty]
    #[Groups(['contributions:read'])]
    public array $cards = [];
}

