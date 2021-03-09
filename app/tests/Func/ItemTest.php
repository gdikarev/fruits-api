<?php

namespace App\Tests\Func;

use App\Entity\Basket;
use App\Entity\Item;
use App\Repository\BasketRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Ulid;

class ItemTest extends AbstractEndPoint
{
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

        $response = $this->getResponseFromRequest(
            $client,
            Request::METHOD_POST,
            "api/items",
            [],
            $this->itemPayload(Item::WATERMELON_TYPE, $validWeight, $ulid)
        );

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertJson($response->getContent());
    }

    public function testInvalidTypePostItem(): void
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

        $response = $this->getResponseFromRequest(
            $client,
            Request::METHOD_POST,
            "api/items",
            [],
            $this->itemPayload('not-valid-type', $validWeight, $ulid)
        );

        self::assertFalse($response->isSuccessful());
        self::assertJson($response->getContent());
    }

    public function testInvalidWeightPostItem(): void
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

        $invalidWeight = $basket->getMaxCapacity() + 1;

        $response = $this->getResponseFromRequest(
            $client,
            Request::METHOD_POST,
            "api/items",
            [],
            $this->itemPayload(Item::WATERMELON_TYPE, $invalidWeight, $ulid)
        );

        self::assertFalse($response->isSuccessful());
        self::assertJson($response->getContent());
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

        if ($item) {
            $ulid = (string)$item->getUlid();

            $response = $this->getResponseFromRequest(
                $client,
                Request::METHOD_DELETE,
                "api/items/{$ulid}"
            );

            self::assertTrue($response->isSuccessful());
            self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
        }
    }

    public function testGetNotExistentItem(): void
    {
        $client = static::createClient();

        $randomUlid = new Ulid();
        $response = $this->getResponseFromRequest($client, Request::METHOD_GET, "api/items/{$randomUlid}");
        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        self::assertJson($response->getContent());
    }

    public function testGetItems(): void
    {
        $client = static::createClient();

        $response = $this->getResponseFromRequest($client, Request::METHOD_GET, "api/items");

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertJson($response->getContent());
    }

    /**
     * @param mixed $type
     * @param mixed $weight
     * @param mixed $basketId
     *
     * @return string
     */
    private function itemPayload($type, $weight, $basketId): string
    {
        return json_encode(
            [
                'type' => $type,
                'weight' => $weight,
                'basketId' => $basketId,
            ]
        );
    }
}