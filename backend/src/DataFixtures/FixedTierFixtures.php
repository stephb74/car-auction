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

        $fixedTierFees = [
            ['minAmount' => 1, 'maxAmount' => 500, 'amount' => 5],
            ['minAmount' => 500, 'maxAmount' => 1000, 'amount' => 10],
            ['minAmount' => 1000, 'maxAmount' => 3000, 'amount' => 15],
            ['minAmount' => 3000, 'maxAmount' => null, 'amount' => 20],
        ];

        foreach ($fixedTierFees as $fixedTierFeeData) {
            $fixedTierFee = new FixedTierFee();
            $fixedTierFee->setFee($associationCostFee);
            $fixedTierFee->setMinAmount($fixedTierFeeData['minAmount']);
            $fixedTierFee->setMaxAmount($fixedTierFeeData['maxAmount']);
            $fixedTierFee->setAmount($fixedTierFeeData['amount']);
            $manager->persist($fixedTierFee);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [FeeFixtures::class];
    }
}
