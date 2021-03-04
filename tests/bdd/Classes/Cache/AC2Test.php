<?php
//powered by chloroplast
namespace Marmot\Basecode\Classes;

use PHPUnit\Framework\TestCase;

use Marmot\Basecode\Classes\MockCache;
use Marmot\Core;

/**
 * @Feature: 作为一位开发人员, 我需要在使用缓存操作的时候, 通过Cache, 进行CRUD操作
 * @Scenario: 添加数据到缓存后, 删除缓存数据, 返回true
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
     * @Given: 当开发人员准备添加一个数据. id为1, 值为data
     */
    public function prepare()
    {
        $this->id = 1;
        $this->data = 'data';
        $this->cache->save($this->id, $this->data);
    }

    /**
     * @When: 当从缓存获取数据时候, id为1, 期望返回成功
     */
    public function del()
    {
        return $this->cache->del($this->id);
    }

    /**
     * @Then: 可以在缓存查到该数据
     */
    public function testValidate()
    {
        $this->prepare();
        
        $result = $this->del();
        $this->assertTrue($result);

        $result = $this->cache->get($this->id);
        $this->assertEmpty($result);
    }
}
