<?php

declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Dto\CarPriceCalculatorRequest;
use App\Entity\VehicleType;
use App\Service\CarPriceCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1')]
class CarPriceCalculatorController extends AbstractController
{
    private CarPriceCalculator $carPriceCalculator;

    public function __construct(CarPriceCalculator $carPriceCalculator)
    {
        $this->carPriceCalculator = $carPriceCalculator;
    }

    #[Route('/calculate-car-price/{vehicleType}', methods: ['POST'])]
    public function calculate(
        #[MapRequestPayload] CarPriceCalculatorRequest $request,
        VehicleType $vehicleType,
    ): JsonResponse {
        return $this->json($this->carPriceCalculator->calculateAll($request->price, $vehicleType));
    }
}
