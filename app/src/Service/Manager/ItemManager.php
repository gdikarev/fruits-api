<?php

namespace App\Service\Manager;

use App\Repository\ItemRepository;
use App\Service\ApiPlatformExtensionApplier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class ItemManager
{
    private ApiPlatformExtensionApplier $apiPlatformExtensionApplier;

    private ItemRepository $itemRepository;

    private ?Request $request;

    public function __construct(
        ApiPlatformExtensionApplier $apiPlatformExtensionApplier,
        ItemRepository $itemRepository,
        RequestStack $requestStack
    ) {
        $this->apiPlatformExtensionApplier = $apiPlatformExtensionApplier;
        $this->itemRepository = $itemRepository;
        $this->request = $requestStack->getCurrentRequest();
    }

    public function getItemForDataProvider(
        string $resourceClass,
        $id,
        string $operationName = null,
        array $context = []
    ) {
        $qb = $this->itemRepository->findByUlidQuery(
            $this->request !== null ? $this->request->get('id') : null
        );

        return $this->apiPlatformExtensionApplier->applyToItem(
            $qb,
            ['item.ulid' => $id],
            $resourceClass,
            $operationName,
            $context
        );
    }

    public function getItemsForDataProvider(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): iterable {
        $qb = $this->itemRepository->findAllQuery();

        return $this->apiPlatformExtensionApplier->applyToCollection($qb, $resourceClass, $operationName, $context);
    }
}
