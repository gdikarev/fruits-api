<?php

namespace App\DTO\Item;

use App\Entity\Item as ItemEntity;
use App\Validator\Item\BasketExists;
use App\Validator\Item\Weight;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Weight()
 */
class Item
{
    public string $id;

    /**
     * @var string
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Assert\Choice({ItemEntity::WATERMELON_TYPE, ItemEntity::APPLE_TYPE, ItemEntity::ORANGE_TYPE})
     * @Groups({"item:write"})
     */
    public string $type;

    /**
     * @var float
     * @Assert\NotNull()
     * @Assert\Type("float")
     * @Assert\GreaterThan(0)
     * @Groups({"item:write"})
     */
    public float $weight;

    /**
     * @var string
     * @Assert\NotNull()
     * @Assert\Ulid
     * @BasketExists()
     * @Groups({"item:write"})
     */
    public string $basketId;
}