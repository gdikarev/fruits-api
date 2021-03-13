<?php

namespace App\Tests\Func;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\DTO\Item\Item as ItemDto;
use App\Entity\Basket;
use App\Entity\Item;
use App\Repository\BasketRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ItemApiTest extends ApiTestCase
{
    public function testGetItemsCollection(): void
    {
        $client = static::createClient();

        $client->request(
            Request::METHOD_GET,
            '/api/items',
            ['headers' => ['ACCEPT' => 'application/json']]
        );

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        self::assertMatchesResourceCollectionJsonSchema(ItemDto::class);
    }

    public function testPostItem(): void
    {
        $client = static::createClient();
        /** @var BasketRepository $basketRepo */
        $basketRepo = static::$container->get(BasketRepository::class);
        /** @var Basket $basket */
        $basket = $basketRepo->findOneBy(
            [
                'name' => 'BasketFixture',
            ]
        );

        $ulid = (string)$basket->getUlid();
        $sumWeight = $basketRepo->sumWeight($ulid);

        $validWeight = $basket->getMaxCapacity() - $sumWeight - 0.1;

        $client->request(
            Request::METHOD_POST,
            "api/items",
            [
                'headers' => [
                    'ACCEPT' => 'application/json',
                ],
                'json' => [
                    'type' => Item::WATERMELON_TYPE,
                    'weight' => $validWeight,
                    'basketId' => $ulid,
                ],
            ],
        );

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

        self::assertJsonContains(
            [
                'type' => Item::WATERMELON_TYPE,
                'weight' => $validWeight,
            ]
        );

        $this->assertMatchesResourceItemJsonSchema(ItemDto::class);
    }

    public function testDeleteItem(): void
    {
        $client = static::createClient();
        $basketRepo = static::$container->get(BasketRepository::class);
        /** @var Basket $basket */
        $basket = $basketRepo->findOneBy(
            [
                'name' => 'BasketFixture',
            ]
        );

        $item = $basket->getItems()->first();
        $ulid = (string)$item->getUlid();

        $client->request(
            Request::METHOD_DELETE,
            "api/items/{$ulid}",
            [
                'headers' => [
                    'ACCEPT' => 'application/json',
                ],
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
        self::assertFalse(
            $basket->getItems()->first()
        );
    }
}