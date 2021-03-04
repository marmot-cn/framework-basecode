<?php
namespace Marmot\Basecode\Query;

use Marmot\Interfaces\CacheLayer;

class MockDataCacheQuery extends DataCacheQuery
{
    public function getCacheLayer() : CacheLayer
    {
        return parent::getCacheLayer();
    }
}
