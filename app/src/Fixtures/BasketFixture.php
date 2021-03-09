<?php

namespace App\Fixtures;

use App\Entity\Basket;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BasketFixture extends Fixture
{
    public const BASKET_REFERENCE = 'admin-user';

    public function load(ObjectManager $manager)
    {
        $basket = new Basket(
            'BasketFixture',
            10
        );

        $manager->persist($basket);

        $manager->flush();

        $this->addReference(self::BASKET_REFERENCE, $basket);
    }
}