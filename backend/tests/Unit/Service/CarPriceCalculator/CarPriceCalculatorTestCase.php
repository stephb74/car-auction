<?php
declare(strict_types=1);

namespace App\Tests\Unit\Service\CarPriceCalculator;

use App\Entity\Fee;
use App\Entity\FixedFee;
use App\Entity\FixedTierFee;
use App\Entity\PercentageFeeRate;
use App\Enum\FeeName;
use App\Enum\FeeTypeName;
use App\Repository\FeesRepository;
use App\Repository\PercentageFeeRatesRepository;
use App\Service\CarPriceCalculator;
use ArrayIterator;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class CarPriceCalculatorTestCase extends TestCase
{
    protected CarPriceCalculator $calculator;
    protected FeesRepository&MockObject $feesRepositoryMock;
    protected PercentageFeeRatesRepository&MockObject $percentageFeeRatesRepositoryMock;

    protected static int $commonVehicleTypeId = 1;
    protected static int $luxuryVehicleTypeId = 2;

    protected int $basicBuyerFeeId = 1;
    protected int $sellerSpecialFeeId = 2;
    protected int $associationCostId = 3;
    protected int $storageFeeId = 4;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->percentageFeeRatesRepositoryMock = $this->createPercentageFeeRatesRepositoryMock();
        $this->feesRepositoryMock = $this->createFeesRepositoryMock();
        $this->calculator = new CarPriceCalculator($this->feesRepositoryMock);
    }

    /**
     * @throws Exception
     */
    private function createFeesRepositoryMock(): FeesRepository&MockObject
    {
        $fees = $this->getFees();
        $percentageFeeRates = $this->getPercentageFeeRates();
        $fixedFees = $this->getFixedFees();
        $fixedTieredFees = $this->getFixedTieredFees();

        foreach ($fees as $fee) {
            $feeRates = array_filter(
                $percentageFeeRates,
                fn(PercentageFeeRate $rate) => $rate->getFeeId() === $fee->getId(),
            );

            $feeRatesMock = $this->createMock(Collection::class);
            $feeRatesMock->method('toArray')->willReturn(array_values($feeRates));
            $feeRatesMock->method('isEmpty')->willReturn(empty($feeRates));
            $feeRatesMock->method('count')->willReturn(count($feeRates));
            $feeRatesMock->method('getIterator')->willReturn(new ArrayIterator($feeRates));

            $reflectionProperty = new ReflectionProperty(Fee::class, 'percentageFeeRates');
            $reflectionProperty->setValue($fee, $feeRatesMock);

            $fixedFeeRates = array_filter(
                $fixedFees,
                fn(FixedFee $fixedFee) => $fixedFee->getFeeId() === $fee->getId(),
            );

            $fixedFeesMock = $this->createMock(Collection::class);
            $fixedFeesMock->method('toArray')->willReturn(array_values($fixedFeeRates));
            $fixedFeesMock->method('isEmpty')->willReturn(empty($fixedFeeRates));
            $fixedFeesMock->method('count')->willReturn(count($fixedFeeRates));
            $fixedFeesMock->method('getIterator')->willReturn(new ArrayIterator($fixedFeeRates));

            $reflectionProperty = new ReflectionProperty(Fee::class, 'fixedFees');
            $reflectionProperty->setValue($fee, $fixedFeesMock);

            $fixedTieredFeeRates = array_filter(
                $fixedTieredFees,
                fn(FixedTierFee $fixedTierFee) => $fixedTierFee->getFeeId() === $fee->getId(),
            );

            $fixedTieredFeesMock = $this->createMock(Collection::class);
            $fixedTieredFeesMock->method('toArray')->willReturn(array_values($fixedTieredFeeRates));
            $fixedTieredFeesMock->method('isEmpty')->willReturn(empty($fixedTieredFeeRates));
            $fixedTieredFeesMock->method('count')->willReturn(count($fixedTieredFeeRates));
            $fixedTieredFeesMock->method('getIterator')->willReturn(new ArrayIterator($fixedTieredFeeRates));

            $reflectionProperty = new ReflectionProperty(Fee::class, 'fixedTierFees');
            $reflectionProperty->setValue($fee, $fixedTieredFeesMock);
        }

        $feesRepository = $this->createMock(FeesRepository::class);
        $feesRepository->method('findBy')->willReturnCallback(function ($criteria) use ($fees) {
            if (isset($criteria['type'])) {
                return array_filter($fees, fn(Fee $fee) => $fee->getType() === $criteria['type']);
            }

            return [];
        });

        return $feesRepository;
    }

    /**
     * @throws Exception
     */
    private function createPercentageFeeRatesRepositoryMock(): PercentageFeeRatesRepository&MockObject
    {
        $percentageFeeRates = $this->getPercentageFeeRates();

        $repository = $this->createMock(PercentageFeeRatesRepository::class);
        $repository
            ->method('findByVehicleTypeId')
            ->willReturnCallback(function (int $vehicleTypeId) use ($percentageFeeRates) {
                return array_filter(
                    $percentageFeeRates,
                    fn(PercentageFeeRate $rate) => $rate->getVehicleTypeId() === $vehicleTypeId,
                );
            });

        return $repository;
    }

    /**
     * @return array<int, Fee>
     */
    protected function getFees(): array
    {
        return [
            (new Fee())->setId($this->basicBuyerFeeId)->setName(FeeName::BASIC)->setType(FeeTypeName::PERCENTAGE_RATE),
            (new Fee())
                ->setId($this->sellerSpecialFeeId)
                ->setName(FeeName::SPECIAL)
                ->setType(FeeTypeName::PERCENTAGE_RATE),
            (new Fee())
                ->setId($this->associationCostId)
                ->setName(FeeName::ASSOCIATION)
                ->setType(FeeTypeName::FIXED_TIER),
            (new Fee())->setId($this->storageFeeId)->setName(FeeName::STORAGE)->setType(FeeTypeName::FIXED_FEE),
        ];
    }

    /**
     * @return array<int, PercentageFeeRate>
     */
    protected function getPercentageFeeRates(): array
    {
        return [
            /**
             * Basic buyer fee: 10% of the price of the vehicle
             * Common: minimum $10 and maximum $50
             * Luxury: Not applicable
             */
            (new PercentageFeeRate())
                ->setFeeId($this->basicBuyerFeeId)
                ->setVehicleTypeId(self::$commonVehicleTypeId)
                ->setRate(0.1)
                ->setMinAmount(10)
                ->setMaxAmount(50),

            /**
             * Basic buyer fee: 10% of the price of the vehicle
             * Luxury: minimum $25 and maximum $200
             * Common: Not applicable
             */
            (new PercentageFeeRate())
                ->setFeeId($this->basicBuyerFeeId)
                ->setVehicleTypeId(self::$luxuryVehicleTypeId)
                ->setRate(0.1)
                ->setMinAmount(25)
                ->setMaxAmount(200),

            /**
             * Seller special fee: 2% of the price of the vehicle
             * Common: no minimum or maximum amount
             * Luxury: Not applicable
             */
            (new PercentageFeeRate())
                ->setFeeId($this->sellerSpecialFeeId)
                ->setVehicleTypeId(self::$commonVehicleTypeId)
                ->setRate(0.02)
                ->setMinAmount(null)
                ->setMaxAmount(null),

            /**
             * Seller special fee: 4% of the price of the vehicle
             * Luxury: no minimum or maximum amount
             * Common: Not applicable
             */
            (new PercentageFeeRate())
                ->setFeeId($this->sellerSpecialFeeId)
                ->setVehicleTypeId(self::$luxuryVehicleTypeId)
                ->setRate(0.04)
                ->setMinAmount(null)
                ->setMaxAmount(null),
        ];
    }

    /**
     * @return array<int, FixedFee>
     */
    protected function getFixedFees(): array
    {
        return [(new FixedFee())->setFeeId($this->storageFeeId)->setAmount(100)];
    }

    /**
     * @return array<int, FixedTierFee>
     */
    protected function getFixedTieredFees(): array
    {
        return [
            (new FixedTierFee())->setFeeId($this->associationCostId)->setMinAmount(1)->setMaxAmount(500)->setAmount(5),
            (new FixedTierFee())
                ->setFeeId($this->associationCostId)
                ->setMinAmount(500)
                ->setMaxAmount(1000)
                ->setAmount(10),
            (new FixedTierFee())
                ->setFeeId($this->associationCostId)
                ->setMinAmount(1000)
                ->setMaxAmount(3000)
                ->setAmount(15),
            (new FixedTierFee())
                ->setFeeId($this->associationCostId)
                ->setMinAmount(3000)
                ->setMaxAmount(null)
                ->setAmount(20),
        ];
    }
}
