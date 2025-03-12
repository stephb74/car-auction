<?php

namespace App\Entity;

use App\Repository\FeesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FeesRepository::class)]
#[ORM\Table(name: 'fees')]
class Fee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    /**
     * @var Collection<int, PercentageFeeRate>
     */
    #[ORM\OneToMany(targetEntity: PercentageFeeRate::class, mappedBy: 'fee')]
    private Collection $percentageFeeRates;

    /**
     * @var Collection<int, FixedFee>
     */
    #[ORM\OneToMany(targetEntity: FixedFee::class, mappedBy: 'fee')]
    private Collection $fixedFees;

    /**
     * @var Collection<int, FixedTierFee>
     */
    #[ORM\OneToMany(targetEntity: FixedTierFee::class, mappedBy: 'fee')]
    private Collection $fixedTierFees;

    public function __construct()
    {
        $this->percentageFeeRates = new ArrayCollection();
        $this->fixedFees = new ArrayCollection();
        $this->fixedTierFees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, PercentageFeeRate>
     */
    public function getPercentageFeeRates(): Collection
    {
        return $this->percentageFeeRates;
    }

    /**
     * @return Collection<int, FixedFee>
     */
    public function getFixedFees(): Collection
    {
        return $this->fixedFees;
    }

    /**
     * @return Collection<int, FixedTierFee>
     */
    public function getFixedTierFees(): Collection
    {
        return $this->fixedTierFees;
    }
}
