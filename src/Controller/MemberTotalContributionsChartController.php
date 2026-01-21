<?php

namespace App\Controller;

use App\ApiResource\MemberTotalContributionsChartResponse;
use App\Entity\User;
use App\Repository\TotalSavingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MemberTotalContributionsChartController extends AbstractController
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

        $response = new MemberTotalContributionsChartResponse();
        
        if ($totalSavings) {
            $share = (float) ($totalSavings->getShare() ?? 0);
            $thrif = (float) ($totalSavings->getThrif() ?? 0);
            $savings = (float) ($totalSavings->getSavings() ?? 0);

            // Prepare bar chart data
            $data = [];
            
            if ($share > 0) {
                $data[] = [
                    'name' => 'Total Share',
                    'value' => $share,
                    'type' => 'share',
                ];
            }
            
            if ($thrif > 0) {
                $data[] = [
                    'name' => 'Total Thrif',
                    'value' => $thrif,
                    'type' => 'thrif',
                ];
            }
            
            if ($savings > 0) {
                $data[] = [
                    'name' => 'Total Savings',
                    'value' => $savings,
                    'type' => 'savings',
                ];
            }

            $total = $share + $thrif + $savings;

            $response->data = $data;
            $response->total = $total;
            $response->lastUpdated = $totalSavings->getDate()?->format('Y-m-d');
        } else {
            $response->data = [];
            $response->total = 0.0;
            $response->lastUpdated = null;
        }

        return new JsonResponse($response, Response::HTTP_OK);
    }
}

