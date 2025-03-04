<?php

namespace App\Tests\Unit\Service\CarPriceCalculator;

use App\Entity\PercentageFeeRate;
use App\Service\CarPriceCalculator;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionException;
use ReflectionMethod;

class CapFeeRateTest extends CarPriceCalculatorTestCase
{
    protected ReflectionMethod $capFeeRateReflection;

    protected function setUp(): void
    {
        parent::setUp();
        $this->capFeeRateReflection = new ReflectionMethod(CarPriceCalculator::class, 'capFeeRate');
    }

    /**
     * @return array<string, array<int, float|PercentageFeeRate|float>>
     */
    public static function dataProvider(): array
    {
        $feeRate = self::createFeeRate();

        return [
            'calculated fee is between min and max amount' => [25, $feeRate, 25],
            'calculated fee is less than min amount' => [5, $feeRate, 10],
            'calculated fee is greater than max amount' => [60, $feeRate, 50],
        ];
    }

    /**
     * @test
     * @dataProvider dataProvider
     * @throws ReflectionException
     */
    #[DataProvider('dataProvider')]
    public function testCapFeeRate(float $calculatedFee, PercentageFeeRate $feeRate, float $expected): void
    {
        // Act
        $cappedFeeRate = $this->capFeeRateReflection->invoke($this->calculator, $calculatedFee, $feeRate);

        // Assert
        $this->assertEquals($expected, $cappedFeeRate);
    }

    private static function createFeeRate(): PercentageFeeRate
    {
        $feeRate = new PercentageFeeRate();
        $feeRate->setMinAmount(10);
        $feeRate->setMaxAmount(50);

        return $feeRate;
    }
}
