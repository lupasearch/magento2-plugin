<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Category;

use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\Data\CollectionModifier;

class CollectionBuilder
{
    private CollectionFactory $collectionFactory;

    private CollectionModifier $collectionModifier;

    public function __construct(CollectionFactory $collectionFactory, CollectionModifier $collectionModifier)
    {
        $this->collectionFactory = $collectionFactory;
        $this->collectionModifier = $collectionModifier;
    }

    public function build(): Collection
    {
        $collection = $this->collectionFactory->create();

        $collection
            ->addAttributeToFilter('is_active', true)
            ->addAttributeToFilter('level', ['gt' => 1])
            ->addAttributeToSelect('*')
            ->addUrlRewriteToResult();

        $this->collectionModifier->apply($collection);

        return $collection;
    }
}
