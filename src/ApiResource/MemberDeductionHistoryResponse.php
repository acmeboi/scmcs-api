<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Controller\MemberDeductionHistoryController;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/member/deduction-history',
            controller: MemberDeductionHistoryController::class,
            security: 'is_granted("IS_AUTHENTICATED_FULLY")',
            name: 'member_deduction_history'
        ),
    ],
    normalizationContext: ['groups' => ['deduction_history:read']]
)]
class MemberDeductionHistoryResponse
{
    #[ApiProperty]
    #[Groups(['deduction_history:read'])]
    public array $items = [];

    #[ApiProperty]
    #[Groups(['deduction_history:read'])]
    public array $groupedByMonth = [];

    #[ApiProperty]
    #[Groups(['deduction_history:read'])]
    public array $summary = [];

    #[ApiProperty]
    #[Groups(['deduction_history:read'])]
    public array $pagination = [];
}

