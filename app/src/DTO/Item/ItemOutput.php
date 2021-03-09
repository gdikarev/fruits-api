<?php

namespace App\DTO\Item;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Ulid;

class ItemOutput
{
    /**
     * @var string
     * @Groups({"item:read"})
     */
    public string $id;

    /**
     * @var string
     * @Groups({"item:read"})
     */
    public string $type;

    /**
     * @var float
     * @Groups({"item:read"})
     */
    public float $weight;

    public function __construct(
        Ulid $id,
        string $type,
        float $weight
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->weight = $weight;
    }
}