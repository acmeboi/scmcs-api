<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Controller\MemberTotalContributionsChartController;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/member/total-contributions/bar-chart',
            controller: MemberTotalContributionsChartController::class,
            security: 'is_granted("IS_AUTHENTICATED_FULLY")',
            name: 'member_total_contributions_bar_chart'
        ),
    ],
    normalizationContext: ['groups' => ['total_contributions_chart:read']]
)]
class MemberTotalContributionsChartResponse
{
    #[ApiProperty]
    #[Groups(['total_contributions_chart:read'])]
    public array $data = [];

    #[ApiProperty]
    #[Groups(['total_contributions_chart:read'])]
    public float $total = 0.0;

    #[ApiProperty]
    #[Groups(['total_contributions_chart:read'])]
    public ?string $lastUpdated = null;
}

