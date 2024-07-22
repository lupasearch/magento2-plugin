<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Category;

use LupaSearch\LupaSearchPlugin\Model\Provider\BoostProviderInterface;

class BoostProvider implements BoostProviderInterface
{
    /**
     * @description Title attribute boost
     */
    private const TITLE_BOOST = 1;

    /**
     * @inheritDoc
     */
    public function getBoosts(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getQueryFields(): array
    {
        return [
            'title' => self::TITLE_BOOST,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getBoostFields(): array
    {
        return [];
    }
}
