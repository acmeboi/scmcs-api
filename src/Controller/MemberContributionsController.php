<?php

namespace App\Controller;

use App\ApiResource\MemberContributionsResponse;
use App\Entity\Member;
use App\Entity\Share;
use App\Entity\User;
use App\Repository\MemberRepository;
use App\Repository\ShareRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MemberContributionsController extends AbstractController
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
        $thrif = $member->getThrif() ?? 0;
        $specialSavings = $member->getSpecialSavings() ?? 0;

        // Get cumulative sum of shareAmount from Share entity
        $shareSum = $this->shareRepository->createQueryBuilder('s')
            ->select('COALESCE(SUM(s.shareAmount), 0)')
            ->where('s.member = :member')
            ->setParameter('member', $member)
            ->getQuery()
            ->getSingleScalarResult();

        $response = new MemberContributionsResponse();
        $response->cards = [
            [
                'title' => 'Thrif',
                'amount' => (float) $thrif,
                'type' => 'thrif',
            ],
            [
                'title' => 'Special Savings',
                'amount' => (float) $specialSavings,
                'type' => 'special_savings',
            ],
            [
                'title' => 'Share',
                'amount' => (float) $shareSum,
                'type' => 'share',
            ],
        ];

        return new JsonResponse($response, Response::HTTP_OK);
    }
}

