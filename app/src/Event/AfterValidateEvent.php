<?php

namespace App\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class AfterValidateEvent extends Event
{
    private ViewEvent $viewEvent;

    public function __construct(ViewEvent $event)
    {
        $this->viewEvent = $event;
    }

    public function getControllerResult()
    {
        return $this->viewEvent->getControllerResult();
    }

    public function setControllerResult($controllerResult): void
    {
        $this->viewEvent->setControllerResult($controllerResult);
    }

    public function getRequest(): Request
    {
        return $this->viewEvent->getRequest();
    }

    public function getResponse(): ?Response
    {
        return $this->viewEvent->getResponse();
    }

    public function setResponse(Response $response): void
    {
        $this->viewEvent->setResponse($response);
        $this->stopPropagation();
    }

    public function hasResponse(): bool
    {
        return $this->viewEvent->hasResponse();
    }
}
