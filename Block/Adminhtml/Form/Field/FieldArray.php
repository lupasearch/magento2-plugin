<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Block\Adminhtml\Form\Field;

use LupaSearch\LupaSearchPlugin\Block\Adminhtml\Form\Field\Renderer\ColumnSelect;
use LupaSearch\LupaSearchPlugin\Block\Adminhtml\Form\Field\Renderer\ColumnSelectFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\Element\Html\Select;

class FieldArray extends AbstractFieldArray
{
    private ColumnSelectFactory $selectFactory;

    private ObjectManagerInterface $objectManager;

    // phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
    public function __construct(
        Context $context,
        ColumnSelectFactory $selectFactory,
        ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->selectFactory = $selectFactory;
        $this->objectManager = $objectManager;
    }

    // phpcs:disable PSR2.Methods.MethodDeclaration.Underscore
    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [];

        foreach ($this->getColumns() as $name => $column) {
            $renderer = $column['renderer'] ?? null;

            if (!$renderer instanceof Select) {
                continue;
            }

            $value = $row->getData($name);
            $key = 'option_' . $renderer->calcOptionHash($value);
            $options[$key] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }

    /**
     * @throws LocalizedException
     */
    protected function createRenderer(string $type): BlockInterface
    {
        return $this->getLayout()->createBlock(
            $type,
            '',
            ['data' => ['is_render_to_js_template' => true]]
        );
    }

    protected function createSelectRenderer(string $optionSourceType): ColumnSelect
    {
        $optionSource = $this->objectManager->create($optionSourceType);

        return $this->selectFactory->create(
            [
                'optionSource' => $optionSource,
                'data' => [
                    'is_render_to_js_template' => true
                ]
            ]
        );
    }
}
