<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Modifiers;

use LupaSearch\LupaSearchPlugin\Model\Provider\DataModifierInterface;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Traversable;

/**
 * @codeCoverageIgnore
 */
class CategoryIds implements DataModifierInterface
{
    public function modify(Traversable $data): void
    {
        if (!$data instanceof Collection) {
            return;
        }

        $data->addCategoryIds();
    }
}
