<?php

namespace App\Tests\Func;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\DTO\Basket\Basket as BasketDto;
use App\Entity\Basket;
use App\Repository\BasketRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BasketApiTest extends ApiTestCase
{
    public function testGetBasketCollection(): void
    {
        $client = static::createClient();

        $client->request(
            Request::METHOD_GET,
            '/api/baskets',
            ['headers' => ['ACCEPT' => 'application/json']]
        );

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        self::assertMatchesResourceCollectionJsonSchema(BasketDto::class);
    }

    public function testPostBasket(): void
    {
        $client = static::createClient();

        $client->request(
            Request::METHOD_POST,
            '/api/baskets',
            [
                'headers' => [
                    'ACCEPT' => 'application/json',
                ],
                'json' => [
                    'name' => 'Test',
                    'maxCapacity' => 12.2,
                ],
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');

        self::assertJsonContains(
            [
                'name' => 'Test',
                'maxCapacity' => 12.2,
                'items' => [],
            ]
        );

        $this->assertMatchesResourceItemJsonSchema(BasketDto::class);
    }

    public function testInvalidTypeMaxCapacityPostBasket(): void
    {
        $client = static::createClient();

        $client->request(
            Request::METHOD_POST,
            '/api/baskets',
            [
                'headers' => [
                    'ACCEPT' => 'application/json',
                ],
                'json' => [
                    'name' => 'Test',
                    'maxCapacity' => 'string-not-float',
                ],
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertResponseHeaderSame('content-type', 'application/problem+json; charset=utf-8');
        self::assertJsonContains(
            [
                'title' => 'An error occurred',
                'detail' => 'The type of the "maxCapacity" attribute must be "float", "string" given.',
            ]
        );
    }

    public function testPutBasket(): void
    {
        $client = static::createClient();
        $basketRepo = static::$container->get(BasketRepository::class);
        /** @var Basket $basket */
        $basket = $basketRepo->findOneBy(
            [
                'name' => 'BasketFixture',
            ]
        );

        $ulid = (string)$basket->getUlid();

        $client->request(
            Request::METHOD_PUT,
            "api/baskets/{$ulid}",
            [
                'headers' => [
                    'ACCEPT' => 'application/json',
                ],
                'json' => [
                    'name' => 'Update',
                ],
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
        self::assertJsonContains(
            [
                'name' => 'Update',
            ]
        );
    }

    public function testDeleteBasket(): void
    {
        $client = static::createClient();
        $basketRepo = static::$container->get(BasketRepository::class);
        /** @var Basket $basket */
        $basket = $basketRepo->findOneBy(
            [
                'name' => 'BasketFixture',
            ]
        );

        $ulid = (string)$basket->getUlid();

        $client->request(
            Request::METHOD_DELETE,
            "api/baskets/{$ulid}",
            [
                'headers' => [
                    'ACCEPT' => 'application/json',
                ],
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
        self::assertNull(
            $basketRepo->findOneBy(['name' => 'BasketFixture'])
        );
    }
}