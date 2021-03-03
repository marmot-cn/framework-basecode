<?php
//powered by chloroplast
namespace Marmot\Basecode\Classes;

use PHPUnit\Framework\TestCase;

use Marmot\Basecode\Classes\MockCache;
use Marmot\Core;

/**
 * @Feature: 作为一位开发人员, 我需要在使用缓存操作的时候, 通过Cache, 进行CRUD操作
 * @Scenario: 从缓存获取数据
 */
class AC2 extends TestCase
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
     * @Given: 当开发人员准备获取一个数据. id为1, 值为data
     */
    public function prepare()
    {
        $this->id = 1;
        $this->data = 'data';

        $memcached = $this->cache->getCacheDriver();
        $memcached->save($this->cache->formatID($this->id), $this->data);
    }

    /**
     * @When: 当从缓存获取数据时候, id为1, 期望返回数据
     */
    public function get()
    {
        return $this->cache->get($this->id);
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
