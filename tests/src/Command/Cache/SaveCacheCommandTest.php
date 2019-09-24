<?php
namespace Marmot\Basecode\Command\Cache;

use Marmot\Basecode\Observer\CacheObserver;
use Marmot\Basecode\Classes\Transaction;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class SaveCacheCommandTest extends TestCase
{
    private $command;
    private $cacheDriver;

    protected $key = 'test';
    protected $time = 1;
    protected $data = 'data';

    public function setUp()
    {
        $this->command = $this->getMockBuilder(SaveCacheCommand::class)
                         ->setMethods(
                             [
                                 'getCacheDriver'
                             ]
                         )->setConstructorArgs(array($this->key, $this->data, $this->time))
                         ->getMock();
        $this->cacheDriver = $this->prophesize(Doctrine\Common\Cache\CacheProvider::class);
    }

    public function tearDown()
    {
        unset($this->command);
        unset($this->cacheDriver);
    }

    public function testImplementsCommand()
    {
        $this->assertInstanceOf('Marmot\Basecode\Interfaces\Command', $this->command);
    }

    public function testConstructro()
    {
        $mockCommand = new MockSaveCacheCommand($this->key, $this->data, $this->time);
        $this->assertEquals($this->key, $mockCommand->getKey());
        $this->assertEquals($this->data, $mockCommand->getData());
        $this->assertEquals($this->time, $mockCommand->getTime());
    }


    /**
     * 1. 预言cachedriver
     *  1.1 被调用一次
     *  1.2 入参key, data, time
     *  1.3 返回true
     * 2. 回测getCacheDriver会被调用一次
     * 3. 绑定getcachedriver和预言
     */
    public function testExecute()
    {
        $cacheDriver = $this->prophesize(\Doctrine\Common\Cache\CacheProvider::class);
        $cacheDriver->save(
            Argument::exact($this->key),
            Argument::exact($this->data),
            Argument::exact($this->time)
        )
                    ->shouldBeCalledTimes(1)
                    ->willReturn(true);

        $this->command->expects($this->once())
                      ->method('getCacheDriver')
                      ->willReturn($cacheDriver->reveal());

        $result = $this->command->execute();
        $this->assertTrue($result);
    }

    public function testUndo()
    {
        $cacheDriver = $this->prophesize(\Doctrine\Common\Cache\CacheProvider::class);
        $cacheDriver->delete(
            Argument::exact($this->key)
        )
                    ->shouldBeCalledTimes(1)
                    ->willReturn(true);

        $this->command->expects($this->once())
                      ->method('getCacheDriver')
                      ->willReturn($cacheDriver->reveal());

        $result = $this->command->undo();
        $this->assertTrue($result);
    }
}
