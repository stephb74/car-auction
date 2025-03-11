<?php
declare(strict_types=1);

namespace App\Tests\Unit\Service\CarPriceCalculator;

use App\Entity\Fee;
use App\Entity\PercentageFeeRate;
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

    protected int $commonVehicleTypeId = 1;
    protected int $luxuryVehicleTypeId = 2;

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

        // Set up fee rates collections for each fee
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
            (new Fee())->setId($this->basicBuyerFeeId)->setName('Basic Buyer Fee')->setType('percentageRate'),
            (new Fee())->setId($this->sellerSpecialFeeId)->setName('Seller Special Fee')->setType('percentageRate'),
            (new Fee())->setId($this->associationCostId)->setName('Association Cost')->setType('fixedTier'),
            (new Fee())->setId($this->storageFeeId)->setName('Storage Fee')->setType('fixedFee'),
        ];
    }

    /**
     * @return array<int, PercentageFeeRate>
     */
    protected function getPercentageFeeRates(): array
    {
        return [
            (new PercentageFeeRate())
                ->setFeeId($this->basicBuyerFeeId)
                ->setVehicleTypeId($this->commonVehicleTypeId)
                ->setRate(0.1)
                ->setMinAmount(10)
                ->setMaxAmount(50),

            (new PercentageFeeRate())
                ->setFeeId($this->basicBuyerFeeId)
                ->setVehicleTypeId($this->luxuryVehicleTypeId)
                ->setRate(0.1)
                ->setMinAmount(25)
                ->setMaxAmount(200),

            (new PercentageFeeRate())
                ->setFeeId($this->sellerSpecialFeeId)
                ->setVehicleTypeId($this->commonVehicleTypeId)
                ->setRate(0.02)
                ->setMinAmount(null)
                ->setMaxAmount(null),

            (new PercentageFeeRate())
                ->setFeeId($this->sellerSpecialFeeId)
                ->setVehicleTypeId($this->luxuryVehicleTypeId)
                ->setRate(0.04)
                ->setMinAmount(null)
                ->setMaxAmount(null),
        ];
    }
}
