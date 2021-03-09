<?php
declare(strict_types = 1);

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\DTO\Basket\Basket as BasketDTO;
use App\Entity\Basket;
use App\Repository\BasketRepository;
use App\Service\AfterReadStorage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class BasketMutatorDataProvider
    implements ItemDataProviderInterface, RestrictedDataProviderInterface
{

    private AfterReadStorage $afterReadStorage;

    private BasketRepository $basketRepository;

    public function __construct(
        BasketRepository $basketRepository,
        AfterReadStorage $afterReadStorage
    ) {
        $this->afterReadStorage = $afterReadStorage;
        $this->basketRepository = $basketRepository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return BasketDTO::class === $resourceClass && $operationName === strtolower(Request::METHOD_PUT);
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?object
    {
        /** @var Basket|null $basket */
        $basket = $this->basketRepository->findOneByUlid($id);
        if ($basket === null) {
            return null;
        }

        $this->afterReadStorage->setObject($basket);

        return $basket;
    }
}
