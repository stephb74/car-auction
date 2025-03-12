<?php

namespace App\Tests\Unit\Service\CarPriceCalculator;

use App\Enum\FeeName;
use App\Service\CarPriceCalculator;
use ReflectionException;
use ReflectionMethod;

class CalculateFixedFeesTest extends CarPriceCalculatorTestCase
{
    protected ReflectionMethod $calculateFixedFeesReflection;

    protected function setUp(): void
    {
        parent::setUp();
        $this->calculateFixedFeesReflection = new ReflectionMethod(CarPriceCalculator::class, 'calculateFixedFees');
    }

    /**
     * @throws ReflectionException
     */
    public function testCalculateFixedFees(): void
    {
        // Act
        $fixedFees = $this->calculateFixedFeesReflection->invoke($this->calculator);

        // Assert
        $this->assertArrayHasKey(FeeName::STORAGE->value, $fixedFees);
        $this->assertEquals(100, $fixedFees[FeeName::STORAGE->value]);
    }
}
