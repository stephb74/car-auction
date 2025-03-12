<?php

namespace App\Tests\Unit\Service\CarPriceCalculator;

use App\Entity\VehicleType;
use App\Enum\FeeName;
use App\Enum\FeeTypeName;
use App\Enum\VehicleTypeName;
use PHPUnit\Framework\Attributes\DataProvider;

class CalculateAllTest extends CarPriceCalculatorTestCase
{
    /**
     * @return array<string, array<string, array<string, float|int|string|VehicleTypeName>>>
     */
    public static function dataProvider(): array
    {
        return [
            'Common vehicle type, price $398' => [
                'vehicle' => [
                    'typeId' => self::$commonVehicleTypeId,
                    'price' => 398,
                    'type' => VehicleTypeName::COMMON,
                ],
                'expected' => [
                    FeeName::BASIC->value => 39.8,
                    FeeName::SPECIAL->value => 7.96,
                    FeeName::ASSOCIATION->value => 5,
                    FeeName::STORAGE->value => 100,
                    'Total' => 550.76,
                ],
            ],
            'Common vehicle type, price $501' => [
                'vehicle' => [
                    'typeId' => self::$commonVehicleTypeId,
                    'price' => 501,
                    'type' => VehicleTypeName::COMMON,
                ],
                'expected' => [
                    FeeName::BASIC->value => 50,
                    FeeName::SPECIAL->value => 10.02,
                    FeeName::ASSOCIATION->value => 10,
                    FeeName::STORAGE->value => 100,
                    'Total' => 671.02,
                ],
            ],
            'Common vehicle type, price $57' => [
                'vehicle' => [
                    'typeId' => self::$commonVehicleTypeId,
                    'price' => 57,
                    'type' => VehicleTypeName::COMMON,
                ],
                'expected' => [
                    FeeName::BASIC->value => 10,
                    FeeName::SPECIAL->value => 1.14,
                    FeeName::ASSOCIATION->value => 5,
                    FeeName::STORAGE->value => 100,
                    'Total' => 173.14,
                ],
            ],
            'Luxury vehicle type, price $1800' => [
                'vehicle' => [
                    'typeId' => self::$luxuryVehicleTypeId,
                    'price' => 1800,
                    'type' => VehicleTypeName::LUXURY,
                ],
                'expected' => [
                    FeeName::BASIC->value => 180,
                    FeeName::SPECIAL->value => 72,
                    FeeName::ASSOCIATION->value => 15,
                    FeeName::STORAGE->value => 100,
                    'Total' => 2167,
                ],
            ],
            'Common vehicle type, price $1100' => [
                'vehicle' => [
                    'typeId' => self::$commonVehicleTypeId,
                    'price' => 1100,
                    'type' => VehicleTypeName::COMMON,
                ],
                'expected' => [
                    FeeName::BASIC->value => 50,
                    FeeName::SPECIAL->value => 22,
                    FeeName::ASSOCIATION->value => 15,
                    FeeName::STORAGE->value => 100,
                    'Total' => 1287,
                ],
            ],
            'Luxury vehicle type, price $1000000' => [
                'vehicle' => [
                    'typeId' => self::$luxuryVehicleTypeId,
                    'price' => 1000000,
                    'type' => VehicleTypeName::LUXURY,
                ],
                'expected' => [
                    FeeName::BASIC->value => 200,
                    FeeName::SPECIAL->value => 40000,
                    FeeName::ASSOCIATION->value => 20,
                    FeeName::STORAGE->value => 100,
                    'Total' => 1040320,
                ],
            ],
        ];
    }

    /**
     * @param array{typeId: int, price: int, type: VehicleTypeName} $vehicle
     * @param array<string, float|int> $expected
     * @return void
     */
    #[DataProvider('dataProvider')]
    public function testCalculateAll(array $vehicle, array $expected): void
    {
        // Arrange
        $vehicleType = new VehicleType();
        $vehicleType->setId($vehicle['typeId']);
        $vehicleType->setName($vehicle['type']);

        // Act
        $result = $this->calculator->calculateAll($vehicle['price'], $vehicleType);

        // Assert
        $this->assertArrayHasKey(FeeTypeName::PERCENTAGE_RATE->value, $result);
        $this->assertArrayHasKey(FeeTypeName::FIXED_TIER->value, $result);
        $this->assertArrayHasKey(FeeTypeName::FIXED_FEE->value, $result);
        $this->assertArrayHasKey('total', $result);

        $this->assertArrayHasKey(FeeName::BASIC->value, $result[FeeTypeName::PERCENTAGE_RATE->value]);
        $this->assertArrayHasKey(FeeName::SPECIAL->value, $result[FeeTypeName::PERCENTAGE_RATE->value]);
        $this->assertArrayHasKey(FeeName::ASSOCIATION->value, $result[FeeTypeName::FIXED_TIER->value]);
        $this->assertArrayHasKey(FeeName::STORAGE->value, $result[FeeTypeName::FIXED_FEE->value]);

        $this->assertEquals(
            $expected[FeeName::BASIC->value],
            $result[FeeTypeName::PERCENTAGE_RATE->value][FeeName::BASIC->value],
        );
    }
}
