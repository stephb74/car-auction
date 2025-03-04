<?php

declare(strict_types=1);

namespace App\Controller\Api\v1;

use App\Repository\VehicleTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class VehicleTypeController extends AbstractController
{
    private VehicleTypeRepository $vehicleTypeRepository;

    public function __construct(VehicleTypeRepository $vehicleTypeRepository)
    {
        $this->vehicleTypeRepository = $vehicleTypeRepository;
    }

    #[Route('/api/v1/vehicles/types')]
    public function index(): JsonResponse
    {
        return $this->json(['types' => $this->vehicleTypeRepository->findAll()]);
    }
}
