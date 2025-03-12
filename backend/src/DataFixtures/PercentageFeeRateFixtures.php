<?php

namespace App\DataFixtures;

use App\Entity\PercentageFeeRate;
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
            $feeRateEntity->setFeeId($feeRate['feeId']);
            $feeRateEntity->setVehicleTypeId($feeRate['vehicleTypeId']);
            $feeRateEntity->setRate($feeRate['rate']);
            $feeRateEntity->setMinAmount($feeRate['minAmount']);
            $feeRateEntity->setMaxAmount($feeRate['maxAmount']);

            $manager->persist($feeRateEntity);
        }

        $manager->flush();
    }

    /**
     * @return array<int, array<string, int|float|null>>
     * @throws Exception
     */
    private function getFeeRates(): array
    {
        $basicBuyerFee = $this->feesRepository->findOneBy(['name' => 'Basic Buyer Fee']);

        if (!$basicBuyerFee) {
            throw new Exception('Basic Buyer Fee not found');
        }

        $sellerSpecialFee = $this->feesRepository->findOneBy(['name' => 'Seller Special Fee']);

        if (!$sellerSpecialFee) {
            throw new Exception('Seller Special Fee not found');
        }

        $commonVehicleType = $this->vehicleTypeRepository->findOneBy(['name' => 'Common']);
        $commonVehicleTypeId = $commonVehicleType->getId();

        $luxuryVehicleType = $this->vehicleTypeRepository->findOneBy(['name' => 'Luxury']);
        $luxuryVehicleTypeId = $luxuryVehicleType->getId();

        return [
            [
                'feeId' => $basicBuyerFee,
                'vehicleTypeId' => $commonVehicleTypeId,
                'rate' => 0.1,
                'minAmount' => 10,
                'maxAmount' => 50,
            ],
            [
                'feeId' => $basicBuyerFee,
                'vehicleTypeId' => $luxuryVehicleTypeId,
                'rate' => 0.1,
                'minAmount' => 25,
                'maxAmount' => 200,
            ],
            [
                'feeId' => $sellerSpecialFee,
                'vehicleTypeId' => $commonVehicleTypeId,
                'rate' => 0.02,
                'minAmount' => null,
                'maxAmount' => null,
            ],
            [
                'feeId' => $sellerSpecialFee,
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
