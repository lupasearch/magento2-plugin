<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\SortBy;

use Magento\Framework\DataObject;

class OptionParameters extends DataObject implements OptionParametersInterface
{
    public function getLabel(): string
    {
        return (string)$this->getData('label');
    }

    public function getCode(): string
    {
        return (string)$this->getData('code');
    }

    public function getDirection(): string
    {
        return (string)$this->getData('direction');
    }

    public function setDirection(string $direction): void
    {
        $this->setData('direction', $direction);
    }
}
