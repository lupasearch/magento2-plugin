<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Attribute\Product;

use Magento\Catalog\Model\ResourceModel\Eav\Attribute;

class AttributeAfterSave implements AttributeAfterSaveInterface
{
    /**
     * @var AttributeAfterSaveInterface[]
     */
    private $pool;

    /**
     * @param AttributeAfterSaveInterface[] $pool
     */
    public function __construct(array $pool = [])
    {
        $this->pool = $pool;
    }

    public function process(Attribute $attribute): void
    {
        foreach ($this->pool as $afterSave) {
            if (!$afterSave instanceof AttributeAfterSaveInterface) {
                continue;
            }

            $afterSave->process($attribute);
        }
    }
}
