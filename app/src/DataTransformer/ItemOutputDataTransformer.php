<?php
declare(strict_types = 1);

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\DTO\Item\ItemOutput;
use App\Entity\Item;

class ItemOutputDataTransformer implements DataTransformerInterface
{
    /**
     * @param Item   $object
     * @param string $to
     * @param array  $context
     *
     * @return object|void
     */
    public function transform($object, string $to, array $context = [])
    {
        return new ItemOutput(
            $object->getUlid(),
            $object->getType(),
            $object->getWeight()
        );
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return ItemOutput::class === $to && $data instanceof Item;
    }
}