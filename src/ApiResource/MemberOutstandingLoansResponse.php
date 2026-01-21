<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Controller\MemberOutstandingLoansController;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/member/outstanding-loans',
            controller: MemberOutstandingLoansController::class,
            security: 'is_granted("IS_AUTHENTICATED_FULLY")',
            name: 'member_outstanding_loans'
        ),
    ],
    normalizationContext: ['groups' => ['outstanding_loans:read']]
)]
class MemberOutstandingLoansResponse
{
    #[ApiProperty]
    #[Groups(['outstanding_loans:read'])]
    public array $cards = [];
}

