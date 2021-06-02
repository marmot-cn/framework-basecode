<?php
namespace Marmot\Basecode\Query;

use Marmot\Interfaces\CacheLayer;

class MockFragmentCacheQuery extends FragmentCacheQuery
{
    protected function fetchCacheData()
    {
        return 'data';
    }

    public function getCacheLayer() : CacheLayer
    {
        return parent::getCacheLayer();
    }

    public function getFragmentKey() : string
    {
        return parent::getFragmentKey();
    }

    public function getTtl() : int
    {
        return parent::getTtl();
    }

    public function save($data) : bool
    {
        return parent::save($data);
    }
}
