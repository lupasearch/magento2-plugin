<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Attributes;

use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\AttributeTypeProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\FilterableAttributesProviderInterface;
use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\SystemAttributeMapInterface;
use Exception;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Framework\DB\Select;
use Psr\Log\LoggerInterface;

use function array_merge;
use function implode;
use function in_array;
use function sprintf;

use const PHP_EOL;

class FilterableAttributesProvider implements FilterableAttributesProviderInterface
{
    private CollectionFactory $attributeCollectionFactory;

    private AttributeFactory $attributeFactory;

    /**
     * @var Attribute[]|null
     */
    private ?array $attributeList = null;

    /**
     * @var string[]|null
     */
    private ?array $attributeCodes = null;

    private AttributeTypeProviderInterface $attributeTypeProvider;

    private SystemAttributeMapInterface $systemAttributeMap;

    private LoggerInterface $logger;

    public function __construct(
        CollectionFactory $productAttributeCollectionFactory,
        AttributeFactory $attributeFactory,
        AttributeTypeProviderInterface $attributeTypeProvider,
        SystemAttributeMapInterface $systemAttributeMap,
        LoggerInterface $logger
    ) {
        $this->attributeCollectionFactory = $productAttributeCollectionFactory;
        $this->attributeFactory = $attributeFactory;
        $this->attributeTypeProvider = $attributeTypeProvider;
        $this->systemAttributeMap = $systemAttributeMap;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function getList(): array
    {
        if (null !== $this->attributeList) {
            return $this->attributeList;
        }

        $this->attributeList = $this->getSystemAttributes();

        $collection = $this->getFilterableAttributesCollection();

        foreach ($collection as $attribute) {
            $this->attributeList[$this->getAttributeId($attribute)] = $attribute;
        }

        return $this->attributeList;
    }

    public function getAttributeId(Attribute $attribute): string
    {
        if ($this->isSystemAttribute($attribute)) {
            return $attribute->getAttributeCode();
        }

        /** @psalm-suppress RedundantCastGivenDocblockType */
        $prefix = $this->getAttributePrefix((string)$attribute->getAttributeCode());

        return $prefix . (int)$attribute->getAttributeId();
    }

    /**
     * @inheritDoc
     */
    public function getAttributeCodes(): array
    {
        if (null !== $this->attributeCodes) {
            return $this->attributeCodes;
        }

        foreach ($this->getFilterableAttributeCodes() as $id => $code) {
            $this->attributeCodes[$code] = $this->getAttributePrefix($code) . $id;
        }

        $this->attributeCodes = array_merge($this->attributeCodes ?? [], $this->systemAttributeMap->getList());

        return $this->attributeCodes;
    }

    /**
     * @return string[]
     * @phpcs:disable SlevomatCodingStandard.Functions.FunctionLength.FunctionLength
     */
    protected function getSystemAttributes(): array
    {
        $systemAttributes = [];
        $systemAttributes['category_id'] = $this->createAttribute(
            [
                'store_label' => __('Category'),
                'attribute_code' => 'category_id',
                'backend_type' => 'int',
                'frontend_input' => 'text',
            ],
        );
        $systemAttributes['category_ids'] = $this->createAttribute(
            [
                'store_label' => __('Categories'),
                'attribute_code' => 'category_ids',
                'backend_type' => 'varchar',
                'frontend_input' => 'select',
            ],
        );
        $systemAttributes['categories'] = $this->createAttribute(
            [
                'store_label' => __('Categories'),
                'attribute_code' => 'categories',
                'backend_type' => 'varchar',
                'frontend_input' => 'select',
            ],
        );
        $systemAttributes['category'] = $this->createAttribute(
            [
                'store_label' => __('Category'),
                'attribute_code' => 'category',
                'backend_type' => 'int',
                'frontend_input' => 'select',
            ],
        );
        $systemAttributes['sources'] = $this->createAttribute(
            [
                'store_label' => __('Source'),
                'attribute_code' => 'sources',
                'backend_type' => 'varchar',
                'frontend_input' => 'select',
            ],
        );

        return $systemAttributes;
    }

    protected function getFilterableAttributesCollection(): Collection
    {
        $productAttributes = $this->attributeCollectionFactory->create();
        $select = $productAttributes->getSelect();
        $connection = $productAttributes->getConnection();
        $conditions = [
            $connection->quoteInto('additional_table.is_filterable > ?', 0),
            $connection->quoteInto('additional_table.is_filterable_in_search = ?', 1),
            $connection->quoteInto(
                'main_table.attribute_code IN (?)',
                [ProductInterface::STATUS, ProductInterface::VISIBILITY],
            ),
        ];
        $select->where(sprintf('(%s)', implode(' OR ', $conditions)));
        $select->order('position ' . Select::SQL_ASC);

        return $productAttributes;
    }

    private function getFilterableAttributeCodesCollection(): Collection
    {
        $collection = $this->getFilterableAttributesCollection();
        $collection->getSelect()->reset(Select::COLUMNS);
        $collection->getSelect()->columns([AttributeInterface::ATTRIBUTE_ID, AttributeInterface::ATTRIBUTE_CODE]);

        return $collection;
    }

    /**
     * @return string[]
     */
    private function getFilterableAttributeCodes(): array
    {
        try {
            $collection = $this->getFilterableAttributeCodesCollection();

            return $collection->getConnection()->fetchPairs($collection->getSelect());
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());

            return [];
        }
    }

    private function isSystemAttribute(Attribute $attribute): bool
    {
        return empty($attribute->getId()) ||
            in_array(
                $attribute->getAttributeCode(),
                $this->systemAttributeMap->getList(),
                true,
            );
    }

    /**
     * @param string[] $data
     */
    private function createAttribute(array $data): Attribute
    {
        $defaultValues = [
            Attribute::IS_FILTERABLE => 1,
            Attribute::IS_FILTERABLE_IN_SEARCH => 1,
        ];

        $data = array_merge($data, $defaultValues);

        return $this->attributeFactory->create(['data' => $data]);
    }

    private function getAttributePrefix(string $attributeCode): string
    {
        return self::ATTRIBUTE_PREFIX . $this->attributeTypeProvider->getByCode($attributeCode) . '_';
    }
}
