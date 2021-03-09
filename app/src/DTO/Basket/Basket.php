<?php

namespace App\DTO\Basket;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class Basket
{
    public string $id;

    /**
     * @var string
     * @Assert\NotNull(groups={"write", "update"})
     * @Assert\Type(type="string", groups={"write", "update"})
     * @Groups({"basket:write", "basket:update"})
     */
    public string $name;

    /**
     * @var float
     * @Assert\NotNull(groups={"write"})
     * @Assert\Type(type="float", groups={"write"})
     * @Assert\GreaterThan(value="0", groups={"write"})
     * @Groups({"basket:write"})
     */
    public float $maxCapacity;
}