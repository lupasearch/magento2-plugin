<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model;

interface QueriesGeneratorInterface
{
    public function generateAll(): void;

    public function generateByStore(int $storeId): void;
}
