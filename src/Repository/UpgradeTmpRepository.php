<?php

namespace App\Repository;

use App\Entity\UpgradeTmp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UpgradeTmp>
 */
class UpgradeTmpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UpgradeTmp::class);
    }
}
