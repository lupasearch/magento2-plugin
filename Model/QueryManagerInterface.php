<?php

namespace LupaSearch\LupaSearchPlugin\Model;

use Magento\Framework\Exception\NotFoundException;

interface QueryManagerInterface
{
    /**
     * @throws NotFoundException
     * @throws \LupaSearch\Exceptions\ApiException
     */
    public function generate(string $type, int $storeId): string;
}
