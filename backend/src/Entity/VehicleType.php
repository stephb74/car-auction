<?php

namespace App\Entity;

use App\Enum\VehicleTypeName;
use App\Repository\VehicleTypeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehicleTypeRepository::class)]
#[ORM\Table(name: 'vehicle_types')]
class VehicleType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, enumType: VehicleTypeName::class)]
    private ?VehicleTypeName $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?VehicleTypeName
    {
        return $this->name;
    }

    public function setName(VehicleTypeName $name): static
    {
        $this->name = $name;

        return $this;
    }
}
