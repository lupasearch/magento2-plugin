<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Setup;

use Exception;
use Magento\AdvancedSearch\Model\Client\ClientResolver;
use Magento\Search\Model\SearchEngine\ValidatorInterface;

class Validator implements ValidatorInterface
{
    private ClientResolver $clientResolver;

    public function __construct(ClientResolver $clientResolver)
    {
        $this->clientResolver = $clientResolver;
    }

    /**
     * @inheritDoc
     */
    public function validate(): array
    {
        $errors = [];

        try {
            $client = $this->clientResolver->create();

            if (!$client->testConnection()) {
                $errors[] = 'Could not validate a connection to LupaSearch.'
                    . ' Verify that the LupaSearch API Key / JWT Token / Email&Password and Index ID'
                    . ' are configured correctly.';
            }
        } catch (Exception $e) {
            $errors[] = 'Could not validate a connection to LupaSearch. ' . $e->getMessage();
        }

        return $errors;
    }
}
