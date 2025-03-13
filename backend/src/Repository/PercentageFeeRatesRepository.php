<?php

namespace App\Repository;

use App\Entity\PercentageFeeRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PercentageFeeRate>
 */
class PercentageFeeRatesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PercentageFeeRate::class);
    }

    /**
     * @param int $vehicleTypeId
     * @return array<PercentageFeeRate>
     */
    public function findByVehicleTypeId(int $vehicleTypeId): array
    {
        return $this->createQueryBuilder('pfr')
            ->andWhere('pfr.vehicleType = :vehicleTypeId')
            ->setParameter('vehicleTypeId', $vehicleTypeId)
            ->getQuery()
            ->getResult();
    }
}
