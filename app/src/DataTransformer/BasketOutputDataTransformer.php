<?php
declare(strict_types = 1);

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\DTO\Basket\BasketOutput;
use App\DTO\Item\ItemOutput;
use App\Entity\Basket;
use App\Entity\Item;
use Doctrine\Common\Collections\Collection;

class BasketOutputDataTransformer implements DataTransformerInterface
{
    private ItemOutputDataTransformer $itemOutputDataTransformer;

    public function __construct(ItemOutputDataTransformer $itemOutputDataTransformer)
    {
        $this->itemOutputDataTransformer = $itemOutputDataTransformer;
    }

    /**
     * @param Basket $object
     * @param string $to
     * @param array  $context
     *
     * @return object|void
     */
    public function transform($object, string $to, array $context = [])
    {
        return new BasketOutput(
            $object->getUlid(),
            $object->getName(),
            $object->getMaxCapacity(),
            $this->itemCollectionOutput($object->getItems())
        );
    }

    private function itemCollectionOutput(Collection $items): array
    {
        $itemCollection = [];

        /** @var Item $item */
        foreach ($items ?? [] as $item) {
            $itemCollection[] = $this->itemOutputDataTransformer->transform($item, ItemOutput::class);
        }

        return $itemCollection;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return BasketOutput::class === $to && $data instanceof Basket;
    }
}