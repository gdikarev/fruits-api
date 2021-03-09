<?php

namespace App\EventListener\PostValidateTransformationListener;

use App\DTO\Basket\Basket as BasketDTO;
use App\Entity\Basket;
use App\EventListener\PostValidateTransformListener;
use App\Service\AfterReadStorage;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class BasketPut extends PostValidateTransformListener
{
    private AfterReadStorage $afterReadStorage;

    public function __construct(
        AfterReadStorage $afterReadStorage
    ) {
        $this->afterReadStorage = $afterReadStorage;
    }

    /**
     * @param BasketDTO $payload
     * @param string    $method
     *
     * @return bool
     */
    public function support($payload, string $method): bool
    {
        return $payload instanceof BasketDTO && $method === Request::METHOD_PUT;
    }

    /**
     * @param BasketDTO $payload
     *
     * @return Basket
     * @throws Exception
     */
    public function transform($payload)
    {
        /** @var Basket $entity */
        $entity = $this->afterReadStorage->getObject();

        $entity->update(
            $payload->name
        );

        return $entity;
    }
}