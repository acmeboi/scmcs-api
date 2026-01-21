<?php

namespace App\Controller;

use App\ApiResource\MemberDeductionHistoryResponse;
use App\Entity\User;
use App\Repository\MonthlyDeductionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MemberDeductionHistoryController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MonthlyDeductionRepository $monthlyDeductionRepository
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
        
        // Get months parameter (default: 12)
        $months = (int) $request->query->get('months', 12);
        $months = max(1, min($months, 60)); // Limit between 1 and 60 months

        // Calculate start date
        $startDate = new \DateTime();
        $startDate->modify("-{$months} months");
        $startDate->setTime(0, 0, 0);

        // Get monthly deductions for the date range
        $deductions = $this->monthlyDeductionRepository->createQueryBuilder('md')
            ->where('md.member = :member')
            ->andWhere('md.date >= :startDate')
            ->setParameter('member', $member)
            ->setParameter('startDate', $startDate)
            ->orderBy('md.date', 'DESC')
            ->getQuery()
            ->getResult();

        // Group by month
        $historyByMonth = [];
        foreach ($deductions as $deduction) {
            $date = $deduction->getDate();
            if (!$date) {
                continue;
            }

            $monthKey = $date->format('Y-m');
            
            if (!isset($historyByMonth[$monthKey])) {
                $historyByMonth[$monthKey] = [
                    'month' => $monthKey,
                    'date' => $date->format('Y-m-d'),
                    'modules' => [
                        'share' => 0.0,
                        'thrif' => 0.0,
                        'savings' => 0.0,
                        'softloan' => 0.0,
                        'fixedAsset' => 0.0,
                        'essential' => 0.0,
                        'layya' => 0.0,
                        'watanda' => 0.0,
                        'refund' => 0.0,
                        'other' => 0.0,
                        'formFee' => 0.0,
                    ],
                    'total' => 0.0,
                ];
            }

            // Accumulate module amounts
            $historyByMonth[$monthKey]['modules']['share'] += (float) ($deduction->getShare() ?? 0);
            $historyByMonth[$monthKey]['modules']['thrif'] += (float) ($deduction->getThrif() ?? 0);
            $historyByMonth[$monthKey]['modules']['savings'] += (float) ($deduction->getSavings() ?? 0);
            $historyByMonth[$monthKey]['modules']['softloan'] += (float) ($deduction->getSoftloan() ?? 0);
            $historyByMonth[$monthKey]['modules']['fixedAsset'] += (float) ($deduction->getFixedAsset() ?? 0);
            $historyByMonth[$monthKey]['modules']['essential'] += (float) ($deduction->getEssential() ?? 0);
            $historyByMonth[$monthKey]['modules']['layya'] += (float) ($deduction->getLayya() ?? 0);
            $historyByMonth[$monthKey]['modules']['watanda'] += (float) ($deduction->getWatanda() ?? 0);
            $historyByMonth[$monthKey]['modules']['refund'] += (float) ($deduction->getRefund() ?? 0);
            $historyByMonth[$monthKey]['modules']['other'] += (float) ($deduction->getOther() ?? 0);
            $historyByMonth[$monthKey]['modules']['formFee'] += (float) ($deduction->getFormFee() ?? 0);
            
            // Use total from deduction if available, otherwise sum modules
            $total = $deduction->getTotal() ?? 0;
            if ($total > 0) {
                $historyByMonth[$monthKey]['total'] += (float) $total;
            } else {
                $historyByMonth[$monthKey]['total'] += 
                    $historyByMonth[$monthKey]['modules']['share'] +
                    $historyByMonth[$monthKey]['modules']['thrif'] +
                    $historyByMonth[$monthKey]['modules']['savings'] +
                    $historyByMonth[$monthKey]['modules']['softloan'] +
                    $historyByMonth[$monthKey]['modules']['fixedAsset'] +
                    $historyByMonth[$monthKey]['modules']['essential'] +
                    $historyByMonth[$monthKey]['modules']['layya'] +
                    $historyByMonth[$monthKey]['modules']['watanda'] +
                    $historyByMonth[$monthKey]['modules']['refund'] +
                    $historyByMonth[$monthKey]['modules']['other'] +
                    $historyByMonth[$monthKey]['modules']['formFee'];
            }
        }

        // Convert to array and format amounts
        $history = [];
        $totalAmount = 0.0;
        foreach ($historyByMonth as $monthData) {
            // Round module amounts to 2 decimal places
            foreach ($monthData['modules'] as $key => $value) {
                $monthData['modules'][$key] = round($value, 2);
            }
            $monthData['total'] = round($monthData['total'], 2);
            $totalAmount += $monthData['total'];
            $history[] = $monthData;
        }

        // Sort by month descending (most recent first)
        usort($history, function($a, $b) {
            return strcmp($b['month'], $a['month']);
        });

        $response = new MemberDeductionHistoryResponse();
        $response->history = $history;
        $response->summary = [
            'totalMonths' => count($history),
            'totalAmount' => round($totalAmount, 2),
        ];

        return new JsonResponse($response, Response::HTTP_OK);
    }
}

