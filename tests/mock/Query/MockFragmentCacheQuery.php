<?php
namespace Marmot\Basecode\Query;

class MockFragmentCacheQuery extends FragmentCacheQuery
{
    protected function fetchCacheData()
    {
        return '';
    }

    public function getCacheLayer() : CacheLayer
    {
        return parent::getCacheLayer();
    }

    public function getFragmentKey() : string
    {
        return parent::getFragmentKey();
    }

    public function fetchCacheData()
    {
        return true;
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
