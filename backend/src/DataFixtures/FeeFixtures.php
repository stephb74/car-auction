<?php

namespace App\DataFixtures;

use App\Entity\Fee;
use App\Enum\FeeName;
use App\Enum\FeeTypeName;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FeeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $fees = [
            ['name' => FeeName::BASIC, 'type' => FeeTypeName::PERCENTAGE_RATE],
            ['name' => FeeName::SPECIAL, 'type' => FeeTypeName::PERCENTAGE_RATE],
            ['name' => FeeName::ASSOCIATION, 'type' => FeeTypeName::FIXED_TIER],
            ['name' => FeeName::STORAGE, 'type' => FeeTypeName::FIXED_FEE],
        ];

        foreach ($fees as $fee) {
            $newFee = new Fee();
            $newFee->setName($fee['name']);
            $newFee->setType($fee['type']);
            $manager->persist($newFee);
        }

        $manager->flush();
    }
}
