<?php

namespace App\Fixtures;

use App\Entity\Basket;
use App\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ItemFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /** @var Basket $basket */
        $basket = $this->getReference(BasketFixture::BASKET_REFERENCE);

        $item = new Item(
            Item::WATERMELON_TYPE,
            1,
            $basket
        );

        $manager->persist($item);

        $manager->flush();
    }
}