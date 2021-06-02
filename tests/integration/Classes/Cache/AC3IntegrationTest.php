<?php
//powered by chloroplast
namespace Marmot\Basecode\Classes;

use PHPUnit\Framework\TestCase;

use Marmot\Basecode\Classes\MockCache;
use Marmot\Core;

/**
 * @Feature: 作为一位开发人员, 我需要在使用缓存操作的时候, 通过Cache, 进行CRUD操作
 * @Scenario: 从缓存删除数据
 */
class AC3IntegrationTest extends TestCase
{
    private $key = 'test';

    private $id;
    
    private $data;

    private $cache;

    public function setUp()
    {
        $this->cache = new MockCache($this->key);
    }

    public function tearDown()
    {
        unset($this->cache);
        Core::$cacheDriver->flushAll();
    }

    /**
     * @Given: 当开发人员准备删除一个缓存数据. id为1
     */
    public function prepare()
    {
        $this->id = 1;
        $this->data = 'data';

        $memcached = $this->cache->getCacheDriver();
        $memcached->save($this->cache->formatID($this->id), $this->data);
    }

    /**
     * @When: 当从缓存获取数据时候, id为1, 期望返回成功
     */
    public function del()
    {
        return $this->cache->del($this->id);
    }

    /**
     * @Then: 缓存数据已经为空
     */
    public function testValidate()
    {
        $this->prepare();
        
        $result = $this->del();
        $this->assertTrue($result);

        $memcached = $this->cache->getCacheDriver();
        $actualData = $memcached->fetch($this->cache->formatID($this->id));
        $this->assertEmpty($actualData);
    }
}
