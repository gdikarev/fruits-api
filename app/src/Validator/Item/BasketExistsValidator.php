<?php

namespace App\Validator\Item;

use App\Repository\BasketRepository;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Symfony\Contracts\Service\ServiceSubscriberTrait;

class BasketExistsValidator extends ConstraintValidator implements ServiceSubscriberInterface
{
    use ServiceSubscriberTrait;

    private function basketRepository(): BasketRepository
    {
        return $this->container->get(__METHOD__);
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint BasketExists */
        if (!$constraint instanceof BasketExists) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\BasketExists');
        }

        if (null === $value || '' === $value || !Ulid::isValid($value)) {
            return;
        }

        $basket = $this->basketRepository()->findOneByUlid($value);

        if (!$basket) {
            $this->context->buildViolation($constraint->message)
                          ->addViolation()
            ;
        }
    }
}
