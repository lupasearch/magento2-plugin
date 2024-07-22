<?php

namespace LupaSearch\LupaSearchPlugin\Model;

/**
 * @phpcs:disable SlevomatCodingStandard.TypeHints,SlevomatCodingStandard.Commenting.UselessFunctionDocComment
 */
interface BatchInterface
{
    /**
     * @return int[]
     */
    public function getIds(): array;

    /**
     * @param int[] $ids
     * @return void
     */
    public function setIds(array $ids): void;

    /**
     * @return int
     */
    public function getStoreId(): int;

    /**
     * @param int $id
     * @return void
     */
    public function setStoreId(int $id): void;
}
