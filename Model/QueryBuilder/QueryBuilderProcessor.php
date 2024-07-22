<?php

declare(strict_types=1);

namespace LupaSearch\LupaSearchPlugin\Model\QueryBuilder;

use LupaSearch\LupaSearchPluginCore\Api\Data\SearchQueries\SearchQueryInterface;
use Magento\Framework\App\Area;
use Magento\Framework\App\AreaList;
use Magento\Framework\App\State;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\Store;

class QueryBuilderProcessor implements QueryBuilderInterface
{
    private State $state;

    private Emulation $emulation;

    private QueryBuilderInterface $queryBuilder;

    private AreaList $areaList;

    private bool $translationsLoaded = false;

    public function __construct(
        State $state,
        Emulation $emulation,
        QueryBuilderInterface $queryBuilder,
        AreaList $areaList
    ) {
        $this->state = $state;
        $this->emulation = $emulation;
        $this->queryBuilder = $queryBuilder;
        $this->areaList = $areaList;
    }

    public function build(?SearchQueryInterface $searchQuery = null, ?int $storeId = 0): SearchQueryInterface
    {
        $callback = function (int $storeId, ?SearchQueryInterface $searchQuery = null): SearchQueryInterface {
            $this->emulation->startEnvironmentEmulation($storeId, Area::AREA_FRONTEND, true);
            $result = $this->queryBuilder->build($searchQuery, $storeId);
            $this->emulation->stopEnvironmentEmulation();

            return $result;
        };

        if (!$this->translationsLoaded) {
            $area = $this->areaList->getArea(Area::AREA_FRONTEND);
            $area->load(Area::PART_TRANSLATE);
            $this->translationsLoaded = true;
        }

        return $this->state->emulateAreaCode(
            Area::AREA_FRONTEND,
            $callback,
            [$storeId ?? Store::DEFAULT_STORE_ID, $searchQuery],
        );
    }
}
