<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Conditions;

use Magento\Catalog\Model\Product\Attribute\Source\Status as ProductStatus;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Data\CollectionModifierInterface;

class Status implements CollectionModifierInterface
{
    /**
     * @var ProductStatus
     */
    protected $productStatus;

    public function __construct(ProductStatus $productStatus)
    {
        $this->productStatus = $productStatus;
    }

    public function apply(AbstractDb $collection): void
    {
        $collection->addAttributeToFilter('status', ['in' => $this->productStatus->getVisibleStatusIds()]);
    }
}
