<?php

namespace App\Service;

use App\Entity\PercentageFeeRate;
use App\Entity\VehicleType;
use App\Repository\FeesRepository;

class CarPriceCalculator
{
    private FeesRepository $feesRepository;

    public function __construct(FeesRepository $feesRepository)
    {
        $this->feesRepository = $feesRepository;
    }

    /**
     * @param float $vehiclePrice
     * @param VehicleType $vehicleType
     * @return array<string, array<string, float>>
     */
    public function calculateAll(float $vehiclePrice, VehicleType $vehicleType): array
    {
        return [
            'feeRates' => $this->calculateFeeRates($vehiclePrice, $vehicleType),
        ];
    }

    /**
     * @param float $vehiclePrice
     * @param VehicleType $vehicleType
     * @return array<string, float>
     */
    private function calculateFeeRates(float $vehiclePrice, VehicleType $vehicleType): array
    {
        $fees = $this->feesRepository->findBy(['type' => 'percentageRate']);
        $calculatedFees = [];

        foreach ($fees as $fee) {
            $feeRates = $fee->getPercentageFeeRates();

            foreach ($feeRates as $feeRate) {
                if ($this->feeRateIsApplicable($feeRate, $vehicleType)) {
                    $calculatedFees[$fee->getName()] = $this->capFeeRate($feeRate->getRate() * $vehiclePrice, $feeRate);
                }
            }
        }

        return $calculatedFees;
    }

    /**
     * Check if the calculated fee rate is between the min and max amount, which can both be null.
     * If the calculated rate is not between the min and max amount, cap it at the min or max amount.
     * @param float $calculatedFee
     * @param PercentageFeeRate $feeRate
     * @return float
     */
    private function capFeeRate(float $calculatedFee, PercentageFeeRate $feeRate): float
    {
        if ($this->amountIsBetween($calculatedFee, $feeRate->getMinAmount(), $feeRate->getMaxAmount())) {
            return round($calculatedFee, 2);
        }

        if ($calculatedFee < $feeRate->getMinAmount()) {
            return round($feeRate->getMinAmount());
        }

        return round($feeRate->getMaxAmount(), 2);
    }

    /**
     * @param PercentageFeeRate $feeRate
     * @param VehicleType $vehicleType
     * @return bool
     */
    private function feeRateIsApplicable(PercentageFeeRate $feeRate, VehicleType $vehicleType): bool
    {
        return $feeRate->getVehicleTypeId() === $vehicleType->getId();
    }

    /**
     * @param float $price
     * @param float|null $min
     * @param float|null $max
     * @return bool
     */
    private function amountIsBetween(float $price, float|null $min, float|null $max): bool
    {
        if ($min === null && $max === null) {
            return true;
        }

        if ($min === null) {
            return $price <= $max;
        }

        if ($max === null) {
            return $price >= $min;
        }

        return $price >= $min && $price <= $max;
    }
}
