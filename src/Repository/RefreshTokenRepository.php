<?php

namespace App\Repository;

use App\Entity\RefreshToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Gesdinet\JWTRefreshTokenBundle\Doctrine\RefreshTokenRepositoryInterface;

/**
 * @extends ServiceEntityRepository<RefreshToken>
 */
class RefreshTokenRepository extends ServiceEntityRepository implements RefreshTokenRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RefreshToken::class);
    }

    public function findInvalid(?\DateTimeInterface $datetime = null): array|\Traversable
    {
        $datetime = (null === $datetime) ? new \DateTime() : $datetime;

        return $this->createQueryBuilder('u')
            ->where('u.valid < :datetime')
            ->setParameter(':datetime', $datetime)
            ->getQuery()
            ->getResult();
    }

    public function findInvalidBatch(?\DateTimeInterface $datetime = null, ?int $batchSize = null, int $offset = 0): iterable
    {
        $datetime = (null === $datetime) ? new \DateTime() : $datetime;

        $qb = $this->createQueryBuilder('u')
            ->where('u.valid < :datetime')
            ->setParameter(':datetime', $datetime)
            ->setFirstResult($offset);

        if (null !== $batchSize) {
            $qb->setMaxResults($batchSize);
        }

        return $qb->getQuery()->getResult();
    }
}

