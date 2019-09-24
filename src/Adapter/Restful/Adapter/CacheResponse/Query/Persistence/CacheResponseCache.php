<?php
namespace Marmot\Basecode\Adapter\Restful\Adapter\CacheResponse\Query\Persistence;

use Marmot\Basecode\Classes\Cache;

class CacheResponseCache extends Cache
{
    public function __construct()
    {
        parent::__construct('restful');
    }
}
