<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Product\Attributes;

use LupaSearch\LupaSearchPlugin\Model\Provider\Attributes\SystemAttributeMapInterface;

use function array_filter;

class SystemAttributeMap implements SystemAttributeMapInterface
{
    /**
     * @var string[]
     */
    private $keys;

    /**
     * @param string[] $keys
     */
    public function __construct(array $keys = [])
    {
        $this->keys = array_filter($keys);
    }

    /**
     * @inheritDoc
     */
    public function getList(): array
    {
        return $this->keys;
    }
}
