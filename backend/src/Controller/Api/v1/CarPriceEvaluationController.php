<?php

declare(strict_types=1);

namespace App\Controller\Api\v1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CarPriceEvaluationController extends AbstractController
{
    #[Route('/api/v1/car-price-evaluation')]
    public function calculate(): JsonResponse
    {
        return $this->json([
            'price' => 1000,
        ]);
    }
}
