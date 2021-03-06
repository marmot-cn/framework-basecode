<?php
//powered by kevin
namespace Marmot\Basecode\Classes;

use Marmot\Interfaces\ICommandHandlerFactory;
use Marmot\Interfaces\ICommand;
use Marmot\Interfaces\ICommandHandler;
use Marmot\Interfaces\INull;

use Marmot\Core;

/**
 * 命令总线
 * 1. 构造总线传递 commandHandlerFactory
 * 2. 发送命令,通过 commandHandlerFactory 获取到适当的 commandHandler
 * 3. 执行 commandHandler
 */
abstract class CommandBus
{
    private $commandHandlerFactory;

    public function __construct(ICommandHandlerFactory $commandHandlerFactory)
    {
        $this->commandHandlerFactory = $commandHandlerFactory;
    }

    public function __destruct()
    {
        unset($this->commandHandlerFactory);
    }

    protected function getCommandHandlerFactory() : ICommandHandlerFactory
    {
        return $this->commandHandlerFactory;
    }

    public function send(ICommand $command)
    {
        $handler = $this->getCommandHandlerFactory()->getHandler($command);
        //这里为了没有必要开启事务
        if ($handler instanceof INull) {
            Core::setLastError(COMMAND_HANDLER_NOT_EXIST);
            return false;
        }
        
        return $this->sendAction($handler, $command);
    }

    abstract protected function sendAction(ICommandHandler $handler, ICommand $command) : bool;
}
