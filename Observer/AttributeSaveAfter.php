<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Observer;

use LupaSearch\LupaSearchPlugin\Model\Attribute\Product\AttributeAfterSaveInterface;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AttributeSaveAfter implements ObserverInterface
{
    /**
     * @var AttributeAfterSaveInterface
     */
    private $attributeAfterSave;

    public function __construct(AttributeAfterSaveInterface $attributeAfterSave)
    {
        $this->attributeAfterSave = $attributeAfterSave;
    }

    public function execute(Observer $observer): void
    {
        $attribute = $observer->getEvent()->getData('data_object');

        if (!$attribute instanceof Attribute) {
            return;
        }

        $this->attributeAfterSave->process($attribute);
    }
}
