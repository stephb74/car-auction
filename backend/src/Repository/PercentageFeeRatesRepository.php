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

    //    /**
    //     * @return PercentageFeeRate[] Returns an array of PercentageFeeRate objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?PercentageFeeRate
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
