<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Document;

use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\DocumentQueryResponseInterface;
use Magento\Framework\Api\Search\DocumentInterface;

interface BuilderInterface
{
    /**
     * @return DocumentInterface[]
     */
    public function build(DocumentQueryResponseInterface $response): array;
}
