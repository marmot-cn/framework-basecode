<?php
namespace Marmot\Basecode\Query;

use Marmot\Basecode\Classes;
use Marmot\Basecode\Classes\MockCache;
use Marmot\Interfaces\CacheLayer;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class FragmentCacheQueryTest extends TestCase
{
    private $fragmentCacheQuery;

    private $mockFragmentCacheQuery;

    private $fragmentKey;//片段缓存key名

    private $cacheLayer;//缓存层

    private $mockCache;

    public function setUp()
    {
        $this->fragmentKey = 'key';
        $this->fragmentCacheQuery = $this->getMockBuilder(FragmentCacheQuery::class)
                                ->setMethods(
                                    [
                                        'getCacheLayer',
                                        'fetchCacheData',
                                        'refresh',
                                        'getFragmentKey'
                                    ]
                                )->disableOriginalConstructor()
                                ->getMock();

        $this->mockCache = new MockCache('cacheKey');
        $this->mockFragmentCacheQuery = new MockFragmentCacheQuery(
            $this->fragmentKey,
            $this->mockCache
        );
        $this->cacheLayer = $this->prophesize(CacheLayer::class);
    }

    public function tearDown()
    {
        unset($this->fragmentCacheQuery);
        unset($this->mockFragmentCacheQuery);
        unset($this->cacheLayer);
        unset($this->fragmentKey);
    }

    public function testGetCacheLayer()
    {
        $this->assertEquals($this->mockCache, $this->mockFragmentCacheQuery->getCacheLayer());
    }

    public function testGetFragmentKey()
    {
        $this->assertEquals($this->fragmentKey, $this->mockFragmentCacheQuery->getFragmentKey());
    }

    public function testGetDefaultTtl()
    {
        $this->assertEquals(0, $this->mockFragmentCacheQuery->getTtl());
    }

    /**
     * 测试成功获取数据
     */
    public function testGetSuccess()
    {
        $expected = 'data';

        $this->bindMock();
        $this->cacheLayer->get(Argument::exact($this->fragmentKey))
                         ->shouldBeCalledTimes(1)
                         ->willReturn($expected);

        $result = $this->fragmentCacheQuery->get();
        $this->assertEquals($expected, $result);
    }

    /**
     * 测试获取get, 缓存没有数据, 需要从refresh获取数据
     */
    public function testGetWithRefreshData()
    {
        $expected = 'data';

        $this->cacheLayer->get(Argument::exact($this->fragmentKey))
                         ->shouldBeCalledTimes(1)
                         ->willReturn('');

        $this->fragmentCacheQuery->expects($this->once())
                             ->method('refresh')
                             ->willReturn($expected);

        $this->bindMock();

        $result = $this->fragmentCacheQuery->get();
        $this->assertEquals($expected, $result);
    }

    /**
     * 测试获取数据失败
     */
    public function testGetFail()
    {
        $this->cacheLayer->get(Argument::exact($this->fragmentKey))
                         ->shouldBeCalledTimes(1)
                         ->willReturn('');

        $this->fragmentCacheQuery->expects($this->once())
                             ->method('refresh')
                             ->willReturn('');

        $this->bindMock();

        $result = $this->fragmentCacheQuery->get();
        $this->assertFalse($result);
    }

    /**
     * 测试清楚缓存数据
     */
    public function testClear()
    {
        $this->cacheLayer->del(Argument::exact($this->fragmentKey))
                         ->shouldBeCalledTimes(1)
                         ->willReturn(true);

        $this->bindMock();

        $this->fragmentCacheQuery->clear();
    }

    /**
     * 测试刷新缓存数据
     */
    public function testRefresh()
    {
        $expected = 'data';

        $fragmentCacheQuery = $this->getMockBuilder(FragmentCacheQuery::class)
                                ->setMethods(
                                    [
                                        'fetchCacheData',
                                        'save'
                                    ]
                                )->disableOriginalConstructor()
                                ->getMock();

        $fragmentCacheQuery->expects($this->once())
                             ->method('save');
        $fragmentCacheQuery->expects($this->once())
                             ->method('fetchCacheData')
                             ->willReturn($expected);

        $result = $fragmentCacheQuery->refresh();
        $this->assertEquals($expected, $result);
    }

    /**
     * 测试保存数据
     */
    public function testSave()
    {
        $data = 'data';
        $ttl = 10;

        $fragmentCacheQuery = $this->getMockBuilder(MockFragmentCacheQuery::class)
                                ->setMethods(
                                    [
                                        'getCacheLayer',
                                        'getFragmentKey',
                                        'getTtl'
                                    ]
                                )->disableOriginalConstructor()
                                ->getMock();

        $this->cacheLayer->save(
            Argument::exact($this->fragmentKey),
            Argument::exact($data),
            Argument::exact($ttl)
        )->shouldBeCalledTimes(1)
         ->willReturn(true);

        $fragmentCacheQuery->expects($this->once())
                             ->method('getFragmentKey')
                             ->willReturn($this->fragmentKey);

        $fragmentCacheQuery->expects($this->once())
                             ->method('getCacheLayer')
                             ->willReturn($this->cacheLayer->reveal());

        $fragmentCacheQuery->expects($this->once())
                             ->method('getTtl')
                             ->willReturn($ttl);

        $result = $fragmentCacheQuery->save($data);
        $this->assertTrue($result);
    }

    private function bindMock()
    {
        $this->fragmentCacheQuery->expects($this->once())
                             ->method('getCacheLayer')
                             ->willReturn($this->cacheLayer->reveal());

        $this->fragmentCacheQuery->expects($this->once())
                             ->method('getFragmentKey')
                             ->willReturn($this->fragmentKey);
    }
}
