<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Indexer\Product;

use LupaSearch\LupaSearchPlugin\Model\Indexer\Product;
use Magento\Framework\Indexer\AbstractProcessor;

class Processor extends AbstractProcessor
{
    public const INDEXER_ID = Product::INDEXER_ID;
}
