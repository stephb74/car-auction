<?php

namespace App\DataFixtures;

use App\Entity\FixedFee;
use App\Enum\FeeName;
use App\Repository\FeesRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FixedFeeFixtures extends Fixture implements DependentFixtureInterface
{
    private FeesRepository $feesRepository;

    public function __construct(FeesRepository $feesRepository)
    {
        $this->feesRepository = $feesRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $fee = $this->feesRepository->findOneBy(['name' => FeeName::STORAGE]);

        if (!$fee) {
            return;
        }

        $fixedFee = new FixedFee();
        $fixedFee->setFee($fee);
        $fixedFee->setAmount(100.0);
        $manager->persist($fixedFee);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [FeeFixtures::class];
    }
}
