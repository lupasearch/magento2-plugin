<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Client;

use Magento\AdvancedSearch\Model\Client\ClientOptionsInterface;

class ClientOptions implements ClientOptionsInterface
{
    /**
     * @inheritDoc
     */
    public function prepareClientOptions($options = []): array
    {
        return $options;
    }
}
