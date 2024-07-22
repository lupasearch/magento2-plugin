<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Adapter;

use GuzzleHttp\ClientInterface;
use LupaSearch\Factories\HttpClientFactory as BaseHttpClientFactory;
use LupaSearch\Factories\HttpClientFactoryInterface;

class HttpClientFactory implements HttpClientFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(array $config = []): ClientInterface
    {
        $config = [];

        return (new BaseHttpClientFactory())->create($config);
    }
}
