<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 */
class Item
{
    public const WATERMELON_TYPE = 'watermelon';
    public const ORANGE_TYPE = 'orange';
    public const APPLE_TYPE = 'apple';

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
    protected string $type;

    /**
     * @ORM\Column(type="float", scale=2)
     */
    protected float $weight;

    /**
     * @ORM\ManyToOne(targetEntity="Basket", inversedBy="items")
     */
    protected Basket $basket;

    public function __construct(
        string $type,
        float $weight,
        Basket $basket
    ) {
        $this->ulid = new Ulid();
        $this->type = $type;
        $this->weight = $weight;
        $this->basket = $basket;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUlid(): Ulid
    {
        return $this->ulid;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getBasket(): Basket
    {
        return $this->basket;
    }
}