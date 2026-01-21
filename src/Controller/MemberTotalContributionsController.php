<?php

namespace App\Controller;

use App\ApiResource\MemberTotalContributionsResponse;
use App\Entity\User;
use App\Repository\TotalSavingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MemberTotalContributionsController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TotalSavingsRepository $totalSavingsRepository
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

        // Get latest TotalSavings record for the member
        $totalSavings = $this->totalSavingsRepository->createQueryBuilder('ts')
            ->where('ts.member = :member')
            ->setParameter('member', $member)
            ->orderBy('ts.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $response = new MemberTotalContributionsResponse();
        
        if ($totalSavings) {
            $response->cards = [
                [
                    'title' => 'Total Share',
                    'amount' => (float) ($totalSavings->getShare() ?? 0),
                    'type' => 'share',
                ],
                [
                    'title' => 'Total Thrif',
                    'amount' => (float) ($totalSavings->getThrif() ?? 0),
                    'type' => 'thrif',
                ],
                [
                    'title' => 'Total Savings',
                    'amount' => (float) ($totalSavings->getSavings() ?? 0),
                    'type' => 'savings',
                ],
            ];
            $response->lastUpdated = $totalSavings->getDate()?->format('Y-m-d');
        } else {
            $response->cards = [
                [
                    'title' => 'Total Share',
                    'amount' => 0.0,
                    'type' => 'share',
                ],
                [
                    'title' => 'Total Thrif',
                    'amount' => 0.0,
                    'type' => 'thrif',
                ],
                [
                    'title' => 'Total Savings',
                    'amount' => 0.0,
                    'type' => 'savings',
                ],
            ];
            $response->lastUpdated = null;
        }

        return new JsonResponse($response, Response::HTTP_OK);
    }
}

