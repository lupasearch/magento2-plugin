<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Indexer\Category;

use LupaSearch\LupaSearchPlugin\Model\Indexer\Category;
use Magento\Framework\Indexer\AbstractProcessor;

class Processor extends AbstractProcessor
{
    public const INDEXER_ID = Category::INDEXER_ID;
}
