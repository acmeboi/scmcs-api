<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Controller\MemberTotalContributionsController;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/member/total-contributions',
            controller: MemberTotalContributionsController::class,
            security: 'is_granted("IS_AUTHENTICATED_FULLY")',
            name: 'member_total_contributions'
        ),
    ],
    normalizationContext: ['groups' => ['total_contributions:read']]
)]
class MemberTotalContributionsResponse
{
    #[ApiProperty]
    #[Groups(['total_contributions:read'])]
    public array $cards = [];

    #[ApiProperty]
    #[Groups(['total_contributions:read'])]
    public ?string $lastUpdated = null;
}

