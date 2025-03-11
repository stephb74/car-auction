<?php

namespace App\Tests\Unit\Service\CarPriceCalculator;

use App\Entity\VehicleType;
use App\Service\CarPriceCalculator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use ReflectionException;
use ReflectionMethod;

class CalculateFeeRatesTest extends CarPriceCalculatorTestCase
{
    protected ReflectionMethod $calculateFeeRatesReflection;
    protected MockObject $feeRateIsApplicable;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculateFeeRatesReflection = new ReflectionMethod(CarPriceCalculator::class, 'calculateFeeRates');
    }

    /**
     * @return array<string, list<float|int|string>>
     */
    public static function dataProvider(): array
    {
        return [
            'Common vehicle type, price $398' => [398, 'Common', 1, 'Basic Buyer Fee', 39.8],
            'Luxury vehicle type, price $1800' => [1800, 'Luxury', 2, 'Basic Buyer Fee', 180.0],
        ];
    }

    /**
     * @throws ReflectionException
     */
    #[DataProvider('dataProvider')]
    public function testCalculateFeeRates(
        float $vehiclePrice,
        string $vehicleTypeName,
        int $vehicleTypeId,
        string $expectedFeeName,
        float $expectedFeeRate,
    ): void {
        $vehicleType = new VehicleType();
        $vehicleType->setName($vehicleTypeName);
        $vehicleType->setId($vehicleTypeId);

        $feeRates = $this->calculateFeeRatesReflection->invoke($this->calculator, $vehiclePrice, $vehicleType);

        $this->assertArrayHasKey($expectedFeeName, $feeRates);
        $this->assertEquals($expectedFeeRate, $feeRates[$expectedFeeName]);

        print_r($feeRates);
    }
}
