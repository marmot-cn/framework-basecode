<?php
//powered by kevin
namespace Marmot\Basecode\Classes;

use Marmot\Interfaces\ICommandHandlerFactory;
use Marmot\Interfaces\ICommand;
use Marmot\Interfaces\ICommandHandler;

class MockCommandHandlerFactory implements ICommandHandlerFactory
{
    public function getHandler(ICommand $command) : ICommandHandler
    {
        unset($command);
        return new MockCommandHandler();
    }
}
