<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Search\Adapter\LupaSearch\Queries\SearchQuery\Configuration;

use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\AttributeMapperInterface;
use LupaSearch\LupaSearchPlugin\Model\QueryBuilder\FacetTypeProviderInterface;
use LupaSearch\LupaSearchPluginCore\Factories\OrderedMapFactory;
use Magento\Framework\Search\Adapter\OptionsInterface;
use Magento\Framework\Search\Request\Aggregation\Range;
use Magento\Framework\Search\Request\BucketInterface;
use Magento\Framework\Search\Request\BucketInterface as RequestBucketInterface;
use Magento\Framework\Search\RequestInterface;

use function array_map;

class FacetsBuilder implements FacetsBuilderInterface
{
    private AttributeMapperInterface $attributeMapper;

    private OptionsInterface $config;

    public function __construct(AttributeMapperInterface $attributeMapper, OptionsInterface $config)
    {
        $this->attributeMapper = $attributeMapper;
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function build(RequestInterface $request): array
    {
        $facets = [];

        foreach ($request->getAggregation() as $bucket) {
            $facets[] = OrderedMapFactory::create($this->prepareData($bucket));
        }

        return $facets;
    }

    /**
     * @return array{
     *     type: string,
     *     key: string,
     *     label: string,
     *     ranges?: array<array{from: float, to: float}>|array{min: float, max: float, step: float}
     * }
     */
    private function prepareData(RequestBucketInterface $bucket): array
    {
        $data = [
            'type' => $this->getType($bucket),
            'key' => $this->attributeMapper->getField($bucket->getField()),
            'label' => $bucket->getName(),
        ];

        if (BucketInterface::TYPE_RANGE === $bucket->getType()) {
            $ranges = $bucket->getRanges();

            $data['ranges'] = $this->prepareRanges($ranges);
        }

        return $data;
    }

    /**
     * @param \Magento\Framework\Search\Request\Aggregation\Range[] $ranges
     * @return array<array{from: float, to: float}>|array{min: float, max: float, step: float}
     */
    private function prepareRanges(array $ranges): array
    {
        if (1 === count($ranges)) {
            $range = reset($ranges);
            $gap = ceil($range->getTo() - $range->getFrom());
            $rangeStep = $this->config->get()['range_step'] ?? 100;
            $max = $this->config->get()['max_intervals_number'] ?? 10;
            $intervalCount = ceil($gap / $rangeStep);
            $step = $intervalCount > $max || $intervalCount < 2 ? ceil($gap / $max) : $rangeStep;

            return [
                'min' => $range->getFrom(),
                'max' => $range->getTo(),
                'step' => $step,
            ];
        }

        return array_map(static function (Range $range): array {
            return [
                'from' => $range->getFrom(),
                'to' => $range->getTo(),
            ];
        }, $ranges);
    }

    private function getType(BucketInterface $bucket): string
    {
        switch ($bucket->getType()) {
            case BucketInterface::TYPE_RANGE:
                return FacetTypeProviderInterface::RANGE;

            case BucketInterface::TYPE_DYNAMIC:
                return FacetTypeProviderInterface::STATS;

            case BucketInterface::TYPE_TERM:
            default:
                return FacetTypeProviderInterface::TERMS;
        }
    }
}
