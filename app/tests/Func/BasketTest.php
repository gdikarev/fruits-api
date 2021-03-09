<?php

namespace App\Tests\Func;

use App\Entity\Basket;
use App\Repository\BasketRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

class BasketTest extends AbstractEndPoint
{
    public function testPostBasket(): void
    {
        $client = static::createClient();

        $response = $this->getResponseFromRequest(
            $client,
            Request::METHOD_POST,
            "api/baskets",
            [],
            $this->basketPayload('Basket', 12.2)
        );

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertJson($response->getContent());
    }

    public function testNullMaxCapacityValuePostBasket(): void
    {
        $client = static::createClient();

        $response = $this->getResponseFromRequest(
            $client,
            Request::METHOD_POST,
            "api/baskets",
            [],
            $this->basketPayload('Basket', null)
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertJson($response->getContent());
    }

    public function testStringMaxCapacityValuePostBasket(): void
    {
        $client = static::createClient();

        $response = $this->getResponseFromRequest(
            $client,
            Request::METHOD_POST,
            "api/baskets",
            [],
            $this->basketPayload('Basket', "10")
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertJson($response->getContent());
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

        $response = $this->getResponseFromRequest(
            $client,
            Request::METHOD_PUT,
            "api/baskets/{$ulid}",
            [],
            $this->basketPayload('PutName', null)
        );

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($response->getContent());
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

        $response = $this->getResponseFromRequest(
            $client,
            Request::METHOD_DELETE,
            "api/baskets/{$ulid}"
        );

        self::assertTrue($response->isSuccessful());
        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testGetNotExistentBasket(): void
    {
        $client = static::createClient();

        $randomUlid = new Ulid();
        $response = $this->getResponseFromRequest($client, Request::METHOD_GET, "api/baskets/{$randomUlid}");
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        self::assertJson($response->getContent());
    }

    public function testGetBaskets(): void
    {
        $client = static::createClient();

        $response = $this->getResponseFromRequest($client, Request::METHOD_GET, "api/baskets");

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($response->getContent());
    }

    /**
     * @param mixed $name
     * @param mixed $maxCapacity
     *
     * @return string
     */
    private function basketPayload($name, $maxCapacity): string
    {
        return json_encode(
            [
                'name' => $name,
                'maxCapacity' => $maxCapacity,
            ]
        );
    }
}