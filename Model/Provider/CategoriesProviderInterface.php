<?php

namespace LupaSearch\LupaSearchPlugin\Model\Provider;

interface CategoriesProviderInterface extends DataProviderInterface
{
    /**
     * @param int $storeId
     * @return string[]
     */
    public function getIdNameMap(int $storeId): array;

    public function getNameById(int $id, int $storeId): string;
}
