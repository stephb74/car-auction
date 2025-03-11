<?php

namespace App\DataFixtures;

use App\Entity\Fee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FeeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $fees = [
            ['name' => 'Basic Buyer Fee', 'type' => 'percentageRate'],
            ['name' => 'Seller Special Fee', 'type' => 'percentageRate'],
            ['name' => 'Association Cost', 'type' => 'fixedTier'],
            ['name' => 'Storage Fee', 'type' => 'fixedFee'],
        ];

        foreach ($fees as $fee) {
            $vehicleType = new Fee();
            $vehicleType->setName($fee['name']);
            $vehicleType->setType($fee['type']);
            $manager->persist($vehicleType);
        }

        $manager->flush();
    }
}
