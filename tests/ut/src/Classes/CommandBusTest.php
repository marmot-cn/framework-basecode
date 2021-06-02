<?php
namespace Marmot\Basecode\Classes;

use PHPUnit\Framework\TestCase;

use Marmot\Core;
use Marmot\Basecode\Classes\CommandBus;
use Marmot\Interfaces\ICommandHandlerFactory;
use Marmot\Interfaces\ICommand;
use Marmot\Interfaces\INull;
use Marmot\Interfaces\ICommandHandler;
use Marmot\Basecode\Classes\NullCommandHandler;

use Prophecy\Argument;

class CommandBusTest extends TestCase
{
    private $commandBus;
    
    private $command;

    public function setUp()
    {
        $this->commandHandlerFactory = $this->prophesize(ICommandHandlerFactory::class);
        $this->command = $this->prophesize(ICommand::class);
        $this->commandBus= $this->getMockBuilder(CommandBus::class)
                                ->setMethods(['sendAction',
                                    'getCommandHandlerFactory'])
                                ->setConstructorArgs([$this->commandHandlerFactory->reveal()])
                                ->getMock();
    }

    public function tearDown()
    {
        unset($this->commandBus);
        unset($this->commandHandlerFactory);
        unset($this->command);
    }

    public function testGetCommandHandlerFactory()
    {
        $commandBus = new MockCommandBus(new MockCommandHandlerFactory);
        $commandHandlerFactory = $commandBus->getCommandHandlerFactory();

        $this->assertInstanceOf('Marmot\Interfaces\ICommandHandlerFactory', $commandHandlerFactory);
    }

    /**
     * 测试 CommandHandler 不存在情况
     */
    public function testNullCommandHandler()
    {
        $commandHandler = $this->getMockBuilder(NullCommandHandler::class)
                               ->disableOriginalConstructor()
                               ->getMock();

        $this->commandHandlerFactory->getHandler(
            Argument::exact($this->command)
        )->shouldBeCalledTimes(1)
         ->willReturn($commandHandler);

        $this->commandBus->expects($this->once())
                         ->method('getCommandHandlerFactory')
                         ->willReturn($this->commandHandlerFactory->reveal());

        $result = $this->commandBus->send($this->command->reveal());
        $this->assertFalse($result);
        $this->assertEquals(COMMAND_HANDLER_NOT_EXIST, Core::getLastError()->getId());
    }

    public function testSendCommandHandlerExist()
    {
        $commandHandler = new MockCommandHandler();
        $command = new MockCommand();

        $this->commandHandlerFactory->getHandler(
            Argument::exact($command)
        )->shouldBeCalledTimes(1)
         ->willReturn($commandHandler);

        $this->commandBus->expects($this->once())
                         ->method('getCommandHandlerFactory')
                         ->willReturn($this->commandHandlerFactory->reveal());

        $this->commandBus->expects($this->once())
                         ->method('sendAction')
                         ->with($commandHandler, $command)
                         ->willReturn(true);
        
        $result = $this->commandBus->send($command);
        $this->assertTrue($result);
    }
}
