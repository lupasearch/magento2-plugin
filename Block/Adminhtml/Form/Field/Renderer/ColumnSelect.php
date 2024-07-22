<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Block\Adminhtml\Form\Field\Renderer;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;

class ColumnSelect extends Select
{
    private OptionSourceInterface $optionSource;

    // phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
    public function __construct(Context $context, OptionSourceInterface $optionSource, array $data = [])
    {
        parent::__construct($context, $data);

        $this->optionSource = $optionSource;
    }

    /**
     * @inheritDoc
     */
    public function getOptions(): array
    {
        return $this->optionSource->toOptionArray();
    }

    public function setInputName(string $value): Select
    {
        return $this->setData('name', $value);
    }
}
