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

    private $commandHandler;
    
    private $command;

    private $transaction;

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
        // $commandHandler = $this->prophesize(ICommandHandler::class);
        // $commandHandler->execute(
        //     Argument::exact($this->command)
        // )->shouldBeCalledTimes(1)->willReturn(true);

        // $this->commandHandlerFactory->getHandler(
        //     Argument::exact($this->command)
        // )->shouldBeCalledTimes(1)
        //  ->willReturn($commandHandler->reveal());

        // $this->commandBus->expects($this->once())
        //                  ->method('getCommandHandlerFactory')
        //                  ->willReturn($this->commandHandlerFactory->reveal());
        
        
        // $this->assertTrue($result);
    }
}
