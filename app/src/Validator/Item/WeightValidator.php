<?php

namespace App\Validator\Item;

use App\Entity\Basket;
use App\Repository\BasketRepository;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

class WeightValidator extends ConstraintValidator implements ServiceSubscriberInterface
{
    use ServiceSubscriberTrait;

    private function basketRepository(): BasketRepository
    {
        return $this->container->get(__METHOD__);
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint Weight */
        if (!$constraint instanceof Weight) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\Weight');
        }

        if (null === $value || '' === $value || !Ulid::isValid($value->basketId)) {
            return;
        }

        /** @var Basket $basket */
        $basket = $this->basketRepository()->findOneByUlid($value->basketId);

        if (!$basket) {
            return;
        }

        $currentBasketWeight = $this->basketRepository()->sumWeight($value->basketId);

        if ($value->weight + $currentBasketWeight > $basket->getMaxCapacity()) {
            $this->context->buildViolation($constraint->message)
                          ->setParameter('{{ maxCapacity }}', (string)$basket->getMaxCapacity())
                          ->setParameter('{{ currentWeight }}', (string)$currentBasketWeight)
                          ->addViolation()
            ;
        }
    }
}
