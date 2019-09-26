<?php
namespace Marmot\Basecode\Adapter\Restful\Adapter\CacheResponse\Query;

use PHPUnit\Framework\TestCase;
use Marmot\Core;

use Marmot\Interfaces\DbLayer;
use Marmot\Interfaces\CacheLayer;

class CacheResponseDataCacheQueryTest extends TestCase
{
    private $stub;

    private $tablepre = 'pcore_';

    public function setUp()
    {
        $this->stub= new class extends CacheResponseDataCacheQuery
        {
            public function getCacheLayer() : CacheLayer
            {
                return parent::getCacheLayer();
            }
        };
    }

    /**
     * 测试该文件是否正确的继承DataCacheQuery类
     */
    public function testCorrectInstanceExtendsDataCacheQuery()
    {
        $this->assertInstanceof('Marmot\Basecode\Query\DataCacheQuery', $this->stub);
    }

    /**
     * 测试是否cache层赋值正确
     */
    public function testCorrectCacheLayer()
    {
        $this->assertInstanceof(
            'Marmot\Basecode\Adapter\Restful\Adapter\CacheResponse\Query\Persistence\CacheResponseCache',
            $this->stub->getCacheLayer()
        );
    }
}
