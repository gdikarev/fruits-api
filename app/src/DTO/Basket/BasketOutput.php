<?php

namespace App\DTO\Basket;

use App\DTO\Item\ItemOutput;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Ulid;

class BasketOutput
{
    /**
     * @var Ulid
     * @Groups({"basket:read"})
     */
    public Ulid $id;

    /**
     * @var string
     * @Groups({"basket:read"})
     */
    public string $name;

    /**
     * @var float
     * @Groups({"basket:read"})
     */
    public float $maxCapacity;

    /**
     * @var ItemOutput[]
     * @Groups({"item:read"})
     */
    public array $items;

    public function __construct(
        Ulid $id,
        string $name,
        float $maxCapacity,
        array $items = []
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->maxCapacity = $maxCapacity;
        $this->items = $items;
    }
}