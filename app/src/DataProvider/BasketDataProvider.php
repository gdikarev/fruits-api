<?php
declare(strict_types = 1);

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Basket;
use App\Service\Manager\BasketManager;
use Symfony\Component\HttpFoundation\Request;

class BasketDataProvider implements
    ItemDataProviderInterface,
    ContextAwareCollectionDataProviderInterface,
    RestrictedDataProviderInterface
{
    private BasketManager $basketManager;

    public function __construct(BasketManager $basketManager)
    {
        $this->basketManager = $basketManager;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Basket::class;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        return $this->basketManager->getBasketForDataProvider(...func_get_args());
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        return $this->basketManager->getBasketsForDataProvider(...func_get_args());
    }
}
