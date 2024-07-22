<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Setup\Patch\Data;

use Magento\Catalog\Model\Category;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddCategoriesBoostAttribute implements DataPatchInterface
{
    public const ATTRIBUTE_CODE = 'lupasearch_boost';

    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * @var ModuleDataSetupInterface
     */
    protected $moduleDataSetup;

    public function __construct(EavSetupFactory $categorySetupFactory, ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->eavSetupFactory = $categorySetupFactory;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @inheritDoc
     */
    public function getAliases(): array
    {
        return [];
    }

    public function apply(): void
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute(
            Category::ENTITY,
            self::ATTRIBUTE_CODE,
            [
                'type' => 'decimal',
                'label' => 'Boost',
                'input' => 'text',
                'required' => false,
                'group' => 'LupaSearch',
                'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                'is_html_allowed_on_front' => false,
                'visible' => true,
                'user_defined' => true,
                'default' => '',
                'unique' => false,
                'apply_to' => '',
            ],
        );
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies(): array
    {
        return [];
    }
}
