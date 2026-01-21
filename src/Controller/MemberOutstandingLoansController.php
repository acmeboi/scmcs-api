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
        private OutstandingRepository $outstandingRepository
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

        // Helper function to calculate progress
        $calculateProgress = function ($loan, $paidAmount = null, $hasEndDate = true) use ($now) {
            $startDate = $loan->getStartDate();
            $endDate = $hasEndDate && method_exists($loan, 'getEndDate') ? $loan->getEndDate() : null;
            $amount = is_string($loan->getAmount()) ? (float) $loan->getAmount() : ($loan->getAmount() ?? 0);

            $percentage = 0.0;
            if ($paidAmount !== null && $amount > 0) {
                $percentage = ($paidAmount / $amount) * 100;
            }

            $timeProgress = 0.0;
            $monthsRemaining = 0;
            
            if ($startDate && $endDate) {
                $totalDays = $startDate->diff($endDate)->days;
                $elapsedDays = $startDate->diff($now)->days;
                
                if ($totalDays > 0) {
                    $timeProgress = min(100, ($elapsedDays / $totalDays) * 100);
                }
                
                // Calculate months remaining
                $monthsRemaining = max(0, (int) round(($endDate->diff($now)->days) / 30));
            } elseif ($startDate && !$endDate) {
                // For loans without endDate, calculate months since start
                $monthsElapsed = (int) round(($now->diff($startDate)->days) / 30);
                $monthsRemaining = max(0, $monthsElapsed);
            }

            return [
                'percentage' => round($percentage, 2),
                'timeProgress' => round($timeProgress, 2),
                'monthsRemaining' => $monthsRemaining,
                'startDate' => $startDate?->format('Y-m-d'),
                'endDate' => $endDate?->format('Y-m-d'),
            ];
        };

        // Get Balance loans (status = 1) - aggregate total
        $balanceTotal = $this->balanceRepository->createQueryBuilder('b')
            ->select('COALESCE(SUM(b.amount), 0)')
            ->where('b.member = :member')
            ->andWhere('b.status = 1')
            ->setParameter('member', $member)
            ->getQuery()
            ->getSingleScalarResult();

        if ($balanceTotal > 0) {
            // Get latest balance loan for progress calculation
            $latestBalance = $this->balanceRepository->createQueryBuilder('b')
                ->where('b.member = :member')
                ->andWhere('b.status = 1')
                ->setParameter('member', $member)
                ->orderBy('b.startDate', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

            $outstanding = $this->outstandingRepository->findOneBy(['member' => $member]);
            $paidAmount = $outstanding ? ($outstanding->getContribution() ?? 0) : null;
            
            $progress = $latestBalance ? $calculateProgress($latestBalance, $paidAmount) : [
                'percentage' => 0.0,
                'timeProgress' => 0.0,
                'monthsRemaining' => 0,
                'startDate' => null,
                'endDate' => null,
            ];

            $cards[] = [
                'title' => 'Balance',
                'amount' => (float) $balanceTotal,
                'type' => 'balance',
                'progress' => $progress,
            ];
            $totalOutstanding += (float) $balanceTotal;
        }

        // Get Essential Commodity loans (status = 1)
        $essentialTotal = $this->essentialCommodityRepository->createQueryBuilder('ec')
            ->select('COALESCE(SUM(ec.amount), 0)')
            ->where('ec.member = :member')
            ->andWhere('ec.status = 1')
            ->setParameter('member', $member)
            ->getQuery()
            ->getSingleScalarResult();

        if ($essentialTotal > 0) {
            // Get latest essential commodity loan for progress calculation
            $latestEssential = $this->essentialCommodityRepository->createQueryBuilder('ec')
                ->where('ec.member = :member')
                ->andWhere('ec.status = 1')
                ->setParameter('member', $member)
                ->orderBy('ec.startDate', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

            $paidAmount = $latestEssential ? $this->getPaidAmountForLoan($member, 'essential', $latestEssential->getDate()) : null;
            $progress = $latestEssential ? $calculateProgress($latestEssential, $paidAmount) : [
                'percentage' => 0.0,
                'timeProgress' => 0.0,
                'monthsRemaining' => 0,
                'startDate' => null,
                'endDate' => null,
            ];

            $cards[] = [
                'title' => 'Essential Commodity',
                'amount' => (float) $essentialTotal,
                'type' => 'essential',
                'progress' => $progress,
            ];
            $totalOutstanding += (float) $essentialTotal;
        }

        // Get Fixed Asset Loan (status = 1)
        $fixedAssetTotal = $this->fixedAssetLoanRepository->createQueryBuilder('fal')
            ->select('COALESCE(SUM(fal.amount), 0)')
            ->where('fal.member = :member')
            ->andWhere('fal.status = 1')
            ->setParameter('member', $member)
            ->getQuery()
            ->getSingleScalarResult();

        if ($fixedAssetTotal > 0) {
            // Get latest fixed asset loan for progress calculation
            $latestFixedAsset = $this->fixedAssetLoanRepository->createQueryBuilder('fal')
                ->where('fal.member = :member')
                ->andWhere('fal.status = 1')
                ->setParameter('member', $member)
                ->orderBy('fal.startDate', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

            $paidAmount = $latestFixedAsset ? $this->getPaidAmountForLoan($member, 'fixedAsset', $latestFixedAsset->getDate()) : null;
            $progress = $latestFixedAsset ? $calculateProgress($latestFixedAsset, $paidAmount) : [
                'percentage' => 0.0,
                'timeProgress' => 0.0,
                'monthsRemaining' => 0,
                'startDate' => null,
                'endDate' => null,
            ];

            $cards[] = [
                'title' => 'Fixed Asset Loan',
                'amount' => (float) $fixedAssetTotal,
                'type' => 'fixed_asset',
                'progress' => $progress,
            ];
            $totalOutstanding += (float) $fixedAssetTotal;
        }

        // Get Layya loans (status = 1)
        $layyaTotal = $this->layyaRepository->createQueryBuilder('l')
            ->select('COALESCE(SUM(l.amount), 0)')
            ->where('l.member = :member')
            ->andWhere('l.status = 1')
            ->setParameter('member', $member)
            ->getQuery()
            ->getSingleScalarResult();

        if ($layyaTotal > 0) {
            // Get latest layya loan for progress calculation
            $latestLayya = $this->layyaRepository->createQueryBuilder('l')
                ->where('l.member = :member')
                ->andWhere('l.status = 1')
                ->setParameter('member', $member)
                ->orderBy('l.startDate', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

            $paidAmount = $latestLayya ? $this->getPaidAmountForLoan($member, 'layya', $latestLayya->getDate()) : null;
            $progress = $latestLayya ? $calculateProgress($latestLayya, $paidAmount) : [
                'percentage' => 0.0,
                'timeProgress' => 0.0,
                'monthsRemaining' => 0,
                'startDate' => null,
                'endDate' => null,
            ];

            $cards[] = [
                'title' => 'Layya',
                'amount' => (float) $layyaTotal,
                'type' => 'layya',
                'progress' => $progress,
            ];
            $totalOutstanding += (float) $layyaTotal;
        }

        // Get Soft Loan (status = 1)
        $softLoanTotal = $this->softLoanRepository->createQueryBuilder('sl')
            ->select('COALESCE(SUM(sl.amount), 0)')
            ->where('sl.member = :member')
            ->andWhere('sl.status = 1')
            ->setParameter('member', $member)
            ->getQuery()
            ->getSingleScalarResult();

        if ($softLoanTotal > 0) {
            // Get latest soft loan for progress calculation
            $latestSoftLoan = $this->softLoanRepository->createQueryBuilder('sl')
                ->where('sl.member = :member')
                ->andWhere('sl.status = 1')
                ->setParameter('member', $member)
                ->orderBy('sl.startDate', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

            $paidAmount = $latestSoftLoan ? $this->getPaidAmountForLoan($member, 'softloan', $latestSoftLoan->getDate()) : null;
            $progress = $latestSoftLoan ? $calculateProgress($latestSoftLoan, $paidAmount) : [
                'percentage' => 0.0,
                'timeProgress' => 0.0,
                'monthsRemaining' => 0,
                'startDate' => null,
                'endDate' => null,
            ];

            $cards[] = [
                'title' => 'Soft Loan',
                'amount' => (float) $softLoanTotal,
                'type' => 'soft_loan',
                'progress' => $progress,
            ];
            $totalOutstanding += (float) $softLoanTotal;
        }

        // Get Watanda loans (status = 1) - Note: amount is stored as string
        $watandaLoans = $this->watandaRepository->createQueryBuilder('w')
            ->where('w.member = :member')
            ->andWhere('w.status = 1')
            ->setParameter('member', $member)
            ->getQuery()
            ->getResult();

        $watandaTotal = 0.0;
        foreach ($watandaLoans as $loan) {
            $amount = is_string($loan->getAmount()) ? (float) $loan->getAmount() : ($loan->getAmount() ?? 0);
            $watandaTotal += $amount;
        }

        if ($watandaTotal > 0) {
            // Get latest watanda loan for progress calculation
            $latestWatanda = $this->watandaRepository->createQueryBuilder('w')
                ->where('w.member = :member')
                ->andWhere('w.status = 1')
                ->setParameter('member', $member)
                ->orderBy('w.startDate', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();

            $paidAmount = $latestWatanda ? $this->getPaidAmountForLoan($member, 'watanda', $latestWatanda->getStartDate()) : null;
            // Watanda doesn't have endDate, so pass false for hasEndDate
            $progress = $latestWatanda ? $calculateProgress($latestWatanda, $paidAmount, false) : [
                'percentage' => 0.0,
                'timeProgress' => 0.0,
                'monthsRemaining' => 0,
                'startDate' => null,
                'endDate' => null,
            ];

            $cards[] = [
                'title' => 'Watanda',
                'amount' => (float) $watandaTotal,
                'type' => 'watanda',
                'progress' => $progress,
            ];
            $totalOutstanding += $watandaTotal;
        }

        // Add overall total card
        $cards[] = [
            'title' => 'Overall Total',
            'amount' => round($totalOutstanding, 2),
            'type' => 'total',
        ];

        $response = new MemberOutstandingLoansResponse();
        $response->cards = $cards;

        return new JsonResponse($response, Response::HTTP_OK);
    }

    /**
     * Get paid amount for a loan from monthly deductions
     */
    private function getPaidAmountForLoan($member, string $loanType, ?\DateTimeInterface $loanDate): ?float
    {
        if (!$loanDate) {
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
        
        $paidAmount = $this->monthlyDeductionRepository->createQueryBuilder('md')
            ->select('COALESCE(SUM(md.' . $field . '), 0)')
            ->where('md.member = :member')
            ->andWhere('md.date >= :loanDate')
            ->setParameter('member', $member)
            ->setParameter('loanDate', $loanDate)
            ->getQuery()
            ->getSingleScalarResult();

        return (float) $paidAmount;
    }
}

