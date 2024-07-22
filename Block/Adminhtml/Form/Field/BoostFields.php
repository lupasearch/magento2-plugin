<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Block\Adminhtml\Form\Field;

use LupaSearch\LupaSearchPlugin\Model\Config\Source\ProductBoostFieldAttributeCodes;
use Magento\Framework\View\Element\BlockInterface;

class BoostFields extends FieldArray
{
    /**
     * @phpcs:disable PSR2.Methods.MethodDeclaration.Underscore
     */
    protected function _prepareToRender(): void
    {
        $this->addColumn(
            'attribute_code',
            [
                'label' => __('Attribute'),
                'renderer' => $this->getAttributeRenderer(),
            ],
        );
        $this->addColumn(
            'coefficient',
            [
                'label' => __('Coefficient'),
            ],
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }

    private function getAttributeRenderer(): BlockInterface
    {
        return $this->createSelectRenderer(ProductBoostFieldAttributeCodes::class);
    }
}
