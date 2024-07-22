<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Indexer\Price;

use LupaSearch\LupaSearchPlugin\Model\Indexer\Price;
use Magento\Framework\Indexer\AbstractProcessor;

class Processor extends AbstractProcessor
{
    public const INDEXER_ID = Price::INDEXER_ID;
}
