<?php
namespace Marmot\Basecode\Query;

use PHPUnit\Framework\TestCase;

use Marmot\Basecode\Classes\MockCache;
use Marmot\Core;

/**
 * @Feature: 作为一位开发人员, 我需要在使用数据缓存操作的时候, 通过DataCacheQuery, 进行CRUD操作
 * @Scenario: 从数据缓存获取数据
 */
class GetDataCacheQueryIntegrationTest extends TestCase
{
    private $key = 'test';

    private $id;
    
    private $data;

    public function setUp()
    {
        $cache = new MockCache($this->key);
        $this->dataCacheQuery = new MockDataCacheQUery($cache);
    }

    public function tearDown()
    {
        unset($this->dataCacheQuery);
        Core::$cacheDriver->flushAll();
    }

    /**
     * @Given: 当开发人员准备获取一个数据. id为1, 值为data
     */
    public function prepare()
    {
        $this->id = 1;
        $this->data = 'data';

        $cache = $this->dataCacheQuery->getCacheLayer();
        $memcached = $cache->getCacheDriver();
        $memcached->save($cache->formatID($this->id), $this->data);
    }

    /**
     * @When: 当从缓存获取数据时候, id为1, 期望返回数据
     */
    public function get()
    {
        return $this->dataCacheQuery->get($this->id);
    }

    /**
     * @Then: 数据等于'data', 即和存入数据一致
     */
    public function testValidate()
    {
        $this->prepare();
        
        $result = $this->get();
        $this->assertEquals($this->data, $result);
    }
}
