<?php

namespace App\Controller;

use App\ApiResource\MemberContributionsChartResponse;
use App\Entity\User;
use App\Repository\MemberRepository;
use App\Repository\ShareRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MemberContributionsChartController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MemberRepository $memberRepository,
        private ShareRepository $shareRepository
    ) {
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function __invoke(Request $request): JsonResponse
    {
        $user = $this->getUser();
        
        if (!$user instanceof User || !$user->getMember()) {
            return new JsonResponse(
                ['error' => 'Member profile not found'],
                Response::HTTP_NOT_FOUND
            );
        }

        $member = $user->getMember();

        // Get thrif and specialSavings from Member entity
        $thrif = (float) ($member->getThrif() ?? 0);
        $specialSavings = (float) ($member->getSpecialSavings() ?? 0);

        // Get cumulative sum of shareAmount from Share entity
        $shareSum = (float) $this->shareRepository->createQueryBuilder('s')
            ->select('COALESCE(SUM(s.shareAmount), 0)')
            ->where('s.member = :member')
            ->setParameter('member', $member)
            ->getQuery()
            ->getSingleScalarResult();

        // Prepare pie chart data - always include all categories even if 0
        $data = [
            [
                'name' => 'Thrif',
                'value' => $thrif,
                'type' => 'thrif',
            ],
            [
                'name' => 'Special Savings',
                'value' => $specialSavings,
                'type' => 'special_savings',
            ],
            [
                'name' => 'Share',
                'value' => $shareSum,
                'type' => 'share',
            ],
        ];

        $total = $thrif + $specialSavings + $shareSum;

        $response = new MemberContributionsChartResponse();
        $response->data = $data;
        $response->total = $total;

        return new JsonResponse($response, Response::HTTP_OK);
    }
}

