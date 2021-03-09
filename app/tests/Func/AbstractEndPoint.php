<?php

namespace App\Tests\Func;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractEndPoint extends WebTestCase
{
    private array $serverInfo = ['ACCEPT' => 'application/json', 'CONTENT_TYPE' => 'application/json'];

    public function getResponseFromRequest(
        KernelBrowser $client,
        string $method,
        string $uri,
        array $params = [],
        string $payload = ''
    ): Response {
        $client->request(
            $method,
            $uri,
            $params,
            [],
            $this->serverInfo,
            $payload
        );

        return $client->getResponse();
    }
}