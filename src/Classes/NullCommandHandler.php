<?php
namespace Marmot\Basecode\Classes;

use Marmot\Interfaces\ICommandHandler;
use Marmot\Interfaces\ICommand;
use Marmot\Interfaces\INull;

use Marmot\Core;

class NullCommandHandler implements ICommandHandler, INull
{
    private static $instance;
    
    private function __construct()
    {
    }

    public static function &getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    private function commandHandlerNotExist() : bool
    {
        Core::setLastError(COMMAND_HANDLER_NOT_EXIST);
        return false;
    }

    public function execute(ICommand $command)
    {
        return $this->commandHandlerNotExist();
    }
}
