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
        
        // Get pagination parameters
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = max(1, min(100, (int) $request->query->get('limit', 20))); // Limit between 1 and 100
        $offset = ($page - 1) * $limit;

        // Get date range parameters
        $startDateParam = $request->query->get('startDate');
        $endDateParam = $request->query->get('endDate');
        
        $startDate = null;
        $endDate = null;
        
        if ($startDateParam) {
            try {
                // Create date from YYYY-MM-DD format, ensuring UTC timezone for consistency
                $startDate = \DateTime::createFromFormat('Y-m-d', $startDateParam, new \DateTimeZone('UTC'));
                if ($startDate === false) {
                    return new JsonResponse(
                        ['error' => 'Invalid startDate format. Use YYYY-MM-DD'],
                        Response::HTTP_BAD_REQUEST
                    );
                }
                $startDate->setTime(0, 0, 0);
            } catch (\Exception $e) {
                return new JsonResponse(
                    ['error' => 'Invalid startDate format. Use YYYY-MM-DD'],
                    Response::HTTP_BAD_REQUEST
                );
            }
        }
        
        if ($endDateParam) {
            try {
                // Create date from YYYY-MM-DD format, ensuring UTC timezone for consistency
                $endDate = \DateTime::createFromFormat('Y-m-d', $endDateParam, new \DateTimeZone('UTC'));
                if ($endDate === false) {
                    return new JsonResponse(
                        ['error' => 'Invalid endDate format. Use YYYY-MM-DD'],
                        Response::HTTP_BAD_REQUEST
                    );
                }
                $endDate->setTime(23, 59, 59);
            } catch (\Exception $e) {
                return new JsonResponse(
                    ['error' => 'Invalid endDate format. Use YYYY-MM-DD'],
                    Response::HTTP_BAD_REQUEST
                );
            }
        }

        // If no date range provided, use months parameter (default: 12)
        if (!$startDate && !$endDate) {
            $months = (int) $request->query->get('months', 12);
            $months = max(1, min($months, 60)); // Limit between 1 and 60 months
            $startDate = new \DateTime();
            $startDate->modify("-{$months} months");
            $startDate->setTime(0, 0, 0);
        }

        // Build query
        $qb = $this->monthlyDeductionRepository->createQueryBuilder('md')
            ->where('md.member = :member')
            ->setParameter('member', $member);
        
        if ($startDate) {
            // Compare date directly - startDate is already set to 00:00:00
            $qb->andWhere('md.date >= :startDate')
               ->setParameter('startDate', $startDate);
        }
        
        if ($endDate) {
            // Compare date directly - endDate is already set to 23:59:59
            $qb->andWhere('md.date <= :endDate')
               ->setParameter('endDate', $endDate);
        }

        // Get total count for pagination
        $totalItems = (int) (clone $qb)->select('COUNT(md.id)')
            ->getQuery()
            ->getSingleScalarResult();

        // Get paginated results
        $deductions = $qb->orderBy('md.date', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        // Helper function to determine status (CREDIT or DEBIT)
        $getStatus = function(string $fieldName): string {
            return in_array($fieldName, ['thrif', 'savings', 'share']) ? 'CREDIT' : 'DEBIT';
        };

        // Transform deductions to individual items with status per field
        $items = [];
        $groupedByMonth = [];
        $totalAmount = 0.0;

        foreach ($deductions as $deduction) {
            $date = $deduction->getDate();
            if (!$date) {
                continue;
            }

            $monthKey = $date->format('Y-m');
            $deductionId = $deduction->getId();

            // Build fields array with status
            $fields = [];
            $fieldNames = [
                'share', 'thrif', 'savings', 'softloan', 'fixedAsset',
                'essential', 'layya', 'watanda', 'refund', 'other', 'formFee'
            ];

            foreach ($fieldNames as $fieldName) {
                $getter = 'get' . ucfirst($fieldName === 'formFee' ? 'formFee' : $fieldName);
                $amount = (float) ($deduction->$getter() ?? 0);
                
                if ($amount > 0) {
                    $fields[] = [
                        'name' => $fieldName,
                        'amount' => round($amount, 2),
                        'status' => $getStatus($fieldName),
                    ];
                }
            }
            
            // Calculate total for this deduction
            $deductionTotal = $deduction->getTotal() ?? 0;
            if ($deductionTotal <= 0) {
                // Sum all fields if total is not set
                $deductionTotal = array_sum(array_column($fields, 'amount'));
            }
            $deductionTotal = round((float) $deductionTotal, 2);
            $totalAmount += $deductionTotal;

            // Create item
            $item = [
                'id' => $deductionId,
                'date' => $date->format('Y-m-d'),
                'month' => $monthKey,
                'fields' => $fields,
                'total' => $deductionTotal,
            ];

            $items[] = $item;

            // Group by month
            if (!isset($groupedByMonth[$monthKey])) {
                $groupedByMonth[$monthKey] = [
                    'month' => $monthKey,
                    'date' => $date->format('Y-m-d'),
                    'items' => [],
                ];
            }
            $groupedByMonth[$monthKey]['items'][] = $item;
        }

        // Sort grouped by month descending
        krsort($groupedByMonth);
        $groupedByMonth = array_values($groupedByMonth);

        // Calculate pagination metadata
        $totalPages = (int) ceil($totalItems / $limit);
        $pagination = [
            'currentPage' => $page,
            'pageSize' => $limit,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'hasNext' => $page < $totalPages,
            'hasPrevious' => $page > 1,
        ];

        $response = new MemberDeductionHistoryResponse();
        $response->items = $items;
        $response->groupedByMonth = $groupedByMonth;
        $response->summary = [
            'totalItems' => $totalItems,
            'totalAmount' => round($totalAmount, 2),
        ];
        $response->pagination = $pagination;

        return new JsonResponse($response, Response::HTTP_OK);
    }
}

