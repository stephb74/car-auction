<?php

namespace App\DataFixtures;

use App\Entity\VehicleType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VehicleTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $types = ['Common', 'Luxury'];

        foreach ($types as $type) {
            $vehicleType = new VehicleType();
            $vehicleType->setName($type);
            $manager->persist($vehicleType);
        }

        $manager->flush();
    }
}
