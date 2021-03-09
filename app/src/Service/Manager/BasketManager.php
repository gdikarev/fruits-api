<?php

namespace App\Service\Manager;

use App\Repository\BasketRepository;
use App\Service\ApiPlatformExtensionApplier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class BasketManager
{
    private ApiPlatformExtensionApplier $apiPlatformExtensionApplier;

    private BasketRepository $basketRepository;

    private ?Request $request;

    public function __construct(
        ApiPlatformExtensionApplier $apiPlatformExtensionApplier,
        BasketRepository $basketRepository,
        RequestStack $requestStack
    ) {
        $this->apiPlatformExtensionApplier = $apiPlatformExtensionApplier;
        $this->basketRepository = $basketRepository;
        $this->request = $requestStack->getCurrentRequest();
    }

    public function getBasketForDataProvider(
        string $resourceClass,
        $id,
        string $operationName = null,
        array $context = []
    ) {
        $qb = $this->basketRepository->findByUlidQuery(
            $this->request !== null ? $this->request->get('id') : null
        );

        return $this->apiPlatformExtensionApplier->applyToItem(
            $qb,
            ['basket.ulid' => $id],
            $resourceClass,
            $operationName,
            $context
        );
    }

    public function getBasketsForDataProvider(
        string $resourceClass,
        string $operationName = null,
        array $context = []
    ): iterable {
        $qb = $this->basketRepository->findAllQuery();

        return $this->apiPlatformExtensionApplier->applyToCollection($qb, $resourceClass, $operationName, $context);
    }
}
