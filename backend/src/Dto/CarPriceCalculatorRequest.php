<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CarPriceCalculatorRequest
{
    #[Assert\NotBlank(message: 'Price is required')]
    #[Assert\Type(type: 'numeric', message: 'Price must be a valid number')]
    #[Assert\Positive(message: 'Price must be greater than zero.')]
    #[Assert\Regex(pattern: "/^\d+(\.\d{1,2})?$/", message: 'Price must have at most 2 decimal places.')]
    public float $price;
}
