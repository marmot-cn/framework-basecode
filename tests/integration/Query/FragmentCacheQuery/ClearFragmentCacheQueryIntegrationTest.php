<?php
namespace Marmot\Basecode\Query;

use PHPUnit\Framework\TestCase;

use Marmot\Basecode\Classes\MockCache;
use Marmot\Core;

/**
 * @Feature: 作为一位开发人员, 我需要在使用数据缓存操作的时候, 通过FragmentCacheQuery, 进行CRUD操作
 * @Scenario: 添加数据数据到缓存后, 返回true
 */
class ClearFragmentCacheQueryIntegrationTest extends TestCase
{
    private $key = 'test';

    private $fragmentKey = 'fragmentKey';
    
    private $data;

    private $dataCacheQuery;

    public function setUp()
    {
        $cache = new MockCache($this->key);
        $this->fragmentCacheQuery = new MockFragmentCacheQuery($this->fragmentKey, $cache);
    }

    public function tearDown()
    {
        unset($this->fragmentCacheQuery);
        Core::$cacheDriver->flushAll();
    }

    /**
     * @Given: 当开发人员准备添加一个数据. id为1, 值为data
     */
    public function prepare()
    {
        $this->data = 'data';
        $this->fragmentCacheQuery->save($this->data);
    }

    /**
     * @When: 当调用 clear 函数时
     */
    public function clear()
    {
        return $this->fragmentCacheQuery->clear();
    }

    /**
     * @Then: 可以在缓存查到该数据
     */
    public function testValidate()
    {
        $this->prepare();
        
        $result = $this->clear();

        $cache = $this->fragmentCacheQuery->getCacheLayer();
        $memcached = $cache->getCacheDriver();
        $actualData = $memcached->fetch($cache->formatID($this->fragmentKey));
        $this->assertEmpty($actualData);
    }
}
