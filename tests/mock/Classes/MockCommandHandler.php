<?php
//powered by kevin
namespace Marmot\Basecode\Classes;

use Marmot\Interfaces\ICommandHandler;
use Marmot\Interfaces\ICommand;

class MockCommandHandler implements ICommandHandler
{
    public function execute(ICommand $command)
    {
        unset($command);
        return true;
    }
}
