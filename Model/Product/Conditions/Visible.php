<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Conditions;

use Magento\Catalog\Model\Product\Visibility;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Data\CollectionModifierInterface;

class Visible implements CollectionModifierInterface
{
    /**
     * @var Visibility
     */
    private $visibility;

    public function __construct(Visibility $visibility)
    {
        $this->visibility = $visibility;
    }

    public function apply(AbstractDb $collection): void
    {
        $collection->setVisibility($this->visibility->getVisibleInSiteIds());
    }
}
