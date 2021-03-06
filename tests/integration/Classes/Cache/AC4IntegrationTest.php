<?php
//powered by chloroplast
namespace Marmot\Basecode\Classes;

use PHPUnit\Framework\TestCase;

use Marmot\Basecode\Classes\MockCache;
use Marmot\Core;

/**
 * @Feature: 作为一位开发人员, 我需要在使用缓存操作的时候, 通过Cache, 进行CRUD操作
 * @Scenario: 从缓存批量获取数据
 */
class AC4IntegrationTest extends TestCase
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
     * @Given: 当开发人员准备获取一个数据
     */
    public function prepare()
    {
        $this->ids = [1, 2, 3];

        $this->data = [
            $this->ids[0]=>'data1',
            $this->ids[1]=>'data2',
        ];

        $memcached = $this->cache->getCacheDriver();

        for ($i = 0; $i<2; $i++) {
            $memcached->save(
                $this->cache->formatID($this->ids[$i]),
                $this->data[$this->ids[$i]]
            );
        }
    }

    /**
     * @When: 当从缓存批量获取数据时候
     */
    public function getList()
    {
        return $this->cache->getList($this->ids);
    }

    /**
     * @Then: 数据等于'data', 即和存入数据一致
     * @And: id 为 1, 2 返回 data1 和 data2
     * @And: 3 返回空
     */
    public function testValidate()
    {
        $this->prepare();
        
        list($hits, $miss) = $this->getList();

        $this->assertEquals(array_values($this->data), $hits);
        $this->assertEquals([$this->ids[2]], $miss);
    }
}
