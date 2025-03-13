<?php

namespace App\Tests\Unit\Service\CarPriceCalculator;

use App\Entity\VehicleType;
use App\Enum\FeeName;
use App\Enum\VehicleTypeName;
use App\Service\CarPriceCalculator;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionException;
use ReflectionMethod;

class CalculateFeeRatesTest extends CarPriceCalculatorTestCase
{
    protected ReflectionMethod $calculateFeeRatesReflection;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculateFeeRatesReflection = new ReflectionMethod(
            CarPriceCalculator::class,
            'calculatePercentageRateFees',
        );
    }

    /**
     * @return array<string, list<mixed>>
     */
    public static function dataProvider(): array
    {
        return [
            'Common vehicle type, price $398' => [
                398,
                VehicleTypeName::COMMON,
                self::$commonVehicleTypeId,
                FeeName::BASIC,
                39.8,
            ],
            'Luxury vehicle type, price $1800' => [
                1800,
                VehicleTypeName::LUXURY,
                self::$luxuryVehicleTypeId,
                FeeName::BASIC,
                180,
            ],
        ];
    }

    /**
     * @throws ReflectionException
     */
    #[DataProvider('dataProvider')]
    public function testCalculateFeeRates(
        float $vehiclePrice,
        VehicleTypeName $vehicleTypeName,
        int $vehicleTypeId,
        FeeName $expectedFeeName,
        float $expectedFeeRate,
    ): void {
        // Arrange
        $vehicleType = new VehicleType();
        $vehicleType->setName($vehicleTypeName);
        $vehicleType->setId($vehicleTypeId);

        // Act
        $feeRates = $this->calculateFeeRatesReflection->invoke($this->calculator, $vehiclePrice, $vehicleType);

        // Assert
        $this->assertArrayHasKey($expectedFeeName->value, $feeRates);
        $this->assertEquals($expectedFeeRate, $feeRates[$expectedFeeName->value]);
    }
}
