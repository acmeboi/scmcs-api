<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Controller\MemberContributionsChartController;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/member/contributions/pie-chart',
            controller: MemberContributionsChartController::class,
            security: 'is_granted("IS_AUTHENTICATED_FULLY")',
            name: 'member_contributions_pie_chart'
        ),
    ],
    normalizationContext: ['groups' => ['contributions_chart:read']]
)]
class MemberContributionsChartResponse
{
    #[ApiProperty]
    #[Groups(['contributions_chart:read'])]
    public array $data = [];

    #[ApiProperty]
    #[Groups(['contributions_chart:read'])]
    public float $total = 0.0;
}

