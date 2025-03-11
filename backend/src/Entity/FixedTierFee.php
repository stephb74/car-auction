<?php

namespace App\Entity;

use App\Repository\FixedTierFeeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FixedTierFeeRepository::class)]
class FixedTierFee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $feeId = null;

    #[ORM\Column(nullable: true)]
    private ?float $minAmount = null;

    #[ORM\Column(nullable: true)]
    private ?float $maxAmount = null;

    #[ORM\Column]
    private ?float $amount = null;

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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }
}
