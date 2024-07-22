<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Validator;

use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;

use function array_filter;
use function in_array;

class SortableAttributeValidator implements AttributeValidatorInterface
{
    /**
     * @var string[]
     */
    private array $forbidFrontendInputTypes = [
        'multiselect',
        'select',
        'gallery',
        'textarea',
    ];

    /**
     * @var string[]
     */
    private array $skipSortableAttributes;

    /**
     * @param string[] $skipSortableAttributes
     */
    public function __construct(array $skipSortableAttributes = [])
    {
        $this->skipSortableAttributes = array_filter($skipSortableAttributes);
    }

    public function validate(AbstractAttribute $attribute): bool
    {
        if (!$attribute->getData('used_for_sort_by')) {
            return false;
        }

        if (in_array($attribute->getFrontendInput(), $this->forbidFrontendInputTypes, true)) {
            return false;
        }

        // phpcs:ignore SlevomatCodingStandard.ControlStructures.UselessIfConditionWithReturn.UselessIfCondition
        if (in_array($attribute->getAttributeCode(), $this->skipSortableAttributes, true)) {
            return false;
        }

        return true;
    }
}
