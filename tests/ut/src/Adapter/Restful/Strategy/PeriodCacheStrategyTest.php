<?php
namespace Marmot\Basecode\Adapter\Restful\Strategy;

use Marmot\Basecode\Adapter\Restful\Strategy\MockPeriodCacheStrategy;
use Marmot\Basecode\Adapter\Restful\Strategy\PeriodCacheStrategy;
use Marmot\Basecode\Adapter\Restful\Repository\CacheResponseRepository;
use Marmot\Basecode\Adapter\Restful\CacheResponse;
use Marmot\Core;

use GuzzleHttp\Psr7\Response;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class PeriodCacheStrategyTest extends TestCase
{
    private $periodStrategy;

    private $strategy;

    public function setUp()
    {
        $this->mockStrategy = new MockPeriodCacheStrategy();
        $this->strategy =  $this->getMockForTrait(PeriodCacheStrategy::class);
    }

    public function tearDown()
    {
        unset($this->mockStrategy);
        unset($this->strategy);
    }

    public function testGetPrefix()
    {
        $this->assertEquals('period_', $this->mockStrategy->getPublicPrefix());
    }

    public function testCachedStatusCode()
    {
        $this->assertEquals('304', $this->mockStrategy->getPublicCachedStatusCode());
    }

    public function testIsResponseCached()
    {
        $result = $this->mockStrategy->isPublicResponseCached(new Response(304));
        $this->assertTrue($result);
    }

    /**
     * 测试 testIsTimeOut()
     */
    public function testIsTimeOut()
    {
        $cacheResponse = new CacheResponse(
            200,
            'contents',
            ['headers'],
            Core::$container->get('time') - 1
        );

        $result = $this->mockStrategy->isPublicTimeOut($cacheResponse);
        $this->assertTrue($result);
    }

    /**
     * 测试 testIsNotTimeOut()
     */
    public function testIsNotTimeOut()
    {
        $cacheResponse = new CacheResponse(
            200,
            'contents',
            ['headers'],
            Core::$container->get('time') + 1
        );

        $result = $this->mockStrategy->isPublicTimeOut($cacheResponse);
        $this->assertFalse($result);
    }

    /**
     * 测试 testGetDefaultTTL
     */
    public function testGetDefaultTTL()
    {
        $result = $this->mockStrategy->getPublicTTL();
        $this->assertEquals(300, $result);
    }

    public function testGetTTL()
    {
        Core::$container->set('cache.restful.ttl', 400);
        $result = $this->mockStrategy->getPublicTTL();
        $this->assertEquals(Core::$container->get('cache.restful.ttl'), $result);
    }

    /**
     * 测试 encryptkey
     */
    public function testEncryptKey()
    {
        $url = 'url';
        $query = ['query'];
        $requestHeaders = ['headers'];

        $mockStrategy = $this->getMockBuilder(MockPeriodCacheStrategy::class)
                             ->setMethods(
                                 ['getPrefix']
                             )->getMock();
        $mockStrategy->expects($this->once())
                     ->method('getPrefix')
                     ->willReturn('');

        $key = $mockStrategy->publicEncryptKey($url, $query, $requestHeaders);
        $this->assertInternalType('string', $key);
        $this->assertNotEmpty($key);
    }

    /**
     * 测试 testGetEtagWithoutEtagHeaders
     */
    public function testGetEtagWithoutEtagHeaders()
    {
        $cacheResponse = new CacheResponse(200, 'contents', []);
        $result = $this->mockStrategy->getPublicEtag($cacheResponse);
        $this->assertEmpty($result);
    }

    /**
     * 测试 testGetEtagWithExistEtagHeaders
     */
    public function testGetEtagWithExistEtagHeaders()
    {
        $expectedEtag = 'etag';
        $cacheResponse = new CacheResponse(200, 'contents', ['ETag'=>[$expectedEtag]]);
        $result = $this->mockStrategy->getPublicEtag($cacheResponse);
        $this->assertEquals($expectedEtag, $result);
    }

    /**
     * 测试 refreshTTL
     */
    public function testRefreshTTL()
    {
        $ttl = 400;

        $mockPeriodCacheStrategy = $this->getMockBuilder(MockPeriodCacheStrategy::class)
                            ->setMethods(
                                [
                                    'getTTL'
                                ]
                            )->getMock();

        $mockPeriodCacheStrategy->expects($this->once())
                      ->method('getTTL')
                      ->willReturn($ttl);

        $cacheResponse = $this->prophesize(CacheResponse::class);
        $cacheResponse->setTTL(Core::$container->get('time') + $ttl)
                      ->shouldBeCalledTimes(1);

        $mockPeriodCacheStrategy->publicRefreshTTL($cacheResponse->reveal());
    }

    /**
     * 测试 refreshCache
     */
    public function testRefreshCace()
    {
        $key = 'key';
        $cacheResponse = new CacheResponse(200, 'contents', []);

        $mockPeriodCacheStrategy = $this->getMockBuilder(MockPeriodCacheStrategy::class)
                            ->setMethods(
                                [
                                    'getCacheResponseRepository'
                                ]
                            )->getMock();

        $cacheResponseRepository = $this->prophesize(CacheResponseRepository::class);
        $cacheResponseRepository->save(Argument::exact($key), Argument::exact($cacheResponse))
                                ->shouldBeCalledTimes(1)
                                ->willReturn(true);

        $mockPeriodCacheStrategy->expects($this->once())
                      ->method('getCacheResponseRepository')
                      ->willReturn($cacheResponseRepository->reveal());

        $result = $mockPeriodCacheStrategy->refreshCache($key, $cacheResponse);
        $this->assertTrue($result);
    }

    /**
     * 测试 getWithCache($url, $query, $requestHeaders)
     * not null $cacheResponse
     */
    public function testGetWithCacheNotNullCacheResponse()
    {
        $key = 'key';

        $mockPeriodCacheStrategy = $this->getMockBuilder(MockPeriodCacheStrategy::class)
                            ->setMethods(
                                [
                                    'getWithCache',
                                    'getCacheResponseRepository'
                                ]
                            )->getMock();
    }
}
