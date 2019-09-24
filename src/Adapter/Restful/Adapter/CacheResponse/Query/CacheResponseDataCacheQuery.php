<?php
namespace Marmot\Basecode\Adapter\Restful\Adapter\CacheResponse\Query;

use Marmot\Basecode\Adapter\Restful\Adapter\CacheResponse\Query\Persistence\CacheResponseCache;

use Marmot\Basecode\Query\DataCacheQuery;

class CacheResponseDataCacheQuery extends DataCacheQuery
{
    public function __construct()
    {
        parent::__construct(new CacheResponseCache());
    }
}
