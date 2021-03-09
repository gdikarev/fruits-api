<?php

namespace App\EventListener;

use App\Event\AfterValidateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;

abstract class PostValidateTransformListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            AfterValidateEvent::class => 'transformation',
        ];
    }

    public function transformation(AfterValidateEvent $event): void
    {
        $payload = $event->getControllerResult();
        if ($this->support($payload, $event->getRequest()->getMethod()) === true) {
            $event->setControllerResult($this->transform($payload));
        }
    }

    /**
     * @param object $payload
     * @param string $method
     *
     * @return bool
     */
    abstract public function support($payload, string $method): bool;

    /**
     * @param object $payload
     *
     * @return mixed
     */
    abstract public function transform($payload);
}
