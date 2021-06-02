<?php
namespace Marmot\Basecode\Query;

use PHPUnit\Framework\TestCase;

use Marmot\Basecode\Classes\MockCache;
use Marmot\Core;

/**
 * @Feature: 作为一位开发人员, 我需要在使用数据缓存操作的时候, 通过DataCacheQuery, 进行CRUD操作
 * @Scenario: 从数据缓存删除数据
 */
class DelDataCacheQueryIntegrationTest extends TestCase
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
     * @Given: 当开发人员准备删除一个缓存数据. id为1
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
     * @When: 当从缓存获取数据时候, id为1, 期望返回成功
     */
    public function del()
    {
        return $this->dataCacheQuery->del($this->id);
    }

    /**
     * @Then: 缓存数据已经为空
     */
    public function testValidate()
    {
        $this->prepare();
        
        $result = $this->del();
        $this->assertTrue($result);

        $cache = $this->dataCacheQuery->getCacheLayer();
        $memcached = $cache->getCacheDriver();
        $actualData = $memcached->fetch($cache->formatID($this->id));
        $this->assertEmpty($actualData);
    }
}
