<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries\SearchQuery\Configuration;

use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\AttributeMapperInterface;
use Magento\Framework\Search\RequestInterface;

use function current;
use function is_array;
use function strtolower;

class SortBuilder implements SortBuilderInterface
{
    private AttributeMapperInterface $attributeMapper;

    public function __construct(AttributeMapperInterface $attributeMapper)
    {
        $this->attributeMapper = $attributeMapper;
    }

    /**
     * @inheritDoc
     */
    public function build(RequestInterface $request): array
    {
        $sort = [];

        foreach ($request->getSort() as $sortField) {
            $field = $this->getSortField($sortField['field'], $request);

            if (!$field) {
                continue;
            }

            $sort[] = [$field => $this->getSortDirection($sortField)];
        }

        return $sort;
    }

    private function getSortField(string $field, RequestInterface $request): ?string
    {
        return 'position' !== $field ? $this->getField($field) : $this->getPositionField($request);
    }

    /**
     * @param array{direction: string, field: string} $sort
     */
    private function getSortDirection(array $sort): string
    {
        $direction = strtolower($sort['direction']);
        $field = strtolower($sort['field']);

        return 'relevance' !== $field ? $direction : 'desc';
    }

    private function getPositionField(RequestInterface $request): ?string
    {
        $categoryFilter = $request->getQuery()->getMust()['category'] ?? null;

        if (!$categoryFilter) {
            return null;
        }

        $value = $categoryFilter->getReference()->getValue();
        $value = !is_array($value) ? $value : (current($value) ?: 0);

        return 'position.category_' . $value;
    }

    private function getField(string $field): string
    {
        return $this->attributeMapper->getField($field);
    }
}
