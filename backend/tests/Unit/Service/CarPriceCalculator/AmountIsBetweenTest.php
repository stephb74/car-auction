<?php

namespace App\Tests\Unit\Service\CarPriceCalculator;

use App\Service\CarPriceCalculator;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionException;
use ReflectionMethod;

class AmountIsBetweenTest extends CarPriceCalculatorTestCase
{
    protected ReflectionMethod $amountIsBetweenReflection;

    protected function setUp(): void
    {
        parent::setUp();
        $this->amountIsBetweenReflection = new ReflectionMethod(CarPriceCalculator::class, 'amountIsBetween');
    }

    /**
     * @return array<string, array<int, float|int|null|bool>>
     */
    public static function dataProvider(): array
    {
        return [
            '1000 is between 500 and 1500' => [1000, 500, 1500, true],
            '1000 is between 1500 and 2000' => [1000, 1500, 2000, false],
            '1000 is greater than 500' => [1000, 500, null, true],
            '1000 is greater than 1500' => [1000, 1500, null, false],
            '1000 is less than 1500' => [1000, null, 1500, true],
            '1000 is less than 500' => [1000, null, 500, false],
        ];
    }

    /**
     * @test
     * @throws ReflectionException
     */
    #[DataProvider('dataProvider')]
    public function testPriceIsBetween(float $price, float|null $min, float|null $max, bool $expected): void
    {
        // Act
        $priceIsBetween = $this->amountIsBetweenReflection->invoke($this->calculator, $price, $min, $max);

        // Assert
        $this->assertEquals($expected, $priceIsBetween);
    }
}
