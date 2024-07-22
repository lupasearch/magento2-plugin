<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Attributes;

use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\BoostAttributesProviderInterface;
use LupaSearch\LupaSearchPlugin\Setup\Patch\Data\AddProductBoostAttribute;
use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;

class BoostAttributeProvider
{
    /**
     * @var EavConfig
     */
    private $eavConfig;

    /**
     * @var BoostAttributesProviderInterface
     */
    private $boostAttributesProvider;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var AbstractAttribute|null
     */
    private $attribute;

    /**
     * @var int|null
     */
    private $attributeId;

    public function __construct(
        EavConfig $eavConfig,
        BoostAttributesProviderInterface $boostAttributesProvider,
        LoggerInterface $logger
    ) {
        $this->eavConfig = $eavConfig;
        $this->boostAttributesProvider = $boostAttributesProvider;
        $this->logger = $logger;
    }

    /**
     * @throws LocalizedException
     */
    public function getAttribute(): AbstractAttribute
    {
        if (null !== $this->attribute) {
            return $this->attribute;
        }

        try {
            $this->attribute = $this->eavConfig->getAttribute(
                Product::ENTITY,
                AddProductBoostAttribute::ATTRIBUTE_CODE,
            );
        } catch (LocalizedException $exception) {
            $this->logger->error($exception);

            throw $exception;
        }

        return $this->attribute;
    }

    public function getId(): int
    {
        if (null !== $this->attributeId) {
            return $this->attributeId;
        }

        try {
            $this->attributeId = (int)$this->getAttribute()->getId();
        } catch (LocalizedException $exception) {
            $this->attributeId = 0;
        }

        return $this->attributeId;
    }

    public function getLupaSearchId(): ?string
    {
        return !$this->getId() ? null : $this->boostAttributesProvider->getAttributeId($this->getAttribute());
    }
}
