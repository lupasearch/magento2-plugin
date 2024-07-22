<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Attribute\Product;

use LupaSearch\LupaSearchPlugin\Model\Config\Index\ProductConfigInterface;
use LupaSearch\LupaSearchPlugin\Model\Indexer\Product\Processor;
use LupaSearch\LupaSearchPlugin\Model\Provider\ProductsProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\QueriesGeneratorInterface;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;

abstract class AttributeAfterSaveAbstract implements AttributeAfterSaveInterface
{
    /**
     * @var QueriesGeneratorInterface
     */
    private $queriesGenerator;

    /**
     * @var Processor
     */
    private $processor;

    /**
     * @var ProductsProviderInterface
     */
    private $productsProvider;

    /**
     * @var ProductConfigInterface
     */
    private $productConfig;

    abstract public function process(Attribute $attribute): void;

    public function __construct(
        QueriesGeneratorInterface $queriesGenerator,
        Processor $processor,
        ProductsProviderInterface $productsProvider,
        ProductConfigInterface $productConfig
    ) {
        $this->queriesGenerator = $queriesGenerator;
        $this->processor = $processor;
        $this->productsProvider = $productsProvider;
        $this->productConfig = $productConfig;
    }

    protected function reindex(Attribute $attribute): void
    {
        $ids = $this->productsProvider->getAllIdsByAttribute($attribute);

        if (count($ids) > $this->productConfig->getAttributeMaxProductSize()) {
            $this->processor->markIndexerAsInvalid();

            return;
        }

        $this->processor->reindexList($ids, true);
    }

    protected function generateQueries(): void
    {
        $this->queriesGenerator->generateAll();
    }
}
