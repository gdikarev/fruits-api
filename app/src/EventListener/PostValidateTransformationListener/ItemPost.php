<?php

namespace App\EventListener\PostValidateTransformationListener;

use App\DTO\Item\Item as ItemDTO;
use App\Entity\Basket;
use App\Entity\Item;
use App\EventListener\PostValidateTransformListener;
use App\Repository\BasketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class ItemPost extends PostValidateTransformListener
{
    private EntityManagerInterface $entityManager;

    private BasketRepository $basketRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        BasketRepository $basketRepository
    ) {
        $this->entityManager = $entityManager;
        $this->basketRepository = $basketRepository;
    }

    /**
     * @param ItemDTO $payload
     * @param string  $method
     *
     * @return bool
     */
    public function support($payload, string $method): bool
    {
        return $payload instanceof ItemDTO && $method === Request::METHOD_POST;
    }

    /**
     * @param ItemDTO $payload
     *
     * @return Item
     * @throws Exception
     */
    public function transform($payload)
    {
        /** @var Basket $basket */
        $basket = $this->basketRepository->findOneByUlid($payload->basketId);

        return new Item(
            $payload->type,
            $payload->weight,
            $basket
        );
    }
}