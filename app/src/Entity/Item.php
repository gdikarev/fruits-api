<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Item
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public int $id;

    /**
     * @ORM\Column(type="string", length=36, unique=true)
     */
    private $uuid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public string $type;

    /**
     * @ORM\Column(type="float")
     */
    public int $weight;

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function __construct(
        string $type,
        float $weight
    ) {
        $this->type = $type;
        $this->weight = $weight;
    }
}