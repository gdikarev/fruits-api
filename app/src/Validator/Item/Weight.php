<?php

namespace App\Validator\Item;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Weight extends Constraint
{
    public string $message = 'Basket weight could not be more then "{{ maxCapacity }}", current weight is "{{ currentWeight }}"';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
