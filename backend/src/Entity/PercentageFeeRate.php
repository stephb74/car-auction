<?php

namespace App\Entity;

use App\Repository\PercentageFeeRatesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PercentageFeeRatesRepository::class)]
#[ORM\Table(name: 'percentage_fee_rates')]
class PercentageFeeRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private ?int $feeId = null;

    #[ORM\Column(type: 'integer')]
    private ?int $vehicleTypeId = null;

    #[ORM\Column]
    private ?float $rate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?float $minAmount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?float $maxAmount = null;

    /**
     * @ORM\ManyToOne(targetEntity=Fee::class, inversedBy="percentageFeeRates")
     * @ORM\JoinColumn(nullable=false)
     */
    #[ORM\ManyToOne(targetEntity: Fee::class, inversedBy: 'percentageFeeRates')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fee $fee = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getFeeId(): ?int
    {
        return $this->feeId;
    }

    public function setFeeId(int $feeId): static
    {
        $this->feeId = $feeId;

        return $this;
    }

    public function getVehicleTypeId(): ?int
    {
        return $this->vehicleTypeId;
    }

    public function setVehicleTypeId(int $vehicleType): static
    {
        $this->vehicleTypeId = $vehicleType;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getMinAmount(): ?float
    {
        return $this->minAmount;
    }

    public function setMinAmount(?float $minAmount): static
    {
        $this->minAmount = $minAmount;

        return $this;
    }

    public function getMaxAmount(): ?float
    {
        return $this->maxAmount;
    }

    public function setMaxAmount(?float $maxAmount): static
    {
        $this->maxAmount = $maxAmount;

        return $this;
    }

    public function getFee(): ?Fee
    {
        return $this->fee;
    }

    public function setFee(?Fee $fee): static
    {
        $this->fee = $fee;

        return $this;
    }
}
