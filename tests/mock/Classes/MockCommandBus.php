<?php
//powered by kevin
namespace Marmot\Basecode\Classes;

use Marmot\Interfaces\ICommandHandlerFactory;
use Marmot\Interfaces\ICommand;
use Marmot\Interfaces\ICommandHandler;

class MockCommandBus extends CommandBus
{
    public function getCommandHandlerFactory() : ICommandHandlerFactory
    {
        return parent::getCommandHandlerFactory();
    }

    protected function sendAction(ICommandHandler $handler, ICommand $command) : bool
    {
        unset($handler);
        unset($command);
        return true;
    }
}
