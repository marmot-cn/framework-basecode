<?php
namespace Marmot\Basecode\Query;

use PHPUnit\Framework\TestCase;

use Marmot\Basecode\Classes\MockCache;
use Marmot\Core;

/**
 * @Feature: 作为一位开发人员, 我需要在使用数据缓存操作的时候, 通过DataCacheQuery, 进行CRUD操作
 * @Scenario: 添加数据数据到缓存后, 可以获取到该数据
 */
class AddDataCacheQueryBddTest extends TestCase
{
    private $key = 'test';

    private $id;
    
    private $data;

    private $dataCacheQuery;

    public function setUp()
    {
        $cache = new MockCache($this->key);
        $this->dataCacheQuery = new MockDataCacheQuery($cache);
    }

    public function tearDown()
    {
        unset($this->dataCacheQuery);
        Core::$cacheDriver->flushAll();
    }

    /**
     * @Given: 当开发人员准备添加一个数据. id为1, 值为data
     */
    public function prepare()
    {
        $this->id = 1;
        $this->data = 'data';
    }

    /**
     * @When: 当调用添加函数时, 期望返回添加 true
     */
    public function add()
    {
        return $this->dataCacheQuery->save($this->id, $this->data);
    }

    /**
     * @Then: 可以在缓存查到该数据
     */
    public function testValidate()
    {
        $this->prepare();
        
        $result = $this->add();
        $this->assertTrue($result);

        $actualData = $this->dataCacheQuery->get($this->id);
        $this->assertEquals($this->data, $actualData);
    }
}
