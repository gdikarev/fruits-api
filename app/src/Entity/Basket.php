<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BasketRepository")
 */
class Basket
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected int $id;

    /**
     * @ORM\Column(type="ulid", unique=true)
     */
    protected Ulid $ulid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $name;

    /**
     * @ORM\Column(type="float")
     */
    protected float $maxCapacity;

    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="basket", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected Collection $items;

    public function __construct(
        string $name,
        float $maxCapacity
    ) {
        $this->ulid = new Ulid();
        $this->items = new ArrayCollection();
        $this->name = $name;
        $this->maxCapacity = $maxCapacity;
    }

    public function update(
        string $name
    ) {
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUlid(): Ulid
    {
        return $this->ulid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMaxCapacity(): float
    {
        return $this->maxCapacity;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }
}