<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
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
     * @ORM\Column(type="string", length=36, unique=true)
     */
    protected $uuid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $name;

    /**
     * @ORM\Column(type="float")
     */
    protected float $maxCapacity;

    public function __construct(
        string $name,
        float $maxCapacity = 0.0
    ) {
        $this->name = $name;
        $this->maxCapacity = $maxCapacity;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getMaxCapacity(): float
    {
        return $this->maxCapacity;
    }
}