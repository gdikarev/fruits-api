<?php

namespace App\EventListener\PostValidateTransformationListener;

use App\DTO\Basket\Basket as BasketDTO;
use App\Entity\Basket;
use App\EventListener\PostValidateTransformListener;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class BasketPost extends PostValidateTransformListener
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @param BasketDTO $payload
     * @param string    $method
     *
     * @return bool
     */
    public function support($payload, string $method): bool
    {
        return $payload instanceof BasketDTO && $method === Request::METHOD_POST;
    }

    /**
     * @param BasketDTO $payload
     *
     * @return Basket
     * @throws Exception
     */
    public function transform($payload)
    {
        return new Basket(
            $payload->name,
            $payload->maxCapacity
        );
    }
}