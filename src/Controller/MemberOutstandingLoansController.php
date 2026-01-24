<?php

namespace App\Controller;

use App\ApiResource\MemberOutstandingLoansResponse;
use App\Entity\User;
use App\Repository\BalanceRepository;
use App\Repository\EssentialCommodityRepository;
use App\Repository\FixedAssetLoanRepository;
use App\Repository\LayyaRepository;
use App\Repository\MonthlyDeductionRepository;
use App\Repository\OutstandingRepository;
use App\Repository\SoftLoanRepository;
use App\Repository\TotalSavingsRepository;
use App\Repository\WatandaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MemberOutstandingLoansController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BalanceRepository $balanceRepository,
        private EssentialCommodityRepository $essentialCommodityRepository,
        private FixedAssetLoanRepository $fixedAssetLoanRepository,
        private LayyaRepository $layyaRepository,
        private SoftLoanRepository $softLoanRepository,
        private WatandaRepository $watandaRepository,
        private MonthlyDeductionRepository $monthlyDeductionRepository,
        private OutstandingRepository $outstandingRepository,
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
        $now = new \DateTime();
        $cards = [];
        $totalOutstanding = 0.0;
        $totalPaid = 0.0;
        $totalBalance = 0.0;

        // SPECIAL REQUEST: Get latest TotalSavings date to filter active loans
        // Active loan definition: status = 0 AND endDate >= maxDate (loan is not expired)
        // Only show the latest active loan for each type
        $latestTotalSavings = $this->totalSavingsRepository->createQueryBuilder('ts')
            ->where('ts.member = :member')
            ->setParameter('member', $member)
            ->orderBy('ts.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $maxDate = $latestTotalSavings?->getDate() ?? $now;

        // Helper function to calculate progress
        $calculateProgress = function ($loan, $paidAmount = null, $hasEndDate = true) use ($now, $maxDate) {
            $startDate = $loan->getStartDate();
            $endDate = $hasEndDate && method_exists($loan, 'getEndDate') ? $loan->getEndDate() : null;
            $amount = is_string($loan->getAmount()) ? (float) $loan->getAmount() : ($loan->getAmount() ?? 0);

            // Calculate percentage - cap at 100%
            $percentage = 0.0;
            if ($paidAmount !== null && $amount > 0) {
                $percentage = min(100.0, ($paidAmount / $amount) * 100);
            }

            $timeProgress = 0.0;
            $monthsRemaining = 0;
            
            if ($startDate && $endDate) {
                $totalDays = $startDate->diff($endDate)->days;
                // Use maxDate instead of now for elapsed days calculation
                $elapsedDays = $startDate->diff($maxDate)->days;
                
                if ($totalDays > 0) {
                    $timeProgress = min(100, max(0, ($elapsedDays / $totalDays) * 100));
                }
                
                // Calculate months remaining from maxDate to endDate
                if ($endDate > $maxDate) {
                    $monthsRemaining = max(0, (int) round(($endDate->diff($maxDate)->days) / 30));
                } else {
                    $monthsRemaining = 0; // Loan period has ended
                }
            } elseif ($startDate && !$endDate) {
                // For loans without endDate, calculate months since start (no remaining concept)
                $monthsElapsed = (int) round(($maxDate->diff($startDate)->days) / 30);
                $monthsRemaining = 0; // No end date means no remaining months
            }

            return [
                'percentage' => round($percentage, 2),
                'timeProgress' => round($timeProgress, 2),
                'monthsRemaining' => $monthsRemaining,
                'startDate' => $startDate?->format('Y-m-d'),
                'endDate' => $endDate?->format('Y-m-d'),
            ];
        };

        // SPECIAL REQUEST: Get Balance loans - separate by type, show only latest active loan per type
        // Active loan = status = 0 AND endDate >= maxDate (loan is not expired)
        // Get distinct balance types
        $balanceTypes = $this->balanceRepository->createQueryBuilder('b')
            ->select('DISTINCT b.type')
            ->where('b.member = :member')
            ->andWhere('b.status = 0')
            ->andWhere('b.endDate >= :maxDate')
            ->setParameter('member', $member)
            ->setParameter('maxDate', $maxDate)
            ->getQuery()
            ->getResult();

        foreach ($balanceTypes as $typeRow) {
            $balanceType = $typeRow['type'];
            
            // Get latest active balance loan for this type
            $latestBalance = $this->balanceRepository->createQueryBuilder('b')
                ->where('b.member = :member')
                ->andWhere('b.status = 0')
                ->andWhere('b.type = :balanceType')
                ->andWhere('b.endDate >= :maxDate')
                ->setParameter('member', $member)
                ->setParameter('balanceType', $balanceType)
                ->setParameter('maxDate', $maxDate)
                ->orderBy('b.startDate', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

            if ($latestBalance) {
                $outstanding = $this->outstandingRepository->findOneBy(['member' => $member]);
                $paidAmount = $outstanding ? ($outstanding->getContribution() ?? 0) : 0;
                
                $progress = $calculateProgress($latestBalance, $paidAmount);
                
                // Calculate balance (remaining amount)
                $loanAmount = (float) $latestBalance->getAmount();
                $balance = max(0, $loanAmount - $paidAmount);

                $cards[] = [
                    'title' => 'Balance (Type ' . $balanceType . ')',
                    'amount' => $loanAmount,
                    'type' => 'balance',
                    'balanceType' => $balanceType,
                    'progress' => $progress,
                    'paidAmount' => round($paidAmount, 2),
                    'balance' => round($balance, 2),
                ];
                $totalOutstanding += $loanAmount;
                $totalPaid += $paidAmount;
                $totalBalance += $balance;
            }
        }

        // SPECIAL REQUEST: Get Essential Commodity loans - show only latest active loan
        // Active loan = status = 0 AND endDate >= maxDate (loan is not expired)
        $latestEssential = $this->essentialCommodityRepository->createQueryBuilder('ec')
            ->where('ec.member = :member')
            ->andWhere('ec.status = 0')
            ->andWhere('ec.endDate >= :maxDate')
            ->setParameter('member', $member)
            ->setParameter('maxDate', $maxDate)
            ->orderBy('ec.startDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($latestEssential) {
            $paidAmount = $this->getPaidAmountForLoan($member, 'essential', $latestEssential->getStartDate(), $latestEssential->getEndDate()) ?? 0;
            $progress = $calculateProgress($latestEssential, $paidAmount);
            
            // Calculate balance (remaining amount)
            $loanAmount = (float) $latestEssential->getAmount();
            $balance = max(0, $loanAmount - $paidAmount);

            $cards[] = [
                'title' => 'Essential Commodity',
                'amount' => $loanAmount,
                'type' => 'essential',
                'progress' => $progress,
                'paidAmount' => round($paidAmount, 2),
                'balance' => round($balance, 2),
            ];
            $totalOutstanding += $loanAmount;
            $totalPaid += $paidAmount;
            $totalBalance += $balance;
        }

        // SPECIAL REQUEST: Get Fixed Asset Loan - show only latest active loan
        // Active loan = status = 0 AND endDate >= maxDate (loan is not expired)
        $latestFixedAsset = $this->fixedAssetLoanRepository->createQueryBuilder('fal')
            ->where('fal.member = :member')
            ->andWhere('fal.status = 0')
            ->andWhere('fal.endDate >= :maxDate')
            ->setParameter('member', $member)
            ->setParameter('maxDate', $maxDate)
            ->orderBy('fal.startDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($latestFixedAsset) {
            $paidAmount = $this->getPaidAmountForLoan($member, 'fixedAsset', $latestFixedAsset->getStartDate(), $latestFixedAsset->getEndDate()) ?? 0;
            $progress = $calculateProgress($latestFixedAsset, $paidAmount);
            
            // Calculate balance (remaining amount)
            $loanAmount = (float) $latestFixedAsset->getAmount();
            $balance = max(0, $loanAmount - $paidAmount);

            $cards[] = [
                'title' => 'Fixed Asset Loan',
                'amount' => $loanAmount,
                'type' => 'fixed_asset',
                'progress' => $progress,
                'paidAmount' => round($paidAmount, 2),
                'balance' => round($balance, 2),
            ];
            $totalOutstanding += $loanAmount;
            $totalPaid += $paidAmount;
            $totalBalance += $balance;
        }

        // SPECIAL REQUEST: Get Layya loans - show only latest active loan
        // Active loan = status = 0 AND endDate >= maxDate (loan is not expired)
        $latestLayya = $this->layyaRepository->createQueryBuilder('l')
            ->where('l.member = :member')
            ->andWhere('l.status = 0')
            ->andWhere('l.endDate >= :maxDate')
            ->setParameter('member', $member)
            ->setParameter('maxDate', $maxDate)
            ->orderBy('l.startDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($latestLayya) {
            $paidAmount = $this->getPaidAmountForLoan($member, 'layya', $latestLayya->getStartDate(), $latestLayya->getEndDate()) ?? 0;
            $progress = $calculateProgress($latestLayya, $paidAmount);
            
            // Calculate balance (remaining amount)
            $loanAmount = (float) $latestLayya->getAmount();
            $balance = max(0, $loanAmount - $paidAmount);

            $cards[] = [
                'title' => 'Layya',
                'amount' => $loanAmount,
                'type' => 'layya',
                'progress' => $progress,
                'paidAmount' => round($paidAmount, 2),
                'balance' => round($balance, 2),
            ];
            $totalOutstanding += $loanAmount;
            $totalPaid += $paidAmount;
            $totalBalance += $balance;
        }

        // SPECIAL REQUEST: Get Soft Loan - show only latest active loan
        // Active loan = status = 0 AND endDate >= maxDate (loan is not expired)
        $latestSoftLoan = $this->softLoanRepository->createQueryBuilder('sl')
            ->where('sl.member = :member')
            ->andWhere('sl.status = 0')
            ->andWhere('sl.endDate >= :maxDate')
            ->setParameter('member', $member)
            ->setParameter('maxDate', $maxDate)
            ->orderBy('sl.startDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($latestSoftLoan) {
            $paidAmount = $this->getPaidAmountForLoan($member, 'softloan', $latestSoftLoan->getStartDate(), $latestSoftLoan->getEndDate()) ?? 0;
            $progress = $calculateProgress($latestSoftLoan, $paidAmount);
            
            // Calculate balance (remaining amount)
            $loanAmount = (float) $latestSoftLoan->getAmount();
            $balance = max(0, $loanAmount - $paidAmount);

            $cards[] = [
                'title' => 'Soft Loan',
                'amount' => $loanAmount,
                'type' => 'soft_loan',
                'progress' => $progress,
                'paidAmount' => round($paidAmount, 2),
                'balance' => round($balance, 2),
            ];
            $totalOutstanding += $loanAmount;
            $totalPaid += $paidAmount;
            $totalBalance += $balance;
        }

        // SPECIAL REQUEST: Get Watanda loans - show only latest active loan (no endDate, only startDate)
        // Active loan = status = 0 (Watanda doesn't have endDate, so only check status)
        $latestWatanda = $this->watandaRepository->createQueryBuilder('w')
            ->where('w.member = :member')
            ->andWhere('w.status = 0')
            ->setParameter('member', $member)
            ->orderBy('w.startDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($latestWatanda) {
            $loanAmount = is_string($latestWatanda->getAmount()) ? (float) $latestWatanda->getAmount() : ($latestWatanda->getAmount() ?? 0);
            $paidAmount = $this->getPaidAmountForLoan($member, 'watanda', $latestWatanda->getStartDate(), null) ?? 0;
            // Watanda doesn't have endDate, so pass false for hasEndDate
            $progress = $calculateProgress($latestWatanda, $paidAmount, false);
            
            // Calculate balance (remaining amount)
            $balance = max(0, $loanAmount - $paidAmount);

            $cards[] = [
                'title' => 'Watanda',
                'amount' => $loanAmount,
                'type' => 'watanda',
                'progress' => $progress,
                'paidAmount' => round($paidAmount, 2),
                'balance' => round($balance, 2),
            ];
            $totalOutstanding += $loanAmount;
            $totalPaid += $paidAmount;
            $totalBalance += $balance;
        }

        // Add overall total card with paid and balance
        $totalPercentage = $totalOutstanding > 0 ? min(100.0, ($totalPaid / $totalOutstanding) * 100) : 0.0;
        $cards[] = [
            'title' => 'Overall Total',
            'amount' => round($totalOutstanding, 2),
            'type' => 'total',
            'paidAmount' => round($totalPaid, 2),
            'balance' => round($totalBalance, 2),
            'progress' => [
                'percentage' => round($totalPercentage, 2),
                'timeProgress' => 0.0,
                'monthsRemaining' => 0,
                'startDate' => null,
                'endDate' => null,
            ],
        ];

        $response = new MemberOutstandingLoansResponse();
        $response->cards = $cards;

        return new JsonResponse($response, Response::HTTP_OK);
    }

    /**
     * Get paid amount for a loan from monthly deductions
     * Only counts payments between loan start date and end date (or current date if no end date)
     */
    private function getPaidAmountForLoan($member, string $loanType, ?\DateTimeInterface $loanStartDate, ?\DateTimeInterface $loanEndDate = null): ?float
    {
        if (!$loanStartDate) {
            return null;
        }

        $fieldMap = [
            'essential' => 'essential',
            'fixedAsset' => 'fixedAsset',
            'layya' => 'layya',
            'softloan' => 'softloan',
            'watanda' => 'watanda',
        ];

        if (!isset($fieldMap[$loanType])) {
            return null;
        }

        $field = $fieldMap[$loanType];
        
        $qb = $this->monthlyDeductionRepository->createQueryBuilder('md')
            ->select('COALESCE(SUM(md.' . $field . '), 0)')
            ->where('md.member = :member')
            ->andWhere('md.date >= :loanStartDate')
            ->setParameter('member', $member)
            ->setParameter('loanStartDate', $loanStartDate);
        
        // If loan has end date, only count payments up to end date
        if ($loanEndDate) {
            $qb->andWhere('md.date <= :loanEndDate')
               ->setParameter('loanEndDate', $loanEndDate);
        }
        
        $paidAmount = $qb->getQuery()->getSingleScalarResult();

        return (float) $paidAmount;
    }
}

