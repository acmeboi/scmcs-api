<?php

namespace App\Repository;

use App\Entity\EssentialCommodity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EssentialCommodity>
 */
class EssentialCommodityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EssentialCommodity::class);
    }
}
