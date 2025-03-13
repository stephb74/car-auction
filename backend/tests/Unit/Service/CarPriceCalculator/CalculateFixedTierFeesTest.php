<?php

namespace App\Tests\Unit\Service\CarPriceCalculator;

use App\Enum\FeeName;
use App\Service\CarPriceCalculator;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionException;
use ReflectionMethod;

class CalculateFixedTierFeesTest extends CarPriceCalculatorTestCase
{
    protected ReflectionMethod $calculateFixedTierFeesReflection;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculateFixedTierFeesReflection = new ReflectionMethod(
            CarPriceCalculator::class,
            'calculateFixedTierFees',
        );
    }

    /**
     * @return array<string, list<float|int>>
     */
    public static function dataProvider(): array
    {
        return [
            'Price $100' => [100, 5],
            'Price $600' => [600, 10],
            'Price $1000' => [2000, 15],
            'Price $5000' => [5000, 20],
        ];
    }

    /**
     * @param int $price
     * @param int $expectedAmount
     * @return void
     * @throws ReflectionException
     */
    #[DataProvider('dataProvider')]
    public function testFixedTierFees(int $price, int $expectedAmount): void
    {
        // Act
        $fixedTierFees = $this->calculateFixedTierFeesReflection->invoke($this->calculator, $price);

        // Assert
        $this->assertArrayHasKey(FeeName::ASSOCIATION->value, $fixedTierFees);
        $this->assertEquals($expectedAmount, $fixedTierFees[FeeName::ASSOCIATION->value]);
    }
}
