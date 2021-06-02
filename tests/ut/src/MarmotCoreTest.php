<?php
namespace Marmot\Basecode;

use Marmot\Core;
use PHPUnit\Framework\TestCase;
use Marmot\Basecode\MockApplication;
use Marmot\Basecode\MockFramework;
use Marmot\Interfaces\Application\IApplication;
use Marmot\Interfaces\Application\IFramework;
use Marmot\Interfaces\Application\ISdk;

class MarmotCoreTest extends TestCase
{
    public function testInit()
    {
        $core = $this->getMockBuilder(MockMarmotCore::class)
            ->setMethods(
                [
                    'initAutoload',
                    'initFramework',
                    'initApplication',
                    'initContainer',
                    'initCache',
                    'initEnv',
                    'initDb',
                    'initError',
                    'initRoute'
                ]
            )->getMock();

        $this->initCore($core);
        $core->expects($this->once())
              ->method('initRoute');
        $core->init();
    }

    public function testInitCli()
    {
        $core = $this->getMockBuilder(MockMarmotCore::class)
            ->setMethods(
                [
                    'initAutoload',
                    'initFramework',
                    'initApplication',
                    'initContainer',
                    'initCache',
                    'initEnv',
                    'initDb',
                    'initError'
                ]
            )->getMock();

        $this->initCore($core);
        $core->initCli();
    }

    protected function initCore($core)
    {
        $core->expects($this->once())
              ->method('initAutoload');
        $core->expects($this->once())
              ->method('initFramework');
        $core->expects($this->once())
              ->method('initApplication');
        $core->expects($this->once())
              ->method('initContainer');
        $core->expects($this->once())
              ->method('initCache');
        $core->expects($this->once())
              ->method('initEnv');
        $core->expects($this->once())
              ->method('initDb');
        $core->expects($this->once())
              ->method('initError');
    }

    public function testInitEnv()
    {
        $core = $this->getMockBuilder(MockMarmotCore::class)
                    ->setMethods(
                        [
                            'getFramework',
                            'getApplication'
                        ]
                    )->getMock();
        
        $framework = $this->prophesize(IFramework::class);
        $framework->initConfig()->shouldBeCalledTimes(1);

        $core->expects($this->once())
             ->method('getFramework')
             ->willReturn($framework->reveal());
        
        $application = $this->prophesize(IApplication::class);
        $application->initConfig()->shouldBeCalledTimes(1);

        $core->expects($this->once())
             ->method('getApplication')
             ->willReturn($application->reveal());

        $this->assertLessThanOrEqual(time(), Core::$container->get('time'));
        $core->initEnv();
    }

    public function testIsMockedErrorRoute()
    {
        $mockMarmotCore = new MockMarmotCore();

        $_SERVER['HTTP_MOCK_STATUS'] = 1;
        $_SERVER['HTTP_MOCK_ERROR'] = 1;
        $result = $mockMarmotCore->isPublicMockedErrorRoute();
        $this->assertTrue($result);

        $_SERVER['HTTP_MOCK_STATUS'] = 1;
        $_SERVER['HTTP_MOCK_ERROR'] = 0;
        $result = $mockMarmotCore->isPublicMockedErrorRoute();
        $this->assertFalse($result);

        $_SERVER['HTTP_MOCK_STATUS'] = 0;
        $_SERVER['HTTP_MOCK_ERROR'] = 1;
        $result = $mockMarmotCore->isPublicMockedErrorRoute();
        $this->assertFalse($result);

        $_SERVER['HTTP_MOCK_STATUS'] = 0;
        $_SERVER['HTTP_MOCK_ERROR'] = 0;
        $result = $mockMarmotCore->isPublicMockedErrorRoute();
        $this->assertFalse($result);
    }

    public function testInitError()
    {
        $core = $this->getMockBuilder(MockMarmotCore::class)
                    ->setMethods(
                        [
                            'getFramework',
                            'getApplication',
                            'getSdks'
                        ]
                    )->getMock();

        $framework = $this->prophesize(IFramework::class);
        $framework->initErrorConfig()->shouldBeCalledTimes(1);
        $framework->getErrorDescriptions()->shouldBeCalledTimes(1)->willReturn([]);

        $core->expects($this->once())
             ->method('getFramework')
             ->willReturn($framework->reveal());
        
        $application = $this->prophesize(IApplication::class);
        $application->initErrorConfig()->shouldBeCalledTimes(1);
        $application->getErrorDescriptions()->shouldBeCalledTimes(1)->willReturn([]);

        $core->expects($this->once())
             ->method('getApplication')
             ->willReturn($application->reveal());

        $sdk = $this->prophesize(ISdk::class);
        $sdk->initErrorConfig()->shouldBeCalledTimes(1);
        $sdk->getErrorDescriptions()->shouldBeCalledTimes(1)->willReturn([]);

        $core->expects($this->once())
            ->method('getSdks')
            ->willReturn([$sdk->reveal()]);

        $core->initError();
        $this->assertEquals(ERROR_NOT_DEFINED, $core::getLastError()->getId());
    }

    public function testGetSdks()
    {
        $core = new MockMarmotCore();
        $sdks = $core->getSdks();

        $this->assertEquals([], $sdks);
    }
}
