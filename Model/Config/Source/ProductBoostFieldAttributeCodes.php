<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Config\Source;

use Magento\Catalog\Model\Product\Attribute\Repository;
use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\SortOrderBuilderFactory;
use Magento\Framework\Data\OptionSourceInterface;

class ProductBoostFieldAttributeCodes implements OptionSourceInterface
{
    /**
     * @var string[]
     */
    protected $backendType = ['int', 'decimal'];

    /**
     * @var string[]
     */
    protected $frontendInput = ['text', 'price', 'weight'];

    /**
     * @var array<string, string>
     */
    private array $systemAttributes = [];

    private Repository $repository;

    private SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory;

    private SortOrderBuilderFactory $sortOrderBuilderFactory;

    /**
     * @var array<array{label: string, value: string}>
     */
    private ?array $options = null;

    /**
     * @param array<string, string> $systemAttributes
     */
    public function __construct(
        Repository $repository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        SortOrderBuilderFactory $sortOrderBuilderFactory,
        array $systemAttributes = []
    ) {
        $this->repository = $repository;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->sortOrderBuilderFactory = $sortOrderBuilderFactory;
        $this->systemAttributes = $systemAttributes;
    }

    /**
     * @inheritDoc
     */
    public function toOptionArray(): array
    {
        if (null !== $this->options) {
            return $this->options;
        }

        $this->options = [];
        $this->options[] = ['label' => __('--- Please Select ---'), 'value' => ''];

        foreach ($this->systemAttributes as $code => $label) {
            $this->options[] = [
                'value' => $code,
                'label' => $label . ' (LupaSearch)',
            ];
        }

        $searchCriteriaBuilder = $this->getSearchCriteriaBuilder();
        $list = $this->repository->getList($searchCriteriaBuilder->create());

        foreach ($list->getItems() as $attribute) {
            $this->options[] = [
                'label' => $attribute->getDefaultFrontendLabel(),
                'value' => $attribute->getAttributeCode(),
            ];
        }

        return $this->options;
    }

    protected function getSearchCriteriaBuilder(): SearchCriteriaBuilder
    {
        $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
        $sortOrderBuilder = $this->sortOrderBuilderFactory->create();
        $sortOrderBuilder->setAscendingDirection();
        $sortOrderBuilder->setField(AttributeInterface::FRONTEND_LABEL);
        $searchCriteriaBuilder->addSortOrder($sortOrderBuilder->create());
        $searchCriteriaBuilder->addFilter('backend_type', $this->backendType, 'in');
        $searchCriteriaBuilder->addFilter('frontend_input', $this->frontendInput, 'in');

        return $searchCriteriaBuilder;
    }
}
