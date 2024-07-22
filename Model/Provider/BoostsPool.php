<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\Provider;

use function array_filter;

class BoostsPool
{
    /**
     * @var FieldBoostInterface[]
     */
    private $boosts;

    /**
     * @param FieldBoostInterface[] $boosts
     */
    public function __construct(array $boosts = [])
    {
        $this->boosts = array_filter($boosts);
    }

    /**
     * @return FieldBoostInterface[]
     */
    public function getAll(): array
    {
        $boosts = [];

        foreach ($this->boosts as $field => $fieldBoost) {
            if (!$fieldBoost instanceof FieldBoostInterface) {
                continue;
            }

            $boosts[$field] = $fieldBoost;
        }

        return $boosts;
    }
}
