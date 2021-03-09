<?php

namespace App\Validator\Item;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class BasketExists extends Constraint
{
    public string $message = 'Basket does not exist';
}
