<?php

namespace App\DataFixtures;

use App\Entity\Fee;
use App\Entity\PercentageFeeRate;
use App\Enum\FeeName;
use App\Enum\VehicleTypeName;
use App\Repository\FeesRepository;
use App\Repository\VehicleTypeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Exception;

class PercentageFeeRateFixtures extends Fixture implements DependentFixtureInterface
{
    private FeesRepository $feesRepository;
    private VehicleTypeRepository $vehicleTypeRepository;

    public function __construct(FeesRepository $feesRepository, VehicleTypeRepository $vehicleTypeRepository)
    {
        $this->feesRepository = $feesRepository;
        $this->vehicleTypeRepository = $vehicleTypeRepository;
    }

    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $feeRates = $this->getFeeRates();

        foreach ($feeRates as $feeRate) {
            $feeRateEntity = new PercentageFeeRate();
            $feeRateEntity->setFee($feeRate['fee']);
            $feeRateEntity->setVehicleTypeId($feeRate['vehicleTypeId']);
            $feeRateEntity->setRate($feeRate['rate']);
            $feeRateEntity->setMinAmount($feeRate['minAmount']);
            $feeRateEntity->setMaxAmount($feeRate['maxAmount']);

            $manager->persist($feeRateEntity);
        }

        $manager->flush();
    }

    /**
     * @return array<int, array<string, int|float|null|Fee>>
     * @throws Exception
     */
    private function getFeeRates(): array
    {
        $basicBuyerFee = $this->feesRepository->findOneBy(['name' => FeeName::BASIC]);

        if (!$basicBuyerFee) {
            throw new Exception(FeeName::BASIC->value . ' not found');
        }

        $sellerSpecialFee = $this->feesRepository->findOneBy(['name' => FeeName::SPECIAL]);

        if (!$sellerSpecialFee) {
            throw new Exception(FeeName::SPECIAL->value . ' not found');
        }

        $commonVehicleType = $this->vehicleTypeRepository->findOneBy(['name' => VehicleTypeName::COMMON]);
        $commonVehicleTypeId = $commonVehicleType->getId();

        $luxuryVehicleType = $this->vehicleTypeRepository->findOneBy(['name' => VehicleTypeName::LUXURY]);
        $luxuryVehicleTypeId = $luxuryVehicleType->getId();

        return [
            [
                'fee' => $basicBuyerFee,
                'vehicleTypeId' => $commonVehicleTypeId,
                'rate' => 0.1,
                'minAmount' => 10,
                'maxAmount' => 50,
            ],
            [
                'fee' => $basicBuyerFee,
                'vehicleTypeId' => $luxuryVehicleTypeId,
                'rate' => 0.1,
                'minAmount' => 25,
                'maxAmount' => 200,
            ],
            [
                'fee' => $sellerSpecialFee,
                'vehicleTypeId' => $commonVehicleTypeId,
                'rate' => 0.02,
                'minAmount' => null,
                'maxAmount' => null,
            ],
            [
                'fee' => $sellerSpecialFee,
                'vehicleTypeId' => $luxuryVehicleTypeId,
                'rate' => 0.04,
                'minAmount' => null,
                'maxAmount' => null,
            ],
        ];
    }

    public function getDependencies(): array
    {
        return [FeeFixtures::class, VehicleTypeFixtures::class];
    }
}
