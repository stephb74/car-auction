<?php

namespace App\Entity;

use App\Repository\FixedFeeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FixedFeeRepository::class)]
#[ORM\Table(name: 'fixed_fees')]
class FixedFee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private ?int $feeId = null;

    #[ORM\Column]
    private ?float $amount = null;

    /**
     * @ORM\ManyToOne(targetEntity=Fee::class, inversedBy="fixedFees")
     * @ORM\JoinColumn(nullable=false)
     */
    #[ORM\ManyToOne(targetEntity: Fee::class, inversedBy: 'fixedFees')]
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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

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
