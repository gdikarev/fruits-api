<?php
declare(strict_types = 1);

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Item;
use App\Service\Manager\ItemManager;

class ItemDataProvider implements
    ItemDataProviderInterface,
    ContextAwareCollectionDataProviderInterface,
    RestrictedDataProviderInterface
{
    private ItemManager $itemManager;

    public function __construct(ItemManager $itemManager)
    {
        $this->itemManager = $itemManager;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Item::class;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        return $this->itemManager->getItemForDataProvider(...func_get_args());
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        return $this->itemManager->getItemsForDataProvider(...func_get_args());
    }
}
