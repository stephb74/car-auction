<?php

namespace App\DataFixtures;

use App\Entity\FixedTierFee;
use App\Repository\FeesRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class FixedTierFixtures extends Fixture implements DependentFixtureInterface
{
    private FeesRepository $feesRepository;

    public function __construct(FeesRepository $feesRepository)
    {
        $this->feesRepository = $feesRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $associationCostFee = $this->feesRepository->findOneBy(['name' => 'Association Cost']);

        if (!$associationCostFee) {
            return;
        }

        $feeId = $associationCostFee->getId();

        $fixedTierFees = [
            ['feeId' => $feeId, 'minAmount' => 1, 'maxAmount' => 500, 'amount' => 5],
            ['feeId' => $feeId, 'minAmount' => 500, 'maxAmount' => 1000, 'amount' => 10],
            ['feeId' => $feeId, 'minAmount' => 1000, 'maxAmount' => 3000, 'amount' => 15],
            ['feeId' => $feeId, 'minAmount' => 3000, 'maxAmount' => null, 'amount' => 20],
        ];

        foreach ($fixedTierFees as $fixedTierFee) {
            $manager->persist(
                (new FixedTierFee())
                    ->setFeeId($fixedTierFee['feeId'])
                    ->setMinAmount($fixedTierFee['minAmount'])
                    ->setMaxAmount($fixedTierFee['maxAmount'])
                    ->setAmount($fixedTierFee['amount']),
            );
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [FeeFixtures::class];
    }
}
